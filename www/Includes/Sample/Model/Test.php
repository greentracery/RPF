<?php
/**
 * Sample Extension for RPF framework
 *
 * @author A.Mikhaylichenko (greentracery@gmail.com)
 * @package Sample
 * @version    0.1b
 */
 
 /**
  * This class simulate data source & generate test data for sample action controller
  *
  */

class Sample_Model_Test extends RPF_Model_Abstract
{
	function __construct()
	{
		$Application = RPF_Application::getInstance();
		$Application->config['db']['type'] = 'sqlite'; // override config variable;

		parent::__construct();
	}

	public function getDebugInfo()
	{
		$outData['request'] = (array)$this->request;
		$outData['extension'] = (array)$this->extension;
		$outData['action'] = (array)$this->action;
		
		foreach($outData['request'] as &$property)
		{
			if(!is_array($property))
			{
				$property = htmlspecialchars($property);
			}
		}
		
		return $outData;
	}
	
	/**
	* Get data from table `region` from test sqlite database `Northwind`
	*/
	public function getAllRegions()
	{
		$qs = " SELECT RegionID, RegionDescription FROM region ";
		
		$order = array('regionid' => 'ASC');
		
		$qb = new RPF_Model_QueryBuilder( $qs, null, $order );
		
		return $this->fetch($qb->query, $qb->placeholders);
	}
	
	public function prepareRegions(&$regions)
	{
		foreach ($regions as $i => $region)
		{
			$regions[$i]['RegionDescription'] = trim($region['RegionDescription']);
		}
	}
	
	/**
	* Get data from table `territories` from test sqlite database `Northwind`
	*/
	public function getTerritoryByRegionId($regionId = 0)
	{
		$qs = " SELECT TerritoryID, TerritoryDescription, RegionID FROM territories ";
		
		if(0 != $regionId)
		{
			$conditions = array(
				'regionid' => array('condition' => '=', 'value' => $regionId)
			);
		}
		else
		{
			$conditions = null;
		}
		
		$order = array('territoryid' => 'ASC');
		
		$qb = new RPF_Model_QueryBuilder( $qs, $conditions, $order );
		
		return $this->fetch($qb->query, $qb->placeholders);
	}
	
	public function prepareTerritories(&$territories)
	{
		foreach ($territories as $i => $territory)
		{
			$territories[$i]['TerritoryDescription'] = trim($territory['TerritoryDescription']);
		}
	}
	
	/**
	* Get data from table `categories` from test sqlite database `Northwind`
	*/
	public function getAllCategories()
	{
		$qs = " SELECT CategoryID, CategoryName FROM categories ";
		
		$order = array('categoryid' => 'ASC');
		
		$qb = new RPF_Model_QueryBuilder( $qs, null, $order );
		
		return $this->fetch($qb->query, $qb->placeholders);
	}
}