<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;
use App\Models\Person;

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
        $messageHandler = $handlerPrefix . 'FallbackHandler';
        $method = 'process'; 

        // Check the text to match preset global keywords (like STOP)
        if ($globalKeyword = $this->message->getKeywordMethod()) {
            $messageHandler = $handlerPrefix . 'GlobalKeywordHandler';
        }

        // Check if message's person has an expected state
        else if ($stateHandler = $person->state_handler) {
            $messageHandler = $handlerPrefix . $stateHandler;
            $method = $person->state_data['method'] ?? 'stateFallback';
        }

        // Use a provided default handler
        else if ($this->messageHandler) {
            $messageHandler = $handlerPrefix . $this->messageHandler;
        }

        return (new $messageHandler($message, $person))->$method();
    }

    private function identifyPerson() {
        if (!$person = Person::where('identity', $this->message->from)->first()) {
            $person = new Person;
            $person->identity = $this->message->from;
            $person->save();
        }
        return $person;
    }
}
