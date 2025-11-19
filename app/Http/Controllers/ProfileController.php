<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'bio' => ['nullable', 'string', 'max:500'],
            // Validasi Baru
            'fakultas' => ['nullable', 'string', 'max:100'],
            'jurusan' => ['nullable', 'string', 'max:100'],
            'angkatan' => ['nullable', 'integer', 'min:2000', 'max:' . (date('Y') + 1)],
            'avatar' => ['nullable', 'image', 'max:1024'], // Max 1MB
        ]);

        $user = $request->user();

        // 1. Update Nama di User
        $user->update(['name' => $validated['name']]);

        // 2. Siapkan Data Profil
        $profileData = [
            'phone' => $validated['phone'],
            'bio'   => $validated['bio'],
            'fakultas' => $validated['fakultas'],
            'jurusan' => $validated['jurusan'],
            'angkatan' => $validated['angkatan'],
            'is_first_login' => false, // Matikan status first login setelah update
        ];

        // 3. Handle Upload Avatar (Jika ada file yang diupload)
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada (Opsional, untuk hemat storage)
            // if ($user->profile && $user->profile->avatar_path) {
            //     Storage::disk('public')->delete($user->profile->avatar_path);
            // }

            // Simpan file baru ke folder 'avatars' di disk public
            $path = $request->file('avatar')->store('avatars', 'public');
            $profileData['avatar_path'] = $path;
        }

        // 4. Simpan ke Database
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
