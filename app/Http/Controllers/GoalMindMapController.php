<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;

class GoalMindmapController extends Controller
{
    /**
     * Display interactive mindmap of goals and tasks
     */
    public function index()
    {
        $goals = Goal::with(['tasks' => function($query) {
                $query->orderBy('due_date', 'asc');
            }])
            ->where('user_id', auth()->id())
            ->get();

        return view('mindMap', [
            'goals' => $goals,
            'averageProgress' => $goals->avg('progress') ?? 0
        ]);
    }

    /**
     * API endpoint for fetching mindmap data (JSON)
     */
    public function apiIndex()
    {
        $goals = Goal::with(['tasks' => function($query) {
                $query->orderBy('due_date', 'asc');
            }])
            ->where('user_id', auth()->id())
            ->get()
            ->map(function($goal) {
                return [
                    'id' => $goal->id,
                    'name' => $goal->title,
                    'progress' => $goal->progress,
                    'visibility' => $goal->visibility,
                    'status' => $goal->status,
                    'tasks' => $goal->tasks->map(function($task) {
                        return [
                            'id' => $task->id,
                            'name' => $task->title,
                            'completed' => $task->status === \App\Models\Task::STATUS_COMPLETED,
                            'due_date' => optional($task->due_date)->format('Y-m-d'),
                            'priority' => $task->priority,
                            'status' => $task->status
                        ];
                    })
                ];
            });

        return response()->json([
            'data' => $goals,
            'meta' => [
                'total_goals' => count($goals),
                'total_tasks' => $goals->sum(fn($goal) => count($goal['tasks'])),
                'completed_tasks' => $goals->sum(fn($goal) => collect($goal['tasks'])->where('completed', true)->count()),
                'pending_tasks' => $goals->sum(fn($goal) => collect($goal['tasks'])->where('completed', false)->count()),
            ]
        ]);
    }

    /**
     * Export mindmap as Markdown
     */
    public function exportMarkdown()
    {
        $goals = Goal::with('tasks')
            ->where('user_id', auth()->id())
            ->get();

        $markdown = "# My Goals Mindmap\n\n";
        
        foreach ($goals as $goal) {
            $markdown .= "## {$goal->title} (Progress: {$goal->progress}%)\n";
            
            foreach ($goal->tasks as $task) {
                $status = $task->status === \App\Models\Task::STATUS_COMPLETED ? '✓' : '◯';
                $priority = $task->priority ? "[{$task->priority}] " : "";
                $dueDate = $task->due_date ? " (due: {$task->due_date->format('Y-m-d')})" : "";
                $markdown .= "- {$status} {$priority}{$task->title}{$dueDate}\n";
            }
            
            $markdown .= "\n";
        }

        return response($markdown)
            ->header('Content-Type', 'text/markdown')
            ->header('Content-Disposition', 'attachment; filename="goals_mindmap.md"');
    }
}