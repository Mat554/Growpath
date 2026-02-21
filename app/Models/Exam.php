<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'target_class', 'duration_minutes', 'exam_date'];

    // Relasi Many-to-Many ke model Question
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'exam_question');
    }
}