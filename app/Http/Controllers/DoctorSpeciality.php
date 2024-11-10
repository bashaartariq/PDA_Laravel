<?php

namespace App\Http\Controllers;

use App\Models\Speciality;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DoctorSpeciality extends Controller
{
    function getSpeciality(Request $request)
    {
        $speciality = Speciality::all();
        Log::info($speciality);
        return response()->json($speciality);
    }
    function createSpeciality(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);
            $speciality = Speciality::create([
                'name' => $validated['name'],
            ]);
            return response()->json($speciality, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error While Creating.' . $e, 500]);
        }
    }
    function updateSpeciality(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);
            $speciality = Speciality::find($id);
            if (!$speciality) {
                return response()->json(['message' => 'Speciality not found'], 404);
            }
            $speciality->name = $validated['name'];
            $speciality->save();
            return response()->json($speciality, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the speciality.' . $e], 500);
        }
    }
    public function deleteSpeciality(Request $request, $id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:specialities,id',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }
            $speciality = Speciality::find($id);
            if (!$speciality) {
                return response()->json(['message' => 'Speciality not found'], 404);
            }
            $speciality->delete();
            return response()->json(['message' => 'Speciality deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the speciality.'], 500);
        }
    }
}
