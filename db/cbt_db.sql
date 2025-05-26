-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2025 at 12:09 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `nama_admin` text NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `nama_admin`, `password`, `created_at`) VALUES
(1, 'gludug', 'Admin', '$2y$10$/RMEibFbfc45T0gmAM7H4u9CGhjKCkjFe/ZnQnTS6qc0JFEbOsaoW', '2025-05-05 09:13:31');

-- --------------------------------------------------------

--
-- Table structure for table `butir_soal`
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
-- Dumping data for table `butir_soal`
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
(187, 5, 'MAT9-01', 'Pasangkan pernyataan di kolom kiri dengan jawaban yang tepat di kolom kanan dengan menulis huruf di depan nomor yang sesuai!', 'Menjodohkan', '', '', '', '', 'Bilangan prima antara 10 dan 15:11 dan 13|Volume kubus dengan rusuk 4 cm:64 cm³|Luas persegi dengan sisi 6 cm:36 cm²|Nilai x dari 2x+5=19:7', 'Aktif', '2025-05-21 08:51:02'),
(188, 6, 'MAT9-01', 'Faktor persekutuan terbesar (FPB) dari 12 dan 18', 'Uraian', '', '', '', '', '6', 'Aktif', '2025-05-21 08:51:30'),
(195, 1, 'MAT9-02', '<p><img style=\"\" src=\"http://localhost/cbt-eschool/gambar/682d8ead64c7b.jpg\" id=\"gbrsoal\"></p><p>Sampah anorganik lebih lama terurai dibandingkan dengan sampah organik. Waktu dekomposisi popok sekali pakai lebih lama dari plastik, namun kurang dari kulit sintetis. Berapa waktu dekomposisi yang mungkin dari popok sekali pakai?</p>', 'Pilihan Ganda', '100 tahun', '250 tahun', '375 tahun', '475 tahun', 'pilihan_4', 'Aktif', '2025-05-24 05:34:36'),
(196, 2, 'MAT9-02', '<p><img style=\"\" src=\"http://localhost/cbt-eschool/gambar/682d8f5650e12.jpg\" id=\"gbrsoal\"></p><p>Pilih Benar atau Salah pada setiap pernyataan berikut!</p><p><br></p>', 'Benar/Salah', 'Panjang AB = Panjang CD', 'Panjang PQ = Panjang SR', 'Jarak Q ke S = Jarak B ke C', '', 'Benar|Benar|Salah', 'Aktif', '2025-05-24 05:34:36'),
(197, 3, 'MAT9-02', 'Suatu kali, PT Suka-Suka Kalian mendapatkan pesanan 30 unit tenda dengan bentuk dan ukuran seperti di atas. Waktu penyelesaian yang diperlukan untuk memenuhi seluruh pesanan adalah 20 hari kerja.<br><br>Berdasarkan keterangan di atas, berilah tanda Benar atau Salah untuk setiap pernyataan berikut!', 'Benar/Salah', 'Waktu rata-rata pembuatan 3 buah tenda adalah 2 hari.', 'Waktu penyelesaian semua pesanan bisa tepat waktu jika dalam sehari dihasilkan sebuah tenda.', 'Jika pesanan bertambah 5 tenda, lama penyelesaian bertambah 2 hari.', 'Jika dalam sehari dapat dihasilkan 2 tenda, waktu penyelesaian seluruh pesanan menjadi 5 hari lebih cepat.', 'Salah|Salah|Salah|Benar', 'Aktif', '2025-05-24 05:34:36'),
(198, 4, 'MAT9-02', 'Biskuit merupakan camilan yang banyak digemari sebagai pelengkap minum teh setiap waktu. Berikut komposisi 2 jenis biskuit yang sering dijual di pasaran:<br><br>(I) Komposisi Biskuit Sehat (berat 149 g):<br><br>&nbsp;&nbsp;&nbsp; Lemak total: 9%<br>&nbsp;&nbsp;&nbsp; Lemak jenuh: 20%<br>&nbsp;&nbsp;&nbsp; Protein: 3%<br>&nbsp;&nbsp;&nbsp; Karbohidrat total: 6%<br>&nbsp;&nbsp;&nbsp; Natrium: 10%<br><br>(II) Komposisi Biskuit Lezat (250 g):<br><br>&nbsp;&nbsp;&nbsp; Lemak total: 8%<br>&nbsp;&nbsp;&nbsp; Lemak jenuh: 16%<br>&nbsp;&nbsp;&nbsp; Protein: 2%<br>&nbsp;&nbsp;&nbsp; Karbohidrat total: 4%<br>&nbsp;&nbsp;&nbsp; Natrium: 5%<br><br>Berdasarkan informasi di atas, pilihlah pernyataan-pernyataan berikut yang benar:', 'Pilihan Ganda Kompleks', 'Komposisi protein Biskuit Lezat adalah 0,02 bagian', 'Komposisi natrium Biskuit Sehat adalah 0,01 bagian', 'Komposisi lemak jenuh Biskuit Lezat adalah 0,16 bagian', 'Komposisi lemak jenuh Biskuit Sehat adalah 0,02 bagian', 'pilihan_1,pilihan_3', 'Aktif', '2025-05-24 05:34:36'),
(199, 5, 'MAT9-02', 'Pasangkan pernyataan di kolom kiri dengan jawaban yang tepat di kolom kanan dengan menulis huruf di depan nomor yang sesuai!', 'Menjodohkan', '', '', '', '', 'Bilangan prima antara 10 dan 15:11 dan 13|Volume kubus dengan rusuk 4 cm:64 cm³|Luas persegi dengan sisi 6 cm:36 cm²|Nilai x dari 2x+5=19:7', 'Aktif', '2025-05-24 05:34:36');

