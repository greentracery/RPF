<?php
/**
 * Just another small extendable MVC framework for rapid prototyping of APIs, web-services and web-sites.
 * Yep, it's again the `reinventing of wheel` 😉
 *
 * @author A.Mikhaylichenko (greentracery@gmail.com)
 * @package RPF
 * @version    0.1b
 */

/**
 * Class contains functions for working with MySQL\MariaDB\Firebird\Sqlite databases using PDO driver.
 *
 */
 
class RPF_Db
{
	/**
	* Instance manager.
	*
	* @var RPF_Db
	*/
	protected static $instance;
	
	/**
	* Configuration params of application.
	*
	* @array config
	*/
	protected $config;
	
	/**
	* PDO DatabaseHandle.
	*
	* @var _dbh
	*/
	protected $_dbh;
	
	public function __construct()
	{
		$Application = RPF_Application::getInstance();
		$config = $Application->config;
		
		try
		{
			$DbType = 'RPF_Db_'.mb_convert_case($config['db']['type'], MB_CASE_TITLE);
			
			$dbcf = new $DbType($config);
			
			$this->_dbh = new PDO($dbcf->DSN, $dbcf->dbUser, $dbcf->dbPass, $dbcf->options);
			
			return true;
		}
		catch(PDOException $e)
		{
			self::DbExceptionHandler($e);
		}
		catch(Exception $e)
		{
			self::DbExceptionHandler($e);
		}
	}
	
	/**
	* Gets the Db instance.
	*
	* @return Db
	*/
	public static function getInstance()
	{
		if (self::$instance === null) 
		{
			self::$instance = new self();   
		}
 		return self::$instance;
	}
	
	/**
	* Select queries
	* @param string $_query_string 
	* @param array $_placeholders|null. If placeholders is set, it must be array like ('fieldname' => value, ....)
	*
	* @return array of selected rows
	*/
	public function fetch_assoc($_query_string, $_placeholders = null)
	{
		return $this->fetch($_query_string, $_placeholders, PDO::FETCH_ASSOC);
	}

	/**
	* Select queries
	* @param string $_query_string 
	* @param array $_placeholders|null
	*
	* @return array of selected rows
	*/
	public function fetch_num($_query_string, $_placeholders = null)
	{
		return $this->fetch($_query_string, $_placeholders, PDO::FETCH_NUM);
	}
	
	/**
	* Select queries
	* @param string $_query_string 
	* @param array $_placeholders|null
	* @param int $_pdo_fetch_mode
	*
	* @return array of selected rows
	*/
	public function fetch($_query_string, $_placeholders = null, $_pdo_fetch_mode)
	{
		$out = array();
		try
		{
			if(is_null($this->_dbh))
			{
				throw new Exception('PDO Database Handle is empty in '. __CLASS__ . "::" . __METHOD__);
			}
			// Create Statement Handle
			$sth = $this->_dbh->prepare($_query_string);
			
			if($sth)
			{
				$sth->execute($_placeholders); 
				
				$sth->setFetchMode($_pdo_fetch_mode);
				while($row = $sth->fetch()) 
				{
					$out[] = $row;
				}
				$this->_e = null;
				return $out;
			}
			else
			{
				throw new Exception('PDO Statement Handle is empty in '. __CLASS__ . "::" . __METHOD__);
			}
		}
		catch(PDOException $e)
		{
			self::DbExceptionHandler($e);
		}
		catch(Exception $e)
		{
			self::DbExceptionHandler($e);
		}
		return $out;
	}
	
	/**
	* Inser or Delete queries
	* @param string $_query_string 
	* @param array $_placeholders|null
	*
	* @return int rowCount
	*/
	public function query($_query_string, $_placeholders = null)
	{
		try
		{
			if(is_null($this->_dbh))
			{
				throw new Exception('PDO Database Handle is empty in '. __CLASS__ . "::" . __METHOD__);
			}
			// Create Statement Handle
			$sth = $this->_dbh->prepare($_query_string);
			
			if($sth)
			{
				$sth->execute($_placeholders); 
				$this->rowCount = $sth->rowCount();
			}
			else
			{
				throw new Exception('PDO Statement Handle is empty in '. __CLASS__ . "::" . __METHOD__);
			}
		}
		catch(PDOException $e)
		{
			self::DbExceptionHandler($e);
		}
		catch(Exception $e)
		{
			self::DbExceptionHandler($e);
		}
		return $this->rowCount;
	}
	
	/**
	* @return bool
	*/
	public function beginTransaction()
	{
		try
		{
			return $this->_dbh->beginTransaction();
		}
		catch(PDOException $e)
		{
			self::DbExceptionHandler($e);
		}
		catch(Exception $e)
		{
			self::DbExceptionHandler($e);
		}
	}
	
	/**
	* @return bool
	*/
	public function commitTransaction()
	{
		try
		{
			return $this->_dbh->commit();
		}
		catch(PDOException $e)
		{
			self::DbExceptionHandler($e);
		}
		catch(Exception $e)
		{
			self::DbExceptionHandler($e);
		}
	}

	/**
	* @return bool
	*/
	public function rollbackTransaction()
	{
		try
		{
			return $this->_dbh->rollBack();
		}
		catch(PDOException $e)
		{
			self::DbExceptionHandler($e);
		}
		catch(Exception $e)
		{
			self::DbExceptionHandler($e);
		}
	}

	/**
	* @return bool
	*/
	public function inTransaction()
	{
		return $this->_dbh->inTransaction();
	}
	
	/**
	* This method may not return a meaningful or consistent result across different 
	* PDO drivers, because the underlying database may not even support the notion 
	* of auto-increment fields or sequences. 
	* 
	* @retrun string PDO::lastInsertId
	*/
	public function lastInsertId()
	{
		try
		{
			return $this->_dbh->lastInsertId();
		}
		catch(PDOException $e)
		{
			self::DbExceptionHandler($e);
		}
		catch(Exception $e)
		{
			self::DbExceptionHandler($e);
		}
	}
	
	/**
	* @param Exception | Throwable
	*/
	public static function DbExceptionHandler($e)
	{
		return RPF_ApplicationError::ExceptionHandler($e);
	}
}