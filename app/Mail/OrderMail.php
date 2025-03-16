<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Setting;
use App\Models\Template;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Order $order, public $name, public $pdfContent, public $setting) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $status = $this->order->order_status == 'cancel' ? 'cancelled' : $this->order->order_status;
        return new Envelope(
            subject: "Your order {$this->order->order_no} is {$status}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $setting             = Setting::pluck('value', 'key')->toArray();
        $template            = Template::pluck('content', 'id')->toArray();
        $key                 = "order_{$this->order->order_status}_template";
        $order_template_id   = $setting[$key] ?? "";
        $footer_template_id  = $setting['order_footer_template'] ?? "";
        $terms_template_id   = $setting['terms_and_conditions_template'] ?? "";
        $body                = isset($template[$order_template_id]) ? $template[$order_template_id] : config("mail_template.$key");
        $footer              = isset($template[$footer_template_id]) ? $template[$footer_template_id] : "";
        $terms_and_condtions = isset($template[$terms_template_id]) ? $template[$footer_template_id] : "";


        $values = [
            '{customer}'   => $this->order->orderCustomer->first_name,
            '{order_code}' => $this->order->order_no,
            '{date}'       => $this->order->created_at->format('d M Y'),
        ];

        foreach ($values as $key => $value) {
            $body = str_replace($key, $value, $body);
        }

        return new Content(
            view: 'emails.order.' . $this->order->order_status,
            with: [
                'order'                => $this->order,
                'setting'              => $this->setting,
                'body'                 => $body,
                'footer'               => $footer,
                'terms_and_conditions' => $terms_and_condtions,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [Attachment::fromData(fn() => $this->pdfContent, 'invoice.pdf')->withMime('application/pdf')];
    }
}
