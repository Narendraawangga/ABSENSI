@extends('admin.layout')
@section('admin_content')
<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-dark-navy">Laporan Kehadiran & Rekap</h1>
        <p class="text-gray-500 mt-1">Rekapitulasi kehadiran anak magang bulanan.</p>
    </div>
    <div class="flex gap-2">
        <form method="GET" action="{{ route('admin.reports') }}" class="flex gap-2">
            <select name="month" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                @for($m=1; $m<=12; $m++)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endfor
            </select>
            <select name="year" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                @for($y=date('Y')-1; $y<=date('Y')+1; $y++)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <button type="submit" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200">Filter</button>
        </form>
        <button class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-medium shadow flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Export Excel
        </button>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-sm border-b border-gray-200">
                    <th class="py-3 px-6 font-medium">Nama Anak Magang</th>
                    <th class="py-3 px-6 font-medium text-center">Hadir</th>
                    <th class="py-3 px-6 font-medium text-center">Terlambat</th>
                    <th class="py-3 px-6 font-medium text-center">Izin/Sakit</th>
                    <th class="py-3 px-6 font-medium text-center">Tanpa Keterangan (Alpha)</th>
                    <th class="py-3 px-6 font-medium text-center">Persentase Kehadiran</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($reports as $report)
                @php
                    $totalKehadiran = $report['hadir'] + $report['terlambat'];
                    $persentase = $totalKehadiran > 0 ? round(($totalKehadiran / 20) * 100, 1) : 0; // Asumsi 20 hari kerja
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 font-medium text-dark-navy">{{ $report['user']->name }}</td>
                    <td class="py-4 px-6 text-center text-success font-semibold">{{ $report['hadir'] }}</td>
                    <td class="py-4 px-6 text-center text-warning font-semibold">{{ $report['terlambat'] }}</td>
                    <td class="py-4 px-6 text-center text-info font-semibold">{{ $report['izin'] }}</td>
                    <td class="py-4 px-6 text-center text-danger font-semibold">{{ $report['alpha'] > 0 ? $report['alpha'] : 0 }}</td>
                    <td class="py-4 px-6 text-center">
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-1">
                            <div class="{{ $persentase >= 80 ? 'bg-success' : ($persentase >= 50 ? 'bg-warning' : 'bg-danger') }} h-2.5 rounded-full" style="width: {{ min($persentase, 100) }}%"></div>
                        </div>
                        <span class="text-xs text-gray-500">{{ min($persentase, 100) }}%</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-gray-500">Data laporan tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection