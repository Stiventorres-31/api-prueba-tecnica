<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;

class FallbackController extends Controller
{
    public function __invoke(Request $request)
    {
        return ResponseHelper::error("Endpoint or method not found",404);
       
    }
}
