<?php

namespace App\Http\Controllers;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class roles extends Controller
{
    
    function getRoles(Request $request)
    {
        Log::info("I am working");
        $roles = Role::all();
        Log::info($roles);
        return response()->json($roles);
    }
}
