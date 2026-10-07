<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Attendance;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::where('user_id', Auth::id())
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Check if user has submitted today
        $hasSubmittedToday = Activity::where('user_id', Auth::id())
            ->whereDate('date', Carbon::today())
            ->exists();

        return view('intern.activities', compact('activities', 'hasSubmittedToday'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string',
            'dokumentasi' => 'required|image|mimes:jpeg,png,jpg|max:5120', // max 5MB
        ]);

        $today = Carbon::today();
        $now = Carbon::now();
        $user = Auth::user();

        // Check if already submitted today
        $exists = Activity::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah mengirimkan laporan hari ini! Anda hanya bisa mengirim 1 laporan per hari.');
        }

        DB::beginTransaction();
        try {
            // Upload dokumentasi
            $file = $request->file('dokumentasi');
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('dokumentasi', $filename, 'public');

            // Create Activity
            $activity = Activity::create([
                'user_id' => $user->id,
                'date' => $today,
                'title' => 'Laporan Harian '.$today->translatedFormat('d F Y'),
                'description' => $request->description,
                'start_time' => '08:00:00', // Auto default
                'end_time' => $now->format('H:i:s'),
                'progress' => 'Selesai',
                'constraints' => $request->constraints,
            ]);

            // Save attachment
            DB::table('activity_attachments')->insert([
                'activity_id' => $activity->id,
                'file_path' => 'storage/dokumentasi/'.$filename,
                'file_name' => $filename,
                'file_type' => $file->getClientMimeType(),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Auto Check-In / Create Attendance
            $schedule = WorkSchedule::where('is_default', true)->first();
            $status = 'hadir';

            // Logika terlambat jika mensubmit di atas batas jam tertentu?
            // User bilang "jika sampai jam 12 malam tidak mengisi maka alfa". Jadi jam berapapun dia kirim (selama di hari itu) akan tercatat hadir.
            // Namun agar rapi, kita anggap selalu hadir jika mengirim hari itu.

            Attendance::updateOrCreate(
                ['user_id' => $user->id, 'date' => $today],
                [
                    'check_in' => '08:00:00',
                    'check_out' => $now->format('H:i:s'),
                    'status' => $status,
                    'duration' => 480, // 8 hours default
                ]
            );

            DB::commit();

            return redirect()->route('intern.activities')->with('success', 'Laporan harian berhasil dikirim dan kehadiran Anda telah tercatat!');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string',
            'dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $user = Auth::user();
        $activity = Activity::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        // Check if the activity was created today
        if (!Carbon::parse($activity->date)->isToday()) {
            return back()->with('error', 'Laporan yang sudah lewat hari tidak dapat diubah lagi.');
        }

        DB::beginTransaction();
        try {
            $activity->update([
                'description' => $request->description,
            ]);

            if ($request->hasFile('dokumentasi')) {
                $file = $request->file('dokumentasi');
                $filename = time().'_'.$file->getClientOriginalName();
                $path = $file->storeAs('dokumentasi', $filename, 'public');

                // Update or create attachment
                $attachment = DB::table('activity_attachments')->where('activity_id', $activity->id)->first();
                
                if ($attachment) {
                    DB::table('activity_attachments')
                        ->where('id', $attachment->id)
                        ->update([
                            'file_path' => 'storage/dokumentasi/'.$filename,
                            'file_name' => $filename,
                            'file_type' => $file->getClientMimeType(),
                            'updated_at' => Carbon::now(),
                        ]);
                } else {
                    DB::table('activity_attachments')->insert([
                        'activity_id' => $activity->id,
                        'file_path' => 'storage/dokumentasi/'.$filename,
                        'file_name' => $filename,
                        'file_type' => $file->getClientMimeType(),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('intern.activities')->with('success', 'Laporan harian berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }
}
