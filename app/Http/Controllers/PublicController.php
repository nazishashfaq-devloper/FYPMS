<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proposal;

class PublicController extends Controller
{
    public function guidelines()
    {
        return view('public.guidelines');
    }

    public function searchProjects(Request $request)
    {
        $query = Proposal::where('status', 'approved')->with('team');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('domain')) {
            $query->where('domain', $request->domain);
        }

        $projects = $query->latest()->paginate(10)->withQueryString();
        $domains = Proposal::where('status', 'approved')->distinct()->pluck('domain');

        return view('public.project_search', compact('projects', 'domains'));
    }
}
