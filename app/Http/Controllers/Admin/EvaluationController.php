<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    public function index()
    {
        $evaluations = Evaluation::with(['intern', 'evaluator'])->latest()->paginate(10);
        $interns = User::whereHas('role', function ($q) {
            $q->where('slug', 'intern');
        })->where('is_active', true)->get();

        return view('admin.evaluations.index', compact('evaluations', 'interns'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'intern_id' => 'required|exists:users,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer',
            'discipline_score' => 'required|numeric|min:0|max:100',
            'attendance_score' => 'required|numeric|min:0|max:100',
            'responsibility_score' => 'required|numeric|min:0|max:100',
            'communication_score' => 'required|numeric|min:0|max:100',
            'teamwork_score' => 'required|numeric|min:0|max:100',
            'initiative_score' => 'required|numeric|min:0|max:100',
            'technical_score' => 'required|numeric|min:0|max:100',
            'completion_score' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        $exists = Evaluation::where('intern_id', $request->intern_id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->exists();

        if ($exists) {
            return back()->withErrors(['intern_id' => 'Anak magang ini sudah dievaluasi untuk bulan dan tahun tersebut. Evaluasi hanya dapat dilakukan 1 kali per bulan.'])->withInput();
        }

        Evaluation::create(array_merge($request->all(), [
            'evaluator_id' => Auth::id(),
        ]));

        return back()->with('success', 'Evaluasi anak magang berhasil disimpan.');
    }
}
