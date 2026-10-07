@extends('intern.layout')
@section('intern_content')
<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-dark-navy">Laporan Rangkuman Harian</h1>
        <p class="text-gray-500 mt-1">Isi rangkuman pekerjaan Anda hari ini. <strong class="text-danger">Maksimal 1 kali kirim per hari.</strong> Jika tidak mengisi sampai jam 23:59, akan otomatis dihitung Alpha.</p>
    </div>
    
    @if(!$hasSubmittedToday)
    <button @click="$dispatch('open-modal')" class="bg-primary-600 hover:bg-primary-700 text-white px-5 py-2.5 rounded-lg font-medium shadow-lg shadow-primary-600/30 transition-all flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Buat Laporan Hari Ini
    </button>
    @else
    <button disabled class="bg-gray-300 text-gray-500 px-5 py-2.5 rounded-lg font-medium cursor-not-allowed flex items-center gap-2">
        <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        Laporan Selesai
    </button>
    @endif
</div>

@if(session('success'))
<div class="mb-6 p-4 rounded-lg bg-success/10 border border-success/20 text-success text-sm flex items-center gap-2">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-6 p-4 rounded-lg bg-danger/10 border border-danger/20 text-danger text-sm flex items-center gap-2">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    {{ session('error') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-sm border-b border-gray-100">
                    <th class="py-4 px-6 font-medium">Tanggal</th>
                    <th class="py-4 px-6 font-medium">Rangkuman Pekerjaan</th>
                    <th class="py-4 px-6 font-medium">Dokumentasi</th>
                    <th class="py-4 px-6 font-medium">Komentar Pembimbing</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($activities as $activity)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6">
                        <div class="font-medium text-dark-navy">{{ \Carbon\Carbon::parse($activity->date)->translatedFormat('l, d M Y') }}</div>
                        <div class="text-xs text-gray-500 mt-1">Dikirim jam: {{ \Carbon\Carbon::parse($activity->created_at)->format('H:i') }}</div>
                    </td>
                    <td class="py-4 px-6 max-w-sm">
                        <div class="text-sm text-gray-700 whitespace-pre-wrap">{{ $activity->description }}</div>
                        @if($activity->constraints)
                            <div class="mt-2 text-xs text-danger bg-danger/5 p-2 rounded border border-danger/10"><strong>Kendala:</strong> {{ $activity->constraints }}</div>
                        @endif
                        
                        @if(\Carbon\Carbon::parse($activity->date)->isToday())
                            <div class="mt-3">
                                <button @click="$dispatch('open-edit-modal-{{ $activity->id }}')" class="text-xs bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded flex items-center inline-flex gap-1 font-medium transition-colors shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit Laporan
                                </button>
                            </div>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        @php 
                            $attachment = DB::table('activity_attachments')->where('activity_id', $activity->id)->first();
                        @endphp
                        @if($attachment)
                            <a href="{{ asset($attachment->file_path) }}" target="_blank" class="block w-16 h-16 rounded overflow-hidden border border-gray-200 hover:opacity-80 transition-opacity">
                                <img src="{{ asset($attachment->file_path) }}" alt="Dokumentasi" class="w-full h-full object-cover">
                            </a>
                        @else
                            <span class="text-gray-400 italic text-xs">Tidak ada</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-gray-500 italic">
                        {{ $activity->admin_comment ?? 'Belum ada komentar' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-8 text-center text-gray-500">Belum ada aktivitas yang dicatat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($activities->hasPages())
    <div class="p-4 border-t border-gray-100">
        {{ $activities->links() }}
    </div>
    @endif
</div>

<!-- Edit Modals (for editable activities) -->
@foreach($activities as $activity)
    @if(\Carbon\Carbon::parse($activity->date)->isToday())
    <div x-data="{ openEdit: false }" 
         x-show="openEdit" 
         @open-edit-modal-{{ $activity->id }}.window="openEdit = true" 
         @keydown.escape.window="openEdit = false"
         class="relative z-50" style="display: none;">
         
        <div x-show="openEdit" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>
        
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="openEdit" @click.away="openEdit = false" class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg font-semibold leading-6 text-dark-navy mb-4">Edit Rangkuman Pekerjaan</h3>
                                <form action="{{ route('intern.activities.update', $activity->id) }}" method="POST" id="editForm-{{ $activity->id }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Rangkuman Pekerjaan <span class="text-danger">*</span></label>
                                            <textarea name="description" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 text-sm">{{ $activity->description }}</textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Upload Dokumentasi Baru (Opsional)</label>
                                            <input type="file" name="dokumentasi" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm file:mr-4 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                                            <p class="text-xs text-gray-500 mt-1">Abaikan jika tidak ingin mengubah dokumentasi sebelumnya.</p>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="submit" form="editForm-{{ $activity->id }}" class="inline-flex w-full justify-center rounded-md bg-yellow-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-yellow-600 sm:ml-3 sm:w-auto">Simpan Perubahan</button>
                        <button type="button" @click="openEdit = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach

<!-- Modal Form -->
@if(!$hasSubmittedToday)
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
                            <h3 class="text-lg font-semibold leading-6 text-dark-navy mb-4">Laporan Rangkuman Pekerjaan</h3>
                            <div class="bg-info/10 border border-info/20 text-info text-xs p-3 rounded-lg mb-4">
                                Waktu dan Tanggal otomatis akan disesuaikan dengan saat Anda mengirim laporan ini ({{ now()->translatedFormat('d M Y, H:i') }}).
                            </div>
                            <form action="{{ route('intern.activities.store') }}" method="POST" id="activityForm" enctype="multipart/form-data">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Rangkuman Pekerjaan Hari Ini <span class="text-danger">*</span></label>
                                        <textarea name="description" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 text-sm" placeholder="Ceritakan apa saja yang telah Anda kerjakan hari ini..."></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Upload Dokumentasi (Foto/Screenshot) <span class="text-danger">*</span></label>
                                        <input type="file" name="dokumentasi" accept="image/*" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm file:mr-4 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maksimal 5MB.</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Kendala (Opsional)</label>
                                        <textarea name="constraints" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 text-sm" placeholder="Apakah ada kendala saat mengerjakan tugas hari ini?"></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="submit" form="activityForm" class="inline-flex w-full justify-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 sm:ml-3 sm:w-auto">Kirim Laporan</button>
                    <button type="button" @click="open = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection