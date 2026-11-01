-- Database: promethee_db
-- SPK PROMETHEE Penentuan Game Roblox Ramah & Edukatif Anak

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `penilaian`;
DROP TABLE IF EXISTS `alternatif`;
DROP TABLE IF EXISTS `kriteria`;
SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------
-- Table structure for table `alternatif`
-- --------------------------------------------------------
CREATE TABLE `alternatif` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `alternatif`
INSERT INTO `alternatif` (`id`, `kode`, `nama`, `created_at`) VALUES
(1, 'A1', 'Supermarket Together', '2025-12-14 11:36:40'),
(2, 'A2', 'Brookhaven', '2025-12-14 11:36:40'),
(3, 'A3', 'Plant vs Brainrot', '2025-12-14 11:36:40'),
(4, 'A4', 'Grow a Garden', '2025-12-14 11:36:40');

-- --------------------------------------------------------
-- Table structure for table `kriteria`
-- --------------------------------------------------------
CREATE TABLE `kriteria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `kriteria`
INSERT INTO `kriteria` (`id`, `kode`, `nama`, `created_at`) VALUES
(1, 'K1', 'Tingkat Kekerasan', '2025-12-14 11:36:40'),
(2, 'K2', 'Nilai Edukasi', '2025-12-14 11:36:40'),
(3, 'K3', 'Keamanan Interaksi Sosial', '2025-12-14 11:36:40'),
(4, 'K4', 'Kompleksitas Gameplay', '2025-12-14 11:36:40'),
(5, 'K5', 'Konten Ramah Anak', '2025-12-14 11:36:40');

-- --------------------------------------------------------
-- Table structure for table `penilaian`
-- --------------------------------------------------------
CREATE TABLE `penilaian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_alternatif` int(11) NOT NULL,
  `id_kriteria` int(11) NOT NULL,
  `nilai` int(11) NOT NULL CHECK (`nilai` between 1 and 10),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_penilaian` (`id_alternatif`,`id_kriteria`),
  KEY `id_kriteria` (`id_kriteria`),
  CONSTRAINT `penilaian_ibfk_1` FOREIGN KEY (`id_alternatif`) REFERENCES `alternatif` (`id`) ON DELETE CASCADE,
  CONSTRAINT `penilaian_ibfk_2` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `penilaian`
INSERT INTO `penilaian` (`id`, `id_alternatif`, `id_kriteria`, `nilai`, `created_at`) VALUES
(1, 1, 1, 1, '2025-12-14 11:36:40'),
(2, 1, 2, 4, '2025-12-14 11:36:40'),
(3, 1, 3, 4, '2025-12-14 11:36:40'),
(4, 1, 4, 2, '2025-12-14 11:36:40'),
(5, 1, 5, 5, '2025-12-14 11:36:40'),
(6, 2, 1, 1, '2025-12-14 11:36:40'),
(7, 2, 2, 3, '2025-12-14 11:36:40'),
(8, 2, 3, 3, '2025-12-14 11:36:40'),
(9, 2, 4, 1, '2025-12-14 11:36:40'),
(10, 2, 5, 4, '2025-12-14 11:36:40'),
(11, 3, 1, 2, '2025-12-14 11:36:40'),
(12, 3, 2, 3, '2025-12-14 11:36:40'),
(13, 3, 3, 2, '2025-12-14 11:36:40'),
(14, 3, 4, 2, '2025-12-14 11:36:40'),
(15, 3, 5, 3, '2025-12-14 11:36:40'),
(16, 4, 1, 2, '2025-12-14 11:36:40'),
(17, 4, 2, 4, '2025-12-14 11:36:40'),
(18, 4, 3, 4, '2025-12-14 11:36:40'),
(19, 4, 4, 3, '2025-12-14 11:36:40'),
(20, 4, 5, 4, '2025-12-14 11:36:40');
