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
	* Custom headers
	*
	* @array
	*/
	protected $_custom_headers = array();
	
	/**
	* CORS domains list
	*
	* @array
	*/
	protected $_cors_domains = array();	

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
	
	/**
	* Sets custom headers, if $this->_custom_headers is not empty
	*/
	protected function _custom_header()
	{
		if(is_array($this->_custom_headers) && count($this->_custom_headers) > 0)
		{
			foreach($this->_custom_headers as $_header_string)
			{
				header($_header_string);
			}
		}
	}
	
	/**
	* Sets 'Access-Control-Allow-Origin' header, if $this->_cors_domains is not empty
	*/
	protected function _cors_header()
	{
		if(is_array($this->_cors_domains) && count($this->_cors_domains) > 0)
		{
			$_origin = implode(' ', $this->_cors_domains);
			header('Access-Control-Allow-Origin: '.$_origin);
		}
	}
}
