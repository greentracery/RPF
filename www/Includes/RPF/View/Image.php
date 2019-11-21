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
 * Gets image from binary data and sends it.
 *
 */
 
class RPF_View_Image extends RPF_View
{
	public function Render()
	{
		$out = $this->_params;
		if(!isset($out['image_mime_type'], $out['image_size'], $out['image_data'])) exit(1);
		
		header("Cache-Control: no-store, no-cache, must-revalidate"); 
		header("Expires: " .  date("r"));
		header("X-Server: RPF");
		header("Content-Type: image/".$out['image_mime_type']."; ");
		header("Content-Length: ".$out['image_size']);
		echo $out['image_data'];
		exit(0);
	}
}