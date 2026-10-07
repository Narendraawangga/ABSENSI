<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));

        $activities = Activity::with('user.internProfile')
            ->whereDate('date', $date)
            ->latest()
            ->paginate(15);

        return view('admin.activities.index', compact('activities', 'date'));
    }

    public function addComment(Request $request, $id)
    {
        $request->validate([
            'admin_comment' => 'required|string',
        ]);

        $activity = Activity::findOrFail($id);
        $activity->update([
            'admin_comment' => $request->admin_comment,
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan pada aktivitas tersebut.');
    }
}
