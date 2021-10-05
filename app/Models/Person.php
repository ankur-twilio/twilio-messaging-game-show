<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    public $table = 'people';

    protected $casts = [
        'state_data' => 'array',
    ];

    /**
     *
     * Relationships
     *
     */
    
    public function optIns()
    {
        return $this->hasMany(OptIn::class, 'person_id');
    }

    public function optOuts()
    {
        return $this->hasMany(OptOut::class, 'person_id');
    }

    /**
     *
     * Functions
     *
     */

    public function globalOptOut($description, $messageId = null) {
        $optOut = new OptOut;
        $optOut->source_description = $description;
        $optOut->message_id = $messageId;
        $optOut->person_id = $this->id;
        $optOut->save();

        OptIn::where('person_id', $this->id)
            ->update(array('opt_out_id' => $optOut->id));

        return true;
    }

    public function optIn($description, $messageId = null, $program = null) {
        $optIn = new OptIn;
        $optIn->person_id = $this->id;
        $optIn->source_description = $description;
        $optIn->message_id = $messageId;
        $optIn->program = $program;
        $optIn->save();

        OptOut::where('person_id', $this->id)
            ->where('program', $program)
            ->update(array('opt_in_id' => $optIn->id));

        return true;
    }

    public function setState($handler, $array) {
        $this->state_handler = $handler;
        $this->state_data = $array;
        return $this->save();
    }
    
    public function optedInTo($program = null) {
        if ($program) {
            \Log::info('here');
            return $this->optIns()
            ->where('program', $program)
            ->whereNull('opt_out_id')
            ->exists();
        }

        return !$this->optOuts()
            ->whereNull('opt_in_id')
            ->whereNull('program')
            ->exists();
    }
}
