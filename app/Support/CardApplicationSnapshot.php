<?php

namespace App\Support;

use App\Models\CardApplication;
use App\Models\CardApplicationDocument;
use App\Models\User;

class CardApplicationSnapshot
{
    public static function capture(CardApplication $application): array
    {
        $application->loadMissing([
            'user.jobseekerProfile.educations',
            'user.jobseekerProfile.trainings',
            'documents',
        ]);

        return static::captureFromContext($application->user, $application->documents);
    }

    public static function captureFromContext(User $user, $documents = null): array
    {
        $user->loadMissing('jobseekerProfile.educations', 'jobseekerProfile.trainings');
        $profile = optional($user->jobseekerProfile);

        $educations = $profile
            ? $profile->educations->sortBy('tahun_mulai')->values()->map(fn ($edu) => [
                'tingkat' => $edu->tingkat,
                'nama_institusi' => $edu->nama_institusi,
                'jurusan' => $edu->jurusan,
                'tahun_mulai' => $edu->tahun_mulai,
                'tahun_selesai' => $edu->tahun_selesai,
            ])->toArray()
            : [];

        $trainings = $profile
            ? $profile->trainings->sortBy('tahun')->values()->map(fn ($training) => [
                'jenis_pelatihan' => $training->jenis_pelatihan,
                'lembaga_pelatihan' => $training->lembaga_pelatihan,
                'tahun' => $training->tahun,
            ])->toArray()
            : [];

        $documentsCollection = $documents ?? collect();
        if ($documentsCollection instanceof \Illuminate\Database\Eloquent\Collection === false) {
            $documentsCollection = collect($documents);
        }

        $documentsData = $documentsCollection->map(function ($doc) {
            if ($doc instanceof CardApplicationDocument) {
                return [
                    'type' => $doc->type,
                    'file_path' => $doc->file_path,
                ];
            }

            return (array) $doc;
        })->values()->toArray();

        return [
            'profile' => [
                'nama_lengkap' => $profile?->nama_lengkap,
                'nik' => $profile?->nik,
                'tempat_lahir' => $profile?->tempat_lahir,
                'tanggal_lahir' => $profile?->tanggal_lahir,
                'jenis_kelamin' => $profile?->jenis_kelamin,
                'agama' => $profile?->agama,
                'status_perkawinan' => $profile?->status_perkawinan,
                'pendidikan_terakhir' => $profile?->pendidikan_terakhir,
                'alamat_lengkap' => $profile?->alamat_lengkap,
                'domisili_kecamatan' => $profile?->domisili_kecamatan,
                'no_telepon' => $profile?->no_telepon,
                'status_disabilitas' => $profile?->status_disabilitas,
                'akun_media_sosial' => $profile?->akun_media_sosial,
            ],
            'educations' => $educations,
            'trainings' => $trainings,
            'documents' => $documentsData,
        ];
    }
}
