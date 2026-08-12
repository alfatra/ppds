<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KonsulenController extends Controller
{
    /**
     * Menampilkan daftar PPDS (Mahasiswa) yang dibimbing oleh Konsulen (DPJP).
     */
    public function mahasiswa()
    {
        $user = Auth::user();

        // Hanya konsulen, admin, atau superadmin yang boleh melihat
        if (!$user->isKonsulen() && !$user->canManage()) {
            abort(403, 'Akses Ditolak');
        }

        // Ambil mahasiswa yang supervisor_id-nya adalah ID user yang sedang login,
        // ATAU mahasiswa yang pernah mengirimkan SOAP Log ke konsulen ini
        $mahasiswa = User::where('role', 'user')
                        ->where(function ($query) use ($user) {
                            $query->where('supervisor_id', $user->id)
                                  ->orWhereHas('soapLogs', function ($q) use ($user) {
                                      $q->where('supervisor_id', $user->id);
                                  });
                        })
                        ->with('ppds')
                        ->get();

        return view('konsulen.mahasiswa', compact('mahasiswa'));
    }
}
