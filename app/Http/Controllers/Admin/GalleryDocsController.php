<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class GalleryDocsController extends Controller
{
    public function index()
    {
        $galleryPath = public_path('storage/galeri');
        $photos = [];
        
        if (File::exists($galleryPath)) {
            $files = File::files($galleryPath);
            foreach ($files as $file) {
                $ext = strtolower($file->getExtension());
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $photos[] = [
                        'filename' => $file->getFilename(),
                        'src' => asset('storage/galeri/' . $file->getFilename()),
                        'date' => filemtime($file->getPathname())
                    ];
                }
            }
        }
        
        // Sort by date descending
        usort($photos, function($a, $b) {
            return $b['date'] <=> $a['date'];
        });

        return view('admin.gallery_docs.index', compact('photos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'photos' => 'required',
            'photos.*' => 'image|mimes:jpg,jpeg,png,gif,webp|max:5120'
        ]);

        if ($request->hasFile('photos')) {
            $galleryPath = public_path('storage/galeri');
            if (!File::exists($galleryPath)) {
                File::makeDirectory($galleryPath, 0755, true);
            }

            foreach ($request->file('photos') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($galleryPath, $filename);
            }

            return redirect()->back()->with('success', 'Foto dokumentasi berhasil ditambahkan.');
        }

        return redirect()->back()->with('error', 'Tidak ada foto yang diunggah.');
    }

    public function destroy($filename)
    {
        $filePath = public_path('storage/galeri/' . $filename);
        
        if (File::exists($filePath)) {
            File::delete($filePath);
            return redirect()->back()->with('success', 'Foto berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Foto tidak ditemukan.');
    }
}
