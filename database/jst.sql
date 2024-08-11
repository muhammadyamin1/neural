-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Agu 2024 pada 20.44
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
(35, 2019, 270, '2024-08-11 07:38:38'),
(36, 2020, 185, '2024-08-11 07:38:53'),
(37, 2021, 152, '2024-08-11 07:39:07'),
(38, 2022, 189, '2024-08-11 07:39:20'),
(39, 2023, 163, '2024-08-11 07:40:12');

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

--
-- Dumping data untuk tabel `parameter_model`
--

INSERT INTO `parameter_model` (`id`, `inputSize`, `hiddenLayerSize`, `outputSize`, `learningRate`, `epochs`, `iterasiError`, `modified_at`) VALUES
(1, 2, 2, 1, 0.1, 1000, 100, '2024-08-11 07:46:10');

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
  `accuracy` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `prediksi_laporan`
--

INSERT INTO `prediksi_laporan` (`id`, `tahun`, `actual_value`, `data_historis`, `prediksi`, `error_loss_epoch_terakhir`, `error_absolut`, `error_kuadrat`, `mae`, `mse`, `rmse`, `mape`, `accuracy`) VALUES
(24, 2023, 189, '[270,185,152,189]', 189.9150, 0.1156, 0.9150, 0.8371, 30.9575, 1969.0380, 44.3738, 14.44, 85.56);

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
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT untuk tabel `prediksi_laporan`
--
ALTER TABLE `prediksi_laporan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
