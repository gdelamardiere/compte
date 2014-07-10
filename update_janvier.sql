ALTER TABLE `releve`
  DROP `mois_releve`,
  DROP `annee_releve`,
  DROP `date_debut`,
  DROP `date_fin`,
  DROP `montant_debut`,
  DROP `montant_fin`;

ALTER TABLE  `releve` ADD  `compte` INT NULL ,
ADD INDEX (  `compte` ) ;
UPDATE releve SET compte =1;
ALTER TABLE  `releve` ADD FOREIGN KEY (  `compte` ) REFERENCES  `compte`.`liste_comptes` (

`id`
) ON DELETE SET NULL ON UPDATE CASCADE ;

INSERT INTO `liste_comptes` (`id`, `libelle`) VALUES (NULL, 'compte courant BNP');

ALTER TABLE  `releve_detail` ADD UNIQUE (
`date` ,
`montant` ,
`libelle`
) COMMENT  '';