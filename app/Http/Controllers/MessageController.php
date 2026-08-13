<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Team;
use App\Models\Message;

class MessageController extends Controller
{
    /* ================= STUDENT ================= */

    private function studentTeam()
    {
        $userId = Auth::id();

        return Team::where('leader_id', $userId)
            ->orWhereHas('members', function ($q) use ($userId) {
                $q->where('student_id', $userId)
                  ->where('status', 'accepted');
            })
            ->first();
    }

    public function studentIndex()
    {
        $team = $this->studentTeam();

        $messages = $team
            ? Message::where('team_id', $team->id)->with('sender')->oldest()->get()
            : collect();

        return view('student.messages', compact('team', 'messages'));
    }

    public function studentSend(Request $request)
    {
        $team = $this->studentTeam();

        if (!$team) {
            return back()->with('error', 'You must be part of a team to send messages.');
        }

        if (!$team->supervisor_id) {
            return back()->with('error', 'No supervisor has been assigned to your team yet.');
        }

        $request->validate(['body' => 'required|string|max:2000']);

        Message::create([
            'team_id'   => $team->id,
            'sender_id' => Auth::id(),
            'body'      => $request->body,
        ]);

        return back()->with('success', 'Message sent.');
    }

    /* ================= SUPERVISOR ================= */

    public function supervisorTeams()
    {
        $teams = Team::where('supervisor_id', Auth::id())->orderBy('team_name')->get();
        return view('supervisor.messages_list', compact('teams'));
    }

    public function supervisorThread($teamId)
    {
        $team = Team::where('id', $teamId)->where('supervisor_id', Auth::id())->firstOrFail();

        $messages = Message::where('team_id', $team->id)->with('sender')->oldest()->get();

        return view('supervisor.messages_thread', compact('team', 'messages'));
    }

    public function supervisorSend(Request $request, $teamId)
    {
        $team = Team::where('id', $teamId)->where('supervisor_id', Auth::id())->firstOrFail();

        $request->validate(['body' => 'required|string|max:2000']);

        Message::create([
            'team_id'   => $team->id,
            'sender_id' => Auth::id(),
            'body'      => $request->body,
        ]);

        return back()->with('success', 'Message sent.');
    }
}