-- --------------------------------------------------------

--
-- Table structure for table `chat`
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
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`id`, `id_user`, `pesan`, `waktu`, `deleted`, `role`) VALUES
(68, 1, '👍🔥semangat murid murid', '2025-05-24 11:16:29', 0, 'admin'),
(69, 1, 'halo gays', '2025-05-24 15:01:42', 0, 'siswa'),
(70, 1, '😍', '2025-05-24 15:02:05', 1, 'siswa'),
(71, 1, '🚀rocket', '2025-05-24 15:02:50', 0, 'siswa'),
(72, 1, '😢', '2025-05-25 00:31:57', 1, 'admin'),
(73, 17, '😢', '2025-05-25 00:32:11', 0, 'siswa'),
(74, 14, '🔥🔥🔥', '2025-05-25 18:44:17', 0, 'siswa');

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE `faq` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faq`
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
-- Table structure for table `jawaban_siswa`
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
-- Dumping data for table `jawaban_siswa`
--

INSERT INTO `jawaban_siswa` (`id_jawaban`, `id_siswa`, `nama_siswa`, `kode_soal`, `total_soal`, `jawaban_siswa`, `waktu_sisa`, `waktu_dijawab`, `status_ujian`) VALUES
(2, 3, 'Agum Gumelar', 'SR9-01', '', '[3:Benar|Benar][6:Salah|Benar][10:sdfsdf:sdfdfgdfff|sdf:sdf][7:pilihan_1,pilihan_2][12:wet][13:pilihan_2][11:Salah|Benar][2:Salah|Salah][1:pilihan_3][4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu][9:wet][8:pilihan_1,pilihan_2,pilihan_4][5:pilihan_1,pilihan_2,pilihan_4]', '52', '2025-05-10 16:10:29', 'Selesai'),
(14, 1, 'Jokowi JK', 'SR9-01', '', '[6:Salah|Benar][5:pilihan_1,pilihan_2][4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu][2:Benar|Salah][1:pilihan_3][11:Benar|Salah][3:Benar|Benar][9:ewt][7:pilihan_2,pilihan_3,pilihan_4][8:pilihan_1,pilihan_2][10:sdfsdf:sdf|sdf:sdfdfgdfff][13:pilihan_1][12:asdsasd]', '59', '2025-05-15 21:47:59', 'Selesai'),
(27, 20, 'Robi', 'SR9-01', '', '[10:sdfsdf:sdf|sdf:sdfdfgdfff][7:pilihan_1,pilihan_3,pilihan_4][1:pilihan_3][9:tydul][11:Benar|Salah][2:Salah|Salah][4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu][6:Salah|Benar][13:pilihan_2][12:nb][3:Benar|Salah][5:pilihan_1,pilihan_2,pilihan_4][8:pilihan_1,pilihan_2]', '40', '2025-05-15 22:05:32', 'Selesai'),
(537, 15, 'Zevan', 'BINDO7-1', '', '[6:Benar|Salah][3:Benar|Benar|Salah|Benar][5:icon][7:pilihan_1,pilihan_2,pilihan_3][4:Pilihan satu:pasangan satu|pilihan dua:pasangan dua|pilihan tiga:pasangan tiga|pilihan empat:pasangan empat][2:pilihan_1,pilihan_2,pilihan_4][1:pilihan_4]', '23', '2025-05-17 01:57:58', 'Selesai'),
(678, 16, 'Denny', 'BINDO7-1', '', '[1:pilihan_3][3:Benar|Benar|Salah|Benar][4:Pilihan satu:pasangan satu|pilihan dua:pasangan dua|pilihan tiga:pasangan tiga|pilihan empat:pasangan empat][5:icon][2:pilihan_1,pilihan_2,pilihan_4][7:pilihan_1,pilihan_2,pilihan_3][6:Benar|Salah]', '59', '2025-05-18 13:10:42', 'Selesai'),
(786, 1, 'Jokowi JK', 'MAT9-02', '', '[1:pilihan_4][4:pilihan_1,pilihan_3][2:Benar|Benar|Salah][5:Bilangan prima antara 10 dan 15:11 dan 13|Volume kubus dengan rusuk 4 cm:64 cm³|Luas persegi dengan sisi 6 cm:36 cm²][3:Salah|Salah|Salah|Benar]', '59', '2025-05-24 15:10:47', 'Selesai'),
(795, 1, 'Jokowi JK', 'IPA9-01', '', '[1:pilihan_2][2:Benar|Salah|Benar|Salah][3:pilihan_1,pilihan_3,pilihan_4][4:Keterampilan proses dalam IPA pada saat menimbang buah apel menggunakan neraca:mikrometer|Kegiatan membandingkan suatu besaran dengan besaran lain yang sejenis sebagai satuan:diafragma|Besaran turunan yang diturunkan dari besaran pokok panjang:Pengamatan|Alat ukur ketebalan kertas:Jangka sorong][5:]', '59', '2025-05-24 15:37:10', 'Selesai'),
(796, 17, 'Lintar', 'MAT9-02', '', '[2:Salah|Salah|Benar][1:pilihan_4][5:Bilangan prima antara 10 dan 15:11 dan 13|Volume kubus dengan rusuk 4 cm:64 cm³|Luas persegi dengan sisi 6 cm:36 cm²|Nilai x dari 2x+5=19:7][4:pilihan_1,pilihan_2,pilihan_3][3:Salah|Salah|Salah|Benar]', '57', '2025-05-24 17:15:15', 'Selesai'),
(799, 5, 'Corbuzier', 'IPA9-01', '', '[1:pilihan_2][2:Benar|Salah|Benar|Salah][3:pilihan_1,pilihan_3][4:Keterampilan proses dalam IPA pada saat menimbang buah apel menggunakan neraca:mikrometer|Kegiatan membandingkan suatu besaran dengan besaran lain yang sejenis sebagai satuan:diafragma|Besaran turunan yang diturunkan dari besaran pokok panjang:Pengamatan|Alat ukur ketebalan kertas:Jangka sorong][5:rt]', '56', '2025-05-24 17:17:27', 'Selesai'),
(808, 14, 'Phoebe', 'BINDO7-1', '', '[3:Benar|Benar|Salah|Benar][6:Benar|Salah][7:pilihan_1,pilihan_2,pilihan_3][1:pilihan_4][5:bn kjbh][4:Pilihan satu:pasangan satu|pilihan dua:pasangan dua|pilihan tiga:pasangan tiga|pilihan empat:pasangan empat][2:pilihan_1,pilihan_2,pilihan_4]', '56', '2025-05-25 11:09:21', 'Selesai'),
(814, 17, 'Lintar', 'IPA9-01', '', '[1:pilihan_3][2:Benar|Salah|Benar|Benar][3:pilihan_2,pilihan_3][4:Keterampilan proses dalam IPA pada saat menimbang buah apel menggunakan neraca:mikrometer|Kegiatan membandingkan suatu besaran dengan besaran lain yang sejenis sebagai satuan:Pengamatan|Besaran turunan yang diturunkan dari besaran pokok panjang:Jangka sorong|Alat ukur ketebalan kertas:diafragma][5:cv]', '55', '2025-05-25 16:48:21', 'Selesai');

-- --------------------------------------------------------

--
-- Table structure for table `nilai`
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
  `nilai_uraian` decimal(5,2) DEFAULT 0.00,
  `detail_uraian` text NOT NULL,
  `tanggal_ujian` datetime NOT NULL,
  `status_penilaian` enum('otomatis','perlu_dinilai','selesai') DEFAULT 'otomatis'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nilai`
