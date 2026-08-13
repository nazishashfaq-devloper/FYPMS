@extends('layouts.public')

@section('title', 'Project Guidelines')

@section('content')
<h2 class="mb-4"><i class="bi bi-journal-text me-2"></i>Final Year Project Guidelines</h2>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5>General Rules</h5>
        <ul>
            <li>Every project must be undertaken by a team of 1&ndash;3 students.</li>
            <li>Each team must register and submit a project proposal for supervisor approval before development begins.</li>
            <li>A supervisor will be allocated to each team by the FYP administration after team formation.</li>
            <li>All deliverables (proposal, SRS, design document, progress reports, final report) must be uploaded through the portal in the formats specified by your supervisor.</li>
        </ul>

        <h5 class="mt-4">Submission Timeline</h5>
        <ol>
            <li><strong>Team Formation</strong> &mdash; create or join a team within the first two weeks of the semester.</li>
            <li><strong>Proposal Submission</strong> &mdash; submit your project title, abstract, domain, and tools for review.</li>
            <li><strong>Proposal Defense</strong> &mdash; present your proposal to the evaluation panel.</li>
            <li><strong>Progress Evaluation</strong> &mdash; demonstrate progress against your milestones to your supervisor.</li>
            <li><strong>Final Report &amp; Defense</strong> &mdash; submit your final report and present your completed project.</li>
        </ol>

        <h5 class="mt-4">Evaluation Criteria</h5>
        <p class="text-muted">
            Each phase (proposal defense, progress evaluation, and final defense) is marked separately by your
            supervisor and/or the evaluation panel. Marks, remarks, and recommendations from each phase are
            visible to you under <em>Evaluation History</em> once published.
        </p>

        <p class="text-muted mb-0">For exact dates relevant to the current semester, check the <a href="{{ route('public.deadlines') }}">Deadlines</a> page.</p>
    </div>
</div>
@endsection
