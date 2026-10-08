-- ============================================================
-- Acara 15 - Langkah 1: data awal untuk uji API
-- Modul memakai tabel sederhana (id, nim, nama, email). Tabel mahasiswa di
-- database ini memiliki kolom tambahan (prodi_id, angkatan, status), sehingga
-- ketiga data modul diberi prodi TI dan angkatan 2023.
-- Aman dijalankan ulang (INSERT IGNORE).
-- ============================================================
USE si_akademik;

INSERT IGNORE INTO mahasiswa (nim, nama, email, prodi_id, angkatan) VALUES
    ('23001', 'Budi', 'budi@gmail.com', (SELECT id FROM prodi WHERE kode = 'TI'), 2023),
    ('23002', 'Siti', 'siti@gmail.com', (SELECT id FROM prodi WHERE kode = 'TI'), 2023),
    ('23003', 'Andi', 'andi@gmail.com', (SELECT id FROM prodi WHERE kode = 'TI'), 2023);
