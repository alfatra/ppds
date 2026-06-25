<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Import Storage facade
use App\Models\Ppds; // Import model Ppds
use App\Models\User;

class PpdsController extends Controller
{
    /**
     * Menampilkan halaman daftar data PPDS (tabel).
     */
    public function index()
    {
        // Ambil semua data dari database, urutkan dari yang terbaru
        $ppds_list = Ppds::latest()->get();

        return view('ppds.form', compact('ppds_list'));
    }

    /**
     * Menampilkan halaman form untuk menambah data PPDS baru.
     */
    public function create()
    {
        return view('ppds.create');
    }
    // App\Http\Controllers\PpdsController.php
public function edit(Ppds $ppds)
{
    return view('ppds.create', compact('ppds')); // Menggunakan view yang sama
}


    /**
     * Menyimpan data PPDS baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $validatedData = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:ppds,email',
            'telepon' => 'nullable|string|max:15',
            'agama' => 'nullable|string',
            'alamat' => 'nullable|string',
            'berkas' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120', // Validasi file (maks 5MB)
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi foto (maks 2MB)
        ]);

        // 2. Handle file upload jika ada
        if ($request->hasFile('berkas')) {
            // Simpan file ke storage/app/public/berkas_ppds dan dapatkan path-nya
            $path = $request->file('berkas')->store('berkas_ppds', 'public');
            $validatedData['path_berkas'] = $path;
        }

        // Handle foto_profil
        if ($request->has('gunakan_foto_profil') && $request->gunakan_foto_profil) {
            $user = auth()->user();
            if ($user && $user->profile_photo_path) {
                // Gunakan foto profil user
                $validatedData['foto_profil'] = $user->profile_photo_path;
            }
        } elseif ($request->hasFile('foto_profil')) {
            $fotoPath = $request->file('foto_profil')->store('foto_ppds', 'public');
            $validatedData['foto_profil'] = $fotoPath;
        }

        // 3. Simpan data ke database
        Ppds::create($validatedData);

        // 4. Redirect ke halaman daftar dengan pesan sukses
        return redirect()->route('ppds.index')
                         ->with('success', 'Data PPDS berhasil ditambahkan.');
    }
    // App\Http\Controllers\PpdsController.php
public function update(Request $request, Ppds $ppds)
{
    $validatedData = $request->validate([
        'nama_lengkap' => 'required|string|max:255',
        'email' => 'required|email|unique:ppds,email,' . $ppds->id, // Pastikan email unik kecuali untuk data ini sendiri
        'telepon' => 'nullable|string|max:20',
        'agama' => 'nullable|string|max:50',
        'alamat' => 'nullable|string',
        'berkas' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
        'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // Handle file upload jika ada file baru
    if ($request->hasFile('berkas')) {
        // Hapus file lama jika ada
        if ($ppds->path_berkas) {
            Storage::disk('public')->delete($ppds->path_berkas);
        }

        // Simpan file baru dan update path
        $path = $request->file('berkas')->store('berkas_ppds', 'public');
        $validatedData['path_berkas'] = $path;
    }

    // Handle foto_profil update
    if ($request->has('gunakan_foto_profil') && $request->gunakan_foto_profil) {
        $user = auth()->user();
        if ($user && $user->profile_photo_path) {
            $validatedData['foto_profil'] = $user->profile_photo_path;
        }
    } elseif ($request->hasFile('foto_profil')) {
        // Hapus foto lama jika ada
        if ($ppds->foto_profil && !str_contains($ppds->foto_profil, 'profile-photos')) {
             // Opsional: Storage::disk('public')->delete($ppds->foto_profil);
        }
        $fotoPath = $request->file('foto_profil')->store('foto_ppds', 'public');
        $validatedData['foto_profil'] = $fotoPath;
    }

    $oldEmail = $ppds->email;
    $ppds->update($validatedData);

    if (isset($validatedData['email']) && $validatedData['email'] !== $oldEmail) {
        $user = User::where('email', $oldEmail)->first();
        if ($user && !User::where('email', $validatedData['email'])->where('id', '!=', $user->id)->exists()) {
            $user->update(['email' => $validatedData['email']]);
        }
    }

    return redirect()->route('ppds.index')->with('success', 'Data PPDS berhasil diperbarui!');
}

    /**
     * Menghapus data PPDS dari database.
     */
    public function destroy(Ppds $ppds)
    {
        // Hapus file terkait dari storage jika ada
        if ($ppds->path_berkas) {
            Storage::disk('public')->delete($ppds->path_berkas);
        }

        // Hapus data
        $ppds->delete();

        // Redirect ke halaman daftar dengan pesan sukses
        return redirect()->route('ppds.index')
                         ->with('success', 'Data PPDS berhasil dihapus.');
    }

    /**
     * Mengunduh file berkas yang terlampir.
     */
    public function downloadBerkas(Ppds $ppds)
    {
        // Pastikan file ada sebelum mencoba mengunduh
        if ($ppds->path_berkas && Storage::disk('public')->exists($ppds->path_berkas)) {
            return Storage::disk('public')->download($ppds->path_berkas);
        }

        // Jika file tidak ditemukan, kembalikan ke halaman sebelumnya dengan pesan error
        return back()->with('error', 'File berkas tidak ditemukan.');
    }

    }
