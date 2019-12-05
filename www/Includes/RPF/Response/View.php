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
 * loads the view, renders the view, and outputs the response.
 *
 */
 
class RPF_Response_View extends RPF_Response_Abstract
{
	public $params = array();
	
	public $viewName = '';

	public $templateName = '';
	
	public function SendResponse()
	{
		$view = new $this->viewName($this->params, $this->templateName);

		http_response_code($this->responseCode);
		
		$view->Render();
	}
}