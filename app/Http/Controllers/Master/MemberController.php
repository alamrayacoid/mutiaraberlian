<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MemberController extends Controller
{
    public function index()
    {
        return view('masterdatautama.member.index');
    }

    public function create()
    {
        return view('masterdatautama.member.create');
    }

    public function edit()
    {
        return view('masterdatautama.member.edit');
    }
}
