SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE DATABASE `comptes` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `comptes`;
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;




DROP TABLE IF EXISTS `filtres`;
CREATE TABLE IF NOT EXISTS `filtres` (
  `id_filtre` int(11) NOT NULL AUTO_INCREMENT,
  `nom_filtre` varchar(100) NOT NULL,
  `id_releve` varchar(100) NOT NULL,
  `coche_operations` varchar(100) NOT NULL,
  `coche_categorie` varchar(100) NOT NULL,
  `liste_graphe` varchar(100) NOT NULL,
  `filtre_annee_1` varchar(100) NOT NULL,
  `filtre_annee_2` varchar(100) NOT NULL,
  `filtre_perso_1` varchar(100) NOT NULL,
  `filtre_perso_2` varchar(100) NOT NULL,
  PRIMARY KEY (`id_filtre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `import_excel`;
CREATE TABLE IF NOT EXISTS `import_excel` (
  `id_excel` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(20) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id_excel`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

INSERT INTO `import_excel` (`id_excel`, `libelle`, `position`) VALUES
(1, 'montant', 4),
(2, 'date', 1),
(3, 'libelle', 2);

DROP TABLE IF EXISTS `keywords`;
CREATE TABLE IF NOT EXISTS `keywords` (
  `id_keywords` int(11) NOT NULL AUTO_INCREMENT,
  `id_cat` int(11) DEFAULT NULL,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY (`id_keywords`),
  KEY `id_cat` (`id_cat`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=52 ;

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
(15, 3, 'ESSFLOREALY'),
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
(42, 3, 'ESSO'),
(43, 19, 'SUPER U'),
(44, 23, 'LEROY MERLIN'),
(45, 19, 'CHARCUT MOUSSU'),
(46, 19, 'INTERMARCHE'),
(47, 23, 'BRICOMARCHE'),
(48, 24, 'ERAM'),
(49, 25, 'TISSUS BELLICE'),
(50, 24, 'HALLE CHAUSSURES'),
(51, 25, 'SAINT MACLOU');

DROP TABLE IF EXISTS `liste_cat`;
CREATE TABLE IF NOT EXISTS `liste_cat` (
  `id_cat` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) NOT NULL,
  PRIMARY KEY (`id_cat`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

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
(13, 'impot + amendes'),
(14, 'ratp'),
(15, 'edf'),
(16, 'pressing'),
(17, 'PEL'),
(18, 'virement compte a compte'),
(19, 'nouriture'),
(23, 'bricolage'),
(24, 'habillement'),
(25, 'ameublement');

DROP TABLE IF EXISTS `operations`;
CREATE TABLE IF NOT EXISTS `operations` (
  `id_operations` int(11) NOT NULL AUTO_INCREMENT,
  `nom_operations` varchar(50) NOT NULL,
  PRIMARY KEY (`id_operations`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

INSERT INTO `operations` (`id_operations`, `nom_operations`) VALUES
(1, 'virement'),
(2, 'cb'),
(3, 'cheque'),
(4, 'espece'),
(5, 'prelevement'),
(6, 'frais banquaire'),
(7, 'pret');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
DROP TRIGGER IF EXISTS `releve_detail_update`;
DELIMITER //
CREATE TRIGGER `releve_detail_update` BEFORE UPDATE ON `releve_detail`
 FOR EACH ROW BEGIN
SET NEW.`date_modif`= NOW();
END
//
DELIMITER ;


ALTER TABLE `keywords`
  ADD CONSTRAINT `keywords_ibfk_1` FOREIGN KEY (`id_cat`) REFERENCES `liste_cat` (`id_cat`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `releve_detail`
  ADD CONSTRAINT `releve_detail_ibfk_3` FOREIGN KEY (`id_releve`) REFERENCES `releve` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `releve_detail_ibfk_4` FOREIGN KEY (`id_operations`) REFERENCES `operations` (`id_operations`),
  ADD CONSTRAINT `releve_detail_ibfk_5` FOREIGN KEY (`id_cat`) REFERENCES `liste_cat` (`id_cat`) ON DELETE SET NULL ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;




