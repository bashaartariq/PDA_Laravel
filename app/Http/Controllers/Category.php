<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;
use Illuminate\Support\Facades\Log;

class Category extends Controller
{
    function getCategory()
    {
        try {
            $categories = Categories::all();
            return response()->json($categories);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Failed to fetch categories'], 500);
        }
    }
}
