<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PracticeLocation;
use Illuminate\Support\Facades\Log;

class PracticeLocationController extends Controller
{
    function getPracticeLocation(Request $request)
    {
        try {
            $locations = PracticeLocation::all();
            return response()->json($locations);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Failed to fetch practice locations'], 500);
        }
    }
}
