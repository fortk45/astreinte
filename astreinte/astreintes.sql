-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 01 mars 2023 à 13:03
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `astreintes`
--

-- --------------------------------------------------------

--
-- Structure de la table `concerner`
--

DROP TABLE IF EXISTS `concerner`;
CREATE TABLE IF NOT EXISTS `concerner` (
  `id_interv` int NOT NULL,
  `id_elu` int NOT NULL,
  PRIMARY KEY (`id_interv`,`id_elu`),
  KEY `concerner_Elu0_FK` (`id_elu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `demandeur`
--

DROP TABLE IF EXISTS `demandeur`;
CREATE TABLE IF NOT EXISTS `demandeur` (
  `id_demandeur` int NOT NULL AUTO_INCREMENT,
  `nom_demandeur` varchar(255) NOT NULL,
  PRIMARY KEY (`id_demandeur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `effectue_sur`
--

DROP TABLE IF EXISTS `effectue_sur`;
CREATE TABLE IF NOT EXISTS `effectue_sur` (
  `id_sujet` int NOT NULL,
  `id_interv` int NOT NULL,
  PRIMARY KEY (`id_sujet`,`id_interv`),
  KEY `effectue_sur_Intervention0_FK` (`id_interv`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `elu`
--

DROP TABLE IF EXISTS `elu`;
CREATE TABLE IF NOT EXISTS `elu` (
  `id_elu` int NOT NULL AUTO_INCREMENT,
  `nom_elu` varchar(80) NOT NULL,
  `prenom_elu` varchar(80) NOT NULL,
  `genre_elu` varchar(10) NOT NULL,
  PRIMARY KEY (`id_elu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `intervention`
--

DROP TABLE IF EXISTS `intervention`;
CREATE TABLE IF NOT EXISTS `intervention` (
  `id_interv` int NOT NULL AUTO_INCREMENT,
  `date_appel` date NOT NULL,
  `heure_appel` time NOT NULL,
  `lieu_appel` varchar(255) NOT NULL,
  `motif_appel` varchar(255) NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `observations_interv` longtext,
  `id_demandeur` int NOT NULL,
  `id_preferences` int NOT NULL COMMENT 'l''id du cadre',
  PRIMARY KEY (`id_interv`),
  KEY `Intervention_demandeur_FK` (`id_demandeur`),
  KEY `Intervention_preferences0_FK` (`id_preferences`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `preferences`
--

DROP TABLE IF EXISTS `preferences`;
CREATE TABLE IF NOT EXISTS `preferences` (
  `id_preferences` int NOT NULL AUTO_INCREMENT,
  `samaccountname` varchar(150) NOT NULL,
  `prenom` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `nom` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `display_iframe` tinyint(1) DEFAULT '1',
  `display_empty` tinyint(1) DEFAULT '1',
  `display_admFunction` tinyint DEFAULT '0',
  `categorie` enum('agent','elu') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`id_preferences`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `preferences`
--

INSERT INTO `preferences` (`id_preferences`, `samaccountname`, `prenom`, `nom`, `display_iframe`, `display_empty`, `display_admFunction`, `categorie`) VALUES
(10, 'fort', 'Kevin', 'FORT', 1, 1, 1, 'agent');

-- --------------------------------------------------------

--
-- Structure de la table `sujet`
--

DROP TABLE IF EXISTS `sujet`;
CREATE TABLE IF NOT EXISTS `sujet` (
  `id_sujet` int NOT NULL AUTO_INCREMENT,
  `libelle_sujet` varchar(255) NOT NULL,
  PRIMARY KEY (`id_sujet`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `concerner`
--
ALTER TABLE `concerner`
  ADD CONSTRAINT `concerner_Elu0_FK` FOREIGN KEY (`id_elu`) REFERENCES `elu` (`id_elu`),
  ADD CONSTRAINT `concerner_Intervention_FK` FOREIGN KEY (`id_interv`) REFERENCES `intervention` (`id_interv`);

--
-- Contraintes pour la table `effectue_sur`
--
ALTER TABLE `effectue_sur`
  ADD CONSTRAINT `effectue_sur_Intervention0_FK` FOREIGN KEY (`id_interv`) REFERENCES `intervention` (`id_interv`),
  ADD CONSTRAINT `effectue_sur_Sujet_FK` FOREIGN KEY (`id_sujet`) REFERENCES `sujet` (`id_sujet`);

--
-- Contraintes pour la table `intervention`
--
ALTER TABLE `intervention`
  ADD CONSTRAINT `Intervention_demandeur_FK` FOREIGN KEY (`id_demandeur`) REFERENCES `demandeur` (`id_demandeur`),
  ADD CONSTRAINT `Intervention_preferences0_FK` FOREIGN KEY (`id_preferences`) REFERENCES `preferences` (`id_preferences`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
