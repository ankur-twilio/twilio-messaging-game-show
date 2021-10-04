<?php

namespace App\Handlers;

use Twilio\Rest\Client as TwilioClient;
use Twilio\Exceptions\TwilioException;
use App\Models\Message;
use App\Models\Person;
use Log;

use App\Traits\StoresTwilioRequests;

class BaseHandler
{
    use StoresTwilioRequests;
    protected $twilio;
    protected $message;
    protected $person;

    public function __construct($message, $person)
    {
        $this->message = $message;
        $this->person = $person;
        $this->twilio = new TwilioClient(
            config('services.twilio.sid'), 
            config('services.twilio.token')
        );
    }

    protected function newSend($from, $to, $body, $program = null) {
        $newMessage = $this->newMessage($to, $from, $body);

        if ($this->person->optedInTo($program)) {
            $this->executeSend($newMessage);
        }
        else {
            $errorData = [
                'description' => 'Attempted send to unsubscribed person.',
                'to' => $to,
                'from' => $from,
                'program' => $program,
            ];
            Log::error($errorData);
            return false;
        }
    }

    private function newMessage($to, $from, $body) {
        $message = new Message;
        $message->from = $from;
        $message->to = $to;
        $message->body = $body;
        $message->inbound = false;
        return $message;
    }

    private function executeSend($message) {
        try {
            $response = $this->twilio->messages->create(
                $message->to, 
                array(
                    'from' => $message->from,
                    'body' => $message->body
                )
            );

            $message->message_sid = $response->sid;
            $message->save();
        }
        catch (TwilioException $e) {
            $message->status = $e->getCode();
            $message->save();

            $errorData = [
                'to' => $message->to,
                'from' => $message->from,
                'message' => $message->id,
                'code' => $e->getCode()
            ];

            Log::error($errorData);
        }
        
        $this->storeTwilioRequest($this->twilio->getHttpClient()->lastResponse->getHeaders(), 'basehandler-executesend');
    }

}
