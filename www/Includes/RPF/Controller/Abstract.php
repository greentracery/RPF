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
 * Abstract class for an action controller. New controller types must extend this.
 *
 */

abstract class RPF_Controller_Abstract  
{
	/**
	* Instance of RPF_Application.
	*
	* @var RPF_Application
	*/
	protected  $Application;
	
	/**
	* Instance of RPF_FrontController.
	*
	* @var RPF_FrontController
	*/
	protected $FrontController;
	
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
	* Instance of RPF_Response
	*
	* @var RPF_Response
	*/
	public $response;

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
		$this->Application = RPF_Application::getInstance();
		$this->FrontController = RPF_FrontController::getInstance();
		
		$this->config = $this->Application->config;
		$this->request = $this->FrontController->request; 
		$this->extension = $this->FrontController->extension; 
		$this->action = $this->FrontController->action; 
		
	}
	
	/**
	* Creates instance of RPF_Response_View with defined viewName, templateName & params
	*
	* @param string $viewName
	* @param string $templateName
	* @param array $params
	*
	* @return Response $controllerResponse
	*/
	public function responseView($viewName = '', $templateName = '', array $params = array())
	{
		$controllerResponse = new RPF_Response_View();
		$controllerResponse->viewName = $viewName;
		$controllerResponse->templateName = $templateName;
		$controllerResponse->params = $params;
		return $controllerResponse;
	}
	
	/**
	* Creates instance of RPF_Response_Redirect with defined target & params
	*
	* @param string $redirectTarget
	* @param array $params
	*
	* @return Response $controllerResponse
	*/
	public function responseRedirect($redirectTarget, array $params = array())
	{

		$controllerResponse = new RPF_Response_Redirect();
		$controllerResponse->redirectTarget = $redirectTarget;
		$controllerResponse->params = $params;
		return $controllerResponse;
	}
	
	/**
	* Creates instance of RPF_Response_Errort with defined error & params
	*
	* @param string $error
	* @param int $responseCode
	* @param array $params
	*
	* @return Response $controllerResponse
	*/
	public function responseError($error, $responseCode = 200, array $params = array())
	{
		$controllerResponse = new RPF_Response_Error();
		$controllerResponse->errorText = $error;
		$controllerResponse->responseCode = $responseCode;
		$controllerResponse->params = $params;
		return $controllerResponse;
	}
	
	/**
	* RFC 2616 
	*/
	public function assertPostOnly()
	{	
		if($this->request->requestMethod != 'POST')
		{
			throw new Exception('HTTP POST required in '. __CLASS__ . "::" . __METHOD__);
		}
	}
	
	/**
	* Checks that mandatory fields exists in input data
	*
	* @param array $inputData
	* @param array  $mandatoryFields
	*/
	public function checkMandatoryFields(array &$inputData, array $mandatoryFields)
	{
		foreach ($mandatoryFields as $key ) 
		{
			if (!isset($inputData[$key]) || empty($inputData[$key]) )
			{
				throw new Exception("Mandatory field $key is not set in ". __CLASS__ . "::" . __METHOD__);
			}
			if(!array_key_exists($key, array_change_key_case($this->request->httpPOSTParams, CASE_LOWER) )
					&&  !array_key_exists($key, array_change_key_case($this->request->httpGETParams, CASE_LOWER) )
			){
				throw new Exception("Mandatory field $key is not set in ". __CLASS__ . "::" . __METHOD__);
			}
		}
	}
	
	/**
	* Deletes empty fields from input data
	*
	* @param array $inputData
	*/
	public function clearEmptyFields(array &$inputData)
	{
		foreach($inputData as $key => $field)
		{
			if(empty($field) 
				&& !array_key_exists($key, array_change_key_case($this->request->httpPOSTParams, CASE_LOWER) )
					&&  !array_key_exists($key, array_change_key_case($this->request->httpGETParams, CASE_LOWER) )
			){
				unset($inputData[$key]);
			}
		}
	}
	
}