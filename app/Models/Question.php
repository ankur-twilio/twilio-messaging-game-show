<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DB;

class Question extends Model
{
    use HasFactory;

    protected $casts = [
        'options' => 'array',
    ];

    /**
     *
     * Relationships
     *
     */

    public function answerRecords() {
        return $this->hasMany(AnswerRecord::class);
    }
    
    /**
     *
     * Functions
     *
     */

    public function getFormattedAnswerArray() {
        $answersArray = $this->answerRecords()
             ->select(DB::raw('count(*) as answer_count, answer'))
             ->groupBy('answer')
             ->get()
             ->toArray();
             
        $count = $this->answerRecords()->count();
        
        return [
            'total_count' => $count,
            'answers' => $answersArray
        ];
    }

    public function playerAnswerLimitCheck($playerId) {
        return $this->allowed_answer_count - $this->answerRecords()->where('player_id', $playerId)->count();
    }
}
