<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function history()
    {
        $attendances = Attendance::where('user_id', Auth::id())
            ->orderBy('date', 'desc')
            ->paginate(15);

        return view('intern.attendance_history', compact('attendances'));
    }
}
