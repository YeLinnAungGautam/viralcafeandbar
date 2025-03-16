<?php

namespace App\Class;

use App\Helpers\ResponseJson;
use App\Jobs\SendSMSJob;
use App\Mail\VerificationMail;
use App\Models\Setting;
use App\Models\Template;
use App\Notifications\SMSPush;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class SendOtpCode
{
    /**
     * Create a new class instance.
     */
    private $code;

    public function __construct(public Request $request)
    {
        $this->code = random_int(1111, 9999);
        // $this->code = 1234;
    }

    public function validatePhone()
    {
        if (is_numeric($this->request->input('credential'))) {
            if ($this->request->is_oversea) {
                throw new Exception('You need to create account with email.');
            }
            SendSMSJob::dispatch($this->code, $this->request->input('credential'));
        }
    }

    public function validateEmail()
    {
        if (!is_numeric($this->request->input('credential'))) {
            Mail::to($this->request->input('credential'))->send(new VerificationMail($this->code));
        }
    }

    public function send()
    {
        $this->validatePhone();
        $this->validateEmail();
        Cache::put('verify_crendential' . $this->request->input('credential'), $this->code, 60 * 3); //3 min

        return ResponseJson::success($this->request->all(), 'OTP code sent successfully.');
    }
}
