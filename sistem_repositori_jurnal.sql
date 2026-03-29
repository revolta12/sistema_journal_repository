-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 29 Mar 2026 pada 00.56
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
-- Database: `sistem_repositori_jurnal`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `naran_kompletu` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `naran_kompletu`, `created_at`) VALUES
(5, 'donatos', '$2y$10$VKZBot10Uy0DWjFMxFuO5u0NYPUss.Voqa3/11AjGjcYBBPSQAE0e', 'donatos Bubu admin sistema', '2026-03-28 16:06:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `journals`
--

CREATE TABLE `journals` (
  `id` int(11) NOT NULL,
  `topiku` varchar(255) NOT NULL,
  `deskripsaun` text DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `journals`
--

INSERT INTO `journals` (`id`, `topiku`, `deskripsaun`, `file_path`, `uploaded_by`, `created_at`) VALUES
(3, 'DEZEÑU NO KONSTRUSAUN SERVIDOR VOIP OPEN  SOURCE  UZA UBUNTU BA KOMUNIKASAUN INTERNAL  IHA  RTTL.EP', 'Teknolojia VoIP (Voice over Internet Protocol) maka inovasaun ida ne\'ebé uza rede \r\nIP sira hodi fornese komunikasaun lian eletróniku ne’ebe  real-time. Iha Rádiu no \r\nTelevizaun Timor-Leste, empreza públika ida (RTTL, EP), kordenasaun no \r\nkomunikasaun interna entre departamentu sira iha koridor 1 no 3 koridor ne’e \r\ndaudaun depende de’it ba kréditu telemovel no aplikasaun mensajen esterna, ne’ebé \r\nrezulta iha kustu operasionál ne’ebé a’as no dependénsia ba fornesedór internet. \r\nPeskiza ida-ne\'e hakarak atu dezeña no harii servidór VoIP open-source ida uza \r\nUbuntu Server no teknolojia Docker  container hodi fasilita komunikasaun ne\'ebé \r\nlivre no seguru. Implementa uza Ubuntu Server, Docker FreePBX, no Asterisk \r\nhanesan mekanizmu prinsipál ba koordenasaun xamada, integradu iha rede Wi-Fi \r\nRTTL liuhosi métodu Bridged Adapter. Atu asegura kualidade lian no seguransa \r\nrede nian, peskiza ne\'e mós konfigura protokolu PJSIP no RTP no firewall UFW. \r\nRezultadu implementasaun hatudu katak servidór VoIP ne\'ebé konstrui ona bele \r\nliga funsionáriu sira liuhosi aplikasaun Zoiper ho kualidade áudiu ne\'ebé di\'ak no \r\ndependénsia menus ba internet externu. Ho sistema ida ne\'e, RTTL,EP bele hetan \r\nbenefísiu hosi monitorizasaun xamada (CDR), efisiénsia kustu komunikasaun, no \r\njestaun rekursu IT ne\'ebé fasil liu, tanba sira utiliza teknolojia kontentór. \r\nLiafuan Xave: VoIP, Open Source, Ubuntu Server, Docker, FreePBX, Asterisk, \r\nRTTL, EP.', 'uploads/journals/1774741560_RELATORIOVOIPSERVER.pdf', 13, '2026-03-28 23:46:00'),
(4, 'História Komputadór husi Jerasaun Primeiru to’o Agora', 'Jerasaun Primeiru (1940–1956) ENIAC \r\nKomputador jerasaun primeiru hahu iha tinan 1942 to\'o 1959, momentu \r\nne\'eba komputador ho tamanhu ne\'ebe maka boot, Ezemplu; ENIAC, \r\nEDVAC, EDSAC, UNIVAC. Komputador jerasaun primeiru ne\'e ninia \r\nmedidas boot hanesan uma ida. No programa sira sei uza lian mesin, no so \r\nema ne\'ebe mak treinadu de\'it mak bele utiliza. \r\nEzemplu komputador jerasaun primeiru mak hanesan tuir mai ne\'e: \r\nENIAC (Electronic Numerical Integrator and Calculator) ne\'ebe mak \r\nhalo hosi Dr. John Mauchly no Presper Eckert iha tinan 1946. ENIAC iha \r\n17.468 tabung vakum 17.200 diodo Kristal, 1.500 transmiter, 70.000 resistor, \r\n10.000 kapasitor no 5 mill konektor ne\'ebe maka solda ho liman. Ninia todan \r\n27 ton, ninia medidas 2,4 m x 0,9 m x 30 m. ENIAC ninia luan mais/menus \r\n167 m² no konsumu energia eletrica 160 KW.', 'uploads/journals/1774741603_traballunela.pdf', 13, '2026-03-28 23:46:43'),
(5, 'Konfigurasaun Baziku Iha Mikrotik Utiliza Virtualbox (IP Address, DNS, Route, NAT, DHCP no HOTSPOT)', 'Konfigurasi MikroTik adalah proses pengaturan sistem operasi RouterOS pada perangkat MikroTik agar dapat berfungsi sebagai pengelola jaringan yang efisien. Melalui konfigurasi ini, Anda bisa mengatur bagaimana data masuk dan keluar, mengamankan jaringan, hingga membagi kecepatan internet. \r\nDibimbing\r\nDibimbing\r\n +2\r\nBerikut adalah poin-poin utama mengenai apa yang dilakukan saat mengonfigurasi MikroTik:\r\nManajemen Jaringan: Menghubungkan berbagai jaringan (LAN dan WAN) serta mengatur lalu lintas data di dalamnya.\r\nPengaturan Internet Gateway: Langkah dasar untuk membuat perangkat MikroTik bisa menyebarkan koneksi internet dari modem ke perangkat lain.\r\nFitur Utama: Meliputi pengaturan Routing (jalur data), Firewall (keamanan), Bandwidth Management (pembagian kecepatan), dan pembuatan Hotspot.\r\nAlat Konfigurasi: Paling sering menggunakan aplikasi Winbox yang berbasis antarmuka grafis (GUI) sehingga lebih mudah dipahami pemula dibandingkan melalui teks perintah (CLI). \r\nLangkah Dasar yang Biasanya Dilakukan:\r\nMenghubungkan kabel internet ke port 1 dan komputer ke port lainnya.\r\nMengakses router melalui Winbox menggunakan alamat MAC atau IP default.\r\nMengatur IP Address, DNS, dan NAT Masquerade agar perangkat di bawahnya bisa mengakses internet.\r\nMengaktifkan dan mengamankan Wi-Fi (jika mendukung)', 'uploads/journals/1774741919_KonfigurasaunBasicihaMikrotikutilizaVirtualBox.pdf', 14, '2026-03-28 23:51:59');

-- --------------------------------------------------------

--
-- Struktur dari tabel `journal_stats`
--

CREATE TABLE `journal_stats` (
  `id` int(11) NOT NULL,
  `journal_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` enum('view','download') NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `journal_stats`
--

INSERT INTO `journal_stats` (`id`, `journal_id`, `user_id`, `type`, `ip_address`, `created_at`) VALUES
(24, 4, NULL, 'view', '::1', '2026-03-28 23:47:01'),
(25, 4, 14, 'view', '::1', '2026-03-28 23:52:03'),
(26, 5, 14, 'view', '::1', '2026-03-28 23:53:12'),
(27, 4, 14, 'view', '::1', '2026-03-28 23:53:19'),
(28, 3, 14, 'view', '::1', '2026-03-28 23:54:28'),
(29, 3, 14, 'download', '::1', '2026-03-28 23:54:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `naran_kompletu` varchar(100) NOT NULL,
  `role` enum('author','reader') NOT NULL DEFAULT 'reader',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `naran_kompletu`, `role`, `created_at`) VALUES
(13, 'Deonisio', '$2y$10$DF0UFaDAuHL0tN2HO51.3.ZcX7PppkHJVw/HgqkirxnjzR00sNN0S', 'Deonisio da Costa', 'author', '2026-03-28 23:44:37'),
(14, 'Celestina', '$2y$10$TnYnv6eXzakHqjY8nQaPnesry23VyM7DQnumbDjxfwkfgvkmk4ni6', 'Celestina Almeida Da Costa', 'author', '2026-03-28 23:48:08');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `journals`
--
ALTER TABLE `journals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_journal_user` (`uploaded_by`);

--
-- Indeks untuk tabel `journal_stats`
--
ALTER TABLE `journal_stats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_stat_journal` (`journal_id`),
  ADD KEY `fk_stat_user` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `journals`
--
ALTER TABLE `journals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `journal_stats`
--
ALTER TABLE `journal_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `journals`
--
ALTER TABLE `journals`
  ADD CONSTRAINT `fk_journal_user` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `journal_stats`
--
ALTER TABLE `journal_stats`
  ADD CONSTRAINT `fk_stat_journal` FOREIGN KEY (`journal_id`) REFERENCES `journals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_stat_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
