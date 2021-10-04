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

    public function activeQuestion() {
        return $this->questions()->where('active', true)->first();
    }

}
