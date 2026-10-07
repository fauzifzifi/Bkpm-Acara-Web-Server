<?php
return [
    'app_name'   => 'SI Akademik',
    'debug'      => false,  // false = pengguna hanya melihat pesan aman. Isi true HANYA saat belajar/development.
    // Kredensial sementara (hardcode) sesuai modul.
    'admin_user' => 'admin',
    'admin_pass' => 'admin123',
    // Acara 15: prodi bawaan untuk POST /api/mahasiswa bila 'prodi_id' tidak dikirim (1 = TI pada data awal).
    'api_default_prodi_id' => 1,
];
