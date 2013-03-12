<?php
class database extends PDO {

	/**
	 * @var Singleton
	 * @access private
	 * @static
	 */
	private static $_instance = null;	

	 /**
		* Constructeur de la classe
		*
		* @param void
		* @return void
		*/
	 public function __construct() { 
		try{
			$db_config = array();
			$db_config['SGBD']  = 'mysql';
			$db_config['HOST']  = HOSTNAME_BASE;
			$db_config['DB_NAME'] = DATABASE_BASE;
			$db_config['USER']  = USERNAME_BASE;
			$db_config['PASSWORD']  = PASSWORD_BASE;
			$db_config['OPTIONS'] = array(
						// Activation des exceptions PDO :
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
						// Change le fetch mode par défaut sur FETCH_ASSOC ( fetch() retournera un tableau associatif ) :
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
				);

			$pdo = parent::__construct($db_config['SGBD'] .':host='. $db_config['HOST'] .';dbname='. $db_config['DB_NAME'],
				$db_config['USER'],
				$db_config['PASSWORD'],
				$db_config['OPTIONS']);
			unset($db_config);
		}
		catch(Exception $e)
		{
			trigger_error($e->getMessage(), E_USER_ERROR);
		} 
	}

	 /**
		* Méthode qui crée l'unique instance de la classe
		* si elle n'existe pas encore puis la retourne.
		*
		* @param void
		* @return Singleton
		*/
	 public static function getInstance() {

		 if(is_null(self::$_instance)) {
			 self::$_instance = new database();  
		 }

		 return self::$_instance;
	 }




/*
pour mettre un prefixe
 
    public function exec($statement)
    {
        $statement = $this->_tablePrefixSuffix($statement);
        return parent::exec($statement);
    }

    public function prepare($statement, $driver_options = array())
    {
        $statement = $this->_tablePrefixSuffix($statement);
        return parent::prepare($statement, $driver_options);
    }

    public function query($statement)
    {
        $statement = $this->_tablePrefixSuffix($statement);
        $args      = func_get_args();

        if (count($args) > 1) {
            return call_user_func_array(array($this, 'parent::query'), $args);
        } else {
            return parent::query($statement);
        }
    }

    protected function _tablePrefixSuffix($statement)
    {
    		$sql_find = array(
                        '#(FROM\s+`?)#i',
                        '#(INTO\s+`?)#i',
                        '#(JOIN\s?\(\s?`?)#i',
                        '#(UPDATE\s+`?)#i',
                        '#(CREATE TABLE\s+`?)#i',
                         '#'.PREFIX_BASE.'INFORMATION_SCHEMA#i',
                          '#TABLE_NAME\s?=\s?\'(.+)\'#i'

        );

        $sql_replace = array(
                '$1'.PREFIX_BASE,
                '$1'.PREFIX_BASE,
                '$1'.PREFIX_BASE,
                '$1'.PREFIX_BASE,
                '$1'.PREFIX_BASE,
                'INFORMATION_SCHEMA',
                'TABLE_NAME = \''.PREFIX_BASE.'$1\''

        );
        return preg_replace($sql_find,$sql_replace,$statement);
    }*/

 }
 
 ?>