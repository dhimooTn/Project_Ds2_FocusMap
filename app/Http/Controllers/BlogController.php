<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Journal;
use App\Models\Goal;
use Carbon\Carbon;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->journals()->with('goal');
        
        // Apply filters
        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'today':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', 
                        [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereBetween('created_at', 
                        [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                    break;
            }
        }
        
        $journals = $query->latest()->paginate(10);
        $goals = auth()->user()->goals()->get();
        
        if ($request->ajax()) {
            return response()->json([
                'html' => view('blog.partials.entries', compact('journals'))->render()
            ]);
        }
        
        return view('blog', compact('journals', 'goals'));
    }

    public function create()
    {
        $goals = auth()->user()->goals()->get();
        return view('blog.create', compact('goals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'goal_id' => 'nullable|exists:goals,id',
            'tags' => 'nullable|string',
            'motivation' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $journal = auth()->user()->journals()->create($validated);

        if ($request->hasFile('image')) {
            $journal->addMediaFromRequest('image')
                   ->toMediaCollection('journal-images');
        }

        return redirect()->route('blog.index')
               ->with('success', 'Journal entry created successfully!');
    }

    public function show(Journal $journal)
    {
        if(request()->ajax()) {
            return response()->json([
                'html' => view('blog.partials.show_modal', compact('journal'))->render()
            ]);
        }
        
        return view('blog.show', compact('journal'));
    }

    public function edit(Journal $journal)
    {
        $goals = auth()->user()->goals()->get();
        $motivations = ['inspiration', 'progrès', 'défi', 'réussite', 'apprentissage'];
        
        if(request()->ajax()) {
            return response()->json([
                'html' => view('blog.partials.edit_modal', compact('journal', 'goals', 'motivations'))->render()
            ]);
        }
        
        return view('blog.edit', compact('journal', 'goals', 'motivations'));
    }

    public function update(Request $request, Journal $journal)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'goal_id' => 'nullable|exists:goals,id',
            'tags' => 'nullable|string',
            'motivation' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'remove_image' => 'nullable|boolean',
        ]);

        $journal->update($validated);

        if ($request->has('remove_image')) {
            $journal->clearMediaCollection('journal-images');
        }

        if ($request->hasFile('image')) {
            $journal->clearMediaCollection('journal-images');
            $journal->addMediaFromRequest('image')
                   ->toMediaCollection('journal-images');
        }

        return redirect()->route('blog.index')
               ->with('success', 'Journal entry updated successfully!');
    }

    public function destroy(Journal $journal)
    {
        $journal->delete();
        return redirect()->route('blog.index')
               ->with('success', 'Journal entry deleted successfully!');
    }
}