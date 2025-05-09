-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 08 Bulan Mei 2025 pada 22.50
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cbt_db`
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admins`
--

INSERT INTO `admins` (`id`, `username`, `nama_admin`, `password`, `created_at`) VALUES
(1, 'admin', 'Gludug', '$2y$10$mrAbypXzHnq8jrxBEto.cOEoGVJ1UHpdqNNjKS8AD8TZXh8.3SD76', '2025-05-05 09:13:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `butir_soal`
--

CREATE TABLE `butir_soal` (
  `id_soal` int(11) NOT NULL,
  `nomer_soal` int(11) NOT NULL,
  `kode_soal` varchar(50) NOT NULL,
  `pertanyaan` text NOT NULL,
  `tipe_soal` enum('Pilihan Ganda','Pilihan Ganda Kompleks','Benar/Salah','Uraian','Menjodohkan') NOT NULL,
  `pilihan_1` varchar(255) DEFAULT NULL,
  `pilihan_2` varchar(255) DEFAULT NULL,
  `pilihan_3` varchar(255) DEFAULT NULL,
  `pilihan_4` varchar(255) DEFAULT NULL,
  `jawaban_benar` text DEFAULT NULL,
  `status_soal` enum('Aktif','Tidak Aktif') DEFAULT 'Aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `butir_soal`
--

INSERT INTO `butir_soal` (`id_soal`, `nomer_soal`, `kode_soal`, `pertanyaan`, `tipe_soal`, `pilihan_1`, `pilihan_2`, `pilihan_3`, `pilihan_4`, `jawaban_benar`, `status_soal`, `created_at`) VALUES
(52, 1, 'SR9-01', 'Suatu cara memperbanyak gambar dengan alat cetak merupakan pengertian dari ...', 'Pilihan Ganda', 'Seni musik', 'Seni budaya', 'Seni mencetak', 'Seni tari', 'pilihan_3', 'Aktif', '2025-05-06 09:12:13'),
(55, 4, 'SR9-01', 'Jodohkan antara kolom A (istilah seni rupa) dengan kolom B (penjelasan atau contoh yang sesuai)!', 'Menjodohkan', NULL, NULL, NULL, NULL, 'Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya, seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu', 'Aktif', '2025-05-06 09:23:24'),
(127, 1, 'BINDO7-1', 'asdasd', 'Pilihan Ganda', 'asd', 'asdasd', 'asdasd', 'asd', 'pilihan_4', 'Aktif', '2025-05-07 22:06:43'),
(129, 2, 'BINDO7-1', 'Apakah ini shin tae yong ?<br>\r\n<img src=\"/cbt-app/gambar/681cd9623ae54.png\" id=\"gbrsoal\" style=\"\"><br>', 'Pilihan Ganda Kompleks', 'sfsdf', 'sdfsdf', 'sdfsdf', 'sdfsdf', 'pilihan_1,pilihan_2,pilihan_4', 'Aktif', '2025-05-07 22:07:14'),
(131, 3, 'BINDO7-1', '<img src=\"/cbt-app/gambar/681cf6516e647.png\" id=\"gbrsoal\" style=\"\"><br>', 'Benar/Salah', 'sdfsdf', 'sdfsdfs', 'sdfsdf', 'gdfdgdg', 'Benar|Benar|Salah|Benar', 'Aktif', '2025-05-07 22:08:01'),
(132, 4, 'BINDO7-1', 'dfgdfgdg', 'Menjodohkan', NULL, NULL, NULL, NULL, 'pilihan 1:pasangan 1|pilihan 2:pasangan 2|pilihan 3:pasangan 3|pilihan 4:pasangan 3', 'Aktif', '2025-05-07 22:08:29'),
(133, 5, 'BINDO7-1', 'rtertert', 'Uraian', NULL, NULL, NULL, NULL, 'r', 'Aktif', '2025-05-07 22:08:42'),
(137, 2, 'SR9-01', 'jawab pernyataan berikut', 'Benar/Salah', 'Seni rupa terapan diciptakan hanya untuk dinikmati', 'Seni rupa terapan diciptakan hanya untuk dijual', '', '', 'Salah|Salah', 'Aktif', '2025-05-07 22:18:24'),
(145, 6, '', 'fgdgd', 'Pilihan Ganda', 'fgd', 'fg', 'fgd', 'fgd', 'pilihan_1', 'Aktif', '2025-05-07 22:45:37'),
(152, 3, 'SR9-01', 'jawab petanyaan', 'Benar/Salah', 'weq', 'asd', '', '', 'Benar|Salah', 'Aktif', '2025-05-07 23:06:04'),
(154, 6, 'SR9-01', 'benar apa salah', 'Benar/Salah', '<img src=\"/cbt-app/gambar/681d11adf3f4b.png\" id=\"gbrsoal\" style=\"\"><br>', '<img src=\"/cbt-app/gambar/681cd99beef50.png\" id=\"gbrsoal\" style=\"\"><br>', '', '', 'Salah|Benar', 'Aktif', '2025-05-07 23:07:49'),
(159, 7, 'SR9-01', 'fgdgdfgdgdgd', 'Pilihan Ganda Kompleks', 'fgdfgd', 'fgdfgd', 'fgd', 'fgd', 'pilihan_1,pilihan_3,pilihan_4', 'Aktif', '2025-05-07 23:11:28'),
(160, 5, 'SR9-01', '<img src=\"/cbt-app/gambar/681cd98185325.png\" id=\"gbrsoal\" style=\"\"><br>', 'Pilihan Ganda Kompleks', 'xdfg', 'xdfg', 'vdfgdf', 'xff', 'pilihan_1,pilihan_2,pilihan_4', 'Aktif', '2025-05-07 23:11:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jawaban_siswa`
--

CREATE TABLE `jawaban_siswa` (
  `id_jawaban` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `kode_soal` varchar(50) NOT NULL,
  `tipe_soal` enum('Pilihan Ganda','Pilihan Ganda Kompleks','Benar/Salah','Uraian','Menjodohkan') NOT NULL,
  `jawaban_siswa` text DEFAULT NULL,
  `waktu_dijawab` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_ujian` enum('Aktif','Non-Aktif') DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `nilai`
--

CREATE TABLE `nilai` (
  `id_nilai` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `kode_soal` text NOT NULL,
  `total_soal` int(11) NOT NULL,
  `jawaban_benar` varchar(100) NOT NULL,
  `jawaban_salah` varchar(100) NOT NULL,
  `nilai` text NOT NULL,
  `tanggal_ujian` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `profil`
--

CREATE TABLE `profil` (
  `id` int(11) NOT NULL,
  `encrypt` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `status` text NOT NULL DEFAULT 'Nonaktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nama_siswa`, `password`, `username`, `kelas`, `rombel`, `status`) VALUES
(1, 'Jokowi JK', 'hzjsXWfD4JUaLP981RWRr0xwb3dzYTFjRldIM1hXOTVWcDduWVE9PQ==', '123456', '9', 'A', 'Nonaktif'),
(2, 'Prabowo', 'm9MaPSetPwkYW68qNsWwUlUrOW9HNWFlRzJRVENVVi9xNW9vN0E9PQ==', '123457', '9', 'B', 'Nonaktif'),
(3, 'Agum Gumelar', '5mv6Upz6eP/GpQrkjcebOHcyOFNxV2RRT2xQdkVxRUh0ZVZ0d3c9PQ==', '123458', '9', 'C', 'nonaktif'),
(4, 'Deddy ', '5uKDYI7JoYmjpgBTg8LxUi9YZ2dIVGFucU5FM2wySDYvcmFVQXc9PQ==', '123459', '9', 'D', 'nonaktif'),
(5, 'Corbuzier', '/SbMMmTczf7Ry0qUn/f6XmhpM1BYS0l6S1F0cmlHSlB3ZjE1cEE9PQ==', '123461', '9', 'E', 'Nonaktif'),
(12, 'Intan', 'g/vyJS2pKV1J+hzFfU3ZD3J3OVdjN2tpdWhyL01uaG9kYTdpbEE9PQ==', '124132', '7', 'A', 'Nonaktif');

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
  `waktu_ujian` int(11) DEFAULT 60,
  `tanggal` date DEFAULT curdate(),
  `status` text NOT NULL DEFAULT 'Nonaktif',
  `token` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `soal`
--

INSERT INTO `soal` (`id_soal`, `kode_soal`, `nama_soal`, `mapel`, `kelas`, `waktu_ujian`, `tanggal`, `status`, `token`) VALUES
(7, 'SR9-01', 'Seni Rupa 1', 'Seni Rupa', '9', 90, '2025-05-07', 'Nonaktif', ''),
(10, 'BINDO7-1', 'B. Indo', 'Bahasa Indonesia', '7', 90, '2025-10-08', 'Nonaktif', '');

--
-- Indexes for dumped tables
--

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
-- Indeks untuk tabel `jawaban_siswa`
--
ALTER TABLE `jawaban_siswa`
  ADD PRIMARY KEY (`id_jawaban`),
  ADD KEY `kode_soal` (`kode_soal`);

--
-- Indeks untuk tabel `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`id_nilai`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `butir_soal`
--
ALTER TABLE `butir_soal`
  MODIFY `id_soal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

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
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `soal`
--
ALTER TABLE `soal`
  MODIFY `id_soal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
