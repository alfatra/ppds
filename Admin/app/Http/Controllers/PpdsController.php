<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PpdsController extends Controller
{
    /**
     * Menampilkan halaman form untuk input data PPDS.
     */
    public function create()
    {
        return view('ppds.form', [
            'title' => 'Form PPDS',
            'li_1' => 'Data PPDS'
        ]);
    }
}
