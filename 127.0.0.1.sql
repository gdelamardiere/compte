-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Client: 127.0.0.1
-- Généré le : Mer 13 Mars 2013 à 16:18
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
DROP DATABASE IF EXISTS `comptes`;
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
(2, 19, 'CASINO'),
(3, 5, 'SNCF'),
(4, 6, 'GAUMONT'),
(5, 6, 'LES ENFANTS DU CHARTRES '),
(6, 19, 'MONOP'),
(7, 2, 'TELEPHONE'),
(8, 2, 'NUMERICABLE'),
(9, 4, 'COMMISSIONS'),
(10, 2, 'SFR'),
(11, 15, 'EDF'),
(12, 19, 'UNIMAG'),
(13, 10, 'MUTUELLE MOTARD'),
(14, 19, 'GEANT'),
(15, 3, ' ESSFLOREALY'),
(16, 19, 'PROXI'),
(17, 9, 'ECHEANCE PRET'),
(18, 19, 'G 20'),
(19, 16, '5 A SEC'),
(20, 6, 'UGC'),
(21, 19, 'SUBWAY'),
(22, 3, 'STATION AVIA'),
(23, 17, 'LE PEL'),
(24, 11, 'RESOURCES FRANCE'),
(25, 19, 'CARREFOUR'),
(26, 19, 'MCDONALDS'),
(27, 19, 'FRANPRIX'),
(28, 19, 'MAC DO'),
(29, 14, 'RATP'),
(30, 8, 'RETRAIT DAB'),
(31, 18, 'VIR CPTE A CPTE'),
(32, 3, 'SUPER MOZART'),
(33, 5, 'SNC'),
(34, 7, 'PERMANENT LOYER'),
(35, 19, 'HUIT A 8'),
(36, 2, 'DEBITEL'),
(37, 19, 'GALOPINS'),
(38, 19, 'CARREF'),
(39, 19, 'G20'),
(40, 19, 'CAFE'),
(41, 19, 'ED MOZART'),
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Contenu de la table `liste_cat`
--

INSERT INTO `liste_cat` (`id_cat`, `libelle`) VALUES
(1, ''),
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
(18, 'virement compte a compte'),
(19, 'nouriture');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

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
(14, '#(ECHEANCE PRET [0-9]{5,})#', '/explode/$1', 12, 7, 'DEBIT'),
(15, '#(REMISE[^0-9]*[0-9]{6,})#', '/explode/$1', 10, 3, 'CREDIT'),
(16, '#(VIR[^0-9^]+FAVEUR TIERS)#', '/explode/$1', 5, 1, 'DEBIT'),
(17, '#(CARTE)#', '/explode/$1', 13, 2, 'DEBIT');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `releve`
--

