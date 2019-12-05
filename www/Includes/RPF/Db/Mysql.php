<?php
/**
 * Just another small extendable MVC framework for rapid prototyping of APIs, web-services and web-sites.
 * Yep, it's again the `reinventing of wheel` 😉
 *
 * @author A.Mikhaylichenko (greentracery@gmail.com)
 * @url https://github.com/greentracery/RPF/
 * @package RPF
 * @version    0.1b
 */
 
/**
 * Class provides connection to MySQL\MariaDB databases using PDO driver.
 *
 */
 class RPF_Db_Mysql
 {
 	/**
	* Data Source Name, DSN.
	*
	* @string
	*/
	public $DSN;

	/**
	* A key=>value array of driver-specific connection options. 
	* 
	* @array
	*/
	public $options;
	
	public $dbUser;
	
	public $dbPass;
 
	function __construct($config)
	{
		$this->DSN = "mysql:"
				."host=".$config['db']['host'].";"
				."port=".$config['db']['port'].";"
				."dbname=".$config['db']['dbname'].";"
				."charset=utf8";
		
		$this->dbUser = $config['db']['username'];
		$this->dbPass = $config['db']['password'];
		
		$this->options = array(
						PDO::ATTR_PERSISTENT => false,
						PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
					);
	}
 }