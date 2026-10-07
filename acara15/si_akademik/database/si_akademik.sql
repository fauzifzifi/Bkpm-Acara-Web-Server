-- ============================================================
-- Database SI Akademik (Workshop SI Web Server TIF330805)
-- Skema dari modul Acara 7 + kolom 'status' (Tugas Mandiri Acara 7).
-- Aman dijalankan ulang. Jika database si_akademik sudah ada di komputermu,
-- file ini TIDAK wajib dijalankan.
-- ============================================================

-- Langkah 1 (Acara 14): buat database
CREATE DATABASE IF NOT EXISTS si_akademik CHARACTER SET utf8mb4;
USE si_akademik;

CREATE TABLE IF NOT EXISTS prodi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(10) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel mahasiswa: nim UNIQUE (dasar pengecekan NIM duplikat), FOREIGN KEY ke prodi
CREATE TABLE IF NOT EXISTS mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NULL,
    prodi_id INT NOT NULL,
    angkatan YEAR NOT NULL,
    status ENUM('aktif','cuti','lulus') NOT NULL DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_mahasiswa_prodi FOREIGN KEY (prodi_id) REFERENCES prodi(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS matakuliah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(10) NOT NULL UNIQUE,
    nama VARCHAR(150) NOT NULL,
    sks TINYINT NOT NULL,
    prodi_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_matakuliah_prodi FOREIGN KEY (prodi_id) REFERENCES prodi(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Data awal (INSERT IGNORE: tidak error bila sudah ada)
INSERT IGNORE INTO prodi (kode, nama) VALUES
    ('TI', 'Teknik Informatika'),
    ('SI', 'Sistem Informasi'),
    ('TK', 'Teknik Komputer');

INSERT IGNORE INTO mahasiswa (nim, nama, email, prodi_id, angkatan) VALUES
    ('2401001', 'Budi Santoso', 'budi@email.com', 1, 2024),
    ('2401002', 'Ani Wijaya',   'ani@email.com',  1, 2024),
    ('2402001', 'Citra Lestari','citra@email.com',2, 2024);

INSERT IGNORE INTO matakuliah (kode, nama, sks, prodi_id) VALUES
    ('TI101', 'Pemrograman Dasar', 3, 1),
    ('TI102', 'Basis Data',        3, 1),
    ('SI101', 'Pengantar SI',      2, 2);

-- Acara 15: data contoh API (sama dengan modul: 23001-23003). Aman dijalankan ulang.
INSERT IGNORE INTO mahasiswa (nim, nama, email, prodi_id, angkatan) VALUES
    ('23001', 'Budi', 'budi@gmail.com', 1, 2023),
    ('23002', 'Siti', 'siti@gmail.com', 1, 2023),
    ('23003', 'Andi', 'andi@gmail.com', 1, 2023);
