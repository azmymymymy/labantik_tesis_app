<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User; // Pastikan model User diimport
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|unique:users,username',
            'namalengkap' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        User::create([
            'username' => $request->username,
            'namalengkap' => $request->namalengkap,
            'password' => bcrypt($request->password),
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Registrasi berhasil! Silakan login.']);
        }
        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }
}
