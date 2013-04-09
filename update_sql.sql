ALTER TABLE `releve_detail` ADD `bcat` BOOLEAN NOT NULL DEFAULT '0';
UPDATE `releve_detail` set `bCat`=1 WHERE `id_cat` is not null;


ALTER TABLE `releve_detail` DROP FOREIGN KEY `releve_detail_ibfk_5` ;

ALTER TABLE `releve_detail` CHANGE `id_cat` `id_cat` INT( 11 ) NOT NULL DEFAULT '1';
ALTER TABLE `releve_detail` ADD FOREIGN KEY ( `id_cat` ) REFERENCES `comptes`.`liste_cat` (
`id_cat`
) ON DELETE CASCADE ON UPDATE CASCADE ;
UPDATE `comptes`.`liste_cat` SET `libelle` = 'Non défini' WHERE `liste_cat`.`id_cat` =1;



-- --------------------------------------------------------

--
-- Structure de la table `regroupement`
--

DROP TABLE IF EXISTS `regroupement`;
CREATE TABLE IF NOT EXISTS `regroupement` (
  `id_regroupement` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  PRIMARY KEY (`id_regroupement`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `regroupement`
--

INSERT INTO `regroupement` (`id_regroupement`, `nom`) VALUES
(1, 'divers');

-- --------------------------------------------------------

--
-- Structure de la table `r_regroupement_cat`
--

DROP TABLE IF EXISTS `r_regroupement_cat`;
CREATE TABLE IF NOT EXISTS `r_regroupement_cat` (
  `id_regroupement` int(11) NOT NULL,
  `id_cat` int(11) NOT NULL,
  KEY `id_regroupement` (`id_regroupement`),
  KEY `id_cat` (`id_cat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `r_regroupement_cat`
--

INSERT INTO `r_regroupement_cat` (`id_regroupement`, `id_cat`) VALUES
(1, 1);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `r_regroupement_cat`
--
ALTER TABLE `r_regroupement_cat`
  ADD CONSTRAINT `r_regroupement_cat_ibfk_2` FOREIGN KEY (`id_cat`) REFERENCES `liste_cat` (`id_cat`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `r_regroupement_cat_ibfk_1` FOREIGN KEY (`id_regroupement`) REFERENCES `regroupement` (`id_regroupement`) ON DELETE CASCADE ON UPDATE CASCADE;



DELIMITER $$
--
-- Procédures
--
DROP PROCEDURE IF EXISTS `update_releve_detail`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_releve_detail`()
BEGIN
				update releve_detail rd  set rd.id_cat=
					ifnull((select k.id_cat
						from keywords k 
						where rd.libelle REGEXP k.value
						limit 1),1)
			where rd.bcat = 0 ; 
			update releve_detail rd  set rd.bcat=1
			where rd.id_cat != 1 ; 
			END$$

DELIMITER ;