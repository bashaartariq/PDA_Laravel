<?php

namespace App\Http\Controllers;

use App\Models\States;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class statecityzip extends Controller
{
    //
    public function getStates()
    {
        // $states = States::with(['cities.zipCodes'])->get();
        try {
            $states = States::all();
            Log::info($states);
            return response()->json($states, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch data.' . $e], 500);
        }
    }

    public function getCity(Request $request, $stateName)
    {
        try {
            $state = States::where('name', $stateName)->first();
            if (!$state) {
                return response()->json(['message' => 'State not found'], 404);
            }
            $cities = $state->cities;
            Log::info($state);
            Log::info($cities);
            return response()->json($cities);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'An error occurred while fetching cities'], 500);
        }
    }
}
