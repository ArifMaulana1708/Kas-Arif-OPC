<?php

if (!function_exists('rupiah')) {
    function rupiah($angka): string
    {
        return 'Rp ' . number_format((int) $angka, 0, ',', '.');
    }
}

if (!function_exists('parse_rupiah')) {
    function parse_rupiah($value): int
    {
        return (int) preg_replace('/[^0-9]/', '', (string) $value);
    }
}
