-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 05 Feb 2025 pada 16.31
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
-- Database: `dbbukutamu`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `ttamu`
--

CREATE TABLE `ttamu` (
  `id` int(3) NOT NULL,
  `tanggal` date NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `tujuan` varchar(100) NOT NULL,
  `nope` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ttamu`
--

INSERT INTO `ttamu` (`id`, `tanggal`, `nama`, `alamat`, `tujuan`, `nope`) VALUES
(30, '2024-12-18', 'putri', 'Makassar', 'Bimbingan Skripsi', '097647877'),
(33, '2025-01-08', 'Aziz ahmad', 'Bandung', 'Konsultasi', '0864532647'),
(34, '2025-01-08', 'Aisyah', 'Makassar', 'Bimbingan', '08544453454'),
(35, '2025-01-08', 'Rahul', 'Maros', 'Ketemu ketua', '08765434345674'),
(36, '2025-01-08', 'c:/Windows/system.ini', 'Maros', 'Ketemu ketua', '08765434345674'),
(37, '2025-01-08', '../../../../../../../../../../../../../../../../Windows/system.ini', 'Maros', 'Ketemu ketua', '08765434345674'),
(38, '2025-01-08', 'Rahul', 'c:/Windows/system.ini', 'Ketemu ketua', '08765434345674'),
(39, '2025-01-08', 'Rahul', 'Maros', 'c:/Windows/system.ini', '08765434345674'),
(40, '2025-01-08', 'Rahul', 'Maros', 'Ketemu ketua', 'c:/Windows/system.in'),
(41, '2025-01-08', 'Radit', 'enrekang', 'Bimbingan Skripsi', '083773646246'),
(42, '2025-02-05', 'Aziz ahmad', 'Maros', 'Bimbingan Aplikasi', '083773646246');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tuser`
--

CREATE TABLE `tuser` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(128) NOT NULL,
  `nama_pengguna` varchar(50) NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tuser`
--

INSERT INTO `tuser` (`id_user`, `username`, `password`, `nama_pengguna`, `status`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'Administrator', 'Aktif');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `ttamu`
--
ALTER TABLE `ttamu`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tuser`
--
ALTER TABLE `tuser`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `ttamu`
--
ALTER TABLE `ttamu`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT untuk tabel `tuser`
--
ALTER TABLE `tuser`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
