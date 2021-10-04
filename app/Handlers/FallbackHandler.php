<?php

namespace App\Handlers;

use Log;

class FallbackHandler extends BaseHandler
{
    public function process()
    {
        $errorData = [
            'description' => 'Fallback handler invoked.',
            'to' => $this->message->to,
            'from' => $this->message->from,
            'program' => $this->message->program,
            'message' => $this->message->id
        ];

        Log::error($errorData);

        return $this->newSend(
            $this->message->to,
            $this->message->from,
            'Sorry, we cannot process this message at this time.'
        );
    }
}
