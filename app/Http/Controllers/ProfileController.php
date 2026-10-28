<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        // Selalu akun yang sedang login, bukan dari input form.
        $request->user()->update([
            'password' => Hash::make($request->validated('password')),
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Password berhasil diganti.');
    }
}