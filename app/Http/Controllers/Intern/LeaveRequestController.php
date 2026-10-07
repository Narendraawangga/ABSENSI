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
            'date_end' => 'required|date|after_or_equal:date_start',
            'reason' => 'required|string',
        ]);

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
