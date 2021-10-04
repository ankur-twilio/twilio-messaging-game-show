<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerRecord extends Model
{
    use HasFactory;

    public $fillable = ['answer', 'message_id'];

    public function player() {
        return $this->belongsTo(Player::class);
    }

    public function game() {
        return $this->belongsTo(Game::class);
    }

    public function question() {
        return $this->belongsTo(Question::class);
    }
}
