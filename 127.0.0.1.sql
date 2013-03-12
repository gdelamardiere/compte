-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Client: 127.0.0.1
-- Généré le : Mar 12 Mars 2013 à 17:11
-- Version du serveur: 5.5.16
-- Version de PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `comptes`
--
DROP DATABASE `comptes`;
CREATE DATABASE `comptes` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `comptes`;

DELIMITER $$
--
-- Procédures
--
DROP PROCEDURE IF EXISTS `update_releve_detail`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_releve_detail`()
BEGIN
       update releve_detail rd  set rd.id_cat=
              (select k.id_cat
               from keywords k 
               where rd.libelle REGEXP k.value
               limit 1)
        where rd.id_cat is null ;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `keywords`
--

DROP TABLE IF EXISTS `keywords`;
CREATE TABLE IF NOT EXISTS `keywords` (
  `id_keywords` int(11) NOT NULL AUTO_INCREMENT,
  `id_cat` int(11) DEFAULT NULL,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY (`id_keywords`),
  KEY `id_cat` (`id_cat`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

--
-- Contenu de la table `keywords`
--

INSERT INTO `keywords` (`id_keywords`, `id_cat`, `value`) VALUES
(2, 1, 'CASINO'),
(3, 5, 'SNCF'),
(4, 6, 'GAUMONT'),
(5, 6, 'LES ENFANTS DU CHARTRES '),
(6, 1, 'MONOP'),
(7, 2, 'TELEPHONE'),
(8, 2, 'NUMERICABLE'),
(9, 4, 'COMMISSIONS'),
(10, 2, 'SFR'),
(11, 15, 'EDF'),
(12, 1, 'UNIMAG'),
(13, 10, 'MUTUELLE MOTARD'),
(14, 1, 'GEANT'),
(15, 3, ' ESSFLOREALY'),
(16, 1, 'PROXI'),
(17, 9, 'ECHEANCE PRET'),
(18, 1, 'G 20'),
(19, 16, '5 A SEC'),
(20, 6, 'UGC'),
(21, 1, 'SUBWAY'),
(22, 3, 'STATION AVIA'),
(23, 17, 'LE PEL'),
(24, 11, 'RESOURCES FRANCE'),
(25, 1, 'CARREFOUR'),
(26, 1, 'MCDONALDS'),
(27, 1, 'FRANPRIX'),
(28, 1, 'MAC DO'),
(29, 14, 'RATP'),
(30, 8, 'RETRAIT DAB'),
(31, 18, 'VIR CPTE A CPTE'),
(32, 3, 'SUPER MOZART'),
(33, 5, 'SNC'),
(34, 7, 'PERMANENT LOYER'),
(35, 1, 'HUIT A 8'),
(36, 2, 'DEBITEL'),
(37, 1, 'GALOPINS'),
(38, 1, 'CARREF'),
(39, 1, 'G20'),
(40, 1, 'CAFE'),
(41, 1, 'ED MOZART'),
(42, 3, 'ESSO');

-- --------------------------------------------------------

--
-- Structure de la table `liste_cat`
--

DROP TABLE IF EXISTS `liste_cat`;
CREATE TABLE IF NOT EXISTS `liste_cat` (
  `id_cat` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) NOT NULL,
  PRIMARY KEY (`id_cat`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Contenu de la table `liste_cat`
--

INSERT INTO `liste_cat` (`id_cat`, `libelle`) VALUES
(1, 'nouriture'),
(2, 'telephone + internet'),
(3, 'essence'),
(4, 'frais banquaire'),
(5, 'sncf'),
(6, 'cinema'),
(7, 'loyer'),
(8, 'liquide'),
(9, 'pret'),
(10, 'assurance'),
(11, 'salaire'),
(12, 'divers'),
(13, 'impot + amende'),
(14, 'ratp'),
(15, 'edf'),
(16, 'pressing'),
(17, 'PEL'),
(18, 'virement compte a compte');

-- --------------------------------------------------------

--
-- Structure de la table `operations`
--

DROP TABLE IF EXISTS `operations`;
CREATE TABLE IF NOT EXISTS `operations` (
  `id_operations` int(11) NOT NULL AUTO_INCREMENT,
  `nom_operations` varchar(50) NOT NULL,
  PRIMARY KEY (`id_operations`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `operations`
--

INSERT INTO `operations` (`id_operations`, `nom_operations`) VALUES
(1, 'virement'),
(2, 'cb'),
(3, 'cheque'),
(4, 'espece'),
(5, 'prelevement'),
(6, 'frais banquaire'),
(7, 'pret');

-- --------------------------------------------------------

--
-- Structure de la table `regex_replace`
--

DROP TABLE IF EXISTS `regex_replace`;
CREATE TABLE IF NOT EXISTS `regex_replace` (
  `id_keywords` int(11) NOT NULL AUTO_INCREMENT,
  `regex` varchar(50) NOT NULL,
  `replace` varchar(50) NOT NULL,
  `ordre` int(11) NOT NULL,
  `id_operations` int(20) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_keywords`),
  KEY `id_operations` (`id_operations`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Contenu de la table `regex_replace`
--

INSERT INTO `regex_replace` (`id_keywords`, `regex`, `replace`, `ordre`, `id_operations`, `type`) VALUES
(1, '#(VIR[^R]+RECU)#', '/explode/$1', 4, 1, 'CREDIT'),
(2, '#(VIR[^0-9^]+EMIS)#', '/explode/$1', 5, 1, 'DEBIT'),
(4, '#(DU [0-9]{6})#', '/explode/$1', 6, 2, 'DEBIT'),
(5, '#(PRELEVEMENT)#', '/explode/$1', 7, 5, 'DEBIT'),
(6, '#(RETRAIT DAB)#', '/explode/$1', 8, 4, 'DEBIT'),
(7, '#(COMMISSIONS)#', '/explode/$1', 9, 6, 'DEBIT'),
(9, '#(CHEQUE [0-9]{6,})#', '/explode/$1', 10, 3, 'DEBIT'),
(10, '#FACTURE[^DU]* CARTE[^DU]+ #', '', 3, NULL, NULL),
(11, '#^/explode/(.+)$#', '$1', 11, NULL, NULL),
(12, '#[^a-zA-Z0-9_ .,\\s]#', '', 1, NULL, NULL),
(13, '#[\\s]#', ' ', 2, NULL, NULL),
(14, '#(ECHEANCE PRET [0-9]{5,})#', '/explode/$1', 12, 7, 'DEBIT'),
(15, '#(REMISE[^0-9]*[0-9]{6,})#', '/explode/$1', 10, 3, 'CREDIT'),
(16, '#(VIR[^0-9^]+FAVEUR TIERS)#', '/explode/$1', 5, 1, 'DEBIT');

-- --------------------------------------------------------

--
-- Structure de la table `releve`
--

DROP TABLE IF EXISTS `releve`;
CREATE TABLE IF NOT EXISTS `releve` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mois_releve` int(11) NOT NULL,
  `annee_releve` int(11) NOT NULL,
  `date_crea` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `montant_debut` double(10,2) NOT NULL,
  `montant_fin` double(10,2) NOT NULL,
  `fichier` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `releve_detail`
--

DROP TABLE IF EXISTS `releve_detail`;
CREATE TABLE IF NOT EXISTS `releve_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(200) NOT NULL,
  `montant` double(10,2) NOT NULL,
  `type` varchar(20) NOT NULL,
  `id_operations` int(11) DEFAULT NULL,
  `id_cat` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `date_crea` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modif` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `id_releve` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_releve` (`id_releve`),
  KEY `id_cat` (`id_cat`),
  KEY `id_operations` (`id_operations`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Déclencheurs `releve_detail`
--
DROP TRIGGER IF EXISTS `releve_detail_update`;
DELIMITER //
CREATE TRIGGER `releve_detail_update` BEFORE UPDATE ON `releve_detail`
 FOR EACH ROW BEGIN
SET NEW.`date_modif`= NOW();
END
//
DELIMITER ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `keywords`
--
ALTER TABLE `keywords`
  ADD CONSTRAINT `keywords_ibfk_1` FOREIGN KEY (`id_cat`) REFERENCES `liste_cat` (`id_cat`) ON DELETE CASCADE;

--
-- Contraintes pour la table `releve_detail`
--
ALTER TABLE `releve_detail`
  ADD CONSTRAINT `releve_detail_ibfk_3` FOREIGN KEY (`id_releve`) REFERENCES `releve` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `releve_detail_ibfk_1` FOREIGN KEY (`id_operations`) REFERENCES `operations` (`id_operations`),
  ADD CONSTRAINT `releve_detail_ibfk_2` FOREIGN KEY (`id_cat`) REFERENCES `liste_cat` (`id_cat`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