--

INSERT INTO `nilai` (`id_nilai`, `id_siswa`, `nama_siswa`, `kode_soal`, `total_soal`, `jawaban_benar`, `jawaban_salah`, `jawaban_kurang`, `jawaban_siswa`, `kunci`, `nilai`, `nilai_uraian`, `detail_uraian`, `tanggal_ujian`, `status_penilaian`) VALUES
(40, 1, 'Jokowi JK', 'SR9-01', 13, '6', '2', '5', '[6:Salah|Benar][5:pilihan_1,pilihan_2][4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu][2:Benar|Salah][1:pilihan_3][11:Benar|Salah][3:Benar|Benar][9:ewt][7:pilihan_2,pilihan_3,pilihan_4][8:pilihan_1,pilihan_2][10:sdfsdf:sdf|sdf:sdfdfgdfff][13:pilihan_1][12:asdsasd]', '[1:pilihan_3],[2:Salah|Salah],[3:Benar|Salah],[4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu],[5:pilihan_1,pilihan_2,pilihan_4],[6:Salah|Benar],[7:pilihan_1,pilihan_3,pilihan_4],[8:pilihan_1,pilihan_2],[9:ewt],[10:sdfsdf:sdf|sdf:sdfdfgdfff],[11:Benar|Salah],[12:asdsasd],[13:pilihan_2]', 58.97, 15.38, '[9:7.69][12:7.69]', '2025-04-08 20:58:04', 'perlu_dinilai'),
(41, 1, 'Jokowi JK', 'MAT9-02', 5, '4', '0', '1', '[1:pilihan_4][4:pilihan_1,pilihan_3][2:Benar|Benar|Salah][5:Bilangan prima antara 10 dan 15:11 dan 13|Volume kubus dengan rusuk 4 cm:64 cm³|Luas persegi dengan sisi 6 cm:36 cm²][3:Salah|Salah|Salah|Benar]', '[1:pilihan_4],[2:Benar|Benar|Salah],[3:Salah|Salah|Salah|Benar],[4:pilihan_1,pilihan_3],[5:Bilangan prima antara 10 dan 15:11 dan 13|Volume kubus dengan rusuk 4 cm:64 cm³|Luas persegi dengan sisi 6 cm:36 cm²|Nilai x dari 2x+5=19:7]', 95.00, 0.00, '', '2025-03-17 22:11:36', 'selesai'),
(42, 3, 'Agum Gumelar', 'SR9-01', 13, '6', '4', '3', '[3:Benar|Benar][6:Salah|Benar][10:sdfsdf:sdfdfgdfff|sdf:sdf][7:pilihan_1,pilihan_2][12:wet][13:pilihan_2][11:Salah|Benar][2:Salah|Salah][1:pilihan_3][4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu][9:wet][8:pilihan_1,pilihan_2,pilihan_4][5:pilihan_1,pilihan_2,pilihan_4]', '[1:pilihan_3],[2:Salah|Salah],[3:Benar|Salah],[4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu],[5:pilihan_1,pilihan_2,pilihan_4],[6:Salah|Benar],[7:pilihan_1,pilihan_3,pilihan_4],[8:pilihan_1,pilihan_2],[9:ewt],[10:sdfsdf:sdf|sdf:sdfdfgdfff],[11:Benar|Salah],[12:asdsasd],[13:pilihan_2]', 50.00, 15.38, '[9:7.69][12:7.69]', '2025-03-18 22:21:10', 'perlu_dinilai'),
(43, 20, 'Robi', 'SR9-01', 13, '11', '0', '2', '[10:sdfsdf:sdf|sdf:sdfdfgdfff][7:pilihan_1,pilihan_3,pilihan_4][1:pilihan_3][9:tydul][11:Benar|Salah][2:Salah|Salah][4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu][6:Salah|Benar][13:pilihan_2][12:nb][3:Benar|Salah][5:pilihan_1,pilihan_2,pilihan_4][8:pilihan_1,pilihan_2]', '[1:pilihan_3],[2:Salah|Salah],[3:Benar|Salah],[4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu],[5:pilihan_1,pilihan_2,pilihan_4],[6:Salah|Benar],[7:pilihan_1,pilihan_3,pilihan_4],[8:pilihan_1,pilihan_2],[9:ewt],[10:sdfsdf:sdf|sdf:sdfdfgdfff],[11:Benar|Salah],[12:asdsasd],[13:pilihan_2]', 84.62, 15.38, '[9:7.69][12:7.69]', '2025-05-24 22:26:16', 'perlu_dinilai'),
(44, 15, 'Zevan', 'BINDO7-1', 7, '6', '0', '1', '[6:Benar|Salah][3:Benar|Benar|Salah|Benar][5:icon][7:pilihan_1,pilihan_2,pilihan_3][4:Pilihan satu:pasangan satu|pilihan dua:pasangan dua|pilihan tiga:pasangan tiga|pilihan empat:pasangan empat][2:pilihan_1,pilihan_2,pilihan_4][1:pilihan_4]', '[1:pilihan_4],[2:pilihan_1,pilihan_2,pilihan_4],[3:Benar|Benar|Salah|Benar],[4:Pilihan satu:pasangan satu|pilihan dua:pasangan dua|pilihan tiga:pasangan tiga|pilihan empat:pasangan empat],[5:icon],[6:Benar|Salah],[7:pilihan_1,pilihan_2,pilihan_3]', 85.71, 14.29, '[5:14.29]', '2025-05-22 22:29:29', 'perlu_dinilai'),
(45, 16, 'Denny', 'BINDO7-1', 7, '5', '1', '1', '[1:pilihan_3][3:Benar|Benar|Salah|Benar][4:Pilihan satu:pasangan satu|pilihan dua:pasangan dua|pilihan tiga:pasangan tiga|pilihan empat:pasangan empat][5:icon][2:pilihan_1,pilihan_2,pilihan_4][7:pilihan_1,pilihan_2,pilihan_3][6:Benar|Salah]', '[1:pilihan_4],[2:pilihan_1,pilihan_2,pilihan_4],[3:Benar|Benar|Salah|Benar],[4:Pilihan satu:pasangan satu|pilihan dua:pasangan dua|pilihan tiga:pasangan tiga|pilihan empat:pasangan empat],[5:icon],[6:Benar|Salah],[7:pilihan_1,pilihan_2,pilihan_3]', 71.43, 8.90, '[5:8.90]', '2025-05-24 22:33:02', 'perlu_dinilai'),
(46, 1, 'Jokowi JK', 'IPA9-01', 5, '0', '2', '3', '[1:pilihan_2][2:Benar|Salah|Benar|Salah][3:pilihan_1,pilihan_3,pilihan_4][4:Keterampilan proses dalam IPA pada saat menimbang buah apel menggunakan neraca:mikrometer|Kegiatan membandingkan suatu besaran dengan besaran lain yang sejenis sebagai satuan:diafragma|Besaran turunan yang diturunkan dari besaran pokok panjang:Pengamatan|Alat ukur ketebalan kertas:Jangka sorong][5:]', '[1:pilihan_1],[2:Benar|Salah|Benar|Benar],[3:pilihan_1,pilihan_2,pilihan_4],[4:Keterampilan proses dalam IPA pada saat menimbang buah apel menggunakan neraca:mikrometer|Kegiatan membandingkan suatu besaran dengan besaran lain yang sejenis sebagai satuan:Pengamatan|Besaran turunan yang diturunkan dari besaran pokok panjang:Jangka sorong|Alat ukur ketebalan kertas:diafragma],[5:hanya sampai 1.000 hingga 2.000 jam penggunaan saja]', 20.00, 20.00, '[5:20.00]', '2025-05-24 22:37:10', 'perlu_dinilai'),
(47, 5, 'Corbuzier', 'IPA9-01', 5, '0', '2', '3', '[1:pilihan_2][2:Benar|Salah|Benar|Salah][3:pilihan_1,pilihan_3][4:Keterampilan proses dalam IPA pada saat menimbang buah apel menggunakan neraca:mikrometer|Kegiatan membandingkan suatu besaran dengan besaran lain yang sejenis sebagai satuan:diafragma|Besaran turunan yang diturunkan dari besaran pokok panjang:Pengamatan|Alat ukur ketebalan kertas:Jangka sorong][5:rt]', '[1:pilihan_1],[2:Benar|Salah|Benar|Benar],[3:pilihan_1,pilihan_2,pilihan_4],[4:Keterampilan proses dalam IPA pada saat menimbang buah apel menggunakan neraca:mikrometer|Kegiatan membandingkan suatu besaran dengan besaran lain yang sejenis sebagai satuan:Pengamatan|Besaran turunan yang diturunkan dari besaran pokok panjang:Jangka sorong|Alat ukur ketebalan kertas:diafragma],[5:hanya sampai 1.000 hingga 2.000 jam penggunaan saja]', 20.00, 0.00, '[5:0.00]', '2025-05-25 00:20:30', 'perlu_dinilai'),
(48, 17, 'Lintar', 'MAT9-02', 5, '3', '2', '0', '[2:Salah|Salah|Benar][1:pilihan_4][5:Bilangan prima antara 10 dan 15:11 dan 13|Volume kubus dengan rusuk 4 cm:64 cm³|Luas persegi dengan sisi 6 cm:36 cm²|Nilai x dari 2x+5=19:7][4:pilihan_1,pilihan_2,pilihan_3][3:Salah|Salah|Salah|Benar]', '[1:pilihan_4],[2:Benar|Benar|Salah],[3:Salah|Salah|Salah|Benar],[4:pilihan_1,pilihan_3],[5:Bilangan prima antara 10 dan 15:11 dan 13|Volume kubus dengan rusuk 4 cm:64 cm³|Luas persegi dengan sisi 6 cm:36 cm²|Nilai x dari 2x+5=19:7]', 60.00, 0.00, '', '2025-05-25 00:20:45', 'selesai'),
(49, 14, 'Phoebe', 'BINDO7-1', 7, '6', '0', '1', '[3:Benar|Benar|Salah|Benar][6:Benar|Salah][7:pilihan_1,pilihan_2,pilihan_3][1:pilihan_4][5:bn kjbh][4:Pilihan satu:pasangan satu|pilihan dua:pasangan dua|pilihan tiga:pasangan tiga|pilihan empat:pasangan empat][2:pilihan_1,pilihan_2,pilihan_4]', '[1:pilihan_4],[2:pilihan_1,pilihan_2,pilihan_4],[3:Benar|Benar|Salah|Benar],[4:Pilihan satu:pasangan satu|pilihan dua:pasangan dua|pilihan tiga:pasangan tiga|pilihan empat:pasangan empat],[5:icon],[6:Benar|Salah],[7:pilihan_1,pilihan_2,pilihan_3]', 85.71, 14.29, '[5:14.29]', '2025-05-25 18:13:30', 'perlu_dinilai'),
(50, 17, 'Lintar', 'IPA9-01', 5, '2', '2', '1', '[1:pilihan_3][2:Benar|Salah|Benar|Benar][3:pilihan_2,pilihan_3][4:Keterampilan proses dalam IPA pada saat menimbang buah apel menggunakan neraca:mikrometer|Kegiatan membandingkan suatu besaran dengan besaran lain yang sejenis sebagai satuan:Pengamatan|Besaran turunan yang diturunkan dari besaran pokok panjang:Jangka sorong|Alat ukur ketebalan kertas:diafragma][5:cv]', '[1:pilihan_1],[2:Benar|Salah|Benar|Benar],[3:pilihan_1,pilihan_2,pilihan_4],[4:Keterampilan proses dalam IPA pada saat menimbang buah apel menggunakan neraca:mikrometer|Kegiatan membandingkan suatu besaran dengan besaran lain yang sejenis sebagai satuan:Pengamatan|Besaran turunan yang diturunkan dari besaran pokok panjang:Jangka sorong|Alat ukur ketebalan kertas:diafragma],[5:hanya sampai 1.000 hingga 2.000 jam penggunaan saja]', 40.00, 20.00, '[5:20.00]', '2025-05-25 23:57:14', 'perlu_dinilai');

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan`
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
-- Dumping data for table `pengaturan`
--

INSERT INTO `pengaturan` (`id`, `nama_aplikasi`, `logo_sekolah`, `warna_tema`, `waktu_sinkronisasi`, `sembunyikan_nilai`, `login_ganda`, `chat`, `versi_aplikasi`) VALUES
(1, 'CBT-Eschool', 'logo_1747650742.png', '#2f90c1', 60, 0, 'izinkan', 'izinkan', '1.0.7');

-- --------------------------------------------------------

--
-- Table structure for table `profil`
--

CREATE TABLE `profil` (
  `id` int(11) NOT NULL,
  `encrypt` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profil`
