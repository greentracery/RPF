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
 * Link Builder
 *
 */
 
class RPF_Link
{
	/**
	 * @var string
	 */
	protected static $_indexRoute = 'index.php';

	protected $_linkString = '';

	/**
	 * Constructor. Use the static methods in general. However, you can create
	 * an object of this type from a link builder to generate an arbitrary URL.
	 *
	 * @param string $linkString
	 */
	public function __construct($linkString)
	{
		$this->_linkString = $linkString;
	}
	
	/**
	 * @return string Link
	 */
	public function __toString()
	{
		return $this->_linkString;
	}

	/**
	 * Builds a link to resource. The type should contain a prefix
	 * optionally split by a "/" with the specific action (eg "templates/edit").
	 *
	 * @param string $fullActionRoute Prefix and Action
	 * @param array $extraParams Additional params
	 *
	 * @return string The link
	 */
	public static function buildLink($fullActionRoute, array $extraParams = array())
	{
		$queryString = '';
		$paramElements = array();
		
		foreach($extraParams as $name => $value)
		{
			$paramElements[] = urlencode($name).'='.urlencode($value);
		}
		
		//$FrontController = RPF_FrontController::getInstance();
		//$baseUrl = $FrontController->request->baseUrl;
		$targetUrl = '/'.self::$_indexRoute."/".$fullActionRoute;
		
		if (count($paramElements) > 0)
		{
			$queryString = implode('&', $paramElements);
			$queryString = ((strpos($targetUrl, '?') === false)? "?" : "&").$queryString;
		}
		return $targetUrl.$queryString;
	}
}