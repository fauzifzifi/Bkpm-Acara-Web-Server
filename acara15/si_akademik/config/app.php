<?php
return [
    'app_name'   => 'SI Akademik',
    'debug'      => false,  // false = pengguna hanya melihat pesan aman. Isi true HANYA saat belajar/development.
    // API: bila POST /api/mahasiswa tidak mengirim prodi_id, dipakai prodi dengan kode ini.
    // (Contoh body di modul hanya berisi nim, nama, email.) Isi null agar prodi_id wajib dikirim.
    'api_default_prodi' => 'TI',
    // Kredensial sementara (hardcode) sesuai modul.
    'admin_user' => 'admin',
    'admin_pass' => 'admin123',
];
