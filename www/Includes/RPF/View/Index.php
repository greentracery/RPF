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
 * Substitutes output data in to the template, 
 * render the template to HTML representation and outputs it.
 *
 */
 
class RPF_View_Index extends RPF_View
{
	public function Render()
	{
		$templateEngine = new RPF_TemplateEngine();
		
		foreach($this->_params as $key => $value)
		{
			$templateEngine->assign($key, $value);
		}
		
		header("Cache-Control: no-store, no-cache, must-revalidate"); 
		header("Expires: " .  date("r"));
		header("X-Server: RPF");
		
		$templateEngine->display($this->_templateName.'.tpl');
	}
}