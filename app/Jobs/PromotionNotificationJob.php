<?php

namespace App\Jobs;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use App\Services\FcmNotifyService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Promotion as NotificationsPromotion;


class PromotionNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $request) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $customers = Customer::with('fcmToken')
            ->cursor();

        foreach ($customers as $customer) {
            $customer->notify(new NotificationsPromotion($this->request));
        }
    }
}
