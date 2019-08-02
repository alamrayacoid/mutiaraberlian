<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\m_company;
use Auth;
use Carbon\Carbon;
use DB;
use App\m_agen;
use App\m_employee;

class ProfileController extends Controller
{
    public function profile(){

        $detailUser = m_company::where('c_id', Auth::user()->u_company)
        ->with('getCity')
        ->with('getAgent')
        ->with('getEmployee')
        ->first();

        // dd($detailUser);
        if (Auth::user()->u_user == 'E') {
            if (!is_null($detailUser->getEmployee)) {
                $birthDate = Carbon::parse($detailUser->getEmployee->e_birth)->format('d M Y');
            }
        } elseif (Auth::user()->u_user == 'A') {
            if (!is_null($detailUser->getAgent)) {
                $birthDate = Carbon::parse($detailUser->getAgent->a_birthday)->format('d M Y');
            }
        }
        $detailUser->birthday = $birthDate;


    	return view('profile.profile', compact('detailUser'));
    }

    public function updatePhoto(Request $request)
    {
        DB::beginTransaction();
        try {
            if (Auth::user()->u_user == 'E') {
                $employee = m_employee::where('e_id', Auth::user()->u_code)->first();
                $imageName = $employee->e_id . '-photo';
                $photo = $request->file('photo')->storeAs('Employees', $imageName);
                $employee->e_foto = $photo;
                $employee->save();
            }
            elseif (Auth::user()->u_user == 'A') {
                $agent = m_agen::where('a_code', Auth::user()->u_code)->first();
                $imageName = $agent->a_code . '-photo';
                $photo = $request->file('photo')->storeAs('Agents', $imageName);
                $agent->a_img = $photo;
                $agent->save();
            }

            DB::commit();
            return response()->json([
                'status' => 'berhasil'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updatePassword(Request $request)
    {
        DB::beginTransaction();
        try {
            // $id = decrypt($request->id);
            $id = Auth::user()->u_id;
            $cek = DB::table('d_username')->where('u_id', $id)->first();
            if (sha1(md5('islamjaya') . $request->oldPassword) == $cek->u_password) {
                if ($request->newPassword == $request->newPasswordConfirm) {
                    DB::table('d_username')->where('u_id', $id)->update([
                        'u_password' => sha1(md5('islamjaya') . $request->newPassword)
                    ]);
                } else {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Password confirmasi tidak sama dengan password baru!'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Password lama salah!'
                ]);
            }

            DB::commit();
            return response()->json([
                'status' => 'success'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

    }
}
