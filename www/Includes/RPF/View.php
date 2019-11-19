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
* Abstract class for a view. New view types must extend this.
* Views must implement Render method. These methods take no arguments.
*/

abstract class RPF_View
{
	/**
	*  Template name to render
	*
	* @string | null
	*/
	protected $_templateName;

	/**
	* View params
	*
	* @array
	*/
	protected $_params = array();

	/**
	* Constructor
	*
	* @param array                         View params
	* @param string                        Template name to render (possibly ignored)
	*/
	public function __construct(array $params = array(), $templateName = '')
	{
		$this->_templateName = $templateName;

		if ($params)
		{
			$this->setParams($params);
		}
	}
	
	/**
	* Add an array of params to the view. Overwrites parameters with the same name.
	*
	* @param array
	*/
	public function setParams(array $params)
	{
		$this->_params = array_merge($this->_params, $params);
	}
	
}