<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Message;

class ProcessMessageJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $message; 
    private $messageHandler; 
    public $tries = 1;

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId()
    {
        return $this->message->sid;
    }

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Message $message, $messageHandler = null)
    {
        $this->message = $message;
        $this->messageHandler = $messageHandler;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $message = $this->message;
        $person = $this->identifyPerson();
        $handlerPrefix = '\\App\\Handlers\\';
        $messageHandler = $messageHandler . 'FallbackHandler';

        // Check the text to match preset global keywords (like STOP)
        if ($globalKeyword = $this->message->matchesGlobalKeyword()) {
            $messageHandler = $messageHandler . 'GlobalKeywordHandler';
        }

        // Check if message's person has an expected state
        else if ($stateHandler = $person->state_handler) {
            $messageHandler = $messageHandler . $stateHandler;
        }

        // Use a provided default handler
        else if ($this->messageHandler) {
            $messageHandler = $messageHandler . $messageHandler;
        }

        return new $messageHandler($message, $person);
    }
}
