<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game;
use App\Models\Question;

use DB;

use App\Jobs\AssignMapsAndMapItemsJob;

class NewGameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('games')->update(['active' => 0]);


        DB::table('games')->truncate();
        DB::table('questions')->truncate();


        $game = new Game;
        $game->name = "Intro survey & meet session!";
        $game->active = true;
        $game->save();

        $question = new Question;
        $question->game_id = $game->id;
        $question->quick_title = 'Q1: Complex Deals';
        $question->title = 'On a scale of 1-5 (1 novice / 5 expert), how comfortable do you feel solutioning complex messaging deals?';
        $question->options = [
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
        ];
        $question->type = '1_to_5';
        $question->active = false;
        $question->default_live_answer_display = false;
        $question->allow_answer_change = true;
        $question->use_options = true;
        $question->allowed_answer_count = 1;
        $question->save();

        $question = new Question;
        $question->game_id = $game->id;
        $question->quick_title = 'Q2: Intl Messaging';
        $question->title = 'On a scale of 1-5, how comfortable are you with international messaging recommendations?';
        
        $question->options = [
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
        ];

        $question->type = '1_to_5';
        $question->active = false;
        $question->default_live_answer_display = false;
        $question->allow_answer_change = true;
        $question->use_options = true;
        $question->allowed_answer_count = 1;
        $question->save();

        $question = new Question;
        $question->game_id = $game->id;
        $question->quick_title = 'Q3: Senders';
        $question->title = 'On a scale of 1-5, how well do you understand Messaging Sender Types?';
        
        $question->options = [
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
        ];

        $question->type = '1_to_5';
        $question->active = false;
        $question->default_live_answer_display = false;
        $question->allow_answer_change = true;
        $question->use_options = true;
        $question->allowed_answer_count = 1;
        $question->save();

        $question = new Question;
        $question->game_id = $game->id;
        $question->quick_title = 'Q4: Throughput';
        $question->title = 'On a scale of 1-5, how comfortable are you with throughput discussions?';
        
        $question->options = [
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
        ];

        $question->type = '1_to_5';
        $question->active = false;
        $question->default_live_answer_display = false;
        $question->allow_answer_change = true;
        $question->use_options = true;
        $question->allowed_answer_count = 1;
        $question->save();

        $question = new Question;
        $question->game_id = $game->id;
        $question->quick_title = 'Q5: Compliance';
        $question->title = 'On a scale of 1-5, how comfortable are you with advising on messsaging best practices such as opt-in and opt-out?';
        
        $question->options = [
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
        ];

        $question->type = '1_to_5';
        $question->active = false;
        $question->default_live_answer_display = false;
        $question->allow_answer_change = true;
        $question->use_options = true;
        $question->allowed_answer_count = 1;
        $question->save();

        $question = new Question;
        $question->game_id = $game->id;
        $question->quick_title = 'Q6: Preferences';
        $question->title = 'Choose your favorite ways to learn (text in up to two times).';
        $question->options = [
            'A' => 'Open office hours',
            'B' => 'Public Slack',
            'C' => 'Events (e.g. lunch & learn)',
            'D' => 'Docs (Wiki, Blog)',
            'E' => 'Recorded videos',
        ];
        $question->type = 'multiple_choice';
        $question->active = false;
        $question->default_live_answer_display = false;
        $question->allow_answer_change = true;
        $question->use_options = true;
        $question->allowed_answer_count = 2;
        $question->save();

        $question = new Question;
        $question->game_id = $game->id;
        $question->quick_title = 'Q7: Open Ended';
        $question->title = 'Free response: Name one way you’d like to see the Messaging Specialists help you. Send in up to 100 texts.';
        $question->type = 'free_response';
        $question->active = false;
        $question->default_live_answer_display = false;
        $question->allow_answer_change = false;
        $question->use_options = false;
        $question->allowed_answer_count = 100;
        $question->save();

        AssignMapsAndMapItemsJob::dispatchNow();
    }
}
