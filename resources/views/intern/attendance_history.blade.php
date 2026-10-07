@extends('intern.layout')
@section('intern_content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-dark-navy">Riwayat Kehadiran</h1>
    <p class="text-gray-500 mt-1">Pantau catatan kehadiran dan jam kerja Anda.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-sm border-b border-gray-100">
                    <th class="py-4 px-6 font-medium">Tanggal</th>
                    <th class="py-4 px-6 font-medium">Durasi</th>
                    <th class="py-4 px-6 font-medium">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($attendances as $att)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 font-medium text-dark-navy">
                        {{ \Carbon\Carbon::parse($att->date)->translatedFormat('l, d M Y') }}
                    </td>
                    <td class="py-4 px-6 text-gray-700">
                        {{ $att->duration ? round($att->duration / 60, 1) . ' Jam' : '-' }}
                    </td>
                    <td class="py-4 px-6">
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
                    <td colspan="3" class="py-8 text-center text-gray-500">Belum ada riwayat kehadiran.</td>
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