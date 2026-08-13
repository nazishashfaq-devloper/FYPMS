<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Document;
use App\Models\Team;
use App\Models\Deadline;

class DocumentController extends Controller
{
    /**
     * Document types the upload form / validation accept. Kept in sync with
     * DeadlineController::DOCUMENT_TYPES so admin deadlines and the upload
     * form always agree on the same set of types.
     */
    public const DOCUMENT_TYPES = DeadlineController::DOCUMENT_TYPES;

    /**
     * Resolve the team the current user belongs to, as leader OR as an
     * accepted member. Centralised here so index()/store() can never drift
     * out of sync with each other.
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
     * Find the deadline that governs a given document type: a deadline
     * created specifically for that type takes priority, otherwise fall
     * back to the general deadline (document_type = null), if any.
     */
    private function deadlineFor(string $documentType)
    {
        return Deadline::where('document_type', $documentType)->latest()->first()
            ?? Deadline::whereNull('document_type')->latest()->first();
    }

    /**
     * Build a [document_type => ['deadline' => Deadline|null, 'expired' => bool]]
     * map for every supported document type, so the view can show/disable
     * per-type upload state without duplicating this logic in Blade.
     */
    private function deadlineStatuses(): array
    {
        $statuses = [];

        foreach (self::DOCUMENT_TYPES as $type) {
            $deadline = $this->deadlineFor($type);

            $statuses[$type] = [
                'deadline' => $deadline,
                'expired'  => $deadline ? now()->gt($deadline->deadline_date->copy()->endOfDay()) : false,
            ];
        }

        return $statuses;
    }

    /**
     * Show upload page + list documents.
     *
     * IMPORTANT: this action always renders the Documents view. It must
     * never redirect elsewhere (e.g. redirect()->back()), because that
     * makes the destination depend on whatever page the user happened to
     * be on previously (session's "previous URL"), which is what caused
     * the Documents menu link to intermittently bounce to the Proposal
     * page or other unrelated pages. The "no team yet" state is instead
     * handled inline in the view, exactly like team.dashboard and
     * proposal.index already do.
     */
    public function index()
    {
        $team = $this->currentTeam();

        $documents = $team
            ? Document::where('team_id', $team->id)->latest()->get()
            : collect();

        $deadlineStatuses = $this->deadlineStatuses();

        return view('student.documents', compact('team', 'documents', 'deadlineStatuses'));
    }

    // Upload document
    public function store(Request $request)
    {
        $data = $request->validate([
            'document_type' => ['required', Rule::in(self::DOCUMENT_TYPES)],
            'file' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $team = $this->currentTeam();

        if (!$team) {
            return redirect()->route('documents.index')
                ->with('error', 'You must be part of a team to upload documents.');
        }

        $deadline = $this->deadlineFor($data['document_type']);

        if ($deadline && now()->gt($deadline->deadline_date->copy()->endOfDay())) {
            return redirect()->route('documents.index')
                ->with('error', 'The deadline for submitting "' . ucfirst(str_replace('_', ' ', $data['document_type'])) . '" was ' . $deadline->deadline_date->format('d M Y') . '. This deadline has passed, so uploads for this document type are no longer accepted.');
        }

        $uploadedFile = $request->file('file');
        $originalName = $uploadedFile->getClientOriginalName();
        $filePath = $uploadedFile->store('documents', 'public');

        // CHECK if same type already exists → replace it
        Document::updateOrCreate(
            [
                'team_id' => $team->id,
                'document_type' => $data['document_type'],
            ],
            [
                'uploaded_by' => Auth::id(),
                'file_path' => $filePath,
                'original_name' => $originalName,
                'status' => 'pending',
                'feedback' => null,
            ]
        );

        return redirect()->route('documents.index')->with('success', 'Document uploaded successfully.');
    }

    /**
     * Download a document. Accessible by:
     *  - the student who is a member/leader of the document's team
     *  - the supervisor assigned to that team
     *  - an admin
     */
    public function download($id)
    {
        $document = Document::with('team')->findOrFail($id);
        $user = Auth::user();
        $team = $document->team;

        $isTeamMember = $team && (
            $team->leader_id == $user->id ||
            $team->members()->where('student_id', $user->id)->where('status', 'accepted')->exists()
        );

        $isAssignedSupervisor = $team && $team->supervisor_id == $user->id;

        $isAdmin = $user->role === 'admin';

        if (!($isTeamMember || $isAssignedSupervisor || $isAdmin)) {
            abort(403, 'You are not authorized to download this document.');
        }

        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found on server.');
        }

        $downloadName = $document->original_name
            ?: (str_replace('_', ' ', $document->document_type) . '.' . pathinfo($document->file_path, PATHINFO_EXTENSION));

        return Storage::disk('public')->download($document->file_path, $downloadName);
    }
}
