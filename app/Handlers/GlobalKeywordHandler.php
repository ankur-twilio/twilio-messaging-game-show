<?php

namespace App\Handlers;

use App\Models\Message;
use App\Models\OptIn;
use Log;

class GlobalKeywordHandler extends BaseHandler
{
    public function process()
    {
        $method = $this->message->getKeywordMethod();
        \Log::info('Keyword Handler: ' . $method);
        \Log::info('Person ID: ' . $this->person->id);
        return $this->$method();
    }

    private function stop() {
        return $this->person->globalOptOut(
            'Texted in opt out',
            $this->message->id
        );

        // Note: Twilio AOO to handle response... 
    }

    private function start() {
        $this->person->optIn(
            'User texted in an opt in.',
            $this->message->id
        );

        // Note: Twilio AOO to handle response... 
    }

    private function subscribe() {
        $this->person->optIn(
            'User texted in an opt in.',
            $this->message->id,
            'MARKETING'
        );

        return $this->newSend(
            $this->message->to,
            $this->message->from,
            'You\'re subscribed to SE Messaging Specialists marketing texts. STOP2quit. HELP4info. Msg&data rates may apply. Msg freq vary. Terms at https://twilio.com.',
            'MARKETING'
        );
    }
}
