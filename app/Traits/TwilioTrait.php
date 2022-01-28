<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Contracts\Validation\Validator as ValidationErrors;
use Twilio\Rest\Client;


trait TwilioTrait
{
    /**
     * sendSMS
     *
     * @param  mixed $receiverNumber
     * @param  mixed $otp
     * @return void
     */
    public function sendSMS($receiverNumber, $otp)
    {
        try {
            $account_sid = getenv("TWILLIO_ACCOUNT_SID");
            $auth_token = getenv("TWILLIO_AUTHTOKEN");
            $twilio_number = getenv("TWILIO_FROM");

            $client = new Client($account_sid, $auth_token);
            $client->messages->create($receiverNumber, [
                'from' => $twilio_number,
                'body' => $otp . " is the otp to login in smirk acount."
            ]);
        } catch (Exception $e) {
            dd("Error: " . $e->getMessage());
        }
    }
}