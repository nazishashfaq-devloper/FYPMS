<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Evaluation;

class EvaluationController extends Controller
{
    public function history()
    {
        $userId = auth()->id();

        $team = Team::where('leader_id', $userId)
            ->orWhereHas('members', function ($q) use ($userId) {
                $q->where('student_id', $userId)
                  ->where('status', 'accepted');
            })
            ->first();

        if (!$team) {
            return view('student.evaluation_history', [
                'evaluations' => collect()
            ]);
        }

        $evaluations = Evaluation::where('team_id', $team->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.evaluation_history', compact('evaluations'));
    }
}