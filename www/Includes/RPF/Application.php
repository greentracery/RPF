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
 * Base application class. Sets up the environment as necessary 
 * and acts as the registry for the application.
 *
 */

class RPF_Application
{
	public $config = array();                // config

	/**
	* Path to directory containing the application's configuration file(s).
	*
	* @var string
	*/
	protected $_configDir = '.';

	/**
	* Path to applications root directory. Specific directories will be looked for within this.
	*
	* @var string
	*/
	protected $_rootDir = '.';

	/**
	* Stores whether the application has been initialized yet.
	*
	* @var boolean
	*/
	protected $_initialized = false;
	
	/**
	* Instance manager.
	*
	* @var Application
	*/
	protected static $instance;


	function __construct()
	{
		self::$instance = $this;
	}
	
	/**
	* Helper function to initialize the application.
	*
	* @param string Path to application configuration directory. See {@link $_configDir}.
	* @param string Path to application root directory. See {@link $_rootDir}.
	*/
	
	public static function initialize($configDir = '.', $rootDir = '.')
	{
		self::getInstance()->beginApplication($configDir, $rootDir);
	}
	
	/**
	* Begin the application. 
	*
	* @param string Path to application configuration directory. See {@link $_configDir}.
	* @param string Path to application root directory. See {@link $_rootDir}.
	*/
	public function beginApplication($configDir = '.', $rootDir = '.')
	{
		if ($this->_initialized)
		{
			return;
		}
		// Обработка ошибок и исключений
		set_error_handler(array('RPF_ApplicationError', 'PhpErrorHandler'),  E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
		set_exception_handler(array('RPF_ApplicationError', 'ExceptionHandler'));
		register_shutdown_function(array('RPF_ApplicationError', 'FatalErrorHandler'));
		@ini_set("display_errors", 0);
		
		$this->_configDir = $configDir;
		$this->_rootDir = $rootDir;
		$this->config = $this->loadConfig();
	}
	
	/**
	* Load the configuration file. 
	*
	* @return Config
	*/
	public function loadConfig()
	{
		try
		{
			if(file_exists($this->_configDir . '/Config.php'))
			{
				$config = array();
				require($this->_configDir . '/Config.php');
			}
			else
			{
				throw new Exception('Config loading error: not found or access denied');
			}
		}
		catch(Exception $e)
		{
			RPF_ApplicationError::ExceptionHandler($e);
		}
		
		return $config;
	}
	
	/**
	* Gets the RPF_Application instance.
	*
	* @return RPF_Application
	*/
	public static final function getInstance()
	{
        
		if (self::$instance === null) 
		{
			self::$instance = new self();   
		}
 
		return self::$instance;
	}
    
    	/**
	* Manually sets the application instance. Use this to inject a modified version.
	*
	* @param RPF_Application|null
	*/
	public static function setInstance(RPF_Application $app = null)
	{
		self::$_instance = $app;
	}
	
	/**
	* Gets the path to the configuration directory.
	*
	* @return string
	*/
	public function getConfigDir()
	{
		return $this->_configDir;
	}

	/**
	* Gets the path to the application root directory.
	*
	* @return string
	*/
	public function getRootDir()
	{
		return $this->_rootDir;
	}	

	protected static $_memoryLimit = null;

	/**
	 * Sets the memory limit. Will not shrink the limit.
	 *
	 * @param integer $limit Limit must be given in integer (byte) format.
	 *
	 * @return bool True if the limit was set (or already bigger)
	 */
	public static function setMemoryLimit($limit)
	{
		$limit = intval($limit);
		$currentLimit = self::getMemoryLimit();

		if ($limit == -1 || ($limit > $currentLimit && $currentLimit >= 0))
		{
			$success = @ini_set('memory_limit', $limit);
			if ($success)
			{
				self::$_memoryLimit = $limit;
			}

			return $success;
		}

		return true; // already big enough
	}

	public static function increaseMemoryLimit($amount)
	{
		$amount = intval($amount);
		if ($amount <= 0)
		{
			return false;
		}

		$currentLimit = self::getMemoryLimit();
		if ($currentLimit < 0)
		{
			return true;
		}

		return self::setMemoryLimit($currentLimit + $amount);
	}
	
	/**
	 * Gets the current memory limit.
	 *
	 * @return int
	 */
	public static function getMemoryLimit()
	{
		if (self::$_memoryLimit === null)
		{
			$curLimit = @ini_get('memory_limit');
			if ($curLimit === false)
			{
				// reading failed, so we have to treat it as unlimited - unlikely to be able to change anyway
				$curLimit = -1;
			}
			else
			{
				switch (substr($curLimit, -1))
				{
					case 'g':
					case 'G':
						$curLimit *= 1024;
						// fall through

					case 'm':
					case 'M':
						$curLimit *= 1024;
						// fall through

					case 'k':
					case 'K':
						$curLimit *= 1024;
				}
			}

			self::$_memoryLimit = intval($curLimit);
		}

		return self::$_memoryLimit;
	}
	
	/**
	 * Attempts to determine the current available amount of memory.
	 * If there is no memory limit
	 *
	 * @return int
	 */
	public static function getAvailableMemory()
	{
		$limit = self::getMemoryLimit();
		if ($limit < 0)
		{
			return PHP_INT_MAX;
		}

		$used = memory_get_usage();
		$available = $limit - $used;

		return ($available < 0 ? 0 : $available);
	}
	
}