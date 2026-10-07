<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Attendance;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        $activities = Activity::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->latest()
            ->get();

        $latestEvaluation = \App\Models\Evaluation::where('intern_id', $user->id)
            ->latest()
            ->first();

        return view('intern.dashboard', compact('attendance', 'activities', 'latestEvaluation'));
    }

    public function checkIn(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();
        $now = Carbon::now();

        $existing = Attendance::where('user_id', $user->id)->whereDate('date', $today)->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah melakukan check-in hari ini.');
        }

        $schedule = WorkSchedule::where('is_default', true)->first();
        $status = 'hadir';

        if ($schedule) {
            $scheduleTimeIn = Carbon::createFromFormat('H:i:s', $schedule->time_in);
            $scheduleTimeIn->addMinutes($schedule->late_tolerance);

            if ($now->format('H:i:s') > $scheduleTimeIn->format('H:i:s')) {
                $status = 'terlambat';
            }
        }

        Attendance::create([
            'user_id' => $user->id,
            'date' => $today,
            'check_in' => $now->format('H:i:s'),
            'status' => $status,
        ]);

        return back()->with('success', 'Berhasil Check-In pada '.$now->format('H:i'));
    }

    public function checkOut(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();
        $now = Carbon::now();

        $attendance = Attendance::where('user_id', $user->id)->whereDate('date', $today)->first();

        if (! $attendance || ! $attendance->check_in) {
            return back()->with('error', 'Anda belum melakukan check-in.');
        }

        if ($attendance->check_out) {
            return back()->with('error', 'Anda sudah melakukan check-out hari ini.');
        }

        $checkInTime = Carbon::createFromFormat('H:i:s', $attendance->check_in);
        $durationMinutes = $checkInTime->diffInMinutes($now);

        $attendance->update([
            'check_out' => $now->format('H:i:s'),
            'duration' => $durationMinutes,
        ]);

        return back()->with('success', 'Berhasil Check-Out pada '.$now->format('H:i'));
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gallery_color' => 'nullable|string|max:7',
        ]);

        $user = Auth::user();
        $internProfile = $user->internProfile;

        if (!$internProfile) {
            return back()->with('error', 'Profil intern tidak ditemukan.');
        }

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($internProfile->avatar && file_exists(public_path($internProfile->avatar))) {
                unlink(public_path($internProfile->avatar));
            }

            $file = $request->file('avatar');
            $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            // Move to public/storage/avatars
            $file->move(public_path('storage/avatars'), $filename);
            
            $internProfile->avatar = 'storage/avatars/' . $filename;
        }

        if ($request->filled('gallery_color')) {
            $internProfile->gallery_color = $request->gallery_color;
        }

        $internProfile->save();

        return back()->with('success', 'Pengaturan galeri berhasil diperbarui.');
    }
}
