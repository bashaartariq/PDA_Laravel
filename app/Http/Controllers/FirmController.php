<?php

namespace App\Http\Controllers;

use App\Models\Firm;
use App\Models\Insurance;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
    function createInsurance(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'state' => 'required|string|max:100',
                'city' => 'required|string|max:100',
                'zip' => 'required|regex:/^\d{5}(-\d{4})?$/',
            ]);
            $insurance = Insurance::create([
                'name' => $validated['name'],
                'state' => $validated['state'],
                'city' => $validated['city'],
                'zip_code' => $validated['zip'],
            ]);
            return response()->json($insurance, 200);
        } catch (Exception $e) {
            Log::info($e);
            return response()->json(['error' => 'An error occurred while creating the insurance.'], 500);
        }
    }
    function updateInsurance(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'state' => 'required|string|max:100',
                'city' => 'required|string|max:100',
                'zip' => 'required|regex:/^\d{5}(-\d{4})?$/',
            ]);
            $insurance = Insurance::find($id);
            if (!$insurance) {
                return response()->json(['message' => 'Insurance not found'], 404);
            }
            $insurance->name = $validated['name'];
            $insurance->state = $validated['state'];
            $insurance->city = $validated['city'];
            $insurance->zip_code = $validated['zip'];
            $insurance->save();
            return response()->json($insurance, 200);
        } catch (Exception $e) {
            Log::info($e);
            return response()->json(['error' => 'An error occurred while updating the insurance.'], 500);
        }
    }
    public function deleteInsurance($id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:insurances,id',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }
            $insurance = Insurance::find($id);
            if (!$insurance) {
                return response()->json(['message' => 'Insurance not found'], 404);
            }
            $insurance->delete();
            return response()->json(['message' => 'Insurance deleted successfully'], 200);
        } catch (Exception $e) {
            Log::info($e);
            return response()->json(['error' => 'An error occurred while deleting the insurance.'], 500);
        }
    }
    public function createFirm(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'state' => 'required|string|max:100',
                'city' => 'required|string|max:100',
                'zip' => 'required|regex:/^\d{5}(-\d{4})?$/',
            ]);
            $firm = Firm::create([
                'name' => $validated['name'],
                'state' => $validated['state'],
                'city' => $validated['city'],
                'zip_code' => $validated['zip'],
            ]);
            return response()->json($firm, 200);
        } catch (Exception $e) {
            Log::info($e);
            return response()->json(['error' => 'An error occurred while creating the firm.'], 500);
        }
    }

    public function updateFirm(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'state' => 'required|string|max:100',
                'city' => 'required|string|max:100',
                'zip' => 'required|regex:/^\d{5}(-\d{4})?$/',
            ]);
            $firm = Firm::find($id);
            if (!$firm) {
                return response()->json(['message' => 'Firm not found'], 404);
            }
            $firm->name = $validated['name'];
            $firm->state = $validated['state'];
            $firm->city = $validated['city'];
            $firm->zip_code = $validated['zip'];
            $firm->save();
            return response()->json($firm, 200);
        } catch (Exception $e) {
            Log::info($e);
            return response()->json(['error' => 'An error occurred while updating the firm.'], 500);
        }
    }
    public function deleteFirm($id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:firms,id',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }
            $firm = Firm::find($id);
            if (!$firm) {
                return response()->json(['message' => 'Firm not found'], 404);
            }
            $firm->delete();
            return response()->json(['message' => 'Firm deleted successfully'], 200);
        } catch (Exception $e) {
            Log::info($e);
            return response()->json(['error' => 'An error occurred while deleting the firm.'], 500);
        }
    }
}
