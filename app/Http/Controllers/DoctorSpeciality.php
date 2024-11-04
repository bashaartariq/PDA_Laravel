<?php

namespace App\Http\Controllers;

use App\Models\Speciality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DoctorSpeciality extends Controller
{
    function getSpeciality(Request $request)
    {
        $speciality = Speciality::all();
        Log::info($speciality);
        return response()->json($speciality);
    }
}
