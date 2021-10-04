<?php

namespace App\Handlers;

use Twilio\Rest\Client as TwilioClient;
use App\Models\Message;
use App\Models\Person;

class BaseHandler
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new TwilioClient(
            config('services.twilio.sid'), 
            config('services.twilio.token')
        );
    }

    public function send($to, $from, $body) {
        if ($person->optedInTo($program) && $person->notOptedOut()) {
            $this->twilio->
        }
        else {
            $errorData = 
            Log::error()
        }
    }
}
