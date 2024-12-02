-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Nov 2024 pada 01.26
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
-- Database: `website_chat_sinderela`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `username`, `password_hash`) VALUES
(1, 'admin', '$2y$10$l.dhu3hzMYpQBF0d/WKrtuw9UECG7u1ctCpwSVN45d3IN46iIvZua');

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
(1, 'SINDERELA RILIS', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2024-11-10 19:02:26', 'uploads/hk.png', '#NEWS', 21, 0, ''),
(2, 'ORANG TUA HEBAT', 'Lorem ipsum ', '2024-11-10 23:57:50', 'uploads/file.png', '10', 11, 0, '');

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
  `id_wilayah` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `session_id`, `sender_id`, `message`, `sent_at`, `id_wilayah`) VALUES
(531, 134, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-09 23:32:35', NULL),
(532, 134, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-09 23:32:41', NULL),
(533, 134, 9999, 'Konselor telah masuk ke room chat. Silakan mulai percakapan.', '2024-11-09 23:32:41', NULL),
(534, 134, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 00:02:14', NULL),
(535, 134, 9999, 'Konselor telah masuk ke room chat. Silakan mulai percakapan.', '2024-11-10 00:02:14', NULL),
(536, 136, 5, 'hai', '2024-11-10 00:18:57', NULL),
(537, 136, 1, 'iya pak', '2024-11-10 00:19:01', NULL),
(538, 136, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 00:25:35', NULL),
(539, 136, 8, 'selamat siang', '2024-11-10 00:25:38', NULL),
(540, 136, 1, 'siang pak', '2024-11-10 00:25:43', NULL),
(541, 136, 1, 'boleh izin untuk bertanya', '2024-11-10 00:25:49', NULL),
(542, 136, 1, 'boleh nak', '2024-11-10 00:25:52', NULL),
(543, 136, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 00:32:45', NULL),
(544, 136, 5, 'gai', '2024-11-10 00:35:08', NULL),
(545, 137, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 01:29:14', NULL),
(546, 137, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 01:29:17', NULL),
(547, 137, 9999, 'Konselor telah masuk ke room chat. Silakan mulai percakapan.', '2024-11-10 01:29:17', NULL),
(548, 137, 5, 'selamat datang', '2024-11-10 01:29:17', NULL),
(549, 137, 1, 'pagi pak', '2024-11-10 01:29:30', NULL),
(550, 137, 5, 'ada yang bisa saya banting', '2024-11-10 01:29:46', NULL),
(551, 137, 1, 'banting aja pak', '2024-11-10 01:29:52', NULL),
(552, 137, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 01:30:29', NULL),
(553, 137, 8, 'selamat sore nak', '2024-11-10 01:30:33', NULL),
(554, 137, 8, 'pagi nak', '2024-11-10 01:30:41', NULL),
(555, 137, 8, 'woi nak', '2024-11-10 01:30:43', NULL),
(556, 137, 8, 'kampret', '2024-11-10 01:30:45', NULL),
(557, 137, 1, 'iya pak', '2024-11-10 01:30:51', NULL),
(558, 138, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 01:31:45', NULL),
(559, 138, 5, 'pagi pak', '2024-11-10 01:31:58', NULL),
(560, 138, 9, 'pagi', '2024-11-10 01:32:03', NULL),
(561, 140, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 01:32:32', NULL),
(562, 141, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 01:40:49', NULL),
(563, 141, 10, 'cek', '2024-11-10 01:40:56', NULL),
(564, 141, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 01:40:57', NULL),
(565, 141, 9999, 'Konselor telah masuk ke room chat. Silakan mulai percakapan.', '2024-11-10 01:40:57', NULL),
(566, 141, 9, 'cek', '2024-11-10 01:40:58', NULL),
(567, 141, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 01:52:35', NULL),
(568, 142, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 03:46:28', NULL),
(569, 142, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 03:46:35', NULL),
(570, 142, 9999, 'Konselor telah masuk ke room chat. Silakan mulai percakapan.', '2024-11-10 03:46:35', NULL),
(571, 142, 1, 'selamat siang', '2024-11-10 03:46:46', NULL),
(572, 142, 5, 'siang', '2024-11-10 03:47:05', NULL),
(573, 137, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 20:29:38', NULL),
(574, 144, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 20:31:27', NULL),
(575, 144, 9999, 'Konselor telah masuk ke room chat. Silakan mulai percakapan.', '2024-11-10 20:31:27', NULL),
(576, 144, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 20:33:48', NULL),
(577, 144, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 20:34:06', NULL),
(578, 144, 9999, 'Konselor telah masuk ke room chat. Silakan mulai percakapan.', '2024-11-10 20:34:06', NULL),
(579, 144, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 20:39:07', NULL),
(580, 144, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 20:39:16', NULL),
(581, 144, 9999, 'Konselor telah masuk ke room chat. Silakan mulai percakapan.', '2024-11-10 20:39:16', NULL),
(582, 144, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 20:39:28', NULL),
(583, 144, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 20:40:46', NULL),
(584, 144, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 20:42:01', NULL),
(585, 145, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 20:48:08', NULL),
(586, 145, 1, 'pagi kak', '2024-11-10 20:48:17', NULL),
(587, 145, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 20:48:41', NULL),
(588, 145, 9999, 'Konselor telah masuk ke room chat. Silakan mulai percakapan.', '2024-11-10 20:48:41', NULL),
(589, 145, 5, 'pagi kak', '2024-11-10 20:48:41', NULL),
(590, 145, 5, 'saya ingin konsultasi', '2024-11-10 20:48:56', NULL),
(591, 145, 5, 'kamu ingin konsultasi terkait apa', '2024-11-10 20:49:12', NULL),
(592, 145, 1, 'mau psikolog kak', '2024-11-10 20:49:22', NULL),
(593, 145, 1, 'ok', '2024-11-10 20:49:27', NULL),
(594, 145, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 20:50:48', NULL),
(595, 145, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 20:52:27', NULL),
(596, 145, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 20:58:48', NULL),
(597, 137, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 21:05:36', NULL),
(598, 137, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 21:08:36', NULL),
(599, 137, 9999, 'Konselor telah masuk ke room chat. Silakan mulai percakapan.', '2024-11-10 21:08:36', NULL),
(600, 137, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 21:09:36', NULL),
(601, 137, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 21:16:36', NULL),
(602, 137, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 21:22:36', NULL),
(603, 147, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 21:26:06', NULL),
(604, 137, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 21:26:36', NULL),
(605, 137, 9999, 'Konselor telah masuk ke room chat. Silakan mulai percakapan.', '2024-11-10 21:26:36', NULL),
(606, 147, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 21:26:47', NULL),
(607, 147, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 21:27:00', NULL),
(608, 147, 9999, 'Konselor telah masuk ke room chat. Silakan mulai percakapan.', '2024-11-10 21:27:00', NULL),
(609, 147, 9999, 'Klien telah masuk ke room chat.', '2024-11-10 21:28:08', NULL),
(610, 147, 5, 'hai', '2024-11-10 21:28:29', NULL),
(611, 147, 1, 'iya pak', '2024-11-10 21:28:40', NULL),
(612, 137, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 21:32:36', NULL),
(613, 137, 9999, 'Klien telah masuk ke room chat.', '2024-11-10 21:32:36', NULL),
(614, 147, 5, 'good', '2024-11-10 21:40:30', NULL),
(615, 147, 1, 'wak', '2024-11-10 21:42:28', NULL),
(616, 147, 9999, 'Selamat datang di layanan konseling SINDERELA. Kami hadir untuk membantu Anda.', '2024-11-10 21:52:26', NULL),
(617, 147, 9999, 'Klien telah masuk ke room chat.', '2024-11-10 21:52:26', NULL);

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

--
-- Dumping data untuk tabel `chat_sessions`
--

INSERT INTO `chat_sessions` (`id`, `klien_id`, `konselor_id`, `id_wilayah`, `status`, `created_at`, `ended_at`, `psikolog_id`, `refer`, `notifikasi`) VALUES
(134, 1, 5, 10, 'selesai', '2024-11-09 23:32:32', NULL, NULL, 0, 0),
(135, 1, 5, 10, 'selesai', '2024-11-10 00:02:52', NULL, NULL, 0, 0),
(136, 1, 5, 10, 'selesai', '2024-11-10 00:18:49', NULL, 8, 1, 0),
(137, 1, 5, 10, 'selesai', '2024-11-10 01:29:10', NULL, 8, 1, 0),
(138, 9, 5, 10, 'selesai', '2024-11-10 01:31:42', NULL, NULL, 0, 0),
(140, 10000, 5, 10, 'selesai', '2024-11-10 01:32:29', NULL, NULL, 0, 0),
(141, 9, 10, 4, 'selesai', '2024-11-10 01:40:46', NULL, NULL, 0, 0),
(142, 1, 5, 10, 'selesai', '2024-11-10 02:15:55', NULL, NULL, 0, 0),
(144, 10006, 5, 10, 'selesai', '2024-11-10 20:30:05', NULL, NULL, 0, 0),
(145, 1, 5, 10, 'selesai', '2024-11-10 20:48:04', NULL, 8, 1, 0),
(146, 9, 5, 10, 'selesai', '2024-11-10 20:52:31', NULL, NULL, 0, 0),
(147, 1, 5, 10, 'selesai', '2024-11-10 21:17:56', NULL, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `galeri`
--

CREATE TABLE `galeri` (
  `id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `jenis` varchar(255) NOT NULL
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
(12, 'ADVIN');

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
(5, 0, NULL, NULL, 'belum_dibaca', '2024-11-10 23:57:50', 'Berita baru ditambahkan: ORANG TUA HEBAT');

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
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
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
-- Struktur dari tabel `rating`
--

CREATE TABLE `rating` (
  `id` int(11) NOT NULL,
  `berita_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `rating`
--

INSERT INTO `rating` (`id`, `berita_id`, `user_id`, `rating`, `created_at`) VALUES
(1, 1, 1, 5, '2024-11-10 19:05:14'),
(2, 1, 1, 5, '2024-11-10 19:05:18');

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

--
-- Dumping data untuk tabel `rating_konselor`
--

INSERT INTO `rating_konselor` (`id`, `konselor_id`, `klien_id`, `rating`, `feedback`, `timestamp`) VALUES
(49, 5, 9, 0, '5', '2024-11-10 01:54:13'),
(50, 5, 9, 0, '5', '2024-11-10 01:55:37'),
(51, 5, 9, 0, 'waa', '2024-11-10 01:55:41'),
(52, 5, 9, 0, '5', '2024-11-10 01:58:23'),
(53, 5, 9, 0, '5', '2024-11-10 01:58:41'),
(54, 5, 9, 5, 'wad', '2024-11-10 02:05:43'),
(55, 5, 1, 5, '22', '2024-11-10 20:33:58');

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

--
-- Dumping data untuk tabel `rating_sinderela`
--

INSERT INTO `rating_sinderela` (`id`, `user_id`, `rated_by_id`, `rating`, `feedback`, `timestamp`, `konselor_id`, `klien_id`) VALUES
(31, 1, 1, 5, 'wada', '2024-11-10 01:25:55', 0, NULL),
(32, 1, 1, 5, 'wada', '2024-11-10 01:25:55', 0, NULL),
(33, 1, 1, 5, 'gg', '2024-11-10 01:26:10', 0, NULL),
(34, 1, 1, 5, 'gg', '2024-11-10 01:26:10', 0, NULL),
(35, 1, 1, 5, '0828', '2024-11-10 01:28:18', 0, NULL),
(36, 9, 9, 5, 'gffa', '2024-11-10 01:45:30', 0, NULL),
(37, 9, 9, 5, 'gffa', '2024-11-10 01:50:09', 0, NULL),
(38, 9, 9, 5, '5', '2024-11-10 01:55:55', 0, NULL),
(39, 9, 9, 5, '5', '2024-11-10 01:58:19', 0, NULL),
(40, 9, 9, 5, '1', '2024-11-10 01:58:34', 0, NULL),
(41, 9, 9, 5, 'wda', '2024-11-10 02:06:03', 0, NULL),
(42, 9, 9, 5, 'wda', '2024-11-10 02:06:21', 0, NULL),
(43, 1, 1, 5, '22', '2024-11-10 20:34:01', 0, NULL),
(44, 1, 1, 5, 'mantap', '2024-11-10 21:26:31', 0, NULL),
(45, 1, 1, 5, 'mantap', '2024-11-10 21:26:48', 0, NULL);

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
(163, 'index.php', 73, '2024-11-11'),
(164, 'index.php', 73, '2024-11-11'),
(165, 'index.php', 73, '2024-11-11'),
(166, 'index.php', 73, '2024-11-11'),
(167, 'index.php', 73, '2024-11-11'),
(168, 'index.php', 73, '2024-11-11'),
(169, 'index.php', 73, '2024-11-11'),
(170, 'index.php', 73, '2024-11-11'),
(171, 'index.php', 73, '2024-11-11'),
(172, 'index.php', 73, '2024-11-11'),
(173, 'index.php', 73, '2024-11-11'),
(174, 'index.php', 73, '2024-11-11'),
(175, 'index.php', 73, '2024-11-11'),
(176, 'index.php', 73, '2024-11-11'),
(177, 'index.php', 73, '2024-11-11'),
(178, 'index.php', 73, '2024-11-11'),
(179, 'index.php', 73, '2024-11-11'),
(180, 'index.php', 73, '2024-11-11'),
(181, 'index.php', 73, '2024-11-11'),
(182, 'index.php', 73, '2024-11-11'),
(183, 'index.php', 73, '2024-11-11'),
(184, 'index.php', 73, '2024-11-11'),
(185, 'index.php', 73, '2024-11-11'),
(186, 'index.php', 73, '2024-11-11'),
(187, 'index.php', 73, '2024-11-11'),
(188, 'index.php', 73, '2024-11-11'),
(189, 'index.php', 73, '2024-11-11'),
(190, 'index.php', 73, '2024-11-11'),
(191, 'index.php', 73, '2024-11-11'),
(192, 'index.php', 73, '2024-11-11'),
(193, 'index.php', 73, '2024-11-11'),
(194, 'index.php', 73, '2024-11-11'),
(195, 'index.php', 73, '2024-11-11'),
(196, 'index.php', 73, '2024-11-11'),
(197, 'index.php', 73, '2024-11-11'),
(198, 'index.php', 73, '2024-11-11'),
(199, 'index.php', 73, '2024-11-11'),
(200, 'index.php', 73, '2024-11-11'),
(201, 'index.php', 73, '2024-11-11'),
(202, 'index.php', 73, '2024-11-11'),
(203, 'index.php', 73, '2024-11-11'),
(204, 'index.php', 73, '2024-11-11'),
(205, 'index.php', 73, '2024-11-11'),
(206, 'index.php', 73, '2024-11-11'),
(207, 'index.php', 73, '2024-11-11'),
(208, 'index.php', 73, '2024-11-11'),
(209, 'index.php', 73, '2024-11-11'),
(210, 'index.php', 73, '2024-11-11'),
(211, 'index.php', 73, '2024-11-11'),
(212, 'index.php', 73, '2024-11-11'),
(213, 'index.php', 73, '2024-11-11'),
(214, 'index.php', 73, '2024-11-11'),
(215, 'index.php', 73, '2024-11-11'),
(216, 'index.php', 73, '2024-11-11'),
(217, 'index.php', 73, '2024-11-11'),
(218, 'index.php', 73, '2024-11-11'),
(219, 'index.php', 73, '2024-11-11'),
(220, 'index.php', 73, '2024-11-11'),
(221, 'index.php', 73, '2024-11-11'),
(222, 'index.php', 73, '2024-11-11'),
(223, 'index.php', 73, '2024-11-11'),
(224, 'index.php', 73, '2024-11-11'),
(225, 'index.php', 73, '2024-11-11'),
(226, 'index.php', 73, '2024-11-11'),
(227, 'index.php', 73, '2024-11-11'),
(228, 'index.php', 73, '2024-11-11'),
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
(239, 'detail_berita.php', 11, '2024-11-11'),
(240, 'detail_berita.php', 11, '2024-11-11'),
(241, 'detail_berita.php', 11, '2024-11-11'),
(242, 'detail_berita.php', 11, '2024-11-11');

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
  `is_online` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `nama`, `nomor_hp`, `id_wilayah`, `password_hash`, `role`, `session`, `ratting`, `is_verified`, `reset_token`, `token_expiry`, `is_online`) VALUES
(1, 'klien', 'namaklien', '082278052962', 10, '$2y$10$Iur1Hwy5/h2TTSoz7QoTMemenXhNjTsaM5gQj5q5UU8I8NAk3.jgC', 'klien', 0, NULL, 0, NULL, '2024-11-10 23:53:10', 0),
(5, 'konselor', 'namakonselor', '0822780529621', 10, '$2y$10$SEUnBETLW8FmsoFCyfu7CeJvUaNxCBcjtI5D0Hf.DpU./M7Kur1qG', 'konselor', 0, NULL, 0, NULL, '2024-11-10 23:53:03', 0),
(8, 'psikolog', 'namapsikolog', '082278052962123', 10, '$2y$10$.M.IIZJxzdoyMJJvwmdEb.5K.h7zEovf3E0M98rJ/CVtKqowBheVa', 'psikolog', 0, NULL, 0, NULL, '2024-11-10 23:52:56', 0),
(9, 'klien2', 'klien ke dua', '082278052962', 4, '$2y$10$9C7KFBhKegKijNNEzzBwzOol3mJktICC5fhTojXFQGQ/r9Rf6eCDm', 'klien', 0, NULL, 0, NULL, '2024-11-10 20:52:50', 0),
(10, 'konselor2', 'konselor ke dua', '0822780529622', 4, '$2y$10$0hl2q8k6Hx9VRzVWFgqtTul7tklxufSVQFxE8MykA4RXUBhUVpGUq', 'konselor', 0, NULL, 0, NULL, '2024-11-10 02:06:14', 0),
(9999, 'Sistem', '', NULL, NULL, '', '', 0, NULL, 0, NULL, '2024-11-09 16:19:15', 0),
(10000, 'dayat', 'dwi wahyu hidayat', '0812345678', 10, '$2y$10$QkzGAaTyWhEgCnlYzGLSxeu/meVpuapy.NvVAHeF5W53GK7zwSPg6', 'klien', 0, NULL, 0, NULL, '2024-11-10 01:40:28', 0),
(10001, 'sari', 'Sari', '0826727515999', 4, '$2y$10$CHR.5yQ.gcKgcpGIm2oJ4eKdPiVv5Xash1IBh6io0nVbluBlUjZhK', 'klien', 0, NULL, 0, NULL, '2024-11-09 21:12:40', 0),
(10002, 'edo', 'Edojet', '0824778147218', 4, '$2y$10$MI4ETtQwkUGefE/QEEzTs.8jOMNDn0Buj6aiDERIBjriN73AdsYN.', 'klien', 0, NULL, 0, NULL, '2024-11-09 18:11:37', 0),
(10003, 'etik', 'Etik Nurjanah', '082271984287451', 4, '$2y$10$8E/RHC1WeCrLJ4tCBAd6QOE/KU0LXMABC.MzMAliLbp0NJcNNYzTa', 'klien', 0, NULL, 0, NULL, '2024-11-09 18:11:42', 0),
(10004, 'septi', 'septi', '0214919481', 4, '$2y$10$iUsk4YdCqK30IVD9J9xNY.MhcJxrdIjTKVidQvlL6imBlVjq6D7gK', 'klien', 0, NULL, 0, NULL, '2024-11-09 18:34:23', 0),
(10005, 'ridho', 'ridho', '082278529949219', 4, '$2y$10$smjA9tSHd5YwLdIG3VOu3.AtgarC6cgfmkjgbmt513kB2/CpRGIDm', 'klien', 0, NULL, 0, NULL, '2024-11-09 22:39:48', 0),
(10006, 'deftri', 'deftri prihandaru', '083802662321', 10, '$2y$10$gKsj6skbQCraGFEaoBcixe2rsZaj/QGWIKNXhS3DBowCWHVOuFsrK', 'klien', 0, NULL, 0, NULL, '2024-11-10 20:30:54', 0);

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
-- Indeks untuk tabel `galeri`
--
ALTER TABLE `galeri`
  ADD PRIMARY KEY (`id`);

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
-- Indeks untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `antrian`
--
ALTER TABLE `antrian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `berita`
--
ALTER TABLE `berita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=618;

--
-- AUTO_INCREMENT untuk tabel `chat_sessions`
--
ALTER TABLE `chat_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT untuk tabel `galeri`
--
ALTER TABLE `galeri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `komentar`
--
ALTER TABLE `komentar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `polls`
--
ALTER TABLE `polls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `poll_votes`
--
ALTER TABLE `poll_votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT untuk tabel `rating_sinderela`
--
ALTER TABLE `rating_sinderela`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

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
-- AUTO_INCREMENT untuk tabel `statistik`
--
ALTER TABLE `statistik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=243;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10007;

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
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_wilayah`) REFERENCES `wilayah` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
