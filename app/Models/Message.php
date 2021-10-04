<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\GlobalKeywordTrait;

class Message extends Model
{
    use HasFactory, GlobalKeywordTrait;

    public function getKeywordMethod() {
        return $this->matchGloalKeyword($this->body);
    }
}
