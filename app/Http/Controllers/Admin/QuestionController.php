<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    /**
     * Store a new question
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'opt_r' => 'required|string',
            'opt_i' => 'required|string',
            'opt_a' => 'required|string',
            'opt_s' => 'required|string',
            'opt_e' => 'required|string',
            'opt_c' => 'required|string',
        ]);

        Question::create($validated);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Soal RIASEC berhasil ditambahkan!')
            ->with('tab', 'publish');
    }

    /**
     * Toggle question status (active/draft)
     */
    public function toggleStatus($id)
    {
        $question = Question::findOrFail($id);

        // Toggle status
        $question->is_active = !$question->is_active;
        $question->save();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Status soal berhasil diubah!')
            ->with('tab', 'publish');
    }
}