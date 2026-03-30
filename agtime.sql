-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2026 at 09:09 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agtime`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrateur`
--

CREATE TABLE `administrateur` (
  `id_admin` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `matricule` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `administrateur`
--

INSERT INTO `administrateur` (`id_admin`, `id_user`, `matricule`) VALUES
(2, 67, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `demande`
--

CREATE TABLE `demande` (
  `id_demande` int(11) NOT NULL,
  `id_employe` int(11) NOT NULL,
  `type_demande` enum('CONGE','PERMISSION') NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `motif` text DEFAULT NULL,
  `statut` enum('EN_ATTENTE','ACCEPTEE','REFUSEE') DEFAULT 'EN_ATTENTE',
  `date_demande` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `demande`
--

INSERT INTO `demande` (`id_demande`, `id_employe`, `type_demande`, `date_debut`, `date_fin`, `motif`, `statut`, `date_demande`) VALUES
(14, 25, 'PERMISSION', '2026-03-28', '2026-05-27', 'voyage a but touristique ', 'REFUSEE', '2026-03-27 11:34:55'),
(15, 25, 'PERMISSION', '2026-03-31', '2026-04-01', 'rendez-vous ', 'REFUSEE', '2026-03-28 00:35:56'),
(16, 25, 'CONGE', '2026-04-01', '2027-05-01', 'maternité ', 'ACCEPTEE', '2026-03-28 00:37:39'),
(17, 31, 'PERMISSION', '2026-03-31', '2026-04-02', 'maladie', 'ACCEPTEE', '2026-03-28 12:10:55'),
(18, 31, 'CONGE', '2026-04-01', '2026-04-16', 'tourisme', 'REFUSEE', '2026-03-28 12:11:32'),
(19, 31, 'PERMISSION', '2026-04-04', '2026-04-06', 'mariage', 'ACCEPTEE', '2026-03-28 12:12:47'),
(20, 29, 'PERMISSION', '2026-03-31', '2026-04-01', 'soutenance', 'ACCEPTEE', '2026-03-28 12:15:02'),
(21, 29, 'CONGE', '2026-04-02', '2026-04-04', 'mission spéciale ', 'ACCEPTEE', '2026-03-28 12:16:25'),
(22, 27, 'CONGE', '2026-04-01', '2026-05-01', 'inspection des autres structures', 'REFUSEE', '2026-03-28 12:24:01'),
(23, 27, 'PERMISSION', '2026-05-16', '2026-05-18', 'inspection', 'REFUSEE', '2026-03-28 12:25:35'),
(24, 27, 'CONGE', '2026-03-31', '2026-04-03', 'hscjsljpskfn', 'ACCEPTEE', '2026-03-29 18:52:57');

-- --------------------------------------------------------

--
-- Table structure for table `employe`
--

CREATE TABLE `employe` (
  `id_employe` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `matricule` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employe`
--

INSERT INTO `employe` (`id_employe`, `id_user`, `matricule`) VALUES
(25, 73, NULL),
(26, 75, NULL),
(27, 76, NULL),
(28, 77, NULL),
(29, 78, NULL),
(30, 79, NULL),
(31, 83, NULL),
(32, 86, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `historique`
--

CREATE TABLE `historique` (
  `id_historique` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `date_action` datetime DEFAULT current_timestamp(),
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id_notif` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `statut` enum('non_lu','lu') DEFAULT 'non_lu',
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`id_notif`, `id_user`, `message`, `statut`, `date_creation`) VALUES
(3, 65, 'Nouvelle demande de permission soumise par macabo macabo.', 'lu', '2026-03-26 11:51:19'),
(7, 65, 'Nouvelle demande de permission soumise par Astrid grace.', 'lu', '2026-03-26 13:10:48'),
(8, 67, 'Nouvelle demande de permission soumise par Astrid grace.', 'non_lu', '2026-03-26 13:10:48'),
(9, 70, 'Nouvelle demande de permission soumise par Astrid grace.', 'lu', '2026-03-26 13:10:48'),
(11, 65, 'Nouvelle demande de permission soumise par laeticia mendouga.', 'lu', '2026-03-27 10:34:55'),
(12, 67, 'Nouvelle demande de permission soumise par laeticia mendouga.', 'non_lu', '2026-03-27 10:34:55'),
(13, 70, 'Nouvelle demande de permission soumise par laeticia mendouga.', 'non_lu', '2026-03-27 10:34:55'),
(14, 72, 'Nouvelle demande de permission soumise par laeticia mendouga.', 'non_lu', '2026-03-27 10:34:55'),
(15, 73, '❌ Votre demande a été refusée. Motif : periode longue', 'lu', '2026-03-27 15:56:31'),
(16, 65, 'Nouvelle demande de permission soumise par laeticia mendouga.', 'lu', '2026-03-27 23:35:56'),
(17, 67, 'Nouvelle demande de permission soumise par laeticia mendouga.', 'non_lu', '2026-03-27 23:35:56'),
(18, 70, 'Nouvelle demande de permission soumise par laeticia mendouga.', 'non_lu', '2026-03-27 23:35:56'),
(19, 72, 'Nouvelle demande de permission soumise par laeticia mendouga.', 'non_lu', '2026-03-27 23:35:56'),
(21, 65, 'Nouvelle demande de conge soumise par laeticia mendouga.', 'lu', '2026-03-27 23:37:39'),
(22, 67, 'Nouvelle demande de conge soumise par laeticia mendouga.', 'non_lu', '2026-03-27 23:37:39'),
(23, 70, 'Nouvelle demande de conge soumise par laeticia mendouga.', 'non_lu', '2026-03-27 23:37:39'),
(24, 72, 'Nouvelle demande de conge soumise par laeticia mendouga.', 'non_lu', '2026-03-27 23:37:39'),
(26, 73, '❌ Votre demande a été refusée. Motif : ', 'lu', '2026-03-27 23:43:54'),
(27, 73, '✅ Votre demande a été acceptée.', 'lu', '2026-03-27 23:44:03'),
(28, 65, 'Nouvelle demande de permission soumise par Midoriya izuku.', 'lu', '2026-03-28 11:10:55'),
(29, 67, 'Nouvelle demande de permission soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:10:55'),
(30, 70, 'Nouvelle demande de permission soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:10:55'),
(31, 72, 'Nouvelle demande de permission soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:10:55'),
(32, 80, 'Nouvelle demande de permission soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:10:55'),
(33, 81, 'Nouvelle demande de permission soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:10:55'),
(34, 82, 'Nouvelle demande de permission soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:10:55'),
(35, 84, 'Nouvelle demande de permission soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:10:55'),
(36, 65, 'Nouvelle demande de conge soumise par Midoriya izuku.', 'lu', '2026-03-28 11:11:32'),
(37, 67, 'Nouvelle demande de conge soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:11:32'),
(38, 70, 'Nouvelle demande de conge soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:11:32'),
(39, 72, 'Nouvelle demande de conge soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:11:32'),
(40, 80, 'Nouvelle demande de conge soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:11:32'),
(41, 81, 'Nouvelle demande de conge soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:11:32'),
(42, 82, 'Nouvelle demande de conge soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:11:32'),
(43, 84, 'Nouvelle demande de conge soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:11:32'),
(44, 65, 'Nouvelle demande de permission soumise par Midoriya izuku.', 'lu', '2026-03-28 11:12:47'),
(45, 67, 'Nouvelle demande de permission soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:12:47'),
(46, 70, 'Nouvelle demande de permission soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:12:47'),
(47, 72, 'Nouvelle demande de permission soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:12:47'),
(48, 80, 'Nouvelle demande de permission soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:12:47'),
(49, 81, 'Nouvelle demande de permission soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:12:47'),
(50, 82, 'Nouvelle demande de permission soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:12:47'),
(51, 84, 'Nouvelle demande de permission soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:12:47'),
(52, 65, 'Nouvelle demande de permission soumise par Sukuna Ryomen.', 'lu', '2026-03-28 11:15:02'),
(53, 67, 'Nouvelle demande de permission soumise par Sukuna Ryomen.', 'non_lu', '2026-03-28 11:15:02'),
(54, 70, 'Nouvelle demande de permission soumise par Sukuna Ryomen.', 'non_lu', '2026-03-28 11:15:02'),
(55, 72, 'Nouvelle demande de permission soumise par Sukuna Ryomen.', 'non_lu', '2026-03-28 11:15:02'),
(56, 80, 'Nouvelle demande de permission soumise par Sukuna Ryomen.', 'non_lu', '2026-03-28 11:15:02'),
(57, 81, 'Nouvelle demande de permission soumise par Sukuna Ryomen.', 'non_lu', '2026-03-28 11:15:02'),
(58, 82, 'Nouvelle demande de permission soumise par Sukuna Ryomen.', 'non_lu', '2026-03-28 11:15:02'),
(59, 84, 'Nouvelle demande de permission soumise par Sukuna Ryomen.', 'non_lu', '2026-03-28 11:15:02'),
(60, 65, 'Nouvelle demande de conge soumise par Sukuna Ryomen.', 'lu', '2026-03-28 11:16:25'),
(61, 67, 'Nouvelle demande de conge soumise par Sukuna Ryomen.', 'non_lu', '2026-03-28 11:16:25'),
(62, 70, 'Nouvelle demande de conge soumise par Sukuna Ryomen.', 'non_lu', '2026-03-28 11:16:25'),
(63, 72, 'Nouvelle demande de conge soumise par Sukuna Ryomen.', 'non_lu', '2026-03-28 11:16:25'),
(64, 80, 'Nouvelle demande de conge soumise par Sukuna Ryomen.', 'non_lu', '2026-03-28 11:16:25'),
(65, 81, 'Nouvelle demande de conge soumise par Sukuna Ryomen.', 'non_lu', '2026-03-28 11:16:25'),
(66, 82, 'Nouvelle demande de conge soumise par Sukuna Ryomen.', 'non_lu', '2026-03-28 11:16:25'),
(67, 84, 'Nouvelle demande de conge soumise par Sukuna Ryomen.', 'non_lu', '2026-03-28 11:16:25'),
(68, 78, '✅ Votre demande a été acceptée.', 'non_lu', '2026-03-28 11:17:49'),
(69, 78, '✅ Votre demande a été acceptée.', 'non_lu', '2026-03-28 11:17:59'),
(70, 83, '❌ Votre demande a été refusée. Motif : ', 'non_lu', '2026-03-28 11:18:12'),
(71, 83, '✅ Votre demande a été acceptée.', 'non_lu', '2026-03-28 11:18:25'),
(72, 83, '✅ Votre demande a été acceptée.', 'non_lu', '2026-03-28 11:18:46'),
(73, 65, 'Nouvelle demande de conge soumise par sasuke uchiwa.', 'lu', '2026-03-28 11:24:01'),
(74, 67, 'Nouvelle demande de conge soumise par sasuke uchiwa.', 'non_lu', '2026-03-28 11:24:01'),
(75, 70, 'Nouvelle demande de conge soumise par sasuke uchiwa.', 'non_lu', '2026-03-28 11:24:01'),
(76, 72, 'Nouvelle demande de conge soumise par sasuke uchiwa.', 'non_lu', '2026-03-28 11:24:01'),
(77, 80, 'Nouvelle demande de conge soumise par sasuke uchiwa.', 'non_lu', '2026-03-28 11:24:01'),
(78, 81, 'Nouvelle demande de conge soumise par sasuke uchiwa.', 'non_lu', '2026-03-28 11:24:01'),
(79, 82, 'Nouvelle demande de conge soumise par sasuke uchiwa.', 'non_lu', '2026-03-28 11:24:01'),
(80, 84, 'Nouvelle demande de conge soumise par sasuke uchiwa.', 'non_lu', '2026-03-28 11:24:02'),
(81, 85, 'Nouvelle demande de conge soumise par sasuke uchiwa.', 'lu', '2026-03-28 11:24:02'),
(82, 65, 'Nouvelle demande de permission soumise par sasuke uchiwa.', 'lu', '2026-03-28 11:25:35'),
(83, 67, 'Nouvelle demande de permission soumise par sasuke uchiwa.', 'non_lu', '2026-03-28 11:25:35'),
(84, 70, 'Nouvelle demande de permission soumise par sasuke uchiwa.', 'non_lu', '2026-03-28 11:25:35'),
(85, 72, 'Nouvelle demande de permission soumise par sasuke uchiwa.', 'non_lu', '2026-03-28 11:25:35'),
(86, 80, 'Nouvelle demande de permission soumise par sasuke uchiwa.', 'non_lu', '2026-03-28 11:25:35'),
(87, 81, 'Nouvelle demande de permission soumise par sasuke uchiwa.', 'non_lu', '2026-03-28 11:25:35'),
(88, 82, 'Nouvelle demande de permission soumise par sasuke uchiwa.', 'non_lu', '2026-03-28 11:25:35'),
(89, 84, 'Nouvelle demande de permission soumise par sasuke uchiwa.', 'non_lu', '2026-03-28 11:25:35'),
(90, 85, 'Nouvelle demande de permission soumise par sasuke uchiwa.', 'lu', '2026-03-28 11:25:35'),
(91, 76, '❌ Votre demande a été refusée. Motif : impossible', 'lu', '2026-03-28 16:17:26'),
(92, 76, '❌ Votre demande a été refusée. Motif : periode longue', 'lu', '2026-03-29 17:51:21'),
(93, 65, 'Nouvelle demande de conge soumise par sasuke uchiwa.', 'lu', '2026-03-29 17:52:57'),
(94, 67, 'Nouvelle demande de conge soumise par sasuke uchiwa.', 'non_lu', '2026-03-29 17:52:57'),
(95, 70, 'Nouvelle demande de conge soumise par sasuke uchiwa.', 'non_lu', '2026-03-29 17:52:57'),
(96, 72, 'Nouvelle demande de conge soumise par sasuke uchiwa.', 'non_lu', '2026-03-29 17:52:57'),
(97, 80, 'Nouvelle demande de conge soumise par sasuke uchiwa.', 'non_lu', '2026-03-29 17:52:57'),
(98, 81, 'Nouvelle demande de conge soumise par sasuke uchiwa.', 'non_lu', '2026-03-29 17:52:57'),
(99, 82, 'Nouvelle demande de conge soumise par sasuke uchiwa.', 'non_lu', '2026-03-29 17:52:57'),
(100, 84, 'Nouvelle demande de conge soumise par sasuke uchiwa.', 'non_lu', '2026-03-29 17:52:57'),
(101, 85, 'Nouvelle demande de conge soumise par sasuke uchiwa.', 'lu', '2026-03-29 17:52:57'),
(102, 76, '✅ Votre demande a été acceptée.', 'non_lu', '2026-03-29 17:54:42');

-- --------------------------------------------------------

--
-- Table structure for table `poste`
--

CREATE TABLE `poste` (
  `id_poste` int(11) NOT NULL,
  `nom_poste` varchar(50) NOT NULL,
  `departement` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `rh`
--

CREATE TABLE `rh` (
  `id_rh` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `matricule` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rh`
--

INSERT INTO `rh` (`id_rh`, `id_user`, `matricule`) VALUES
(22, 70, NULL),
(23, 72, NULL),
(25, 80, NULL),
(26, 81, NULL),
(27, 82, NULL),
(28, 84, NULL),
(29, 85, NULL),
(30, 88, NULL),
(31, 89, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_user` int(11) NOT NULL,
  `matricule` varchar(15) DEFAULT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `tel` varchar(12) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('ADMIN','RH','EMPLOYE') NOT NULL,
  `actif` tinyint(1) DEFAULT 1,
  `date_creation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `utilisateur`
--

INSERT INTO `utilisateur` (`id_user`, `matricule`, `nom`, `prenom`, `tel`, `email`, `mot_de_passe`, `role`, `actif`, `date_creation`) VALUES
(65, NULL, 'giress', 'arnold', '687146628', 'giressarnold@gmail.com', '12345', 'ADMIN', 1, '2026-03-26 12:42:37'),
(67, 'ADM-26-067', 'wandji', 'estelle', '688462229', 'estellewandji@gmail.com', '123456789', 'ADMIN', 1, '2026-03-26 13:06:16'),
(70, 'RH-26-070', 'Emole', 'Virena', '670360211', 'virenaemole50@gmail.com', 'bonjour', 'RH', 1, '2026-03-26 14:07:46'),
(72, 'RH-26-072', 'Ngani', 'Grace', '657669536', 'angengani@gmail.com', 'ange', 'RH', 0, '2026-03-27 11:20:48'),
(73, 'EMP-26-073', 'mendouga', 'laeticia', '698414494', 'mendougalaeticia@gmail.com', '123', 'EMPLOYE', 1, '2026-03-27 11:23:38'),
(75, 'EMP-26-075', 'uchiwa', 'itachi', '691234567', 'itachi@gmail.com', 'itachi', 'EMPLOYE', 0, '2026-03-28 10:26:41'),
(76, 'EMP-26-076', 'uchiwa', 'sasuke', '678901234', 'sasuke@gmail.com', 'sasuke', 'EMPLOYE', 1, '2026-03-28 10:27:08'),
(77, 'EMP-26-077', 'kamado', 'tanjiro', '654321956', 'tanjiro@gmail.com', 'tanjiro', 'EMPLOYE', 0, '2026-03-28 10:27:42'),
(78, 'EMP-26-078', 'Ryomen', 'Sukuna', '678902345', 'sukuna@gmail.com', 'sukuna', 'EMPLOYE', 1, '2026-03-28 10:28:55'),
(79, 'EMP-26-079', 'Itadori', 'yuji', '678435690', 'itadori@gmail.com', 'yuji', 'EMPLOYE', 0, '2026-03-28 10:30:36'),
(80, 'RH-26-080', 'Satoru', 'Gojo', '623456780', 'gojo@gmail.com', 'gojo', 'RH', 1, '2026-03-28 10:31:06'),
(81, 'RH-26-081', 'hyuga', 'Hinata', '699000000', 'hinata@gmail.com', 'hinata', 'RH', 0, '2026-03-28 10:32:17'),
(82, 'RH-26-082', 'Djouela', 'Delia', '693055530', 'delia@gmail.com', 'doma237', 'RH', 1, '2026-03-28 10:33:23'),
(83, 'EMP-26-083', 'izuku', 'Midoriya', '654782354', 'midoriya@gmail.com', 'hero', 'EMPLOYE', 1, '2026-03-28 12:00:50'),
(84, 'RH-26-084', 'Namikaze', 'Minato', '678763323', 'Minato@gmail.com', 'hokage', 'RH', 0, '2026-03-28 12:03:49'),
(85, 'RH-26-085', 'Grace', 'Astrid', '688731823', 'graceastrid@gmail.com', 'JESUS', 'RH', 1, '2026-03-28 12:20:57'),
(86, 'EMP-26-086', 'qwerty', 'azerty', '679874095', 'qwerty@gmail.com', 'qwerty', 'EMPLOYE', 1, '2026-03-29 18:56:32'),
(87, NULL, 'andy', 'ismael', '69843985', 'ismael@gmail.com', 'ismael', 'EMPLOYE', 1, '2026-03-30 05:24:21'),
(88, 'RH-26-088', 'giress', 'arnold', '687146628', 'giressarnold@icloud.com', 'joue', 'RH', 1, '2026-03-30 05:53:19'),
(89, 'RH-26-089', 'grace', 'Astrid', '678906488', 'gracemedom@gmail.com', 'bonjour', 'RH', 1, '2026-03-30 07:36:31');

-- --------------------------------------------------------

--
-- Table structure for table `validation`
--

CREATE TABLE `validation` (
  `id_validation` int(11) NOT NULL,
  `date_validation` datetime DEFAULT current_timestamp(),
  `decision` enum('ACCEPTEE','REFUSEE') NOT NULL,
  `commentaire` text DEFAULT NULL,
  `id_rh` int(11) NOT NULL,
  `id_demande` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `validation`
--

INSERT INTO `validation` (`id_validation`, `date_validation`, `decision`, `commentaire`, `id_rh`, `id_demande`) VALUES
(3, '2026-03-27 16:56:31', 'REFUSEE', 'periode longue', 23, 14),
(4, '2026-03-29 18:54:42', 'ACCEPTEE', '', 29, 24);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrateur`
--
ALTER TABLE `administrateur`
  ADD PRIMARY KEY (`id_admin`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `demande`
--
ALTER TABLE `demande`
  ADD PRIMARY KEY (`id_demande`),
  ADD KEY `id_employe` (`id_employe`);

--
-- Indexes for table `employe`
--
ALTER TABLE `employe`
  ADD PRIMARY KEY (`id_employe`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `historique`
--
ALTER TABLE `historique`
  ADD PRIMARY KEY (`id_historique`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id_notif`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `poste`
--
ALTER TABLE `poste`
  ADD PRIMARY KEY (`id_poste`);

--
-- Indexes for table `rh`
--
ALTER TABLE `rh`
  ADD PRIMARY KEY (`id_rh`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `validation`
--
ALTER TABLE `validation`
  ADD PRIMARY KEY (`id_validation`),
  ADD KEY `id_rh` (`id_rh`),
  ADD KEY `id_demande` (`id_demande`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrateur`
--
ALTER TABLE `administrateur`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `demande`
--
ALTER TABLE `demande`
  MODIFY `id_demande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `employe`
--
ALTER TABLE `employe`
  MODIFY `id_employe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `historique`
--
ALTER TABLE `historique`
  MODIFY `id_historique` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id_notif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `poste`
--
ALTER TABLE `poste`
  MODIFY `id_poste` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rh`
--
ALTER TABLE `rh`
  MODIFY `id_rh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `validation`
--
ALTER TABLE `validation`
  MODIFY `id_validation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `administrateur`
--
ALTER TABLE `administrateur`
  ADD CONSTRAINT `administrateur_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `demande`
--
ALTER TABLE `demande`
  ADD CONSTRAINT `demande_ibfk_1` FOREIGN KEY (`id_employe`) REFERENCES `employe` (`id_employe`) ON DELETE CASCADE;

--
-- Constraints for table `employe`
--
ALTER TABLE `employe`
  ADD CONSTRAINT `employe_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `historique`
--
ALTER TABLE `historique`
  ADD CONSTRAINT `historique_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id_user`);

--
-- Constraints for table `rh`
--
ALTER TABLE `rh`
  ADD CONSTRAINT `rh_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `validation`
--
ALTER TABLE `validation`
  ADD CONSTRAINT `validation_ibfk_1` FOREIGN KEY (`id_rh`) REFERENCES `rh` (`id_rh`) ON DELETE CASCADE,
  ADD CONSTRAINT `validation_ibfk_2` FOREIGN KEY (`id_demande`) REFERENCES `demande` (`id_demande`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
