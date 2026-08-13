<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TeamController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\DeadlineController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\PresentationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (!Auth::check()) {
        return view('welcome');
    }

    $user = Auth::user();

    if ($user->role == 'admin') {
        return redirect('/admin/dashboard');
    }

    if ($user->role == 'supervisor') {
        return redirect('/supervisor/dashboard');
    }

    return redirect('/student/dashboard');
});

/*
|--------------------------------------------------------------------------
| PUBLIC / GUEST
|--------------------------------------------------------------------------
*/

Route::get('/announcements', function () {
    $announcements = \App\Models\Announcement::latest()->get();
    return view('announcements.index', compact('announcements'));
})->name('public.announcements');

Route::get('/deadlines', function () {
    $deadlines = \App\Models\Deadline::latest()->get();
    return view('deadlines.index', compact('deadlines'));
})->name('public.deadlines');

Route::get('/guidelines', [PublicController::class, 'guidelines'])->name('public.guidelines');

Route::get('/projects/search', [PublicController::class, 'searchProjects'])->name('public.projects.search');

Auth::routes();



/*
|--------------------------------------------------------------------------
| DASHBOARDS
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'prevent-back-history'])->group(function () {

    // STUDENT
    Route::get('/student/dashboard', [StudentController::class, 'dashboard'])
        ->middleware('role:student');

    // SUPERVISOR
    Route::get('/supervisor/dashboard', [SupervisorController::class, 'dashboard'])
        ->middleware('role:supervisor');

    // ADMIN
    Route::get('/admin/dashboard', [AdminController::class, 'index'])
        ->middleware('role:admin');

    // DOCUMENT DOWNLOAD (student, supervisor, admin — authorization handled in controller)
    Route::get('/documents/{id}/download', [DocumentController::class, 'download'])
        ->name('documents.download');

    // PROFILE / PASSWORD UPDATE (available to every authenticated role)
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password.update');
});


/*
|--------------------------------------------------------------------------
| STUDENT MODULE
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:student'])->group(function () {

    /* ================= PROPOSAL ================= */

    Route::get('/student/proposal/create', [ProposalController::class, 'create'])
        ->name('proposal.create');

    Route::post('/student/proposal/store', [ProposalController::class, 'store'])
        ->name('proposal.store');

    Route::get('/student/proposal', [ProposalController::class, 'myProposals'])
        ->name('proposal.index');

    Route::get('/student/proposal/edit/{id}', [ProposalController::class, 'edit'])
        ->name('proposal.edit');

    Route::post('/student/proposal/update/{id}', [ProposalController::class, 'update'])
        ->name('proposal.update');


    /* ================= TEAM ================= */

    Route::get('/team/create', [TeamController::class, 'create'])->name('team.create');
    Route::post('/team/store', [TeamController::class, 'store'])->name('team.store');
    Route::get('/team/my', [TeamController::class, 'myTeam'])->name('team.my');
    Route::get('/team/dashboard', [TeamController::class, 'teamDashboard'])->name('team.dashboard');

    Route::get('/team/invite', [TeamController::class, 'invitePage'])->name('team.invite');
    Route::post('/team/invite/send', [TeamController::class, 'sendInvite'])->name('team.invite.send');
    Route::post('/team/invite/accept/{id}', [TeamController::class, 'acceptInvite'])->name('team.invite.accept');
    Route::post('/team/invite/reject/{id}', [TeamController::class, 'rejectInvite'])->name('team.invite.reject');


    /* ================= DOCUMENTS ================= */

    Route::get('/student/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::post('/student/documents/upload', [DocumentController::class, 'store'])->name('documents.store');


    /* ================= MILESTONES ================= */

    Route::get('/student/milestones', [MilestoneController::class, 'studentMilestones'])
        ->name('milestones.index');


    /* ================= EVALUATION ================= */

    Route::get('/student/evaluation-history', [EvaluationController::class, 'history'])
        ->name('evaluation.history');


    /* ================= PRESENTATION ================= */

    Route::get('/student/presentation', [PresentationController::class, 'studentView'])
        ->name('student.presentation');


    /* ================= COMMUNICATION ================= */

    Route::get('/student/messages', [MessageController::class, 'studentIndex'])
        ->name('student.messages');

    Route::post('/student/messages/send', [MessageController::class, 'studentSend'])
        ->name('student.messages.send');
});

