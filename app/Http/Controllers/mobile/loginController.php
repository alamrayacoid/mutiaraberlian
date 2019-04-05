<?php

namespace App\Http\Controllers\mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Passport\Passport;

class loginController extends Controller
{
    public function logout(Request $request)
    {
        Passport::routes();
        return $request;
    }
}
