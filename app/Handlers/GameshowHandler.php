<?php

namespace App\Handlers;

use App\Models\Message;
use App\Models\OptIn;
use App\Models\Person;
use App\Models\Player;
use App\Models\Game;
use App\Exceptions\NotOptedInException;
use Log;

use App\Jobs\UpdateSyncJob;
use App\Jobs\UpdateSyncPlayersJob;

class GameshowHandler extends BaseHandler {

	private $player; 
	private $game; 

	/**
	 *
	 * PUBLIC METHODS
	 *
	 */

	public function __construct($message, $person) {
		parent::__construct($message, $person);

		if ( ! $person->optedInTo(null)) {
			$error = [
				'person' => $person->id,
				'description' => 'Exiting. User not opted in.'
			];
			throw new NotOptedInException(implode(',',$error));
		}

		$this->game = Game::where('active', 1)->first();
		$this->player = Player::where('person_id', $this->person->id)
			->where('game_id', $this->game->id)
			->first();
	}

	public function process() {
		if (!$this->player) {
			return $this->idPlayer();
		}

		return $this->answeringQuestions();
	}
	
	public function answeringName() {
		if (!$this->player) {
			return $this->idPlayer();
		}

		$this->player->friendly_name = trim($this->message->body);
		$this->player->save();

		$this->person->setState(
			'GameshowHandler', 
			['method' => 'answeringQuestions']
		);
		
		UpdateSyncPlayersJob::dispatch($this->game);

        return $this->newSend(
            $this->message->to,
            $this->message->from,
            'Thanks, ' . $this->player->friendly_name . '! Please watch the screen for more instructions!'
        );
	}

	public function answeringQuestions() {
		$cleanMsg = trim($this->message->body);

		if (!$question = $this->game->activeQuestion()) {
			return $this->noActiveQuestionsResponse();	
		}

		if ($question->use_options) {
			$cleanMsg = strtoupper($cleanMsg);
			if (!array_key_exists($cleanMsg, $question->options)) {
				return $this->noCorrectOptionsResponse();	
			}
		}

		if ($response = $this->player->answer($question, $this->message, $cleanMsg)) {

			Log::info($response);

			if ($response['type'] === 'UPDATED') {
				UpdateSyncJob::dispatch($question);
		        return $this->newSend(
		            $this->message->to,
		            $this->message->from,
		            'Your answer from before for this question was updated!'
		        );
			}
			if ($response['type'] === 'ADDED') {
				UpdateSyncJob::dispatch($question);
				$addon = (intval($response['remaining']) > 0 ) ? ' You can answer again!' : null;

		        return $this->newSend(
		            $this->message->to,
		            $this->message->from,
		            'Your answer for this question was saved!' . $addon
		        );
			}
			if ($response['type'] === 'LIMITED') {
		        return $this->newSend(
		            $this->message->to,
		            $this->message->from,
		            'You cannot answer this question again.'
		        );
			}
		}
	}


	/**
	 *
	 * PRIVATE METHODS
	 *
	 */
	
	private function noCorrectOptionsResponse() {	
        return $this->newSend(
            $this->message->to,
            $this->message->from,
            'Sorry that answer doesn\'t match any of the options. Try again.'
        );
	}

	private function noActiveQuestionsResponse() {	
        return $this->newSend(
            $this->message->to,
            $this->message->from,
            'Hey ' . $this->player->friendly_name . ', please hang tight. There are no active questions in play!'
        );
	}

	private function idPlayer()
	{
		$this->person->setState(
			'GameshowHandler', 
			['method' => 'answeringName']
		);

		$player = new Player;
		$player->person_id = $this->person->id;
		$player->game_id = $this->game->id;
		$player->save();

        return $this->newSend(
            $this->message->to,
            $this->message->from,
            'Welcome welcome! Please enter your full-name/username/what-have-you to ID yourself for the session!'
        );
	}
}

?>