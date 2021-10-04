<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptIn extends Model
{
    use HasFactory;

    public $fillable = ['person_id', 'program', 'description', 'opt_out_id'];
}
