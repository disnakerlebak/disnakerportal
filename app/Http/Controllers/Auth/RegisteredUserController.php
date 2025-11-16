<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\JobseekerProfile;
use App\Models\CompanyProfile;
use App\Models\ActivityLog;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Show registration page.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // Nama lengkap (pencaker) atau nama perusahaan
            'name' => [
                // Wajib hanya jika mendaftar sebagai pencaker
                Rule::requiredIf(fn () => $request->role === 'pencaker'),
                'nullable',
                'string',
                'max:255',
            ],
            'company_name' => [
                // Wajib hanya jika mendaftar sebagai perusahaan
                Rule::requiredIf(fn () => $request->role === 'perusahaan'),
                'nullable',
                'string',
                'max:255',
            ],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            
            // Role hanya pencaker atau perusahaan
            'role' => [
                'required',
                Rule::in(['pencaker', 'perusahaan'])
            ],

            // NIK hanya wajib jika role = pencaker
            'nik' => [
                Rule::requiredIf(fn () => $request->role === 'pencaker'),
                'nullable',
                'digits:16',
                Rule::unique('jobseeker_profiles', 'nik'),
            ],
        ]);

        // Tentukan nama yang akan disimpan ke tabel users
        $userName = $validated['name'] ?? $validated['company_name'] ?? null;

        // Create user
        $user = User::create([
            'name'     => $userName,
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
            'status'   => 'active', // default sesuai struktur tabel
        ]);

        // Activity log
        ActivityLog::create([
            'user_id'     => $user->id,
            'action'      => 'created',
            'model_type'  => User::class,
            'model_id'    => $user->id,
            'description' => 'Registrasi sebagai ' . $validated['role'],
        ]);

        // Auto-create profile for pencaker
        if ($user->role === 'pencaker') {
            JobseekerProfile::create([
                'user_id'       => $user->id,
                'nama_lengkap'  => $validated['name'],
                'nik'           => $validated['nik'],
                'email_cache'   => $validated['email'],
            ]);
        }

        // Auto-create profile for perusahaan
        if ($user->role === 'perusahaan') {
            CompanyProfile::create([
                'user_id'         => $user->id,
                'nama_perusahaan' => $validated['company_name'] ?? $userName,
                'alamat_lengkap'  => '-',
                'email'           => $validated['email'],
            ]);
        }

        // Fire event
        event(new Registered($user));

        // Redirect ke login, jangan auto login.
        return redirect()
            ->route('login')
            ->with('status', 'Registrasi berhasil. Silakan masuk menggunakan email dan kata sandi Anda.');
    }
}
