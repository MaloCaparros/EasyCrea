-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 14 oct. 2024 à 12:29
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `easycrea`
--

-- --------------------------------------------------------

--
-- Structure de la table `administrateur`
--

CREATE TABLE `administrateur` (
  `id_administrateur` int(11) NOT NULL,
  `ad_mail_admin` text NOT NULL,
  `mdp_admin` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `carte`
--

CREATE TABLE `carte` (
  `id_carte` int(11) NOT NULL,
  `texte_carte` varchar(280) NOT NULL,
  `valeurs_choix1` float NOT NULL,
  `valeurs_choix2` float NOT NULL,
  `date_soumission` date NOT NULL,
  `ordre_soumission` int(11) NOT NULL,
  `id_deck` int(11) NOT NULL,
  `id_createur` int(11) DEFAULT NULL,
  `id_administrateur` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `carte aléatoire`
--

CREATE TABLE `carte aléatoire` (
  `num_carte` int(11) NOT NULL,
  `id_carte` int(11) NOT NULL,
  `id_deck` int(11) NOT NULL,
  `id_createur` int(11) NOT NULL,
  `id_administrateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `createur`
--

CREATE TABLE `createur` (
  `id_createur` int(11) NOT NULL,
  `nom_createur` text NOT NULL,
  `ad_mail_createur` text NOT NULL,
  `mdp_createur` text NOT NULL,
  `genre` text NOT NULL,
  `ddn` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `deck`
--

CREATE TABLE `deck` (
  `id_deck` int(11) NOT NULL,
  `titre_deck` text NOT NULL,
  `date_debut_deck` datetime NOT NULL,
  `date_fin_deck` datetime NOT NULL,
  `nb_cartes` int(11) NOT NULL,
  `nb_jaime` int(11) NOT NULL,
  `id_administrateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `administrateur`
--
ALTER TABLE `administrateur`
  ADD PRIMARY KEY (`id_administrateur`);

--
-- Index pour la table `carte`
--
ALTER TABLE `carte`
  ADD PRIMARY KEY (`id_carte`),
  ADD KEY `fk_deck` (`id_deck`),
  ADD KEY `fk_carte_createur` (`id_createur`),
  ADD KEY `fk_admin_carte` (`id_administrateur`);

--
-- Index pour la table `carte aléatoire`
--
ALTER TABLE `carte aléatoire`
  ADD KEY `fk_carte` (`id_carte`),
  ADD KEY `fk_createur` (`id_createur`),
  ADD KEY `fk_deck_random` (`id_deck`),
  ADD KEY `fk_admin_random` (`id_administrateur`);

--
-- Index pour la table `createur`
--
ALTER TABLE `createur`
  ADD PRIMARY KEY (`id_createur`);

--
-- Index pour la table `deck`
--
ALTER TABLE `deck`
  ADD PRIMARY KEY (`id_deck`),
  ADD KEY `fk_administrateur` (`id_administrateur`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `administrateur`
--
ALTER TABLE `administrateur`
  MODIFY `id_administrateur` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `carte`
--
ALTER TABLE `carte`
  MODIFY `id_carte` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `createur`
--
ALTER TABLE `createur`
  MODIFY `id_createur` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `deck`
--
ALTER TABLE `deck`
  MODIFY `id_deck` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `carte`
--
ALTER TABLE `carte`
  ADD CONSTRAINT `fk_admin_carte` FOREIGN KEY (`id_administrateur`) REFERENCES `administrateur` (`id_administrateur`),
  ADD CONSTRAINT `fk_carte_createur` FOREIGN KEY (`id_createur`) REFERENCES `createur` (`id_createur`),
  ADD CONSTRAINT `fk_deck` FOREIGN KEY (`id_deck`) REFERENCES `deck` (`id_deck`);

--
-- Contraintes pour la table `carte aléatoire`
--
ALTER TABLE `carte aléatoire`
  ADD CONSTRAINT `fk_admin_random` FOREIGN KEY (`id_administrateur`) REFERENCES `administrateur` (`id_administrateur`),
  ADD CONSTRAINT `fk_carte` FOREIGN KEY (`id_carte`) REFERENCES `carte` (`id_carte`),
  ADD CONSTRAINT `fk_createur` FOREIGN KEY (`id_createur`) REFERENCES `carte` (`id_carte`),
  ADD CONSTRAINT `fk_deck_random` FOREIGN KEY (`id_deck`) REFERENCES `deck` (`id_deck`);

--
-- Contraintes pour la table `deck`
--
ALTER TABLE `deck`
  ADD CONSTRAINT `fk_administrateur` FOREIGN KEY (`id_administrateur`) REFERENCES `administrateur` (`id_administrateur`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
