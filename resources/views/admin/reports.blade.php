@extends('admin.layout')
@section('admin_content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
    <div>
        <h1 class="text-2xl font-bold text-dark-navy">Laporan Kehadiran</h1>
        <p class="text-gray-500 mt-1">Rekapitulasi absensi seluruh anak magang.</p>
    </div>
    <button class="bg-primary-600 hover:bg-primary-700 text-white px-5 py-2 rounded-lg font-medium shadow-lg shadow-primary-600/30 transition-all flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
        Export PDF
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="p-4 border-b border-gray-100 bg-gray-50">
        <form method="GET" action="{{ route('admin.reports') }}" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Bulan</label>
                <select name="month" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-primary-500">
                    @for($i=1; $i<=12; $i++)
                        <option value="{{ $i }}" {{ request('month', now()->month) == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Tahun</label>
                <select name="year" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-primary-500">
                    @for($i=now()->year-1; $i<=now()->year+1; $i++)
                        <option value="{{ $i }}" {{ request('year', now()->year) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50 font-medium">Filter</button>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white text-gray-500 text-sm border-b border-gray-200">
                    <th class="py-3 px-6 font-medium">Tanggal</th>
                    <th class="py-3 px-6 font-medium">Anak Magang</th>
                    <th class="py-3 px-6 font-medium">Check-In</th>
                    <th class="py-3 px-6 font-medium">Check-Out</th>
                    <th class="py-3 px-6 font-medium">Durasi</th>
                    <th class="py-3 px-6 font-medium">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($attendances as $att)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-3 px-6 font-medium text-dark-navy">
                        {{ \Carbon\Carbon::parse($att->date)->translatedFormat('d M Y') }}
                    </td>
                    <td class="py-3 px-6 text-gray-800">
                        {{ $att->user->name }}
                    </td>
                    <td class="py-3 px-6 text-gray-600">
                        {{ $att->check_in ? \Carbon\Carbon::parse($att->check_in)->format('H:i') : '-' }}
                    </td>
                    <td class="py-3 px-6 text-gray-600">
                        {{ $att->check_out ? \Carbon\Carbon::parse($att->check_out)->format('H:i') : '-' }}
                    </td>
                    <td class="py-3 px-6 text-gray-600">
                        {{ $att->duration ? round($att->duration / 60, 1) . 'h' : '-' }}
                    </td>
                    <td class="py-3 px-6">
                        @if($att->status == 'hadir')
                            <span class="px-2 py-1 bg-success/10 text-success rounded text-xs font-medium">Hadir</span>
                        @elseif($att->status == 'terlambat')
                            <span class="px-2 py-1 bg-warning/10 text-warning rounded text-xs font-medium">Terlambat</span>
                        @else
                            <span class="px-2 py-1 bg-danger/10 text-danger rounded text-xs font-medium">{{ ucfirst($att->status) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-gray-500">Data laporan kosong pada bulan ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($attendances->hasPages())
    <div class="p-4 border-t border-gray-100">
        {{ $attendances->links() }}
    </div>
    @endif
</div>
@endsection