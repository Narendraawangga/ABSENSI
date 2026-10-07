<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $totalInterns = User::whereHas('role', function ($q) {
            $q->where('slug', 'intern');
        })->where('is_active', true)->count();

        $attendances = Attendance::with('user.internProfile')
            ->whereDate('date', $today)
            ->get();

        $present = $attendances->whereIn('status', ['hadir', 'terlambat'])->count();
        $late = $attendances->where('status', 'terlambat')->count();
        $absentCount = $totalInterns - $attendances->count();

        // Get latest activities
        $latestActivities = Activity::with('user')
            ->whereDate('date', $today)
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalInterns', 'present', 'late', 'absentCount', 'attendances', 'latestActivities'
        ));
    }
}
