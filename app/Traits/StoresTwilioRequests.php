<?php
namespace App\Traits;

use App\Models\TwilioRequest;

use Log;
trait StoresTwilioRequests {
    protected function storeTwilioRequest($headers, $tag = null) {
        try {
            $request = new TwilioRequest;
            $request->request_id = $headers["Twilio-Request-Id"] ?? null;
            $request->concurrent_requests = $headers["Twilio-Concurrent-Requests"] ?? null;
            $request->request_duration = $headers["Twilio-Request-Duration"] ?? null;
            $request->tag = $tag;
            
            return $request->save();
        }
        catch (\Exception $e) {
            Log::error('Error while saving Twilio Request!');
            Log::error($e->getMessage());
        }

    }
}

?>