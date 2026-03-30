-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 28, 2026 at 10:20 AM
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
(16, 25, 'CONGE', '2026-04-01', '2027-05-01', 'maternité ', 'ACCEPTEE', '2026-03-28 00:37:39');

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
(25, 73, NULL);

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
(20, 74, 'Nouvelle demande de permission soumise par laeticia mendouga.', 'non_lu', '2026-03-27 23:35:56'),
(21, 65, 'Nouvelle demande de conge soumise par laeticia mendouga.', 'lu', '2026-03-27 23:37:39'),
(22, 67, 'Nouvelle demande de conge soumise par laeticia mendouga.', 'non_lu', '2026-03-27 23:37:39'),
(23, 70, 'Nouvelle demande de conge soumise par laeticia mendouga.', 'non_lu', '2026-03-27 23:37:39'),
(24, 72, 'Nouvelle demande de conge soumise par laeticia mendouga.', 'non_lu', '2026-03-27 23:37:39'),
(25, 74, 'Nouvelle demande de conge soumise par laeticia mendouga.', 'non_lu', '2026-03-27 23:37:39'),
(26, 73, '❌ Votre demande a été refusée. Motif : ', 'lu', '2026-03-27 23:43:54'),
(27, 73, '✅ Votre demande a été acceptée.', 'lu', '2026-03-27 23:44:03');

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
(24, 74, NULL);

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
(74, 'RH-26-074', 'kamdem', 'Lucrèce', '678986534', 'lucrece@gmail.com', 'asso', 'RH', 1, '2026-03-28 00:32:30');

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
(3, '2026-03-27 16:56:31', 'REFUSEE', 'periode longue', 23, 14);

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
  MODIFY `id_demande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `employe`
--
ALTER TABLE `employe`
  MODIFY `id_employe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `historique`
--
ALTER TABLE `historique`
  MODIFY `id_historique` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id_notif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `poste`
--
ALTER TABLE `poste`
  MODIFY `id_poste` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rh`
--
ALTER TABLE `rh`
  MODIFY `id_rh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `validation`
--
ALTER TABLE `validation`
  MODIFY `id_validation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
