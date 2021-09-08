-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 22 mai 2021 à 19:52
-- Version du serveur :  10.4.13-MariaDB
-- Version de PHP : 7.4.8

--
-- Base de données : `trello_db`
--

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

DROP DATABASE IF EXISTS `trello_db`;
CREATE DATABASE IF NOT EXISTS `trello_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `trello_db`;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Structure de la table `board`
--

CREATE TABLE `board` (
  `ID` int(11) NOT NULL,
  `Title` varchar(128) NOT NULL,
  `Owner` int(11) NOT NULL,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `ModifiedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `board`
--

INSERT INTO `board` (`ID`, `Title`, `Owner`, `CreatedAt`, `ModifiedAt`) VALUES
(1, 'Projet PRWB', 1, '2020-10-11 17:48:59', NULL),
(2, 'Projet ANC3', 3, '2020-10-11 17:48:59', NULL),
(4, 'Boulot', 5, '2020-11-25 18:54:53', NULL),
(5, 'Collabs', 1, '2021-05-22 17:03:01', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `card`
--

CREATE TABLE `card` (
  `ID` int(11) NOT NULL,
  `Title` varchar(128) NOT NULL,
  `Body` text NOT NULL,
  `Position` int(11) NOT NULL DEFAULT 0,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `ModifiedAt` datetime DEFAULT NULL,
  `Author` int(11) NOT NULL,
  `Column` int(11) NOT NULL,
  `DueDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `card`
--

INSERT INTO `card` (`ID`, `Title`, `Body`, `Position`, `CreatedAt`, `ModifiedAt`, `Author`, `Column`, `DueDate`) VALUES
(1, 'Analyse conceptuelle', 'Faire l\'analyse conceptuelle de la base de données du projet.', 1, '2020-10-11 17:56:40', '2020-11-27 13:07:39', 2, 3, NULL),
(2, 'Mockups itération 1', 'Faire des prototypes d\'écrans pour les fonctionnalités de la première itération.', 0, '2020-10-11 17:56:40', '2020-11-27 13:07:40', 1, 2, NULL),
(3, 'Ecrire énoncé itération 1.', '', 1, '2020-10-11 17:58:37', '2020-11-27 13:07:42', 4, 2, '2021-01-01'),
(4, 'Echéances IT1 !', 'Décider des dates d\'échéance pour la première itération.', 0, '2020-10-11 17:58:37', '2020-11-27 13:07:34', 1, 3, NULL),
(6, 'Enoncé itération 2', '', 0, '2020-11-27 13:07:54', NULL, 5, 1, NULL),
(7, 'un peu de collab', '', 0, '2021-05-22 17:03:23', '2021-05-22 17:04:46', 1, 16, '2021-05-29'),
(8, 'des collabs', '', 1, '2021-05-22 17:03:49', '2021-05-22 17:04:36', 1, 16, '2021-05-29'),
(9, 'beaucoup de collabs', '', 2, '2021-05-22 17:04:04', '2021-05-22 17:04:27', 1, 16, '2021-05-29');

-- --------------------------------------------------------

--
-- Structure de la table `collaborate`
--

CREATE TABLE `collaborate` (
  `Board` int(11) NOT NULL,
  `Collaborator` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `collaborate`
--

INSERT INTO `collaborate` (`Board`, `Collaborator`) VALUES
(1, 2),
(1, 4),
(1, 5),
(2, 1),
(5, 2),
(5, 3),
(5, 4),
(5, 5);

-- --------------------------------------------------------

--
-- Structure de la table `column`
--

CREATE TABLE `column` (
  `ID` int(11) NOT NULL,
  `Title` varchar(128) NOT NULL,
  `Position` int(11) NOT NULL DEFAULT 0,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `ModifiedAt` datetime DEFAULT NULL,
  `Board` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `column`
--

INSERT INTO `column` (`ID`, `Title`, `Position`, `CreatedAt`, `ModifiedAt`, `Board`) VALUES
(1, 'A faire', 0, '2020-10-11 17:51:59', NULL, 1),
(2, 'En cours', 1, '2020-10-11 17:51:59', NULL, 1),
(3, 'Terminé', 2, '2020-10-11 17:52:27', NULL, 1),
(4, 'A faire', 0, '2020-10-11 17:52:27', NULL, 2),
(5, 'Fini', 1, '2020-10-11 17:53:07', NULL, 2),
(6, 'Abandonné', 2, '2020-10-11 17:53:07', NULL, 2),
(11, 'Pas urgent...', 0, '2020-11-25 18:55:06', NULL, 4),
(12, 'Ne pas perdre de vue', 1, '2020-11-25 18:55:17', NULL, 4),
(13, 'Pour hier', 2, '2020-11-25 18:55:32', NULL, 4),
(15, 'Trop tard', 3, '2020-11-25 18:56:11', NULL, 4),
(16, 'Colonne', 0, '2021-05-22 17:03:18', NULL, 5);

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `ID` int(11) NOT NULL,
  `Body` text NOT NULL,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `ModifiedAt` datetime DEFAULT NULL,
  `Author` int(11) NOT NULL,
  `Card` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`ID`, `Body`, `CreatedAt`, `ModifiedAt`, `Author`, `Card`) VALUES
(1, 'Ceci est un commentaire', '2020-11-27 14:45:39', NULL, 5, 6),
(2, 'Voila un autre commentaire', '2020-11-27 14:46:02', NULL, 1, 6);

-- --------------------------------------------------------

--
-- Structure de la table `participate`
--

CREATE TABLE `participate` (
  `Participant` int(11) NOT NULL,
  `Card` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `participate`
--

INSERT INTO `participate` (`Participant`, `Card`) VALUES
(1, 1),
(1, 7),
(1, 8),
(1, 9),
(2, 8),
(2, 9),
(3, 8),
(3, 9),
(4, 9),
(5, 1),
(5, 9),
(6, 9);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `ID` int(11) NOT NULL,
  `Mail` varchar(128) NOT NULL,
  `FullName` varchar(128) NOT NULL,
  `Password` varchar(256) NOT NULL,
  `RegisteredAt` datetime NOT NULL DEFAULT current_timestamp(),
  `Role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`ID`, `Mail`, `FullName`, `Password`, `RegisteredAt`, `Role`) VALUES
(1, 'user1@mail.com', 'User Semel', '56ce92d1de4f05017cf03d6cd514d6d1', '2020-10-11 17:46:19', 'admin'),
(2, 'user2@mail.com', 'User Bis', '56ce92d1de4f05017cf03d6cd514d6d1', '2020-10-11 17:46:19', 'admin'),
(3, 'user3@mail.com', 'User Ter', '56ce92d1de4f05017cf03d6cd514d6d1', '2020-10-11 17:47:20', 'user'),
(4, 'user4@mail.com', 'User Quater', '56ce92d1de4f05017cf03d6cd514d6d1', '2020-10-11 17:47:20', 'admin'),
(5, 'user5@mail.com', 'User Quinquies', '56ce92d1de4f05017cf03d6cd514d6d1', '2020-11-25 18:46:55', 'user'),
(6, 'test@epfc.eu', 'Test Semel', '56ce92d1de4f05017cf03d6cd514d6d1', '2021-05-22 16:59:43', 'user'),
(7, 'test2@epfc.eu', 'Test Bis', '56ce92d1de4f05017cf03d6cd514d6d1', '2021-05-22 17:48:06', 'admin'),
(8, 'test3@epfc.eu', 'Test Ter', '56ce92d1de4f05017cf03d6cd514d6d1', '2021-05-22 17:48:30', 'admin'),
(9, 'test4@epfc.eu', 'Test Quater', '56ce92d1de4f05017cf03d6cd514d6d1', '2021-05-22 17:48:41', 'user');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `board`
--
ALTER TABLE `board`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Title` (`Title`),
  ADD KEY `Owner` (`Owner`);

--
-- Index pour la table `card`
--
ALTER TABLE `card`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Author` (`Author`),
  ADD KEY `Column` (`Column`);

--
-- Index pour la table `collaborate`
--
ALTER TABLE `collaborate`
  ADD PRIMARY KEY (`Board`,`Collaborator`),
  ADD KEY `Collaborator` (`Collaborator`);

--
-- Index pour la table `column`
--
ALTER TABLE `column`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Board` (`Board`);

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Author` (`Author`),
  ADD KEY `Card` (`Card`);

--
-- Index pour la table `participate`
--
ALTER TABLE `participate`
  ADD PRIMARY KEY (`Participant`,`Card`),
  ADD KEY `Card` (`Card`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Mail` (`Mail`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `board`
--
ALTER TABLE `board`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `card`
--
ALTER TABLE `card`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT pour la table `column`
--
ALTER TABLE `column`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `board`
--
ALTER TABLE `board`
  ADD CONSTRAINT `board_ibfk_1` FOREIGN KEY (`Owner`) REFERENCES `user` (`ID`);

--
-- Contraintes pour la table `card`
--
ALTER TABLE `card`
  ADD CONSTRAINT `card_ibfk_1` FOREIGN KEY (`Author`) REFERENCES `user` (`ID`),
  ADD CONSTRAINT `card_ibfk_2` FOREIGN KEY (`Column`) REFERENCES `column` (`ID`);

--
-- Contraintes pour la table `collaborate`
--
ALTER TABLE `collaborate`
  ADD CONSTRAINT `collaborate_ibfk_1` FOREIGN KEY (`Collaborator`) REFERENCES `user` (`ID`),
  ADD CONSTRAINT `collaborate_ibfk_2` FOREIGN KEY (`Board`) REFERENCES `board` (`ID`);

--
-- Contraintes pour la table `column`
--
ALTER TABLE `column`
  ADD CONSTRAINT `column_ibfk_1` FOREIGN KEY (`Board`) REFERENCES `board` (`ID`);

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`Author`) REFERENCES `user` (`ID`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`Card`) REFERENCES `card` (`ID`);

--
-- Contraintes pour la table `participate`
--
ALTER TABLE `participate`
  ADD CONSTRAINT `participate_ibfk_1` FOREIGN KEY (`Card`) REFERENCES `card` (`ID`),
  ADD CONSTRAINT `participate_ibfk_2` FOREIGN KEY (`Participant`) REFERENCES `user` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
