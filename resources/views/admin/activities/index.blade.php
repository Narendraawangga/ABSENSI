@extends('admin.layout')
@section('admin_content')
<div class="mb-6 flex flex-col md:flex-row justify-between md:items-end gap-4">
    <div>
        <h1 class="text-2xl font-bold text-dark-navy">Laporan Harian (Aktivitas)</h1>
        <p class="text-gray-500 mt-1">Pantau seluruh aktivitas dan dokumentasi anak magang setiap hari.</p>
    </div>
    <form method="GET" action="{{ route('admin.activities') }}" class="flex gap-2">
        <input type="date" name="date" value="{{ $date }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:border-primary-500" onchange="this.form.submit()">
    </form>
</div>

@if(session('success'))
<div class="mb-6 p-4 rounded-lg bg-success/10 border border-success/20 text-success text-sm flex items-center gap-2">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-sm border-b border-gray-200">
                    <th class="py-4 px-6 font-medium">Anak Magang</th>
                    <th class="py-4 px-6 font-medium">Jam Kirim</th>
                    <th class="py-4 px-6 font-medium">Dokumentasi</th>
                    <th class="py-4 px-6 font-medium">Rangkuman Pekerjaan</th>
                    <th class="py-4 px-6 font-medium text-center">Tindakan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($activities as $activity)
                <tr class="hover:bg-gray-50 transition-colors" x-data="{ openModal: false }">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center font-bold text-xs">
                                {{ substr($activity->user->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-medium text-dark-navy">{{ $activity->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $activity->user->internProfile->division ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-gray-700">
                        {{ \Carbon\Carbon::parse($activity->created_at)->format('H:i') }}
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
                    <td class="py-4 px-6 max-w-md">
                        <div class="text-sm text-gray-700 whitespace-pre-wrap line-clamp-3" title="{{ $activity->description }}">{{ $activity->description }}</div>
                        @if($activity->constraints)
                            <div class="mt-2 text-xs text-danger bg-danger/5 p-2 rounded border border-danger/10"><strong>Kendala:</strong> {{ $activity->constraints }}</div>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-center">
                        <button @click="openModal = true" class="text-sm font-medium bg-primary-50 text-primary-600 hover:bg-primary-100 px-3 py-1.5 rounded-lg transition-colors border border-primary-200">
                            {{ $activity->admin_comment ? 'Ubah Komentar' : 'Beri Komentar' }}
                        </button>
                        
                        <!-- Modal Komentar -->
                        <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="openModal = false"></div>
                            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg z-50">
                                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                        <h3 class="text-lg font-semibold leading-6 text-dark-navy mb-4">Tinjau Laporan Harian</h3>
                                        
                                        <div class="bg-gray-50 rounded-lg p-4 mb-4 border border-gray-200">
                                            @if($attachment)
                                                <div class="mb-4">
                                                    <p class="text-xs font-semibold text-gray-500 mb-2">DOKUMENTASI:</p>
                                                    <img src="{{ asset($attachment->file_path) }}" class="w-full h-auto rounded-lg border border-gray-200" alt="Dokumentasi">
                                                </div>
                                            @endif
                                            <p class="text-xs font-semibold text-gray-500 mb-1">LAPORAN:</p>
                                            <p class="text-sm text-gray-800 whitespace-pre-wrap">{{ $activity->description }}</p>
                                            
                                            @if($activity->constraints)
                                            <p class="text-sm text-danger mt-4"><strong>Kendala:</strong> {{ $activity->constraints }}</p>
                                            @endif
                                        </div>
                                        
                                        <form action="{{ route('admin.activities.comment', $activity->id) }}" method="POST" id="form-comment-{{$activity->id}}">
                                            @csrf
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Berikan Komentar/Umpan Balik</label>
                                                <textarea name="admin_comment" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 text-sm" placeholder="Misal: Bagus, tolong perbaiki bagian stylingnya...">{{ $activity->admin_comment }}</textarea>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                        <button type="submit" form="form-comment-{{$activity->id}}" class="inline-flex w-full justify-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 sm:ml-3 sm:w-auto">Simpan</button>
                                        <button type="button" @click="openModal = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            Belum ada aktivitas dilaporkan pada tanggal {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}.
                        </div>
                    </td>
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
@endsection