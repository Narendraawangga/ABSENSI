@extends('admin.layout')
@section('admin_content')
<div class="mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-dark-navy flex items-center gap-2">
            <i class="bi bi-megaphone text-primary-600"></i> Papan Pengumuman
        </h1>
        <p class="text-gray-500 mt-1">Siarkan informasi atau pengumuman ke seluruh dashboard anak magang.</p>
    </div>
    
    <button @click="$dispatch('open-modal')" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-medium shadow flex items-center gap-2">
        <i class="bi bi-megaphone-fill text-lg"></i>
        Buat Pengumuman
    </button>
</div>



<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-4">
        @forelse($announcements as $announcement)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 relative overflow-hidden transition-all hover:shadow-md">
            @if($announcement->priority == 'high')
                <div class="absolute top-0 left-0 w-1 h-full bg-red-500"></div>
            @elseif($announcement->priority == 'medium')
                <div class="absolute top-0 left-0 w-1 h-full bg-yellow-500"></div>
            @else
                <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
            @endif

            <div class="flex justify-between items-start mb-2">
                <h3 class="font-bold text-lg text-dark-navy pl-2">{{ $announcement->title }}</h3>
                <form action="{{ route('admin.announcements.destroy', $announcement->id) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:bg-red-50 p-1.5 rounded-md transition-colors" title="Hapus">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
            
            <div class="pl-2">
                <p class="text-gray-700 text-sm whitespace-pre-wrap mb-3">{{ $announcement->content }}</p>
                <div class="flex items-center gap-3 text-xs text-gray-400">
                    <span class="flex items-center gap-1"><i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($announcement->created_at)->translatedFormat('d M Y, H:i') }}</span>
                    <span class="flex items-center gap-1"><i class="bi bi-person-fill"></i> {{ $announcement->creator->name ?? 'Admin' }}</span>
                </div>
            </div>
        </div>
        @empty
        <div class="py-12 flex flex-col items-center justify-center text-gray-400 bg-white rounded-xl shadow-sm border border-gray-200 border-dashed">
            <i class="bi bi-mailbox2 text-4xl mb-3 text-gray-300"></i>
            <p>Belum ada pengumuman yang dibuat.</p>
        </div>
        @endforelse
    </div>
    
    <div>
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-5">
            <h3 class="font-bold text-blue-900 mb-2 flex items-center gap-2">
                <i class="bi bi-info-circle-fill text-blue-500"></i> Tips Pengumuman
            </h3>
            <p class="text-sm text-blue-800 mb-3">Pengumuman yang Anda buat akan langsung muncul di halaman Dashboard Anak Magang.</p>
            <ul class="text-xs text-blue-700 space-y-2 list-disc pl-4">
                <li>Gunakan prioritas <strong>Tinggi (Merah)</strong> untuk informasi darurat (misal: Libur mendadak).</li>
                <li>Gunakan prioritas <strong>Sedang (Kuning)</strong> untuk pengingat (misal: Jangan lupa isi logbook).</li>
                <li>Gunakan prioritas <strong>Rendah (Biru)</strong> untuk info umum.</li>
            </ul>
        </div>
    </div>
</div>

<!-- Modal Create -->
<div x-data="{ open: false }" 
     x-show="open" 
     @open-modal.window="open = true" 
     @keydown.escape.window="open = false"
     class="relative z-50" style="display: none;">
     
    <div x-show="open" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>
    
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="open" @click.away="open = false" class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg font-semibold leading-6 text-dark-navy mb-4">Buat Pengumuman Baru</h3>
                            <form action="{{ route('admin.announcements.store') }}" method="POST" id="announcementForm">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Pengumuman <span class="text-danger">*</span></label>
                                        <input type="text" name="title" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 text-sm" placeholder="Contoh: Apel Pagi Besok">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat Prioritas</label>
                                        <select name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 text-sm">
                                            <option value="low">Rendah (Biru) - Info Umum</option>
                                            <option value="medium">Sedang (Kuning) - Peringatan/Reminder</option>
                                            <option value="high">Tinggi (Merah) - Penting/Darurat</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Isi Pesan <span class="text-danger">*</span></label>
                                        <textarea name="content" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 text-sm" placeholder="Tuliskan pesan lengkap pengumuman di sini..."></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="submit" form="announcementForm" class="inline-flex w-full justify-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 sm:ml-3 sm:w-auto">Siarkan Sekarang</button>
                    <button type="button" @click="open = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
