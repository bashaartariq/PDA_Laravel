<?php

namespace App\Http\Controllers;

use App\Models\CaseTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CaseType extends Controller
{
    //
    function getCaseType(Request $request)
    {
        try {
            $caseType = CaseTypes::all();
            return response()->json($caseType);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Failed to fetch categories'], 500);
        }
    }
}
