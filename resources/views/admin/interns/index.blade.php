@extends('admin.layout')
@section('admin_content')
<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-dark-navy">Data Anak Magang</h1>
        <p class="text-gray-500 mt-1">Kelola data anak magang yang terdaftar di sistem.</p>
    </div>
    <button @click="$dispatch('open-modal')" class="bg-primary-600 hover:bg-primary-700 text-white px-5 py-2 rounded-lg font-medium shadow-lg shadow-primary-600/30 transition-all flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
        Tambah Magang
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
    <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
        <div class="relative w-64">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none"><path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            </span>
            <input type="text" class="w-full py-2 pl-10 pr-4 text-gray-700 bg-white border border-gray-300 rounded-lg focus:border-primary-500 focus:outline-none focus:ring focus:ring-primary-500/20" placeholder="Cari nama atau NIM...">
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white text-gray-500 text-sm border-b border-gray-200">
                    <th class="py-3 px-6 font-medium">Profil</th>
                    <th class="py-3 px-6 font-medium">Username</th>
                    <th class="py-3 px-6 font-medium">Divisi & Universitas</th>
                    <th class="py-3 px-6 font-medium">Periode</th>
                    <th class="py-3 px-6 font-medium">Status</th>
                    <th class="py-3 px-6 font-medium text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($interns as $intern)
                <tr class="hover:bg-gray-50 transition-colors" x-data="{ openEdit: false }">
                    <td class="py-4 px-6 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center font-bold">
                            {{ substr($intern->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-medium text-dark-navy">{{ $intern->name }}</div>
                            <div class="text-xs text-gray-500">NIM: {{ $intern->internProfile->nim ?? '-' }}</div>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-gray-700 font-medium">
                        {{ $intern->username }}
                    </td>
                    <td class="py-4 px-6">
                        <div class="font-medium text-gray-800">{{ $intern->internProfile->division ?? '-' }}</div>
                        <div class="text-xs text-gray-500">{{ $intern->internProfile->university ?? '-' }}</div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="text-gray-700 text-xs">
                            {{ $intern->internProfile->start_date ? \Carbon\Carbon::parse($intern->internProfile->start_date)->format('d M Y') : '-' }} s/d <br> 
                            {{ $intern->internProfile->end_date ? \Carbon\Carbon::parse($intern->internProfile->end_date)->format('d M Y') : '-' }}
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        @if($intern->is_active)
                            <span class="px-2.5 py-1 bg-success/10 text-success rounded-md text-xs font-medium">Aktif</span>
                        @else
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-500 rounded-md text-xs font-medium">Nonaktif</span>
                        @endif
                    </td>
                                        <td class="py-4 px-6 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button @click="openEdit = true" class="p-1.5 text-warning hover:bg-warning/10 rounded transition-colors border border-transparent hover:border-warning/30" title="Edit Data & Password">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            
                            <form action="{{ route('admin.interns.destroy', $intern->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anak magang ini secara permanen? Data tidak dapat dikembalikan.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-danger hover:bg-danger/10 rounded transition-colors border border-transparent hover:border-danger/30" title="Hapus Permanen">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>

                        <!-- Modal Edit Intern -->
                        <div x-show="openEdit" class="fixed inset-0 z-50 overflow-y-auto" style="display: none; text-align: left;">
                            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="openEdit = false"></div>
                            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl z-50">
                                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                        <h3 class="text-lg font-semibold leading-6 text-dark-navy mb-4">Edit Data & Akses ({{ $intern->name }})</h3>
                                        
                                        <form action="{{ route('admin.interns.update', $intern->id) }}" method="POST" id="form-edit-{{$intern->id}}">
                                            @csrf
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                                    <input type="text" name="name" value="{{ $intern->name }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:border-primary-500">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Username (Untuk Login)</label>
                                                    <input type="text" name="username" value="{{ $intern->username }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:border-primary-500">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">NIM / NISN</label>
                                                    <input type="text" name="nim" value="{{ $intern->internProfile->nim ?? '' }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:border-primary-500">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Universitas / Sekolah</label>
                                                    <input type="text" name="university" value="{{ $intern->internProfile->university ?? '' }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:border-primary-500">
                                                </div>
                                                <div class="md:col-span-2">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Divisi / Penempatan</label>
                                                    <input type="text" name="division" value="{{ $intern->internProfile->division ?? '' }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:border-primary-500">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai Magang</label>
                                                    <input type="date" name="start_date" value="{{ $intern->internProfile->start_date ?? '' }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:border-primary-500">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai Magang</label>
                                                    <input type="date" name="end_date" value="{{ $intern->internProfile->end_date ?? '' }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:border-primary-500">
                                                </div>
                                            </div>

                                            <div class="mt-6 border-t border-gray-200 pt-4">
                                                <h4 class="text-sm font-bold text-dark-navy mb-3">Kontrol Keamanan & Status</h4>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Reset Password</label>
                                                        <input type="password" name="password" placeholder="Kosongkan jika tidak ingin diubah" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:border-primary-500 bg-gray-50 focus:bg-white transition-colors">
                                                        <p class="text-xs text-gray-500 mt-1">Hanya diisi jika anak magang lupa password.</p>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Akun</label>
                                                        <select name="is_active" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:border-primary-500">
                                                            <option value="1" {{ $intern->is_active ? 'selected' : '' }}>Aktif</option>
                                                            <option value="0" {{ !$intern->is_active ? 'selected' : '' }}>Nonaktif (Suspend / Selesai)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                        <button type="submit" form="form-edit-{{$intern->id}}" class="inline-flex w-full justify-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 sm:ml-3 sm:w-auto">Simpan Perubahan</button>
                                        <button type="button" @click="openEdit = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-gray-500">Belum ada data anak magang.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($interns->hasPages())
    <div class="p-4 border-t border-gray-100">
        {{ $interns->links() }}
    </div>
    @endif
</div>

<!-- Modal Tambah Intern -->
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
                    <h3 class="text-lg font-semibold leading-6 text-dark-navy mb-4">Buat Akun Anak Magang Baru</h3>
                    
                    <form action="{{ route('admin.interns.store') }}" method="POST" id="form-create">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:border-primary-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Username (Untuk Login)</label>
                                <input type="text" name="username" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:border-primary-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NIM / NISN</label>
                                <input type="text" name="nim" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:border-primary-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Universitas / Sekolah</label>
                                <input type="text" name="university" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:border-primary-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Divisi / Penempatan</label>
                                <input type="text" name="division" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:border-primary-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai Magang</label>
                                <input type="date" name="start_date" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:border-primary-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai Magang</label>
                                <input type="date" name="end_date" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:border-primary-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password Awal (Sementara)</label>
                                <input type="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:border-primary-500">
                                <p class="text-xs text-gray-500 mt-1">Anak magang akan menggunakan password ini untuk login pertama kali.</p>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="submit" form="form-create" class="inline-flex w-full justify-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 sm:ml-3 sm:w-auto">Buat Akun</button>
                    <button type="button" @click="open = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection