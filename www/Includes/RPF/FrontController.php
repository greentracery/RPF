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
 * This class: resolves a URL to a route, loads a specified controller, executes an action
 * in that controller, loads the view, renders the view, and outputs the response.
 *
 */

class RPF_FrontController
{
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
	
	/**
	* Instance of RPF_HTTPRequest
	*
	* @var RPF_HTTPRequest
	*/
	public $request;      
	
	/**
	* Instance of RPF_Response
	*
	* @var RPF_Response
	*/
	public $response;
	
	/**
	* Instance manager.
	*
	* @var RPF_FrontController
	*/
	protected static $instance;

	function __construct()
	{
		$this->request = new RPF_HttpRequest(); 
		$this->action = $this->getAction();
		$this->extension = $this->getExtension();
		self::$instance = $this;
	}

	/**
	* Gets the action name from http request.
	*
	* @return string 
	*/
	public function getAction()
	{
		$routePath = (!empty($this->request->routePath))? 
			$this->request->routePath : $this->request->staticRoutePath;
		$httpGET = $this->request->httpGETParams;
		$httpPOST = $this->request->httpPOSTParams;
		
		// Get actionName from URL:
		$routingParts = explode('/', $routePath, 2); 
		$action = isset($routingParts[1]) ? $routingParts[1] : '' ;  
		// If actionName is not set in URL -  check POST param.'action':
		if(empty($action) && isset($httpPOST['action'])) $action = $httpPOST['action']; 
		// If actionName is not set in URL or in POST -  check GET param.'action':
		if(empty($action) && isset($httpGET['action'])) $action = $httpGET['action'];
		// If actionName is not set in URL or in POST  or in GET -  use default action Index:
		if(empty($action)) $action = 'Index';
		
		return ucfirst($action);
	}

	/**
	* Gets the extension name from http request.
	*
	* @return string 
	*/
	public function getExtension()
	{
		$routePath = (!empty($this->request->routePath))? 
			$this->request->routePath : $this->request->staticRoutePath;
		$httpGET = $this->request->httpGETParams;
		$httpPOST = $this->request->httpPOSTParams;
		
		// Get extensionName from URL:
		$routingParts = explode('/', $routePath, 2); 
		$extension = (isset($routingParts[0])) ? $routingParts[0] : '' ;  
		// If extensionName is not set in URL -  check POST param.'package':
		if(empty($extension) && isset($httpPOST['package'])) $extension = $httpPOST['package']; 
		// If extensionName is not set in URL or in POST -  check GET param.'action':
		if(empty($extension) && isset($httpGET['package'])) $extension = $httpGET['package'];
		// If actionName is not set in URL or in POST  or in GET -  use default package RPF:
		if(empty($extension)) $extension = 'RPF';
		
		return ucfirst($extension);
	}

	public function ProcessAction()
	{
		$loader = RPF_Autoloader::getInstance();
		
		$actionControllerClassName[] = $this->extension;
		$actionControllerClassName[] = 'Controller';
		$actionControllerClassName[] = $this->action;
		
		$actionControllerClass = implode('_', $actionControllerClassName); 
		
		try
		{
			if(!$loader->autoload($actionControllerClass))
			{
				throw new Exception("Can't load class $actionControllerClass in ". __CLASS__ . "::" . __METHOD__);
			}
		}
		catch(Exception $e)
		{
			RPF_ApplicationError::LogException($e);
			$actionControllerClass = 'RPF_CustomErrorHandler_NotFound';
		}
		
		$actionController = new $actionControllerClass();
		$actionController->response->SendResponse();
	}


	/**
	* Gets the RPF_FrontContriller instance.
	*
	* @return RPF_FrontContriller 
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
	* Manually sets the FrontContriller instance. Use this to inject a modified version.
	*
	* @param RPF_FrontContriller|null
	*/
	public static function setInstance(RPF_FrontController $app = null)
	{
		self::$_instance = $app;
	}

	/**
	* Gets the path to the configuration directory.
	*
	* @return string
	*/
	public function getConfigDir()
	{
		return $this->_configDir;
	}

	/**
	* Gets the path to the application root directory.
	*
	* @return string
	*/
	public function getRootDir()
	{
		return $this->_rootDir;
	}

}