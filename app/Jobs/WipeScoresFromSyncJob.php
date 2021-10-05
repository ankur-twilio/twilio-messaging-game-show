<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use romanzipp\QueueMonitor\Traits\IsMonitored;

use App\Traits\StoresTwilioRequests;

use Twilio\Rest\Client as TwilioClient;
use Twilio\Exceptions\TwilioException;

use App\Models\Game;

use Log;

class WipeScoresFromSyncJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, StoresTwilioRequests;
    use IsMonitored; 

    private $game;
    private $twilio;
    public $tries = 1;

    public function uniqueId()
    {
        return $this->game->id;
    }

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Game $game)
    {
        $this->game = $game;
        $this->twilio = new TwilioClient(
            config('services.twilio.sid'), 
            config('services.twilio.token')
        );
        \Log::info('Sync wipe for ' . $this->game->id . ' dispatched');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($this->game->questions as $question) {
            $mapItemName = $this->game->sync_map . '-question-' . $question->id;
            $this->clearSyncItem($mapItemName);
        }
        $mapItemName = $this->game->sync_map . '-players';
        $this->clearSyncItem($mapItemName);
        return true;
    }

    private function clearSyncItem($mapItemName) {
        try {
            $this->twilio->sync->v1->services(config('services.twilio.sync_service'))
                ->syncMaps($this->game->sync_map)
                ->syncMapItems($mapItemName)
                ->update(["data" => [
                    'status' => 'new'
                ]]);

            $this->storeTwilioRequest($this->twilio->getHttpClient()->lastResponse->getHeaders(), 'updatesyncjob-handler');

            \Log::info('Wipe for ' . $mapItemName . ' succeeded');
        }
        catch (TwilioException $e) {
            Log::error($e);
        }
    }
}
