<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_text', 'opt_r', 'opt_i', 'opt_a', 'opt_s', 'opt_e', 'opt_c', 'is_active'
    ];
}