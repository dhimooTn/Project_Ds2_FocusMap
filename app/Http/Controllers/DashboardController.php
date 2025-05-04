<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goal;
use App\Models\Badge_User;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get all goals for the user to avoid multiple queries
        $goals = Goal::where('user_id', $user->id)->get();

        // Separate active and completed goals
        $activeGoals = $goals->where('status', 'active');
        $completedGoals = $goals->where('status', 'completed');

        // Count badges
        $badges = Badge_User::where('user_id', $user->id)->count();

        // Calculate average progress
        $averageProgress = Goal::where('user_id', $user->id)
            ->whereNotNull('progress')
            ->avg('progress');

        // Get today's tasks for the first goal (if exists)
        $goal = $goals->first(); // You can further improve if multiple goals are handled differently
        $todaysTasks = collect(); // Default to empty collection

        if ($goal) {
            $todaysTasks = Task::where('goal_id', $goal->id)
                ->whereDate('due_date', now()->toDateString())
                ->get();
        }

        return view('dashboard', compact(
            'user',
            'activeGoals',
            'completedGoals',
            'badges',
            'averageProgress',
            'todaysTasks'
        ));
    }
}
