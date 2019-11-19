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
 * Wrapper for Smarty Templates. 
 *
 */

$fileDir = dirname(__FILE__);

// Reference to Smarty library
require_once ( realpath($fileDir.'/../Smarty/Smarty.class.php') );

class RPF_TemplateEngine extends Smarty 
{
	// Class constructor
	public function __construct() 
	{
		// Call Smarty's constructor
		parent::__construct();
		
		$this->error_reporting = E_ALL & ~E_NOTICE;
		$this->muteExpectedErrors();
		
		$_Application = RPF_Application::getInstance();
		$_config = $_Application->config;
		$_rootDir = $_Application->getRootDir();
		
		// Set configuration of template engine:
		$this->template_dir = $_rootDir.DIRECTORY_SEPARATOR.$_config['smarty']['template_dir'];
		$this->compile_dir = $_rootDir.DIRECTORY_SEPARATOR.$_config['smarty']['compile_dir'];
		$this->config_dir = $_rootDir.DIRECTORY_SEPARATOR.$_config['smarty']['config_dir'];
		$this->cache_dir = $_rootDir.DIRECTORY_SEPARATOR.$_config['smarty']['cache_dir'];
	}
}