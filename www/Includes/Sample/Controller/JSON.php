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
 * Sample action controller for "sample" extension.
 * Creates response with a new View rendering data into JSON representation.
 *
 */
 
class Sample_Controller_JSON extends RPF_Controller_Abstract  
{
	public function __construct()
	{
		parent::__construct();
		
		// Filtered input data:
		$input  = new RPF_Input($this->request->getRequestData());
		$regionId = $input->filterSingle('region_id', RPF_Input::UINT);
		
		// Get test data for sample JSON API:
		$model =  new Sample_Model_Test();
		$outData['regions'] = $model->getAllRegions();
		$model->prepareRegions($outData['regions']);
		
		$outData['territories'] = $model->getTerritoryByRegionId($regionId);
		$model->prepareTerritories($outData['territories']);
		
		$outData['region_id'] =  $regionId;
		
		$this->response =  $this->responseView('Sample_View_JSON', null, $outData);
	}

}