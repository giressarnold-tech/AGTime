-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 14 mars 2026 à 20:13
-- Version du serveur : 10.4.24-MariaDB
-- Version de PHP : 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `agtime`
--

-- --------------------------------------------------------

--
-- Structure de la table `administrateur`
--

CREATE TABLE `administrateur` (
  `id_admin` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `demande`
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
-- Déchargement des données de la table `demande`
--

INSERT INTO `demande` (`id_demande`, `id_employe`, `type_demande`, `date_debut`, `date_fin`, `motif`, `statut`, `date_demande`) VALUES
(6, 17, 'PERMISSION', '2026-03-27', '2026-03-31', 'travaux', 'EN_ATTENTE', '2026-03-09 11:51:04'),
(7, 17, 'CONGE', '2026-03-27', '2026-03-31', 'repos', 'EN_ATTENTE', '2026-03-09 11:53:28'),
(8, 17, 'CONGE', '2026-03-27', '2026-03-31', 'repos', 'EN_ATTENTE', '2026-03-09 11:54:20');

-- --------------------------------------------------------

--
-- Structure de la table `employe`
--

CREATE TABLE `employe` (
  `id_employe` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `employe`
--

INSERT INTO `employe` (`id_employe`, `id_user`) VALUES
(17, 52),
(19, 55);

-- --------------------------------------------------------

--
-- Structure de la table `historique`
--

CREATE TABLE `historique` (
  `id_historique` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `date_action` datetime DEFAULT current_timestamp(),
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `notification`
--

CREATE TABLE `notification` (
  `id_notif` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `statut` enum('non_lu','lu') DEFAULT 'non_lu',
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `poste`
--

CREATE TABLE `poste` (
  `id_poste` int(11) NOT NULL,
  `nom_poste` varchar(50) NOT NULL,
  `departement` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `rh`
--

CREATE TABLE `rh` (
  `id_rh` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `fonction` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `rh`
--

INSERT INTO `rh` (`id_rh`, `id_user`, `fonction`) VALUES
(15, 51, NULL),
(16, 53, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_user` int(11) NOT NULL,
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
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_user`, `nom`, `prenom`, `tel`, `email`, `mot_de_passe`, `role`, `actif`, `date_creation`) VALUES
(44, 'giress', 'arnold', '687146628', 'giressarnold@gmail.com', '12345', 'ADMIN', 1, '2026-02-24 02:38:57'),
(47, 'giress', 'arnold', '', 'arnold@gmail.com', 'giress', 'ADMIN', 1, '2026-02-24 02:42:16'),
(50, 'grace ', 'medom', '678987652', 'grace@gmail.com', 'giress', 'ADMIN', 1, '2026-02-24 02:46:46'),
(51, 'etekin', 'loic', '678987345', 'loic@gmail.com', 'rh', 'EMPLOYE', 1, '2026-02-24 02:48:22'),
(52, 'ezechiel', 'paul', '678987654', 'ezechiel@gmail.com', 'papou', 'EMPLOYE', 1, '2026-02-24 02:50:20'),
(53, 'delia', 'djouela', '693055530', 'djoueladelia@gmail.com', 'doma237', 'RH', 1, '2026-02-27 08:34:14'),
(55, 'arnold', 'azerty', '654321798', 'qwerty@gmail.com', '123456789', 'EMPLOYE', 1, '2026-03-11 12:36:01');

-- --------------------------------------------------------

--
-- Structure de la table `validation`
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
-- Index pour les tables déchargées
--

--
-- Index pour la table `administrateur`
--
ALTER TABLE `administrateur`
  ADD PRIMARY KEY (`id_admin`),
  ADD KEY `id_user` (`id_user`);

--
-- Index pour la table `demande`
--
ALTER TABLE `demande`
  ADD PRIMARY KEY (`id_demande`),
  ADD KEY `id_employe` (`id_employe`);

--
-- Index pour la table `employe`
--
ALTER TABLE `employe`
  ADD PRIMARY KEY (`id_employe`),
  ADD KEY `id_user` (`id_user`);

--
-- Index pour la table `historique`
--
ALTER TABLE `historique`
  ADD PRIMARY KEY (`id_historique`),
  ADD KEY `id_user` (`id_user`);

--
-- Index pour la table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id_notif`),
  ADD KEY `id_user` (`id_user`);

--
-- Index pour la table `poste`
--
ALTER TABLE `poste`
  ADD PRIMARY KEY (`id_poste`);

--
-- Index pour la table `rh`
--
ALTER TABLE `rh`
  ADD PRIMARY KEY (`id_rh`),
  ADD KEY `id_user` (`id_user`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `validation`
--
ALTER TABLE `validation`
  ADD PRIMARY KEY (`id_validation`),
  ADD KEY `id_rh` (`id_rh`),
  ADD KEY `id_demande` (`id_demande`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `administrateur`
--
ALTER TABLE `administrateur`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `demande`
--
ALTER TABLE `demande`
  MODIFY `id_demande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `employe`
--
ALTER TABLE `employe`
  MODIFY `id_employe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `historique`
--
ALTER TABLE `historique`
  MODIFY `id_historique` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `notification`
--
ALTER TABLE `notification`
  MODIFY `id_notif` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `poste`
--
ALTER TABLE `poste`
  MODIFY `id_poste` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rh`
--
ALTER TABLE `rh`
  MODIFY `id_rh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT pour la table `validation`
--
ALTER TABLE `validation`
  MODIFY `id_validation` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `administrateur`
--
ALTER TABLE `administrateur`
  ADD CONSTRAINT `administrateur_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id_user`) ON DELETE CASCADE;

--
-- Contraintes pour la table `demande`
--
ALTER TABLE `demande`
  ADD CONSTRAINT `demande_ibfk_1` FOREIGN KEY (`id_employe`) REFERENCES `employe` (`id_employe`) ON DELETE CASCADE;

--
-- Contraintes pour la table `employe`
--
ALTER TABLE `employe`
  ADD CONSTRAINT `employe_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id_user`) ON DELETE CASCADE;

--
-- Contraintes pour la table `historique`
--
ALTER TABLE `historique`
  ADD CONSTRAINT `historique_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id_user`) ON DELETE CASCADE;

--
-- Contraintes pour la table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id_user`);

--
-- Contraintes pour la table `rh`
--
ALTER TABLE `rh`
  ADD CONSTRAINT `rh_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id_user`) ON DELETE CASCADE;

--
-- Contraintes pour la table `validation`
--
ALTER TABLE `validation`
  ADD CONSTRAINT `validation_ibfk_1` FOREIGN KEY (`id_rh`) REFERENCES `rh` (`id_rh`) ON DELETE CASCADE,
  ADD CONSTRAINT `validation_ibfk_2` FOREIGN KEY (`id_demande`) REFERENCES `demande` (`id_demande`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
