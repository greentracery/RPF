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
 * Base autoloader class. This must be the first class loaded and setup as the
 * application depends on it for loading classes.
 *
 */

class RPF_Autoloader
{
	/**
	* Stores whether the autoloader has been setup yet.
	*
	* @var boolean
	*/
	protected $_setup = false;
	
	/**
	* Instance manager.
	*
	* @var RPF_Autoloader
	*/
	protected static $_instance;
	
	/**
	* Path to directory containing the application's library.
	*
	* @var string
	*/
	protected $_rootDir = '.';
	

	/**
	* Protected constructor. Use {@link getInstance()} instead.
	*/
	protected function __construct()
	{
	}
	
	/**
	* Setup the autoloader. This causes the environment to be setup as necessary.
	*
	* @param string Path to application library directory. See {@link $_rootDir}
	*/
	public function setupAutoloader($rootDir)
	{
		if ($this->_setup)
		{
			return;
		}

		$this->_rootDir = $rootDir;
		$this->_setupAutoloader();

		$this->_setup = true;
	}
	
	/**
	* Internal method that actually applies the autoloader. See {@link setupAutoloader()}
	* for external usage.
	*/
	protected function _setupAutoloader()
	{
		if (@ini_get('open_basedir'))
		{
			// many servers don't seem to set include_path correctly with open_basedir, so don't use it
			set_include_path($this->_rootDir . PATH_SEPARATOR . '.');
		}
		else
		{
			set_include_path($this->_rootDir . PATH_SEPARATOR . '.' . PATH_SEPARATOR . get_include_path());
		}
		
		spl_autoload_register(array($this, 'autoload'));
	}
	
	/**
	* Autoload the specified class.
	*
	* @param string $class Name of class to autoload
	*
	* @return boolean
	*/
	public function autoload($class)
	{

		if (class_exists($class, false) || interface_exists($class, false))
		{
			return true;
		}
		
		$filename = $this->autoloaderClassToFile($class);
		if (!$filename)
		{
			return false;
		}
		if (file_exists($filename))
		{
			include_once($filename);
			return (class_exists($class, false) || interface_exists($class, false));
		}
		
		return false;
	}
	
	/**
	* Resolves a class name to an autoload path.
	*
	* @param string Name of class to autoload
	*
	* @return string|false False if the class contains invalid characters.
	*/
	public function autoloaderClassToFile($class)
	{
		if (preg_match('#[^a-zA-Z0-9_\\\\]#', $class))
		{
			return false;
		}

		return $this->_rootDir . DIRECTORY_SEPARATOR . str_replace(array('_', '\\'), DIRECTORY_SEPARATOR , $class) . '.php';
	}
	
	
	/**
	* Gets the autoloader instance.
	*
	* @return Autoloader
	*/
	public static final function getInstance()
	{
		if (!self::$_instance)
		{
			self::$_instance = new self();
		}

		return self::$_instance;
	}
	
	/**
	* Manually sets the autoloader instance. Use this to inject a modified version.
	*
	* @param RPF_Autoloader|null
	*/
	public static function setInstance(RPF_Autoloader $loader = null)
	{
		self::$_instance = $loader;
	}
	
	/**
	 * Gets the autoloader's root directory.
	 *
	 * @return string
	 */
	public function getRootDir()
	{
		return $this->_rootDir;
	}
	
}