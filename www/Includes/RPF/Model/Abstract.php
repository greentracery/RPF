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
* Abstract class for a model. New model types must extend this.
*
*/

abstract class RPF_Model_Abstract  
{
	/**
	* Instance of RPF_Db
	*
	* @var RPF_Db
	*/
	protected $db;
	
	/**
	* Configuration params of application.
	*
	* @array config
	*/
	protected $config;
	
	/**
	* Instance of RPF_HTTPRequest
	*
	* @var RPF_HTTPRequest
	*/
	public $request;

	/**
	* Extension name: routing prefix contains name of extension (component name)
	*
	* @string extension
	*/
	public $extension;
	
	/**
	* Action name: routing prefix contains name of action
	*
	* @string action
	*/
	public $action; 
	
	public function __construct()
	{
		$Application = RPF_Application::getInstance();
		$FrontController = RPF_FrontController::getInstance();

		$this->config = $Application->config;
		$this->request = $FrontController->request; 
		$this->extension = $FrontController->extension;
		$this->action = $FrontController->action; 
		
		if ( !empty($this->config['db']['type']) )
		{
			$this->db = RPF_Db::getInstance();
		}
		else
		{
			$this->db = null;
		}
	}
	
	/**
	* Execute Select query
	* @param string $query 
	* @param array $placeholders
	* @param int $fetchType = PDO::FETCH_ASSOC | PDO::FETCH_NUM
	*
	* @return array
	*/
	public function fetch( $query, array $placeholders = array(), $fetchType = PDO::FETCH_ASSOC )
	{
		return  $this->db->fetch($query, $placeholders, $fetchType);  
	}

	/**
	* Execute Select query
	* @param string $query 
	* @param array $placeholders
	*
	* @return var | boolean 'false'
	*/
	public function fetchValue( $query, array $placeholders = array() )
	{
		$result = $this->fetch($query, $placeholders, PDO::FETCH_NUM);
		if(count($result) == 0) return false;
		return $result[0][0];
	}

	/**
	* Execute Select query
	* @param string $query 
	* @param array $placeholders
	*
	* @return array| boolean 'false'
	*/
	public function fetchRow( $query, array $placeholders = array()  )
	{
		$result = $this->fetch($query, $placeholders, PDO::FETCH_ASSOC);
		if(count($result) == 0) return false;
		return array_shift($result);
	}
	
	/**
	* Execute Select query
	* @param string $query 
	* @param array $placeholders
	*
	* @return array| boolean 'false'
	*/
	public function fetchColumn( $query, array $placeholders = array()  )
	{
		$result = $this->fetch($query, $placeholders, PDO::FETCH_NUM);
		if(count($result) == 0) return false;
		foreach($result as $row)
		{
			$out[] = $row[0];
		}
		return $out;
	}

	/**
	* Execute Insert or Delete queries
	* @param string $query 
	* @param array $placeholders
	*
	* @return int rowCount
	*/
	public function exec( $query, array $placeholders = array() )
	{
		return $this->db->query($query, $placeholders);  
	}
	
} 