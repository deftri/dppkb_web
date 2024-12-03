-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 03 Des 2024 pada 06.34
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u736419561_sinderela`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `username`, `password_hash`, `role`) VALUES
(1, 'admin', '$2y$10$l.dhu3hzMYpQBF0d/WKrtuw9UECG7u1ctCpwSVN45d3IN46iIvZua', 'admin'),
(2, 'adminadvin', '$2b$12$RxfHyJNn4LYGbBjgXAITDuQtoMLGJDw7sCKPZPu2xHhQ.MgO4Rhja', 'admin'),
(3, 'adminkb', '$2b$12$ieBB89EQm6IotcZFgbmIqO3W3JGMSjoUd39HHFN4yUOBcvTw74apa', 'admin'),
(4, 'adminks', '$2b$12$SQ8KrrNg8qRqSBs1LJRjgeoFYLwlh5ubDfYEP/yxlPiOXSMLfKUz2', 'admin');

-- --------------------------------------------------------

--
-- Struktur dari tabel `antrian`
--

CREATE TABLE `antrian` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `waktu_masuk` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('menunggu','diproses','selesai') DEFAULT 'menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `berita`
--

CREATE TABLE `berita` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `konten` text NOT NULL,
  `tanggal_publikasi` timestamp NOT NULL DEFAULT current_timestamp(),
  `gambar` varchar(255) DEFAULT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `view_count` int(11) DEFAULT 0,
  `kunjungan` int(11) DEFAULT 0,
  `jenis` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `berita`
--

INSERT INTO `berita` (`id`, `judul`, `konten`, `tanggal_publikasi`, `gambar`, `kategori`, `view_count`, `kunjungan`, `jenis`) VALUES
(3, 'DPPKB Muara Enim Latih 50 Konselor Remaja dan Dewasa Tekan Kasus Bullying', 'Bidang ADVIN - Dalam rangka menekan kasus bullying di Kabupaten Muara Enim, Dinas Pengendalian Penduduk dan Keluarga Berencana (DPPKB) Muara Enim menggelar Pelatihan Bagi 50 Konselor Remaja dan Dewasa Tentang Penggunaan Platform dan Pendekatan Integratif di ruang rapat DPPKB Muara Enim, Senin 4 Oktober 2024.\r\n\r\n\"Akhir-akhir ini kasus bullying di Muara Enim terutama di sekolah sudah beberapa kali terjadi dan viral. Makanya kita serius dan menjaring para pelajar untuk dilatih menjadi konselor,\" ujar Kepala Dinas Pengendalian Penduduk dan Keluarga Berencana (DPPKB) Kabupaten Muara Enim H Rinaldo SSTP MSi.\r\n\r\nMenurut Rinaldo, untuk mengantisipasi dan menekan meningkatnya kasus bullying, kekerasan kepada anak dan perempuan baik verbal maupun non verbal, pihaknya berinovasi untuk memberikan pelayanan terintegrasi terhadap Kesehatan Mental Remaja baik secara online ataupun offline yakni Proper Layanan Peningkatan Kesehatan Mental Remaja melaui Sistem Terintegrasi dan Elaborasi Berkelanjutan (SINDERELA). \r\n\r\nDan untuk tahap awal pihaknya akan membentuk Balai Penyuluh KB di 7 kecamatan dalam Kabupaten Muara Enim yakni di Kecamatan  Muara Enim, Tanjung Enim, Tanjung Agung, Ujan Mas, Rambang Niru, Rambang, dan Semende Darat Laut (SDL) sebagai pilot project, nanti secara bertahap akan dipenuhi di setiap Kecamatan akan ada tenaga konselor baik untuk remaja (sebaya), dewasa dan tenaga psikologi yang bersetifikat.', '2024-11-11 04:29:03', 'uploads/WhatsApp Image 2024-11-04 at 14.23.34.jpeg', 'ADVIN', 15, 0, ''),
(4, 'Tekan Kasus Bullying, DPP&KB Muara Enim Kenalkan Inovasi SINDERELA', 'Dinas Pengendalian Penduduk dan Keluarga Berencana (DPPKB) Kabupaten Muara Enim meluncurkan program SINDERELA untuk mengatasi kasus bullying dan menyediakan layanan kesehatan mental remaja secara terintegrasi, baik online maupun offline. Program ini bertujuan meningkatkan kesejahteraan keluarga dengan dua layanan utama: Konseling Kesehatan Mental Remaja dan Konseling PPKS (Pusat Pembelajaran Keluarga Sejahtera).\r\n\r\nSetiap Balai Penyuluh akan dilengkapi ruang konseling dengan konselor sebaya dan dewasa yang didampingi psikolog. Aplikasi website SINDERELA memungkinkan remaja dan keluarga mengakses layanan konseling dengan pilihan online atau offline, dan rujukan ke psikolog jika diperlukan. Program ini didukung oleh berbagai pihak melalui PKS, dan diharapkan dapat meningkatkan Indeks Pembangunan Keluarga (IPK), terutama dalam aspek kesehatan mental.', '2024-11-11 04:37:52', 'uploads/WhatsApp Image 2024-11-01 at 14.00.45.jpeg', 'KS', 14, 0, ''),
(5, 'Identifikasi Audit Kasus Stunting Tahap II Semester 2 Tahun 2024', 'Bidang KB - Dinas Pengendalian Penduduk dan Keluarga Berencana Kabupaten Muara Enim mengadakan Kegiatan Identifikasi Audit Kasus Stunting Semester 2 Tahap II pada 21 Oktober 2024 di Ruang Rapat DPPKB Muara Enim. Kegiatan ini dihadiri oleh petugas gizi, penyuluh KB, dan bidan TPK dari berbagai kecamatan di Muara Enim, serta menghadirkan narasumber dari Tim Pakar Gizi Fakultas Kedokteran Universitas Sriwijaya dan Dokter Spesialis Obgyn RSUD H.M. Rabain. Tujuan kegiatan ini adalah untuk mengidentifikasi dan mencari solusi atas kasus stunting. Diharapkan peserta dapat memahami langkah penyelesaian yang telah dipaparkan oleh narasumber. (Tnk)', '2024-11-11 04:56:42', 'uploads/WhatsApp Image 2024-11-11 at 11.56.20.jpeg', 'KB', 46, 0, '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `bookmark`
--

CREATE TABLE `bookmark` (
  `id` int(11) NOT NULL,
  `berita_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `bookmarks`
--

CREATE TABLE `bookmarks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `berita_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_wilayah` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `chat_sessions`
--

CREATE TABLE `chat_sessions` (
  `id` int(11) NOT NULL,
  `klien_id` int(11) NOT NULL,
  `konselor_id` int(11) NOT NULL,
  `id_wilayah` int(11) DEFAULT NULL,
  `status` enum('menunggu','berlangsung','selesai') DEFAULT 'menunggu',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ended_at` timestamp NULL DEFAULT NULL,
  `psikolog_id` int(11) DEFAULT NULL,
  `refer` tinyint(1) DEFAULT 0,
  `notifikasi` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `footer`
--