--

INSERT INTO `profil` (`id`, `encrypt`) VALUES
(1, 'JmNvcHk7IDIwMjUgR2x1ZHVnIGNvZGVsaXRl');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
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
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nama_siswa`, `password`, `username`, `kelas`, `rombel`, `status`, `session_token`, `last_activity`, `page_url`, `force_logout`) VALUES
(1, 'Jokowi JK', 'h7fV3os6WcZ+hNwtoIN5Si9hbEVndnEzRmNodzJlSktYZ2hVMUE9PQ==', '123456', '9', 'A', 'Nonaktif', '', '2025-05-25 00:12:45', 'http://localhost/cbt-eschool/siswa/ujian.php', 0),
(2, 'Prabowo', 'm9MaPSetPwkYW68qNsWwUlUrOW9HNWFlRzJRVENVVi9xNW9vN0E9PQ==', '123457', '9', 'B', 'Nonaktif', 'b21f01ccbda5fcc557d693a0c9466a40afe9e3c1265cf59b2da08061f8edec18', '2025-05-24 00:41:14', 'http://localhost/cbt-eschool/siswa/chat.php', 0),
(3, 'Agum Gumelar', '5mv6Upz6eP/GpQrkjcebOHcyOFNxV2RRT2xQdkVxRUh0ZVZ0d3c9PQ==', '123458', '9', 'C', 'nonaktif', '', '2025-05-24 22:22:37', 'http://localhost/cbt-eschool/siswa/ujian.php', 0),
(4, 'Deddy ', '5uKDYI7JoYmjpgBTg8LxUi9YZ2dIVGFucU5FM2wySDYvcmFVQXc9PQ==', '123459', '9', 'D', 'nonaktif', '', '0000-00-00 00:00:00', 'http://localhost/cbt-eschool/siswa/dashboard.php', 1),
(5, 'Corbuzier', '/SbMMmTczf7Ry0qUn/f6XmhpM1BYS0l6S1F0cmlHSlB3ZjE1cEE9PQ==', '123461', '9', 'E', 'Nonaktif', '', '2025-05-25 02:49:38', 'http://localhost/cbt-eschool/siswa/dashboard.php', 0),
(13, 'Erina', 'FQOm8MYUIes79E36AQv1AU5VMVdUanhIaTBVTURVS0hXckFRUXc9PQ==', '721731', '9', 'A', 'Nonaktif', '', '0000-00-00 00:00:00', 'http://localhost/cbt-eschool/siswa/dashboard.php', 1),
(14, 'Phoebe', 'Dwl3VYW4ysVVEjO67sy6QmdYb2h0NjNFWjhlV3ViamtWY01hc0E9PQ==', '122345', '7', 'C', 'Nonaktif', '9a394ef4a7893401d0185c9a489d7f17483e8e47a105da3ee7174307346d701c', '2025-05-25 21:08:44', 'http://localhost/cbt-eschool/siswa/preview_hasil.php?id_siswa=14&kode_soal=BINDO7-1', 0),
(15, 'Zevan', 'mG5EAQl0ttZQFaqBXlYCgGdMVkdTMjNQQXZ3VmRKdmFNbTJBeEE9PQ==', '257174', '7', 'D', 'Nonaktif', '', '2025-05-25 00:13:02', 'http://localhost/cbt-eschool/siswa/ujian.php', 0),
(16, 'Denny', 'N2ugxO2xwJR74bjbZQv19nYrMFVFbi9JTEk5MFNDeVdITWxmM0E9PQ==', '641343', '7', 'F', 'Nonaktif', '', '2025-05-25 14:58:48', 'http://localhost/cbt-eschool/siswa/hasil.php', 0),
(17, 'Lintar', 'CJ7fgqg1+lzEgNuqTQwdCUtBeHlsdXdGU3FabGdhQ3lQbXQ2NlE9PQ==', '252743', '9', 'D', 'Nonaktif', '', '2025-05-26 16:48:53', 'http://localhost/cbt-eschool/siswa/dashboard.php', 0),
(18, 'andy', '5IiPhwyWU7/GiyYe622atFErOVViUmNXOXRheVk0Z2U1V0tiK2c9PQ==', '876543', '8', 'D', 'Nonaktif', '', '0000-00-00 00:00:00', 'http://localhost/cbt-eschool/siswa/dashboard.php', 1),
(20, 'Robi', 'XpqGGiL6DfhOGnPqcV9EnUdLdTlEbzdwN1pyVE5wb2FJSStLdUE9PQ==', '252645', '9', 'G', 'Nonaktif', '', '2025-05-24 22:26:17', 'http://localhost/cbt-eschool/siswa/dashboard.php', 0),
(21, 'Intan', 'Ya+NHgRRNME9cYTmSRYUz2sxMS9tczlZMGJDaFd0NkN5TzErV0E9PQ==', '1241322', '7', 'B', 'Nonaktif', '', '0000-00-00 00:00:00', 'http://localhost/cbt-eschool/siswa/dashboard.php', 1);

-- --------------------------------------------------------

--
-- Table structure for table `skor_game`
--

CREATE TABLE `skor_game` (
  `id` int(11) NOT NULL,
  `id_siswa` int(11) DEFAULT NULL,
  `nama_game` varchar(50) DEFAULT NULL,
  `skor` int(11) DEFAULT 0,
  `waktu` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skor_game`
