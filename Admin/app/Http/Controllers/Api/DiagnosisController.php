<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Diagnosis;
use Illuminate\Http\Request;

class DiagnosisController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');

        $query = Diagnosis::query();

        if (!empty($q)) {
            $query->where(function ($sub) use ($q) {
                $sub->where('diagnose_name', 'like', "%$q%")
                    ->orWhere('diagnose_id', 'like', "%$q%");
            });
        }

        $diagnoses = Diagnosis::limit(20000)->get();

        return response()->json([
            'success' => true,
            'data' => $diagnoses
        ]);
    }
}