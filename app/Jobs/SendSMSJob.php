<?php

namespace App\Jobs;

use App\Models\Setting;
use App\Models\Template;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendSMSJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $code, public $phone)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->sendSMS();
    }

    public function sendSMS()
    {
        $content = config('mail_template.otp_code');
        $body    = str_replace('{code}', $this->code, $content);

        $resourese = [
            'to'      => $this->phone,
            'sender'  => config('sms.smspoh.sender_id'),
            'message' => $body,
        ];

        $apiKey = config('sms.smspoh.api_key');

        try {
            $client  = new Client();
            $headers = [
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
                'Authorization' => "Bearer $apiKey",
            ];

            $body = json_encode($resourese);

            $request = new Request('POST', config('sms.smspoh.url'), $headers, $body);

            try {
                $response = $client->send($request);

                Log::build([
                    'driver' => 'single',
                    'path'   => storage_path('logs/smspoh.log'),
                ])->info("Error: {$response->getBody()}");

            } catch (\Exception $e) {

                Log::build([
                    'driver' => 'single',
                    'path'   => storage_path('logs/smspoh.log'),
                ])->info("Error: {$e->getMessage()}");

            }

        } catch (\Exception $e) {
            throw $e;
        }
    }
}
