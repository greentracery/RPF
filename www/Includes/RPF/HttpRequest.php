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
 * This class resolves an URL to route and action, get request agruments
 *
 */
class RPF_HttpRequest  {
	
	/**
	* URL of request
	*
	* @string requestUri
	*/
	public $requestUri;	
	
	/**
	* Part of URL contains server name
	*
	* @string baseUrl
	*/
	public $baseUrl;
	
	/**
	* Request method (GET or POST)
	*
	* @string requestMethod
	*/
	public $requestMethod;
	
	/**
	* Part of URL contains request params
	*
	* @string queryString
	*/
	public $queryString;
	
	/**
	* Copy of _GET params
	*
	* @array 
	*/
	public $httpGETParams;
	
	/**
	* Copy of _POST params
	*
	* @array 
	*/
	public $httpPOSTParams;
	
	/**
	* Part of URL contains extension name & action
	*
	* @string routePath
	*/
	public $routePath;
	
	/**
	* Part of URL contains extension name & action
	*
	* @string staticRoutePath
	*/
	public $staticRoutePath;


	function __construct()
	{
		$baseUrl = $_SERVER['SERVER_NAME'];
		
		$requestUri = $_SERVER['REQUEST_URI'];
		
		$this->requestUri = (strpos($requestUri, $baseUrl) === 0)? $requestUri : $baseUrl.$requestUri;
		
		$this->baseUrl = (substr($baseUrl,  (strlen($baseUrl) - 1)) == '/')? $baseUrl : $baseUrl .'/';
		
		$this->requestMethod = $_SERVER['REQUEST_METHOD'];
		
		$this->queryString = $_SERVER['QUERY_STRING'];
		
		$this->routePath = $this->getRoutePath($this->baseUrl,  $this->requestUri);
		
		$this->staticRoutePath = $this->getStaticRoutePath($this->baseUrl,  $this->requestUri, $this->queryString);
		
		$httpGet = $_GET;
		
		$httpPost = $_POST;
		
		if(isset($httpGet[$this->routePath])) unset($httpGet[$this->routePath]);
		
		$this->httpGETParams = $httpGet;
		
		$this->httpPOSTParams = $httpPost;
		
	}

	/**
	* @param string $baseUrl
	* @param string $requestUri
	*
	* @return string 
	*/
	public function getRoutePath($baseUrl,  $requestUri)
	{
		$result = null;
		
		if (substr($requestUri, 0, strlen($baseUrl)) == $baseUrl)
		{
			$routeBase = substr($requestUri, strlen($baseUrl));
			
			if (preg_match('#^/([^?]+)(\?|$)#U', $routeBase, $match))
			{
				// rewrite approach (starts with /). Must be non-empty rewrite up to query string.
				$result = urldecode($match[1]);
			}
			else if (preg_match('#\?([^=&]+)(&|$)#U', $routeBase, $match))
			{
				// query string approach. Must start with non-empty, non-named param.
				$result = urldecode($match[1]);
			}
		}
		
		if ($result !== null)
		{
			return ltrim($result, '/');
		}
		
		return '';
	}
	
	/**
	* @param string $baseUrl
	* @param string $requestUri
	*
	* @return string 
	*/
	public function getStaticRoutePath($baseUrl, $requestUri, $queryString)
	{
		$staticUrl = str_replace( '?'.$queryString, '', $requestUri );
		
		$routeBase = substr($staticUrl, strlen($baseUrl));
		
		$result = str_replace( 'index.php', '', $routeBase );
		
		if ($result !== null)
		{
			return ltrim($result, '/');
		}
		
		return '';
	}
	
	/**
	* @return array _POST | array _GET with keys in lower case 
	*/
	public function getRequestData()
	{
		return ($this->requestMethod == 'POST')? 
			array_change_key_case($this->httpPOSTParams, CASE_LOWER) : 
			array_change_key_case($this->httpGETParams, CASE_LOWER); 
	}
	
	/**
	* @return string $IP -  client's IP address
	*/
	public static function getCleintIp()
	{
		$IP = $_SERVER['REMOTE_ADDR'];
		if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) 
		{
			$IP = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
		}
		return $IP;
	}
	
}