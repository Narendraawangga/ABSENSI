@extends('admin.layout')
@section('admin_content')
<div class="mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-dark-navy">Galeri Dokumentasi</h1>
        <p class="text-gray-500 mt-1">Kelola foto-foto dokumentasi yang tampil di halaman depan galeri magang.</p>
    </div>
    
    <button @click="$dispatch('open-modal')" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-medium shadow flex items-center gap-2">
        <i class="bi bi-cloud-arrow-up-fill text-lg"></i>
        Tambah Foto
    </button>
</div>



@if($errors->any())
<div class="mb-6 p-4 rounded-lg bg-danger/10 border border-danger/20 text-danger text-sm">
    <div class="flex items-center gap-2 font-semibold mb-2">
        <i class="bi bi-exclamation-triangle-fill text-lg"></i>
        Terdapat kesalahan:
    </div>
    <ul class="list-disc list-inside ml-7">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    @forelse($photos as $photo)
    <div class="group relative bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="aspect-[4/3] w-full overflow-hidden bg-gray-100">
            <img src="{{ $photo['src'] }}" alt="Dokumentasi" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
        </div>
        
        <div class="p-3">
            <div class="text-xs text-gray-400 mb-1 flex justify-between items-center">
                <span>{{ date('d M Y, H:i', $photo['date']) }}</span>
            </div>
            <div class="text-sm font-medium text-gray-700 truncate" title="{{ $photo['filename'] }}">
                {{ $photo['filename'] }}
            </div>
        </div>

        <!-- Overlay Action -->
        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
            <form action="{{ route('admin.gallery-docs.destroy', $photo['filename']) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto ini dari galeri?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white w-8 h-8 rounded-full flex items-center justify-center shadow-lg transition-colors" title="Hapus Foto">
                    <i class="bi bi-trash-fill"></i>
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-full py-12 flex flex-col items-center justify-center text-gray-400 bg-white rounded-xl shadow-sm border border-gray-200 border-dashed">
        <i class="bi bi-images text-4xl mb-3"></i>
        <p>Belum ada foto dokumentasi di galeri.</p>
        <button @click="$dispatch('open-modal')" class="mt-4 text-primary-600 font-medium text-sm hover:underline">Upload Foto Pertama</button>
    </div>
    @endforelse
</div>

<!-- Modal Form -->
<div x-data="{ open: false, isDragging: false }" 
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
                            <h3 class="text-lg font-semibold leading-6 text-dark-navy mb-4">Upload Foto Galeri</h3>
                            <form action="{{ route('admin.gallery-docs.store') }}" method="POST" id="uploadForm" enctype="multipart/form-data">
                                @csrf
                                <div class="space-y-4">
                                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center transition-colors hover:border-primary-500 hover:bg-primary-50/50 cursor-pointer"
                                         @dragover.prevent="isDragging = true"
                                         @dragleave.prevent="isDragging = false"
                                         @drop.prevent="isDragging = false; document.getElementById('photos').files = $event.dataTransfer.files"
                                         :class="{'border-primary-500 bg-primary-50/50': isDragging}"
                                         onclick="document.getElementById('photos').click()">
                                        <i class="bi bi-cloud-arrow-up text-4xl text-gray-400 mb-3 block"></i>
                                        <p class="text-sm text-gray-600 font-medium mb-1">Klik untuk memilih foto atau seret ke sini</p>
                                        <p class="text-xs text-gray-400">Bisa memilih lebih dari 1 foto (JPG, PNG). Maks 5MB/foto.</p>
                                        <input type="file" name="photos[]" id="photos" multiple accept="image/*" class="hidden" required onchange="document.getElementById('file-count').innerText = this.files.length + ' foto dipilih'">
                                    </div>
                                    <div id="file-count" class="text-sm font-semibold text-primary-600 text-center"></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="submit" form="uploadForm" class="inline-flex w-full justify-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 sm:ml-3 sm:w-auto">Upload Foto</button>
                    <button type="button" @click="open = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
