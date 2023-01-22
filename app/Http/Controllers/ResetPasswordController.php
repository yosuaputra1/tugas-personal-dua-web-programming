<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\StrongPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    //
    public function index()
    {
        return view('reset_password');
    }

    public function reset(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'email:rfc,dns|required',
            'password' => [
                'required',
                new StrongPassword(),
            ],
            'confirm_password' => [
                'required',
                new StrongPassword(),
            ]
        ]);

        if ($validatedData['password'] != $validatedData['confirm_password']) {
            return back()->withErrors(['resetError' => 'Password is not the same']);
        }

        $user = User::where('email', $validatedData['email']);
        $user->password = Hash::make($validatedData['password']);
        $user->save();

        return back()->with(['success' => 'Password berhasil direset. Silakan kembali ke halaman login']);
    }
}
