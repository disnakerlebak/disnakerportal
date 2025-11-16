<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;

class CompanyProfileController extends Controller
{
    public function show()
    {
        $company = auth()->user()->companyProfile;

        return view('company.profile.show', [
            'company' => $company,
        ]);
    }

    public function edit()
    {
        $company = auth()->user()->companyProfile;

        return view('company.profile.edit', [
            'company' => $company,
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $company = $user->companyProfile;

        if (!$company) {
            $company = new CompanyProfile(['user_id' => $user->id]);
        }

        $validated = $request->validate([
            'nama_perusahaan'   => ['required', 'string', 'max:255'],
            'jenis_usaha'       => ['nullable', 'string', 'max:255'],
            'alamat_lengkap'    => ['required', 'string', 'max:500'],
            'kecamatan'         => ['nullable', 'string', 'max:255'],
            'kabupaten'         => ['nullable', 'string', 'max:255'],
            'provinsi'          => ['nullable', 'string', 'max:255'],
            'telepon'           => ['nullable', 'string', 'max:50'],
            'email'             => ['nullable', 'email', 'max:255'],
            'website'           => ['nullable', 'string', 'max:255'],
            'social_facebook'   => ['nullable', 'string', 'max:255'],
            'social_instagram'  => ['nullable', 'string', 'max:255'],
            'social_linkedin'   => ['nullable', 'string', 'max:255'],
            'social_twitter'    => ['nullable', 'string', 'max:255'],
            'deskripsi'         => ['nullable', 'string'],
            'jumlah_karyawan'   => ['nullable', 'integer', 'min:0'],
            'nib'               => ['nullable', 'string', 'max:100'],
            'npwp'              => ['nullable', 'string', 'max:100'],
        ]);

        $company->fill($validated);
        $company->user_id = $user->id;
        $company->save();

        return redirect()
            ->route('company.profile.show')
            ->with('status', 'Profil perusahaan berhasil disimpan.');
    }

    public function updateLogo(Request $request)
    {
        $user = $request->user();
        $company = $user->companyProfile;

        if (!$company) {
            $company = new CompanyProfile(['user_id' => $user->id]);
        }

        $validated = $request->validate([
            'logo' => ['required', 'image', 'max:1024'],
        ]);

        $path = $request->file('logo')->store('company-logos', 'public');
        $company->logo = $path;
        $company->user_id = $user->id;
        $company->save();

        return redirect()
            ->route('company.profile.show')
            ->with('status', 'Logo perusahaan berhasil diperbarui.');
    }
}