/*
|--------------------------------------------------------------------------
| SUPERVISOR MODULE
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:supervisor'])->group(function () {

    /* ================= DOCUMENTS ================= */
    Route::post('/supervisor/document/approve/{id}', [SupervisorController::class, 'approveDocument']);
    Route::post('/supervisor/document/reject/{id}', [SupervisorController::class, 'rejectDocument']);

    /* ================= PROPOSALS ================= */
    Route::post('/supervisor/proposal/approve/{id}', [SupervisorController::class, 'approveProposal']);
    Route::post('/supervisor/proposal/reject/{id}', [SupervisorController::class, 'rejectProposal']);

    /* ================= EVALUATIONS ================= */
    Route::get('/supervisor/evaluation/{team}', [SupervisorController::class, 'showEvaluationForm'])
        ->name('supervisor.evaluation');

    Route::post('/supervisor/evaluation/store', [SupervisorController::class, 'storeEvaluation'])
        ->name('supervisor.evaluation.store');

    /* ================= MEETINGS ================= */
    Route::get('/supervisor/meetings/create/{team}', [SupervisorController::class, 'createMeeting'])
        ->name('supervisor.meetings.create');

    Route::post('/supervisor/meetings/store', [SupervisorController::class, 'storeMeeting'])
        ->name('supervisor.meetings.store');

    /* ================= MILESTONES ================= */
    Route::get('/supervisor/milestones', [MilestoneController::class, 'supervisorIndex'])
        ->name('supervisor.milestones.index');

    Route::post('/supervisor/milestones/update/{id}', [MilestoneController::class, 'updateStatus'])
        ->name('supervisor.milestones.update');

    /* ================= COMMUNICATION ================= */
    Route::get('/supervisor/messages', [MessageController::class, 'supervisorTeams'])
        ->name('supervisor.messages.index');

    Route::get('/supervisor/messages/{team}', [MessageController::class, 'supervisorThread'])
        ->name('supervisor.messages.thread');

    Route::post('/supervisor/messages/{team}/send', [MessageController::class, 'supervisorSend'])
        ->name('supervisor.messages.send');

    /* ================= NAVIGATION PAGES ================= */
    Route::get('/supervisor/teams', [SupervisorController::class, 'teams']);
    Route::get('/supervisor/proposals', [SupervisorController::class, 'proposals']);
    Route::get('/supervisor/documents', [SupervisorController::class, 'documents']);
    Route::get('/supervisor/meetings', [SupervisorController::class, 'meetings']);
    Route::get('/supervisor/evaluations', [SupervisorController::class, 'evaluations']);

});

/*
|--------------------------------------------------------------------------
| ADMIN MODULE
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin/assign-supervisor', [AdminController::class, 'assignSupervisorForm']);
    Route::post('/admin/assign-supervisor', [AdminController::class, 'assignSupervisor']);

    Route::get('/admin/users/create', [AdminController::class, 'createUser']);
    Route::post('/admin/users/store', [AdminController::class, 'storeUser']);
    Route::get('/admin/users', [AdminController::class, 'allUsers']);
    Route::get('/admin/users/edit/{id}', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::post('/admin/users/update/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::post('/admin/users/toggle/{id}', [AdminController::class, 'toggleUserStatus'])->name('admin.users.toggle');
    Route::post('/admin/users/delete/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');


    Route::get('/admin/announcements', [AnnouncementController::class, 'index'])
      ->name('admin.announcements.index');

    Route::get('/admin/announcements/create', [AnnouncementController::class, 'create'])
      ->name('admin.announcements.create');

    Route::post('/admin/announcements/store', [AnnouncementController::class, 'store'])
     ->name('admin.announcements.store');


   Route::get('/admin/deadlines', [DeadlineController::class, 'index'])->name('admin.deadlines.index');
   Route::get('/admin/deadlines/create', [DeadlineController::class, 'create'])->name('admin.deadlines.create');
   Route::post('/admin/deadlines/store', [DeadlineController::class, 'store'])->name('admin.deadlines.store');


   Route::get('/admin/reports', [AdminController::class, 'reports'])->name('admin.reports');

   Route::post('/admin/reports/generate', [AdminController::class, 'generateReport'])->name('admin.reports.generate');


   /* ================= DOMAINS ================= */
   Route::get('/admin/domains', [DomainController::class, 'index'])->name('admin.domains.index');
   Route::get('/admin/domains/create', [DomainController::class, 'create'])->name('admin.domains.create');
   Route::post('/admin/domains/store', [DomainController::class, 'store'])->name('admin.domains.store');
   Route::post('/admin/domains/delete/{id}', [DomainController::class, 'destroy'])->name('admin.domains.delete');


   /* ================= MILESTONES ================= */
   Route::get('/admin/milestones', [MilestoneController::class, 'adminIndex'])->name('admin.milestones.index');
   Route::get('/admin/milestones/create', [MilestoneController::class, 'adminCreate'])->name('admin.milestones.create');
   Route::post('/admin/milestones/store', [MilestoneController::class, 'adminStore'])->name('admin.milestones.store');


   /* ================= PRESENTATIONS ================= */
   Route::get('/admin/presentations', [PresentationController::class, 'index'])->name('admin.presentations.index');
   Route::get('/admin/presentations/create', [PresentationController::class, 'create'])->name('admin.presentations.create');
   Route::post('/admin/presentations/store', [PresentationController::class, 'store'])->name('admin.presentations.store');

});
