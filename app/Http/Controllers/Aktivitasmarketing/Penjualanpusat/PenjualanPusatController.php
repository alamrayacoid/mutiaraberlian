<?php

namespace App\Http\Controllers\Aktivitasmarketing\Penjualanpusat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

use DB;
use Auth;
use Response;
use DataTables;
use Carbon\Carbon;
use CodeGenerator;

class PenjualanPusatController extends Controller
{
	public function index()
	{
		return view('marketing/penjualanpusat/index');
	}

	// Target Realisasi
	public function createTargetReal()
	{
		return view('marketing.penjualanpusat.targetrealisasi.create');
	}
}