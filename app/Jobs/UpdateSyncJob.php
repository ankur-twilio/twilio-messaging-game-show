<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Traits\StoresTwilioRequests;

use Twilio\Rest\Client as TwilioClient;
use Twilio\Exceptions\TwilioException;

use App\Models\Question;

use Log;

class UpdateSyncJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, StoresTwilioRequests;

    private $question;
    private $twilio;
    public $tries = 1;

    public function uniqueId()
    {
        return $this->question->id;
    }

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Question $question)
    {
        $this->question = $question;
        $this->twilio = new TwilioClient(
            config('services.twilio.sid'), 
            config('services.twilio.token')
        );
        \Log::info('Update for ' . $this->question->id . ' dispatched');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mapName = 'game-'.$this->question->game_id;
        $mapItemName = 'game-'.$this->question->game_id.'-question-'.$this->question->id;
        $data = $this->question->getFormattedAnswerArray();
        return $this->updateSyncMap($mapName, $mapItemName, $data);
    }

    private function updateSyncMap($mapName, $mapItemName, $data) {
        try {
            $this->twilio->sync->v1->services(config('services.twilio.sync_service'))
                ->syncMaps($mapName)
                ->syncMapItems($mapItemName)
                ->update(["data" => $data]);
            $this->storeTwilioRequest($this->twilio->getHttpClient()->lastResponse->getHeaders(), 'updatesyncjob-handler');
            \Log::info('Update for ' . $this->question->id . ' succeeded');
        }
        catch (TwilioException $e) {
            Log::error($e);
        }
    }
}
