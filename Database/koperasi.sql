-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2024 at 05:42 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `koperasi`
--

-- --------------------------------------------------------

--
-- Table structure for table `tabel_admin`
--

CREATE TABLE `tabel_admin` (
  `id` varchar(15) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(15) NOT NULL,
  `level` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tabel_admin`
--

INSERT INTO `tabel_admin` (`id`, `username`, `password`, `level`) VALUES
('LOG - 1', 'azhar', 'admin123', 'admin'),
('LOG - 2', 'user', 'user123', 'user'),
('LOG - 3', 'barok', '12345', 'manager'),
('LOG - 4', 'rizki', '12345', 'pemasok'),
('LOG - 5', 'Satria', 'satria123', 'user'),
('LOG - 6', 'Radit', 'radit123', 'user'),
('LOG - 7', 'Zaqi', 'zaqi123', 'user');

--
-- Triggers `tabel_admin`
--
DELIMITER $$
CREATE TRIGGER `trg_generate_id` BEFORE INSERT ON `tabel_admin` FOR EACH ROW BEGIN
    DECLARE next_id INT;

    SELECT COALESCE(MAX(CAST(SUBSTRING_INDEX(id, ' - ', -1) AS UNSIGNED)), 0) + 1 INTO next_id
    FROM tabel_admin;

    SET NEW.id = CONCAT('LOG - ', next_id);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tabel_barang`
--

CREATE TABLE `tabel_barang` (
  `kode_barang` int(5) NOT NULL,
  `nama_barang` varchar(30) NOT NULL,
  `jenis_barang` varchar(15) NOT NULL,
  `satuan` varchar(15) NOT NULL,
  `harga_beli` int(15) NOT NULL,
  `harga_jual` int(15) NOT NULL,
  `jumlah` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tabel_barang`
--

INSERT INTO `tabel_barang` (`kode_barang`, `nama_barang`, `jenis_barang`, `satuan`, `harga_beli`, `harga_jual`, `jumlah`) VALUES
(2121, 'Pensil', 'alat tulis', 'pcs', 2500, 5000, 50),
(2222, 'Buku Tulis', 'alat tulis', 'pcs', 2500, 7000, 50),
(2373, 'Dasi', 'Pakaian', 'pcs', 3000, 15000, 50),
(4444, 'Roll Tape', 'alat tulis', 'pcs', 3000, 7000, 50),
(5555, 'Penghapus', 'alat tulis', 'pcs', 2500, 5000, 50),
(8888, 'Nabati', 'makanan', 'pcs', 1000, 2500, 50),
(9999, 'Bolpen', 'alat tulis', 'pcs', 1000, 5000, 50);

-- --------------------------------------------------------

--
-- Table structure for table `tabel_pelanggan`
--

CREATE TABLE `tabel_pelanggan` (
  `kode_pelanggan` int(5) NOT NULL,
  `nama_pelanggan` varchar(30) NOT NULL,
  `alamat_pelanggan` varchar(30) NOT NULL,
  `no_telp_pelanggan` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tabel_pelanggan`
--

INSERT INTO `tabel_pelanggan` (`kode_pelanggan`, `nama_pelanggan`, `alamat_pelanggan`, `no_telp_pelanggan`) VALUES
(1, 'Zaky Mubarok', 'Makmur, Jakarta Timur', '088899996666'),
(2, 'Satria', 'Pasar Rebo', '012731031323'),
(3, 'Radit', 'Ciracas', '08891289'),
(4, 'Zaqi', 'Ciracas', '081289211');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_pemasok`
--

CREATE TABLE `tabel_pemasok` (
  `kode_pemasok` int(5) NOT NULL,
  `nama_pemasok` varchar(30) NOT NULL,
  `alamat` varchar(30) NOT NULL,
  `no_telp` varchar(12) NOT NULL,
  `email` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tabel_pemasok`
--

INSERT INTO `tabel_pemasok` (`kode_pemasok`, `nama_pemasok`, `alamat`, `no_telp`, `email`) VALUES
(89789, 'Udin', 'Munjul', '012801803', 'azhar@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_transaksi`
--

CREATE TABLE `tabel_transaksi` (
  `nomor_order` int(5) NOT NULL,
  `tanggal_order` date NOT NULL,
  `kode_pelanggan` int(5) NOT NULL,
  `nama_pelanggan` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tabel_transaksi`
--

INSERT INTO `tabel_transaksi` (`nomor_order`, `tanggal_order`, `kode_pelanggan`, `nama_pelanggan`) VALUES
(3535413, '2024-12-02', 4, 'Zaqi'),
(3535414, '2024-12-02', 2, 'Satria'),
(3535415, '2024-12-03', 4, 'Zaqi'),
(3535416, '2024-12-03', 3, 'Radit'),
(3535417, '2024-12-03', 3, 'Radit'),
(3535418, '2024-12-03', 2, 'Satria'),
(3535419, '2024-12-03', 4, 'Zaqi'),
(3535420, '2024-12-03', 2, 'Satria'),
(3535421, '2024-12-03', 4, 'Zaqi'),
(3535422, '2024-12-03', 4, 'Zaqi');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_koperasi`
--

CREATE TABLE `transaksi_koperasi` (
  `id_transaksi_kop` int(11) NOT NULL,
  `kode_barang` int(5) NOT NULL,
  `nama_barang` varchar(30) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `request_by` varchar(100) NOT NULL,
  `status` enum('Pending','Requested','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `approve_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi_koperasi`
--

INSERT INTO `transaksi_koperasi` (`id_transaksi_kop`, `kode_barang`, `nama_barang`, `jumlah`, `request_by`, `status`, `approve_by`) VALUES
(35, 2121, 'Pensil', 10, 'barok', 'Approved', 'azhar'),
(36, 2222, 'Buku Tulis', 10, 'barok', 'Approved', 'azhar'),
(37, 4444, 'Roll Tape', 10, 'barok', 'Approved', 'azhar'),
(38, 5555, 'Penghapus', 10, 'barok', 'Approved', 'azhar'),
(39, 8888, 'Nabati', 10, 'barok', 'Approved', 'azhar'),
(40, 2222, 'Buku Tulis', 3, 'barok', 'Approved', 'azhar'),
(41, 5555, 'Penghapus', 3, 'barok', 'Approved', 'azhar'),
(42, 2222, 'Buku Tulis', 3, 'barok', 'Approved', 'azhar'),
(43, 8888, 'Nabati', 3, 'barok', 'Approved', 'azhar'),
(44, 2121, 'Pensil', 100, 'barok', 'Approved', 'azhar'),
(45, 2222, 'Buku Tulis', 100, 'barok', 'Approved', 'azhar'),
(46, 4444, 'Roll Tape', 100, 'barok', 'Approved', 'azhar'),
(47, 5555, 'Penghapus', 100, 'barok', 'Approved', 'azhar'),
(48, 8888, 'Nabati', 100, 'barok', 'Approved', 'azhar'),
(49, 9999, 'Bolpoin', 100, 'barok', 'Approved', 'azhar'),
(50, 2373, 'Dasi', 100, 'barok', 'Approved', 'azhar'),
(51, 2373, 'Dasi', 50, 'barok', 'Approved', 'azhar');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_pelanggan`
--

CREATE TABLE `transaksi_pelanggan` (
  `id_transaksi` int(5) NOT NULL,
  `nomor_order` int(5) NOT NULL,
  `kode_barang` int(5) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `harga_barang` int(11) NOT NULL,
  `jumlah_beli` int(11) NOT NULL,
  `jumlah_total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi_pelanggan`
--

INSERT INTO `transaksi_pelanggan` (`id_transaksi`, `nomor_order`, `kode_barang`, `nama_barang`, `harga_barang`, `jumlah_beli`, `jumlah_total`) VALUES
(46, 3535413, 2121, 'Pensil', 5000, 1, 5000),
(47, 3535413, 2222, 'Buku Tulis', 7000, 1, 7000),
(48, 3535413, 4444, 'Roll Tape', 7000, 1, 7000),
(49, 3535413, 5555, 'Penghapus', 5000, 1, 5000),
(50, 3535413, 8888, 'Nabati', 2500, 1, 2500),
(51, 3535414, 2121, 'Pensil', 5000, 4, 20000),
(52, 3535414, 2222, 'Buku Tulis', 7000, 4, 28000),
(53, 3535414, 4444, 'Roll Tape', 7000, 4, 28000),
(54, 3535414, 5555, 'Penghapus', 5000, 4, 20000),
(55, 3535414, 8888, 'Nabati', 2500, 4, 10000),
(56, 3535415, 2222, 'Buku Tulis', 7000, 3, 21000),
(57, 3535415, 5555, 'Penghapus', 5000, 3, 15000),
(58, 3535416, 2222, 'Buku Tulis', 7000, 3, 21000),
(59, 3535416, 8888, 'Nabati', 2500, 3, 7500),
(60, 3535417, 8888, 'Nabati', 2500, 5, 12500),
(61, 3535417, 5555, 'Penghapus', 5000, 5, 25000),
(62, 3535417, 4444, 'Roll Tape', 7000, 5, 35000),
(63, 3535417, 2222, 'Buku Tulis', 7000, 5, 35000),
(64, 3535417, 2121, 'Pensil', 5000, 5, 25000),
(65, 3535418, 2121, 'Pensil', 5000, 50, 250000),
(66, 3535418, 2222, 'Buku Tulis', 7000, 50, 350000),
(67, 3535418, 4444, 'Roll Tape', 7000, 50, 350000),
(68, 3535418, 5555, 'Penghapus', 5000, 50, 250000),
(69, 3535418, 8888, 'Nabati', 2500, 50, 125000),
(70, 3535419, 9999, 'Bolpoin', 5000, 50, 250000),
(71, 3535420, 2373, 'Dasi', 15000, 25, 375000),
(72, 3535421, 2373, 'Dasi', 15000, 25, 375000),
(73, 3535422, 2373, 'Dasi', 15000, 50, 750000);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_pelanggan`
-- (See below for the actual view)
--
CREATE TABLE `view_pelanggan` (
`id` varchar(15)
,`username` varchar(30)
,`kode_pelanggan` int(5)
,`nama_pelanggan` varchar(30)
);

-- --------------------------------------------------------

--
-- Structure for view `view_pelanggan`
--
DROP TABLE IF EXISTS `view_pelanggan`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_pelanggan`  AS SELECT `tabel_admin`.`id` AS `id`, `tabel_admin`.`username` AS `username`, `tabel_pelanggan`.`kode_pelanggan` AS `kode_pelanggan`, `tabel_pelanggan`.`nama_pelanggan` AS `nama_pelanggan` FROM (`tabel_admin` join `tabel_pelanggan` on(`tabel_admin`.`username` = `tabel_pelanggan`.`nama_pelanggan`)) WHERE `tabel_admin`.`level` = 'user' ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tabel_admin`
--
ALTER TABLE `tabel_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tabel_barang`
--
ALTER TABLE `tabel_barang`
  ADD PRIMARY KEY (`kode_barang`);

--
-- Indexes for table `tabel_pelanggan`
--
ALTER TABLE `tabel_pelanggan`
  ADD PRIMARY KEY (`kode_pelanggan`);

--
-- Indexes for table `tabel_pemasok`
--
ALTER TABLE `tabel_pemasok`
  ADD PRIMARY KEY (`kode_pemasok`);

--
-- Indexes for table `tabel_transaksi`
--
ALTER TABLE `tabel_transaksi`
  ADD PRIMARY KEY (`nomor_order`),
  ADD KEY `fk_kode_pelanggan` (`kode_pelanggan`);

--
-- Indexes for table `transaksi_koperasi`
--
ALTER TABLE `transaksi_koperasi`
  ADD PRIMARY KEY (`id_transaksi_kop`),
  ADD KEY `fk_kode_barang` (`kode_barang`);

--
-- Indexes for table `transaksi_pelanggan`
--
ALTER TABLE `transaksi_pelanggan`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `fk_no_order` (`nomor_order`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tabel_pelanggan`
--
ALTER TABLE `tabel_pelanggan`
  MODIFY `kode_pelanggan` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tabel_transaksi`
--
ALTER TABLE `tabel_transaksi`
  MODIFY `nomor_order` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3535423;

--
-- AUTO_INCREMENT for table `transaksi_koperasi`
--
ALTER TABLE `transaksi_koperasi`
  MODIFY `id_transaksi_kop` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `transaksi_pelanggan`
--
ALTER TABLE `transaksi_pelanggan`
  MODIFY `id_transaksi` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tabel_transaksi`
--
ALTER TABLE `tabel_transaksi`
  ADD CONSTRAINT `fk_kode_pelanggan` FOREIGN KEY (`kode_pelanggan`) REFERENCES `tabel_pelanggan` (`kode_pelanggan`);

--
-- Constraints for table `transaksi_koperasi`
--
ALTER TABLE `transaksi_koperasi`
  ADD CONSTRAINT `fk_kode_barang` FOREIGN KEY (`kode_barang`) REFERENCES `tabel_barang` (`kode_barang`);

--
-- Constraints for table `transaksi_pelanggan`
--
ALTER TABLE `transaksi_pelanggan`
  ADD CONSTRAINT `fk_no_order` FOREIGN KEY (`nomor_order`) REFERENCES `tabel_transaksi` (`nomor_order`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
