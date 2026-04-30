-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 30 Apr 2026 pada 17.07
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `akademik`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `absensi`
--

CREATE TABLE `absensi` (
  `id_absensi` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('hadir','sakit','izin','alpha','terlambat') NOT NULL DEFAULT 'hadir',
  `jam_masuk` time DEFAULT NULL,
  `jam_keluar` time DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `guru_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `semester` varchar(10) DEFAULT NULL,
  `tahun_ajaran` varchar(20) DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `absensi`
--

INSERT INTO `absensi` (`id_absensi`, `siswa_id`, `tanggal`, `status`, `jam_masuk`, `jam_keluar`, `keterangan`, `guru_id`, `created_at`, `updated_at`, `semester`, `tahun_ajaran`, `is_verified`) VALUES
(1, 33, '2026-04-11', 'sakit', '02:03:00', '00:02:00', NULL, 1, '2026-04-11 16:02:09', '2026-04-11 16:02:09', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `absensi_guru`
--

CREATE TABLE `absensi_guru` (
  `id_absensi_guru` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `tgl` date NOT NULL,
  `status` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `absensi_kelas`
--

CREATE TABLE `absensi_kelas` (
  `id_absensi_kelas` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `tgl` date NOT NULL,
  `status` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `absensi_kelas`
--

INSERT INTO `absensi_kelas` (`id_absensi_kelas`, `siswa_id`, `kelas_id`, `tgl`, `status`) VALUES
(3, 35, 2, '2026-04-29', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `guru`
--

CREATE TABLE `guru` (
  `id_guru` int(11) NOT NULL,
  `nama_guru` varchar(50) NOT NULL,
  `ttl_guru` date NOT NULL,
  `alamat_guru` text NOT NULL,
  `wa_guru` char(15) NOT NULL,
  `foto_guru` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `guru`
--

INSERT INTO `guru` (`id_guru`, `nama_guru`, `ttl_guru`, `alamat_guru`, `wa_guru`, `foto_guru`) VALUES
(1, 'I Gusti Bagus Astika, S.Kom', '2016-04-01', 'Padang Kerta', '081907283456', '202604101775831053.png'),
(2, 'I Komang Adi Suartama, S.Kom', '2017-04-02', 'Bebandem', '081245364527', '202604101775831020.png'),
(3, 'Putu Dody Setiawan, S.Pd', '2013-02-05', 'Seraya', '081908674566', '202604101775831000.png'),
(6, 'Ni Ketut Karin Asih, S.Pd', '2026-04-30', 'Manggis', '081902647345', '202604300001.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jurnal_guru`
--

CREATE TABLE `jurnal_guru` (
  `id_jurnal_guru` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `keterangan` text NOT NULL,
  `tgl` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jurnal_guru`
--

INSERT INTO `jurnal_guru` (`id_jurnal_guru`, `guru_id`, `kelas_id`, `keterangan`, `tgl`) VALUES
(1, 2, 2, 'basis data', '2026-04-30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jurnal_kelas`
--

CREATE TABLE `jurnal_kelas` (
  `id_jurnal_kelas` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `keterangan` text NOT NULL,
  `tgl` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jurnal_kelas`
--

INSERT INTO `jurnal_kelas` (`id_jurnal_kelas`, `kelas_id`, `keterangan`, `tgl`) VALUES
(1, 2, 'belajar basis data', '2026-04-29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jurusan`
--

CREATE TABLE `jurusan` (
  `id_jurusan` int(11) NOT NULL,
  `nama_jurusan` varchar(125) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jurusan`
--

INSERT INTO `jurusan` (`id_jurusan`, `nama_jurusan`) VALUES
(1, 'AKL'),
(2, 'AP'),
(3, 'PPLG'),
(4, 'DKV'),
(5, 'HK'),
(9, 'TJKT'),
(10, 'MM'),
(11, 'Tata Boga'),
(12, 'Front Office');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int(11) NOT NULL,
  `jurusan_id` int(10) NOT NULL,
  `nama_kelas` varchar(125) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `jurusan_id`, `nama_kelas`) VALUES
(1, 1, 'X'),
(2, 3, 'XI'),
(32, 9, 'XII'),
(33, 3, 'X');

-- --------------------------------------------------------

--
-- Struktur dari tabel `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL DEFAULT 1,
  `theme` varchar(10) DEFAULT 'light',
  `notifications` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `settings`
--

INSERT INTO `settings` (`id`, `theme`, `notifications`) VALUES
(1, 'light', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(11) NOT NULL,
  `nama_siswa` varchar(125) NOT NULL,
  `nis_siswa` varchar(8) NOT NULL,
  `nisn_siswa` varchar(10) NOT NULL,
  `ttl_siswa` date NOT NULL,
  `alamat_siswa` text NOT NULL,
  `wa_siswa` char(15) NOT NULL,
  `foto_siswa` text NOT NULL,
  `kelas_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nama_siswa`, `nis_siswa`, `nisn_siswa`, `ttl_siswa`, `alamat_siswa`, `wa_siswa`, `foto_siswa`, `kelas_id`) VALUES
(32, 'I Kadek Oka Sujana', '1675', '1', '2009-01-08', 'Asak', '081956378256', '202604110003.png', 2),
(33, 'I Gede Aditya Arimbawa', '1676', '2', '2008-10-17', 'Bungaya', '081907848147', '202604101.png', 2),
(34, 'I Gusti Agus Putra Angga', '1677', '3', '2008-07-21', 'Padang Kerta', '081465836573', '202604101.png', 2),
(35, 'I Komang Agus Wiranata Kusuma', '1678', '4', '2008-08-03', 'Samuh', '084637485674', '202604101.png', 2),
(36, 'I Wayan Aris Wira Putra', '1679', '5', '2008-10-09', 'Selat', '081345646378', '20260410999.png', 2),
(52, 'arimbawa', '3567', '6377338674', '2026-04-17', 'Asak', '081908674566', '202604110001.png', 2),
(53, 'aditya', '4560', '6377338840', '2026-04-11', 'satu dua tiga', '081908674566', '202604110002.png', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama`, `email`) VALUES
(1, 'admin', '202cb962ac59075b964b07152d234b70', 'aditya', 'admin@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id_absensi`),
  ADD UNIQUE KEY `uniq_absensi_siswa_tanggal` (`siswa_id`,`tanggal`),
  ADD KEY `fk_absensi_guru` (`guru_id`),
  ADD KEY `idx_absensi_tanggal` (`tanggal`),
  ADD KEY `idx_absensi_status` (`status`),
  ADD KEY `idx_absensi_siswa` (`siswa_id`);

--
-- Indeks untuk tabel `absensi_guru`
--
ALTER TABLE `absensi_guru`
  ADD PRIMARY KEY (`id_absensi_guru`),
  ADD KEY `guru_id` (`guru_id`),
  ADD KEY `siswa_id` (`siswa_id`,`kelas_id`),
  ADD KEY `kelas_id` (`kelas_id`);

--
-- Indeks untuk tabel `absensi_kelas`
--
ALTER TABLE `absensi_kelas`
  ADD PRIMARY KEY (`id_absensi_kelas`),
  ADD KEY `siswa_id` (`siswa_id`,`kelas_id`),
  ADD KEY `kelas_id` (`kelas_id`);

--
-- Indeks untuk tabel `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id_guru`);

--
-- Indeks untuk tabel `jurnal_guru`
--
ALTER TABLE `jurnal_guru`
  ADD PRIMARY KEY (`id_jurnal_guru`),
  ADD KEY `guru_id` (`guru_id`,`kelas_id`),
  ADD KEY `kelas_id` (`kelas_id`);

--
-- Indeks untuk tabel `jurnal_kelas`
--
ALTER TABLE `jurnal_kelas`
  ADD PRIMARY KEY (`id_jurnal_kelas`),
  ADD KEY `kelas_id` (`kelas_id`);

--
-- Indeks untuk tabel `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`id_jurusan`);

--
-- Indeks untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`),
  ADD KEY `jurusan_id` (`jurusan_id`) USING BTREE;

--
-- Indeks untuk tabel `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`),
  ADD UNIQUE KEY `nis_siswa` (`nis_siswa`),
  ADD UNIQUE KEY `nisn_siswa` (`nisn_siswa`),
  ADD KEY `kelas_id` (`kelas_id`) USING BTREE;

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id_absensi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `absensi_guru`
--
ALTER TABLE `absensi_guru`
  MODIFY `id_absensi_guru` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `absensi_kelas`
--
ALTER TABLE `absensi_kelas`
  MODIFY `id_absensi_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `guru`
--
ALTER TABLE `guru`
  MODIFY `id_guru` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `jurnal_guru`
--
ALTER TABLE `jurnal_guru`
  MODIFY `id_jurnal_guru` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `jurnal_kelas`
--
ALTER TABLE `jurnal_kelas`
  MODIFY `id_jurnal_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `id_jurusan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `absensi_guru`
--
ALTER TABLE `absensi_guru`
  ADD CONSTRAINT `absensi_guru_ibfk_1` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id_guru`),
  ADD CONSTRAINT `absensi_guru_ibfk_2` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id_kelas`),
  ADD CONSTRAINT `absensi_guru_ibfk_3` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id_siswa`);

--
-- Ketidakleluasaan untuk tabel `absensi_kelas`
--
ALTER TABLE `absensi_kelas`
  ADD CONSTRAINT `absensi_kelas_ibfk_1` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id_kelas`),
  ADD CONSTRAINT `absensi_kelas_ibfk_2` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id_siswa`);

--
-- Ketidakleluasaan untuk tabel `jurnal_guru`
--
ALTER TABLE `jurnal_guru`
  ADD CONSTRAINT `jurnal_guru_ibfk_1` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id_guru`),
  ADD CONSTRAINT `jurnal_guru_ibfk_2` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id_kelas`);

--
-- Ketidakleluasaan untuk tabel `jurnal_kelas`
--
ALTER TABLE `jurnal_kelas`
  ADD CONSTRAINT `jurnal_kelas_ibfk_1` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id_kelas`);

--
-- Ketidakleluasaan untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`jurusan_id`) REFERENCES `jurusan` (`id_jurusan`);

--
-- Ketidakleluasaan untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
