<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Question;
use App\Jobs\WipeScoresFromSyncJob;

class WelcomeSEEventController extends Controller
{
    public function index() {
        $games = Game::all();
        return view('welcome-se-event.index', compact('games'));
    }

    public function wipe(Game $game) {
        WipeScoresFromSyncJob::dispatch($game);
        return redirect()->back()->with('status', 'Wipe dispatched.');
    }

    public function game(Game $game) {
        return view('welcome-se-event.game', compact('game'));
    }

    public function questions(Game $game) {
        $questions = $game->questions()->select(['id', 'quick_title'])->get();
        return view('welcome-se-event.questions', compact('game', 'questions'));
    }

    public function question(Game $game, Question $question) {
        $game->setActiveQuestion($question);
        return view('welcome-se-event.question', compact('game', 'question'));
    }
}
