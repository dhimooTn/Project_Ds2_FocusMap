<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MindMapController extends Controller
{
    public function index(Request $request)
    {
        // Fetch goals with their tasks for the authenticated user
        $goals = Goal::with('tasks')
            ->where('user_id', Auth::id())
            ->get();

        // Format data for GoJS mind map
        $nodes = [];
        $nodeId = 1;

        foreach ($goals as $goal) {
            // Add goal as parent node
            $nodes[] = [
                'key' => $nodeId,
                'text' => $goal->title,
                'color' => '#4CAF50', // Green for goals
            ];
            $goalNodeId = $nodeId;
            $nodeId++;

            // Add tasks as child nodes
            foreach ($goal->tasks as $task) {
                $nodes[] = [
                    'key' => $nodeId,
                    'parent' => $goalNodeId,
                    'text' => $task->title,
                    'color' => '#2196F3', // Blue for tasks
                ];
                $nodeId++;
            }
        }

        return view('mindMap', compact('nodes'));
    }
}