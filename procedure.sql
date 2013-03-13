DELIMITER $$
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