INSERT INTO `releve` (`id`, `mois_releve`, `annee_releve`, `date_crea`, `date_debut`, `date_fin`, `montant_debut`, `montant_fin`, `fichier`) VALUES
(1, 1, 2013, '2013-03-12 17:18:38', '2013-01-01', '2013-01-01', 0.00, 0.00, 'J:\\EasyPHP-5.3.8.1\\www\\compte\\WB00U99JJ/fichiers_importes/test.csv'),
(2, 1, 2013, '2013-03-12 17:18:57', '2013-01-01', '2013-01-01', 0.00, 0.00, 'J:\\EasyPHP-5.3.8.1\\www\\compte\\WB00U99JJ/fichiers_importes/test.csv'),
(3, 1, 2013, '2013-03-12 17:21:02', '2013-01-01', '2013-01-01', 0.00, 0.00, 'J:\\EasyPHP-5.3.8.1\\www\\compte\\WB00U99JJ/fichiers_importes/test.csv');

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
  `trouve` tinyint(1) NOT NULL DEFAULT '1',
  `pointe` enum('-1','0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_releve` (`id_releve`),
  KEY `id_operations` (`id_operations`),
  KEY `id_cat` (`id_cat`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=133 ;

--
-- Contenu de la table `releve_detail`
--

INSERT INTO `releve_detail` (`id`, `libelle`, `montant`, `type`, `id_operations`, `id_cat`, `date`, `date_crea`, `date_modif`, `id_releve`, `trouve`, `pointe`) VALUES
(1, 'RETRAIT DAB 27/01/2012 DAB SENONCHES', -20.00, 'DEBIT', 4, 8, '0000-00-00', '2013-03-12 17:18:57', '2013-03-13 10:28:15', 2, 1, '0'),
(2, 'RETRAIT DAB 20/01/2012 DAB COURVILLE', -220.00, 'DEBIT', 4, 8, '0000-00-00', '2013-03-12 17:18:57', '2013-03-13 10:28:15', 2, 1, '0'),
(3, 'RETRAIT DAB 20/01/2012 CM SENONCHES', -20.00, 'DEBIT', 4, 8, '0000-00-00', '2013-03-12 17:18:57', '2013-03-13 10:28:15', 2, 1, '0'),
(4, 'RETRAIT DAB 21/01/2012 DAB LA LOUPE', -200.00, 'DEBIT', 4, 8, '0000-00-00', '2013-03-12 17:18:57', '2013-03-13 10:28:15', 2, 1, '0'),
(5, 'PRELEVEMENT BOUYGUES TELECOM', -31.00, 'DEBIT', 5, NULL, '0000-00-00', '2013-03-12 17:18:58', '2013-03-13 15:14:45', 2, 1, '0'),
(6, 'CHEQUE 1498717', -13.00, 'DEBIT', 3, NULL, '0000-00-00', '2013-03-12 17:18:58', '2013-03-13 15:14:45', 2, 1, '0'),
(7, 'CHEQUE 1498716', -16.00, 'DEBIT', 3, NULL, '0000-00-00', '2013-03-12 17:18:58', '2013-03-13 15:14:45', 2, 1, '0'),
(8, 'CHEQUE 1498715', -1500.00, 'DEBIT', 3, NULL, '0000-00-00', '2013-03-12 17:18:58', '2013-03-13 15:14:45', 2, 1, '0'),
(9, 'VIREMENT SEPA EMIS VERS DE VEILLECHEZE CLAIR 10028118210 DE PAPA A CLAIRE', -20.00, 'DEBIT', 1, NULL, '0000-00-00', '2013-03-12 17:18:58', '2013-03-13 15:14:45', 2, 1, '0'),
(10, 'PRELEVEMENT BANQUE ACCORD', -140.00, 'DEBIT', 5, NULL, '0000-00-00', '2013-03-12 17:18:58', '2013-03-13 15:14:45', 2, 1, '0'),
(11, 'CHEQUE 1961205', -21.00, 'DEBIT', 3, NULL, '0000-00-00', '2013-03-12 17:18:58', '2013-03-13 15:14:45', 2, 1, '0'),
(12, 'RETRAIT DAB 18/01/2012 RETRAIT DAB 18-01-14505-0071260', -30.00, 'DEBIT', 4, 8, '0000-00-00', '2013-03-12 17:18:58', '2013-03-13 10:28:15', 2, 1, '0'),
(13, 'VIREMENT SEPA EMIS VERS DE VEILLECHEZE GUERRIC 00000762828 DE PAPA A GUERRIC CONCERT   TR', -62.00, 'DEBIT', 1, NULL, '0000-00-00', '2013-03-12 17:18:58', '2013-03-13 15:14:45', 2, 1, '0'),
(14, 'PRELEVEMENT COFIROUTE S.A.', -18.00, 'DEBIT', 5, NULL, '0000-00-00', '2013-03-12 17:18:58', '2013-03-13 15:14:45', 2, 1, '0'),
(15, 'VIREMENT RECU M. DE VEILLECHEZE XAVIER 10004263942 DE LEO A CC', 2400.00, 'CREDIT', 1, NULL, '0000-00-00', '2013-03-12 17:18:58', '2013-03-13 15:14:45', 2, 1, '0'),
(16, 'VIREMENT RECU S.M.I. )1135740 2012016003914', 33.00, 'CREDIT', 1, NULL, '0000-00-00', '2013-03-12 17:18:58', '2013-03-13 15:14:45', 2, 1, '0'),
(17, 'CHEQUE 1961206', -334.00, 'DEBIT', 3, NULL, '0000-00-00', '2013-03-12 17:18:58', '2013-03-13 15:14:45', 2, 1, '0'),
(18, 'RETRAIT DAB 13/01/2012 CM SENONCHES', -20.00, 'DEBIT', 4, 8, '0000-00-00', '2013-03-12 17:18:58', '2013-03-13 10:28:15', 2, 1, '0'),
(19, 'VIREMENT RECU S.M.I. )1135740 2012012004298', 22.00, 'CREDIT', 1, NULL, '0000-00-00', '2013-03-12 17:18:58', '2013-03-13 15:14:45', 2, 1, '0'),
(20, 'PRELEVEMENT FRANCE TELECOM ORLEANS', -42.00, 'DEBIT', 5, NULL, '0000-00-00', '2013-03-12 17:18:58', '2013-03-13 15:14:45', 2, 1, '0'),
(21, 'RETRAIT DAB 11/01/2012 S2P', -40.00, 'DEBIT', 4, 8, '0000-00-00', '2013-03-12 17:18:58', '2013-03-13 10:28:15', 2, 1, '0'),
(22, 'VIREMENT RECU C.P.A.M. CHARTRES 120100002786', 21.00, 'CREDIT', 1, NULL, '0000-00-00', '2013-03-12 17:18:58', '2013-03-13 15:14:45', 2, 1, '0'),
(23, 'VIREMENT RECU M XAVIER DE VEILLECHEZE 11000467669 DE LIVR A XAV A CC', 200.00, 'CREDIT', 1, NULL, '0000-00-00', '2013-03-12 17:18:58', '2013-03-13 15:14:45', 2, 1, '0'),
(24, 'VIREMENT SEPA EMIS VERS DE VEILLECHEZE LOUIS 30419630089 DE PAPA A LOUIS', -73.00, 'DEBIT', 1, NULL, '0000-00-00', '2013-03-12 17:18:58', '2013-03-13 15:14:45', 2, 1, '0'),
(25, 'RETRAIT DAB 07/01/2012 RETRAIT DAB 07-01-14505-0071130', -20.00, 'DEBIT', 4, 8, '0000-00-00', '2013-03-12 17:18:58', '2013-03-13 10:28:15', 2, 1, '0'),
(26, 'RETRAIT DAB 06/01/2012 GAB SENONCHES', -30.00, 'DEBIT', 4, 8, '0000-00-00', '2013-03-12 17:18:58', '2013-03-13 10:28:15', 2, 1, '0'),
(27, 'RETRAIT DAB 31/12/2011 DAB COURVILLE', -120.00, 'DEBIT', 4, 8, '0000-00-00', '2013-03-12 17:18:58', '2013-03-13 10:28:15', 2, 1, '0'),
(28, 'RETRAIT DAB 02/01/2012 CHABRIERES', -100.00, 'DEBIT', 4, 8, '0000-00-00', '2013-03-12 17:18:58', '2013-03-13 10:28:15', 2, 1, '0'),
(29, 'PRELEVEMENT L ODYSSEE', -9.00, 'DEBIT', 5, NULL, '0000-00-00', '2013-03-12 17:18:58', '2013-03-13 15:14:45', 2, 1, '0'),
(30, 'CHEQUE 1498714', -412.00, 'DEBIT', 3, NULL, '0000-00-00', '2013-03-12 17:18:58', '2013-03-13 15:14:45', 2, 1, '0'),
(31, 'VIREMENT RECU C.A.F DE L EURE-ET-LOIR P 1038469ADE VEILLECHE122011ME', 161.00, 'CREDIT', 1, NULL, '0000-00-00', '2013-03-12 17:18:58', '2013-03-13 15:14:45', 2, 1, '0'),
(32, 'RETRAIT DAB 30/12/2011 GAB SENONCHES', -200.00, 'DEBIT', 4, 8, '0000-00-00', '2013-03-12 17:18:59', '2013-03-13 10:28:15', 2, 1, '0'),
(33, 'CHEQUE 1498712', -70.00, 'DEBIT', 3, NULL, '0000-00-00', '2013-03-12 17:18:59', '2013-03-13 15:14:45', 2, 1, '0'),
(34, 'CARTE 26/01/2012 SNCF', -10.00, 'DEBIT', 2, 5, '0000-00-00', '2013-03-12 17:21:02', '2013-03-13 10:28:15', 3, 1, '0'),
(35, 'CARTE 26/01/2012 DAC 24/24 SUPER U', -24.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:03', '2013-03-13 15:14:45', 3, 1, '0'),
(36, 'CARTE 26/01/2012 ETOILE DU BERGER', -50.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:03', '2013-03-13 15:14:45', 3, 1, '0'),
(37, 'CARTE 25/01/2012 FRANPRIX', -7.00, 'DEBIT', 2, 19, '0000-00-00', '2013-03-12 17:21:03', '2013-03-13 10:28:15', 3, 1, '0'),
(38, 'CARTE 24/01/2012 LEROY MERLIN', -12.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:03', '2013-03-13 15:14:45', 3, 1, '0'),
(39, 'CARTE 21/01/2012 1.2.3', -122.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:03', '2013-03-13 15:14:45', 3, 1, '0'),
(40, 'CARTE 21/01/2012 CHARTRCATHEDRAUT', -2.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:03', '2013-03-13 15:14:45', 3, 1, '0'),
(41, 'CARTE 20/01/2012 3D - DOUDARD', -18.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:03', '2013-03-13 15:14:45', 3, 1, '0'),
(42, 'CARTE 21/01/2012 ESSFLOREALYG320', -59.00, 'DEBIT', 2, 3, '0000-00-00', '2013-03-12 17:21:03', '2013-03-13 10:28:15', 3, 1, '0'),
(43, 'CARTE 21/01/2012 INTERMARCHE', -31.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:03', '2013-03-13 15:14:45', 3, 1, '0'),
(44, 'CARTE 21/01/2012 SUPER U', -20.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:03', '2013-03-13 15:14:45', 3, 1, '0'),
(45, 'CARTE 21/01/2012 CONCEPT MODE', -44.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:03', '2013-03-13 15:14:45', 3, 1, '0'),
(46, 'CARTE 20/01/2012 CARREFOURMARKET', -37.00, 'DEBIT', 2, 19, '0000-00-00', '2013-03-12 17:21:03', '2013-03-13 10:28:15', 3, 1, '0'),
(47, 'CARTE 16/01/2012 CARREFOUR CHARTR', -2.00, 'DEBIT', 2, 19, '0000-00-00', '2013-03-12 17:21:03', '2013-03-13 10:28:15', 3, 1, '0'),
(48, 'CARTE 16/01/2012 ERAM', -90.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:03', '2013-03-13 15:14:45', 3, 1, '0'),
(49, 'CARTE 16/01/2012 CHARTRESCOEURAUT', -9.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:03', '2013-03-13 15:14:45', 3, 1, '0'),
(50, 'CARTE 16/01/2012 SNCF', -11.00, 'DEBIT', 2, 5, '0000-00-00', '2013-03-12 17:21:03', '2013-03-13 10:28:15', 3, 1, '0'),
(51, 'CARTE 16/01/2012 MONOPRIX', -35.00, 'DEBIT', 2, 19, '0000-00-00', '2013-03-12 17:21:03', '2013-03-13 10:28:15', 3, 1, '0'),
(52, 'CARTE 16/01/2012 CARREFDAC CHARTR', -75.00, 'DEBIT', 2, 19, '0000-00-00', '2013-03-12 17:21:03', '2013-03-13 10:28:15', 3, 1, '0'),
(53, 'CARTE 16/01/2012 CHARTRESCOEURAUT', -2.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:03', '2013-03-13 15:14:45', 3, 1, '0'),
(54, 'CARTE 16/01/2012 COMPASS GROUP F', -40.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:03', '2013-03-13 15:14:45', 3, 1, '0'),
(55, 'CARTE 17/01/2012 LE METROPOLITAN', -61.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:03', '2013-03-13 15:14:45', 3, 1, '0'),
(56, 'CARTE 16/01/2012 BURTON 058', -146.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:03', '2013-03-13 15:14:45', 3, 1, '0'),
(57, 'CARTE 13/01/2012 SUPER U', -19.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:03', '2013-03-13 15:14:45', 3, 1, '0'),
(58, 'CARTE 13/01/2012 AUX TISSUS BELLICE', -58.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:03', '2013-03-13 15:14:45', 3, 1, '0'),
(59, 'CARTE 13/01/2012 OLIVINE', -55.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:03', '2013-03-13 15:14:45', 3, 1, '0'),
(60, 'CARTE 14/01/2012 CHARTRESCOEURAUT', -3.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:03', '2013-03-13 15:14:45', 3, 1, '0'),
(61, 'CARTE 13/01/2012 UN JOUR AILLEURS', -69.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:03', '2013-03-13 15:14:45', 3, 1, '0'),
(62, 'CARTE 13/01/2012 3D - DOUDARD', -24.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:03', '2013-03-13 15:14:45', 3, 1, '0'),
(63, 'CARTE 16/01/2012 RATP', -3.00, 'DEBIT', 2, 14, '0000-00-00', '2013-03-12 17:21:03', '2013-03-13 10:28:15', 3, 1, '0'),
(64, 'CARTE 13/01/2012 CARREFDAC CHARTR', -73.00, 'DEBIT', 2, 19, '0000-00-00', '2013-03-12 17:21:04', '2013-03-13 10:28:15', 3, 1, '0'),
(65, 'CARTE 14/01/2012 INTERMARCHE', -151.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:04', '2013-03-13 15:14:45', 3, 1, '0'),
(66, 'CARTE 12/01/2012 AVERT CYRIL', -7.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:04', '2013-03-13 15:14:45', 3, 1, '0'),
(67, 'CARTE 12/01/2012 SUPER U', -8.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:04', '2013-03-13 15:14:45', 3, 1, '0'),
(68, 'CARTE 12/01/2012 ESCOFFIER NET', -49.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:04', '2013-03-13 15:14:45', 3, 1, '0'),
(69, 'CARTE 12/01/2012 SNCF INTERNET', -58.00, 'DEBIT', 2, 5, '0000-00-00', '2013-03-12 17:21:04', '2013-03-13 10:28:15', 3, 1, '0'),
(70, 'CARTE 12/01/2012 SNCF INTERNET', -24.00, 'DEBIT', 2, 5, '0000-00-00', '2013-03-12 17:21:04', '2013-03-13 10:28:15', 3, 1, '0'),
(71, 'CARTE 12/01/2012 SNCF INTERNET', -60.00, 'DEBIT', 2, 5, '0000-00-00', '2013-03-12 17:21:04', '2013-03-13 10:28:15', 3, 1, '0'),
(72, 'CARTE 12/01/2012 HALLE CHAUSSURES', -52.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:04', '2013-03-13 15:14:45', 3, 1, '0'),
(73, 'CARTE 10/01/2012 AVERT CYRIL', -8.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:04', '2013-03-13 15:14:45', 3, 1, '0'),
(74, 'CARTE 11/01/2012 CARREFOUR ATHIS', -229.00, 'DEBIT', 2, 19, '0000-00-00', '2013-03-12 17:21:04', '2013-03-13 10:28:15', 3, 1, '0'),
(75, 'CARTE 11/01/2012 CARREFOUR ATHIS', -46.00, 'DEBIT', 2, 19, '0000-00-00', '2013-03-12 17:21:04', '2013-03-13 13:40:13', 3, 1, '0'),
(76, 'CARTE 11/01/2012 BOUYGUES TELECOM', -29.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:04', '2013-03-13 15:14:45', 3, 1, '0'),
(77, 'CARTE 10/01/2012 SUPER U', -6.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:04', '2013-03-13 15:14:45', 3, 1, '0'),
(78, 'CARTE 10/01/2012 PHIE DES SABLES', -33.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:04', '2013-03-13 15:14:45', 3, 1, '0'),
(79, 'CARTE 06/01/2012 DAC INTERMARCHE VL', -66.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:04', '2013-03-13 15:14:45', 3, 1, '0'),
(80, 'CARTE 06/01/2012 INTERMARCHE', -16.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:04', '2013-03-13 15:14:45', 3, 1, '0'),
(81, 'CARTE 06/01/2012 DR BELLOY J-PAUL', -26.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:04', '2013-03-13 15:14:45', 3, 1, '0'),
(82, 'CARTE 06/01/2012 EPELBAUM MARC', -32.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:04', '2013-03-13 15:14:45', 3, 1, '0'),
(83, 'CARTE 07/01/2012 LE ST HILAIRE', -68.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:04', '2013-03-13 15:14:45', 3, 1, '0'),
(84, 'CARTE 08/01/2012 CEDIB', -7.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:04', '2013-03-13 15:14:45', 3, 1, '0'),
(85, 'CARTE 05/01/2012 CARREFOUR CHARTR', -2.00, 'DEBIT', 2, 19, '0000-00-00', '2013-03-12 17:21:04', '2013-03-13 10:28:15', 3, 1, '0'),
(86, 'CARTE 05/01/2012 CARREFDAC CHARTR', -69.00, 'DEBIT', 2, 19, '0000-00-00', '2013-03-12 17:21:04', '2013-03-13 10:28:15', 3, 1, '0'),
(87, 'CARTE 05/01/2012 LEROY MERLIN', -39.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:04', '2013-03-13 15:14:45', 3, 1, '0'),
(88, 'CARTE 05/01/2012 PHIE POUZOLS', -10.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:04', '2013-03-13 15:14:45', 3, 1, '0'),
(89, 'CARTE 03/01/2012 SNCF', -11.00, 'DEBIT', 2, 5, '0000-00-00', '2013-03-12 17:21:04', '2013-03-13 10:28:15', 3, 1, '0'),
(90, 'CARTE 03/01/2012 RATP', -3.00, 'DEBIT', 2, 14, '0000-00-00', '2013-03-12 17:21:04', '2013-03-13 10:28:15', 3, 1, '0'),
(91, 'CARTE 02/01/2012 INTERMARCHE', -11.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:04', '2013-03-13 15:14:45', 3, 1, '0'),
(92, 'CARTE 30/12/2011 AUX TISSUS BELLICE', -352.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:04', '2013-03-13 15:14:45', 3, 1, '0'),
(93, 'CARTE 30/12/2011 SUPER U', -25.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:05', '2013-03-13 15:14:45', 3, 1, '0'),
(94, 'CARTE 27/12/2011 CHARCUT MOUSSU', -31.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:05', '2013-03-13 15:14:45', 3, 1, '0'),
(95, 'CARTE 30/12/2011 BRICOMARCHE', -46.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:05', '2013-03-13 15:14:45', 3, 1, '0'),
(96, 'CARTE 30/12/2011 3D - DOUDARD', -33.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:05', '2013-03-13 15:14:45', 3, 1, '0'),
(97, 'CARTE 29/12/2011 SAINT MACLOU', -53.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:05', '2013-03-13 15:14:45', 3, 1, '0'),
(98, 'CARTE 29/12/2011 AUX TISSUS BELLICE', -59.00, 'DEBIT', 2, NULL, '0000-00-00', '2013-03-12 17:21:05', '2013-03-13 15:14:45', 3, 1, '0'),
(99, 'RETRAIT DAB 27/01/2012 DAB SENONCHES', -20.00, 'DEBIT', 4, 8, '0000-00-00', '2013-03-12 17:21:05', '2013-03-13 10:28:15', 3, 1, '0'),
(100, 'RETRAIT DAB 20/01/2012 DAB COURVILLE', -220.00, 'DEBIT', 4, 8, '0000-00-00', '2013-03-12 17:21:05', '2013-03-13 10:28:15', 3, 1, '0'),
(101, 'RETRAIT DAB 20/01/2012 CM SENONCHES', -20.00, 'DEBIT', 4, 8, '0000-00-00', '2013-03-12 17:21:05', '2013-03-13 10:28:15', 3, 1, '0'),
(102, 'RETRAIT DAB 21/01/2012 DAB LA LOUPE', -200.00, 'DEBIT', 4, 8, '0000-00-00', '2013-03-12 17:21:05', '2013-03-13 10:28:15', 3, 1, '0'),
(103, 'PRELEVEMENT BOUYGUES TELECOM', -31.00, 'DEBIT', 5, NULL, '0000-00-00', '2013-03-12 17:21:05', '2013-03-13 15:14:45', 3, 1, '0'),
(104, 'CHEQUE 1498717', -13.00, 'DEBIT', 3, NULL, '0000-00-00', '2013-03-12 17:21:05', '2013-03-13 15:14:45', 3, 1, '0'),
(105, 'CHEQUE 1498716', -16.00, 'DEBIT', 3, NULL, '0000-00-00', '2013-03-12 17:21:05', '2013-03-13 15:14:45', 3, 1, '0'),
(106, 'CHEQUE 1498715', -1500.00, 'DEBIT', 3, NULL, '0000-00-00', '2013-03-12 17:21:05', '2013-03-13 15:14:45', 3, 1, '0'),
(107, 'VIREMENT SEPA EMIS VERS DE VEILLECHEZE CLAIR 10028118210 DE PAPA A CLAIRE', -20.00, 'DEBIT', 1, NULL, '0000-00-00', '2013-03-12 17:21:05', '2013-03-13 15:14:45', 3, 1, '0'),
(108, 'PRELEVEMENT BANQUE ACCORD', -140.00, 'DEBIT', 5, NULL, '0000-00-00', '2013-03-12 17:21:05', '2013-03-13 15:14:45', 3, 1, '0'),
(109, 'CHEQUE 1961205', -21.00, 'DEBIT', 3, NULL, '0000-00-00', '2013-03-12 17:21:05', '2013-03-13 15:14:45', 3, 1, '0'),
(110, 'RETRAIT DAB 18/01/2012 RETRAIT DAB 18-01-14505-0071260', -30.00, 'DEBIT', 4, 8, '0000-00-00', '2013-03-12 17:21:05', '2013-03-13 10:28:15', 3, 1, '0'),
(111, 'VIREMENT SEPA EMIS VERS DE VEILLECHEZE GUERRIC 00000762828 DE PAPA A GUERRIC CONCERT   TR', -62.00, 'DEBIT', 1, NULL, '0000-00-00', '2013-03-12 17:21:06', '2013-03-13 15:14:45', 3, 1, '1'),
(112, 'PRELEVEMENT COFIROUTE S.A.', -18.00, 'DEBIT', 5, NULL, '0000-00-00', '2013-03-12 17:21:06', '2013-03-13 15:14:45', 3, 1, '0'),
(113, 'VIREMENT RECU M. DE VEILLECHEZE XAVIER 10004263942 DE LEO A CC', 2400.00, 'CREDIT', 1, NULL, '0000-00-00', '2013-03-12 17:21:06', '2013-03-13 15:14:55', 3, 1, '-1'),
(114, 'VIREMENT RECU S.M.I. )1135740 2012016003914', 33.00, 'CREDIT', 1, NULL, '0000-00-00', '2013-03-12 17:21:06', '2013-03-13 15:14:45', 3, 1, '-1'),
(115, 'CHEQUE 1961206', -334.00, 'DEBIT', 3, NULL, '0000-00-00', '2013-03-12 17:21:06', '2013-03-13 15:14:45', 3, 1, '0'),
(116, 'RETRAIT DAB 13/01/2012 CM SENONCHES', -20.00, 'DEBIT', 4, 8, '0000-00-00', '2013-03-12 17:21:06', '2013-03-13 10:28:15', 3, 1, '0'),
(117, 'VIREMENT RECU S.M.I. )1135740 2012012004298', 22.00, 'CREDIT', 1, NULL, '0000-00-00', '2013-03-12 17:21:06', '2013-03-13 15:14:45', 3, 1, '-1'),
(118, 'PRELEVEMENT FRANCE TELECOM ORLEANS', -42.00, 'DEBIT', 5, NULL, '0000-00-00', '2013-03-12 17:21:06', '2013-03-13 15:14:45', 3, 1, '0'),
(119, 'RETRAIT DAB 11/01/2012 S2P', -40.00, 'DEBIT', 4, 8, '0000-00-00', '2013-03-12 17:21:06', '2013-03-13 10:28:15', 3, 1, '0'),
(120, 'VIREMENT RECU C.P.A.M. CHARTRES 120100002786', 21.00, 'CREDIT', 1, NULL, '0000-00-00', '2013-03-12 17:21:06', '2013-03-13 15:14:53', 3, 1, '1'),
(121, 'VIREMENT RECU M XAVIER DE VEILLECHEZE 11000467669 DE LIVR A XAV A CC', 200.00, 'CREDIT', 1, NULL, '0000-00-00', '2013-03-12 17:21:06', '2013-03-13 15:14:45', 3, 1, '-1'),
(122, 'VIREMENT SEPA EMIS VERS DE VEILLECHEZE LOUIS 30419630089 DE PAPA A LOUIS', -73.00, 'DEBIT', 1, NULL, '0000-00-00', '2013-03-12 17:21:06', '2013-03-13 15:14:45', 3, 1, '-1'),
(123, 'RETRAIT DAB 07/01/2012 RETRAIT DAB 07-01-14505-0071130', -20.00, 'DEBIT', 4, 8, '0000-00-00', '2013-03-12 17:21:06', '2013-03-13 10:28:15', 3, 1, '0'),
(124, 'RETRAIT DAB 06/01/2012 GAB SENONCHES', -30.00, 'DEBIT', 4, 8, '0000-00-00', '2013-03-12 17:21:06', '2013-03-13 10:28:15', 3, 1, '0'),
(125, 'RETRAIT DAB 31/12/2011 DAB COURVILLE', -120.00, 'DEBIT', 4, 8, '0000-00-00', '2013-03-12 17:21:06', '2013-03-13 10:28:15', 3, 1, '0'),
(126, 'RETRAIT DAB 02/01/2012 CHABRIERES', -100.00, 'DEBIT', 4, 8, '0000-00-00', '2013-03-12 17:21:06', '2013-03-13 10:28:15', 3, 1, '0'),
(127, 'PRELEVEMENT L ODYSSEE', -9.00, 'DEBIT', 5, NULL, '0000-00-00', '2013-03-12 17:21:06', '2013-03-13 15:14:45', 3, 1, '0'),
(128, 'CHEQUE 1498714', -412.00, 'DEBIT', 3, NULL, '0000-00-00', '2013-03-12 17:21:06', '2013-03-13 15:14:45', 3, 1, '0'),
(129, 'VIREMENT RECU C.A.F DE L EURE-ET-LOIR P 1038469ADE VEILLECHE122011ME', 161.00, 'CREDIT', 1, NULL, '0000-00-00', '2013-03-12 17:21:06', '2013-03-13 15:15:01', 3, 1, '0'),
(130, 'RETRAIT DAB 30/12/2011 GAB SENONCHES', -200.00, 'DEBIT', 4, 8, '0000-00-00', '2013-03-12 17:21:06', '2013-03-13 10:28:15', 3, 1, '0'),
(131, 'CHEQUE 1498712', -70.00, 'DEBIT', 3, NULL, '0000-00-00', '2013-03-12 17:21:06', '2013-03-13 15:14:45', 3, 1, '0'),
(132, 'test', 10.00, 'DEBIT', 1, 2, '2013-03-12', '2013-03-13 14:20:50', '2013-03-13 14:30:56', 3, 1, '0');

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

-- --------------------------------------------------------

--
-- Structure de la table `import_excel`
--

CREATE TABLE IF NOT EXISTS `import_excel` (
  `id_excel` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(20) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id_excel`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `import_excel`
--

INSERT INTO `import_excel` (`id_excel`, `libelle`, `position`) VALUES
(1, 'montant', 4),
(2, 'date', 1),
(3, 'libelle', 2);


--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `keywords`
--
ALTER TABLE `keywords`
  ADD CONSTRAINT `keywords_ibfk_1` FOREIGN KEY (`id_cat`) REFERENCES `liste_cat` (`id_cat`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `releve_detail`
--
ALTER TABLE `releve_detail`
  ADD CONSTRAINT `releve_detail_ibfk_3` FOREIGN KEY (`id_releve`) REFERENCES `releve` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `releve_detail_ibfk_4` FOREIGN KEY (`id_operations`) REFERENCES `operations` (`id_operations`),
  ADD CONSTRAINT `releve_detail_ibfk_5` FOREIGN KEY (`id_cat`) REFERENCES `liste_cat` (`id_cat`) ON DELETE SET NULL ON UPDATE CASCADE;



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


