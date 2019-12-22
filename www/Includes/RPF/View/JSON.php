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
 * Converts output data in to JSON representation and outputs it.
 *
 */
 
class RPF_View_JSON extends RPF_View
{
	public function Render()
	{
		if(!defined('JSON_UNESCAPED_UNICODE')) // PHP v.<5.4
		{
			$out = json_encode($this->_params);
			// Поскольку php функция json_encode преобразует все не-ASCII символы в unicode-сущности. 
			 $out= preg_replace_callback(
						'/\\\\u([0-9a-f]{4})/i', 
						function($match) {
							return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
						}, 
						$out
					);
		}
		else // PHP v.>=5.4
		{
			$out = json_encode($this->_params, JSON_UNESCAPED_UNICODE);
		}
		
		header("Cache-Control: no-store, no-cache, must-revalidate"); 
		header("Expires: " .  date("r"));
		header("X-Server: RPF");
		header("Content-Type: application/json; charset=utf-8");
		header("Content-Length: ".strlen($out));
		$this->_custom_header();
		$this->_cors_header();
		echo $out;
		exit(0);
	}
}