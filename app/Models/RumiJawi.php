<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RumiJawi extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'rumi',
        'jawi'
    ];
}
