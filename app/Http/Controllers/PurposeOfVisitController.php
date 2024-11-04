<?php

namespace App\Http\Controllers;

use App\Models\PurposeOfVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PurposeOfVisitController extends Controller
{
    //

    function getPurposeOfVisit(Request $request)
    {
        try {
            Log::info("RUNNING");
            $purposeofvisit = PurposeOfVisit::get();
            return response()->json($purposeofvisit);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Failed to fetch purposeofvisit'], 500);
        }
    }
}
