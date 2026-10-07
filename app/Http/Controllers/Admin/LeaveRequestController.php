<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $requests = LeaveRequest::with('user')->latest()->paginate(10);

        return view('admin.leave_requests', compact('requests'));
    }

    public function update(Request $request, $id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);

        $request->validate([
            'status' => 'required|in:Disetujui,Ditolak',
        ]);

        $leaveRequest->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', 'Status pengajuan izin berhasil diperbarui.');
    }
}
