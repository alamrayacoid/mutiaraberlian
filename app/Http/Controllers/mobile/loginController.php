<?php

namespace App\Http\Controllers\mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class loginController extends Controller
{
    public function login(Request $request)
    {
        return json_encode([
            'data' => 'sukses'
        ]);
    }
}
