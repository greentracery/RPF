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
 * Class implements method SendResponse - 
 * outputs the response with selected http code.
 *
 */
 
class RPF_Response_Error extends RPF_Response_Abstract
{
	public $errorText = '';
	
	public function SendResponse()
	{
		http_response_code($this->responseCode);
		echo $this->errorText;
		exit(0);
	}
}