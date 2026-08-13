<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Presentation;
use App\Models\Team;

class PresentationController extends Controller
{
    /* ================= ADMIN ================= */

    public function index()
    {
        $presentations = Presentation::with('team')->latest('presentation_date')->get();
        return view('admin.presentations.index', compact('presentations'));
    }

    public function create()
    {
        $teams = Team::orderBy('team_name')->get();
        return view('admin.presentations.create', compact('teams'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'team_id'            => 'required|exists:teams,id',
            'phase'               => 'required|in:proposal_defense,progress_evaluation,final_defense',
            'presentation_date'  => 'required|date',
            'presentation_time'  => 'required',
            'venue'               => 'nullable|string|max:255',
            'meeting_link'        => 'nullable|string|max:255',
            'panel_members'       => 'nullable|string',
        ]);

        $data['scheduled_by'] = Auth::id();

        Presentation::create($data);

        return redirect()->route('admin.presentations.index')
            ->with('success', 'Presentation scheduled successfully.');
    }

    /* ================= STUDENT ================= */

    public function studentView()
    {
        $userId = Auth::id();

        $team = Team::where('leader_id', $userId)
            ->orWhereHas('members', function ($q) use ($userId) {
                $q->where('student_id', $userId)
                  ->where('status', 'accepted');
            })
            ->first();

        $presentations = $team
            ? Presentation::where('team_id', $team->id)->orderBy('presentation_date')->get()
            : collect();

        return view('student.presentation', compact('presentations', 'team'));
    }
}
