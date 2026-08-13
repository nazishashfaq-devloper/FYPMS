<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Deadline;

class DeadlineController extends Controller
{
    /**
     * Document types the Documents module accepts. Kept here (and in
     * DocumentController) as the single list the "Document Type" dropdowns
     * are validated against.
     */
    public const DOCUMENT_TYPES = ['proposal', 'srs', 'design', 'progress_report', 'final_report'];

    public function index()
    {
        $deadlines = Deadline::latest()->get();
        return view('admin.deadlines.index', compact('deadlines'));
    }

    public function create()
    {
        $documentTypes = self::DOCUMENT_TYPES;
        return view('admin.deadlines.create', compact('documentTypes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'document_type' => ['nullable', Rule::in(self::DOCUMENT_TYPES)],
            'deadline_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        Deadline::create($data);

        return redirect()->route('admin.deadlines.index')
            ->with('success', 'Deadline added successfully');
    }
}