<?php

use Carbon\Carbon;

// Format lengkap: tanggal + jam WIB
if (! function_exists('indoDate')) {
    function indoDate($date)
    {
        if (!$date) return '-';

        return Carbon::parse($date)
            ->setTimezone('Asia/Jakarta')
            ->translatedFormat('d F Y, H:i') . ' WIB';
    }
}

// Format hanya tanggal saja (tanpa jam)
if (! function_exists('indoDateOnly')) {
    function indoDateOnly($date)
    {
        if (!$date) return '-';

        return Carbon::parse($date)
            ->setTimezone('Asia/Jakarta')
            ->translatedFormat('d F Y');
    }
}
