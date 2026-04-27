<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Ppds;

class ProfileController extends Controller
{
    /**
     * Menampilkan form lengkapi profil.
     */
    public function edit()
    {
        $user = Auth::user();
        // Ambil data PPDS yang berelasi dengan email user
        $ppds = Ppds::where('email', $user->email)->first();

        return view('profile.edit', compact('user', 'ppds'));
    }

    /**
     * Memperbarui data profil dan menyinkronkannya ke tabel PPDS.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'telepon'      => 'nullable|string|max:20',
            'agama'        => 'nullable|string|max:50',
            'alamat'       => 'nullable|string',
            'berkas'       => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
            'foto_profil'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048|dimensions:ratio=3/4',
        ]);

        // 1. Update nama di tabel Users
        $user->update([
            'name' => $validatedData['nama_lengkap'],
        ]);

        // 2. Sinkronisasikan dengan data di tabel PPDS
        $ppds = Ppds::where('email', $user->email)->first();

        // Handle dokumen/berkas upload
        if ($request->hasFile('berkas')) {
            if ($ppds && $ppds->path_berkas) {
                Storage::disk('public')->delete($ppds->path_berkas);
            }
            $path = $request->file('berkas')->store('berkas_ppds', 'public');
            $validatedData['path_berkas'] = $path;
        }

        // Handle foto profil upload
        if ($request->hasFile('foto_profil')) {
            if ($ppds && $ppds->foto_profil) {
                Storage::disk('public')->delete($ppds->foto_profil);
            }
            $photoPath = $request->file('foto_profil')->store('foto_profil', 'public');
            $validatedData['foto_profil'] = $photoPath;
        }

        if ($ppds) {
            $ppds->update($validatedData);
        } else {
            // Fallback jika belum ada data PPDS, buat baru dan jadikan email user sebagai acuannya
            $validatedData['email'] = $user->email;
            Ppds::create($validatedData);
        }

        return back()->with('success', 'Profil Anda dan Data PPDS berhasil diperbarui!');
    }
}