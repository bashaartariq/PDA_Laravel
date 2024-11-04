<?php

namespace App\Http\Controllers;

use App\Models\Firm;
use App\Models\Insurance;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FirmController extends Controller
{
    function getFirm(Request $request)
    {
        try {
            $Firm = Firm::all();
            return response()->json($Firm);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Failed to fetch categories'], 500);
        }
    }

    function getInsurance(Request $request)
    {
        try {
            $Insurance = Insurance::all();
            return response()->json($Insurance);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Failed to fetch categories'], 500);
        }
    }
}
