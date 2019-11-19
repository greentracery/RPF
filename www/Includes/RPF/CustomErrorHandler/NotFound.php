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
 * Creates response with defined HTTP response code.
 */
 
class RPF_CustomErrorHandler_NotFound extends RPF_Controller_Abstract  
{
	public function __construct()
	{
		parent::__construct();
		
		$this->response =  $this->responseError('404 Not Found', 404);
	}
}