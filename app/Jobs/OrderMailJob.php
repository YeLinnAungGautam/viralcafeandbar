<?php

namespace App\Jobs;

use App\Models\Order;
use App\Mail\OrderMail;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use function PHPUnit\Framework\isEmpty;

class OrderMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    public function __construct(public $customer, public Order $order, public $name = 'confirmation') {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $setting = Setting::pluck('value', 'key')->toArray();

        $ccEmails = [];
        if (isset($setting['main_email'])) {
            $ccEmails = explode(',', $setting['main_email']);
        }

        $pdf = Pdf::loadView('pdf.order.invoice', [
            'order'   => $this->order,
            'setting' => $setting,
        ]);

        $pdfContent = $pdf->output();

        Mail::to($this->customer->email)
            ->cc($ccEmails)
            ->send(new OrderMail($this->order, $this->name, $pdfContent, $setting));
    }
}
