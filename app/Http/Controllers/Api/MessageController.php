<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function message()
    {
        return response()->json([
            'response' => 101,
            'data' => 'Hello, World!'
        ]);
    }
    
}
