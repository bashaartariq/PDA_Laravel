<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gender as gen;

class Gender extends Controller
{
    function getGender(Request $request)
    {
        $gender = gen::all();
        return response()->json($gender);
    }
}
