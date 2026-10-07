<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        $interns = User::with(['internProfile'])->whereHas('role', function ($q) {
            $q->where('slug', 'intern');
        })->get();

        $attendances = Attendance::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get()
            ->groupBy('user_id');

        $reports = $interns->map(function ($intern) use ($attendances) {
            $internAtts = $attendances->get($intern->id, collect());

            return [
                'user' => $intern,
                'hadir' => $internAtts->where('status', 'hadir')->count(),
                'terlambat' => $internAtts->where('status', 'terlambat')->count(),
                'izin' => $internAtts->whereIn('status', ['izin', 'sakit'])->count(),
                'alpha' => 20 - $internAtts->count(), // Asumsi 20 hari kerja sebulan
            ];
        });

        return view('admin.reports.index', compact('reports', 'month', 'year'));
    }
}
