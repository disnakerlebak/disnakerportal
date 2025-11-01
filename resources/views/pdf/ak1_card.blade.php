<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Pencari Kerja {{ $application->nomor_ak1 ?? 'Belum Ditetapkan' }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10px; 
            color: #000;
            margin: 25px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        td, th {
            border: 1px solid #000;
            padding: 2px 4px; /* Padding lebih kecil */
            vertical-align: top; /* Teks rata atas */
        }
        .no-border, .no-border td, .no-border th {
            border: none;
            height: auto;
            vertical-align: top;
        }
        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }

        /* Header */
        .header-table td {
            border: none;
            vertical-align: top;
            padding: 0;
            height: auto;
        }
        .header-text {
            text-align: center;
            line-height: 1.4;
        }
        .form-ak2-box { /* Formulir AK/II di Target */
            font-weight: bold;
            border: 1px solid #000;
            padding: 2px 10px;
            margin-top: 4px;
            float: right;
            font-size: 11px;
        }
        
        /* Konten */
        .ketentuan {
            font-size: 9px;
            line-height: 1.3;
            list-style-type: decimal;
            padding-left: 17px;
            margin: 0;
        }
        .photo {
            width: 100px; 
            height: 120px;
            object-fit: cover;
            border: 1px solid #000;
            display: block;
            margin: 0 auto;
        }
        .ttd-box {
            height: 50px; /* Tinggi kotak ttd pencari */
            margin-top: 2px;
        }
        .laporan-row td {
            height: 35px;
               /* Tinggi baris laporan 6 bulanan */
        }
        .no-border .bordered-table td,
        .no-border .bordered-table th {
            border: 1px solid #000;
        }
        .section-title {
            font-weight: bold;
            margin-top: 6px;
            margin-bottom: 1px;
            font-size: 10px;
            text-align: center;
        }
        .pendidikan-label {
            font-size: 9px;
            vertical-align: middle;
            padding: 0 4px;
        }
        .edu-row td {
            height: 18px; /* Tinggi baris tetap pendidikan */
        }
        
        /* Footer */
        .footer-table, .footer-table td {
            border: none;
            vertical-align: top;
            font-size: 10px;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    @php
        // Siapkan data pendidikan untuk dipetakan ke baris tetap
        $eduMap = [];
        foreach ($educations as $edu) {
            $tingkat = strtoupper($edu->tingkat);
            if (in_array($tingkat, ['SD', 'MI', 'SD/SEDERAJAT'])) $eduMap['SD'] = $edu;
            elseif (in_array($tingkat, ['SMP', 'MTS', 'SLTP', 'SLTP/SEDERAJAT'])) $eduMap['SLTP'] = $edu;
            elseif (in_array($tingkat, ['SMA', 'SMK', 'MA', 'SLTA', 'SLTA/SMK/SEDERAJAT'])) $eduMap['SLTA'] = $edu;
            elseif (in_array($tingkat, ['D1', 'D2', 'D3', 'D4', 'D.I/II/III/D.IV'])) $eduMap['D'] = $edu;
            elseif (in_array($tingkat, ['AKTA', 'AKTA I/AKTA II/AKTA III/AKTA.IV'])) $eduMap['AKTA'] = $edu;
            elseif (in_array($tingkat, ['S1', 'S2', 'S3', 'S1/S2/S3'])) $eduMap['S'] = $edu;
        }
    @endphp

    {{-- Header --}}
    <table class="header-table">
        <tr>
            <td width="15%" class="center">
                <img src="{{ public_path('images/Logo-Lebak.png') }}" width="70">
            </td>
            <td width="85%" class="header-text" class="center">
                <div class="form-ak2-box">Formulir AK/I</div>
                <div style="font-size:16px; font-weight:bold;">PEMERINTAH KABUPATEN LEBAK</div>
                <div style="font-size:15px; font-weight:bold;">DINAS TENAGA KERJA</div>
                <div style="font-size:12px; font-weight:bold; margin-top:2px;">KARTU TANDA BUKTI PENDAFTARAN PENCARI KERJA</div>
            </td>
        </tr>
    </table>
    <hr>

    {{-- Info Atas (No. Pendaftaran & NIK) --}}
    <table class="no-border" style="margin-top: 8px;">
        <tr>
            <td width="40%" style="font-size: 11px; padding-left: 2px;">
                <b>No. Pendaftaran Pencari Kerja</b><br>
                <b>No. Induk Kependudukan</b>
            </td>
            <td width="2%" style="font-size: 11px;">
                :<br>
                :
            </td>
            <td width="58%" style="font-size: 11px;">
                {{ strtoupper($application->nomor_ak1 ?? '-') }}<br>
                {{ $profile->nik }}
            </td>
        </tr>
    </table>
    
    {{-- ========================================================== --}}
    {{-- BAGIAN BARU 1: Foto (Kiri) || Ketentuan (Kanan) --}}
    {{-- ========================================================== --}}
    <table class="no-border" style="margin-top: 8px;">
        <tr>
            {{-- Kolom Kiri: Foto --}}
            <td width="25%" align="center">
                @if($fotoPath && file_exists($fotoPath))
                    <img src="{{ $fotoPath }}" style="width: 120px; height: 150px; object-fit: cover; border:1px solid #000;">
                @else
                    <div style="width: 120px; height: 150px; border:1px solid #000; display:flex; align-items:center; justify-content:center;">
                        <small>Pas Foto</small>
                    </div>
                @endif
                <br>
                <!-- <small>Tanda Tangan,<br>Pencari Kerja</small> -->
            </td>
            
            {{-- Kolom Kanan: Ketentuan --}}
            <td width="70%" style="padding-left: 10px;">
                 <b>Ketentuan:</b>
                <ol class="ketentuan">
                    <li>Berlaku Nasional</li>
                    <li>Bila ada perubahan data/keterangan lainnya atau telah mendapat pekerjaan harap melapor...</li>
                    <li>Apabila Pencari Kerja yang bersangkutan telah diterima bekerja...</li>
                    <li>Kartu ini berlaku selama 2 (dua) tahun...</li>
                </ol>
            </td>
        </tr>
    </table>

    {{-- ==================================================================== --}}
    {{-- BAGIAN BARU 2: TTD Pencari (Kiri) || Laporan 6 Bulan (Kanan) --}}
    {{-- ==================================================================== --}}
    <table class="no-border" style="margin-top: 5px;">
        <tr>
            {{-- Kolom Kiri: TTD Pencari Kerja (Tanpa Border) --}}
            <td width="30%" style="padding-right: 10px;" class="center">
                <div class="center" style="margin-top: 4px;">
                    Tanda Tangan,<br>Pencari Kerja
                </div>
                {{-- Sesuai permintaan terakhir, border di-set 'none' --}}
                <div class="ttd-box" style="border: none;"></div>
            </td>

            {{-- Kolom Kanan: Tabel Laporan --}}
            <td width="70%">
                <table style="font-size: 9px;" class="bordered-table">
                    <tr class="center bold">
                        <td width="25%">Laporan</td>
                        <td width="30%">Tanggal-Bulan-Tahun</td>
                        <td width="45%">Tanda Tangan Pengantar Kerja/Petugas<br>Pendaftar (Cantumkan Nama dan NIP)</td>
                    </tr>
                    <tr class="center laporan-row">
                        <td>Kesatu</td>
                        <td>{{ $application->approved_at?->addMonths(6)->format('d/m/Y') ?? '' }}</td>
                        <td></td>
                    </tr>
                    <tr class="center laporan-row">
                        <td>Kedua</td>
                        <td>{{ $application->approved_at?->addMonths(12)->format('d/m/Y') ?? '' }}</td>
                        <td></td>
                    </tr>
                    <tr class="center laporan-row">
                        <td>Ketiga</td>
                        <td>{{ $application->approved_at?->addMonths(18)->format('d/m/Y') ?? '' }}</td>
                        <td></td>
                    </tr>
                    {{-- Sesuai permintaan terakhir, border diaktifkan --}}
                    <tr>
                        <td colspan="2" style="padding-left: 5px;"><b>Diterima Penempatan:</b></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding-left: 5px;"><b>Tanggal Penempatan:</b></td>
                        <td></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>


    {{-- ========================================================== --}}
    {{-- BAGIAN SELANJUTNYA: Data Diri, Pendidikan, Keterampilan --}}
    {{-- ========================================================== --}}

    {{-- Data Diri --}}
    <div class="section-title" style="text-align: left">DATA DIRI:</div>
    <table>
        <tr><td width="30%">NAMA LENGKAP</td><td>{{ strtoupper($profile->nama_lengkap) }}</td></tr>
        <tr><td>TEMPAT/TANGGAL LAHIR</td><td>{{ strtoupper($profile->tempat_lahir) }}, {{ \Carbon\Carbon::parse($profile->tanggal_lahir)->format('d/m/Y') }}</td></tr>
        <tr><td>JENIS KELAMIN</td><td>{{ strtoupper($profile->jenis_kelamin) }}</td></tr>
        <tr><td>STATUS</td><td>{{ strtoupper($profile->status_perkawinan) }}</td></tr>
        <tr><td>AGAMA</td><td>{{ strtoupper($profile->agama) }}</td></tr>
        <tr><td>ALAMAT DOMISILI</td><td>{{ strtoupper($profile->alamat_lengkap) }}</td></tr>
    </table>

    {{-- Pendidikan Formal (Struktur Tetap) --}}
    <div class="section-title" style="text-align: left">PENDIDIKAN FORMAL:</div>
    <table>
        <tr class="center bold">
            <td width="50%">Jenjang / Nama Institusi</td>
            <td width="35%">Jurusan</td>
            {{-- Header "Tahun" diubah ke "Thn" dan di-align kanan --}}
            <td width="15%" class="center">Tahun Lulus</td> 
        </tr>
        @forelse($educations as $edu)
        <tr>
            <td>{{ strtoupper($edu->tingkat) }} — {{ strtoupper($edu->nama_institusi) }}</td>
            <td>{{ strtoupper($edu->jurusan ?? '-') }}</td>
            <td class="center">{{ $edu->tahun_selesai ?? '-' }}</td>
        </tr>
        @empty
        <tr><td colspan="3" class="center">- Tidak ada data -</td></tr>
        @endforelse
    </table>

    {{-- Keterampilan --}}
    <div class="section-title" style="text-align: left">KETERAMPILAN</div>
    <table>
        <tr class="center bold">
            <td width="5%">No</td>
            <td width="70%">Jenis Keterampilan</td>
            {{-- Header "Tahun" diubah ke "Thn" dan di-align kanan --}}
            <td width="25%" class="center">Tahun</td>
        </tr>
        @forelse($trainings as $i => $t)
        <tr>
            <td class="center">{{ $i + 1 }}</td>
            <td>{{ strtoupper($t->jenis_pelatihan) }}</td>
            <td class="center">{{ $t->tahun }}</td>
        </tr>
        @empty
        <tr><td colspan="3" class="center">- Tidak ada data -</td></tr>
        @endforelse
    </table>
    
    {{-- Footer TTD --}}
    <table class="footer-table" style="margin-top: 10px;">
        <tr>
            <td width="50%">
                Dikeluarkan pada tanggal: {{ $application->created_at?->format('d/m/Y') ?? '-' }}<br>
                Berlaku sampai dengan: {{ $application->created_at?->addYears(2)->format('d/m/Y') ?? '-' }}
            </td>
            <td width="50%" class="center">
                <b>Pengantar Kerja/Petugas Antar Kerja,</b><br>
                Dinas Tenaga Kerja Kabupaten Lebak<br>
                <img src="{{ public_path('images/ttd-barcode-yuningsih.png') }}" style="width: 80px; height: 80px; margin: 2px 0;">
                <div class="bold" style="text-decoration: underline;">YUNINGSIH, S.Sos</div>
                <div class="bold">NIP. 19691220 200701 2 018</div>
            </td>
        </tr>
    </table>
</body>
</html>