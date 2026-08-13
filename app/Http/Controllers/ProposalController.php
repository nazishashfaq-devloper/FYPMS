<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\Team;
use App\Models\Domain;
use Illuminate\Support\Facades\Auth;

class ProposalController extends Controller
{
    /**
     * Find the team the current student belongs to (as leader or accepted member).
     */
    private function currentTeam()
    {
        $userId = Auth::id();

        return Team::where('leader_id', $userId)
            ->orWhereHas('members', function ($q) use ($userId) {
                $q->where('student_id', $userId)
                  ->where('status', 'accepted');
            })
            ->first();
    }

    /**
     * Show the "submit new proposal" form.
     */
    public function create()
    {
        $team = $this->currentTeam();

        if (!$team) {
            return redirect()->route('team.create')
                ->with('error', 'You must create or join a team before submitting a proposal.');
        }

        $existing = Proposal::where('team_id', $team->id)->latest()->first();

        if ($existing && $existing->status !== 'rejected') {
            return redirect()->route('proposal.index')
                ->with('error', 'Your team already has a proposal that is ' . $existing->status . '.');
        }

        $domains = Domain::orderBy('name')->get();

        return view('student.proposals.create', compact('team', 'domains'));
    }

    /**
     * Store a newly submitted proposal.
     */
    public function store(Request $request)
    {
        $team = $this->currentTeam();

        if (!$team) {
            return redirect()->route('team.create')
                ->with('error', 'You must create or join a team before submitting a proposal.');
        }

        if ($team->leader_id !== Auth::id()) {
            return redirect()->route('proposal.index')
                ->with('error', 'Only the team leader can submit the project proposal.');
        }

        $existing = Proposal::where('team_id', $team->id)->latest()->first();

        if ($existing && $existing->status !== 'rejected') {
            return redirect()->route('proposal.index')
                ->with('error', 'Your team already has a proposal that is ' . $existing->status . '.');
        }

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'domain'      => 'required|string|max:255',
            'tools'       => 'nullable|string|max:1000',
            'description' => 'required|string',
        ]);

        Proposal::create([
            'student_id'    => Auth::id(),
            'team_id'       => $team->id,
            'supervisor_id' => $team->supervisor_id,
            'title'         => $data['title'],
            'domain'        => $data['domain'],
            'tools'         => $data['tools'] ?? null,
            'description'   => $data['description'],
            'status'        => 'pending',
        ]);

        return redirect()->route('proposal.index')
            ->with('success', 'Proposal submitted successfully. Awaiting supervisor review.');
    }

    /**
     * List the proposal(s) belonging to the student's team.
     */
    public function myProposals()
    {
        $team = $this->currentTeam();

        $proposals = $team
            ? Proposal::where('team_id', $team->id)->latest()->get()
            : collect();

        return view('student.my_proposals', compact('proposals', 'team'));
    }

    /**
     * Show the resubmission form for a rejected proposal.
     */
    public function edit($id)
    {
        $proposal = Proposal::findOrFail($id);
        $team = $this->currentTeam();

        if (!$team || $proposal->team_id !== $team->id) {
            return redirect()->route('proposal.index')->with('error', 'Unauthorized action.');
        }

        if ($team->leader_id !== Auth::id()) {
            return redirect()->route('proposal.index')
                ->with('error', 'Only the team leader can resubmit the proposal.');
        }

        if ($proposal->status !== 'rejected') {
            return redirect()->route('proposal.index')
                ->with('error', 'Only a rejected proposal can be resubmitted.');
        }

        $domains = Domain::orderBy('name')->get();

        return view('student.proposals.edit', compact('proposal', 'domains'));
    }

    /**
     * Update (resubmit) a previously rejected proposal.
     */
    public function update(Request $request, $id)
    {
        $proposal = Proposal::findOrFail($id);
        $team = $this->currentTeam();

        if (!$team || $proposal->team_id !== $team->id) {
            return redirect()->route('proposal.index')->with('error', 'Unauthorized action.');
        }

        if ($team->leader_id !== Auth::id()) {
            return redirect()->route('proposal.index')
                ->with('error', 'Only the team leader can resubmit the proposal.');
        }

        if ($proposal->status !== 'rejected') {
            return redirect()->route('proposal.index')
                ->with('error', 'Only a rejected proposal can be resubmitted.');
        }

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'domain'      => 'required|string|max:255',
            'tools'       => 'nullable|string|max:1000',
            'description' => 'required|string',
        ]);

        $proposal->update([
            'title'       => $data['title'],
            'domain'      => $data['domain'],
            'tools'       => $data['tools'] ?? null,
            'description' => $data['description'],
            'status'      => 'pending',
            'feedback'    => null,
        ]);

        return redirect()->route('proposal.index')
            ->with('success', 'Proposal resubmitted successfully. Awaiting supervisor review.');
    }
}
