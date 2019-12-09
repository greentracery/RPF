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
	/**
	*  Image stream in the string
	*
	* @string image_data
	*/
	protected $image_data;
	
	/**
	*  Image format (JPG, PNG, GIF etc...)
	*
	* @string image_format
	*/
	protected $image_format;
	
	/**
	*  Image MIME Type
	*
	* @string image_mime_type
	*/
	protected $image_mime_type;
	
	/**
	*  Image unique identifier for HTTP header
	*
	* @string image_etag
	*/
	protected $image_etag;
	
	/**
	*  Cache time, s
	*/
	const CACHE_TIME = 300;
	
	/**
	*  Image size, bytes
	*
	* @int image_filesize
	*/
	protected $image_filesize;
	
	/**
	*  Resize images using PHP Gd Library
	*
	* @bool image_resize = false
	* WARNING! Using image_resize = true may degrade image quality.
	*/
	protected $image_resize = false;
	
	public function Render()
	{
		$in_data = $this->_params;
		
		if(isset($in_data['image_data'])) 
		{
			$this->image_data = $in_data['image_data'];
		}
		else
		{
			throw new Exception('Empty Image stream in'. __CLASS__ . "::" . __METHOD__);
		}
		if(isset($in_data['image_format'])) $this->image_format = strtolower($in_data['image_format']);
		
		if(isset($in_data['image_mime_type'])) $this->image_mime_type = $in_data['image_mime_type'];
		
		if(isset($in_data['image_filesize'])) $this->image_filesize = $in_data['image_filesize'];
		
		if(isset($in_data['image_resize'])) $this->image_resize = $in_data['image_resize'];
		
		$this->image_etag = md5($in_data['image_data']);
		
		if (function_exists('imagecreatefromstring') && function_exists('getimagesizefromstring') 
		&& $this->image_resize == true)
		{
			try
			{
				$img = imagecreatefromstring($this->image_data);
				if(false != $img)
				{
					$imagesize = getimagesizefromstring($this->image_data);
					$this->_sendGDImage ($img, $imagesize[2]);
				}
				else
				{
					$this->_sendSimpleImage();
				}
			}
			catch(Exception $e)
			{
				$this->_sendSimpleImage();
			}
		}
		else
		{
			$this->_sendSimpleImage();
		}
		exit(0);
	}
	
	/**
	* @param resourse $image
	* @param integer $image_fromat. 
	* Supports IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, 
	* IMAGETYPE_BMP, IMAGETYPE_WEBP, IMAGETYPE_WBMP 
	*/
	protected function  _sendGDImage ($image, $image_format = IMAGETYPE_JPEG)
	{
		try
		{
			$image_file_ext = image_type_to_extension($image_format);
			$image_mime_type = image_type_to_mime_type($image_format);
			ob_start();
			switch($image_format)
			{
				case IMAGETYPE_JPEG:
					$Gd_Createimage_Func = 'imagejpeg';
				case IMAGETYPE_GIF:
					$Gd_Createimage_Func = 'imagegif';
				case IMAGETYPE_PNG:
					$Gd_Createimage_Func = 'imagepng';
				case IMAGETYPE_BMP:
					$Gd_Createimage_Func = 'imagebmp';
				case IMAGETYPE_WEBP:
					$Gd_Createimage_Func = 'imagewebp';
				case IMAGETYPE_WBMP:
					$Gd_Createimage_Func = 'imagewbmp';
				default:
					$Gd_Createimage_Func = 'imagejpeg';
			}
			if(!function_exists($Gd_Createimage_Func)) 
			{
				throw new Exception('Function '.$Gd_Createimage_Func.' is not defined in'. __CLASS__ . "::" . __METHOD__);
			}
			imagejpeg($image);
			$size = ob_get_length();
			header('Content-Type:'.$image_mime_type);
			header('Content-Length: ' . $size);
			header('Expires: ' .  date("r", time() + self::CACHE_TIME));
			header('Etag: '.$this->image_etag);
			header('Content-disposition: filename="'.$this->image_etag.$image_file_ext.'"');
			header('Pragma: public');
			header('X-Server: RPF');
			header('X-Server-Gd: Gd');
			ob_end_flush();
			imagedestroy($image);
		}
		catch(Exception $e)
		{
			// somthing is wrong.. start simple image sending;
			// что-то пошло не так.. запустим простое создание изображения
			$this->_sendSimpleImage();
		}
	}
	
	protected function _sendSimpleImage()
	{
			if(!empty($this->image_mime_type))
			{
				$image_mime_type = $this->image_mime_type;
			}
			elseif(!empty($this->image_format))
			{
				$image_mime_type = 'image/'.$this->image_format;
			}
			else
			{
				$image_mime_type = image_type_to_mime_type(IMAGETYPE_JPEG);
			}
			
			if(!empty($this->image_filesize))
			{
				$image_size = $this->image_filesize;
			}
			else
			{
				$image_size = strlen($this->image_data);
			}
			
			header('Content-Type: '.$image_mime_type);
			header('Content-Length: ' . $image_size);
			header('Expires: ' .  date("r", time() + self::CACHE_TIME));
			header('Etag: '.$this->image_etag);
			header('Content-disposition: filename="'.$this->image_etag.'" ');
			header('Pragma: public');
			header('X-Server: RPF');
			header('X-Server-Gd: Simple');
			echo $this->image_data;
			exit(0);
	}
	
}