<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Team;
use App\Models\Document;
use App\Models\Proposal;
use App\Models\Deadline;
use App\Models\Evaluation;
use App\Models\Meeting;
use App\Models\Milestone;
use App\Models\Notification;

class SupervisorController extends Controller
{
    /* ================= DASHBOARD ================= */
    public function dashboard()
    {
        $supervisorId = auth()->id();

        $teams = Team::where('supervisor_id', $supervisorId)->with('leader')->get();

        $teamIds = $teams->pluck('id');

        $documents = Document::whereIn('team_id', $teamIds)->latest()->get();

        $proposals = Proposal::whereIn('team_id', $teamIds)->with('team')->latest()->get();

        $deadlines = Deadline::latest()->take(5)->get();

        /* ===== SUMMARY STATS ===== */
        $pendingProposals = $proposals->where('status', 'pending')->count();
        $pendingDocuments = $documents->where('status', 'pending')->count();
        $upcomingMilestones = Milestone::whereIn('team_id', $teamIds)
            ->where('status', 'pending')
            ->count();
        $evaluationWorkload = $teams->count() - Evaluation::whereIn('team_id', $teamIds)
            ->distinct('team_id')
            ->count('team_id');

        return view('supervisor.dashboard', compact(
            'teams',
            'documents',
            'proposals',
            'deadlines',
            'pendingProposals',
            'pendingDocuments',
            'upcomingMilestones',
            'evaluationWorkload'
        ));
    }

    /* ================= TEAMS PAGE ================= */
    public function teams()
    {
        $teams = Team::where('supervisor_id', auth()->id())->with('leader')->get();
        return view('supervisor.teams', compact('teams'));
    }

    /* ================= PROPOSALS PAGE ================= */
    public function proposals()
    {
        $supervisorId = auth()->id();

        $teamIds = Team::where('supervisor_id', $supervisorId)->pluck('id');

        $proposals = Proposal::whereIn('team_id', $teamIds)->with('team')->latest()->get();

        return view('supervisor.proposals', compact('proposals'));
    }

    /* ================= DOCUMENTS PAGE ================= */
    public function documents()
    {
        $teams = Team::where('supervisor_id', auth()->id())->pluck('id');

        $documents = Document::whereIn('team_id', $teams)->with('team')->latest()->get();

        return view('supervisor.documents', compact('documents'));
    }

    /* ================= Approve DOCUMENTS ================= */
    public function approveDocument($id)
    {
        $doc = Document::findOrFail($id);
        $this->authorizeTeam($doc->team_id);

        $doc->status = 'approved';
        $doc->feedback = 'Approved by supervisor';
        $doc->save();

        return back()->with('success', 'Document Approved');
    }

    /**
     * Abort if the given team is not supervised by the logged-in supervisor.
     */
    private function authorizeTeam($teamId)
    {
        $owns = Team::where('id', $teamId)->where('supervisor_id', auth()->id())->exists();

        if (!$owns) {
            abort(403, 'You are not the supervisor for this team.');
        }
    }

    /* ================= MEETINGS PAGE ================= */
    public function meetings()
    {
        $meetings = Meeting::where('supervisor_id', auth()->id())->with('team')->latest()->get();

        return view('supervisor.meetings', compact('meetings'));
    }

    /* ================= EVALUATIONS PAGE ================= */
    public function evaluations()
    {
        $evaluations = Evaluation::where('supervisor_id', auth()->id())->with('team')->latest()->get();

        return view('supervisor.evaluations', compact('evaluations'));
    }

    /* ================= DOCUMENT ACTION ================= */
    public function rejectDocument(Request $request, $id)
    {
        $doc = Document::findOrFail($id);
        $this->authorizeTeam($doc->team_id);

        $doc->status = 'rejected';
        $doc->feedback = $request->feedback ?? 'Rejected by supervisor';
        $doc->save();

        return redirect()->back()->with('success', 'Document Rejected');
    }

    /* ================= PROPOSAL ACTIONS ================= */
    public function approveProposal($id)
 {
    $proposal = Proposal::findOrFail($id);
    $this->authorizeTeam($proposal->team_id);

    $proposal->status = 'approved';
    $proposal->feedback = 'Approved by supervisor';
    $proposal->save();

    // 🔔 NOTIFICATION
    Notification::create([
        'user_id' => $proposal->student_id,
        'title' => 'Proposal Approved',
        'message' => 'Your project proposal has been approved by supervisor.',
    ]);

    return back()->with('success', 'Proposal approved');
 }

   public function rejectProposal(Request $request, $id)
{
    $proposal = Proposal::findOrFail($id);
    $this->authorizeTeam($proposal->team_id);

    $proposal->status = 'rejected';
    $proposal->feedback = $request->feedback ?? 'Rejected by supervisor';
    $proposal->save();

    // 🔔 NOTIFICATION
    Notification::create([
        'user_id' => $proposal->student_id,
        'title' => 'Proposal Rejected',
        'message' => $request->feedback ?? 'Your proposal was rejected by supervisor.',
    ]);

    return back()->with('success', 'Proposal rejected');
}

    /* ================= EVALUATION ================= */
    public function showEvaluationForm($teamId)
    {
        $this->authorizeTeam($teamId);
        $team = Team::findOrFail($teamId);

        return view('supervisor.evaluation', compact('team'));
    }

    public function storeEvaluation(Request $request)
    {
        $request->validate([
            'team_id' => 'required',
            'marks' => 'required|integer|min:0|max:100',
            'remarks' => 'nullable|string'
        ]);

        $this->authorizeTeam($request->team_id);

        Evaluation::create([
            'team_id' => $request->team_id,
            'supervisor_id' => auth()->id(),
            'marks' => $request->marks,
            'remarks' => $request->remarks,
            'phase' => $request->phase ?? 'progress'
        ]);

        return back()->with('success', 'Evaluation saved successfully!');
    }

    /* ================= MEETING ================= */
    public function createMeeting($teamId)
    {
        $this->authorizeTeam($teamId);
        $team = Team::findOrFail($teamId);

        return view('supervisor.create_meeting', compact('team'));
    }

    public function storeMeeting(Request $request)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'meeting_date' => 'required|date',
            'meeting_time' => 'required',
            'venue' => 'nullable|string|max:255',
            'meeting_link' => 'nullable|string|max:255',
            'agenda' => 'nullable|string',
        ]);

        $this->authorizeTeam($request->team_id);

        Meeting::create([
            'team_id' => $request->team_id,
            'supervisor_id' => auth()->id(),
            'meeting_date' => $request->meeting_date,
            'meeting_time' => $request->meeting_time,
            'venue' => $request->venue,
            'meeting_link' => $request->meeting_link,
            'agenda' => $request->agenda,
        ]);

        return redirect()->back()->with('success', 'Meeting scheduled successfully.');
    }
}