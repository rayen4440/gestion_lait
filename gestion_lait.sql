-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 04 oct. 2025 à 13:04
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_lait`
--

-- --------------------------------------------------------

--
-- Structure de la table `alimentation`
--

DROP TABLE IF EXISTS `alimentation`;
CREATE TABLE IF NOT EXISTS `alimentation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type_aliment` varchar(50) NOT NULL,
  `quantite` varchar(50) NOT NULL,
  `date_achat` date NOT NULL,
  `fournisseur` varchar(50) NOT NULL,
  `fournisseur_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `alimentation`
--

INSERT INTO `alimentation` (`id`, `type_aliment`, `quantite`, `date_achat`, `fournisseur`, `fournisseur_id`) VALUES
(3, 'alfa +', '2000', '2025-09-06', '', 2),
(4, 'alfa', '6000', '2025-09-12', '', 3),
(5, 'alfaa +', '7000', '2025-09-16', '', 2),
(6, 'alfa', '5700', '2025-09-15', '', 4);

-- --------------------------------------------------------

--
-- Structure de la table `employes`
--

DROP TABLE IF EXISTS `employes`;
CREATE TABLE IF NOT EXISTS `employes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `poste` varchar(50) NOT NULL,
  `salaire_base` varchar(10) NOT NULL,
  `cnss_taux` decimal(5,0) NOT NULL,
  `date` date NOT NULL,
  `date_paiement` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `employes`
--

INSERT INTO `employes` (`id`, `nom`, `prenom`, `poste`, `salaire_base`, `cnss_taux`, `date`, `date_paiement`) VALUES
(4, 'exp', 'exp', 'agent', '2000', 0, '0000-00-00', '0000-00-00'),
(3, 'Rayen', 'guezmir', 'agent', '1500', 0, '0000-00-00', '0000-00-00'),
(7, 'saami', 'gs', 'directure financier', '3000', 0, '0000-00-00', '0000-00-00'),
(6, 'nada', 'belhadj', 'directeur', '2500', 0, '0000-00-00', '0000-00-00'),
(8, 'Ramzi', 'torki', 'securité', '950', 0, '0000-00-00', '0000-00-00');

-- --------------------------------------------------------

--
-- Structure de la table `fournisseur`
--

DROP TABLE IF EXISTS `fournisseur`;
CREATE TABLE IF NOT EXISTS `fournisseur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `telephone` varchar(8) NOT NULL,
  `email` varchar(255) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `date_ajout` tinyint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `fournisseur`
--

INSERT INTO `fournisseur` (`id`, `nom`, `telephone`, `email`, `adresse`, `date_ajout`) VALUES
(2, 'ali', '55777111', 'ali@gami.com', 'tunisie', 0),
(3, 'Fat7i', '58719447', 'guezmir44@gmail.com', 'tunisie', 0),
(4, 'sara', '56871569', 'sara@trainingpro.com', 'sousse', 0),
(5, 'nada', '22116655', 'nada@formation.com', 'ariana', 0),
(6, 'guezmir Rayen', '58719877', 'guezmirr44@gmail.com', 'tunisie', 0);

-- --------------------------------------------------------

--
-- Structure de la table `lait`
--

DROP TABLE IF EXISTS `lait`;
CREATE TABLE IF NOT EXISTS `lait` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date_lait` date NOT NULL,
  `quantite` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `lait`
--

INSERT INTO `lait` (`id`, `date_lait`, `quantite`) VALUES
(1, '2025-09-02', '100'),
(2, '2025-09-04', '1500'),
(3, '2025-09-05', '1500'),
(4, '2025-09-13', '5000'),
(5, '2025-09-01', '4744'),
(6, '2025-09-30', '5400'),
(7, '2025-09-15', '4852'),
(8, '2025-09-02', '4700');

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

DROP TABLE IF EXISTS `paiement`;
CREATE TABLE IF NOT EXISTS `paiement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employe_id` int NOT NULL,
  `mois` date NOT NULL,
  `salaire_net` decimal(10,0) NOT NULL,
  `cnss` varchar(50) NOT NULL,
  `jours_absence` int NOT NULL,
  `salaire_brut` decimal(10,0) NOT NULL,
  `jours_presence` int NOT NULL,
  `salaire_base` decimal(10,0) NOT NULL,
  `montant` decimal(10,0) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `paiement`
--

INSERT INTO `paiement` (`id`, `employe_id`, `mois`, `salaire_net`, `cnss`, `jours_absence`, `salaire_brut`, `jours_presence`, `salaire_base`, `montant`) VALUES
(1, 2, '2025-09-01', 906, '94', 0, 0, 1, 0, 0),
(28, 3, '2025-08-01', 1314, '136.3', 1, 0, 0, 0, 0),
(29, 5, '2025-09-01', 4530, '470', 1, 0, 0, 0, 0),
(30, 4, '2025-09-01', 1752, '181.73', 1, 0, 0, 0, 0),
(31, 7, '2025-09-01', 2718, '282', 0, 0, 1, 0, 0),
(32, 6, '2025-09-01', 2265, '235', 0, 0, 1, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `presence`
--

DROP TABLE IF EXISTS `presence`;
CREATE TABLE IF NOT EXISTS `presence` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employe_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `date` date NOT NULL,
  `statut` varchar(50) NOT NULL,
  `date_absence` date NOT NULL,
  `date_presence` date NOT NULL,
  `mois` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `presence`
--

INSERT INTO `presence` (`id`, `employe_id`, `date`, `statut`, `date_absence`, `date_presence`, `mois`) VALUES
(1, '2', '2025-09-02', 'present', '0000-00-00', '0000-00-00', '0000-00-00'),
(5, '6', '2025-09-30', 'present', '0000-00-00', '0000-00-00', '0000-00-00'),
(7, '7', '2025-09-30', 'present', '0000-00-00', '0000-00-00', '0000-00-00'),
(4, '4', '2025-09-30', 'absent', '0000-00-00', '0000-00-00', '0000-00-00'),
(6, '3', '2025-09-30', 'present', '0000-00-00', '0000-00-00', '0000-00-00');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `mdp` varchar(100) NOT NULL,
  `username` varchar(255) NOT NULL,
  `pref_lang` varchar(10) NOT NULL,
  `pref_dark` tinyint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `login`, `mdp`, `username`, `pref_lang`, `pref_dark`) VALUES
(1, 'Rayen', '123', 'Rayen gz', '', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
