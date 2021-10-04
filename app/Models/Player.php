<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    public function answerRecords() {
        return $this->hasMany(AnswerRecord::class);
    }

    public function answer(Question $question, Message $message, $cleanAnswer) {
        $remaining = $question->playerAnswerLimitCheck($this->id);
        if ($remaining > 0) {
            $answer = new AnswerRecord;
            $answer->player_id = $this->id;
            $answer->question_id = $question->id;
            $answer->game_id = $question->game_id;
            $answer->message_id = $message->id;
            $answer->answer = $cleanAnswer;
            $answer->save();   
            return ['type' => 'ADDED', 'remaining' => $remaining - 1];
        }
        else if ($question->allow_answer_change == true) 
        {
            AnswerRecord::where('question_id', $question->id)
                ->where('player_id', $this->id)
                ->limit(1)
                ->update([
                    'answer' => $cleanAnswer,
                    'message_id' => $message->id
                ]);
            return ['type' => 'UPDATED', 'remaining' => $remaining - 1];
        }
        else {
            return ['type' => 'LIMITED', 'remaining' => $remaining - 1];
        }
    }
}
