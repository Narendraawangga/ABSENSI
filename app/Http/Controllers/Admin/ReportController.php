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

    public function export(Request $request)
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

        $fileName = 'Laporan_Kehadiran_' . $month . '_' . $year . '.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $callback = function() use($interns, $attendances) {
            $file = fopen('php://output', 'w');
            $columns = array('Nama Anak Magang', 'Hadir', 'Terlambat', 'Izin/Sakit', 'Tanpa Keterangan (Alpha)', 'Persentase Kehadiran');
            fputcsv($file, $columns);

            foreach ($interns as $intern) {
                $internAtts = $attendances->get($intern->id, collect());
                
                $hadir = $internAtts->where('status', 'hadir')->count();
                $terlambat = $internAtts->where('status', 'terlambat')->count();
                $izin = $internAtts->whereIn('status', ['izin', 'sakit'])->count();
                $alpha = 20 - $internAtts->count();
                $alpha = $alpha > 0 ? $alpha : 0;
                
                $totalKehadiran = $hadir + $terlambat;
                $persentase = $totalKehadiran > 0 ? round(($totalKehadiran / 20) * 100, 1) : 0;
                $persentase = min($persentase, 100) . '%';

                fputcsv($file, array($intern->name, $hadir, $terlambat, $izin, $alpha, $persentase));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
