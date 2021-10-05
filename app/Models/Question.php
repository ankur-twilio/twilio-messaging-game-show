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

    public $fillable = ['active'];

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
        if ($this->type == 'free_response') {
            $answersArray = $this->answerRecords()
                ->select('answer', 'created_at')
                ->orderBy('created_at', 'ASC')
                ->get()
                ->toArray();
        }
        else {
            $answersArray = $this->answerRecords()
                ->select(DB::raw('count(*) as answer_count, answer'))
                ->groupBy('answer')
                ->get()
                ->toArray();            
            }
             
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
