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
        return Message::where('message_sid', $request->get('message_sid'))->exists();
    }

    private function validations() {
        return [
            'message_sid' => 'required',
            'from' => 'required',
            'to' => 'required',
            'body' => 'required'
        ];
    }

    private function newMessage($request) {
        $message = new Message;
        $message->message_sid = $request->get('message_sid');
        $message->from = $request->get('from');
        $message->to = $request->get('to');
        $message->body = $request->get('body');
        $message->inbound = true;
        $message->save();
        return $message;
    }
}
