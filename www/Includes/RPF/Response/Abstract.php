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
 * Abstract class for a contoller response. New response types must extend this.
 * Responses must implement SendResponse method. These methods take no arguments.
 *
 */

abstract class RPF_Response_Abstract
{
	public $params = array();
	
	public $viewName = '';

	public $responseCode = 200;
	
	abstract public function SendResponse();
}