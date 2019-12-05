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
 * Sample action controller. Default action controller for "sample" extension.
 * Creates response with a new View rendering template into HTML code.
 *
 */

class Sample_Controller_Index extends RPF_Controller_Abstract  
{
	public function __construct()
	{
		parent::__construct();
		
		// Filtered input data:
		$input  = new RPF_Input($this->request->getRequestData());
		$regionId = $input->filterSingle('region_id', RPF_Input::UINT);
		
		// Get test data for sample sample template:
		$model =  new Sample_Model_Test();
		$outData['regions'] = $model->getAllRegions();
		$model->prepareRegions($outData['regions']);
		
		$outData['territories'] = $model->getTerritoryByRegionId($regionId);
		$model->prepareTerritories($outData['territories']);
		
		$outData['region_id'] =  $regionId;
		
		$this->response =  $this->responseView('Sample_View_Index', 'Sample', $outData);
	}

}