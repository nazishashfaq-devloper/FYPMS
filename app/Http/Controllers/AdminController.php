<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Team;
use App\Models\Proposal;
use App\Models\Document;
use App\Models\Report;
use App\Models\Evaluation;




class AdminController extends Controller
{
    public function index()
    {
        $students = User::where('role', 'student')->count();
        $supervisors = User::where('role', 'supervisor')->count();

        $teams = Team::count();
        $proposals = Proposal::count();
        $documents = Document::count();

        $allTeams = Team::with('leader')->get();
        $allProposals = Proposal::all();
        $allDocuments = Document::all();
        $allUsers = User::all();

        $evaluations = Evaluation::with(['team', 'supervisor'])->latest()->get();

        return view('admin.dashboard', compact(
            'students',
            'supervisors',
            'teams',
            'proposals',
            'documents',
            'allTeams',
            'allProposals',
            'allDocuments',
            'allUsers',
            'evaluations'
        ));
    }

    public function assignSupervisorForm()
  {
    $teams = Team::whereNull('supervisor_id')->get();
    $supervisors = User::where('role', 'supervisor')->get();

    return view('admin.assign_supervisor', compact('teams', 'supervisors'));
  }

    public function assignSupervisor(Request $request)
  {
    $team = Team::findOrFail($request->team_id);

    $team->supervisor_id = $request->supervisor_id;
    $team->save();

    return redirect()->back()->with('success', 'Supervisor assigned successfully');
  }



// Show form
    public function createUser()
    {
        return view('admin.create_user');
    }

    // Store user
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return back()->with('success', 'User created successfully!');
    }

    // Show users
    public function allUsers()
    {
        $users = User::all();
        return view('admin.users_list', compact('users'));
    }

    // Edit user form
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit_user', compact('user'));
    }

    // Update user
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:student,supervisor,admin',
            'password' => 'nullable|min:6',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect('/admin/users')->with('success', 'User updated successfully.');
    }

    // Toggle active / deactivate
    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        return back()->with('success', 'User ' . ($user->is_active ? 'activated' : 'deactivated') . ' successfully.');
    }

    // Delete user
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    //show reports
    public function reports()
  {
    $reports = Report::latest()->get();

    return view('admin.reports', compact('reports'));
  }


    public function generateReport()
 {
    $report = new Report();

    $report->total_teams = Team::count();
    $report->total_students = User::where('role', 'student')->count();
    $report->total_supervisors = User::where('role', 'supervisor')->count();

    $report->total_proposals = Proposal::count();
    $report->approved_proposals = Proposal::where('status', 'approved')->count();
    $report->pending_proposals = Proposal::where('status', 'pending')->count();
    $report->rejected_proposals = Proposal::where('status', 'rejected')->count();

    $report->generated_by = auth()->id();

    $report->save();

    return redirect()->route('admin.reports')
        ->with('success', 'Report generated successfully!');
  }


}
