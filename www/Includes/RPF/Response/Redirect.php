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
 * Class implements method SendResponse - 
 * outputs the response with http code 302 and new location.
 *
 */
 
class RPF_Response_Redirect extends RPF_Response_Abstract
{
	public $params = array();

	public $redirectTarget = '';
	
	public $responseCode = 302; // REDIRECT
	
	public $protocol = 'http://';
	
	public function SendResponse()
	{
		$queryString = '';
		$paramElements = array();
		foreach($this->params as $name => $value)
		{
			$paramElements[] = urlencode($name).'='.urlencode($value);
		}
		if (count($paramElements) > 0)
		{
			$queryString = implode('&', $paramElements);
			$queryString = ((strpos($this->redirectTarget, '?') === false)? "?" : "&").$queryString;
		}
		
		$FrontController = RPF_FrontController::getInstance();
		$baseUrl = $FrontController->request->baseUrl;
		$replaceCount = 1;
		
		if(strpos($this->redirectTarget, 'https://') === 0)
		{
			$this->protocol = 'https://';
			$this->redirectTarget = str_replace('https://', '', $this->redirectTarget, $replaceCount);
		}
		
		if(strpos($this->redirectTarget, 'http://') === 0)
		{
			$this->protocol = 'http://';
			$this->redirectTarget = str_replace('http://', '', $this->redirectTarget,  $replaceCount);
		}
		
		if(strpos($this->redirectTarget, '/') === 0)
		{
			$baseUrl = str_replace('/', '', $baseUrl);
			$redirectUrl = $this->protocol . $baseUrl . $this->redirectTarget . $queryString;
		}
		else
		{
			$redirectUrl = $this->protocol . $this->redirectTarget . $queryString;
		}

		http_response_code($this->responseCode);
		
		header("Location: $redirectUrl");
		exit(0);
	}
}