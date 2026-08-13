<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Team;
use App\Models\TeamMember;
use App\Models\Document;
use App\Models\Milestone;
use App\Models\Evaluation;
use App\Models\Meeting;
use App\Models\Notification;
use App\Models\Proposal;
use App\Models\Presentation;


class StudentController extends Controller
{
    public function dashboard()
    {
        $userId = auth()->id();

        /* ================= TEAM ================= */
        $team = Team::where('leader_id', $userId)
            ->orWhereHas('members', function ($q) use ($userId) {
                $q->where('student_id', $userId)
                  ->where('status', 'accepted');
            })
            ->first();

        /* ================= INVITATIONS ================= */
        $invitations = TeamMember::with('student')
            ->where('student_id', $userId)
            ->where('status', 'pending')
            ->get();

        /* ================= DOCUMENTS ================= */
        $documents = collect();

        if ($team) {
            $documents = Document::where('team_id', $team->id)->get();
        }

        /* ================= MEETINGS ================= */
        $meetings = collect();

        if ($team) {
            $meetings = Meeting::where('team_id', $team->id)
                ->orderBy('meeting_date')
                ->orderBy('meeting_time')
                ->get();
        }

        /* ================= MILESTONES ================= */
        $milestones = collect();

        if ($team) {
            $milestones = Milestone::where('team_id', $team->id)->get();
        }

        /* ================= EVALUATIONS (FIXED) ================= */
        $evaluations = collect();

        if ($team) {
            $evaluations = Evaluation::where('team_id', $team->id)
                ->latest()
                ->get();
        }

        /* ================= NOTIFICATIONS ================= */
        $notifications = Notification::where('user_id', $userId)
            ->latest()
            ->get();
        /* ================= PRESENTATIONS ================= */
        $presentations = collect();

        if ($team) {
            $presentations = Presentation::where('team_id', $team->id)
                ->orderBy('presentation_date')
                ->get();
        }

        /* ================= Latest proposal  ================= */
       $latestProposal = $team
           ? Proposal::where('team_id', $team->id)->latest()->first()
           : null;

        return view('student.dashboard', compact(
            'team',
            'invitations',
            'documents',
            'milestones',
            'evaluations',
            'meetings',
            'notifications',
            'latestProposal',
            'presentations'
        ));
    }
}