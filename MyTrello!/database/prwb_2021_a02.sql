-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 22 mai 2021 à 19:52
-- Version du serveur :  10.4.13-MariaDB
-- Version de PHP : 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

DROP DATABASE IF EXISTS `prwb_2021_a02`;
CREATE DATABASE IF NOT EXISTS `prwb_2021_a02` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `prwb_2021_a02`;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `prwb_2021_a02`
--

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
(5, 'Collabs', 1, '2021-05-22 17:03:01', NULL),
(6, 'Plein de carte pour calendar', 1, '2021-05-22 17:20:28', NULL);

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
(9, 'beaucoup de collabs', '', 2, '2021-05-22 17:04:04', '2021-05-22 17:04:27', 1, 16, '2021-05-29'),
(10, 'Woody', 'J\'ai un serpent dans ma botte\r\n', 0, '2021-05-22 17:20:46', '2021-05-22 17:31:46', 1, 17, '2021-06-04'),
(11, 'Buzz', 'Vers l\'infini et l\'au-dela.', 1, '2021-05-22 17:20:49', '2021-05-22 17:31:58', 1, 17, '2021-06-11'),
(12, 'Pile-Poil', 'Vif comme le vent Pile-Poil\r\n', 2, '2021-05-22 17:20:55', '2021-05-22 17:32:12', 1, 17, '2021-06-12'),
(13, 'Jessy', '', 3, '2021-05-22 17:21:00', '2021-05-22 17:28:13', 1, 17, '2021-05-26'),
(14, 'Papy Pépite', 'Je danse en boîte.', 0, '2021-05-22 17:21:05', '2021-05-22 17:32:41', 1, 18, '2021-06-18'),
(15, 'Mr patate', '', 1, '2021-05-22 17:21:09', '2021-05-22 17:30:04', 1, 18, '2021-06-26'),
(16, 'Mme patate', '', 2, '2021-05-22 17:21:14', '2021-05-22 17:30:14', 1, 18, '2021-07-09'),
(17, 'Soldat de plomb', 'A vos ordres, Commandant.\r\n', 3, '2021-05-22 17:21:19', '2021-05-22 17:32:27', 1, 18, '2021-07-11'),
(18, 'La bergère', '', 0, '2021-05-22 17:21:24', '2021-05-22 17:30:34', 1, 19, '2021-07-02'),
(19, 'Léodagan', 'Je dis déjà pas merci dans ma langue, je risque pas de l\'apprendre en Picte', 1, '2021-05-22 17:25:15', '2021-05-22 17:33:06', 1, 19, '2021-07-11'),
(20, 'Arthur', 'Je vois que ca bien avancé encore cette semaine', 2, '2021-05-22 17:25:19', '2021-05-22 17:33:40', 1, 19, '2021-07-19'),
(21, 'Venec', 'Moi, je préfère quand même quand c\'est vous le roi.', 3, '2021-05-22 17:25:23', '2021-05-22 17:33:57', 1, 19, '2021-07-27'),
(22, 'Le père Blaise', 'En voila une bonne idée. Faisons la liste des cons, en plus c\'est bien, ca va me faire gratter du papier.', 4, '2021-05-22 17:25:32', '2021-05-22 17:34:28', 1, 19, '2021-07-31'),
(23, 'très long mot', 'yeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeees', 0, '2021-05-22 17:34:40', '2021-05-22 17:34:59', 1, 20, '2021-06-07'),
(24, 'très long texte', 'On la trouvait plutôt jolie, Lily. Elle arrivait des Somalie, Lily. Dans un bateau plein d\'immigrés, qui venaient tous de leur plein gré, vider les poubelles à Paris. Elle croyait qu\'on était égaux, Lily. Au pays de Voltaire et d\'Hugo, Lily. Mais pour Debussy en revanche, il faut deux noires pour une blanche, ca fait un sacré distinguo. Elle aimait tant la liberté, Lily, elle rêvait de fraternité, Lily. Un hôtelier rue Secret-tant, lui a précisé en arrivant qu\'on ne servait que des blanc. Elle a déchargé des cageots, Lily, elle s\'est tapée les sales boulots, Lily, elle crie pour vendre des choux-fleurs, dans la rue ses frères de couleur, l\'accompagne au marteau piqueur. Et quand on l\'appelait blanche -Neige Lily, elle se laissait plus prendre au piège, Lily. Elle trouvait ca très amusant, même s\'il fallait serrer les dents, ils auraient été trop content.', 1, '2021-05-22 17:34:44', '2021-05-22 17:41:23', 1, 20, '2021-06-07'),
(25, 'carte 1', '', 0, '2021-05-22 17:42:08', '2021-05-22 17:43:00', 1, 4, '2021-07-07'),
(26, 'carte 2', '', 1, '2021-05-22 17:42:11', NULL, 1, 4, NULL),
(27, 'carte 3', '', 0, '2021-05-22 17:42:15', '2021-05-22 17:42:38', 1, 5, '2021-05-25'),
(28, 'carte 4', '', 1, '2021-05-22 17:42:18', '2021-05-22 17:42:52', 1, 5, '2021-06-17'),
(29, 'carte 5', '', 0, '2021-05-22 17:42:21', '2021-05-22 17:42:28', 1, 6, '2021-05-22'),
(30, 'un truc', '', 0, '2021-05-22 17:43:26', '2021-05-22 17:45:23', 1, 11, '2021-05-22'),
(31, 'un autre truc', '', 1, '2021-05-22 17:43:30', '2021-05-22 17:45:03', 1, 11, '2021-07-20'),
(32, 'un ptit machin', '', 2, '2021-05-22 17:43:36', '2021-05-22 17:44:52', 1, 11, '2021-07-03'),
(33, 'un ptit zinzin', '', 0, '2021-05-22 17:43:40', '2021-05-22 17:44:06', 1, 12, '2021-05-30'),
(34, 'qui passe par ici', '', 1, '2021-05-22 17:43:44', '2021-05-22 17:44:16', 1, 12, '2021-06-18'),
(35, 'et qui repasse par la', '', 2, '2021-05-22 17:43:49', '2021-05-22 17:44:24', 1, 12, '2021-06-30'),
(36, 'et qui va toucher', '', 3, '2021-05-22 17:43:53', '2021-05-22 17:44:32', 1, 12, '2021-07-05'),
(37, 'les petits machins', '', 4, '2021-05-22 17:43:59', '2021-05-22 17:44:44', 1, 12, '2021-06-20');

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
(5, 5),
(5, 6);

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
(16, 'Colonne', 0, '2021-05-22 17:03:18', NULL, 5),
(17, 'colonne 1', 0, '2021-05-22 17:20:32', NULL, 6),
(18, 'col 2', 1, '2021-05-22 17:20:35', NULL, 6),
(19, 'col3', 2, '2021-05-22 17:20:40', NULL, 6),
(20, 'très long', 3, '2021-05-22 17:34:33', NULL, 6);

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
(2, 'Voilà un autre commentaire', '2020-11-27 14:46:02', NULL, 1, 6);

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
(1, 'boverhaegen@epfc.eu', 'Boris Verhaegen', '56ce92d1de4f05017cf03d6cd514d6d1', '2020-10-11 17:46:19', 'admin'),
(2, 'bepenelle@epfc.eu', 'Benoît Penelle', '56ce92d1de4f05017cf03d6cd514d6d1', '2020-10-11 17:46:19', 'admin'),
(3, 'brlacroix@epfc.eu', 'Bruno Lacroix', '56ce92d1de4f05017cf03d6cd514d6d1', '2020-10-11 17:47:20', 'user'),
(4, 'xapigeolet@epfc.eu', 'aXavier Pigeolet', '56ce92d1de4f05017cf03d6cd514d6d1', '2020-10-11 17:47:20', 'admin'),
(5, 'galagaffe@epfc.eu', 'Gaston Lagaffe', '56ce92d1de4f05017cf03d6cd514d6d1', '2020-11-25 18:46:55', 'user'),
(6, 'test@epfc.eu', 'test', '56ce92d1de4f05017cf03d6cd514d6d1', '2021-05-22 16:59:43', 'user'),
(7, 'test2@epfc.eu', 'testbis', '56ce92d1de4f05017cf03d6cd514d6d1', '2021-05-22 17:48:06', 'admin'),
(8, 'test1@epfc.eu', 'testbisbis', '56ce92d1de4f05017cf03d6cd514d6d1', '2021-05-22 17:48:30', 'admin'),
(9, 'test3@epfc.eu', 'testbisbisbis', '56ce92d1de4f05017cf03d6cd514d6d1', '2021-05-22 17:48:41', 'user');

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