CREATE TABLE `footer` (
  `id` int(11) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telepon` varchar(50) NOT NULL,
  `fax` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `footer`
--

INSERT INTO `footer` (`id`, `alamat`, `email`, `telepon`, `fax`) VALUES
(1, 'Jl. Jend. A.K Gani No. 99, Muara Enim, Sumatera Selatan', 'dppkb.muaraenim@gmail.com', '(0734) 421001', '-');

-- --------------------------------------------------------

--
-- Struktur dari tabel `galeri`
--

CREATE TABLE `galeri` (
  `id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `jenis` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `galeri`
--

INSERT INTO `galeri` (`id`, `file_path`, `caption`, `upload_date`, `jenis`) VALUES
(5, 'uploads/galeri/galeri_6749e307aea004.85089104.JPG', 'cover1', '2024-11-29 15:51:35', 'cover'),
(6, 'uploads/galeri/galeri_674ad103288bb8.57361776.JPG', 'SAMPUL BARU', '2024-11-30 08:46:59', 'cover');

-- --------------------------------------------------------

--
-- Struktur dari tabel `halaman_statis`
--

CREATE TABLE `halaman_statis` (
  `id` int(10) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `konten` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`) VALUES
(10, 'KB'),
(11, 'KS'),
(12, 'ADVIN'),
(13, 'SEKRETARIAT');

-- --------------------------------------------------------

--
-- Struktur dari tabel `komentar`
--

CREATE TABLE `komentar` (
  `id` int(11) NOT NULL,
  `berita_id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `isi` text NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `komentar`
--

INSERT INTO `komentar` (`id`, `berita_id`, `nama`, `isi`, `tanggal`) VALUES
(1, 5, 'Deftri', 'Mantap', '2024-11-11 04:57:54'),
(2, 5, 'Dayat', 'Mantap', '2024-11-12 15:59:10'),
(3, 5, 'wa', 'wa', '2024-11-29 17:07:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `aktivitas` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `messages_feedback`
--

CREATE TABLE `messages_feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `status` enum('pending','read','responded') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `messages_feedback`
--

INSERT INTO `messages_feedback` (`id`, `name`, `email`, `subject`, `message`, `created_at`, `status`) VALUES
(2, 'wad', 'adadaw@gmail.com', 'dada', 'dada', '2024-11-30 12:22:54', 'read');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tipe` enum('berita_baru','komentar_baru') DEFAULT NULL,
  `isi` text DEFAULT NULL,
  `status` enum('belum_dibaca','dibaca') DEFAULT 'belum_dibaca',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `isi_notifikasi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `notifikasi`
--

INSERT INTO `notifikasi` (`id`, `user_id`, `tipe`, `isi`, `status`, `created_at`, `isi_notifikasi`) VALUES
(1, 0, 'berita_baru', 'Berita baru menunggu persetujuan', 'belum_dibaca', '2024-11-10 19:02:26', ''),
(2, 0, NULL, NULL, 'belum_dibaca', '2024-11-10 19:19:48', 'Komentar baru ditambahkan pada berita ID: 1'),
(3, 0, NULL, NULL, 'belum_dibaca', '2024-11-10 19:20:01', 'Komentar baru ditambahkan pada berita ID: 1'),
(4, 0, 'berita_baru', 'Berita baru menunggu persetujuan', 'belum_dibaca', '2024-11-10 23:57:50', ''),
(5, 0, NULL, NULL, 'belum_dibaca', '2024-11-10 23:57:50', 'Berita baru ditambahkan: ORANG TUA HEBAT'),
(6, 0, 'berita_baru', 'Berita baru menunggu persetujuan', 'belum_dibaca', '2024-11-11 04:29:03', ''),
(7, 0, NULL, NULL, 'belum_dibaca', '2024-11-11 04:29:03', 'Berita baru ditambahkan: DPPKB Muara Enim Latih 50 Konselor Remaja dan Dewasa Tekan Kasus Bullying'),
(8, 0, 'berita_baru', 'Berita baru menunggu persetujuan', 'belum_dibaca', '2024-11-11 04:37:52', ''),
(9, 0, NULL, NULL, 'belum_dibaca', '2024-11-11 04:37:52', 'Berita baru ditambahkan: Tekan Kasus Bullying, DPP&KB Muara Enim Kenalkan Inovasi SINDERELA'),
(10, 0, 'berita_baru', 'Berita baru menunggu persetujuan', 'belum_dibaca', '2024-11-11 04:56:42', ''),
(11, 0, NULL, NULL, 'belum_dibaca', '2024-11-11 04:56:42', 'Berita baru ditambahkan: Identifikasi Audit Kasus Stunting Tahap II Semester 2 Tahun 2024');

-- --------------------------------------------------------

--
-- Struktur dari tabel `offline_sessions`
--

CREATE TABLE `offline_sessions` (
  `id` int(11) NOT NULL,
  `klien_id` int(11) DEFAULT NULL,
  `wilayah_id` int(11) DEFAULT NULL,
  `jadwal` datetime DEFAULT NULL,
  `status` enum('menunggu','terkonfirmasi') DEFAULT 'menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','kontributor','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `otp_code` varchar(6) DEFAULT NULL,
  `otp_expires` timestamp NULL DEFAULT NULL,
  `poin` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`id`, `username`, `email`, `password_hash`, `role`, `created_at`, `otp_code`, `otp_expires`, `poin`) VALUES
(1, 'deftri', 'deftriprihandaru.az@gmail.com', '$2y$10$W3sbKx6DdEI9GLhBzbCvaO0a.VWInKOJqXHX6kvU2Ckc2Ra/ei26a', 'user', '2024-11-10 22:54:06', '744240', '2024-11-10 17:07:03', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `tanggal` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengumuman`
--

INSERT INTO `pengumuman` (`id`, `judul`, `isi`, `tanggal`) VALUES
(99, 'SEGERAH', 'COMING SOON!', '2025-02-28 00:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `poll`
--

CREATE TABLE `poll` (
  `id` int(11) NOT NULL,
  `vote` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `poll`
--

INSERT INTO `poll` (`id`, `vote`, `created_at`) VALUES
(1, 'Ya', '2024-11-29 16:25:04'),
(2, 'Cukup', '2024-11-29 16:25:04'),
(3, 'Tidak', '2024-11-29 16:25:04'),
(4, 'Tidak Tahu', '2024-11-29 16:25:04');

-- --------------------------------------------------------

--
-- Struktur dari tabel `polling`
--

CREATE TABLE `polling` (
  `id` int(10) UNSIGNED NOT NULL,
  `pertanyaan` varchar(255) NOT NULL,
  `opsi1` varchar(100) NOT NULL,
  `opsi2` varchar(100) NOT NULL,
  `opsi3` varchar(100) DEFAULT NULL,
  `opsi4` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `polls`
--

CREATE TABLE `polls` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `options` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `poll_votes`
--

CREATE TABLE `poll_votes` (
  `id` int(11) NOT NULL,
  `poll_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vote` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `program_ppkb`
--

CREATE TABLE `program_ppkb` (
  `id` int(11) NOT NULL,
  `judul_program` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_berakhir` date DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `link_detail` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rating`
--

CREATE TABLE `rating` (
  `id` int(11) NOT NULL,
  `berita_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `berita_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rating_konselor`
--

CREATE TABLE `rating_konselor` (
  `id` int(11) NOT NULL,
  `konselor_id` int(11) NOT NULL,
  `klien_id` int(11) NOT NULL,
  `rating` float DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rating_sinderela`
--

CREATE TABLE `rating_sinderela` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rated_by_id` int(11) NOT NULL,
  `rating` float NOT NULL,
  `feedback` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `konselor_id` int(11) NOT NULL,
  `klien_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `renja`
--

CREATE TABLE `renja` (
  `id` int(11) NOT NULL,
  `kegiatan` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `tahun` int(11) NOT NULL,
  `status` enum('Aktif','Selesai','Tertunda') NOT NULL,
  `tanggal_dibuat` datetime DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `renja`
--

INSERT INTO `renja` (`id`, `kegiatan`, `deskripsi`, `tahun`, `status`, `tanggal_dibuat`, `user_id`) VALUES
(2, 'KEGIATAN', 'TES 1', 2025, 'Aktif', '2024-11-30 08:53:22', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `report`
--

CREATE TABLE `report` (
  `id` int(11) NOT NULL,
  `komentar_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `alasan` text DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sesi_konseling`
--

CREATE TABLE `sesi_konseling` (
  `id` int(11) NOT NULL,
  `konselor_id` int(11) NOT NULL,
  `klien_id` int(11) NOT NULL,
  `status` enum('menunggu','berlangsung','selesai') DEFAULT 'menunggu',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `site_title` varchar(255) NOT NULL,
  `site_description` text NOT NULL,
  `logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `site_settings`
--

INSERT INTO `site_settings` (`id`, `site_title`, `site_description`, `logo`) VALUES
(1, 'DPPKB Kabupaten Muara Enim', 'Deskripsi situs Anda di sini.', NULL),
(2, 'DPPKB Kabupaten Muara Enim', 'Deskripsi situs Anda di sini.', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `statistik`
--

CREATE TABLE `statistik` (
  `id` int(11) NOT NULL,
  `halaman` varchar(255) DEFAULT NULL,
  `kunjungan` int(11) DEFAULT 1,
  `tanggal` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `statistik`
--

INSERT INTO `statistik` (`id`, `halaman`, `kunjungan`, `tanggal`) VALUES
(1, 'index.php', 1, '2024-11-10'),
(2, 'index.php', 1, '2024-11-10'),
(3, 'index.php', 1, '2024-11-10'),
(4, 'index.php', 1, '2024-11-10'),
(5, 'index.php', 1, '2024-11-10'),
(6, 'index.php', 1, '2024-11-10'),
(7, 'index.php', 1, '2024-11-10'),
(8, 'index.php', 1, '2024-11-10'),
(9, 'index.php', 1, '2024-11-10'),
(10, 'detail_berita.php', 1, '2024-11-10'),
(11, 'detail_berita.php', 1, '2024-11-10'),
(12, 'detail_berita.php', 1, '2024-11-10'),
(13, 'detail_berita.php', 1, '2024-11-10'),
(14, 'detail_berita.php', 1, '2024-11-10'),
(15, 'detail_berita.php', 1, '2024-11-10'),
(16, 'detail_berita.php', 1, '2024-11-10'),
(17, 'detail_berita.php', 1, '2024-11-10'),
(18, 'detail_berita.php', 1, '2024-11-10'),
(19, 'detail_berita.php', 1, '2024-11-10'),
(20, 'detail_berita.php', 1, '2024-11-10'),
(21, 'detail_berita.php', 1, '2024-11-10'),
(22, 'detail_berita.php', 1, '2024-11-10'),
(23, 'index.php', 1, '2024-11-10'),
(24, 'detail_berita.php', 1, '2024-11-10'),
(25, 'detail_berita.php', 1, '2024-11-10'),
(26, 'detail_berita.php', 1, '2024-11-10'),
(27, 'index.php', 1, '2024-11-10'),
(28, 'index.php', 1, '2024-11-10'),
(29, 'index.php', 1, '2024-11-10'),
(30, 'detail_berita.php', 1, '2024-11-10'),
(31, 'index.php', 1, '2024-11-10'),
(32, 'index.php', 1, '2024-11-10'),
(33, 'index.php', 1, '2024-11-10'),
(34, 'index.php', 1, '2024-11-10'),
(35, 'index.php', 1, '2024-11-10'),
(36, 'index.php', 1, '2024-11-10'),
(37, 'index.php', 1, '2024-11-10'),
(38, 'index.php', 1, '2024-11-10'),
(39, 'index.php', 1, '2024-11-10'),
(40, 'index.php', 1, '2024-11-10'),
(41, 'index.php', 1, '2024-11-10'),
(42, 'index.php', 1, '2024-11-10'),
(43, 'index.php', 1, '2024-11-10'),
(44, 'index.php', 1, '2024-11-10'),
(45, 'index.php', 1, '2024-11-10'),
(46, 'index.php', 1, '2024-11-10'),
(47, 'index.php', 1, '2024-11-10'),
(48, 'index.php', 1, '2024-11-10'),
(49, 'index.php', 1, '2024-11-10'),
(50, 'index.php', 1, '2024-11-10'),
(51, 'index.php', 1, '2024-11-10'),
(52, 'index.php', 1, '2024-11-10'),
(53, 'index.php', 1, '2024-11-10'),
(54, 'index.php', 1, '2024-11-10'),
(55, 'index.php', 1, '2024-11-10'),
(56, 'index.php', 1, '2024-11-10'),
(57, 'index.php', 1, '2024-11-10'),
(58, 'detail_berita.php', 1, '2024-11-10'),
(59, 'index.php', 1, '2024-11-10'),
(60, 'index.php', 1, '2024-11-10'),
(61, 'index.php', 1, '2024-11-10'),
(62, 'index.php', 1, '2024-11-10'),
(63, 'index.php', 1, '2024-11-10'),
(64, 'index.php', 1, '2024-11-10'),
(65, 'index.php', 1, '2024-11-10'),
(66, 'index.php', 1, '2024-11-10'),
(67, 'index.php', 1, '2024-11-10'),
(68, 'index.php', 1, '2024-11-10'),
(69, 'index.php', 1, '2024-11-10'),
(70, 'index.php', 1, '2024-11-10'),
(71, 'index.php', 1, '2024-11-10'),
(72, 'index.php', 1, '2024-11-10'),
(73, 'index.php', 1, '2024-11-10'),
(74, 'index.php', 1, '2024-11-10'),
(75, 'index.php', 1, '2024-11-10'),
(76, 'index.php', 1, '2024-11-10'),
(77, 'index.php', 1, '2024-11-10'),
(78, 'index.php', 1, '2024-11-10'),
(79, 'index.php', 1, '2024-11-10'),
(80, 'index.php', 1, '2024-11-10'),
(81, 'index.php', 1, '2024-11-10'),
(82, 'index.php', 1, '2024-11-10'),
(83, 'index.php', 1, '2024-11-10'),
(84, 'index.php', 1, '2024-11-10'),
(85, 'index.php', 1, '2024-11-10'),
(86, 'index.php', 1, '2024-11-10'),
(87, 'index.php', 1, '2024-11-10'),
(88, 'index.php', 1, '2024-11-10'),
(89, 'index.php', 1, '2024-11-10'),
(90, 'index.php', 1, '2024-11-10'),
(91, 'index.php', 1, '2024-11-10'),
(92, 'index.php', 1, '2024-11-10'),
(93, 'index.php', 1, '2024-11-10'),
(94, 'index.php', 1, '2024-11-10'),
(95, 'index.php', 1, '2024-11-10'),
(96, 'index.php', 1, '2024-11-10'),
(97, 'index.php', 1, '2024-11-10'),
(98, 'index.php', 1, '2024-11-10'),
(99, 'index.php', 1, '2024-11-10'),
(100, 'index.php', 1, '2024-11-10'),
(101, 'index.php', 1, '2024-11-10'),
(102, 'index.php', 1, '2024-11-10'),
(103, 'index.php', 1, '2024-11-10'),
(104, 'index.php', 1, '2024-11-10'),
(105, 'index.php', 1, '2024-11-10'),
(106, 'index.php', 1, '2024-11-10'),
(107, 'index.php', 1, '2024-11-10'),
(108, 'index.php', 1, '2024-11-10'),
(109, 'index.php', 1, '2024-11-10'),
(110, 'index.php', 1, '2024-11-10'),
(111, 'index.php', 1, '2024-11-10'),
(112, 'index.php', 1, '2024-11-10'),
(113, 'index.php', 1, '2024-11-10'),
(114, 'index.php', 1, '2024-11-10'),
(115, 'index.php', 1, '2024-11-10'),
(116, 'index.php', 1, '2024-11-10'),
(117, 'index.php', 1, '2024-11-10'),
(118, 'index.php', 1, '2024-11-10'),
(119, 'index.php', 1, '2024-11-10'),
(120, 'index.php', 1, '2024-11-10'),
(121, 'index.php', 1, '2024-11-10'),
(122, 'index.php', 1, '2024-11-10'),
(123, 'index.php', 1, '2024-11-10'),
(124, 'index.php', 1, '2024-11-10'),
(125, 'index.php', 1, '2024-11-10'),
(126, 'index.php', 1, '2024-11-10'),
(127, 'index.php', 1, '2024-11-10'),
(128, 'index.php', 1, '2024-11-10'),
(129, 'kelola_kategori.php', 1, '2024-11-10'),
(130, 'kelola_kategori.php', 1, '2024-11-10'),
(131, 'kelola_kategori.php', 1, '2024-11-10'),
(132, 'kelola_kategori.php', 1, '2024-11-10'),
(133, 'kelola_kategori.php', 1, '2024-11-10'),
(134, 'kelola_kategori.php', 1, '2024-11-10'),
(135, 'kelola_kategori.php', 1, '2024-11-10'),
(136, 'kelola_kategori.php', 1, '2024-11-10'),
(137, 'kelola_kategori.php', 1, '2024-11-10'),
(138, 'kelola_kategori.php', 1, '2024-11-10'),
(139, 'kelola_kategori.php', 1, '2024-11-10'),
(140, 'kelola_kategori.php', 1, '2024-11-10'),
(141, 'kelola_kategori.php', 1, '2024-11-10'),
(142, 'kelola_kategori.php', 1, '2024-11-10'),
(143, 'kelola_kategori.php', 1, '2024-11-10'),
(144, 'kelola_kategori.php', 1, '2024-11-10'),
(145, 'kelola_kategori.php', 1, '2024-11-10'),
(146, 'kelola_kategori.php', 1, '2024-11-10'),
(147, 'kelola_kategori.php', 1, '2024-11-10'),
(148, 'login_user.php', 1, '2024-11-10'),
(149, 'login_user.php', 1, '2024-11-10'),
(150, 'login_user.php', 1, '2024-11-10'),
(151, 'login_user.php', 1, '2024-11-10'),
(152, 'login_user.php', 1, '2024-11-10'),
(153, 'login_user.php', 1, '2024-11-10'),
(154, 'login_user.php', 1, '2024-11-11'),
(155, 'login_user.php', 1, '2024-11-11'),
(156, 'kelola_kategori.php', 1, '2024-11-11'),
(157, 'login_user.php', 1, '2024-11-11'),
(158, 'login_user.php', 1, '2024-11-11'),
(159, 'login_user.php', 1, '2024-11-11'),
(160, 'login_user.php', 1, '2024-11-11'),
(161, 'halaman_utama.php', 1, '2024-11-11'),
(162, 'halaman_utama.php', 1, '2024-11-11'),
(163, 'index.php', 138, '2024-11-11'),
(164, 'index.php', 138, '2024-11-11'),
(165, 'index.php', 138, '2024-11-11'),
(166, 'index.php', 138, '2024-11-11'),
(167, 'index.php', 138, '2024-11-11'),
(168, 'index.php', 138, '2024-11-11'),
(169, 'index.php', 138, '2024-11-11'),
(170, 'index.php', 138, '2024-11-11'),
(171, 'index.php', 138, '2024-11-11'),
(172, 'index.php', 138, '2024-11-11'),
(173, 'index.php', 138, '2024-11-11'),
(174, 'index.php', 138, '2024-11-11'),
(175, 'index.php', 138, '2024-11-11'),
(176, 'index.php', 138, '2024-11-11'),
(177, 'index.php', 138, '2024-11-11'),
(178, 'index.php', 138, '2024-11-11'),
(179, 'index.php', 138, '2024-11-11'),
(180, 'index.php', 138, '2024-11-11'),
(181, 'index.php', 138, '2024-11-11'),
(182, 'index.php', 138, '2024-11-11'),
(183, 'index.php', 138, '2024-11-11'),
(184, 'index.php', 138, '2024-11-11'),
(185, 'index.php', 138, '2024-11-11'),
(186, 'index.php', 138, '2024-11-11'),
(187, 'index.php', 138, '2024-11-11'),
(188, 'index.php', 138, '2024-11-11'),
(189, 'index.php', 138, '2024-11-11'),
(190, 'index.php', 138, '2024-11-11'),
(191, 'index.php', 138, '2024-11-11'),
(192, 'index.php', 138, '2024-11-11'),
(193, 'index.php', 138, '2024-11-11'),
(194, 'index.php', 138, '2024-11-11'),
(195, 'index.php', 138, '2024-11-11'),
(196, 'index.php', 138, '2024-11-11'),
(197, 'index.php', 138, '2024-11-11'),
(198, 'index.php', 138, '2024-11-11'),
(199, 'index.php', 138, '2024-11-11'),
(200, 'index.php', 138, '2024-11-11'),
(201, 'index.php', 138, '2024-11-11'),
(202, 'index.php', 138, '2024-11-11'),
(203, 'index.php', 138, '2024-11-11'),
(204, 'index.php', 138, '2024-11-11'),
(205, 'index.php', 138, '2024-11-11'),
(206, 'index.php', 138, '2024-11-11'),
(207, 'index.php', 138, '2024-11-11'),
(208, 'index.php', 138, '2024-11-11'),
(209, 'index.php', 138, '2024-11-11'),
(210, 'index.php', 138, '2024-11-11'),
(211, 'index.php', 138, '2024-11-11'),
(212, 'index.php', 138, '2024-11-11'),
(213, 'index.php', 138, '2024-11-11'),
(214, 'index.php', 138, '2024-11-11'),
(215, 'index.php', 138, '2024-11-11'),
(216, 'index.php', 138, '2024-11-11'),
(217, 'index.php', 138, '2024-11-11'),
(218, 'index.php', 138, '2024-11-11'),
(219, 'index.php', 138, '2024-11-11'),
(220, 'index.php', 138, '2024-11-11'),
(221, 'index.php', 138, '2024-11-11'),
(222, 'index.php', 138, '2024-11-11'),
(223, 'index.php', 138, '2024-11-11'),
(224, 'index.php', 138, '2024-11-11'),
(225, 'index.php', 138, '2024-11-11'),
(226, 'index.php', 138, '2024-11-11'),
(227, 'index.php', 138, '2024-11-11'),
(228, 'index.php', 138, '2024-11-11'),
(229, 'kelola_kategori.php', 1, '2024-11-11'),
(230, 'kelola_kategori.php', 1, '2024-11-11'),
(231, 'kelola_kategori.php', 1, '2024-11-11'),
(232, 'kelola_kategori.php', 1, '2024-11-11'),
(233, 'kelola_kategori.php', 1, '2024-11-11'),
(234, 'kelola_kategori.php', 1, '2024-11-11'),
(235, 'kelola_kategori.php', 1, '2024-11-11'),
(236, 'kelola_kategori.php', 1, '2024-11-11'),
(237, 'kelola_kategori.php', 1, '2024-11-11'),
(238, 'kelola_kategori.php', 1, '2024-11-11'),
(239, 'detail_berita.php', 27, '2024-11-11'),
(240, 'detail_berita.php', 27, '2024-11-11'),
(241, 'detail_berita.php', 27, '2024-11-11'),
(242, 'detail_berita.php', 27, '2024-11-11'),
(243, 'sitemap.xml', 2, '2024-11-11'),
(244, 'robots.txt', 2, '2024-11-11'),
(245, 'index.js', 1, '2024-11-11'),
(246, 'index.php', 40, '2024-11-12'),
(247, 'detail_berita.php', 21, '2024-11-12'),
(248, 'index.php', 154, '2024-11-13'),
(249, 'detail_berita.php', 8, '2024-11-13'),
(250, 'kelola_kategori.php', 1, '2024-11-13'),
(251, 'kelola_kategori.php', 1, '2024-11-13'),
(252, 'index.php', 80, '2024-11-14'),
(253, 'kelola_kategori.php', 1, '2024-11-14'),
(254, 'detail_berita.php', 1, '2024-11-14'),
(255, 'index.php', 564, '2024-11-29'),
(256, 'kelola_kategori.php', 1, '2024-11-29'),
(257, 'kelola_kategori.php', 1, '2024-11-29'),
(258, 'kelola_kategori.php', 1, '2024-11-29'),
(259, 'kelola_kategori.php', 1, '2024-11-29'),
(260, 'detail_berita.php', 38, '2024-11-29'),
(261, 'kelola_kategori.php', 1, '2024-11-29'),
(262, 'kelola_kategori.php', 1, '2024-11-29'),
(263, 'index.php', 72, '2024-11-30'),
(264, NULL, 1, '2024-11-30'),
(265, 'kelola_kategori.php', 1, '2024-11-30'),
(266, 'detail_berita.php', 9, '2024-11-30'),
(267, 'kelola_kategori.php', 1, '2024-11-30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nomor_hp` varchar(15) DEFAULT NULL,
  `id_wilayah` int(11) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('klien','konselor','psikolog','admin') NOT NULL,
  `session` tinyint(1) DEFAULT 0,
  `ratting` float DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_online` tinyint(1) DEFAULT 0,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `sub_role` enum('Dewasa','Sebaya','Psikolog','Admin','Klien') NOT NULL DEFAULT 'Klien'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `nama`, `nomor_hp`, `id_wilayah`, `password_hash`, `role`, `session`, `ratting`, `is_verified`, `reset_token`, `token_expiry`, `is_online`, `email`, `created_at`, `sub_role`) VALUES
(1, 'admin', 'admin sinderela', '0822780529621', 10, '$2y$10$SEUnBETLW8FmsoFCyfu7CeJvUaNxCBcjtI5D0Hf.DpU./M7Kur1qG', 'admin', 0, NULL, 0, NULL, '2024-11-30 17:32:06', 1, '', '2024-11-13 18:38:05', 'Dewasa'),
(77, 'deftri', 'deftri', '0812234567890', 10, '$2y$10$SEUnBETLW8FmsoFCyfu7CeJvUaNxCBcjtI5D0Hf.DpU./M7Kur1qG', 'klien', 0, NULL, 0, NULL, '2024-11-30 11:50:11', 0, 'deftriprihandaru.az@gmail.com', '2024-11-30 10:39:53', 'Dewasa'),
(9999, 'Sistem', '', NULL, 10, '$2y$10$ry4ALT9XlY8I2tpW39STA.2.jqc2JtagAuwehetdwNJ6MneWu.Fd.', 'admin', 0, NULL, 0, NULL, '2024-11-30 15:15:48', 0, '', '2024-11-13 18:38:05', 'Dewasa'),
(10165, 'kss.lawangkidul.01', 'Wisya Angel Chelsy Ananta ', '085953298613', 9, '$2y$10$2vxbk1D3dCr3gTCfVbVg5Owwc1rL/BJXBardldFL5wVqvdLOs.gZO', 'konselor', 0, NULL, 0, NULL, '2024-11-30 20:01:17', 0, 'udinsar63@gmail.com', '2024-11-30 13:51:22', 'Sebaya'),
(10166, 'kss.lawangkidul.02', 'afifah fajarina kemuning', '0851216503622', 9, '$2y$10$bpZeu/o9lPVNNEnVhENzzeHQgU8caC5Cd1QxJShVn3bDNdYdmovuu', 'konselor', 0, NULL, 0, NULL, '2024-11-30 20:01:13', 0, 'afifahfajarina575@gmail.com', '2024-11-30 13:54:09', 'Sebaya'),
(10167, 'kss.muaraenim.01', 'Kayla Khairunnisa Maharani', '0852375339007', 10, '$2y$10$bDqjUmeLnNhWCtn6m6NA1e9JIZF/93b6x8TeR47OiiwlGqPuSxaLq', 'konselor', 0, NULL, 0, NULL, '2024-11-30 20:01:10', 0, 'yosephat_tribuono2006@yahoo.com', '2024-11-30 14:04:36', 'Sebaya'),
(10168, 'kss.muaraenim.02', 'Bintang Zahira', '0882281003489', 10, '$2y$10$P3SX7PwckeYaEzPOvFl/re3PHvK9kjZwPShc9RlwJRY6vYsSJ3Bhq', 'konselor', 0, NULL, 0, NULL, '2024-11-30 20:01:05', 0, 'bintangzahira76@gmail.com', '2024-11-30 14:05:34', 'Sebaya'),
(10169, 'kss.rambang.01', 'Weni Juliana', '088286204689', 6, '$2y$10$tVYJD7ls729E7AokPbeoJuay0pNVvNswCMbYeePx42jILBiMz7iGS', 'konselor', 0, NULL, 0, NULL, '2024-11-30 20:00:58', 0, 'Wenijulianaarr@gmail.Com', '2024-11-30 14:07:21', 'Sebaya'),
(10170, 'kss.rambang.02', 'Desy Sri wahyuni', '0851369615419', 6, '$2y$10$uXbhHoC6gDEe..27JAEjPOrzbN8ZtUYQH7h91Jxc/PAo4eg7/fAem', 'konselor', 0, NULL, 0, NULL, '2024-11-30 20:01:02', 0, 'wahyunidesysri@gmail.com', '2024-11-30 14:20:11', 'Sebaya'),
(10171, 'kss.rambangniru.01', 'Salsabilla khoirun nisa', '0851283544148', 15, '$2y$10$M90mlWihRII3pgMC7GWlXOdgG3uf.JqD3OeK26OIWA4EhjYQH027y', 'konselor', 0, NULL, 0, NULL, '2024-11-30 20:00:55', 0, 'salsabila618489@gmail.com', '2024-11-30 14:26:58', 'Sebaya'),
(10172, 'kss.rambangniru.02', 'Renti Febriyani ', '0852279351980', 15, '$2y$10$sA7Zwlw.m7qDiH/H/UbJe.sKVbtbVLvw7QcyLd.7XaAtXmf6Z5Hcu', 'konselor', 0, NULL, 0, NULL, '2024-11-30 20:00:51', 0, 'renti@gmail.com', '2024-11-30 14:36:50', 'Sebaya'),
(10173, 'kss.sdl.01', 'Shifatun Naswa Shafwatillah', '085783951711', 1, '$2y$10$/cHrD93dPZLcmbV4TX4RvOXmB9la2tih6wxn4ue0kIzU3UasZbn1.', 'konselor', 0, NULL, 0, NULL, '2024-11-30 20:00:48', 0, 'shifatun.naswa50@smp.belajar.id', '2024-11-30 14:42:40', 'Sebaya'),
(10177, 'kss.sdl.02', 'REVALIA PUTRI UTAMI', '0851318776372', 1, '$2y$10$pzgwbSuti40GQZ57cexE3uo7xCgLcErHgBfoDwID0njBINSUwv5gO', 'konselor', 0, NULL, 0, NULL, '2024-11-30 20:00:43', 0, 'revaliaputriutami@gmail.com', '2024-11-30 14:51:46', 'Sebaya'),
(10178, 'kss.tanjungagung.01', 'Fadillah Birahmatika', '085788694928', 4, '$2y$10$Nfkaa7kbTjXZFoChWnjcUOl4K4IWrVMSYNI4I25iBmGNRlhTur1NK', 'konselor', 0, NULL, 0, NULL, '2024-11-30 20:00:40', 0, 'fadilah@gmail.com', '2024-11-30 14:55:43', 'Sebaya'),
(10181, 'kss.tanjungagung.02', 'Elisa Oktavia', '085788418166', 1, '$2y$10$lAOkl7vEQpsmKPJC7gdQcO0S0zaxXZDB3QTcN.rdbBjZcbTEEm3w2', 'konselor', 0, NULL, 0, NULL, '2024-11-30 20:00:27', 0, 'elisa@gmail.com', '2024-11-30 15:06:12', 'Sebaya'),
(10189, 'ksd.lawangkidul.02', 'Firdi Yusan Zekkri', '081321640042', 9, '$2y$10$T.3Pij4S91EFRMjW/IqljeT9hRssEka9p4sFrS4dVCMcYsv2aHlte', 'konselor', 0, NULL, 0, NULL, '2024-11-30 19:45:05', 0, 'firdi.yz19842@gmail.com', '2024-11-30 19:44:16', 'Dewasa'),
(10191, 'ksd.muaraenim.01', 'FADILLAH, SH', '081373553456', 10, '$2y$10$m9nr0kEfo5sDLXs59ctZ4.JLOZLTeGgFtEXuRBAmR/X/zZDdtqtOO', 'konselor', 0, NULL, 0, NULL, '2024-11-30 19:58:20', 0, 'fadillahbiru@gmail.com', '2024-11-30 19:46:04', 'Dewasa'),
(10192, 'ksd.muaraenim.02', 'Hesty Setiyorini', '082289885750', 10, '$2y$10$aCcNIJUYGVoOQ9P4y9NyY.8vyYvn7pPvHfPFn/ovWhI1EnCYoBuJC', 'konselor', 0, NULL, 0, NULL, '2024-11-30 20:00:14', 0, 'hestysetiyorini1234@gmail.com', '2024-11-30 19:46:21', 'Dewasa'),
(10193, 'ksd.rambang.01', 'Helmiyahro SKM ', '085768736821', 6, '$2y$10$8AULCgG2X/82DXpwVr7vOO7QWvG6qt.PbBwLptLOqS3bLt6H7ZOZC', 'konselor', 0, NULL, 0, NULL, '2024-11-30 20:00:05', 0, 'helmiyahro27@gmail.com', '2024-11-30 19:47:14', 'Dewasa'),
(10195, 'ksd.rambang.02', 'LOKEN LASRY', '085368025694', 6, '$2y$10$lYtmExxgC9NBB95LXn/lkea5nyI2tVjBg7AGrar9/kqvwsu6s6a.C', 'konselor', 0, NULL, 0, NULL, '2024-11-30 19:59:48', 0, 'lokenleni@gmail.com', '2024-11-30 19:49:42', 'Dewasa'),
(10196, 'ksd.rambangniru.01', 'Harce Juliantino ', '085273577781', 15, '$2y$10$eI.PF9obUBcGzvD.Dkj/Hu7UlCbeCl4bauWTvm.hDH4EO/MwTZaHK', 'konselor', 0, NULL, 0, NULL, '2024-11-30 19:59:40', 0, 'harce.juliantino@gmail.com', '2024-11-30 19:50:14', 'Dewasa'),
(10197, 'ksd.rambangniru.02', 'Arlinda, S. Si', '081218555986', 15, '$2y$10$jJsLBTpVXX2p2hnAdcK6fe/o.bbAd8gUUhJivSN2qMBHvC/HwSeE.', 'konselor', 0, NULL, 0, NULL, '2024-11-30 19:59:31', 0, 'arlindaae15@gmail.com', '2024-11-30 19:50:49', 'Dewasa'),
(10198, 'ksd.sdl.01', 'DESSY WIDIANTI, S.P', '082378707172', 1, '$2y$10$8ZdoqYTI9EvXBsXS59W/tOIZlu46aN5TURS6njwgCf5osuruPtvJe', 'konselor', 0, NULL, 0, NULL, '2024-11-30 19:59:22', 0, 'dechy.wiwid21@gmail.com', '2024-11-30 19:51:33', 'Dewasa'),
(10199, 'ksd.sdl.02', 'AGUSTINAH, S.I.P', '082278429569', 1, '$2y$10$7.gofptjbmRHhfmhdiVsYOIdALtgLyhnqQNjqUmIQSvTSluJae4.y', 'konselor', 0, NULL, 0, NULL, '2024-11-30 19:59:09', 0, 'agustinahsdl@gmail.com', '2024-11-30 19:52:27', 'Dewasa'),
(10200, 'ksd.tanjungagung.01', 'AGUSTINAH, S.I.P', '083802414652', 4, '$2y$10$cfuyBJyswjfunFJxwkTYj.s1ZAmcd7R8g9x77XmAN0TZOBnp3liTm', 'konselor', 0, NULL, 0, NULL, '2024-11-30 19:59:01', 0, 'dianagustiani469@gmail.com', '2024-11-30 19:53:32', 'Dewasa'),
(10201, 'ksd.tanjungagung.02', 'Risma Anilawati S.Ag', '082282635141', 4, '$2y$10$uigLK41dji.BwOHosbT8seMFWc2TpoSH9WsUZ/d5WNwJfkiaWEPBa', 'konselor', 0, NULL, 0, NULL, '2024-11-30 19:58:49', 0, 'rismaanilawati13@gmail.com', '2024-11-30 19:54:19', 'Dewasa'),
(10202, 'ksd.ujanmas.01', 'DARLENAH ', '082177372336', 11, '$2y$10$8ju/uXBHVMQtx00R1RaniOcSvdoC/GYgKV/V8wkou1Z0JILDnzWxG', 'konselor', 0, NULL, 0, NULL, '2024-11-30 19:58:39', 0, 'darlenah4@gmail.com', '2024-11-30 19:54:45', 'Dewasa'),
(10204, 'ksd.ujanmas.02', 'Rina Andriani ', '08136775292', 11, '$2y$10$j7ps0W.L5n1HxZByy4kryOA6vZYswVq0BUeJQMO/RtRimV100CKwm', 'konselor', 0, NULL, 0, NULL, '2024-11-30 19:57:24', 0, 'rinahasman06@gmail.com', '2024-11-30 19:56:48', 'Dewasa'),
(10205, 'kss.ujanmas.01', 'BILQIS DIANI AL ZAERA', '088808222071', 11, '$2y$10$orTQ7hKnktrtZoIGdzv45OqrZ9fAuKqQLk3vzCyVKxfDGx0nK/q0W', 'konselor', 0, NULL, 0, NULL, '2024-11-30 20:17:56', 0, 'bilqis@gmail.com', '2024-11-30 20:13:34', 'Sebaya'),
(10206, 'kss.ujanmas.02', 'Rahma batubara', '0883898560391', 11, '$2y$10$ew7cgIqKPvD.Y8htCLedFOMFzi/N74IcdCimHOUn5sYkPXxAMPiNm', 'konselor', 0, NULL, 0, NULL, '2024-11-30 20:17:49', 0, 'Rahma@gmail.com', '2024-11-30 20:16:22', 'Sebaya'),
(10207, 'kss.ujanmas.03', 'Yuti atiya', '0852185284742', 11, '$2y$10$POq3ailIQBY5EGrTDuvsfepL1usNLIYX0KSrXEs.5vNZPNN3kcGuq', 'konselor', 0, NULL, 0, NULL, '2024-11-30 20:17:45', 0, 'yutiatiya49@gmail.com', '2024-11-30 20:17:12', 'Sebaya'),
(10208, 'ps.sinderela1', 'Dwi Ratna Anggraini, S.Psi', '085809085375', 10, '$2y$10$eDtpdosSKD9EMwx3IjbqBeMRFPeIBbTGA7Sr5WrJF6b1pn7O9Ia4C', 'psikolog', 0, NULL, 0, NULL, '2024-11-30 20:21:10', 0, 'layananpuspaga2025@gmail.com', '2024-11-30 20:19:53', 'Psikolog'),
(10209, 'ps.sinderela2', 'Muhammad Tommy Caesar ', '081273081576', 10, '$2y$10$INo9EDiIjRMWbjpB.u7EEuVxl31wn22.Tu8Hfw5IGw7vyySRkPdYq', 'psikolog', 0, NULL, 0, NULL, '2024-11-30 20:21:06', 0, 'Tommycaesar77@gmail.com', '2024-11-30 20:20:32', 'Psikolog'),
(10210, 'ksd.lawangkidul.01', 'Ria Puspita Sari', '085267846378', 9, '$2y$10$8FkBltjgsIz1LDeTvEuVD.bbnQUHsRf0NqoRW.G6jJBKZGSSNukQ.', 'konselor', 0, NULL, 0, NULL, '2024-11-30 20:35:59', 0, 'ria.puspita43@gmail.com', '2024-11-30 20:35:31', 'Dewasa');

--
-- Trigger `users`
--
DELIMITER $$
CREATE TRIGGER `set_sub_role_before_insert` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
    IF NEW.role = 'klien' THEN
        SET NEW.sub_role = 'Klien';
    ELSEIF NEW.role = 'konselor' THEN
        -- Default sub_role kosong, admin akan menentukan ('Sebaya' atau 'Dewasa')
        SET NEW.sub_role = NULL;
    ELSEIF NEW.role = 'psikolog' THEN
        SET NEW.sub_role = 'Psikolog';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `wilayah`
--

CREATE TABLE `wilayah` (
  `id` int(11) NOT NULL,
  `nama_wilayah` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `wilayah`
--

INSERT INTO `wilayah` (`id`, `nama_wilayah`) VALUES
(1, 'Semende Darat Laut'),
(2, 'Semende Darat Ulu'),
(3, 'Semende Darat Tengah'),
(4, 'Tanjung Agung'),
(5, 'Panang Enim'),
(6, 'Rambang'),
(7, 'Lubai'),
(8, 'Lubai Ulu'),
(9, 'Lawang Kidul'),
(10, 'Muara Enim'),
(11, 'Ujan Mas'),
(12, 'Gunung Megang'),
(13, 'Benakat'),
(14, 'Belimbing'),
(15, 'Rambang Niru'),
(16, 'Empat Petulai Dangku'),
(17, 'Gelumbang'),
(18, 'Lembak'),
(19, 'Sungai Rotan'),
(20, 'Muara Belida'),
(21, 'Kelekar'),
(22, 'Belida Darat');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `antrian`
--
ALTER TABLE `antrian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_antrian_userid_uniq` (`user_id`);

--
-- Indeks untuk tabel `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_id` (`id`),
  ADD KEY `idx_tanggal_publikasi` (`tanggal_publikasi`);

--
-- Indeks untuk tabel `bookmark`
--
ALTER TABLE `bookmark`
  ADD PRIMARY KEY (`id`),
  ADD KEY `berita_id` (`berita_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `berita_id` (`berita_id`);

--
-- Indeks untuk tabel `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `fk_chat_messages_session` (`session_id`);

--
-- Indeks untuk tabel `chat_sessions`
--
ALTER TABLE `chat_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `klien_id` (`klien_id`),
  ADD KEY `konselor_id` (`konselor_id`);

--
-- Indeks untuk tabel `footer`
--
ALTER TABLE `footer`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `galeri`
--
ALTER TABLE `galeri`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `halaman_statis`
--
ALTER TABLE `halaman_statis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `komentar`
--
ALTER TABLE `komentar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `berita_id` (`berita_id`);

--
-- Indeks untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `messages_feedback`
--
ALTER TABLE `messages_feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `offline_sessions`
--
ALTER TABLE `offline_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `klien_id` (`klien_id`),
  ADD KEY `wilayah_id` (`wilayah_id`);

--
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `poll`
--
ALTER TABLE `poll`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `polling`
--
ALTER TABLE `polling`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `polls`
--
ALTER TABLE `polls`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `poll_votes`
--
ALTER TABLE `poll_votes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_poll_id` (`poll_id`),
  ADD KEY `FK_user_id` (`user_id`);

--
-- Indeks untuk tabel `program_ppkb`
--
ALTER TABLE `program_ppkb`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`id`),
  ADD KEY `berita_id` (`berita_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `berita_id` (`berita_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `rating_konselor`
--
ALTER TABLE `rating_konselor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `konselor_id` (`konselor_id`),
  ADD KEY `klien_id` (`klien_id`);

--
-- Indeks untuk tabel `rating_sinderela`
--
ALTER TABLE `rating_sinderela`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `rated_by_id` (`rated_by_id`);

--
-- Indeks untuk tabel `renja`
--
ALTER TABLE `renja`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`id`),
  ADD KEY `komentar_id` (`komentar_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `sesi_konseling`
--
ALTER TABLE `sesi_konseling`
  ADD PRIMARY KEY (`id`),
  ADD KEY `konselor_id` (`konselor_id`),
  ADD KEY `klien_id` (`klien_id`);

--
-- Indeks untuk tabel `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `statistik`
--
ALTER TABLE `statistik`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `id_wilayah` (`id_wilayah`);

--
-- Indeks untuk tabel `wilayah`
--
ALTER TABLE `wilayah`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `antrian`
--
ALTER TABLE `antrian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `berita`
--
ALTER TABLE `berita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `bookmark`
--
ALTER TABLE `bookmark`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1084;

--
-- AUTO_INCREMENT untuk tabel `chat_sessions`
--
ALTER TABLE `chat_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=207;

--
-- AUTO_INCREMENT untuk tabel `footer`
--
ALTER TABLE `footer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `galeri`
--
ALTER TABLE `galeri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `halaman_statis`
--
ALTER TABLE `halaman_statis`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `komentar`
--
ALTER TABLE `komentar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `messages_feedback`
--
ALTER TABLE `messages_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `offline_sessions`
--
ALTER TABLE `offline_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT untuk tabel `poll`
--
ALTER TABLE `poll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `polling`
--
ALTER TABLE `polling`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `polls`
--
ALTER TABLE `polls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `poll_votes`
--
ALTER TABLE `poll_votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `program_ppkb`
--
ALTER TABLE `program_ppkb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `rating`
--
ALTER TABLE `rating`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `rating_konselor`
--
ALTER TABLE `rating_konselor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT untuk tabel `rating_sinderela`
--
ALTER TABLE `rating_sinderela`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT untuk tabel `renja`
--
ALTER TABLE `renja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `report`
--
ALTER TABLE `report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `sesi_konseling`
--
ALTER TABLE `sesi_konseling`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `statistik`
--
ALTER TABLE `statistik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=268;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10211;

--
-- AUTO_INCREMENT untuk tabel `wilayah`
--
ALTER TABLE `wilayah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `antrian`
--
ALTER TABLE `antrian`
  ADD CONSTRAINT `antrian_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_antrian_user_id_unique` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_antrian_userid_uniq` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `bookmark`
--
ALTER TABLE `bookmark`
  ADD CONSTRAINT `bookmark_ibfk_1` FOREIGN KEY (`berita_id`) REFERENCES `berita` (`id`),
  ADD CONSTRAINT `bookmark_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `pengguna` (`id`);

--
-- Ketidakleluasaan untuk tabel `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD CONSTRAINT `bookmarks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookmarks_ibfk_2` FOREIGN KEY (`berita_id`) REFERENCES `berita` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `chat_sessions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_chat_messages_session` FOREIGN KEY (`session_id`) REFERENCES `chat_sessions` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `chat_sessions`
--
ALTER TABLE `chat_sessions`
  ADD CONSTRAINT `chat_sessions_ibfk_1` FOREIGN KEY (`klien_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_sessions_ibfk_2` FOREIGN KEY (`konselor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `komentar`
--
ALTER TABLE `komentar`
  ADD CONSTRAINT `komentar_ibfk_1` FOREIGN KEY (`berita_id`) REFERENCES `berita` (`id`);

--
-- Ketidakleluasaan untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `offline_sessions`
--
ALTER TABLE `offline_sessions`
  ADD CONSTRAINT `offline_sessions_ibfk_1` FOREIGN KEY (`klien_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `offline_sessions_ibfk_2` FOREIGN KEY (`wilayah_id`) REFERENCES `wilayah` (`id`);

--
-- Ketidakleluasaan untuk tabel `poll_votes`
--
ALTER TABLE `poll_votes`
  ADD CONSTRAINT `FK_poll_id` FOREIGN KEY (`poll_id`) REFERENCES `polls` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `poll_votes_ibfk_1` FOREIGN KEY (`poll_id`) REFERENCES `polls` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `poll_votes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rating`
--
ALTER TABLE `rating`
  ADD CONSTRAINT `rating_ibfk_1` FOREIGN KEY (`berita_id`) REFERENCES `berita` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rating_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`berita_id`) REFERENCES `berita` (`id`),
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `rating_konselor`
--
ALTER TABLE `rating_konselor`
  ADD CONSTRAINT `fk_klien_id` FOREIGN KEY (`klien_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rating_konselor_ibfk_1` FOREIGN KEY (`konselor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rating_konselor_ibfk_2` FOREIGN KEY (`klien_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rating_sinderela`
--
ALTER TABLE `rating_sinderela`
  ADD CONSTRAINT `rating_sinderela_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rating_sinderela_ibfk_2` FOREIGN KEY (`rated_by_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`komentar_id`) REFERENCES `komentar` (`id`),
  ADD CONSTRAINT `report_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `pengguna` (`id`);

--
-- Ketidakleluasaan untuk tabel `sesi_konseling`
--
ALTER TABLE `sesi_konseling`
  ADD CONSTRAINT `sesi_konseling_ibfk_1` FOREIGN KEY (`konselor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sesi_konseling_ibfk_2` FOREIGN KEY (`klien_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_id_wilayah` FOREIGN KEY (`id_wilayah`) REFERENCES `wilayah` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
