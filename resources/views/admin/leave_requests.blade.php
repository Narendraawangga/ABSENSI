@extends('admin.layout')
@section('admin_content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-dark-navy">Persetujuan Izin & Sakit</h1>
    <p class="text-gray-500 mt-1">Kelola dan berikan persetujuan untuk pengajuan izin anak magang.</p>
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
                    <th class="py-3 px-6 font-medium">Anak Magang</th>
                    <th class="py-3 px-6 font-medium">Tanggal</th>
                    <th class="py-3 px-6 font-medium">Jenis & Alasan</th>
                    <th class="py-3 px-6 font-medium">Status</th>
                    <th class="py-3 px-6 font-medium text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($requests as $req)
                <tr class="hover:bg-gray-50 transition-colors" x-data="{ openModal: false }">
                    <td class="py-4 px-6 font-medium text-dark-navy">
                        {{ $req->user->name }}
                    </td>
                    <td class="py-4 px-6 text-gray-700">
                        {{ \Carbon\Carbon::parse($req->date_start)->translatedFormat('d M') }}
                        @if($req->date_start != $req->date_end)
                            - {{ \Carbon\Carbon::parse($req->date_end)->translatedFormat('d M') }}
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <div class="font-medium text-gray-800">{{ $req->type }}</div>
                        <div class="text-xs text-gray-500">{{ Str::limit($req->reason, 40) }}</div>
                    </td>
                    <td class="py-4 px-6">
                        @if($req->status == 'Disetujui')
                            <span class="px-2.5 py-1 bg-success/10 text-success rounded-md text-xs font-medium">{{ $req->status }}</span>
                        @elseif($req->status == 'Ditolak')
                            <span class="px-2.5 py-1 bg-danger/10 text-danger rounded-md text-xs font-medium">{{ $req->status }}</span>
                        @else
                            <span class="px-2.5 py-1 bg-warning/10 text-warning rounded-md text-xs font-medium">{{ $req->status }}</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-center">
                        @if($req->status == 'Menunggu')
                            <button @click="openModal = true" class="text-sm font-medium bg-primary-100 text-primary-700 hover:bg-primary-200 px-3 py-1.5 rounded transition-colors">Tinjau</button>
                            
                            <!-- Modal Review -->
                            <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                                <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="openModal = false"></div>
                                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                    <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg z-50">
                                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                            <h3 class="text-lg font-semibold leading-6 text-dark-navy mb-4">Tinjau Pengajuan Izin</h3>
                                            
                                            <div class="bg-gray-50 rounded-lg p-4 mb-4 border border-gray-200">
                                                <p><strong>Nama:</strong> {{ $req->user->name }}</p>
                                                <p><strong>Jenis:</strong> {{ $req->type }}</p>
                                                <p><strong>Tanggal:</strong> {{ $req->date_start }} s/d {{ $req->date_end }}</p>
                                                <p class="mt-2"><strong>Alasan:</strong><br/>{{ $req->reason }}</p>
                                            </div>
                                            
                                            <form action="{{ route('admin.leave-requests.update', $req->id) }}" method="POST" id="form-review-{{$req->id}}">
                                                @csrf
                                                <div class="mb-4">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Keputusan</label>
                                                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 text-sm">
                                                        <option value="Disetujui">Setujui</option>
                                                        <option value="Ditolak">Tolak</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                                                    <textarea name="admin_notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 text-sm"></textarea>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                            <button type="submit" form="form-review-{{$req->id}}" class="inline-flex w-full justify-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 sm:ml-3 sm:w-auto">Simpan</button>
                                            <button type="button" @click="openModal = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Batal</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <span class="text-xs text-gray-400">Sudah ditinjau</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-500">Belum ada pengajuan izin.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection