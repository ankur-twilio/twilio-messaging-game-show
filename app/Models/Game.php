<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DB;

class Game extends Model
{
    use HasFactory;
    public $fillable = ['active'];

    public function answerRecords() {
        return $this->hasMany(AnswerRecord::class);
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function players() {
        return $this->hasMany(Player::class);
    }

    public function activeQuestion() {
        return $this->questions()->where('active', true)->first();
    }

    public function setActiveQuestion(Question $question) {
        \Log::info($question);
        $question->active = 1;
        $question->save();
        \Log::info($question);
        Question::where('game_id', $this->id)
            ->where('id', '!=', $question->id)
            ->update(['active' => 0]);
        \Log::info($this->questions);
    }

}
