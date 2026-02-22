<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    // WAJIB ADA AGAR BISA DISIMPAN
    protected $fillable = ['title', 'target_class', 'duration_minutes', 'exam_date'];

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'exam_question');
    }
}