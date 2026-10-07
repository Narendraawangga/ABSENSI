@extends('admin.layout')
@section('admin_content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-dark-navy">Dashboard Admin</h1>
    <p class="text-gray-500 mt-1">Overview statistik magang hari ini: {{ now()->translatedFormat('l, d F Y') }}</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
        <div class="p-3 rounded-lg bg-primary-100 text-primary-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium">Total Magang</p>
            <p class="text-2xl font-bold text-dark-navy">{{ $totalInterns }}</p>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
        <div class="p-3 rounded-lg bg-success/20 text-success">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium">Hadir Hari Ini</p>
            <p class="text-2xl font-bold text-dark-navy">{{ $present }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
        <div class="p-3 rounded-lg bg-warning/20 text-warning">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium">Terlambat</p>
            <p class="text-2xl font-bold text-dark-navy">{{ $late }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
        <div class="p-3 rounded-lg bg-danger/20 text-danger">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium">Tidak Hadir (Belum Absen)</p>
            <p class="text-2xl font-bold text-dark-navy">{{ $absentCount }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- Tabel Monitoring Kehadiran Hari Ini -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-lg font-bold text-dark-navy">Monitoring Kehadiran Hari Ini</h2>
                <a href="#" class="text-sm font-medium text-primary-600 hover:text-primary-700">Lihat Semua</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-sm">
                            <th class="py-3 px-6 font-medium">Nama Anak Magang</th>
                            <th class="py-3 px-6 font-medium">Divisi</th>
                            <th class="py-3 px-6 font-medium">Jam Masuk</th>
                            <th class="py-3 px-6 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($attendances as $att)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-6 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center font-bold text-xs">
                                    {{ substr($att->user->name, 0, 1) }}
                                </div>
                                <span class="font-medium text-dark-navy">{{ $att->user->name }}</span>
                            </td>
                            <td class="py-3 px-6 text-gray-600">{{ $att->user->internProfile->division ?? '-' }}</td>
                            <td class="py-3 px-6 text-gray-600">{{ \Carbon\Carbon::parse($att->check_in)->format('H:i') }}</td>
                            <td class="py-3 px-6">
                                @if($att->status == 'hadir')
                                    <span class="px-2.5 py-1 bg-success/10 text-success rounded-md text-xs font-medium">Hadir</span>
                                @elseif($att->status == 'terlambat')
                                    <span class="px-2.5 py-1 bg-warning/10 text-warning rounded-md text-xs font-medium">Terlambat</span>
                                @else
                                    <span class="px-2.5 py-1 bg-danger/10 text-danger rounded-md text-xs font-medium">{{ ucfirst($att->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-gray-500">Belum ada data kehadiran hari ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="space-y-6">
        <!-- Aktivitas Terbaru Timeline -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-dark-navy mb-6">Aktivitas Terbaru</h2>
            
            <div class="relative border-l border-gray-200 ml-3 space-y-6">
                @forelse($latestActivities as $activity)
                <div class="mb-6 ml-6 relative">
                    <span class="absolute -left-[35px] bg-primary-100 text-primary-600 w-6 h-6 rounded-full flex items-center justify-center border-2 border-white shadow">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </span>
                    <h3 class="text-sm font-semibold text-dark-navy">{{ $activity->user->name }}</h3>
                    <p class="text-xs text-gray-500 mb-1">{{ \Carbon\Carbon::parse($activity->created_at)->format('H:i') }} — Mengirim aktivitas harian</p>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-100 mt-2">
                        <p class="text-sm text-gray-700 font-medium">{{ $activity->title }}</p>
                    </div>
                </div>
                @empty
                <div class="ml-6 text-sm text-gray-500">Belum ada aktivitas dilaporkan hari ini.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection