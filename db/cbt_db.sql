-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Bulan Mei 2025 pada 05.11
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
(55, 4, 'SR9-01', 'Jodohkan antara kolom A (istilah seni rupa) dengan kolom B (penjelasan atau contoh yang sesuai)!', 'Menjodohkan', NULL, NULL, NULL, NULL, 'Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu', 'Aktif', '2025-05-06 09:23:24'),
(127, 1, 'BINDO7-1', '<img style=\"\" src=\"../gambar/681f9154c46e8.png\" id=\"gbrsoal\"><br>\r\n\r\nPertanyaan<br>', 'Pilihan Ganda', 'asd', 'asdasd', 'asdasd', 'asd', 'pilihan_4', 'Aktif', '2025-05-07 22:06:43'),
(129, 2, 'BINDO7-1', '<img style=\"\" src=\"../gambar/681f91673e178.png\" id=\"gbrsoal\"><br>', 'Pilihan Ganda Kompleks', 'sfsdf', 'sdfsdf', 'sdfsdf', 'sdfsdf', 'pilihan_1,pilihan_2,pilihan_4', 'Aktif', '2025-05-07 22:07:14'),
(131, 3, 'BINDO7-1', '<img style=\"\" src=\"../gambar/681f917dea885.png\" id=\"gbrsoal\"><br>', 'Benar/Salah', 'sdfsdf', 'sdfsdfs', 'sdfsdf', 'gdfdgdg', 'Benar|Benar|Salah|Benar', 'Aktif', '2025-05-07 22:08:01'),
(132, 4, 'BINDO7-1', '<p>Hdslkhfl lksdgsdg</p><p><img src=\"http://localhost/cbt-eschool/gambar/6827d97c1ea52.png\" id=\"gbrsoal\" style=\"\"><br></p><p></p><p></p>', 'Menjodohkan', NULL, NULL, NULL, NULL, 'Pilihan satu:pasangan satu|pilihan dua:pasangan dua|pilihan tiga:pasangan tiga|pilihan empat:pasangan empat', 'Aktif', '2025-05-07 22:08:29'),
(133, 5, 'BINDO7-1', 'perhatikan tabel<br><img src=\"../gambar/681fe8683b18f.png\" id=\"gbrsoal\" style=\"\">', 'Uraian', NULL, NULL, NULL, NULL, 'icon', 'Aktif', '2025-05-07 22:08:42'),
(137, 2, 'SR9-01', 'jawab pernyataan berikut', 'Benar/Salah', 'Seni rupa terapan diciptakan hanya untuk dinikmati', 'Seni rupa terapan diciptakan hanya untuk dijual', '', '', 'Salah|Salah', 'Aktif', '2025-05-07 22:18:24'),
(152, 3, 'SR9-01', 'jawab petanyaan', 'Benar/Salah', 'weq', 'asd', '', '', 'Benar|Salah', 'Aktif', '2025-05-07 23:06:04'),
(154, 6, 'SR9-01', 'benar apa salah', 'Benar/Salah', '<img style=\"width: 100%;\" src=\"../gambar/681f91d575243.png\" id=\"gbrsoal\"><br>', '<img style=\"width: 100%;\" src=\"../gambar/681f924a3e359.png\" id=\"gbrsoal\"><br>', '', '', 'Salah|Benar', 'Aktif', '2025-05-07 23:07:49'),
(159, 7, 'SR9-01', '<p>Literasi Generasi Z: Antara Buku Digital dan Buku Fisi', 'Pilihan Ganda Kompleks', 'fgdfgd', 'fgdfgd', 'fgd', 'fgd', 'pilihan_1,pilihan_3,pilihan_4', 'Aktif', '2025-05-07 23:11:28'),
(160, 5, 'SR9-01', '<img style=\"\" src=\"../gambar/681f91b8491bf.png\" id=\"gbrsoal\"><br>', 'Pilihan Ganda Kompleks', 'xdfg', 'xdfg', 'vdfgdf', 'xff', 'pilihan_1,pilihan_2,pilihan_4', 'Aktif', '2025-05-07 23:11:39'),
(163, 9, 'SR9-01', '<p><img src=\"../gambar/681f976012389.png\" id=\"gbrsoal\" style=\"\"><br>adasd assdasdasd&nbsp; sfwtewtwt&nbsp; gv</p><p><br></p>', 'Uraian', NULL, NULL, NULL, NULL, 'ewt', 'Aktif', '2025-05-10 18:13:58'),
(165, 11, 'SR9-01', 'sdfsd sdfsdf sdfsdfsfsf', 'Benar/Salah', '<p>sdfsdf</p>', 'sdfsdf', '', '', 'Benar|Salah', 'Aktif', '2025-05-10 18:17:23'),
(166, 12, 'SR9-01', 'aat saya mau xvxvcsdf dsfsdf<br><img src=\"../gambar/681f9a391e6ec.png\" id=\"gbrsoal\" style=\"\">', 'Uraian', NULL, NULL, NULL, NULL, 'asdsasd', 'Aktif', '2025-05-10 18:21:20'),
(167, 13, 'SR9-01', 'sata juga mau makan<br><img src=\"../gambar/681f993b9b957.png\" id=\"gbrsoal\" style=\"\">', 'Pilihan Ganda', 'asdasd', 'asdasd', 'asdsad', 'asdasd', 'pilihan_2', 'Aktif', '2025-05-10 18:22:14'),
(168, 6, 'BINDO7-1', 'saya juga akan membelinya<br><img src=\"../gambar/681f9d009ea6e.png\" id=\"gbrsoal\" style=\"width: 50%;\">', 'Benar/Salah', 'benar', 'afaf', '', '', 'Benar|Salah', 'Aktif', '2025-05-10 18:39:22'),
(169, 7, 'BINDO7-1', '<p><img style=\"\" src=\"http://localhost/cbt-eschool/gambar/6827db2065333.png\" id=\"gbrsoal\"><br></p>', 'Pilihan Ganda Kompleks', 'azis', 'Gus Azis', 'Zais', 'Dadok', 'pilihan_1,pilihan_2,pilihan_3', 'Aktif', '2025-05-10 23:53:54'),
(171, 1, 'IPA9-01', '<p>1. Perhatikan kejadian sehari-hari berikut ini!\r\n</p><p>(1) Bola basket menggelinding di lapangan basket.\r\n</p><p>(2) Budi menjatuhkan bola basket dari atas tangga ke lantai.\r\n</p><p>(3) Mobil mainan digerakkan dengan baterai.\r\n</p><p>(4) Tamia meluncur pada lintasannya.\r\nYang termasuk gerak lurus berubah beraturan ditunjukkan oleh nomor?</p>', 'Pilihan Ganda', '(1) dan (2)', '(2) dan (3)', '(3) dan (4)', '(1) dan (4)', 'pilihan_1', 'Aktif', '2025-05-13 12:42:24'),
(172, 2, 'IPA9-01', '<br><img src=\"http://localhost/cbt-eschool/gambar/68233f7be9731.png\" id=\"gbrsoal\" style=\"width: 100%;\">', 'Benar/Salah', 'Setelah pembelahan meiosis, spermatogenesis menghasilkan 4 sel anak yang berukuransama, sedangkan oogenesis menghasilkan 4 sel anak yang berukuran tidak sama', 'Waktu   yang dibutuhkan   untuk   satu   proses   spermatogenesis lebih pendek jika dibandingkan dengan satu proses oogenesis', 'Spermatogenesis dan oogenesis terjadi ketika laki-laki dan perempuan memasuki masapubertas.', 'Dalam satu proses spermatogenesis dan oogenesis dihasilkan jutaan sel kelamin', 'Benar|Salah|Benar|Benar', 'Aktif', '2025-05-13 12:49:03'),
(173, 3, 'IPA9-01', 'Perrhatikan bacaan berikut!Korpus luteum adalah badan folikel yang telah melepaskan sel telur pada saat ovulasi sehinggasering  disebut  sebagai  folikel  kosong.  Bagian  ini  berfungsi  untuk  menghasilkan  hormonprogesteron. Setelah hormon progesteron diproduksi, lapisan dinding rahim atau endometriumpada wanita mengalami pertumbuhan menebal.Perhatikan tabel ketebalan dinding rahim dan perubahan (fluktuasi) kadar hormon progesteronberikut.<br><img src=\"http://localhost/cbt-eschool/gambar/682340392f1c0.png\" id=\"gbrsoal\" style=\"\">', 'Pilihan Ganda Kompleks', 'Sekresi hormon progesteron meningkat setelah ovulasi kemudian menurun jika tidakterjadi pembuahan dan menyebabkan terjadinya menstruasi', 'Hormon progesteron segera disekresikan setelah selesai menstruasi dan menyebabkandinding rahim menebal secara perlahan', 'Tidak ada hormon progesteron pada saat menstruasi sehingga semua lapisan dindingrahim luruh', 'Sekresi hormon progesteron pada saat ovulasi paling tinggi sehingga dinding rahim palingtebal', 'pilihan_1,pilihan_2,pilihan_4', 'Aktif', '2025-05-13 12:52:22'),
(174, 4, 'IPA9-01', 'Jodohkanlah pernyataan berikut dengan jawaban yang benar!', 'Menjodohkan', NULL, NULL, NULL, NULL, 'Keterampilan proses dalam IPA pada saat menimbang buah apel menggunakan neraca:mikrometer|Kegiatan membandingkan suatu besaran dengan besaran lain yang sejenis sebagai satuan:Pengamatan|Besaran turunan yang diturunkan dari besaran pokok panjang:Jangka sorong|Alat ukur ketebalan kertas:diafragma', 'Aktif', '2025-05-13 12:56:35'),
(175, 5, 'IPA9-01', 'Kelebihan lampu smart LED adalah hemat daya dan tahan lama. Lampu LED dapatbertahan  hingga  15.000  jam  penggunaan.  Sebagai  perbandingan,  lampu  bohlamkonvensional umurnya hanya...', 'Uraian', NULL, NULL, NULL, NULL, 'hanya sampai 1.000 hingga 2.000 jam penggunaan saja', 'Aktif', '2025-05-13 12:57:53'),
(176, 8, 'SR9-01', '<p><img style=\"\" src=\"http://localhost/cbt-eschool/gambar/6825eec81c17d.png\" id=\"gbrsoal\"><br></p>', 'Pilihan Ganda Kompleks', 'asaf', 'gfrsg', 'hfjhgf', 'ngfjhfd', 'pilihan_1,pilihan_2', 'Aktif', '2025-05-15 13:40:39'),
(178, 10, 'SR9-01', 'sfsdfsfdsf', 'Menjodohkan', NULL, NULL, NULL, NULL, 'sdfsdf:sdf|sdf:sdddff', 'Aktif', '2025-05-17 03:05:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `faq`
--

CREATE TABLE `faq` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `faq`
--

INSERT INTO `faq` (`id`, `question`, `answer`) VALUES
(1, 'Bagaimana cara mengikuti ujian CBT?', 'Siswa dapat mengikuti ujian dengan login ke dashboard, memilih menu \'Ujian\', dan mengikuti instruksi yang tersedia.'),
(2, 'Apa yang harus dilakukan jika lupa password?', 'Hubungi admin atau Guru untuk reset password'),
(3, 'Apakah ujian dapat diulang jika koneksi terputus?', 'Jika koneksi terputus jawaban masih tersimpan dan kamu bisa melanjutkan ujian lagi. Silakan hubungi guru atau admin untuk informasi lebih lanjut.'),
(4, 'Bagaimana cara melihat hasil ujian?', 'Setelah ujian selesai, hasil dapat dilihat pada menu \'Nilai\' di dashboard siswa.'),
(5, 'Perangkat apa saja yang dapat digunakan untuk ujian CBT?', 'Ujian dapat diakses melalui komputer, laptop, atau perangkat mobile dengan koneksi internet yang stabil.'),
(6, 'Saya tiba-tiba keluar dari aplikasi ujian. Bagaimana cara masuk kembali?', 'Silakan buka kembali aplikasi ujian seperti biasa,  Jika tidak bisa masuk atau muncul pesan error, segera hubungi pengawas atau admin ujian untuk reset login.');

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
(2, 3, 'Agum Gumelar', 'SR9-01', '', '[1:pilihan_3],[2:Salah|Salah],[3:Benar|Benar],[4:Teknik Mozaik:Karya seni yang dibuat untuk dinikmati keindahannya, seperti lukisan|Seni Rupa Murni:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu],[5:pilihan_1,pilihan_2,pilihan_4],[6:Salah|Benar],[7:pilihan_1,pilihan_2]', '54', '2025-05-10 16:10:29', 'Aktif'),
(14, 1, 'Jokowi JK', 'SR9-01', '', '[1:pilihan_3][2:Benar|Salah][3:Benar|Benar][4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu][5:pilihan_1,pilihan_2][6:Salah|Benar][7:pilihan_2,pilihan_3,pilihan_4][8:pilihan_1,pilihan_2][9:ewt][10:sdfsdf:sdf|sdf:sdddff][11:Benar|Salah][12:asdsasd][13:pilihan_2]', '37', '2025-05-15 21:47:59', 'Aktif'),
(27, 20, 'Robi', 'SR9-01', '', '[1:pilihan_2][2:Benar|Benar][3:Benar|Benar][4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu][5:pilihan_1,pilihan_2][6:Benar|Salah][9:][10:][12:][13:pilihan_2]', '43', '2025-05-15 22:05:32', 'Aktif'),
(49, 4, 'Deddy ', 'SR9-01', '', '[1:pilihan_1][2:Benar,Salah][3:Salah,Benar][4:Teknik Mozaik:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Seni Rupa Murni:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Relief:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan][5:pilihan_1,pilihan_2,pilihan_3][6:Salah,Benar][7:pilihan_2,pilihan_3][9:][10:saya jua:asdasd][11:Salah,Benar][12:hvjhvkjg][13:pilihan_2]', '34', '2025-05-15 22:16:31', 'Non-Aktif'),
(537, 15, 'Zevan', 'BINDO7-1', '', '[1:pilihan_4][2:pilihan_1,pilihan_2,pilihan_4][3:Benar|Benar|Salah|Benar][4:Pilihan satu:pasangan satu|pilihan dua:pasangan dua|pilihan tiga:pasangan tiga|pilihan empat:pasangan empat][5:icon][6:Benar|Salah][7:pilihan_1,pilihan_2,pilihan_3]', '18', '2025-05-17 01:57:58', 'Aktif');

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
  `jawaban_kurang` varchar(100) NOT NULL,
  `jawaban_siswa` text NOT NULL,
  `kunci` text NOT NULL,
  `nilai` text NOT NULL,
  `tanggal_ujian` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaturan`
--

CREATE TABLE `pengaturan` (
  `id` int(11) NOT NULL DEFAULT 1,
  `nama_aplikasi` varchar(100) DEFAULT 'CBT Siswa',
  `logo_sekolah` varchar(255) DEFAULT '',
  `warna_tema` varchar(10) DEFAULT '#0d6efd',
  `waktu_sinkronisasi` int(11) DEFAULT 60,
  `sembunyikan_nilai` tinyint(1) DEFAULT 0,
  `login_ganda` enum('izinkan','blokir') DEFAULT 'blokir'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengaturan`
--

INSERT INTO `pengaturan` (`id`, `nama_aplikasi`, `logo_sekolah`, `warna_tema`, `waktu_sinkronisasi`, `sembunyikan_nilai`, `login_ganda`) VALUES
(1, 'CBT-Eschool v.1.0.0', 'codelite2.png', '#787878', 60, 0, 'izinkan');

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
  `status` text NOT NULL DEFAULT 'Nonaktif',
  `session_token` varchar(255) NOT NULL,
  `last_activity` datetime DEFAULT NULL,
  `page_url` text NOT NULL,
  `force_logout` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nama_siswa`, `password`, `username`, `kelas`, `rombel`, `status`, `session_token`, `last_activity`, `page_url`, `force_logout`) VALUES
(1, 'Jokowi JK', 'h7fV3os6WcZ+hNwtoIN5Si9hbEVndnEzRmNodzJlSktYZ2hVMUE9PQ==', '123456', '9', 'A', 'Nonaktif', '2caf4a3c85fd8a5823744e93d73004660b3a0e822afe87a1f1726692b475d60e', '2025-05-17 10:11:44', 'http://localhost/cbt-eschool/siswa/mulaiujian.php', 0),
(2, 'Prabowo', 'm9MaPSetPwkYW68qNsWwUlUrOW9HNWFlRzJRVENVVi9xNW9vN0E9PQ==', '123457', '9', 'B', 'Nonaktif', '', '2025-05-12 23:31:49', 'http://localhost/cbt-eschool/siswa/dashboard.php', 1),
(3, 'Agum Gumelar', '5mv6Upz6eP/GpQrkjcebOHcyOFNxV2RRT2xQdkVxRUh0ZVZ0d3c9PQ==', '123458', '9', 'C', 'nonaktif', '', '2025-05-12 23:31:58', 'http://localhost/cbt-eschool/siswa/dashboard.php', 1),
(4, 'Deddy ', '5uKDYI7JoYmjpgBTg8LxUi9YZ2dIVGFucU5FM2wySDYvcmFVQXc9PQ==', '123459', '9', 'D', 'nonaktif', '21de4e6987afb196892873b5e8992f3494c33f0f29872d5141328bd3de652771', '2025-05-16 08:43:46', 'http://localhost/cbt-eschool/siswa/mulaiujian.php#', 0),
(5, 'Corbuzier', '/SbMMmTczf7Ry0qUn/f6XmhpM1BYS0l6S1F0cmlHSlB3ZjE1cEE9PQ==', '123461', '9', 'E', 'Nonaktif', '', '2025-05-12 23:32:10', 'http://localhost/cbt-eschool/siswa/dashboard.php', 1),
(13, 'Erina', 'FQOm8MYUIes79E36AQv1AU5VMVdUanhIaTBVTURVS0hXckFRUXc9PQ==', '721731', '9', 'A', 'Nonaktif', '', '2025-05-12 23:32:14', 'http://localhost/cbt-eschool/siswa/dashboard.php', 1),
(14, 'Phoebe', 'Dwl3VYW4ysVVEjO67sy6QmdYb2h0NjNFWjhlV3ViamtWY01hc0E9PQ==', '122345', '7', 'C', 'Nonaktif', '', '2025-05-15 02:04:10', 'http://localhost/cbt-eschool/siswa/ujian.php', 0),
(15, 'Zevan', 'mG5EAQl0ttZQFaqBXlYCgGdMVkdTMjNQQXZ3VmRKdmFNbTJBeEE9PQ==', '257174', '7', 'D', 'Nonaktif', 'e2d4be3016c1ca44f0bfebf1ec5a22a9265e6247a6ca6c2c20b287541018fdc3', '2025-05-17 10:10:19', 'http://localhost/cbt-eschool/siswa/mulaiujian.php', 0),
(16, 'Denny', 'N2ugxO2xwJR74bjbZQv19nYrMFVFbi9JTEk5MFNDeVdITWxmM0E9PQ==', '641343', '8', 'F', 'Nonaktif', '', '2025-05-12 23:32:55', 'http://localhost/cbt-eschool/siswa/ujian.php', 1),
(17, 'Lintar', 'CJ7fgqg1+lzEgNuqTQwdCUtBeHlsdXdGU3FabGdhQ3lQbXQ2NlE9PQ==', '252743', '8', 'D', 'Nonaktif', '', '2025-05-12 23:32:51', 'http://localhost/cbt-eschool/siswa/dashboard.php', 1),
(18, 'andy', '5IiPhwyWU7/GiyYe622atFErOVViUmNXOXRheVk0Z2U1V0tiK2c9PQ==', '876543', '8', 'D', 'Nonaktif', '', '2025-05-12 23:32:47', 'http://localhost/cbt-eschool/siswa/dashboard.php', 1),
(20, 'Robi', 'XpqGGiL6DfhOGnPqcV9EnUdLdTlEbzdwN1pyVE5wb2FJSStLdUE9PQ==', '252645', '9', 'G', 'Nonaktif', '', '2025-05-17 07:50:31', 'http://localhost/cbt-eschool/siswa/ujian.php', 0),
(21, 'Intan', 'Ya+NHgRRNME9cYTmSRYUz2sxMS9tczlZMGJDaFd0NkN5TzErV0E9PQ==', '1241322', '7', 'B', 'Nonaktif', '', '2025-05-13 01:10:23', 'http://localhost/cbt-eschool/siswa/dashboard.php', 0);

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
(1, 'SR9-01', 'Seni Rupa 1', 'Seni Rupa', '9', 90, '2025-05-16', 'Aktif', '[1:pilihan_3],[2:Salah|Salah],[3:Benar|Salah],[4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu],[5:pilihan_1,pilihan_2,pilihan_4],[6:Salah|Benar],[7:pilihan_1,pilihan_3,pilihan_4],[8:pilihan_1,pilihan_2],[9:ewt],[10:sdfsdf:sdf|sdf:sdfdfgdfff],[11:Benar|Salah],[12:asdsasd],[13:pilihan_2]', 'ODBYQN'),
(10, 'BINDO7-1', 'B. Indonesia', 'Bahasa Indo', '7', 90, '2025-05-15', 'Aktif', '[1:pilihan_4],[2:pilihan_1,pilihan_2,pilihan_4],[3:Benar|Benar|Salah|Benar],[4:Pilihan satu:pasangan satu|pilihan dua:pasangan dua|pilihan tiga:pasangan tiga|pilihan empat:pasangan empat],[5:icon],[6:Benar|Salah],[7:pilihan_1,pilihan_2,pilihan_3]', 'DCHTWY'),
(17, 'IPA9-01', 'IPA 01', 'IPA', '9', 90, '2025-05-19', 'Nonaktif', '[1:pilihan_1],[2:Benar|Salah|Benar|Benar],[3:pilihan_1,pilihan_2,pilihan_4],[4:Keterampilan proses dalam IPA pada saat menimbang buah apel menggunakan neraca:mikrometer|Kegiatan membandingkan suatu besaran dengan besaran lain yang sejenis sebagai satuan:Pengamatan|Besaran turunan yang diturunkan dari besaran pokok panjang:Jangka sorong|Alat ukur ketebalan kertas:diafragma],[5:hanya sampai 1.000 hingga 2.000 jam penggunaan saja]', '');

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
  ADD PRIMARY KEY (`id_nilai`);

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
  MODIFY `id_soal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=179;

--
-- AUTO_INCREMENT untuk tabel `faq`
--
ALTER TABLE `faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `jawaban_siswa`
--
ALTER TABLE `jawaban_siswa`
  MODIFY `id_jawaban` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=598;

--
-- AUTO_INCREMENT untuk tabel `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `soal`
--
ALTER TABLE `soal`
  MODIFY `id_soal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
