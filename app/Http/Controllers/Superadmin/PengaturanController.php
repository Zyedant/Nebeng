<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    public function index()
    {
        return view('superadmin.pages.pengaturan');
    }

    public function updateProfile(Request $request)
    {
        $u = $request->user();

        $data = $request->validate([
            'name'         => ['required','string','max:255'],
            'email'        => ['required','email','max:255', Rule::unique('users','email')->ignore($u->id)],
            'birth_place'  => ['nullable','string','max:255'],
            'birth_date'   => ['nullable','date'],
            'gender'       => ['nullable', Rule::in(['Laki-laki','Perempuan'])],
            'phone_number' => ['nullable','string','max:30'],

            'avatar'       => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        if ($request->hasFile('avatar')) {

            // hapus lama jika ada
            if (!empty($u->image)) {
                Storage::disk('public')->delete($u->image); // karena DB "avatars/xxx.png"
            }

            // simpan baru
            $path = $request->file('avatar')->store('avatars', 'public'); // "avatars/xxxx.png"
            $data['image'] = $path;
        }

        unset($data['avatar']);

        $u->update($data);

        return redirect()->route('sa.pengaturan')->with('saved_success', true);
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])
                ->withInput();
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('sa.pengaturan')->with('saved_success', true);
    }
}