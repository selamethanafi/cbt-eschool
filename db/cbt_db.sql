-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 11 Okt 2025 pada 19.21
-- Versi server: 5.7.34-log
-- Versi PHP: 7.0.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ubk`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `nama_admin` text NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `admins`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `butir_soal`
--

CREATE TABLE `butir_soal` (
  `id_soal` int(11) NOT NULL,
  `nomer_soal` int(11) NOT NULL,
  `kode_soal` varchar(50) NOT NULL,
  `pertanyaan` longtext NOT NULL,
  `tipe_soal` enum('Pilihan Ganda','Pilihan Ganda Kompleks','Benar/Salah','Uraian','Menjodohkan') NOT NULL,
  `pilihan_1` text DEFAULT NULL,
  `pilihan_2` text DEFAULT NULL,
  `pilihan_3` text DEFAULT NULL,
  `pilihan_4` text DEFAULT NULL,
  `pilihan_5` text DEFAULT NULL,
  `jawaban_benar` text DEFAULT NULL,
  `status_soal` enum('Aktif','Tidak Aktif') DEFAULT 'Aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Struktur dari tabel `cbt_konfigurasi`
--

CREATE TABLE `cbt_konfigurasi` (
  `konfigurasi_id` int(11) NOT NULL,
  `konfigurasi_kode` varchar(50) NOT NULL,
  `konfigurasi_isi` varchar(500) NOT NULL,
  `konfigurasi_keterangan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `cbt_konfigurasi`
--

INSERT INTO `cbt_konfigurasi` (`konfigurasi_id`, `konfigurasi_kode`, `konfigurasi_isi`, `konfigurasi_keterangan`) VALUES
(1, 'cbt_sianis', '', 'alamat sianis'),
(2, 'cbt_key_elearning', '', 'kunci elearning'),
(3, 'cbt_ruang', '10', 'ruang cbt'),
(4, 'cbt_qr', 'tidak', 'Apakah menggunakan kode QR? ya atau tidak'),
(5, 'app_key_server_cbt_lokal', '', 'Kunci rahasia CBT dan SIM'),
(6, 'tunjukkan_hasil', '0', 'Nilai ditunjukkan ke peserta? 1 = ya atau = 0 tidak');

-- --------------------------------------------------------

--
-- Struktur dari tabel `chat`
--

CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `pesan` text NOT NULL,
  `waktu` datetime DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(1) DEFAULT '0',
  `role` enum('siswa','admin') NOT NULL DEFAULT 'siswa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `faq`
--

CREATE TABLE `faq` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `faq`
--

INSERT INTO `faq` (`id`, `question`, `answer`) VALUES
(1, 'cara Ujian?', 'Siswa dapat mengikuti ujian dengan login ke dashboard, memilih menu \'Ujian\', dan mengikuti instruksi yang tersedia.'),
(2, 'Lupa password', 'Hubungi admin atau Guru untuk reset password'),
(3, 'jawaban hilang', 'Jika koneksi terputus jawaban masih tersimpan dan kamu bisa melanjutkan ujian lagi. Silakan hubungi guru atau admin untuk informasi lebih lanjut.'),
(4, 'hasil ujian', 'Setelah ujian selesai, hasil dapat dilihat pada menu \'Nilai\' di dashboard siswa.'),
(5, 'Perangkat', 'Ujian dapat diakses melalui komputer, laptop, atau perangkat mobile dengan koneksi internet yang stabil.'),
(6, 'Jaringan Terputus', 'Silakan buka kembali aplikasi ujian seperti biasa,  Jika tidak bisa masuk atau muncul pesan error, segera hubungi pengawas atau admin ujian untuk reset login.'),
(13, 'Reset Login.', 'hubungi pengawas atau admin ujian untuk reset login.'),
(20, 'Nilai tersembunyi', 'ya, admin bisa menyembunyikan maupun menampilkan nilai, agar siswa tidak bisa melihat jawaban benar.'),
(21, 'Apa itu CBT?', 'CBT adalah Computer-Based Test atau ujian berbasis komputer.');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jawaban_siswa`
--

CREATE TABLE `jawaban_siswa` (
  `id_jawaban` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `nama_siswa` text NOT NULL,
  `kode_soal` varchar(50) NOT NULL,
  `total_soal` text,
  `jawaban_siswa` text,
  `waktu_sisa` text NOT NULL,
  `waktu_dijawab` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status_ujian` enum('Aktif','Non-Aktif','Selesai') DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `nilai`
--

