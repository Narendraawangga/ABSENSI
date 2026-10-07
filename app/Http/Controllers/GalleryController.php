<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class GalleryController extends Controller
{
    public function index()
    {
        // Foto dokumentasi terbaru dari semua anak magang
        $photos = DB::table('activity_attachments')
            ->join('activities', 'activity_attachments.activity_id', '=', 'activities.id')
            ->join('users', 'activities.user_id', '=', 'users.id')
            ->leftJoin('intern_profiles', 'users.id', '=', 'intern_profiles.user_id')
            ->whereNull('users.deleted_at')
            ->select(
                'activity_attachments.id',
                'activity_attachments.file_path',
                'activity_attachments.file_name',
                'activities.title',
                'activities.description',
                'activities.date',
                'users.name as intern_name',
                'intern_profiles.division',
                'intern_profiles.university'
            )
            ->orderByDesc('activities.date')
            ->limit(60)
            ->get();

        // Stats
        $totalInterns = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('roles.name', 'intern')
            ->whereNull('users.deleted_at')
            ->count();

        $totalPhotos = DB::table('activity_attachments')->count();

        $totalActivities = DB::table('activities')->count();

        // Intern list
        $interns = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->leftJoin('intern_profiles', 'users.id', '=', 'intern_profiles.user_id')
            ->where('roles.name', 'intern')
            ->whereNull('users.deleted_at')
            ->select(
                'users.id',
                'users.name',
                'users.username',
                'intern_profiles.division',
                'intern_profiles.university',
                'intern_profiles.avatar',
                'intern_profiles.gallery_color',
                'intern_profiles.start_date',
                'intern_profiles.end_date'
            )
            ->get();

        // Aktivitas terbaru untuk kolom tengah
        $recentActivities = DB::table('activities')
            ->join('users', 'activities.user_id', '=', 'users.id')
            ->whereNull('users.deleted_at')
            ->select(
                'activities.id',
                'activities.title',
                'activities.description',
                'activities.date',
                'users.name as intern_name'
            )
            ->orderByDesc('activities.date')
            ->limit(6)
            ->get();

        return view('gallery', compact(
            'photos',
            'totalInterns',
            'totalPhotos',
            'totalActivities',
            'interns',
            'recentActivities'
        ));
    }
}
