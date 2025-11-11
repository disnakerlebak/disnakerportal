<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\JobseekerProfile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', Rule::in(['pencaker','perusahaan','admin'])],
            // NIK wajib jika mendaftar sebagai pencaker, unik pada tabel jobseeker_profiles
            'nik' => [
                Rule::requiredIf(fn () => $request->input('role') === 'pencaker'),
                'nullable',
                'digits:16',
                Rule::unique('jobseeker_profiles', 'nik'),
            ],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        // Otomatis buat profil pencaker untuk role pencaker
        if ($user->role === 'pencaker') {
            JobseekerProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'nama_lengkap' => $validated['name'],
                    'nik' => $validated['nik'] ?? null,
                    'email_cache' => $validated['email'],
                ]
            );
        }

        event(new Registered($user));

        // Jangan auto-login. Arahkan ke halaman login dengan notifikasi sukses.
        return redirect()->route('login')->with('status', 'Registrasi berhasil. Silakan masuk menggunakan email dan kata sandi.');
    }

}
