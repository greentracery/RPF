<?php
/**
 * Sample Extension for RPF framework
 *
 * @author A.Mikhaylichenko (greentracery@gmail.com)
 * @url https://github.com/greentracery/RPF/
 * @package Sample
 * @version    0.1b
 */

/**
 * Sample action controller for "Sample" extension.
 * Creates response with a new RPF_Redirect.
 *
 */
 
class Sample_Controller_Redirect extends RPF_Controller_Abstract  
{
	public function __construct()
	{
		parent::__construct();
		
		$param = array('referrer' => 'Index');
		
		$this->response =  $this->responseRedirect('/index.php?package=Sample', $param);
	}

}