<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Auth::user()->business->branches()->paginate(10);
        return view('branches.index', compact('branches'));
    }

    public function create()
    {
        return view('branches.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'phone' => 'required|string',
        ]);

        Auth::user()->business->branches()->create($request->all());

        return redirect()->route('branches.index')->with('success', 'Branch added successfully.');
    }

    public function edit(Branch $branch)
    {
        if ($branch->business_id !== Auth::user()->business->id) abort(403);
        return view('branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        if ($branch->business_id !== Auth::user()->business->id) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'phone' => 'required|string',
        ]);

        $branch->update($request->all());

        return redirect()->route('branches.index')->with('success', 'Branch updated.');
    }

    public function destroy(Branch $branch)
    {
        if ($branch->business_id !== Auth::user()->business->id) abort(403);
        $branch->delete();
        return redirect()->route('branches.index')->with('success', 'Branch deleted.');
    }

    public function setMain(Branch $branch)
    {
        if ($branch->business_id !== Auth::user()->business->id) abort(403);
        
        // Set all to false, then set selected to true
        Auth::user()->business->branches()->update(['is_main' => false]);
        $branch->update(['is_main' => true]);

        return back()->with('success', 'Main branch updated.');
    }
}