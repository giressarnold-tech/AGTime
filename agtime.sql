-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2026 at 02:31 PM
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
(2, 67, NULL),
(3, 105, NULL),
(4, 106, NULL),
(5, 107, NULL);

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
(17, 31, 'PERMISSION', '2026-03-31', '2026-04-02', 'maladie', 'ACCEPTEE', '2026-03-28 12:10:55'),
(18, 31, 'CONGE', '2026-04-01', '2026-04-16', 'tourisme', 'REFUSEE', '2026-03-28 12:11:32'),
(19, 31, 'PERMISSION', '2026-04-04', '2026-04-06', 'mariage', 'ACCEPTEE', '2026-03-28 12:12:47'),
(20, 29, 'PERMISSION', '2026-03-31', '2026-04-01', 'soutenance', 'ACCEPTEE', '2026-03-28 12:15:02'),
(21, 29, 'CONGE', '2026-04-02', '2026-04-04', 'mission spéciale ', 'ACCEPTEE', '2026-03-28 12:16:25'),
(25, 33, 'PERMISSION', '2026-03-30', '2026-04-01', 'HIBSEBVOVOUON', 'REFUSEE', '2026-03-30 13:27:52'),
(26, 35, 'PERMISSION', '2026-04-01', '2026-04-02', 'fdc;kncb', 'REFUSEE', '2026-03-31 10:30:13'),
(27, 40, 'PERMISSION', '2026-03-31', '2026-04-02', 'kshgefhnhsvbo', 'ACCEPTEE', '2026-03-31 16:24:40');

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
(29, 78, NULL),
(31, 83, NULL),
(33, 95, NULL),
(34, 96, NULL),
(35, 99, NULL),
(36, 100, NULL),
(37, 102, NULL),
(39, 104, NULL),
(40, 109, NULL);

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
(11, 65, 'Nouvelle demande de permission soumise par laeticia mendouga.', 'lu', '2026-03-27 10:34:55'),
(12, 67, 'Nouvelle demande de permission soumise par laeticia mendouga.', 'non_lu', '2026-03-27 10:34:55'),
(16, 65, 'Nouvelle demande de permission soumise par laeticia mendouga.', 'lu', '2026-03-27 23:35:56'),
(17, 67, 'Nouvelle demande de permission soumise par laeticia mendouga.', 'non_lu', '2026-03-27 23:35:56'),
(21, 65, 'Nouvelle demande de conge soumise par laeticia mendouga.', 'lu', '2026-03-27 23:37:39'),
(22, 67, 'Nouvelle demande de conge soumise par laeticia mendouga.', 'non_lu', '2026-03-27 23:37:39'),
(28, 65, 'Nouvelle demande de permission soumise par Midoriya izuku.', 'lu', '2026-03-28 11:10:55'),
(29, 67, 'Nouvelle demande de permission soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:10:55'),
(34, 82, 'Nouvelle demande de permission soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:10:55'),
(35, 84, 'Nouvelle demande de permission soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:10:55'),
(36, 65, 'Nouvelle demande de conge soumise par Midoriya izuku.', 'lu', '2026-03-28 11:11:32'),
(37, 67, 'Nouvelle demande de conge soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:11:32'),
(42, 82, 'Nouvelle demande de conge soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:11:32'),
(43, 84, 'Nouvelle demande de conge soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:11:32'),
(44, 65, 'Nouvelle demande de permission soumise par Midoriya izuku.', 'lu', '2026-03-28 11:12:47'),
(45, 67, 'Nouvelle demande de permission soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:12:47'),
(50, 82, 'Nouvelle demande de permission soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:12:47'),
(51, 84, 'Nouvelle demande de permission soumise par Midoriya izuku.', 'non_lu', '2026-03-28 11:12:47'),
(52, 65, 'Nouvelle demande de permission soumise par Sukuna Ryomen.', 'lu', '2026-03-28 11:15:02'),
(53, 67, 'Nouvelle demande de permission soumise par Sukuna Ryomen.', 'non_lu', '2026-03-28 11:15:02'),
(58, 82, 'Nouvelle demande de permission soumise par Sukuna Ryomen.', 'non_lu', '2026-03-28 11:15:02'),
(59, 84, 'Nouvelle demande de permission soumise par Sukuna Ryomen.', 'non_lu', '2026-03-28 11:15:02'),
(60, 65, 'Nouvelle demande de conge soumise par Sukuna Ryomen.', 'lu', '2026-03-28 11:16:25'),
(61, 67, 'Nouvelle demande de conge soumise par Sukuna Ryomen.', 'non_lu', '2026-03-28 11:16:25'),
(66, 82, 'Nouvelle demande de conge soumise par Sukuna Ryomen.', 'non_lu', '2026-03-28 11:16:25'),
(67, 84, 'Nouvelle demande de conge soumise par Sukuna Ryomen.', 'non_lu', '2026-03-28 11:16:25'),
(68, 78, '✅ Votre demande a été acceptée.', 'non_lu', '2026-03-28 11:17:49'),
(69, 78, '✅ Votre demande a été acceptée.', 'non_lu', '2026-03-28 11:17:59'),
(70, 83, '❌ Votre demande a été refusée. Motif : ', 'non_lu', '2026-03-28 11:18:12'),
(71, 83, '✅ Votre demande a été acceptée.', 'non_lu', '2026-03-28 11:18:25'),
(72, 83, '✅ Votre demande a été acceptée.', 'non_lu', '2026-03-28 11:18:46'),
(73, 65, 'Nouvelle demande de conge soumise par sasuke uchiwa.', 'lu', '2026-03-28 11:24:01'),
(74, 67, 'Nouvelle demande de conge soumise par sasuke uchiwa.', 'non_lu', '2026-03-28 11:24:01'),
(79, 82, 'Nouvelle demande de conge soumise par sasuke uchiwa.', 'non_lu', '2026-03-28 11:24:01'),
(80, 84, 'Nouvelle demande de conge soumise par sasuke uchiwa.', 'non_lu', '2026-03-28 11:24:02'),
(81, 85, 'Nouvelle demande de conge soumise par sasuke uchiwa.', 'lu', '2026-03-28 11:24:02'),
(82, 65, 'Nouvelle demande de permission soumise par sasuke uchiwa.', 'lu', '2026-03-28 11:25:35'),
(83, 67, 'Nouvelle demande de permission soumise par sasuke uchiwa.', 'non_lu', '2026-03-28 11:25:35'),
(88, 82, 'Nouvelle demande de permission soumise par sasuke uchiwa.', 'non_lu', '2026-03-28 11:25:35'),
(89, 84, 'Nouvelle demande de permission soumise par sasuke uchiwa.', 'non_lu', '2026-03-28 11:25:35'),
(90, 85, 'Nouvelle demande de permission soumise par sasuke uchiwa.', 'lu', '2026-03-28 11:25:35'),
(93, 65, 'Nouvelle demande de conge soumise par sasuke uchiwa.', 'lu', '2026-03-29 17:52:57'),
(94, 67, 'Nouvelle demande de conge soumise par sasuke uchiwa.', 'non_lu', '2026-03-29 17:52:57'),
(99, 82, 'Nouvelle demande de conge soumise par sasuke uchiwa.', 'non_lu', '2026-03-29 17:52:57'),
(100, 84, 'Nouvelle demande de conge soumise par sasuke uchiwa.', 'non_lu', '2026-03-29 17:52:57'),
(101, 85, 'Nouvelle demande de conge soumise par sasuke uchiwa.', 'lu', '2026-03-29 17:52:57'),
(103, 65, 'Nouvelle demande de permission soumise par Lucrèce Belibi.', 'non_lu', '2026-03-30 12:27:52'),
(104, 67, 'Nouvelle demande de permission soumise par Lucrèce Belibi.', 'non_lu', '2026-03-30 12:27:52'),
(105, 82, 'Nouvelle demande de permission soumise par Lucrèce Belibi.', 'non_lu', '2026-03-30 12:27:52'),
(106, 84, 'Nouvelle demande de permission soumise par Lucrèce Belibi.', 'non_lu', '2026-03-30 12:27:52'),
(107, 85, 'Nouvelle demande de permission soumise par Lucrèce Belibi.', 'non_lu', '2026-03-30 12:27:52'),
(108, 90, 'Nouvelle demande de permission soumise par Lucrèce Belibi.', 'non_lu', '2026-03-30 12:27:52'),
(109, 91, 'Nouvelle demande de permission soumise par Lucrèce Belibi.', 'non_lu', '2026-03-30 12:27:52'),
(110, 92, 'Nouvelle demande de permission soumise par Lucrèce Belibi.', 'non_lu', '2026-03-30 12:27:52'),
(111, 93, 'Nouvelle demande de permission soumise par Lucrèce Belibi.', 'non_lu', '2026-03-30 12:27:52'),
(112, 94, 'Nouvelle demande de permission soumise par Lucrèce Belibi.', 'non_lu', '2026-03-30 12:27:52'),
(113, 97, 'Nouvelle demande de permission soumise par Lucrèce Belibi.', 'non_lu', '2026-03-30 12:27:52'),
(114, 98, 'Nouvelle demande de permission soumise par Lucrèce Belibi.', 'non_lu', '2026-03-30 12:27:52'),
(115, 95, '❌ Votre demande a été refusée. Motif : CGARABIAT', 'lu', '2026-03-30 12:29:12'),
(116, 65, 'Nouvelle demande de permission soumise par stanley jayson.', 'non_lu', '2026-03-31 09:30:13'),
(117, 67, 'Nouvelle demande de permission soumise par stanley jayson.', 'non_lu', '2026-03-31 09:30:13'),
(118, 82, 'Nouvelle demande de permission soumise par stanley jayson.', 'non_lu', '2026-03-31 09:30:13'),
(119, 84, 'Nouvelle demande de permission soumise par stanley jayson.', 'non_lu', '2026-03-31 09:30:13'),
(120, 85, 'Nouvelle demande de permission soumise par stanley jayson.', 'non_lu', '2026-03-31 09:30:13'),
(121, 90, 'Nouvelle demande de permission soumise par stanley jayson.', 'non_lu', '2026-03-31 09:30:13'),
(122, 91, 'Nouvelle demande de permission soumise par stanley jayson.', 'non_lu', '2026-03-31 09:30:13'),
(123, 92, 'Nouvelle demande de permission soumise par stanley jayson.', 'non_lu', '2026-03-31 09:30:13'),
(124, 93, 'Nouvelle demande de permission soumise par stanley jayson.', 'non_lu', '2026-03-31 09:30:13'),
(125, 94, 'Nouvelle demande de permission soumise par stanley jayson.', 'non_lu', '2026-03-31 09:30:13'),
(126, 97, 'Nouvelle demande de permission soumise par stanley jayson.', 'non_lu', '2026-03-31 09:30:13'),
(127, 98, 'Nouvelle demande de permission soumise par stanley jayson.', 'non_lu', '2026-03-31 09:30:13'),
(128, 105, 'Nouvelle demande de permission soumise par stanley jayson.', 'non_lu', '2026-03-31 09:30:13'),
(129, 99, '❌ Votre demande a été refusée. Motif : lfdclaueh', 'non_lu', '2026-03-31 09:30:57'),
(130, 99, '❌ Votre demande a été refusée. Motif : lfdclaueh', 'non_lu', '2026-03-31 09:31:33'),
(131, 65, 'Nouvelle demande de permission soumise par toto toto.', 'non_lu', '2026-03-31 15:24:40'),
(132, 67, 'Nouvelle demande de permission soumise par toto toto.', 'non_lu', '2026-03-31 15:24:40'),
(133, 82, 'Nouvelle demande de permission soumise par toto toto.', 'non_lu', '2026-03-31 15:24:40'),
(134, 84, 'Nouvelle demande de permission soumise par toto toto.', 'non_lu', '2026-03-31 15:24:40'),
(135, 85, 'Nouvelle demande de permission soumise par toto toto.', 'non_lu', '2026-03-31 15:24:40'),
(136, 90, 'Nouvelle demande de permission soumise par toto toto.', 'non_lu', '2026-03-31 15:24:40'),
(137, 91, 'Nouvelle demande de permission soumise par toto toto.', 'non_lu', '2026-03-31 15:24:40'),
(138, 92, 'Nouvelle demande de permission soumise par toto toto.', 'non_lu', '2026-03-31 15:24:40'),
(139, 93, 'Nouvelle demande de permission soumise par toto toto.', 'non_lu', '2026-03-31 15:24:40'),
(140, 94, 'Nouvelle demande de permission soumise par toto toto.', 'non_lu', '2026-03-31 15:24:40'),
(141, 97, 'Nouvelle demande de permission soumise par toto toto.', 'non_lu', '2026-03-31 15:24:40'),
(142, 98, 'Nouvelle demande de permission soumise par toto toto.', 'non_lu', '2026-03-31 15:24:40'),
(143, 105, 'Nouvelle demande de permission soumise par toto toto.', 'non_lu', '2026-03-31 15:24:40'),
(144, 106, 'Nouvelle demande de permission soumise par toto toto.', 'non_lu', '2026-03-31 15:24:40'),
(145, 107, 'Nouvelle demande de permission soumise par toto toto.', 'non_lu', '2026-03-31 15:24:40'),
(146, 109, '✅ Votre demande a été acceptée.', 'lu', '2026-03-31 15:25:35');

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
(27, 82, NULL),
(28, 84, NULL),
(29, 85, NULL),
(32, 90, NULL),
(33, 91, NULL),
(34, 92, NULL),
(35, 93, NULL),
(36, 94, NULL),
(37, 97, NULL),
(38, 98, NULL);

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
(78, 'EMP-26-078', 'Ryomen', 'Sukuna', '678902345', 'sukuna@gmail.com', 'sukuna', 'EMPLOYE', 0, '2026-03-28 10:28:55'),
(82, 'RH-26-082', 'Djouela', 'Delia', '693055530', 'delia@gmail.com', 'doma237', 'RH', 1, '2026-03-28 10:33:23'),
(83, 'EMP-26-083', 'izuku', 'Midoriya', '654782354', 'midoriya@gmail.com', 'hero', 'EMPLOYE', 0, '2026-03-28 12:00:50'),
(84, 'RH-26-084', 'Namikaze', 'Minato', '678763323', 'Minato@gmail.com', 'hokage', 'RH', 0, '2026-03-28 12:03:49'),
(85, 'RH-26-085', 'Grace', 'Astrid', '688731823', 'graceastrid@gmail.com', 'JESUS', 'RH', 1, '2026-03-28 12:20:57'),
(87, NULL, 'andy', 'ismael', '69843985', 'ismael@gmail.com', 'ismael', 'EMPLOYE', 1, '2026-03-30 05:24:21'),
(90, 'RH-26-090', 'grace', 'Astrid', '678906488', 'gracemedom@gmail.com', 'bonjour', 'RH', 1, '2026-03-30 11:49:28'),
(91, 'RH-26-091', 'Emole', 'Virena', '6987653', 'virenaemole50@gmail.com', 'bonjour', 'RH', 1, '2026-03-30 11:53:46'),
(92, 'RH-26-092', 'kemka', 'roh', '687146628', 'ndemk67@gmail.com', 'rohroh', 'RH', 1, '2026-03-30 11:56:32'),
(93, 'RH-26-093', 'wandji', 'estelle', '654789023', 'estellewandji67@gmail.com', 'estelle', 'RH', 1, '2026-03-30 11:57:51'),
(94, 'RH-26-094', 'yemeli', 'ketis', '698234556', 'ketisjuvincelle@gmail.com', 'tomate', 'RH', 1, '2026-03-30 12:00:13'),
(95, 'EMP-26-095', 'Belibi', 'Lucrèce', '683390420', 'belibilucrece@gmail.com', 'lucrece', 'EMPLOYE', 1, '2026-03-30 12:32:17'),
(96, 'EMP-26-096', 'Ivana', 'Lucrèce', '683390420', 'Ivanalucrece@icloud.com', 'tomate', 'EMPLOYE', 1, '2026-03-30 12:39:56'),
(97, 'RH-26-097', 'Domguia', 'Angele', '699725076', 'angelekenmogne65@gmail.com', 'JESUS', 'RH', 1, '2026-03-30 13:20:04'),
(98, 'RH-26-098', 'DOMGUIA', 'Angèle', '675915290', 'angeleken65@gmail.com', 'JESUS', 'RH', 1, '2026-03-30 13:23:23'),
(99, 'EMP-26-099', 'jayson', 'stanley', '698904354', 'Stanleydjems611@gmail.com', 'djems14', 'EMPLOYE', 0, '2026-03-30 13:44:24'),
(100, 'EMP-26-100', 'clémence', 'larissa', '678493844', 'Clemencelarissa53@gmail.com', 'amour', 'EMPLOYE', 1, '2026-03-30 13:46:22'),
(102, 'EMP-26-102', 'delia', 'djouela', '678903456', 'djouelayimendelia@gmail.com', 'baka', 'EMPLOYE', 1, '2026-03-30 15:44:03'),
(104, 'EMP-26-104', 'elvyge', 'queen', '693133030', 'queenelvyge@gmail.com', 'queen', 'EMPLOYE', 0, '2026-03-30 20:21:25'),
(105, 'ADM-26-105', 'FOKOM', 'Rodrigue', '684938933', 'contact@wathup.com', 'wathup', 'ADMIN', 1, '2026-03-30 21:28:01'),
(106, 'ADM-26-106', 'EYENGA', 'Yve', '698345672', 'yveeyenga@gmail.com', 'docteur', 'ADMIN', 1, '2026-03-31 16:19:08'),
(107, 'ADM-26-107', 'EYENGA', 'Yves', '698345672', 'yveseyenga@gmail.com', 'docteur', 'ADMIN', 1, '2026-03-31 16:19:47'),
(109, 'EMP-26-109', 'toto', 'toto', '6543287890', 'toto@gmail.com', 'toto', 'EMPLOYE', 1, '2026-03-31 16:23:21');

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
(5, '2026-03-30 13:29:12', 'REFUSEE', 'CGARABIAT', 38, 25),
(6, '2026-03-31 10:30:57', 'REFUSEE', 'lfdclaueh', 38, 26),
(7, '2026-03-31 10:31:33', 'REFUSEE', 'lfdclaueh', 38, 26);

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
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `demande`
--
ALTER TABLE `demande`
  MODIFY `id_demande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `employe`
--
ALTER TABLE `employe`
  MODIFY `id_employe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `historique`
--
ALTER TABLE `historique`
  MODIFY `id_historique` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id_notif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT for table `poste`
--
ALTER TABLE `poste`
  MODIFY `id_poste` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rh`
--
ALTER TABLE `rh`
  MODIFY `id_rh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `validation`
--
ALTER TABLE `validation`
  MODIFY `id_validation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
