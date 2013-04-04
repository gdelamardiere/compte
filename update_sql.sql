ALTER TABLE `releve_detail` ADD `bcat` BOOLEAN NOT NULL DEFAULT '0';
UPDATE `releve_detail` set `bCat`=1 WHERE `id_cat` is not null;


ALTER TABLE `releve_detail` DROP FOREIGN KEY `releve_detail_ibfk_5` ;

ALTER TABLE `releve_detail` CHANGE `id_cat` `id_cat` INT( 11 ) NOT NULL DEFAULT '1';
ALTER TABLE `releve_detail` ADD FOREIGN KEY ( `id_cat` ) REFERENCES `comptes`.`liste_cat` (
`id_cat`
) ON DELETE CASCADE ON UPDATE CASCADE ;
UPDATE `comptes`.`liste_cat` SET `libelle` = 'Non défini' WHERE `liste_cat`.`id_cat` =1;



















SHOW CREATE PROCEDURE update_releve_detail