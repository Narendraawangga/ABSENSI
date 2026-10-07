@extends('intern.layout')
@section('intern_content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-dark-navy">Pengajuan Izin & Sakit</h1>
        <p class="text-gray-500 mt-1">Ajukan permohonan izin, sakit, atau keperluan lainnya di sini.</p>
    </div>
    <button @click="$dispatch('open-modal')" class="bg-primary-600 hover:bg-primary-700 text-white px-5 py-2 rounded-lg font-medium shadow-lg shadow-primary-600/30 transition-all flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Buat Pengajuan
    </button>
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
                <tr class="bg-gray-50 text-gray-500 text-sm border-b border-gray-100">
                    <th class="py-4 px-6 font-medium">Tanggal</th>
                    <th class="py-4 px-6 font-medium">Jenis Izin</th>
                    <th class="py-4 px-6 font-medium">Alasan</th>
                    <th class="py-4 px-6 font-medium">Status</th>
                    <th class="py-4 px-6 font-medium">Catatan Admin</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($requests as $req)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 font-medium text-dark-navy">
                        {{ \Carbon\Carbon::parse($req->date_start)->translatedFormat('d M Y') }}
                        @if($req->date_start != $req->date_end)
                            - {{ \Carbon\Carbon::parse($req->date_end)->translatedFormat('d M Y') }}
                        @endif
                    </td>
                    <td class="py-4 px-6 text-gray-700">{{ $req->type }}</td>
                    <td class="py-4 px-6 text-gray-500">{{ $req->reason }}</td>
                    <td class="py-4 px-6">
                        @if($req->status == 'Disetujui')
                            <span class="px-2.5 py-1 bg-success/10 text-success rounded-md text-xs font-medium">{{ $req->status }}</span>
                        @elseif($req->status == 'Ditolak')
                            <span class="px-2.5 py-1 bg-danger/10 text-danger rounded-md text-xs font-medium">{{ $req->status }}</span>
                        @else
                            <span class="px-2.5 py-1 bg-warning/10 text-warning rounded-md text-xs font-medium">{{ $req->status }}</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-gray-500 italic">
                        {{ $req->admin_notes ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-500">Belum ada riwayat pengajuan izin.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Form -->
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
                            <h3 class="text-lg font-semibold leading-6 text-dark-navy mb-4">Pengajuan Izin Baru</h3>
                            <form action="{{ route('intern.leave-requests.store') }}" method="POST" id="leaveForm">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Izin</label>
                                        <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 text-sm">
                                            <option value="Sakit">Sakit</option>
                                            <option value="Izin">Izin Keperluan Pribadi</option>
                                            <option value="Keperluan Kampus">Keperluan Kampus</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                                            <input type="date" name="date_start" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                                            <input type="date" name="date_end" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 text-sm">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Detail</label>
                                        <textarea name="reason" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 text-sm" placeholder="Jelaskan alasan izin Anda secara detail..."></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="submit" form="leaveForm" class="inline-flex w-full justify-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 sm:ml-3 sm:w-auto">Kirim Pengajuan</button>
                    <button type="button" @click="open = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection