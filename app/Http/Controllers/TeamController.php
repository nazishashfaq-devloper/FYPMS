<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    // Create Team Page
    public function create()
    {
        $existing = Team::where('leader_id', Auth::id())
            ->orWhereHas('members', function ($q) {
                $q->where('student_id', Auth::id())->where('status', 'accepted');
            })
            ->first();

        if ($existing) {
            return redirect()->route('team.dashboard')->with('error', 'You are already part of a team.');
        }

        return view('student.team_create');
    }

    // Store Team
    public function store(Request $request)
    {
        $existing = Team::where('leader_id', Auth::id())
            ->orWhereHas('members', function ($q) {
                $q->where('student_id', Auth::id())->where('status', 'accepted');
            })
            ->first();

        if ($existing) {
            return redirect()->route('team.dashboard')->with('error', 'You are already part of a team.');
        }

        $request->validate([
            'team_name' => 'required'
        ]);

        $team = Team::create([
            'team_name' => $request->team_name,
            'leader_id' => Auth::id()
        ]);

        // Leader automatically member ban jaye
        TeamMember::create([
            'team_id' => $team->id,
            'student_id' => Auth::id(),
            'status' => 'accepted'
        ]);

        return redirect()->route('team.dashboard')->with('success', 'Team created successfully!');
    }

    // Show My Team (redirects to the full team dashboard which already has this data)
    public function myTeam()
    {
        return redirect()->route('team.dashboard');
    }

    public function invitePage()
         {
            $team = Team::where('leader_id', Auth::id())->first();

            if (!$team) {
                return redirect()->route('team.dashboard')->with('error', 'Only the team leader can invite members.');
            }

            $alreadyOnTeam = TeamMember::where('status', 'accepted')->pluck('student_id');

            $students = User::where('role', 'student')
           ->where('id', '!=', Auth::id())
           ->whereNotIn('id', $alreadyOnTeam)
           ->get();

            return view('student.invite', compact('students', 'team'));
          }
 
    public function sendInvite(Request $request)
  {
         $request->validate(['student_id' => 'required|exists:users,id']);

         $team = Team::where('leader_id', Auth::id())->first();
  
          if (!$team) {
             return back()->with('error', 'You are not a team leader.');
           }

      // max 3 members rule

           $count = TeamMember::where('team_id', $team->id)
            ->where('status', 'accepted')
            ->count();

        if ($count >= 3) {
             return back()->with('error', 'Team already has 3 members.');
         }

    // duplicate check

    $exists = TeamMember::where('team_id', $team->id)
        ->where('student_id', $request->student_id)
        ->first();

    if ($exists) {
        return back()->with('error', 'Already invited.');
    }

    TeamMember::create([
        'team_id' => $team->id,
        'student_id' => $request->student_id,
        'status' => 'pending'
    ]);

    return back()->with('success', 'Invite sent successfully.');
}

 public function acceptInvite($id)
{
    $invite = TeamMember::findOrFail($id);

    if ($invite->student_id != Auth::id()) {
        return back()->with('error', 'Unauthorized action.');
    }

    // check team limit
    $count = TeamMember::where('team_id', $invite->team_id)
        ->where('status', 'accepted')
        ->count();

    if ($count >= 3) {
        return back()->with('error', 'Team is full.');
    }

    $invite->status = 'accepted';
    $invite->save();

    return back()->with('success', 'You joined the team.');
}


public function rejectInvite($id)
{
    $invite = TeamMember::findOrFail($id);

    if ($invite->student_id != Auth::id()) {
        return back()->with('error', 'Unauthorized action.');
    }

    $invite->status = 'rejected';
    $invite->save();

    return back()->with('success', 'Invite rejected.');
}

public function teamDashboard()
{
    $userId = Auth::id();

    // Get team where user is leader OR member
    $team = Team::where('leader_id', $userId)
        ->orWhereHas('members', function ($q) use ($userId) {
            $q->where('student_id', $userId)
              ->where('status', 'accepted');
        })
        ->first();

    if (!$team) {
        return view('student.team_dashboard', [
            'team' => null,
            'members' => [],
            'pending' => []
        ]);
    }

    // accepted members
    $members = TeamMember::where('team_id', $team->id)
        ->where('status', 'accepted')
        ->with('student')
        ->get();

    // pending invites (only visible to leader)
    $pending = TeamMember::where('team_id', $team->id)
        ->where('status', 'pending')
        ->with('student')
        ->get();

    return view('student.team_dashboard', compact('team', 'members', 'pending'));
}

 
}