<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ExamResult extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'exam_id', 'score_r', 'score_i', 'score_a', 'score_s', 'score_e', 'score_c', 'dominant_code'];
}