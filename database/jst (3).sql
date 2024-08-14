-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 14 Agu 2024 pada 06.48
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
-- Database: `jst`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id` int(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mahasiswa`
--

INSERT INTO `mahasiswa` (`id`, `tahun`, `jumlah`, `modified_at`) VALUES
(1, 2019, 270, '2024-08-13 10:30:05'),
(2, 2020, 185, '2024-08-13 10:31:19'),
(3, 2021, 152, '2024-08-13 10:31:29'),
(4, 2022, 189, '2024-08-13 10:31:40'),
(5, 2023, 163, '2024-08-13 10:31:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `parameter_model`
--

CREATE TABLE `parameter_model` (
  `id` int(11) NOT NULL,
  `inputSize` int(11) DEFAULT NULL,
  `hiddenLayerSize` int(11) DEFAULT NULL,
  `outputSize` int(11) DEFAULT NULL,
  `learningRate` float DEFAULT NULL,
  `epochs` int(11) DEFAULT NULL,
  `iterasiError` int(11) DEFAULT NULL,
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `prediksi_laporan`
--

CREATE TABLE `prediksi_laporan` (
  `id` int(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `actual_value` int(11) NOT NULL,
  `data_historis` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`data_historis`)),
  `prediksi` decimal(10,4) NOT NULL,
  `error_loss_epoch_terakhir` decimal(10,4) NOT NULL,
  `error_absolut` decimal(10,4) NOT NULL,
  `error_kuadrat` decimal(10,4) NOT NULL,
  `mae` decimal(10,4) NOT NULL,
  `mse` decimal(10,4) NOT NULL,
  `rmse` decimal(10,4) NOT NULL,
  `mape` decimal(10,2) NOT NULL,
  `accuracy` decimal(10,2) NOT NULL,
  `W1` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`W1`)),
  `b1` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`b1`)),
  `W2` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`W2`)),
  `b2` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`b2`)),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'Eka Nissa', 'admin', '$2y$10$PmqAvZpahxXvdmxkj6mTw.6rC2acIG8PrG5E1JBQWWtqchH8jWpN.', 'admin', '2024-08-13 10:17:58'),
(2, 'User', 'user', '$2y$10$VYT5G0QovrPR8as.t.p4veP8i0MmZFBPd5OsDvpp9TQc0vuYgrjHy', 'user', '2024-08-13 10:34:47'),
(3, 'Afni', 'afni', '$2y$10$SPTAhXxqSETEQNIEKnQTTuuWNk1Bo6ui22De8LzOAxq5MCFD6v1ZS', 'admin', '2024-08-13 10:36:43'),
(4, 'Angga', 'angga', '$2y$10$452MbDBRu4TYDRyv9vkpJOX.F81knbmoWbGs19NjiUQO97oFur6.2', 'user', '2024-08-13 10:37:01');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `parameter_model`
--
ALTER TABLE `parameter_model`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `prediksi_laporan`
--
ALTER TABLE `prediksi_laporan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `prediksi_laporan`
--
ALTER TABLE `prediksi_laporan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
