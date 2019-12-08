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
 * Gets image from binary data and sends it.
 *
 */
 
class RPF_View_Image extends RPF_View
{
	public function Render()
	{
		$out = $this->_params;
		if(!isset($out['image_mime_type'], $out['image_size'], $out['image_data'])) exit(1);
		
		$etag = md5($out['image_data']);
		$filename = (isset($out['image_filename']))? 
			$out['image_filename'] : $etag.'.'.$out['image_mime_type'];
		
		//header("Cache-Control: no-store, no-cache, must-revalidate"); 
		header("Pragma: public");
		header("Etag: ".$etag);
		header("Expires: " .  date("r", time() + 300));
		header("X-Server: RPF");
		header("Content-Type: image/".$out['image_mime_type']."; ");
		header("Content-Length: ".$out['image_size']);
		header('Content-disposition: filename="'.$filename.'"');
		echo $out['image_data'];
		exit(0);
	}
}