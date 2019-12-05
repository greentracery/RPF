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
		
		$routingParts = explode('/', $routePath, 2); 
		$routingCount = count($routingParts); 
		
		switch($routingCount)
		{
			case 1:
				$actionOrder = 0;
			break;
			case 2:
				$actionOrder = 1;
			break;
		}
		
		$action = isset($routingParts[$actionOrder]) ? $routingParts[$actionOrder] : '' ;  
		
		if(empty($action) && isset($httpPOST['action'])) $action = $httpPOST['action'];
		
		if(empty($action) && isset($httpGET['action'])) $action = $httpGET['action'];
		
		return $action;
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
		
		$routingParts = explode('/', $routePath, 2); 
		$routingCount = count($routingParts); 
		
		$extension = ($routingCount > 1 && isset($routingParts[0])) ? $routingParts[0] : '' ;  
		
		return $extension;
	}

	public function ProcessAction()
	{
		$loader = RPF_Autoloader::getInstance();
		
		$this->action = (!empty($this->action))? ucfirst($this->action) : 'Index';
		$this->extension = (!empty($this->extension))? ucfirst($this->extension) : '';
		
		$actionControllerClassName[] = $this->extension;
		$actionControllerClassName[] = 'Controller';
		$actionControllerClassName[] = $this->action;
		
		$actionControllerClass = implode('_', $actionControllerClassName); 
		
		try
		{
			if(!$loader->autoload($actionControllerClass))
			{
				// If URL contained extension name without final slash,
				// try to load default controller for this extension:
				$actionControllerClass = $this->action.'_Controller_Index'; // Extension -> Extension/[Index]
				
				if(!$loader->autoload($actionControllerClass))
				throw new Exception("Can't load class $actionControllerClass in ". __CLASS__ . "::" . __METHOD__);
			}
		}
		catch(Exception $e)
		{
			RPF_ApplicationError::LogException($e);
			// try to load controller for this action from RPF package:
			$actionControllerClass = 'RPF_Controller_'.$this->action;
			
			if(!$loader->autoload($actionControllerClass))
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