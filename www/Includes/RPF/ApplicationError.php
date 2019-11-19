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
 * Class contains user-defined error handler & exception handler functions
 *
 */
 
class RPF_ApplicationError
{
	/**
	* @param Exception | Throwable
	*/
	public static function  ExceptionHandler($e)
	{
		self::LogException($e);
		if(PHP_SAPI != 'cli') http_response_code(500);
		$code = $e->getCode();
		exit($code);
	}
	
	public static function FatalErrorHandler()
	{
		$error = @error_get_last();
		if (!$error)
		{
			return;
		}
		
		if (empty($error['type']) || !($error['type'] & (E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR)))
		{
			return;
		}
		$code = $error['type'];
		$message = $error['message'];
		$file = $error['file'];
		$line = $error['line'];
		$logmessage = "Fatal error: $message (error code $code). Process terminated. File $file, line $line. ";
		error_log($logmessage);
		if(PHP_SAPI != 'cli') http_response_code(500);
		exit($code);
	}
	
	/**
	* @param int $errorType - Error code
	* @param string $errorString - Error message
	* @param string $errorFile - PHP file contains error
	* @param int $errorLine - Line in the file contains error
	*/
	public static function PhpErrorHandler($errorType, $errorString, $errorFile, $errorLine)
	{
		$message = $errorString;
		$code = $errorType;
		$file = $errorFile;
		$line = $errorLine;
		$logmessage = "PHP error: $message (error code $code). Process terminated. File $file, line $line. ";
		error_log($logmessage);
		if(PHP_SAPI != 'cli') http_response_code(500);
		exit($code);
	}
	
	/**
	* @param Exception | Throwable
	*/
	public static function LogException($e)
	{
		$code = $e->getCode();
		$message = $e->getMessage();
		$file = $e->getFile();
		$line = $e->getLine();
		$logmessage = "Exception: $message  (error code $code). File $file, line $line. ";
		error_log($logmessage);
	}
}