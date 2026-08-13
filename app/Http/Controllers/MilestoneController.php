<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Milestone;
use App\Models\Team;

class MilestoneController extends Controller
{
    /* ================= STUDENT ================= */

    public function studentMilestones()
    {
        $userId = Auth::id();

        $team = Team::where('leader_id', $userId)
            ->orWhereHas('members', function ($q) use ($userId) {
                $q->where('student_id', $userId)
                  ->where('status', 'accepted');
            })
            ->first();

        $milestones = $team
            ? Milestone::where('team_id', $team->id)->orderBy('due_date')->get()
            : collect();

        return view('student.milestones', compact('milestones', 'team'));
    }

    /* ================= SUPERVISOR ================= */

    public function supervisorIndex()
    {
        $teamIds = Team::where('supervisor_id', Auth::id())->pluck('id');

        $milestones = Milestone::whereIn('team_id', $teamIds)
            ->with('team')
            ->orderBy('due_date')
            ->get();

        return view('supervisor.milestones', compact('milestones'));
    }

    public function updateStatus(Request $request, $id)
    {
        $milestone = Milestone::findOrFail($id);

        // Make sure this milestone belongs to a team supervised by the logged-in supervisor
        $team = Team::where('id', $milestone->team_id)
            ->where('supervisor_id', Auth::id())
            ->first();

        if (!$team) {
            return back()->with('error', 'Unauthorized action.');
        }

        $request->validate(['status' => 'required|in:pending,completed']);

        $milestone->status = $request->status;
        $milestone->save();

        return back()->with('success', 'Milestone status updated.');
    }

    /* ================= ADMIN ================= */

    public function adminIndex()
    {
        $milestones = Milestone::with('team')->orderBy('due_date')->get();
        $teams = Team::orderBy('team_name')->get();

        return view('admin.milestones.index', compact('milestones', 'teams'));
    }

    public function adminCreate()
    {
        $teams = Team::orderBy('team_name')->get();
        return view('admin.milestones.create', compact('teams'));
    }

    public function adminStore(Request $request)
    {
        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'due_date' => 'nullable|date',
            'team_id'  => 'required', // either a specific team id or "all"
        ]);

        if ($data['team_id'] === 'all') {
            $teams = Team::all();

            foreach ($teams as $team) {
                Milestone::create([
                    'team_id'  => $team->id,
                    'title'    => $data['title'],
                    'due_date' => $data['due_date'] ?? null,
                    'status'   => 'pending',
                ]);
            }

            $message = 'Milestone "' . $data['title'] . '" applied to all ' . $teams->count() . ' teams.';
        } else {
            Milestone::create([
                'team_id'  => $data['team_id'],
                'title'    => $data['title'],
                'due_date' => $data['due_date'] ?? null,
                'status'   => 'pending',
            ]);

            $message = 'Milestone added for the selected team.';
        }

        return redirect()->route('admin.milestones.index')->with('success', $message);
    }
}