CREATE TABLE `nilai` (
  `id_nilai` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `nama_siswa` text NOT NULL,
  `kode_soal` varchar(250) NOT NULL,
  `total_soal` int(11) NOT NULL,
  `jawaban_benar` varchar(100) NOT NULL,
  `jawaban_salah` varchar(100) NOT NULL,
  `jawaban_kurang` varchar(100) NOT NULL,
  `jawaban_siswa` text NOT NULL,
  `kunci` text NOT NULL,
  `nilai` decimal(5,2) NOT NULL,
  `nilai_uraian` decimal(5,2) DEFAULT '0.00',
  `detail_uraian` text,
  `tanggal_ujian` datetime NOT NULL,
  `status_penilaian` enum('otomatis','perlu_dinilai','selesai') DEFAULT 'otomatis'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaturan`
--

CREATE TABLE `pengaturan` (
  `id` int(11) NOT NULL DEFAULT '1',
  `nama_aplikasi` varchar(100) DEFAULT 'CBT Siswa',
  `logo_sekolah` varchar(255) DEFAULT '',
  `warna_tema` varchar(10) DEFAULT '#0d6efd',
  `waktu_sinkronisasi` int(11) DEFAULT '60',
  `sembunyikan_nilai` tinyint(1) DEFAULT '0',
  `login_ganda` enum('izinkan','blokir') DEFAULT 'blokir',
  `chat` varchar(100) NOT NULL,
  `versi_aplikasi` varchar(20) DEFAULT '1.0.0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `pengaturan`
--

INSERT INTO `pengaturan` (`id`, `nama_aplikasi`, `logo_sekolah`, `warna_tema`, `waktu_sinkronisasi`, `sembunyikan_nilai`, `login_ganda`, `chat`, `versi_aplikasi`) VALUES
(1, 'UBK MAN 2 SMG', 'logo_1747650742.png', '#20c997', 60, 0, 'blokir', 'blokir', '1.1.7.3');

-- --------------------------------------------------------

--
-- Struktur dari tabel `profil`
--

CREATE TABLE `profil` (
  `id` int(11) NOT NULL,
  `encrypt` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `profil`
--

INSERT INTO `profil` (`id`, `encrypt`) VALUES
(1, 'JmNvcHk7IDIwMjUgR2x1ZHVnIGNvZGVsaXRl');

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(11) NOT NULL,
  `nama_siswa` varchar(100) NOT NULL,
  `password` text NOT NULL,
  `username` varchar(200) NOT NULL,
  `kelas` varchar(100) NOT NULL,
  `rombel` varchar(100) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Nonaktif',
  `session_token` varchar(255) DEFAULT NULL,
  `last_activity` datetime DEFAULT NULL,
  `page_url` text,
  `force_logout` tinyint(1) DEFAULT '0',
  `nis` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `skor_game`
--

CREATE TABLE `skor_game` (
  `id` int(11) NOT NULL,
  `id_siswa` int(11) DEFAULT NULL,
  `nama_game` varchar(50) DEFAULT NULL,
  `skor` int(11) DEFAULT '0',
  `waktu` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `soal`
--

CREATE TABLE `soal` (
  `id_soal` int(11) NOT NULL,
  `kode_soal` varchar(200) NOT NULL,
  `nama_soal` varchar(255) NOT NULL,
  `mapel` varchar(100) NOT NULL,
  `kelas` varchar(50) NOT NULL,
  `waktu_ujian` int(11) DEFAULT '60',
  `tanggal` datetime DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Nonaktif',
  `tampilan_soal` varchar(10) NOT NULL,
  `kunci` text,
  `token` varchar(6) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `exambrowser` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `agen` (
  `id` int NOT NULL,
  `nama` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--
ALTER TABLE `soal` ADD `tahun` VARCHAR(4) NULL DEFAULT NULL AFTER `exambrowser`, ADD `semester` VARCHAR(1) NULL DEFAULT NULL AFTER `tahun`;
--
-- Indeks untuk tabel `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `butir_soal`
--
ALTER TABLE `butir_soal`
  ADD PRIMARY KEY (`id_soal`);

--
-- Indeks untuk tabel `cbt_konfigurasi`
--
ALTER TABLE `cbt_konfigurasi`
  ADD PRIMARY KEY (`konfigurasi_id`),
  ADD UNIQUE KEY `konfigurasi_kode` (`konfigurasi_kode`);

--
-- Indeks untuk tabel `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jawaban_siswa`
--
ALTER TABLE `jawaban_siswa`
  ADD PRIMARY KEY (`id_jawaban`),
  ADD UNIQUE KEY `id_jawaban` (`id_jawaban`),
  ADD UNIQUE KEY `unik_jawaban` (`id_siswa`,`kode_soal`),
  ADD KEY `kode_soal` (`kode_soal`);

--
-- Indeks untuk tabel `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`id_nilai`),
  ADD UNIQUE KEY `unique_siswa_soal` (`id_siswa`,`kode_soal`);

--
-- Indeks untuk tabel `pengaturan`
--
ALTER TABLE `pengaturan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `profil`
--
ALTER TABLE `profil`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `skor_game`
--
ALTER TABLE `skor_game`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_siswa` (`id_siswa`);

--
-- Indeks untuk tabel `soal`
--
ALTER TABLE `soal`
  ADD PRIMARY KEY (`id_soal`),
  ADD UNIQUE KEY `kode_soal` (`kode_soal`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `butir_soal`
--
ALTER TABLE `butir_soal`
  MODIFY `id_soal` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `cbt_konfigurasi`
--
ALTER TABLE `cbt_konfigurasi`
  MODIFY `konfigurasi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `faq`
--
ALTER TABLE `faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `jawaban_siswa`
--
ALTER TABLE `jawaban_siswa`
  MODIFY `id_jawaban` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `skor_game`
--
ALTER TABLE `skor_game`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `soal`
--
ALTER TABLE `soal`
  MODIFY `id_soal` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--
ALTER TABLE `agen`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `agen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
--
-- Ketidakleluasaan untuk tabel `skor_game`
--
ALTER TABLE `skor_game`
  ADD CONSTRAINT `skor_game_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
