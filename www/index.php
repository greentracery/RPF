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
 * Single entry point. Get (or create) instance of autoloader,
 * start the application, create new front controller & process an action
 *
 */
 
$fileDir = dirname(__FILE__);

// Autoloader
require($fileDir.DIRECTORY_SEPARATOR .'Includes/RPF/Autoloader.php');
RPF_Autoloader::getInstance()->setupAutoloader($fileDir . '/Includes');

// Start Application
try
{
	RPF_Application::initialize($fileDir . '/Configs', $fileDir);
	$app = new RPF_FrontController();
	$app->ProcessAction();
}
catch(Exception $e)
{
	RPF_ApplicationError::ExceptionHandler($e);
}