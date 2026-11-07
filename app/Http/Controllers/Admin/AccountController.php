<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function edit(Request $request): View
    {
        return view('admin.account', ['account' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $account = $request->user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email,'.$account->id],
            'current_password' => ['required_with:password', 'current_password'],
            'password' => ['nullable', 'confirmed', Password::min(12)],
        ]);

        $account->name = $validated['name'];
        $account->email = $validated['email'];
        if (filled($validated['password'] ?? null)) {
            $account->password = Hash::make($validated['password']);
        }
        $account->save();

        return back()->with('success', 'Pengaturan akun berhasil diperbarui.');
    }
}
