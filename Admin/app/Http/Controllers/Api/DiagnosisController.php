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

        // Reduce payload and use the built query. If a search query is present,
        // return a reasonably sized result set for autocomplete (max 200).
        // Otherwise return a paginated/small listing (max 50) to avoid huge responses.
        $limit = !empty($q) ? 200 : 50;

        $diagnoses = $query->select('diagnose_id', 'diagnose_name')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $diagnoses,
            'count' => $diagnoses->count()
        ]);
    }
}