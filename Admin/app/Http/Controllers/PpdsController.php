<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ppds; // Import model Ppds

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
        ]);

        // 2. Simpan data ke database
        Ppds::create($validatedData);

        // 3. Redirect ke halaman daftar dengan pesan sukses
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
    ]);

    $ppds->update($validatedData);

    return redirect()->route('ppds.index')->with('success', 'Data PPDS berhasil diperbarui!');
}

    /**
     * Menghapus data PPDS dari database.
     */
    public function destroy(Ppds $ppds)
    {
        // Hapus data
        $ppds->delete();

        // Redirect ke halaman daftar dengan pesan sukses
        return redirect()->route('ppds.index')
                         ->with('success', 'Data PPDS berhasil dihapus.');
    }

    }
