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
 * Default actionController of application. Create redirect to extension/actionController,
 * wich is defined in config file, or to sample extension.
 * 
 */
 
class RPF_Controller_Index extends RPF_Controller_Abstract  
{
	public function __construct()
	{
		parent::__construct();
		
		
		if(!empty($this->config['default']['action']))
		{
			$defaultAction = $this->config['default']['action']; // Configured default extension/action
		}
		else
		{
			throw new Exception('Default package/action is not set in config');
		}
		
		$defaultActionLink = RPF_Link::buildLink($defaultAction);
		$this->response =  $this->responseRedirect($defaultActionLink, array());
		
	}

}