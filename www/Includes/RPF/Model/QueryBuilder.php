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
* SQL Query Builder. Create query string with placeholders (if exists), conditions, order and limit; bind placeholders.
*
*/

class RPF_Model_QueryBuilder
{
	/**
	* SQL Query 
	*
	* @string
	*/
	public $query;
	
	/**
	* Placeholders for query
	*
	* @array
	*/
	public $placeholders;
	
	/**
	* @param string $query            - SELECT { * | list of fieldnames } FROM { tablename-1} [ alias-1]
	* 	[ [ LEFT | RIGHT | INNER ] JOIN ON { tablename-2 } [ alias-2] ON { join conditions} ... ] - optional
	* 	[ WHERE ...] [ ORDER BY ... ] [ LIMIT ... ] - optional
	*
	* Optional conditions `WHERE`, `ORDER`, `LIMIT` can be defined with next params:
	* @param array $conditions      -  see param for self::setWhereConditions method;
	* @param array $order               -  see param for self::setQueryOrder  method;
	* @param array|integer $limit   -  see param for self::setQueryLimit method;
	*
	* Sample of using this methods you can see in 'Sample_Model_Test' class.
	* 
	*/
	public function __construct($query, $conditions = null, $order = null, $limit = null)
	{
		$where = $this->setWhereConditions($conditions);
		$whereCondition = $where['wherecondition'];
		$this->placeholders = $where['whereholder'];
			
		$orderCondition = $this->setQueryOrder($order);
		$limitCondition = $this->setQueryLimit($limit);

		$this->query = $query.$whereCondition.$orderCondition.$limitCondition; 
	}
	
	/**
	* @param array $conditions | null
	*
	* Sample:
	* array(
	*		'conversation_id' => array('value' => $conversationId, 'condition' => '=' ),
	*		'user_id' => array('value' => $userId, 'condition' => '=' ),
	*		'deleted' => array('value' => 0, 'condition' => '=' )
	*         )
	*
	* @return array ('whereConditions' => string, 'whereHolders' = array)
	*/
	protected final function setWhereConditions($conditions)
	{
		$whereClause = array(); $whereHolder = array();
		if(!is_null($conditions) && is_array($conditions) && count($conditions) != 0)
		{
			foreach($conditions as $key => $condition)
			{
				if( is_array($condition) && array_key_exists('value', $condition) 
					&& array_key_exists('condition', $condition) )
				{
				
					if( in_array( strtoupper( trim($condition['condition']) ), array('<', '>', '=', '<>', 'IS NOT', 'IS', '<=', '>=')) )
					{
						$whereClause[] = " `" . $key . "` " .$condition['condition'] . " :" . $key . " "; // name of placeholder like `:fieldname`
						$whereHolder[$key] = $condition['value'];
					
						if(strtoupper( trim($condition['condition']) ) == 'IS' || strtoupper( trim($condition['condition']) ) == 'IS NOT')
						$whereHolder[$key] = NULL;
					}
				
					if( strtoupper( trim($condition['condition']) ) ==  'IN' && is_array($condition['value']) )
					{
						foreach($condition['value'] as $k => $value)
						{
							$InString[$key][$k] =  " :" . $key . $k . " "; // name of placeholder like `:fieldname`
							$whereHolder[$key.$k] = $value;
						}
						$whereClause[] = "  `" . $key . "` IN (".implode(',', $InString[$key]).") ";
					}
				
					if( strtoupper( trim($condition['condition']) ) ==  'BETWEEN' 
						&& is_array($condition['value']) && count($condition['value']) == 2)
					{
						$whereClause[] = " `" . $key . "` BETWEEN :" . $key . "0 AND :" . $key . "1 ";
						$whereHolder[$key."0"] = $condition['value'][0];
						$whereHolder[$key."1"] = $condition['value'][1];
					}
				}
			}
		}
		$whereCondition = '';
		if(count($whereClause) > 0)
			$whereCondition = " WHERE " . implode(' AND ', $whereClause);
		
		return array('wherecondition' =>  $whereCondition, 'whereholder' => $whereHolder);
	}
	
	/**
	* @param array $order | null
	*
	* Sample:
	* array(
	*		'conversation_id' => 'ASC',
	*		'user_id' => 'DESC'
	*         )
	*
	* @return string
	*/
	protected final function setQueryOrder($order)
	{
		$orderClause = array();
		if(!is_null($order) && count($order) != 0)
		{
			foreach($order as $key => $value)
			{
				if( in_array( strtoupper($value), array('ASC', 'DESC') ) )
				{
					$orderClause[] = " `" . $key . "` " . $value. " ";
				}
			}
		}
		$orderCondition = '';
		if(count($orderClause) > 0)
			$orderCondition = " ORDER BY " . implode(',' , $orderClause);
			
		return $orderCondition;
	}
	
	/**
	* @param array $limit | int $limit | null
	*
	* @return string
	*/
	protected final function setQueryLimit($limit)
	{
		$limitCondition = '';
		if(!is_null($limit))
		{
			if(is_array($limit))
			{
				if(count($limit) != 0)
				{
					$limitCondition = " LIMIT " .( implode( ',' , array_slice($limit,0, 2) ) );
				}
			}
			else
			{
				$limitCondition = " LIMIT " . (int)$limit;
			}
		}
		
		return $limitCondition;
	}
	
}