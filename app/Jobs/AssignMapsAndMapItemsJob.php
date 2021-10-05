<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Traits\StoresTwilioRequests;

use App\Models\Game;
use App\Models\Question;
use Twilio\Rest\Client as TwilioClient;
use Twilio\Exceptions\TwilioException;

class AssignMapsAndMapItemsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,StoresTwilioRequests;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->twilio = new TwilioClient(
            config('services.twilio.sid'), 
            config('services.twilio.token')
        );
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Game::whereNull('sync_map')->get()->each(function ($game) {
            $mapName = 'game-'.$game->id;
            $this->createMap($mapName);
            $this->createMapItem($mapName, $mapName.'-players');
            $game->sync_map = $mapName;
            $game->save();    
        });

        Question::whereNull('sync_map_item')->get()->each(function ($question) {
            $mapName = 'game-'.$question->game_id;
            $mapItemName = 'game-'.$question->game_id.'-question-'.$question->id;

            $this->createMapItem($mapName, $mapItemName);

            $question->sync_map_item = $mapItemName;
            $question->save();
        });
    }

    private function createMap($mapName) {
        try {
            $this->twilio->sync->v1->services(config('services.twilio.sync_service'))
            ->syncMaps
            ->create(["uniqueName" => $mapName]);
        }
        catch (TwilioException $e) {
            \Log::error('SyncMap Error: ' . $e->getCode());
        }
        $this->storeTwilioRequest($this->twilio->getHttpClient()->lastResponse->getHeaders(), 'assignmapsandmapitemsjob-map');
    }

    private function createMapItem($mapName, $mapItemName) {
        try {
            $this->twilio->sync->v1->services(config('services.twilio.sync_service'))
            ->syncMaps($mapName)
            ->syncMapItems
            ->create($mapItemName, ['status' => 'new']);
        }
        catch (TwilioException $e) {
            \Log::error('SyncMapItem Error: ' . $e->getCode());
        }
        
        $this->storeTwilioRequest($this->twilio->getHttpClient()->lastResponse->getHeaders(), 'assignmapsandmapitemsjob-mapitem');
    }
}
