-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 22 Bulan Mei 2025 pada 19.44
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
(1, 'admin', 'Gludug', '$2y$10$HqRnKQWr17V5cWpwXUv5t.BPtuiYBfvECrldmsQMNowVPjZXnEKwC', '2025-05-05 09:13:31');

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
(55, 4, 'SR9-01', 'Jodohkan antara kolom A (istilah seni rupa) dengan kolom B (penjelasan atau contoh yang sesuai)!', 'Menjodohkan', '', '', '', '', 'Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu', 'Aktif', '2025-05-06 09:23:24'),
(127, 1, 'BINDO7-1', '<img style=\"\" src=\"../gambar/681f9154c46e8.png\" id=\"gbrsoal\"><br>\r\n\r\nPertanyaan<br>', 'Pilihan Ganda', 'asd', 'asdasd', 'asdasd', 'asd', 'pilihan_4', 'Aktif', '2025-05-07 22:06:43'),
(129, 2, 'BINDO7-1', '<img style=\"\" src=\"../gambar/681f91673e178.png\" id=\"gbrsoal\"><br>', 'Pilihan Ganda Kompleks', 'sfsdf', 'sdfsdf', 'sdfsdf', 'sdfsdf', 'pilihan_1,pilihan_2,pilihan_4', 'Aktif', '2025-05-07 22:07:14'),
(131, 3, 'BINDO7-1', '<img style=\"\" src=\"../gambar/681f917dea885.png\" id=\"gbrsoal\"><br>', 'Benar/Salah', 'sdfsdf', 'sdfsdfs', 'sdfsdf', 'gdfdgdg', 'Benar|Benar|Salah|Benar', 'Aktif', '2025-05-07 22:08:01'),
(132, 4, 'BINDO7-1', '<p>Hdslkhfl lksdgsdg</p><p><img src=\"http://localhost/cbt-eschool/gambar/6827d97c1ea52.png\" id=\"gbrsoal\" style=\"\"><br></p><p></p><p></p>', 'Menjodohkan', '', '', '', '', 'Pilihan satu:pasangan satu|pilihan dua:pasangan dua|pilihan tiga:pasangan tiga|pilihan empat:pasangan empat', 'Aktif', '2025-05-07 22:08:29'),
(133, 5, 'BINDO7-1', 'perhatikan tabel<br><img src=\"../gambar/681fe8683b18f.png\" id=\"gbrsoal\" style=\"\">', 'Uraian', '', '', '', '', 'icon', 'Aktif', '2025-05-07 22:08:42'),
(137, 2, 'SR9-01', 'jawab pernyataan berikut', 'Benar/Salah', 'Seni rupa terapan diciptakan hanya untuk dinikmati', 'Seni rupa terapan diciptakan hanya untuk dijual', '', '', 'Salah|Salah', 'Aktif', '2025-05-07 22:18:24'),
(152, 3, 'SR9-01', 'jawab petanyaan', 'Benar/Salah', 'weq', 'asd', '', '', 'Benar|Salah', 'Aktif', '2025-05-07 23:06:04'),
(154, 6, 'SR9-01', 'benar apa salah', 'Benar/Salah', '<img style=\"width: 100%;\" src=\"../gambar/681f91d575243.png\" id=\"gbrsoal\"><br>', '<img style=\"width: 100%;\" src=\"../gambar/681f924a3e359.png\" id=\"gbrsoal\"><br>', '', '', 'Salah|Benar', 'Aktif', '2025-05-07 23:07:49'),
(159, 7, 'SR9-01', '<p>Literasi Generasi Z: Antara Buku Digital dan Buku Fisi', 'Pilihan Ganda Kompleks', 'fgdfgd', 'fgdfgd', 'fgd', 'fgd', 'pilihan_1,pilihan_3,pilihan_4', 'Aktif', '2025-05-07 23:11:28'),
(160, 5, 'SR9-01', '<img style=\"\" src=\"../gambar/681f91b8491bf.png\" id=\"gbrsoal\"><br>', 'Pilihan Ganda Kompleks', 'xdfg', 'xdfg', 'vdfgdf', 'xff', 'pilihan_1,pilihan_2,pilihan_4', 'Aktif', '2025-05-07 23:11:39'),
(163, 9, 'SR9-01', '<p><img src=\"../gambar/681f976012389.png\" id=\"gbrsoal\" style=\"\"><br>adasd assdasdasd&nbsp; sfwtewtwt&nbsp; gv</p><p><br></p>', 'Uraian', '', '', '', '', 'ewt', 'Aktif', '2025-05-10 18:13:58'),
(165, 11, 'SR9-01', 'sdfsd sdfsdf sdfsdfsfsf', 'Benar/Salah', '<p>sdfsdf</p>', 'sdfsdf', '', '', 'Benar|Salah', 'Aktif', '2025-05-10 18:17:23'),
(166, 12, 'SR9-01', 'aat saya mau xvxvcsdf dsfsdf<br><img src=\"../gambar/681f9a391e6ec.png\" id=\"gbrsoal\" style=\"\">', 'Uraian', '', '', '', '', 'asdsasd', 'Aktif', '2025-05-10 18:21:20'),
(167, 13, 'SR9-01', 'sata juga mau makan<br><img src=\"../gambar/681f993b9b957.png\" id=\"gbrsoal\" style=\"\">', 'Pilihan Ganda', 'asdasd', 'asdasd', 'asdsad', 'asdasd', 'pilihan_2', 'Aktif', '2025-05-10 18:22:14'),
(168, 6, 'BINDO7-1', 'saya juga akan membelinya<br><img src=\"../gambar/681f9d009ea6e.png\" id=\"gbrsoal\" style=\"width: 50%;\">', 'Benar/Salah', 'benar', 'afaf', '', '', 'Benar|Salah', 'Aktif', '2025-05-10 18:39:22'),
(169, 7, 'BINDO7-1', '<p><img style=\"\" src=\"http://localhost/cbt-eschool/gambar/6827db2065333.png\" id=\"gbrsoal\"><br></p>', 'Pilihan Ganda Kompleks', 'azis', 'Gus Azis', 'Zais', 'Dadok', 'pilihan_1,pilihan_2,pilihan_3', 'Aktif', '2025-05-10 23:53:54'),
(171, 1, 'IPA9-01', '<p>1. Perhatikan kejadian sehari-hari berikut ini!\r\n</p><p>(1) Bola basket menggelinding di lapangan basket.\r\n</p><p>(2) Budi menjatuhkan bola basket dari atas tangga ke lantai.\r\n</p><p>(3) Mobil mainan digerakkan dengan baterai.\r\n</p><p>(4) Tamia meluncur pada lintasannya.\r\nYang termasuk gerak lurus berubah beraturan ditunjukkan oleh nomor?</p>', 'Pilihan Ganda', '(1) dan (2)', '(2) dan (3)', '(3) dan (4)', '(1) dan (4)', 'pilihan_1', 'Aktif', '2025-05-13 12:42:24'),
(172, 2, 'IPA9-01', '<br><img src=\"http://localhost/cbt-eschool/gambar/68233f7be9731.png\" id=\"gbrsoal\" style=\"width: 100%;\">', 'Benar/Salah', 'Setelah pembelahan meiosis, spermatogenesis menghasilkan 4 sel anak yang berukuransama, sedangkan oogenesis menghasilkan 4 sel anak yang berukuran tidak sama', 'Waktu   yang dibutuhkan   untuk   satu   proses   spermatogenesis lebih pendek jika dibandingkan dengan satu proses oogenesis', 'Spermatogenesis dan oogenesis terjadi ketika laki-laki dan perempuan memasuki masapubertas.', 'Dalam satu proses spermatogenesis dan oogenesis dihasilkan jutaan sel kelamin', 'Benar|Salah|Benar|Benar', 'Aktif', '2025-05-13 12:49:03'),
(173, 3, 'IPA9-01', 'Perrhatikan bacaan berikut!Korpus luteum adalah badan folikel yang telah melepaskan sel telur pada saat ovulasi sehinggasering  disebut  sebagai  folikel  kosong.  Bagian  ini  berfungsi  untuk  menghasilkan  hormonprogesteron. Setelah hormon progesteron diproduksi, lapisan dinding rahim atau endometriumpada wanita mengalami pertumbuhan menebal.Perhatikan tabel ketebalan dinding rahim dan perubahan (fluktuasi) kadar hormon progesteronberikut.<br><img src=\"http://localhost/cbt-eschool/gambar/682340392f1c0.png\" id=\"gbrsoal\" style=\"\">', 'Pilihan Ganda Kompleks', 'Sekresi hormon progesteron meningkat setelah ovulasi kemudian menurun jika tidakterjadi pembuahan dan menyebabkan terjadinya menstruasi', 'Hormon progesteron segera disekresikan setelah selesai menstruasi dan menyebabkandinding rahim menebal secara perlahan', 'Tidak ada hormon progesteron pada saat menstruasi sehingga semua lapisan dindingrahim luruh', 'Sekresi hormon progesteron pada saat ovulasi paling tinggi sehingga dinding rahim palingtebal', 'pilihan_1,pilihan_2,pilihan_4', 'Aktif', '2025-05-13 12:52:22'),
(174, 4, 'IPA9-01', 'Jodohkanlah pernyataan berikut dengan jawaban yang benar!', 'Menjodohkan', '', '', '', '', 'Keterampilan proses dalam IPA pada saat menimbang buah apel menggunakan neraca:mikrometer|Kegiatan membandingkan suatu besaran dengan besaran lain yang sejenis sebagai satuan:Pengamatan|Besaran turunan yang diturunkan dari besaran pokok panjang:Jangka sorong|Alat ukur ketebalan kertas:diafragma', 'Aktif', '2025-05-13 12:56:35'),
(175, 5, 'IPA9-01', 'Kelebihan lampu smart LED adalah hemat daya dan tahan lama. Lampu LED dapatbertahan  hingga  15.000  jam  penggunaan.  Sebagai  perbandingan,  lampu  bohlamkonvensional umurnya hanya...', 'Uraian', '', '', '', '', 'hanya sampai 1.000 hingga 2.000 jam penggunaan saja', 'Aktif', '2025-05-13 12:57:53'),
(176, 8, 'SR9-01', '<p><img style=\"\" src=\"http://localhost/cbt-eschool/gambar/6825eec81c17d.png\" id=\"gbrsoal\"><br></p>', 'Pilihan Ganda Kompleks', 'asaf', 'gfrsg', 'hfjhgf', 'ngfjhfd', 'pilihan_1,pilihan_2', 'Aktif', '2025-05-15 13:40:39'),
(178, 10, 'SR9-01', 'sfsdfsfdsf', 'Menjodohkan', '', '', '', '', 'sdfsdf:sdf|sdf:sdfdfgdfff', 'Aktif', '2025-05-17 03:05:17'),
(183, 1, 'MAT9-01', '<p><img style=\"\" src=\"http://localhost/cbt-eschool/gambar/682d8ead64c7b.jpg\" id=\"gbrsoal\"></p><p>Sampah anorganik lebih lama terurai dibandingkan dengan sampah organik. Waktu dekomposisi popok sekali pakai lebih lama dari plastik, namun kurang dari kulit sintetis. Berapa waktu dekomposisi yang mungkin dari popok sekali pakai?</p>', 'Pilihan Ganda', '100 tahun', '250 tahun', '375 tahun', '475 tahun', 'pilihan_4', 'Aktif', '2025-05-21 08:29:07'),
(184, 2, 'MAT9-01', '<p><img style=\"\" src=\"http://localhost/cbt-eschool/gambar/682d8f5650e12.jpg\" id=\"gbrsoal\"></p><p>Pilih Benar atau Salah pada setiap pernyataan berikut!</p><p><br></p>', 'Benar/Salah', 'Panjang AB = Panjang CD', 'Panjang PQ = Panjang SR', 'Jarak Q ke S = Jarak B ke C', '', 'Benar|Benar|Salah', 'Aktif', '2025-05-21 08:31:24'),
(185, 3, 'MAT9-01', 'Suatu kali, PT Suka-Suka Kalian mendapatkan pesanan 30 unit tenda dengan bentuk dan ukuran seperti di atas. Waktu penyelesaian yang diperlukan untuk memenuhi seluruh pesanan adalah 20 hari kerja.<br><br>Berdasarkan keterangan di atas, berilah tanda Benar atau Salah untuk setiap pernyataan berikut!', 'Benar/Salah', 'Waktu rata-rata pembuatan 3 buah tenda adalah 2 hari.', 'Waktu penyelesaian semua pesanan bisa tepat waktu jika dalam sehari dihasilkan sebuah tenda.', 'Jika pesanan bertambah 5 tenda, lama penyelesaian bertambah 2 hari.', 'Jika dalam sehari dapat dihasilkan 2 tenda, waktu penyelesaian seluruh pesanan menjadi 5 hari lebih cepat.', 'Salah|Salah|Salah|Benar', 'Aktif', '2025-05-21 08:37:06'),
(186, 4, 'MAT9-01', 'Biskuit merupakan camilan yang banyak digemari sebagai pelengkap minum teh setiap waktu. Berikut komposisi 2 jenis biskuit yang sering dijual di pasaran:<br><br>(I) Komposisi Biskuit Sehat (berat 149 g):<br><br>&nbsp;&nbsp;&nbsp; Lemak total: 9%<br>&nbsp;&nbsp;&nbsp; Lemak jenuh: 20%<br>&nbsp;&nbsp;&nbsp; Protein: 3%<br>&nbsp;&nbsp;&nbsp; Karbohidrat total: 6%<br>&nbsp;&nbsp;&nbsp; Natrium: 10%<br><br>(II) Komposisi Biskuit Lezat (250 g):<br><br>&nbsp;&nbsp;&nbsp; Lemak total: 8%<br>&nbsp;&nbsp;&nbsp; Lemak jenuh: 16%<br>&nbsp;&nbsp;&nbsp; Protein: 2%<br>&nbsp;&nbsp;&nbsp; Karbohidrat total: 4%<br>&nbsp;&nbsp;&nbsp; Natrium: 5%<br><br>Berdasarkan informasi di atas, pilihlah pernyataan-pernyataan berikut yang benar:', 'Pilihan Ganda Kompleks', 'Komposisi protein Biskuit Lezat adalah 0,02 bagian', 'Komposisi natrium Biskuit Sehat adalah 0,01 bagian', 'Komposisi lemak jenuh Biskuit Lezat adalah 0,16 bagian', 'Komposisi lemak jenuh Biskuit Sehat adalah 0,02 bagian', 'pilihan_1,pilihan_3', 'Aktif', '2025-05-21 08:38:42'),
(187, 5, 'MAT9-01', 'Pasangkan pernyataan di kolom kiri dengan jawaban yang tepat di kolom kanan dengan menulis huruf di depan nomor yang sesuai!', 'Menjodohkan', NULL, NULL, NULL, NULL, 'Bilangan prima antara 10 dan 15:11 dan 13|Volume kubus dengan rusuk 4 cm:64 cm³|Luas persegi dengan sisi 6 cm:36 cm²|Nilai x dari 2x+5=19:7', 'Aktif', '2025-05-21 08:51:02'),
(188, 6, 'MAT9-01', 'Faktor persekutuan terbesar (FPB) dari 12 dan 18', 'Uraian', NULL, NULL, NULL, NULL, '6', 'Aktif', '2025-05-21 08:51:30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `chat`
--

CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `pesan` text NOT NULL,
  `waktu` datetime DEFAULT current_timestamp(),
  `deleted` tinyint(1) DEFAULT 0,
  `role` enum('siswa','admin') NOT NULL DEFAULT 'siswa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `chat`
--

INSERT INTO `chat` (`id`, `id_user`, `pesan`, `waktu`, `deleted`, `role`) VALUES
(1, 17, '😊', '2025-05-20 22:41:45', 0, 'siswa'),
(2, 17, 'Halo', '2025-05-20 22:42:18', 1, 'siswa'),
(3, 17, 'gfh', '2025-05-20 22:46:03', 1, 'siswa'),
(4, 17, 'fgh', '2025-05-20 22:46:17', 1, 'siswa'),
(5, 17, '👍👍', '2025-05-20 22:47:10', 1, 'siswa'),
(6, 17, '🔥🔥🔥🔥🔥', '2025-05-20 22:47:43', 0, 'siswa'),
(7, 17, 'fg', '2025-05-20 22:47:58', 0, 'siswa'),
(8, 5, 'apalah???😊', '2025-05-20 22:48:59', 0, 'siswa'),
(9, 17, 'gfsdg', '2025-05-20 22:49:45', 0, 'siswa'),
(10, 17, 'sdfs', '2025-05-20 22:55:02', 0, 'siswa'),
(11, 5, '👍', '2025-05-20 22:58:36', 1, 'siswa'),
(12, 5, '👍', '2025-05-20 22:58:41', 1, 'siswa'),
(13, 5, '👍', '2025-05-20 22:58:45', 1, 'siswa'),
(14, 5, '👍', '2025-05-20 22:58:48', 1, 'siswa'),
(15, 5, '😊', '2025-05-20 22:58:52', 1, 'siswa'),
(16, 5, 'spam', '2025-05-20 23:00:23', 0, 'siswa'),
(17, 5, 's', '2025-05-20 23:00:37', 1, 'siswa'),
(18, 5, 'sdf', '2025-05-20 23:04:03', 0, 'siswa'),
(19, 17, 'xcv', '2025-05-20 23:04:33', 1, 'siswa'),
(20, 17, 'bv', '2025-05-20 23:08:15', 0, 'siswa'),
(21, 17, 'ssd', '2025-05-20 23:09:37', 1, 'siswa'),
(22, 17, '😊', '2025-05-20 23:15:30', 0, 'siswa'),
(23, 17, 'v', '2025-05-20 23:28:08', 1, 'siswa'),
(24, 5, '🚀🙏🥳', '2025-05-20 23:29:53', 1, 'siswa'),
(25, 17, 'xcvbxc', '2025-05-20 23:43:35', 1, 'siswa'),
(26, 17, 'sdfsdfsd sfsdfsdf sdfsd sdsf sdf', '2025-05-20 23:49:42', 0, 'siswa'),
(27, 17, 'xcvfdsddddddddddddddsfsfsdf', '2025-05-20 23:55:36', 1, 'siswa'),
(28, 17, 'fgdfsg dfgdfg dfgd dfg dfg  dfgd gfdg df gdfg dffg dfgdfg dfg dfg dfg dfg dfg', '2025-05-20 23:56:57', 1, 'siswa'),
(29, 17, 'sdfsd sef', '2025-05-20 23:57:35', 1, 'siswa'),
(30, 17, 'xvxc', '2025-05-20 23:57:47', 1, 'siswa'),
(31, 17, '😡', '2025-05-21 00:30:14', 0, 'siswa'),
(33, 17, 'hehe', '2025-05-21 00:39:03', 0, 'siswa'),
(34, 1, 'hmm', '2025-05-21 00:39:14', 0, 'admin'),
(35, 17, '🥳', '2025-05-21 01:00:54', 0, 'siswa'),
(36, 17, '😡', '2025-05-21 01:44:48', 0, 'siswa'),
(37, 1, 'opo ae tar lintar', '2025-05-21 02:05:39', 0, 'admin'),
(38, 17, 'tes', '2025-05-21 15:19:07', 0, 'siswa'),
(39, 1, 'apa?', '2025-05-21 15:19:13', 0, 'admin'),
(40, 1, 'hehe', '2025-05-21 15:19:57', 0, 'siswa'),
(41, 17, 'hmm', '2025-05-21 15:20:10', 0, 'siswa'),
(42, 16, '@bandy @joni', '2025-05-21 21:26:31', 0, 'siswa'),
(43, 16, '🥳🥳', '2025-05-21 21:26:43', 0, 'siswa'),
(44, 2, 'hore', '2025-05-21 22:07:11', 0, 'siswa'),
(45, 1, 'v', '2025-05-22 00:41:02', 0, 'admin'),
(46, 1, 'apalah', '2025-05-22 00:42:11', 0, 'admin'),
(47, 2, 'xcv', '2025-05-22 00:43:08', 0, 'siswa'),
(48, 1, 'df', '2025-05-22 00:45:37', 1, 'admin'),
(49, 2, 'sdfsf🥳', '2025-05-22 00:45:46', 1, 'siswa'),
(50, 2, 'xc', '2025-05-22 00:49:15', 0, 'siswa'),
(51, 2, '🙏', '2025-05-22 00:51:51', 1, 'siswa'),
(52, 2, '🙏🥳😎', '2025-05-22 00:52:06', 0, 'siswa'),
(53, 1, '😡🎉', '2025-05-22 00:54:06', 1, 'admin'),
(54, 1, 'cvvcbc cvb', '2025-05-22 01:02:34', 0, 'admin'),
(55, 1, '😡🤔🚀', '2025-05-22 03:11:22', 1, 'siswa'),
(56, 1, '🙏', '2025-05-22 10:30:41', 1, 'admin'),
(57, 5, '😎halo guys apa kabar semuanya', '2025-05-23 00:41:28', 0, 'siswa'),
(58, 5, 'xc', '2025-05-23 00:41:48', 1, 'siswa');

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
(1, 'cara Ujian?', 'Siswa dapat mengikuti ujian dengan login ke dashboard, memilih menu \'Ujian\', dan mengikuti instruksi yang tersedia.'),
(2, 'Lupa password', 'Hubungi admin atau Guru untuk reset password'),
(3, 'jawaban hilang', 'Jika koneksi terputus jawaban masih tersimpan dan kamu bisa melanjutkan ujian lagi. Silakan hubungi guru atau admin untuk informasi lebih lanjut.'),
(4, 'hasil ujian', 'Setelah ujian selesai, hasil dapat dilihat pada menu \'Nilai\' di dashboard siswa.'),
(5, 'Perangkat', 'Ujian dapat diakses melalui komputer, laptop, atau perangkat mobile dengan koneksi internet yang stabil.'),
(6, 'Terputus', 'Silakan buka kembali aplikasi ujian seperti biasa,  Jika tidak bisa masuk atau muncul pesan error, segera hubungi pengawas atau admin ujian untuk reset login.'),
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
  `total_soal` text NOT NULL,
  `jawaban_siswa` text DEFAULT NULL,
  `waktu_sisa` text NOT NULL,
  `waktu_dijawab` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_ujian` enum('Aktif','Non-Aktif','Selesai') DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jawaban_siswa`
--

INSERT INTO `jawaban_siswa` (`id_jawaban`, `id_siswa`, `nama_siswa`, `kode_soal`, `total_soal`, `jawaban_siswa`, `waktu_sisa`, `waktu_dijawab`, `status_ujian`) VALUES
(2, 3, 'Agum Gumelar', 'SR9-01', '', '[1:pilihan_3][2:Salah|Salah][3:Benar|Benar][4:Seni Rupa Murni:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu][5:pilihan_1,pilihan_2,pilihan_4][6:Salah|Benar][7:pilihan_1,pilihan_2][8:pilihan_1,pilihan_2,pilihan_4][9:wet][10:sdfsdf:sdf|sdf:sdddff][11:Salah|Benar][12:wet][13:pilihan_2]', '53', '2025-05-10 16:10:29', 'Selesai'),
(14, 1, 'Jokowi JK', 'SR9-01', '', '[1:pilihan_3][2:Benar|Salah][3:Benar|Benar][4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu][5:pilihan_1,pilihan_2][6:Salah|Benar][7:pilihan_2,pilihan_3,pilihan_4][8:pilihan_1,pilihan_2][9:ewt][10:sdfsdf:sdf|sdf:sdfdfgdfff][11:Benar|Salah][12:asdsasd][13:pilihan_1]', '60', '2025-05-15 21:47:59', 'Selesai'),
(27, 20, 'Robi', 'SR9-01', '', '[1:pilihan_2][2:Benar|Benar][3:Benar|Benar][4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu][5:pilihan_1,pilihan_2][6:Benar|Salah][7:pilihan_1,pilihan_2,pilihan_3][8:pilihan_1,pilihan_3][9:tydul][10:sdfsdf:sdf|sdf:sdddff][11:Benar|Salah][12:nb][13:pilihan_2]', '42', '2025-05-15 22:05:32', 'Selesai'),
(49, 4, 'Deddy ', 'SR9-01', '', '[1:pilihan_1][2:Benar|Salah][3:Salah|Benar][4:Teknik Mozaik:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Seni Rupa Murni:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Relief:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan][5:pilihan_1,pilihan_2,pilihan_3][6:Salah|Benar][7:pilihan_2,pilihan_3][8:pilihan_1,pilihan_3][9:fytf][10:sdfsdf:sdf|sdf:sdddff][11:Salah|Benar][12:hvjhvkjg][13:pilihan_2]', '33', '2025-05-15 22:16:31', 'Selesai'),
(537, 15, 'Zevan', 'BINDO7-1', '', '[1:pilihan_4][2:pilihan_1,pilihan_2,pilihan_4][3:Benar|Benar|Salah|Benar][4:Pilihan satu:pasangan satu|pilihan dua:pasangan dua|pilihan tiga:pasangan tiga|pilihan empat:pasangan empat][5:icon][6:Benar|Salah][7:pilihan_1,pilihan_2,pilihan_3]', '0', '2025-05-17 01:57:58', 'Selesai'),
(667, 14, 'Phoebe', 'BINDO7-1', '', '[1:pilihan_2][2:pilihan_2,pilihan_3][3:Benar|Benar|Salah|Benar][4:Pilihan satu:pasangan satu|pilihan dua:pasangan dua|pilihan tiga:pasangan tiga|pilihan empat:pasangan empat][5:icon][6:Benar|Salah][7:pilihan_1,pilihan_2,pilihan_3]', '58', '2025-05-17 14:20:28', 'Selesai'),
(670, 5, 'Corbuzier', 'SR9-01', '', '[1:pilihan_3][2:Benar][3:Benar|Salah][4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu][5:pilihan_1,pilihan_2,pilihan_4][6:Salah|Benar][7:pilihan_2,pilihan_4][8:pilihan_1,pilihan_2,pilihan_4][9:ewt][10:sdfsdf:sdf|sdf:sdddff][11:Benar|Salah][12:asdsasd][13:pilihan_2]', '54', '2025-05-17 14:42:03', 'Selesai'),
(678, 16, 'Denny', 'BINDO7-1', '', '[1:pilihan_3][2:pilihan_1,pilihan_2,pilihan_4][3:Benar|Benar|Salah|Benar][4:Pilihan satu:pasangan satu|pilihan dua:pasangan dua|pilihan tiga:pasangan tiga|pilihan empat:pasangan empat][5:icon][6:Benar|Salah][7:pilihan_1,pilihan_2,pilihan_3]', '59', '2025-05-18 13:10:42', 'Selesai'),
(679, 1, 'Jokowi JK', 'IPA9-01', '', '[1:pilihan_3][2:Benar|Salah|Benar|Benar][3:pilihan_1,pilihan_2,pilihan_4][4:Keterampilan proses dalam IPA pada saat menimbang buah apel menggunakan neraca:mikrometer|Kegiatan membandingkan suatu besaran dengan besaran lain yang sejenis sebagai satuan:Pengamatan|Besaran turunan yang diturunkan dari besaran pokok panjang:Jangka sorong|Alat ukur ketebalan kertas:diafragma][5:fgfj]', '7', '2025-05-19 11:23:01', 'Selesai'),
(763, 5, 'Corbuzier', 'IPA9-01', '', '[1:pilihan_1][2:Benar|Salah|Benar|Benar][3:pilihan_1,pilihan_2,pilihan_4][4:Keterampilan proses dalam IPA pada saat menimbang buah apel menggunakan neraca:mikrometer|Kegiatan membandingkan suatu besaran dengan besaran lain yang sejenis sebagai satuan:Pengamatan|Besaran turunan yang diturunkan dari besaran pokok panjang:Jangka sorong|Alat ukur ketebalan kertas:diafragma][5:]', '60', '2025-05-19 13:49:41', 'Selesai'),
(764, 2, 'Prabowo', 'SR9-01', '', '[3:Benar|Salah][4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu][8:pilihan_1,pilihan_2][12:vhn][2:Benar|Salah][9:sdfgsds][7:pilihan_1,pilihan_2,pilihan_4][6:Benar|Salah][11:Benar|Salah][1:pilihan_3][13:pilihan_1][10:sdfsdf:sdfdfgdfff|sdf:sdf][5:pilihan_1,pilihan_2,pilihan_3]', '53', '2025-05-19 17:14:32', 'Selesai'),
(772, 17, 'Lintar', 'SR9-01', '', '[10:sdfsdf:sdf|sdf:sdfdfgdfff][2:Benar|Salah][4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu][12:xcbx][8:pilihan_1,pilihan_2,pilihan_4][1:pilihan_3][3:Benar|Salah][9:asf][11:Benar|Salah][6:Salah|Benar][7:pilihan_1,pilihan_3][13:pilihan_2][5:pilihan_1,pilihan_3]', '57', '2025-05-20 14:34:41', 'Selesai'),
(776, 2, 'Prabowo', 'MAT9-01', '', '[1:pilihan_4][4:pilihan_1,pilihan_2,pilihan_3][6:5][2:Benar|Salah|Benar][3:Salah|Salah|Salah|Benar][5:Bilangan prima antara 10 dan 15:11 dan 13|Volume kubus dengan rusuk 4 cm:64 cm³|Luas persegi dengan sisi 6 cm:36 cm²|Nilai x dari 2x+5=19:7]', '58', '2025-05-21 15:04:07', 'Selesai'),
(779, 1, 'Jokowi JK', 'MAT9-01', '', '[1:pilihan_1][6:5][4:pilihan_1,pilihan_2,pilihan_3][5:Bilangan prima antara 10 dan 15:11 dan 13|Volume kubus dengan rusuk 4 cm:36 cm²|Luas persegi dengan sisi 6 cm:64 cm³|Nilai x dari 2x+5=19:7][2:Benar|Benar|Salah][3:Salah|Salah|Salah|Benar]', '57', '2025-05-21 18:08:17', 'Selesai');

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
  `nilai` text NOT NULL,
  `tanggal_ujian` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `nilai`
--

INSERT INTO `nilai` (`id_nilai`, `id_siswa`, `nama_siswa`, `kode_soal`, `total_soal`, `jawaban_benar`, `jawaban_salah`, `jawaban_kurang`, `jawaban_siswa`, `kunci`, `nilai`, `tanggal_ujian`) VALUES
(22, 15, 'Zevan', 'BINDO7-1', 7, '7', '0', '0', '[1:pilihan_4][2:pilihan_1,pilihan_2,pilihan_4][3:Benar|Benar|Salah|Benar][4:Pilihan satu:pasangan satu|pilihan dua:pasangan dua|pilihan tiga:pasangan tiga|pilihan empat:pasangan empat][5:icon][6:Benar|Salah][7:pilihan_1,pilihan_2,pilihan_3]', '[1:pilihan_4],[2:pilihan_1,pilihan_2,pilihan_4],[3:Benar|Benar|Salah|Benar],[4:Pilihan satu:pasangan satu|pilihan dua:pasangan dua|pilihan tiga:pasangan tiga|pilihan empat:pasangan empat],[5:icon],[6:Benar|Salah],[7:pilihan_1,pilihan_2,pilihan_3]', '100', '2025-03-17 17:28:16'),
(23, 4, 'Deddy ', 'SR9-01', 13, '2', '9', '2', '[1:pilihan_1][2:Benar|Salah][3:Salah|Benar][4:Teknik Mozaik:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Seni Rupa Murni:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Relief:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan][5:pilihan_1,pilihan_2,pilihan_3][6:Salah|Benar][7:pilihan_2,pilihan_3][8:pilihan_1,pilihan_3][9:fytf][10:sdfsdf:sdf|sdf:sdddff][11:Salah|Benar][12:hvjhvkjg][13:pilihan_2]', '[1:pilihan_3],[2:Salah|Salah],[3:Benar|Salah],[4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu],[5:pilihan_1,pilihan_2,pilihan_4],[6:Salah|Benar],[7:pilihan_1,pilihan_3,pilihan_4],[8:pilihan_1,pilihan_2],[9:ewt],[10:sdfsdf:sdf|sdf:sdfdfgdfff],[11:Benar|Salah],[12:asdsasd],[13:pilihan_2]', '23.076923076923', '2025-05-17 17:43:20'),
(24, 20, 'Robi', 'SR9-01', 13, '3', '7', '3', '[1:pilihan_2][2:Benar|Benar][3:Benar|Benar][4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu][5:pilihan_1,pilihan_2][6:Benar|Salah][7:pilihan_1,pilihan_2,pilihan_3][8:pilihan_1,pilihan_3][9:tydul][10:sdfsdf:sdf|sdf:sdddff][11:Benar|Salah][12:nb][13:pilihan_2]', '[1:pilihan_3],[2:Salah|Salah],[3:Benar|Salah],[4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu],[5:pilihan_1,pilihan_2,pilihan_4],[6:Salah|Benar],[7:pilihan_1,pilihan_3,pilihan_4],[8:pilihan_1,pilihan_2],[9:ewt],[10:sdfsdf:sdf|sdf:sdfdfgdfff],[11:Benar|Salah],[12:asdsasd],[13:pilihan_2]', '35.897435897436', '2025-05-17 19:49:13'),
(25, 3, 'Agum Gumelar', 'SR9-01', 13, '5', '6', '2', '[1:pilihan_3][2:Salah|Salah][3:Benar|Benar][4:Seni Rupa Murni:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu][5:pilihan_1,pilihan_2,pilihan_4][6:Salah|Benar][7:pilihan_1,pilihan_2][8:pilihan_1,pilihan_2,pilihan_4][9:wet][10:sdfsdf:sdf|sdf:sdddff][11:Salah|Benar][12:wet][13:pilihan_2]', '[1:pilihan_3],[2:Salah|Salah],[3:Benar|Salah],[4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu],[5:pilihan_1,pilihan_2,pilihan_4],[6:Salah|Benar],[7:pilihan_1,pilihan_3,pilihan_4],[8:pilihan_1,pilihan_2],[9:ewt],[10:sdfsdf:sdf|sdf:sdfdfgdfff],[11:Benar|Salah],[12:asdsasd],[13:pilihan_2]', '46.153846153846', '2025-05-17 19:53:48'),
(26, 14, 'Phoebe', 'BINDO7-1', 7, '5', '2', '0', '[1:pilihan_2][2:pilihan_2,pilihan_3][3:Benar|Benar|Salah|Benar][4:Pilihan satu:pasangan satu|pilihan dua:pasangan dua|pilihan tiga:pasangan tiga|pilihan empat:pasangan empat][5:icon][6:Benar|Salah][7:pilihan_1,pilihan_2,pilihan_3]', '[1:pilihan_4],[2:pilihan_1,pilihan_2,pilihan_4],[3:Benar|Benar|Salah|Benar],[4:Pilihan satu:pasangan satu|pilihan dua:pasangan dua|pilihan tiga:pasangan tiga|pilihan empat:pasangan empat],[5:icon],[6:Benar|Salah],[7:pilihan_1,pilihan_2,pilihan_3]', '71.428571428571', '2025-04-16 21:21:57'),
(27, 5, 'Corbuzier', 'SR9-01', 13, '9', '3', '1', '[1:pilihan_3][2:Benar][3:Benar|Salah][4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu][5:pilihan_1,pilihan_2,pilihan_4][6:Salah|Benar][7:pilihan_2,pilihan_4][8:pilihan_1,pilihan_2,pilihan_4][9:ewt][10:sdfsdf:sdf|sdf:sdddff][11:Benar|Salah][12:asdsasd][13:pilihan_2]', '[1:pilihan_3],[2:Salah|Salah],[3:Benar|Salah],[4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu],[5:pilihan_1,pilihan_2,pilihan_4],[6:Salah|Benar],[7:pilihan_1,pilihan_3,pilihan_4],[8:pilihan_1,pilihan_2],[9:ewt],[10:sdfsdf:sdf|sdf:sdfdfgdfff],[11:Benar|Salah],[12:asdsasd],[13:pilihan_2]', '73.076923076923', '2025-03-10 21:47:20'),
(28, 1, 'Jokowi JK', 'SR9-01', 13, '8', '2', '3', '[1:pilihan_3][2:Benar|Salah][3:Benar|Benar][4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu][5:pilihan_1,pilihan_2][6:Salah|Benar][7:pilihan_2,pilihan_3,pilihan_4][8:pilihan_1,pilihan_2][9:ewt][10:sdfsdf:sdf|sdf:sdfdfgdfff][11:Benar|Salah][12:asdsasd][13:pilihan_1]', '[1:pilihan_3],[2:Salah|Salah],[3:Benar|Salah],[4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu],[5:pilihan_1,pilihan_2,pilihan_4],[6:Salah|Benar],[7:pilihan_1,pilihan_3,pilihan_4],[8:pilihan_1,pilihan_2],[9:ewt],[10:sdfsdf:sdf|sdf:sdfdfgdfff],[11:Benar|Salah],[12:asdsasd],[13:pilihan_2]', '74.358974358974', '2025-05-18 02:26:25'),
(29, 16, 'Denny', 'BINDO7-1', 7, '6', '1', '0', '[1:pilihan_3][2:pilihan_1,pilihan_2,pilihan_4][3:Benar|Benar|Salah|Benar][4:Pilihan satu:pasangan satu|pilihan dua:pasangan dua|pilihan tiga:pasangan tiga|pilihan empat:pasangan empat][5:icon][6:Benar|Salah][7:pilihan_1,pilihan_2,pilihan_3]', '[1:pilihan_4],[2:pilihan_1,pilihan_2,pilihan_4],[3:Benar|Benar|Salah|Benar],[4:Pilihan satu:pasangan satu|pilihan dua:pasangan dua|pilihan tiga:pasangan tiga|pilihan empat:pasangan empat],[5:icon],[6:Benar|Salah],[7:pilihan_1,pilihan_2,pilihan_3]', '85.714285714286', '2025-05-18 20:10:42'),
(32, 1, 'Jokowi JK', 'IPA9-01', 5, '3', '2', '0', '[1:pilihan_3][2:Benar|Salah|Benar|Benar][3:pilihan_1,pilihan_2,pilihan_4][4:Keterampilan proses dalam IPA pada saat menimbang buah apel menggunakan neraca:mikrometer|Kegiatan membandingkan suatu besaran dengan besaran lain yang sejenis sebagai satuan:Pengamatan|Besaran turunan yang diturunkan dari besaran pokok panjang:Jangka sorong|Alat ukur ketebalan kertas:diafragma][5:fgfj]', '[1:pilihan_1],[2:Benar|Salah|Benar|Benar],[3:pilihan_1,pilihan_2,pilihan_4],[4:Keterampilan proses dalam IPA pada saat menimbang buah apel menggunakan neraca:mikrometer|Kegiatan membandingkan suatu besaran dengan besaran lain yang sejenis sebagai satuan:Pengamatan|Besaran turunan yang diturunkan dari besaran pokok panjang:Jangka sorong|Alat ukur ketebalan kertas:diafragma],[5:hanya sampai 1.000 hingga 2.000 jam penggunaan saja]', '60', '2025-05-19 20:43:05'),
(33, 5, 'Corbuzier', 'IPA9-01', 5, '4', '0', '1', '[1:pilihan_1][2:Benar|Salah|Benar|Benar][3:pilihan_1,pilihan_2,pilihan_4][4:Keterampilan proses dalam IPA pada saat menimbang buah apel menggunakan neraca:mikrometer|Kegiatan membandingkan suatu besaran dengan besaran lain yang sejenis sebagai satuan:Pengamatan|Besaran turunan yang diturunkan dari besaran pokok panjang:Jangka sorong|Alat ukur ketebalan kertas:diafragma][5:]', '[1:pilihan_1],[2:Benar|Salah|Benar|Benar],[3:pilihan_1,pilihan_2,pilihan_4],[4:Keterampilan proses dalam IPA pada saat menimbang buah apel menggunakan neraca:mikrometer|Kegiatan membandingkan suatu besaran dengan besaran lain yang sejenis sebagai satuan:Pengamatan|Besaran turunan yang diturunkan dari besaran pokok panjang:Jangka sorong|Alat ukur ketebalan kertas:diafragma],[5:hanya sampai 1.000 hingga 2.000 jam penggunaan saja]', '80', '2025-05-19 20:49:41'),
(34, 2, 'Prabowo', 'SR9-01', 13, '5', '7', '1', '[3:Benar|Salah][4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu][8:pilihan_1,pilihan_2][12:vhn][2:Benar|Salah][9:sdfgsds][7:pilihan_1,pilihan_2,pilihan_4][6:Benar|Salah][11:Benar|Salah][1:pilihan_3][13:pilihan_1][10:sdfsdf:sdfdfgdfff|sdf:sdf][5:pilihan_1,pilihan_2,pilihan_3]', '[1:pilihan_3],[2:Salah|Salah],[3:Benar|Salah],[4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu],[5:pilihan_1,pilihan_2,pilihan_4],[6:Salah|Benar],[7:pilihan_1,pilihan_3,pilihan_4],[8:pilihan_1,pilihan_2],[9:ewt],[10:sdfsdf:sdf|sdf:sdfdfgdfff],[11:Benar|Salah],[12:asdsasd],[13:pilihan_2]', '42.307692307692', '2025-05-20 00:24:33'),
(35, 17, 'Lintar', 'SR9-01', 13, '7', '4', '2', '[10:sdfsdf:sdf|sdf:sdfdfgdfff][2:Benar|Salah][4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu][12:xcbx][8:pilihan_1,pilihan_2,pilihan_4][1:pilihan_3][3:Benar|Salah][9:asf][11:Benar|Salah][6:Salah|Benar][7:pilihan_1,pilihan_3][13:pilihan_2][5:pilihan_1,pilihan_3]', '[1:pilihan_3],[2:Salah|Salah],[3:Benar|Salah],[4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu],[5:pilihan_1,pilihan_2,pilihan_4],[6:Salah|Benar],[7:pilihan_1,pilihan_3,pilihan_4],[8:pilihan_1,pilihan_2],[9:ewt],[10:sdfsdf:sdf|sdf:sdfdfgdfff],[11:Benar|Salah],[12:asdsasd],[13:pilihan_2]', '62.820512820513', '2025-05-20 21:37:15'),
(36, 2, 'Prabowo', 'MAT9-01', 6, '3', '2', '1', '[1:pilihan_4][4:pilihan_1,pilihan_2,pilihan_3][6:5][2:Benar|Salah|Benar][3:Salah|Salah|Salah|Benar][5:Bilangan prima antara 10 dan 15:11 dan 13|Volume kubus dengan rusuk 4 cm:64 cm³|Luas persegi dengan sisi 6 cm:36 cm²|Nilai x dari 2x+5=19:7]', '[1:pilihan_4],[2:Benar|Benar|Salah],[3:Salah|Salah|Salah|Benar],[4:pilihan_1,pilihan_3],[5:Bilangan prima antara 10 dan 15:11 dan 13|Volume kubus dengan rusuk 4 cm:64 cm³|Luas persegi dengan sisi 6 cm:36 cm²|Nilai x dari 2x+5=19:7],[6:6]', '55.555555555556', '2025-05-21 22:05:48'),
(37, 1, 'Jokowi JK', 'MAT9-01', 6, '2', '3', '1', '[1:pilihan_1][6:5][4:pilihan_1,pilihan_2,pilihan_3][5:Bilangan prima antara 10 dan 15:11 dan 13|Volume kubus dengan rusuk 4 cm:36 cm²|Luas persegi dengan sisi 6 cm:64 cm³|Nilai x dari 2x+5=19:7][2:Benar|Benar|Salah][3:Salah|Salah|Salah|Benar]', '[1:pilihan_4],[2:Benar|Benar|Salah],[3:Salah|Salah|Salah|Benar],[4:pilihan_1,pilihan_3],[5:Bilangan prima antara 10 dan 15:11 dan 13|Volume kubus dengan rusuk 4 cm:64 cm³|Luas persegi dengan sisi 6 cm:36 cm²|Nilai x dari 2x+5=19:7],[6:6]', '41.666666666667', '2025-05-22 01:09:54');

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
  `login_ganda` enum('izinkan','blokir') DEFAULT 'blokir',
  `chat` varchar(100) NOT NULL,
  `versi_aplikasi` varchar(20) DEFAULT '1.0.0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengaturan`
--

INSERT INTO `pengaturan` (`id`, `nama_aplikasi`, `logo_sekolah`, `warna_tema`, `waktu_sinkronisasi`, `sembunyikan_nilai`, `login_ganda`, `chat`, `versi_aplikasi`) VALUES
(1, 'CBT-Eschool', 'logo_1747650742.png', '#2f90c1', 60, 0, 'izinkan', 'izinkan', '1.0.5');

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
(1, 'Jokowi JK', 'h7fV3os6WcZ+hNwtoIN5Si9hbEVndnEzRmNodzJlSktYZ2hVMUE9PQ==', '123456', '9', 'A', 'Nonaktif', '', '2025-05-22 10:23:45', 'http://localhost/cbt-eschool/siswa/chat.php', 0),
(2, 'Prabowo', 'm9MaPSetPwkYW68qNsWwUlUrOW9HNWFlRzJRVENVVi9xNW9vN0E9PQ==', '123457', '9', 'B', 'Nonaktif', '', '2025-05-23 00:40:26', 'http://localhost/cbt-eschool/siswa/game.php', 0),
(3, 'Agum Gumelar', '5mv6Upz6eP/GpQrkjcebOHcyOFNxV2RRT2xQdkVxRUh0ZVZ0d3c9PQ==', '123458', '9', 'C', 'nonaktif', '', '2025-05-17 19:53:53', 'http://localhost/cbt-eschool/siswa/hasil.php', 0),
(4, 'Deddy ', '5uKDYI7JoYmjpgBTg8LxUi9YZ2dIVGFucU5FM2wySDYvcmFVQXc9PQ==', '123459', '9', 'D', 'nonaktif', 'a1018c3138744d34ad1c0805c97e21159d2dd44ff5e8e1a3680d0b880621e19b', '2025-05-17 17:50:47', 'http://localhost/cbt-eschool/siswa/dashboard.php', 0),
(5, 'Corbuzier', '/SbMMmTczf7Ry0qUn/f6XmhpM1BYS0l6S1F0cmlHSlB3ZjE1cEE9PQ==', '123461', '9', 'E', 'Nonaktif', '175ab9ac3977b5f605e0372b9622357699af287d6b42885eaff6b02a67b87700', '2025-05-23 00:43:37', 'http://localhost/cbt-eschool/siswa/game.php?log=1&skor=50', 0),
(13, 'Erina', 'FQOm8MYUIes79E36AQv1AU5VMVdUanhIaTBVTURVS0hXckFRUXc9PQ==', '721731', '9', 'A', 'Nonaktif', '', '2025-05-12 23:32:14', 'http://localhost/cbt-eschool/siswa/dashboard.php', 1),
(14, 'Phoebe', 'Dwl3VYW4ysVVEjO67sy6QmdYb2h0NjNFWjhlV3ViamtWY01hc0E9PQ==', '122345', '7', 'C', 'Nonaktif', '', '2025-05-21 22:01:56', 'http://localhost/cbt-eschool/siswa/preview_hasil.php?id_siswa=14&kode_soal=BINDO7-1', 0),
(15, 'Zevan', 'mG5EAQl0ttZQFaqBXlYCgGdMVkdTMjNQQXZ3VmRKdmFNbTJBeEE9PQ==', '257174', '7', 'D', 'Nonaktif', '', '2025-05-21 22:01:23', 'http://localhost/cbt-eschool/siswa/dashboard.php', 0),
(16, 'Denny', 'N2ugxO2xwJR74bjbZQv19nYrMFVFbi9JTEk5MFNDeVdITWxmM0E9PQ==', '641343', '7', 'F', 'Nonaktif', '', '2025-05-21 22:00:37', 'http://localhost/cbt-eschool/siswa/hasil.php', 0),
(17, 'Lintar', 'CJ7fgqg1+lzEgNuqTQwdCUtBeHlsdXdGU3FabGdhQ3lQbXQ2NlE9PQ==', '252743', '9', 'D', 'Nonaktif', '', '2025-05-21 18:13:08', 'http://localhost/cbt-eschool/siswa/game.php?log=1&skor=120', 0),
(18, 'andy', '5IiPhwyWU7/GiyYe622atFErOVViUmNXOXRheVk0Z2U1V0tiK2c9PQ==', '876543', '8', 'D', 'Nonaktif', '', '2025-05-12 23:32:47', 'http://localhost/cbt-eschool/siswa/dashboard.php', 1),
(20, 'Robi', 'XpqGGiL6DfhOGnPqcV9EnUdLdTlEbzdwN1pyVE5wb2FJSStLdUE9PQ==', '252645', '9', 'G', 'Nonaktif', '', '2025-05-17 19:49:21', 'http://localhost/cbt-eschool/siswa/hasil.php', 0),
(21, 'Intan', 'Ya+NHgRRNME9cYTmSRYUz2sxMS9tczlZMGJDaFd0NkN5TzErV0E9PQ==', '1241322', '7', 'B', 'Nonaktif', '', '2025-05-13 01:10:23', 'http://localhost/cbt-eschool/siswa/dashboard.php', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `skor_game`
--

CREATE TABLE `skor_game` (
  `id` int(11) NOT NULL,
  `id_siswa` int(11) DEFAULT NULL,
  `nama_game` varchar(50) DEFAULT NULL,
  `skor` int(11) DEFAULT 0,
  `waktu` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `skor_game`
--

INSERT INTO `skor_game` (`id`, `id_siswa`, `nama_game`, `skor`, `waktu`) VALUES
(3, 1, 'math_puzzle', 440, '2025-05-20 13:56:59'),
(4, 2, 'math_puzzle', 170, '2025-05-20 14:47:08'),
(5, 16, 'math_puzzle', 170, '2025-05-20 15:04:28'),
(6, 5, 'math_puzzle', 280, '2025-05-20 21:09:23'),
(7, 17, 'math_puzzle', 340, '2025-05-20 21:42:08'),
(8, 17, 'scramble', 120, '2025-05-21 18:05:24'),
(9, 16, 'scramble', 150, '2025-05-21 21:28:24'),
(10, 1, 'scramble', 120, '2025-05-22 03:16:05'),
(11, 2, 'scramble', 150, '2025-05-22 10:36:27'),
(12, 5, 'scramble', 50, '2025-05-23 00:43:36');

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
  `tampilan_soal` varchar(10) NOT NULL,
  `kunci` text NOT NULL,
  `token` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `soal`
--

INSERT INTO `soal` (`id_soal`, `kode_soal`, `nama_soal`, `mapel`, `kelas`, `waktu_ujian`, `tanggal`, `status`, `tampilan_soal`, `kunci`, `token`) VALUES
(1, 'SR9-01', 'Seni Rupa 1', 'Seni Rupa', '9', 90, '2025-05-16', 'Aktif', 'Acak', '[1:pilihan_3],[2:Salah|Salah],[3:Benar|Salah],[4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu],[5:pilihan_1,pilihan_2,pilihan_4],[6:Salah|Benar],[7:pilihan_1,pilihan_3,pilihan_4],[8:pilihan_1,pilihan_2],[9:ewt],[10:sdfsdf:sdf|sdf:sdfdfgdfff],[11:Benar|Salah],[12:asdsasd],[13:pilihan_2]', 'DHUCJZ'),
(10, 'BINDO7-1', 'B. Indonesia', 'Bahasa Indo', '7', 90, '2025-05-15', 'Aktif', 'Acak', '[1:pilihan_4],[2:pilihan_1,pilihan_2,pilihan_4],[3:Benar|Benar|Salah|Benar],[4:Pilihan satu:pasangan satu|pilihan dua:pasangan dua|pilihan tiga:pasangan tiga|pilihan empat:pasangan empat],[5:icon],[6:Benar|Salah],[7:pilihan_1,pilihan_2,pilihan_3]', 'KJHCAT'),
(17, 'IPA9-01', 'IPA 01', 'IPA', '9', 90, '2025-05-19', 'Aktif', 'Urut', '[1:pilihan_1],[2:Benar|Salah|Benar|Benar],[3:pilihan_1,pilihan_2,pilihan_4],[4:Keterampilan proses dalam IPA pada saat menimbang buah apel menggunakan neraca:mikrometer|Kegiatan membandingkan suatu besaran dengan besaran lain yang sejenis sebagai satuan:Pengamatan|Besaran turunan yang diturunkan dari besaran pokok panjang:Jangka sorong|Alat ukur ketebalan kertas:diafragma],[5:hanya sampai 1.000 hingga 2.000 jam penggunaan saja]', 'XMTOEW'),
(19, 'MAT9-01', 'Matematika 9', 'Matematika', '9', 60, '2025-05-21', 'Aktif', 'Acak', '[1:pilihan_4],[2:Benar|Benar|Salah],[3:Salah|Salah|Salah|Benar],[4:pilihan_1,pilihan_3],[5:Bilangan prima antara 10 dan 15:11 dan 13|Volume kubus dengan rusuk 4 cm:64 cm³|Luas persegi dengan sisi 6 cm:36 cm²|Nilai x dari 2x+5=19:7],[6:6]', 'OYJFMQ');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `butir_soal`
--
ALTER TABLE `butir_soal`
  MODIFY `id_soal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT untuk tabel `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT untuk tabel `faq`
--
ALTER TABLE `faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `jawaban_siswa`
--
ALTER TABLE `jawaban_siswa`
  MODIFY `id_jawaban` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=782;

--
-- AUTO_INCREMENT untuk tabel `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT untuk tabel `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `skor_game`
--
ALTER TABLE `skor_game`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `soal`
--
ALTER TABLE `soal`
  MODIFY `id_soal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `skor_game`
--
ALTER TABLE `skor_game`
  ADD CONSTRAINT `skor_game_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
