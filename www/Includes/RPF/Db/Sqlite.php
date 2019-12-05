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
 * Class provides connection to Sqlite databases using PDO driver.
 *
 */
 class RPF_Db_Sqlite
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
		$this->DSN = "sqlite:".$config['db']['sqlitepath'] ;
		
		$this->dbUser = null;
		$this->dbPass = null;
		
		$this->options = array(
						PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
					);
	}
 }