<?php
namespace App\Traits;

use App\Models\TwilioRequest;

trait StoresTwilioRequests {
    protected function storeTwilioRequest($headers, $tag = null) {
        $request = new TwilioRequest;
        $request->request_id = $headers["Twilio-Request-Id"] ?? null;
        $request->concurrent_requests = $headers["Twilio-Concurrent-Requests"] ?? null;
        $request->request_duration = $headers["Twilio-Request-Duration"] ?? null;
        $request->tag = $tag;
        
        return $request->save();
    }
}

?>