<?php

namespace App\Http\Controllers\Pencaker;

use App\Http\Controllers\Controller;
use App\Models\JobseekerProfile;
use App\Models\CardApplication;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    // 🔹 Tampilkan halaman Data Diri
    public function edit(Request $request)
    {
        $profile = JobseekerProfile::firstOrNew(
            ['user_id' => $request->user()->id],
            [
                'nama_lengkap' => $request->user()->name,
                'email_cache'  => $request->user()->email,
            ]
        );

        $kecamatan = $this->kecamatanLebak();

        $isLocked = $this->isEditingLocked($request->user()->id);
        return view('pencaker.profile.edit', compact('profile', 'kecamatan', 'isLocked'));
    }

    // 🔹 Update data diri dari form modal
    public function update(Request $request)
    {
        if ($this->isEditingLocked($request->user()->id)) {
            return back()->with('error', 'Perubahan data diri dikunci karena pengajuan AK1 sedang diproses/diterima.');
        }
        $userId = $request->user()->id;
        $profile = JobseekerProfile::firstOrNew(['user_id' => $userId]);

        $data = $request->validate([
            'nama_lengkap'        => ['required', 'regex:/^[A-Za-z\s]+$/', 'max:255'],
            'nik'                 => ['required', 'digits:16', Rule::unique('jobseeker_profiles')->ignore($profile->id)],
            'tempat_lahir'        => ['nullable', 'string', 'max:100'],
            'tanggal_lahir'       => ['nullable', 'date'],
            'jenis_kelamin'       => ['nullable', 'in:Laki-laki,Perempuan'],
            'agama'               => ['nullable', 'string', 'max:50'],
            'status_perkawinan'   => ['nullable', 'in:Belum Kawin,Kawin,Cerai Hidup,Cerai Mati'],
            'pendidikan_terakhir' => ['nullable', 'string', 'max:50'],
            'alamat_lengkap'      => ['nullable', 'string', 'max:255'],
            'domisili_kecamatan'  => ['nullable', 'string', 'max:100'],
            'no_telepon'          => ['nullable', 'string', 'max:20'],
        ]);

        // Cegah edit ulang NIK jika sudah ada
        if ($profile->nik && $profile->nik !== $data['nik']) {
            unset($data['nik']);
        }

        $data['user_id'] = $userId;
        $data['email_cache'] = $request->user()->email;

        $profile->fill($data)->save();

        ActivityLog::create([
            'user_id' => $userId,
            'action' => $profile->wasRecentlyCreated ? 'created' : 'updated',
            'model_type' => JobseekerProfile::class,
            'model_id' => $profile->id,
            'description' => $profile->wasRecentlyCreated ? 'Isi Data Diri pertama kali' : 'Perbarui Data Diri',
        ]);

        return redirect()
    ->to('/pencaker/profile')
    ->with('success', 'Data diri berhasil diperbarui!');

    }

    // Tentukan apakah pengeditan dikunci karena status pengajuan AK1
    private function isEditingLocked(int $userId): bool
    {
        $last = CardApplication::where('user_id', $userId)->latest()->first();
        return $last && in_array($last->status, ['Menunggu Verifikasi', 'Disetujui']);
    }

    // 🔹 Daftar kecamatan di Kabupaten Lebak
    private function kecamatanLebak(): array
    {
        return [
            'Bayah','Banjarsari','Bojongmanik','Cibadak','Cibeber','Cigemblong','Cihara','Cijaku',
            'Cikulur','Cileles','Cilograng','Cimarga','Cipanas','Cirinten','Curugbitung','Gunungkencana',
            'Kalanganyar','Lebakgedong','Leuwidamar','Maja','Malingping','Muncang','Panggarangan',
            'Rangkasbitung','Sajira','Sobang','Wanasalam','Warunggunung'
        ];
    }
}
