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

class UpdateSyncPlayersJob implements ShouldQueue, ShouldBeUnique
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
        \Log::info('Sync players update for ' . $this->game->id . ' dispatched');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $players = $this->game->players()->whereNotNull('friendly_name')->pluck('friendly_name')->toArray();
        return $this->updateSync($players);
    }

    private function updateSync($players) {
        try {
            $mapItemName = $this->game->sync_map . '-players';

            $this->twilio->sync->v1->services(config('services.twilio.sync_service'))
                ->syncMaps($this->game->sync_map)
                ->syncMapItems($mapItemName)
                ->update(["data" => ['players' => $players]]);

            $this->storeTwilioRequest($this->twilio->getHttpClient()->lastResponse->getHeaders(), 'updatesyncplayersjob-handler');

            \Log::info('Update for ' . $mapItemName . ' succeeded');
        }
        catch (TwilioException $e) {
            Log::error($e);
        }
    }
}
