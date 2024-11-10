<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PracticeLocation;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
    public function createPracticeLocation(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);
            $practiceLocation = PracticeLocation::create([
                'name' => $validated['name'],
            ]);

            return response()->json($practiceLocation, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while creating the practice location.'], 500);
        }
    }
    public function updatePracticeLocation(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);
            $practiceLocation = PracticeLocation::find($id);
            if (!$practiceLocation) {
                return response()->json(['message' => 'PracticeLocation not found'], 404);
            }
            $practiceLocation->name = $validated['name'];
            $practiceLocation->save();
            return response()->json($practiceLocation, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the practice location.'], 500);
        }
    }
    public function deletePracticeLocation($id)
    {
        Log::info("Working");
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:practice_locations,id',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }
            $practiceLocation = PracticeLocation::find($id);
            if (!$practiceLocation) {
                return response()->json(['message' => 'PracticeLocation not found'], 404);
            }
            $practiceLocation->delete();

            return response()->json(['message' => 'PracticeLocation deleted successfully'], 200);
        } catch (Exception $e) {
            Log::info($e);
            return response()->json(['error' => 'An error occurred while deleting the practice location.'], 500);
        }
    }
}
