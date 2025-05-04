<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Task;
use App\Models\CalendarEvent;
use App\Models\Badge_User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoalController extends Controller
{
    /**
     * Display a listing of the goals.
     */
    public function index()
    {
        $goals = Goal::with('tasks')
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->paginate(10);

        return view('goals.index', compact('goals'));
    }

    /**
     * Show the form for creating a new goal.
     */
    public function create()
    {
        return view('goals.create');
    }

    /**
     * Store a newly created goal in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'visibility' => 'required|in:private,public',
            'location' => 'nullable|string',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'tasks' => 'nullable|array',
            'tasks.*.title' => 'required_with:tasks|string|max:255',
            'tasks.*.description' => 'nullable|string',
            'tasks.*.due_date' => 'nullable|date',
            'tasks.*.priority' => 'nullable|in:low,medium,high',
            'tasks.*.has_event' => 'nullable|boolean',
            'tasks.*.event_start' => 'nullable|required_with:tasks.*.has_event|date',
            'tasks.*.event_end' => 'nullable|required_with:tasks.*.has_event|date|after:tasks.*.event_start',
        ]);

        // Create the goal
        $goal = Goal::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'visibility' => $validated['visibility'],
            'location' => $validated['location'],
            'lat' => $validated['lat'],
            'lng' => $validated['lng'],
            'status' => 'active',
            'progress' => 0,
        ]);

        // Create tasks if any
        if (!empty($validated['tasks'])) {
            foreach ($validated['tasks'] as $taskData) {
                $task = Task::create([
                    'goal_id' => $goal->id,
                    'title' => $taskData['title'],
                    'description' => $taskData['description'] ?? null,
                    'due_date' => $taskData['due_date'] ?? null,
                    'priority' => $taskData['priority'] ?? 'medium',
                    'status' => 'pending',
                ]);

                // Create calendar event if needed
                if (!empty($taskData['has_event']) && !empty($taskData['event_start'])) {
                    CalendarEvent::create([
                        'user_id' => Auth::id(),
                        'task_id' => $task->id,
                        'title' => $taskData['title'],
                        'description' => $taskData['description'] ?? null,
                        'start_time' => $taskData['event_start'],
                        'end_time' => $taskData['event_end'],
                    ]);
                }
            }
        }

        // Check for badge achievements
        $this->checkBadgeAchievements(Auth::id());

        return redirect()->route('goals.index')->with('success', 'Objectif créé avec succès!');
    }

    /**
     * Display the specified goal.
     */
    public function show(Goal $goal)
    {
        $this->authorize('view', $goal);

        $goal->load('tasks');
        return view('goals.show', compact('goal'));
    }

    /**
     * Show the form for editing the specified goal.
     */
    public function edit(Goal $goal)
    {
        $this->authorize('update', $goal);

        $goal->load('tasks');
        return view('goals.edit', compact('goal'));
    }

    /**
     * Update the specified goal in storage.
     */
    public function update(Request $request, Goal $goal)
    {
        $this->authorize('update', $goal);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'visibility' => 'required|in:private,public',
            'location' => 'nullable|string',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'status' => 'required|in:active,completed,archived',
            'progress' => 'required|integer|min:0|max:100',
        ]);

        $goal->update($validated);

        // Recalculate progress if tasks exist
        if ($goal->tasks()->exists()) {
            $completedTasks = $goal->tasks()->where('status', 'completed')->count();
            $totalTasks = $goal->tasks()->count();
            $newProgress = round(($completedTasks / $totalTasks) * 100);
            
            $goal->update(['progress' => $newProgress]);
        }

        return redirect()->route('goals.show', $goal)->with('success', 'Objectif mis à jour avec succès!');
    }

    /**
     * Remove the specified goal from storage.
     */
    public function destroy(Goal $goal)
    {
        $this->authorize('delete', $goal);

        // Delete associated tasks and events
        $goal->tasks()->each(function($task) {
            $task->events()->delete();
            $task->delete();
        });

        $goal->delete();

        return redirect()->route('goals.index')->with('success', 'Objectif supprimé avec succès!');
    }

    /**
     * Mark tasks as complete/incomplete via AJAX
     */
    public function updateTaskStatus(Request $request, Task $task)
    {
        $this->authorize('update', $task->goal);

        $request->validate([
            'status' => 'required|in:pending,completed'
        ]);

        $task->update(['status' => $request->status]);

        // Update goal progress
        $goal = $task->goal;
        $completedTasks = $goal->tasks()->where('status', 'completed')->count();
        $totalTasks = $goal->tasks()->count();
        $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
        
        $goal->update(['progress' => $progress]);

        // Check for badge achievements
        $this->checkBadgeAchievements(Auth::id());

        return response()->json([
            'success' => true,
            'progress' => $progress,
            'status_label' => $request->status === 'completed' ? 'Complété' : 'En cours',
            'status_color' => $request->status === 'completed' ? 'success' : 'primary'
        ]);
    }

    /**
     * Generate markdown for mind map
     */
    public function markmap()
    {
        $goals = Goal::with('tasks')
                    ->where('user_id', Auth::id())
                    ->get();

        $markdown = "# Mes Objectifs\n\n";

        foreach ($goals as $goal) {
            $markdown .= "## {$goal->title} (Progression: {$goal->progress}%)\n";
            foreach ($goal->tasks as $task) {
                $status = $task->status === 'completed' ? '✓' : '◯';
                $markdown .= "- {$status} {$task->title}\n";
            }
            $markdown .= "\n";
        }

        return view('mindMap', compact('markdown'));
    }

    /**
     * Check for badge achievements
     */
    protected function checkBadgeAchievements($userId)
    {
        // Example badge checks - customize based on your badge logic
        $goalCount = Goal::where('user_id', $userId)->count();
        $completedGoalCount = Goal::where('user_id', $userId)->where('status', 'completed')->count();

        // First Goal Badge
        if ($goalCount >= 1 && !Badge_User::where('user_id', $userId)->where('badge_id', 1)->exists()) {
            Badge_User::create([
                'user_id' => $userId,
                'badge_id' => 1,
                'earned_at' => now()
            ]);
        }

        // Goal Master Badge
        if ($completedGoalCount >= 5 && !Badge_User::where('user_id', $userId)->where('badge_id', 2)->exists()) {
            Badge_User::create([
                'user_id' => $userId,
                'badge_id' => 2,
                'earned_at' => now()
            ]);
        }

        // Task Completer Badge
        $completedTaskCount = Task::whereHas('goal', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('status', 'completed')->count();

        if ($completedTaskCount >= 10 && !Badge_User::where('user_id', $userId)->where('badge_id', 3)->exists()) {
            Badge_User::create([
                'user_id' => $userId,
                'badge_id' => 3,
                'earned_at' => now()
            ]);
        }
    }
}