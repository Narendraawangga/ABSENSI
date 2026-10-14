@extends('intern.layout')
@section('intern_content')


<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-dark-navy">Selamat pagi, {{ explode(' ', auth()->user()->name)[0] }} 👋</h1>
        <p class="text-gray-500 mt-2">Hari ini: {{ now()->locale('id')->translatedFormat('l, d F Y') }}</p>
    </div>
    
    <!-- Tombol Check In/Out Dihilangkan -->
</div>

<!-- Announcements Section -->
@if($announcements && $announcements->count() > 0)
<div class="mb-8">
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach($announcements as $ann)
        @php
            $bg = 'bg-blue-50'; $border = 'border-blue-200'; $text = 'text-blue-800'; $icon = 'text-blue-500';
            if($ann->priority == 'high') {
                $bg = 'bg-red-50'; $border = 'border-red-200'; $text = 'text-red-800'; $icon = 'text-red-500';
            } elseif($ann->priority == 'medium') {
                $bg = 'bg-yellow-50'; $border = 'border-yellow-200'; $text = 'text-yellow-800'; $icon = 'text-yellow-500';
            }
        @endphp
        <div class="{{ $bg }} {{ $border }} border rounded-2xl p-5 relative overflow-hidden shadow-sm hover:-translate-y-1 transition-transform duration-300">
            <!-- Decorative circle -->
            <div class="absolute -right-4 -top-4 w-16 h-16 rounded-full opacity-20 {{ $bg == 'bg-blue-50' ? 'bg-blue-400' : ($bg == 'bg-red-50' ? 'bg-red-400' : 'bg-yellow-400') }}"></div>
            
            <div class="flex gap-3 mb-2">
                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center flex-shrink-0 shadow-sm {{ $icon }}">
                    @if($ann->priority == 'high')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    @elseif($ann->priority == 'medium')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    @endif
                </div>
                <div>
                    <h3 class="font-bold {{ $text }} leading-tight">{{ $ann->title }}</h3>
                    <p class="text-[11px] opacity-70 mt-0.5 {{ $text }}">{{ \Carbon\Carbon::parse($ann->created_at)->locale('id')->diffForHumans() }} &bull; Oleh {{ $ann->creator->name ?? 'Admin' }}</p>
                </div>
            </div>
            <div class="{{ $text }} text-sm mt-3 whitespace-pre-wrap pl-1 relative z-10">{{ $ann->content }}</div>
        </div>
        @endforeach
    </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- Aktivitas Hari Ini -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold text-dark-navy">Aktivitas Hari Ini</h2>
                <button class="text-sm font-medium text-primary-600 hover:text-primary-700">+ Tambah Laporan</button>
            </div>
            
            @if($activities->count() > 0)
                <div class="space-y-4 mt-6">
                    @foreach($activities as $activity)
                    <div class="p-4 border border-gray-100 rounded-lg bg-gray-50">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-semibold text-dark-navy">{{ $activity->title }}</h3>
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $activity->progress == 'Selesai' ? 'bg-success/10 text-success' : 'bg-warning/10 text-warning' }}">
                                {{ $activity->progress }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">{{ $activity->description }}</p>
                        <div class="text-xs text-gray-400 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ \Carbon\Carbon::parse($activity->start_time)->format('H:i') }} - {{ $activity->end_time ? \Carbon\Carbon::parse($activity->end_time)->format('H:i') : 'Sekarang' }}
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-10 text-center border-2 border-dashed border-gray-200 rounded-lg bg-gray-50 mt-4">
                    <div class="p-3 bg-white rounded-full shadow-sm mb-3">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-gray-900 font-medium">Belum ada aktivitas</h3>
                    <p class="text-gray-500 text-sm mt-1">Silakan lapor aktivitas pertama Anda hari ini.</p>
                </div>
            @endif
        </div>

        <!-- Latest Evaluation -->
        @if($latestEvaluation)
        @php
            $eval = $latestEvaluation;
            $avg = round(($eval->discipline_score + $eval->attendance_score + $eval->responsibility_score + $eval->communication_score + $eval->teamwork_score + $eval->initiative_score + $eval->technical_score + $eval->completion_score) / 8, 1);
        @endphp
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mt-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold text-dark-navy">Hasil Evaluasi Terbaru</h2>
                <span class="text-sm font-medium px-2.5 py-1 rounded-md bg-primary-50 text-primary-700">Periode: {{ date('F', mktime(0,0,0,$eval->month,1)) }} {{ $eval->year }}</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Average Score Circle -->
                <div class="flex flex-col items-center justify-center border-r border-gray-100 pr-0 md:pr-6">
                    <div class="text-gray-500 text-sm font-medium mb-2">Nilai Rata-rata</div>
                    <div class="w-24 h-24 rounded-full flex flex-col items-center justify-center border-4 border-primary-100 bg-white">
                        <span class="text-3xl font-black {{ $avg >= 80 ? 'text-success' : ($avg >= 60 ? 'text-warning' : 'text-danger') }}">{{ $avg }}</span>
                    </div>
                </div>
                
                <!-- Detailed Scores -->
                <div class="md:col-span-2 grid grid-cols-2 gap-y-3 gap-x-6 text-sm">
                    <div class="flex justify-between border-b border-gray-50 pb-2"><span class="text-gray-500">Kedisiplinan</span> <span class="font-bold text-dark-navy">{{ $eval->discipline_score }}</span></div>
                    <div class="flex justify-between border-b border-gray-50 pb-2"><span class="text-gray-500">Kehadiran</span> <span class="font-bold text-dark-navy">{{ $eval->attendance_score }}</span></div>
                    <div class="flex justify-between border-b border-gray-50 pb-2"><span class="text-gray-500">Tanggung Jawab</span> <span class="font-bold text-dark-navy">{{ $eval->responsibility_score }}</span></div>
                    <div class="flex justify-between border-b border-gray-50 pb-2"><span class="text-gray-500">Komunikasi</span> <span class="font-bold text-dark-navy">{{ $eval->communication_score }}</span></div>
                    <div class="flex justify-between border-b border-gray-50 pb-2"><span class="text-gray-500">Kerja Sama</span> <span class="font-bold text-dark-navy">{{ $eval->teamwork_score }}</span></div>
                    <div class="flex justify-between border-b border-gray-50 pb-2"><span class="text-gray-500">Inisiatif</span> <span class="font-bold text-dark-navy">{{ $eval->initiative_score }}</span></div>
                    <div class="flex justify-between border-b border-gray-50 pb-2"><span class="text-gray-500">Teknis</span> <span class="font-bold text-dark-navy">{{ $eval->technical_score }}</span></div>
                    <div class="flex justify-between border-b border-gray-50 pb-2"><span class="text-gray-500">Penyelesaian</span> <span class="font-bold text-dark-navy">{{ $eval->completion_score }}</span></div>
                </div>
            </div>
            
            @if($eval->notes)
            <div class="mt-4 p-3 bg-blue-50/50 rounded border border-blue-100">
                <p class="text-xs text-gray-500 font-medium mb-1">Catatan Pembimbing:</p>
                <p class="text-sm text-gray-700 italic">{{ $eval->notes }}</p>
            </div>
            @endif
        </div>
        @endif
    </div>
    
    <div class="space-y-6">
        <!-- Status Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-dark-navy mb-4">Status Kehadiran</h2>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <span class="text-gray-500">Status</span>
                    @if($attendance)
                        @if($attendance->status == 'hadir')
                            <span class="px-2 py-1 bg-success/10 text-success text-xs font-semibold rounded-md uppercase">Hadir</span>
                        @elseif($attendance->status == 'terlambat')
                            <span class="px-2 py-1 bg-warning/10 text-warning text-xs font-semibold rounded-md uppercase">Terlambat</span>
                        @endif
                    @else
                        <span class="px-2 py-1 bg-gray-100 text-gray-500 text-xs font-semibold rounded-md uppercase">Belum Absen</span>
                    @endif
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Durasi Kerja</span>
                    <span class="font-semibold text-dark-navy">
                        {{ $attendance && $attendance->duration ? round($attendance->duration / 60, 1) . ' Jam' : '0 Jam' }}
                    </span>
                </div>
            </div>
        </div>
        <!-- Avatar Upload Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 relative overflow-hidden">
            <!-- Decorative gradient -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary-100 rounded-full blur-3xl opacity-50 transform translate-x-1/2 -translate-y-1/2"></div>
            
            <h2 class="text-lg font-bold text-dark-navy mb-4 relative z-10">Pengaturan Galeri</h2>
            <p class="text-xs text-gray-500 mb-4 relative z-10">Atur foto profil dan warna latar belakang kartu Anda di halaman depan.</p>
            
            <form action="{{ route('intern.avatar.update') }}" method="POST" enctype="multipart/form-data" class="relative z-10" x-data="{ imageUrl: '{{ auth()->user()->internProfile && auth()->user()->internProfile->avatar ? asset(auth()->user()->internProfile->avatar) : '' }}' }">
                @csrf
                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-4">
                        <!-- Current Avatar Preview -->
                        <div class="w-20 h-20 rounded-2xl overflow-hidden border-4 border-gray-50 shadow-md flex-shrink-0 bg-gray-100 flex items-center justify-center">
                            <template x-if="imageUrl">
                                <img :src="imageUrl" class="w-full h-full object-cover" alt="Avatar">
                            </template>
                            <template x-if="!imageUrl">
                                <span class="text-3xl font-bold text-gray-300">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </template>
                        </div>
                        
                        <div class="w-full">
                            <label class="block text-xs text-gray-500 mb-1">Pilih Foto Profil (Opsional)</label>
                            <input type="file" name="avatar" id="avatar" @change="imageUrl = URL.createObjectURL($event.target.files[0])" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100" accept="image/png, image/jpeg, image/jpg">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Warna Latar Galeri</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="gallery_color" value="{{ auth()->user()->internProfile->gallery_color ?? '#1a5fa8' }}" class="w-10 h-10 rounded cursor-pointer border-0 p-0 bg-transparent">
                            <span class="text-sm text-gray-500">Pilih warna kesukaan Anda</span>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 px-4 rounded-lg transition-colors text-sm shadow-md mt-2">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection