<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Message;
use App\Jobs\ProcessMessageJob;

use Log;

class GameshowController extends Controller
{
    private $messageHandler = 'GameshowHandler';

    public function index(Request $request)
    {
        try {
            $validatedData = $request->validate($this->validations());    
        }
        catch (\Exception $e) {
            \Log::error($e);
            return response(null, 400);
        }
        
        if ($this->existingMessage($request)) {
            Log::error('Message already exists: ' . $request->fullUrl);
            return response('Message record already exists.', 409);
        }

        $message = $this->newMessage($request);

        ProcessMessageJob::dispatch($message, $this->messageHandler);

        return response(null, 204);
    }

    private function existingMessage($request) {
        return Message::where('message_sid', $request->get('MessageSid'))->exists();
    }

    private function validations() {
        return [
            'MessageSid' => 'required',
            'From' => 'required',
            'To' => 'required',
            'Body' => 'required'
        ];
    }

    private function newMessage($request) {
        $message = new Message;
        $message->message_sid = $request->get('MessageSid');
        $message->from = $request->get('From');
        $message->to = $request->get('To');
        $message->body = $request->get('Body');
        $message->inbound = true;
        $message->save();
        return $message;
    }
}
