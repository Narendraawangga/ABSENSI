<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $requests = LeaveRequest::where('user_id', Auth::id())->latest()->paginate(10);

        return view('intern.leave_requests', compact('requests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'date_start' => 'required|date',
            'date_end' => [
                'required',
                'date',
                'after_or_equal:date_start',
                function ($attribute, $value, $fail) use ($request) {
                    $start = \Carbon\Carbon::parse($request->date_start);
                    $end = \Carbon\Carbon::parse($value);
                    if ($start->diffInDays($end) > 2) {
                        $fail('Pengajuan dalam satu periode maksimal 3 hari.');
                    }
                },
            ],
            'reason' => 'required|string',
        ]);

        $start = \Carbon\Carbon::parse($request->date_start);
        $existingLeave = LeaveRequest::where('user_id', Auth::id())
            ->whereMonth('date_start', $start->month)
            ->whereYear('date_start', $start->year)
            ->exists();

        if ($existingLeave) {
            return back()->with('error', 'Anda sudah mengajukan cuti/izin pada bulan ini. Pengajuan hanya diperbolehkan satu kali dalam sebulan.');
        }

        LeaveRequest::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'date_start' => $request->date_start,
            'date_end' => $request->date_end,
            'reason' => $request->reason,
            'status' => 'Menunggu',
        ]);

        return back()->with('success', 'Pengajuan izin berhasil dikirim.');
    }
}