--

INSERT INTO `skor_game` (`id`, `id_siswa`, `nama_game`, `skor`, `waktu`) VALUES
(3, 1, 'math_puzzle', 500, '2025-05-20 13:56:59'),
(4, 2, 'math_puzzle', 170, '2025-05-20 14:47:08'),
(5, 16, 'math_puzzle', 170, '2025-05-20 15:04:28'),
(6, 5, 'math_puzzle', 280, '2025-05-20 21:09:23'),
(7, 17, 'math_puzzle', 340, '2025-05-20 21:42:08'),
(8, 17, 'scramble', 120, '2025-05-21 18:05:24'),
(9, 16, 'scramble', 150, '2025-05-21 21:28:24'),
(10, 1, 'scramble', 130, '2025-05-24 14:58:37'),
(11, 2, 'scramble', 150, '2025-05-22 10:36:27'),
(12, 5, 'scramble', 50, '2025-05-23 00:43:36');

-- --------------------------------------------------------

--
-- Table structure for table `soal`
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
-- Dumping data for table `soal`
--

INSERT INTO `soal` (`id_soal`, `kode_soal`, `nama_soal`, `mapel`, `kelas`, `waktu_ujian`, `tanggal`, `status`, `tampilan_soal`, `kunci`, `token`) VALUES
(1, 'SR9-01', 'Seni Rupa 1', 'Seni Rupa', '9', 90, '2025-05-16', 'Aktif', 'Acak', '[1:pilihan_3],[2:Salah|Salah],[3:Benar|Salah],[4:Teknik Mozaik:Menggunakan potongan bahan seperti kertas atau keramik untuk membentuk gambar|Seni Rupa Murni:Karya seni yang dibuat untuk dinikmati keindahannya seperti lukisan|Relief:Gambar atau ukiran timbul di permukaan dinding atau batu],[5:pilihan_1,pilihan_2,pilihan_4],[6:Salah|Benar],[7:pilihan_1,pilihan_3,pilihan_4],[8:pilihan_1,pilihan_2],[9:ewt],[10:sdfsdf:sdf|sdf:sdfdfgdfff],[11:Benar|Salah],[12:asdsasd],[13:pilihan_2]', 'DHUCJZ'),
(10, 'BINDO7-1', 'B. Indonesia', 'Bahasa Indo', '7', 90, '2025-05-15', 'Aktif', 'Acak', '[1:pilihan_4],[2:pilihan_1,pilihan_2,pilihan_4],[3:Benar|Benar|Salah|Benar],[4:Pilihan satu:pasangan satu|pilihan dua:pasangan dua|pilihan tiga:pasangan tiga|pilihan empat:pasangan empat],[5:icon],[6:Benar|Salah],[7:pilihan_1,pilihan_2,pilihan_3]', 'KJHCAT'),
(17, 'IPA9-01', 'IPA 01', 'IPA', '9', 90, '2025-05-19', 'Aktif', 'Urut', '[1:pilihan_1],[2:Benar|Salah|Benar|Benar],[3:pilihan_1,pilihan_2,pilihan_4],[4:Keterampilan proses dalam IPA pada saat menimbang buah apel menggunakan neraca:mikrometer|Kegiatan membandingkan suatu besaran dengan besaran lain yang sejenis sebagai satuan:Pengamatan|Besaran turunan yang diturunkan dari besaran pokok panjang:Jangka sorong|Alat ukur ketebalan kertas:diafragma],[5:hanya sampai 1.000 hingga 2.000 jam penggunaan saja]', 'XMTOEW'),
(19, 'MAT9-01', 'Matematika 9', 'Matematika', '9', 60, '2025-05-21', 'Nonaktif', 'Acak', '[1:pilihan_4],[2:Benar|Benar|Salah],[3:Salah|Salah|Salah|Benar],[4:pilihan_1,pilihan_3],[5:Bilangan prima antara 10 dan 15:11 dan 13|Volume kubus dengan rusuk 4 cm:64 cm³|Luas persegi dengan sisi 6 cm:36 cm²|Nilai x dari 2x+5=19:7],[6:6]', ''),
(41, 'MAT9-02', 'Matematika 9 (Copy)', 'Matematika', '9', 60, '2025-05-24', 'Aktif', 'Acak', '[1:pilihan_4],[2:Benar|Benar|Salah],[3:Salah|Salah|Salah|Benar],[4:pilihan_1,pilihan_3],[5:Bilangan prima antara 10 dan 15:11 dan 13|Volume kubus dengan rusuk 4 cm:64 cm³|Luas persegi dengan sisi 6 cm:36 cm²|Nilai x dari 2x+5=19:7]', 'HOEZPD');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `butir_soal`
--
ALTER TABLE `butir_soal`
  ADD PRIMARY KEY (`id_soal`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jawaban_siswa`
--
ALTER TABLE `jawaban_siswa`
  ADD PRIMARY KEY (`id_jawaban`),
  ADD UNIQUE KEY `id_jawaban` (`id_jawaban`),
  ADD UNIQUE KEY `unik_jawaban` (`id_siswa`,`kode_soal`),
  ADD KEY `kode_soal` (`kode_soal`);

--
-- Indexes for table `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`id_nilai`),
  ADD UNIQUE KEY `unique_siswa_soal` (`id_siswa`,`kode_soal`);

--
-- Indexes for table `pengaturan`
--
ALTER TABLE `pengaturan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profil`
--
ALTER TABLE `profil`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `skor_game`
--
ALTER TABLE `skor_game`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_siswa` (`id_siswa`);

--
-- Indexes for table `soal`
--
ALTER TABLE `soal`
  ADD PRIMARY KEY (`id_soal`),
  ADD UNIQUE KEY `kode_soal` (`kode_soal`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `butir_soal`
--
ALTER TABLE `butir_soal`
  MODIFY `id_soal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `jawaban_siswa`
--
ALTER TABLE `jawaban_siswa`
  MODIFY `id_jawaban` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=821;

--
-- AUTO_INCREMENT for table `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `skor_game`
--
ALTER TABLE `skor_game`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `soal`
--
ALTER TABLE `soal`
  MODIFY `id_soal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `skor_game`
--
ALTER TABLE `skor_game`
  ADD CONSTRAINT `skor_game_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
