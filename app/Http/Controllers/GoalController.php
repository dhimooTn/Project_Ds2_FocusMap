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
    public function index()
    {
        $goals = Goal::with('tasks')
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->paginate(10);

        return view('goals', compact('goals'));
    }

    public function create()
    {
        return view('goals.create');
    }

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

        $this->checkBadgeAchievements(Auth::id());

        return redirect()->route('goals.index')->with('success', 'Goal created successfully!');
    }

    public function show($id)
    {
        $goal = Goal::with('tasks')
                  ->where('user_id', Auth::id())
                  ->findOrFail($id);

        return view('goals.show', compact('goal'));
    }

    public function edit($id)
    {
        $goal = Goal::where('user_id', Auth::id())
                  ->findOrFail($id);

        return view('goals.edit', compact('goal'));
    }

    public function update(Request $request, $id)
    {
        $goal = Goal::where('user_id', Auth::id())
                  ->findOrFail($id);

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

        if ($goal->tasks()->exists()) {
            $completedTasks = $goal->tasks()->where('status', 'completed')->count();
            $totalTasks = $goal->tasks()->count();
            $newProgress = round(($completedTasks / $totalTasks) * 100);
            $goal->update(['progress' => $newProgress]);
        }

        return redirect()->route('goals.show', $goal)->with('success', 'Goal updated successfully!');
    }

    public function destroy($id)
    {
        $goal = Goal::where('user_id', Auth::id())
                  ->findOrFail($id);

        $goal->tasks()->each(function($task) {
            $task->events()->delete();
            $task->delete();
        });

        $goal->delete();

        return redirect()->route('goals.index')->with('success', 'Goal deleted successfully!');
    }

    public function updateTaskStatus(Request $request, Task $task)
    {
        // Verify task belongs to user's goal
        if ($task->goal->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed'
        ]);

        $task->update(['status' => $request->status]);

        $goal = $task->goal;
        $completedTasks = $goal->tasks()->where('status', 'completed')->count();
        $totalTasks = $goal->tasks()->count();
        $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
        
        $goal->update(['progress' => $progress]);

        $this->checkBadgeAchievements(Auth::id());

        return response()->json([
            'success' => true,
            'progress' => $progress,
            'status_label' => $request->status === 'completed' ? 'Completed' : ($request->status === 'in_progress' ? 'In Progress' : 'Pending'),
            'status_color' => $request->status === 'completed' ? 'success' : ($request->status === 'in_progress' ? 'warning' : 'primary')
        ]);
    }

    protected function checkBadgeAchievements($userId)
    {
        $goalCount = Goal::where('user_id', $userId)->count();
        $completedGoalCount = Goal::where('user_id', $userId)->where('status', 'completed')->count();

        if ($goalCount >= 1 && !Badge_User::where('user_id', $userId)->where('badge_id', 1)->exists()) {
            Badge_User::create([
                'user_id' => $userId,
                'badge_id' => 1,
                'awarded_at' => now()
            ]);
        }
    }
}