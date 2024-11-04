<?php

namespace App\Http\Controllers;

use App\Models\AppointmentType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Appointment_types extends Controller
{
    function getAppointmentTypes()
    {
        try {
            $appointment_type = AppointmentType::all();
            return response()->json($appointment_type);
        } catch (Exception $e) {
            Log::info($e);
            return response()->json(['message' => 'Failed to fetch Appointment Types'], 500);
        }
    }
}
