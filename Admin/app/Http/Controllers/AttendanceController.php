<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Menampilkan halaman utama absensi.
     */
    public function index()
    {
        // Untuk saat ini, kita hanya akan menampilkan view.
        // Nantinya, Anda bisa menambahkan logika untuk mengambil data absensi dari database.
        return view('attendance.index');
    }
}