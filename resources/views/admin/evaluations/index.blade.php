@extends('admin.layout')
@section('admin_content')
<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-dark-navy">Evaluasi Kinerja</h1>
        <p class="text-gray-500 mt-1">Lakukan evaluasi bulanan untuk anak magang.</p>
    </div>
    <button @click="$dispatch('open-modal')" class="bg-primary-600 hover:bg-primary-700 text-white px-5 py-2 rounded-lg font-medium shadow-lg shadow-primary-600/30 transition-all flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Buat Evaluasi
    </button>
</div>

@if(session('success'))
<div class="mb-6 p-4 rounded-lg bg-success/10 border border-success/20 text-success text-sm flex items-center gap-2">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    {{ session('success') }}
</div>
@endif
@if($errors->any())
<div class="mb-6 p-4 rounded-lg bg-danger/10 border border-danger/20 text-danger text-sm">
    <ul class="list-disc pl-5">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-sm border-b border-gray-200">
                    <th class="py-3 px-6 font-medium">Anak Magang</th>
                    <th class="py-3 px-6 font-medium">Periode Evaluasi</th>
                    <th class="py-3 px-6 font-medium">Rata-rata Nilai</th>
                    <th class="py-3 px-6 font-medium text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($evaluations as $eval)
                @php
                    $avg = round(($eval->discipline_score + $eval->attendance_score + $eval->responsibility_score + $eval->communication_score + $eval->teamwork_score + $eval->initiative_score + $eval->technical_score + $eval->completion_score) / 8, 1);
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 font-medium text-dark-navy">
                        {{ $eval->intern->name }}
                        <div class="text-xs text-gray-400 mt-1">Oleh: {{ $eval->evaluator->name }}</div>
                    </td>
                    <td class="py-4 px-6 text-gray-700">{{ date('F', mktime(0,0,0,$eval->month,1)) }} {{ $eval->year }}</td>
                    <td class="py-4 px-6">
                        <span class="font-bold {{ $avg >= 80 ? 'text-success' : ($avg >= 60 ? 'text-warning' : 'text-danger') }} text-lg">
                            {{ $avg }}
                        </span>
                        <span class="text-xs text-gray-500">/ 100</span>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <button @click="$dispatch('open-detail-{{ $eval->id }}')" class="text-info hover:bg-info/10 px-3 py-1.5 rounded transition-colors font-medium text-sm">Detail</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-8 text-center text-gray-500">Belum ada riwayat evaluasi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($evaluations->hasPages())
    <div class="p-4 border-t border-gray-100">
        {{ $evaluations->links() }}
    </div>
    @endif
</div>

<!-- Detail Modals -->
@foreach($evaluations as $eval)
<div x-data="{ openDetail: false }" 
     x-show="openDetail" 
     @open-detail-{{ $eval->id }}.window="openDetail = true" 
     @keydown.escape.window="openDetail = false"
     class="relative z-50" style="display: none;">
     
    <div x-show="openDetail" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>
    
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="openDetail" @click.away="openDetail = false" class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-semibold leading-6 text-dark-navy">Detail Evaluasi</h3>
                        <button @click="openDetail = false" class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                        <div>
                            <p class="text-gray-500 mb-1">Nama Anak Magang</p>
                            <p class="font-semibold text-dark-navy">{{ $eval->intern->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 mb-1">Periode</p>
                            <p class="font-semibold text-dark-navy">{{ date('F', mktime(0,0,0,$eval->month,1)) }} {{ $eval->year }}</p>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-4 text-sm">
                        <div class="grid grid-cols-2 gap-y-3 gap-x-4">
                            <div class="flex justify-between"><span class="text-gray-600">Kedisiplinan:</span> <span class="font-semibold">{{ $eval->discipline_score }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600">Kehadiran:</span> <span class="font-semibold">{{ $eval->attendance_score }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600">Tanggung Jawab:</span> <span class="font-semibold">{{ $eval->responsibility_score }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600">Komunikasi:</span> <span class="font-semibold">{{ $eval->communication_score }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600">Kerja Sama Tim:</span> <span class="font-semibold">{{ $eval->teamwork_score }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600">Inisiatif:</span> <span class="font-semibold">{{ $eval->initiative_score }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600">Kemampuan Teknis:</span> <span class="font-semibold">{{ $eval->technical_score }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600">Penyelesaian Tugas:</span> <span class="font-semibold">{{ $eval->completion_score }}</span></div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-200 flex justify-between items-center text-base">
                            <span class="font-bold text-dark-navy">Rata-rata Nilai:</span>
                            @php
                                $avg = round(($eval->discipline_score + $eval->attendance_score + $eval->responsibility_score + $eval->communication_score + $eval->teamwork_score + $eval->initiative_score + $eval->technical_score + $eval->completion_score) / 8, 1);
                            @endphp
                            <span class="font-black text-primary-600">{{ $avg }}</span>
                        </div>
                    </div>
                    
                    @if($eval->notes)
                    <div class="text-sm">
                        <p class="text-gray-500 mb-1">Catatan Tambahan:</p>
                        <div class="p-3 bg-blue-50 text-blue-800 rounded-lg border border-blue-100 italic">{{ $eval->notes }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Form Create Evaluasi -->
<div x-data="{ open: false }" 
     x-show="open" 
     @open-modal.window="open = true" 
     @keydown.escape.window="open = false"
     class="relative z-50" style="display: none;">
     
    <div x-show="open" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>
    
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="open" @click.away="open = false" class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-semibold leading-6 text-dark-navy mb-4">Buat Evaluasi Baru</h3>
                    <form action="{{ route('admin.evaluations.store') }}" method="POST" id="evalForm">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Anak Magang</label>
                                <select name="intern_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 text-sm">
                                    <option value="">-- Pilih Anak Magang --</option>
                                    @foreach($interns as $intern)
                                        <option value="{{ $intern->id }}">{{ $intern->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                                    <select name="month" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                                        @for($m=1; $m<=12; $m++)
                                            <option value="{{ $m }}" {{ date('m') == $m ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                                    <input type="number" name="year" value="{{ date('Y') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-4">
                            <h4 class="text-sm font-semibold text-dark-navy mb-3">Penilaian Kriteria (Skala 0 - 100)</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Kedisiplinan</label>
                                    <input type="number" min="0" max="100" name="discipline_score" required class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Kehadiran</label>
                                    <input type="number" min="0" max="100" name="attendance_score" required class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Tanggung Jawab</label>
                                    <input type="number" min="0" max="100" name="responsibility_score" required class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Komunikasi</label>
                                    <input type="number" min="0" max="100" name="communication_score" required class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Kerja Sama Tim</label>
                                    <input type="number" min="0" max="100" name="teamwork_score" required class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Inisiatif</label>
                                    <input type="number" min="0" max="100" name="initiative_score" required class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Kemampuan Teknis</label>
                                    <input type="number" min="0" max="100" name="technical_score" required class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Penyelesaian Tugas</label>
                                    <input type="number" min="0" max="100" name="completion_score" required class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan (Opsional)</label>
                            <textarea name="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" placeholder="Berikan catatan, masukan, atau pesan untuk perbaikan..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="submit" form="evalForm" class="inline-flex w-full justify-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 sm:ml-3 sm:w-auto">Simpan Evaluasi</button>
                    <button type="button" @click="open = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection