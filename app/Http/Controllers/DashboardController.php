<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        // Build 7-day activity map: count tasks with deadline in each of the last 7 days.
        $days = collect(range(6, 0))->map(fn (int $i) => today()->subDays($i)->toDateString());
        $tasksByDay = Task::query()
            ->whereDate('deadline', '>=', today()->subDays(6))
            ->whereDate('deadline', '<=', today())
            ->selectRaw('DATE(deadline) as day, COUNT(*) as total')
            ->groupByRaw('DATE(deadline)')
            ->pluck('total', 'day');
        $weeklyActivity = $days->mapWithKeys(fn (string $date) => [
            $date => (int) ($tasksByDay[$date] ?? 0),
        ]);

        return view('dashboard', [
            'activeProjects' => Project::query()
                ->where('status', 'Aktif')
                ->withCount([
                    'tasks',
                    'tasks as completed_tasks_count' => fn (Builder $query): Builder => $query->where('status', 'Selesai'),
                ])
                ->orderBy('deadline')
                ->get(),
            'activeProjectCount' => Project::query()->where('status', 'Aktif')->count(),
            'openTaskCount' => Task::query()->where('status', '!=', 'Selesai')->count(),
            'projectCount' => Project::query()->count(),
            'upcomingTasks' => Task::query()
                ->with('project')
                ->where('status', '!=', 'Selesai')
                ->whereDate('deadline', '>=', today())
                ->orderBy('deadline')
                ->limit(8)
                ->get(),
            'weeklyActivity' => $weeklyActivity,
        ]);
    }
}
