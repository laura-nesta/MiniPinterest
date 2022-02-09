-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : ven. 07 mai 2021 à 19:33
-- Version du serveur :  10.3.25-MariaDB-0ubuntu0.20.04.1-log
-- Version de PHP : 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `p1812594`
--

-- --------------------------------------------------------

--
-- Structure de la table `Categorie`
--

CREATE TABLE `Categorie` (
  `catId` int(11) NOT NULL,
  `nomCat` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `Categorie`
--

INSERT INTO `Categorie` (`catId`, `nomCat`) VALUES
(1, 'Paysage'),
(2, 'Nouriture'),
(3, 'Animaux'),
(4, 'Personne'),
(5, 'Film & Série'),
(6, 'Dessin & Art');

-- --------------------------------------------------------

--
-- Structure de la table `Photo`
--

CREATE TABLE `Photo` (
  `photoId` int(11) NOT NULL,
  `nomFich` varchar(250) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `catId` int(11) NOT NULL,
  `extension` varchar(250) NOT NULL,
  `auteur` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `Photo`
--

INSERT INTO `Photo` (`photoId`, `nomFich`, `description`, `catId`, `extension`, `auteur`) VALUES
(6, 'Arthur', 'le roi Arthur dans Kaamelott', 5, 'jpg', 'alchimiste'),
(7, 'sushis-1024x678', 'des sushis', 2, 'jpg', 'alchimiste'),
(8, 'resin', 'galaxie', 6, 'jpg', 'gandalf'),
(10, 'index', 'deux pandas', 3, 'jpeg', 'harryPotter'),
(11, 'vances', 'vacances ente amis', 4, 'jpg', 'gandalf');

-- --------------------------------------------------------

--
-- Structure de la table `Utilisateur`
--

CREATE TABLE `Utilisateur` (
  `pseudo` varchar(250) NOT NULL,
  `mdp` varchar(250) NOT NULL,
  `statut` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `Utilisateur`
--

INSERT INTO `Utilisateur` (`pseudo`, `mdp`, `statut`) VALUES
('alchimiste', 'aa36dc6e81e2ac7ad03e12fedcb6a2c0', 'admin'),
('gandalf', 'aa36dc6e81e2ac7ad03e12fedcb6a2c0', 'utilisateur'),
('harryPotter', 'aa36dc6e81e2ac7ad03e12fedcb6a2c0', 'utilisateur');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Categorie`
--
ALTER TABLE `Categorie`
  ADD PRIMARY KEY (`catId`);

--
-- Index pour la table `Photo`
--
ALTER TABLE `Photo`
  ADD PRIMARY KEY (`photoId`),
  ADD KEY `catId` (`catId`);

--
-- Index pour la table `Utilisateur`
--
ALTER TABLE `Utilisateur`
  ADD PRIMARY KEY (`pseudo`),
  ADD UNIQUE KEY `pseudo` (`pseudo`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Photo`
--
ALTER TABLE `Photo`
  MODIFY `photoId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Photo`
--
ALTER TABLE `Photo`
  ADD CONSTRAINT `Photo_ibfk_1` FOREIGN KEY (`catId`) REFERENCES `Categorie` (`catId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
