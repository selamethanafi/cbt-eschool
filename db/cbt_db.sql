-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 10 Bulan Mei 2025 pada 20.41
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
(127, 1, 'BINDO7-1', '<img style=\"\" src=\"../gambar/681f9154c46e8.png\" id=\"gbrsoal\"><br>\r\n\r\nPertanyaan<br>', 'Pilihan Ganda', 'asd', 'asdasd', 'asdasd', 'asd', 'pilihan_4', 'Aktif', '2025-05-07 22:06:43'),
(129, 2, 'BINDO7-1', '<img style=\"\" src=\"../gambar/681f91673e178.png\" id=\"gbrsoal\"><br>', 'Pilihan Ganda Kompleks', 'sfsdf', 'sdfsdf', 'sdfsdf', 'sdfsdf', 'pilihan_1,pilihan_2,pilihan_4', 'Aktif', '2025-05-07 22:07:14'),
(131, 3, 'BINDO7-1', '<img style=\"\" src=\"../gambar/681f917dea885.png\" id=\"gbrsoal\"><br>', 'Benar/Salah', 'sdfsdf', 'sdfsdfs', 'sdfsdf', 'gdfdgdg', 'Benar|Benar|Salah|Benar', 'Aktif', '2025-05-07 22:08:01'),
(132, 4, 'BINDO7-1', 'dfgdfgdg', 'Menjodohkan', NULL, NULL, NULL, NULL, 'pilihan 1:pasangan 1|pilihan 2:pasangan 2|pilihan 3:pasangan 3|pilihan 4:pasangan 3', 'Aktif', '2025-05-07 22:08:29'),
(133, 5, 'BINDO7-1', 'rtertert', 'Uraian', NULL, NULL, NULL, NULL, 'r', 'Aktif', '2025-05-07 22:08:42'),
(137, 2, 'SR9-01', 'jawab pernyataan berikut', 'Benar/Salah', 'Seni rupa terapan diciptakan hanya untuk dinikmati', 'Seni rupa terapan diciptakan hanya untuk dijual', '', '', 'Salah|Salah', 'Aktif', '2025-05-07 22:18:24'),
(152, 3, 'SR9-01', 'jawab petanyaan', 'Benar/Salah', 'weq', 'asd', '', '', 'Benar|Salah', 'Aktif', '2025-05-07 23:06:04'),
(154, 6, 'SR9-01', 'benar apa salah', 'Benar/Salah', '<img style=\"width: 100%;\" src=\"../gambar/681f91d575243.png\" id=\"gbrsoal\"><br>', '<img style=\"width: 100%;\" src=\"../gambar/681f924a3e359.png\" id=\"gbrsoal\"><br>', '', '', 'Salah|Benar', 'Aktif', '2025-05-07 23:07:49'),
(159, 7, 'SR9-01', '<p>Literasi Generasi Z: Antara Buku Digital dan Buku Fisi', 'Pilihan Ganda Kompleks', 'fgdfgd', 'fgdfgd', 'fgd', 'fgd', 'pilihan_1,pilihan_3,pilihan_4', 'Aktif', '2025-05-07 23:11:28'),
(160, 5, 'SR9-01', '<img style=\"\" src=\"../gambar/681f91b8491bf.png\" id=\"gbrsoal\"><br>', 'Pilihan Ganda Kompleks', 'xdfg', 'xdfg', 'vdfgdf', 'xff', 'pilihan_1,pilihan_2,pilihan_4', 'Aktif', '2025-05-07 23:11:39'),
(163, 9, 'SR9-01', '<p><img src=\"../gambar/681f976012389.png\" id=\"gbrsoal\" style=\"\"><br>adasd assdasdasd&nbsp; sfwtewtwt&nbsp; gv</p><p><br></p>', 'Uraian', NULL, NULL, NULL, NULL, 'ewt', 'Aktif', '2025-05-10 18:13:58'),
(164, 10, 'SR9-01', '<p>asdasda</p>', 'Menjodohkan', NULL, NULL, NULL, NULL, 'saya jua\r\n\r\n:asdasd', 'Aktif', '2025-05-10 18:14:59'),
(165, 11, 'SR9-01', 'sdfsd sdfsdf sdfsdfsfsf', 'Benar/Salah', '<p>sdfsdf</p>', 'sdfsdf', '', '', 'Benar|Salah', 'Aktif', '2025-05-10 18:17:23'),
(166, 12, 'SR9-01', 'aat saya mau xvxvcsdf dsfsdf<br><img src=\"../gambar/681f9a391e6ec.png\" id=\"gbrsoal\" style=\"\">', 'Uraian', NULL, NULL, NULL, NULL, 'asdsasd', 'Aktif', '2025-05-10 18:21:20'),
(167, 13, 'SR9-01', 'sata juga mau makan<br><img src=\"../gambar/681f993b9b957.png\" id=\"gbrsoal\" style=\"\">', 'Pilihan Ganda', 'asdasd', 'asdasd', 'asdsad', 'asdasd', 'pilihan_2', 'Aktif', '2025-05-10 18:22:14'),
(168, 6, 'BINDO7-1', 'saya juga akan membelinya<br><img src=\"../gambar/681f9d009ea6e.png\" id=\"gbrsoal\" style=\"width: 50%;\">', 'Benar/Salah', 'benar', 'afaf', '', '', 'Benar|Salah', 'Aktif', '2025-05-10 18:39:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jawaban_siswa`
--

CREATE TABLE `jawaban_siswa` (
  `id_jawaban` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `nama_siswa` text NOT NULL,
  `kode_soal` varchar(50) NOT NULL,
  `total_soal` text NOT NULL,
  `jawaban_siswa` text DEFAULT NULL,
  `waktu_sisa` text NOT NULL,
  `waktu_dijawab` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_ujian` enum('Aktif','Non-Aktif') DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jawaban_siswa`
--

INSERT INTO `jawaban_siswa` (`id_jawaban`, `id_siswa`, `nama_siswa`, `kode_soal`, `total_soal`, `jawaban_siswa`, `waktu_sisa`, `waktu_dijawab`, `status_ujian`) VALUES
(1, 1, 'Jokowi JK	', 'SR9-01', '', '[1:pilihan_3],[2:Salah|Salah],[3:Benar|Salah],[4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya, seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu],[5:pilihan_1,pilihan_2],[6:Salah|Benar],[7:pilihan_1,pilihan_3,pilihan_2],[8:ewt],[9:saya jua:asdasd],[10:Benar|Salah],[11:asdsasd],[12:pilihan_3]', '45', '2025-05-10 16:04:37', 'Aktif'),
(2, 3, 'Agum Gumelar', 'SR9-01', '', '[1:pilihan_3],[2:Salah|Salah],[3:Benar|Benar],[4:Teknik Mozaik:Karya seni yang dibuat untuk dinikmati keindahannya, seperti lukisan|Seni Rupa Murni:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu],[5:pilihan_1,pilihan_2,pilihan_4],[6:Salah|Benar],[7:pilihan_1,pilihan_2]', '54', '2025-05-10 16:10:29', 'Aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `nilai`
--

CREATE TABLE `nilai` (
  `id_nilai` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `nama_siswa` text NOT NULL,
  `kode_soal` text NOT NULL,
  `total_soal` int(11) NOT NULL,
  `jawaban_benar` varchar(100) NOT NULL,
  `jawaban_salah` varchar(100) NOT NULL,
  `jawaban_siswa` text NOT NULL,
  `nilai` text NOT NULL,
  `tanggal_ujian` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `nilai`
--

INSERT INTO `nilai` (`id_nilai`, `id_siswa`, `nama_siswa`, `kode_soal`, `total_soal`, `jawaban_benar`, `jawaban_salah`, `jawaban_siswa`, `nilai`, `tanggal_ujian`) VALUES
(1, 1, '', 'SR9-01', 50, '90', '10', '[1:pilihan_3],[2:Salah|Salah],[3:Benar|Benar],[4:Teknik Mozaik:Karya seni yang dibuat untuk dinikmati keindahannya, seperti lukisan|Seni Rupa Murni:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu],[5:pilihan_1,pilihan_2,pilihan_4],[6:Salah|Benar],[7:pilihan_1,pilihan_2]', '90', '2025-05-09 07:08:22'),
(2, 12, '', 'SR9-01', 50, '85', '15', '', '85', '2025-06-09 07:08:22'),
(3, 5, '', 'SR9-01', 50, '90', '10', '', '90', '2025-06-11 07:08:22'),
(4, 4, '', 'SR9-01', 50, '50', '50', '', '50', '2025-04-22 07:08:22'),
(5, 2, '', 'SR9-01', 50, '85', '15', '', '85', '2025-05-08 08:20:30'),
(6, 5, '', 'SR9-01', 50, '85', '15', '', '85', '2025-05-08 08:20:30'),
(7, 3, '', 'SR9-01', 50, '85', '15', '', '85', '2025-05-08 08:20:30'),
(8, 13, '', 'SR9-01', 50, '85', '15', '', '85', '2025-04-15 08:20:30'),
(9, 14, '', 'SR9-01', 50, '85', '15', '', '85', '2025-02-10 08:20:30'),
(10, 15, '', 'SR9-01', 50, '82', '18', '', '82', '2025-03-12 08:20:30'),
(12, 16, '', 'SR9-01', 50, '85', '15', '', '85', '2025-02-10 08:20:30'),
(17, 17, '', 'SR9-01', 50, '85', '15', '', '85', '2025-02-10 08:20:30'),
(18, 5, '', 'BINDO7-1', 50, '85', '15', '', '85', '2025-05-08 08:20:30');

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
(1, 'Jokowi JK', 'h7fV3os6WcZ+hNwtoIN5Si9hbEVndnEzRmNodzJlSktYZ2hVMUE9PQ==', '123456', '9', 'A', 'Nonaktif'),
(2, 'Prabowo', 'm9MaPSetPwkYW68qNsWwUlUrOW9HNWFlRzJRVENVVi9xNW9vN0E9PQ==', '123457', '9', 'B', 'Nonaktif'),
(3, 'Agum Gumelar', '5mv6Upz6eP/GpQrkjcebOHcyOFNxV2RRT2xQdkVxRUh0ZVZ0d3c9PQ==', '123458', '9', 'C', 'nonaktif'),
(4, 'Deddy ', '5uKDYI7JoYmjpgBTg8LxUi9YZ2dIVGFucU5FM2wySDYvcmFVQXc9PQ==', '123459', '9', 'D', 'nonaktif'),
(5, 'Corbuzier', '/SbMMmTczf7Ry0qUn/f6XmhpM1BYS0l6S1F0cmlHSlB3ZjE1cEE9PQ==', '123461', '9', 'E', 'Nonaktif'),
(12, 'Intan sadira destiana kartika', 'g/vyJS2pKV1J+hzFfU3ZD3J3OVdjN2tpdWhyL01uaG9kYTdpbEE9PQ==', '124132', '7', 'A', 'Nonaktif'),
(13, 'Erina', 'FQOm8MYUIes79E36AQv1AU5VMVdUanhIaTBVTURVS0hXckFRUXc9PQ==', '7217317', '9', 'A', 'Nonaktif'),
(14, 'Phoebe', '/pUodVDzsKAbUdhVWOg6s202UC9ybUpLRW5OT3paYnpIaHBGM2c9PQ==', '122345', '7', 'C', 'Nonaktif'),
(15, 'Zevan', 'mG5EAQl0ttZQFaqBXlYCgGdMVkdTMjNQQXZ3VmRKdmFNbTJBeEE9PQ==', '257174731', '7', 'D', 'Nonaktif'),
(16, 'Denny', '3tNjgpDyVjS9ZDzs0WtChlUzZHlLTnZ0MUVJS25JZGR3ckxIcUE9PQ==', '64134', '8', 'F', 'Nonaktif'),
(17, 'Lintar', 'CJ7fgqg1+lzEgNuqTQwdCUtBeHlsdXdGU3FabGdhQ3lQbXQ2NlE9PQ==', '2527', '8', 'D', 'Nonaktif');

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
  `kunci` text NOT NULL,
  `token` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `soal`
--

INSERT INTO `soal` (`id_soal`, `kode_soal`, `nama_soal`, `mapel`, `kelas`, `waktu_ujian`, `tanggal`, `status`, `kunci`, `token`) VALUES
(1, 'SR9-01', 'Seni Rupa 1', 'Seni Rupa', '9', 90, '2025-05-16', 'Aktif', '[1:pilihan_3],[2:Salah|Salah],[3:Benar|Salah],[4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya, seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu],[5:pilihan_1,pilihan_2,pilihan_4],[6:Salah|Benar],[7:pilihan_1,pilihan_3,pilihan_4],[8:ewt],[9:saya jua:asdasd],[10:Benar|Salah],[11:asdsasd],[12:pilihan_2]', 'EOLAGZ'),
(10, 'BINDO7-1', 'B. Indonesia', 'Bahasa Indo', '7', 90, '2025-10-08', 'Nonaktif', '[1:pilihan_4],[2:pilihan_1,pilihan_2,pilihan_4],[3:Benar|Benar|Salah|Benar],[4:pilihan 1:pasangan 1|pilihan 2:pasangan 2|pilihan 3:pasangan 3|pilihan 4:pasangan 3],[5:r]', '');

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
  MODIFY `id_soal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169;

--
-- AUTO_INCREMENT untuk tabel `jawaban_siswa`
--
ALTER TABLE `jawaban_siswa`
  MODIFY `id_jawaban` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `soal`
--
ALTER TABLE `soal`
  MODIFY `id_soal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
