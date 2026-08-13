<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Domain;

class DomainController extends Controller
{
    public function index()
    {
        $domains = Domain::orderBy('name')->get();
        return view('admin.domains.index', compact('domains'));
    }

    public function create()
    {
        return view('admin.domains.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:domains,name',
            'description' => 'nullable|string',
        ]);

        Domain::create($request->only('name', 'description'));

        return redirect()->route('admin.domains.index')
            ->with('success', 'Domain added successfully.');
    }

    public function destroy($id)
    {
        $domain = Domain::findOrFail($id);
        $domain->delete();

        return redirect()->route('admin.domains.index')
            ->with('success', 'Domain removed.');
    }
}
