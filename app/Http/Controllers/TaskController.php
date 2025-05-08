<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Goal;
use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Store a new task for a specific goal
     */
    public function store(Request $request, Goal $goal)
    {
        $this->authorize('update', $goal);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'has_event' => 'nullable|boolean',
            'event_start' => 'nullable|required_with:has_event|date',
            'event_end' => 'nullable|required_with:has_event|date|after:event_start',
        ]);

        $task = $goal->tasks()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'due_date' => $validated['due_date'],
            'priority' => $validated['priority'],
            'status' => 'pending',
        ]);

        if ($validated['has_event'] ?? false) {
            CalendarEvent::create([
                'user_id' => Auth::id(),
                'task_id' => $task->id,
                'title' => $validated['title'],
                'description' => $validated['description'],
                'start_time' => $validated['event_start'],
                'end_time' => $validated['event_end'],
            ]);
        }

        $goal->refresh()->calculateProgress();

        return redirect()->back()->with('success', 'Tâche ajoutée avec succès!');
    }

    /**
     * Store a standalone task (from dashboard)
     */
    public function storeStandalone(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'goal_id' => 'nullable|exists:goals,id',
            'has_event' => 'nullable|boolean',
            'event_start' => 'nullable|required_with:has_event|date',
            'event_end' => 'nullable|required_with:has_event|date|after:event_start',
        ]);

        // Important: We need to make sure the user owns the goal OR is authorized to update it
        if (!empty($validated['goal_id'])) {
            $goal = Goal::findOrFail($validated['goal_id']);
            
            // Check if the goal belongs to the authenticated user
            if ($goal->user_id !== Auth::id()) {
                $this->authorize('update', $goal);
            }
        }

        $task = Task::create([
            'goal_id' => $validated['goal_id'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'due_date' => $validated['due_date'],
            'priority' => $validated['priority'],
            'status' => 'pending',
            'user_id' => Auth::id(), // Add this line to ensure tasks are associated with the current user
        ]);

        if ($validated['has_event'] ?? false) {
            CalendarEvent::create([
                'user_id' => Auth::id(),
                'task_id' => $task->id,
                'title' => $validated['title'],
                'description' => $validated['description'],
                'start_time' => $validated['event_start'],
                'end_time' => $validated['event_end'],
            ]);
        }

        if ($task->goal) {
            $task->goal->calculateProgress();
        }

        return redirect()->back()->with('success', 'Tâche ajoutée avec succès!');
    }

    /**
     * Update the specified task
     */
    public function update(Request $request, Goal $goal, Task $task)
    {
        $this->authorize('update', $goal);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,in_progress,completed',
            'has_event' => 'nullable|boolean',
            'event_start' => 'nullable|required_with:has_event|date',
            'event_end' => 'nullable|required_with:has_event|date|after:event_start',
        ]);

        $task->update($validated);

        // Gestion des événements calendrier
        if ($validated['has_event'] ?? false) {
            $eventData = [
                'title' => $validated['title'],
                'description' => $validated['description'],
                'start_time' => $validated['event_start'],
                'end_time' => $validated['event_end'],
            ];

            if ($task->calendarEvent) {
                $task->calendarEvent->update($eventData);
            } else {
                $eventData['user_id'] = Auth::id();
                $eventData['task_id'] = $task->id;
                CalendarEvent::create($eventData);
            }
        } elseif ($task->calendarEvent) {
            $task->calendarEvent->delete();
        }

        $goal->calculateProgress();

        return redirect()->back()->with('success', 'Tâche mise à jour avec succès!');
    }

    /**
     * Remove the specified task
     */
    public function destroy(Goal $goal, Task $task)
    {
        $this->authorize('update', $goal);

        $task->calendarEvent()->delete();
        $task->delete();

        $goal->calculateProgress();

        return redirect()->back()->with('success', 'Tâche supprimée avec succès!');
    }

    /**
     * Update task status via AJAX
     */
    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('update', $task->goal);

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed'
        ]);

        $task->update(['status' => $request->status]);

        $goal = $task->goal;
        $goal->calculateProgress();

        return response()->json([
            'success' => true,
            'progress' => $goal->progress,
            'status_label' => $this->getStatusLabel($request->status),
            'status_color' => $this->getStatusColor($request->status)
        ]);
    }

    protected function getStatusLabel($status)
    {
        return [
            'completed' => 'Complété',
            'in_progress' => 'En cours',
            'pending' => 'En attente'
        ][$status];
    }

    protected function getStatusColor($status)
    {
        return [
            'completed' => 'success',
            'in_progress' => 'warning',
            'pending' => 'primary'
        ][$status];
    }
}