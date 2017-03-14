<?php

LunaLoader::import("luna_lib.util.LunaPdo");
LunaLoader::import("luna_lib.util.LunaMemcache");

class TableMagtController extends Controller
{
	protected $_PDO_NODE_NAME="";			//PDO 配置节点名称
	protected $_MEMCACHE_NODE_NAME="";		//Memcache 配置节点名称
	protected $_MEMCACHE_PREFIX_KEY="";		//Memcache 存储前缀
	
	protected $_memcacheKey=array();
	
	protected $_SERACH_FIELD_COMPARE_TYPE=array();
	
	protected $_EXTRA_SEARCH_FIELDS=array();	//More Search condition support.
	protected $_APPEND_SERACH_FIELD_IF_NOT_SEARCH=false;
	
	protected $_FIELD_DEFAULT_VALUE=array();
	
	protected function getData($params)
	{
		$data=array();
		foreach ($params as $paramName){
			$data[$paramName]=trim(Yii::app()->request->getParam($paramName));
		}
		return $data;
	}
	
	protected function getMemcacheAllKeys($mc_key='',$op=''){
		if(empty($mc_key)){
			$mc_key=$this->_MEMCACHE_PREFIX_KEY;
		}
		return @LunaMemcache::GetInstance($this->_MEMCACHE_NODE_NAME,$this->_MEMCACHE_PREFIX_KEY)->getMemcacheAllKeys($mc_key,$op);
	}
	protected function _deleteMemcache($KeyValue)
	{
		if(isset($this->_memcacheKey) && is_array($this->_memcacheKey)){
			foreach ($this->_memcacheKey as $keys){
				$delKey=str_replace("{ID}", $KeyValue, $keys);
				try {
					@LunaMemcache::GetInstance($this->_MEMCACHE_NODE_NAME,$this->_MEMCACHE_PREFIX_KEY)->delete($delKey);
				}
				catch (Exception $ex){}
			}
		}		
	}
	protected function _actionDel($tableName,$primaryKey,$title,$nextUrl,$cacheNameId="")
	{
		$value = Yii::app()->request->getParam($primaryKey,'');
		if(empty($value)){
			$this->exitWithError("参数错误",$nextUrl);
		}
		$ret  =$this->deleteRow($tableName, $primaryKey, $value);
		if ($ret>0){
			$cacheKeyValue=$value;
			if(empty($cacheNameId)==false){
				$cacheKeyValue=Yii::app()->request->getParam($cacheNameId,'');
			}
			$this->_deleteMemcache($cacheKeyValue);
			$this->exitWithSuccess(sprintf("删除%s成功",$title), $nextUrl);
		}else {
			$this->exitWithError(sprintf("删除%s失败",$title),$nextUrl);
		}
	}
	protected  function _actionModify($tableName,$primaryKey,$title,$nextUrl,$renderName,$columnNames,$viewName="modify",$cacheIdName="")
	{
		$value = Yii::app()->request->getParam($primaryKey,'');
		if(empty($value)){
			$this->exitWithError("参数错误",$nextUrl);
		}
		$evnData=$this->getOneRowByFieldName($tableName, $primaryKey, $value);
		if($evnData==false || is_array($evnData)==false || count($evnData)==0){
			$this->exitWithError("参数值错误",$nextUrl);
		}
		$this->renderData[$renderName]=$evnData;
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if($submit){
			$data=$this->getData($columnNames);
			try {
				$ret = $this->updateRow($data, $tableName, $primaryKey, $value);
				if($ret>0){
					$cacheKeyValue=$value;
					if(empty($cacheIdName)==false){
						$cacheKeyValue=Yii::app()->request->getParam($cacheIdName,'');
					}
					$this->_deleteMemcache($cacheKeyValue);
					$this->exitWithSuccess(sprintf("修改%s成功",$title),$nextUrl);
				}else {
					$this->alert('error',sprintf("修改%s失败",$title));
				}
			} catch (Exception $e) {
				$this->alert('error',sprintf("修改%s失败",$title));
			}
		}
		$this->render($viewName,$this->renderData);
	}
	protected function _actionAdd($tableName,$title,$nextUrl,$columnNames,$viewName="add",$memcacheKeys=array(),$additionColumns=array())
	{
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if ($submit){
			$data=$this->getData($columnNames);
			$data=array_merge($data,$additionColumns);
			try {
				$ret = $this->insertRow($data, $tableName);
				if($ret>0){
					$this->_deleteMemcache("");
					$this->exitWithSuccess(sprintf("增加%s成功",$title),$nextUrl);
				}else {
					$this->alert('error',sprintf("增加%s失败",$title));
				}
			} catch (Exception $e) {
				$this->alert('error',sprintf("增加%s失败",$title));
			}
		}
		$this->render($viewName,$this->renderData);
	}
	protected function _actionIndex($tableName,$searchFieldName,$pageUrl,$renderName,$viewName="index",$orderFiledName="")
	{
		$value 	  = Yii::app()->request->getParam($searchFieldName,$this->getDefaultValue($searchFieldName));
		$page_no  = Yii::app()->request->getParam('page_no',1);
		$search   = Yii::app()->request->getParam('search','');
	
		$page_size = 50;
		$page_no=$page_no<1?1:$page_no;
		$info =$this->getRowsCountByFieldName($tableName, $searchFieldName, $value);
	
		$row_count = $info['num'];
		$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
		$total_page=$total_page<1?1:$total_page;
		$page_no=$page_no>($total_page)?($total_page):$page_no;
		$start = ($page_no - 1) * $page_size;
		
		$PageShowUrl=$pageUrl;
		if($search==1){
			if(stripos($pageUrl,"?")==false){
				$PageShowUrl=sprintf("%s?%s=%s&search=1",$pageUrl,$searchFieldName,$value);
			}else{
				$PageShowUrl=sprintf("%s&%s=%s&search=1",$pageUrl,$searchFieldName,$value);
			}
			
			foreach ($this->_EXTRA_SEARCH_FIELDS as $key => $feildDefvalue){
				switch ($feildDefvalue["compartion_type"]){
					case "like":
					case "equal":
					case "not equal":
					case "greater":
					case "less":
						$extraFeildValue=trim(Yii::app()->request->getParam($feildDefvalue["field_name"],''));
						if(empty($extraFeildValue)==false){
							$PageShowUrl.=sprintf("&%s=%s",$feildDefvalue["field_name"],$extraFeildValue);
						}
						break;
					case "between":
						$sFieldValue=trim(Yii::app()->request->getParam($feildDefvalue["field_name_start"],''));
						$eFieldValue=trim(Yii::app()->request->getParam($feildDefvalue["field_name_end"],''));
						if(empty($sFieldValue)==false){
							$PageShowUrl.=sprintf("&%s=%s",$feildDefvalue["field_name_start"],$sFieldValue);
						}
						if(empty($eFieldValue)==false){
							$PageShowUrl.=sprintf("&%s=%s",$feildDefvalue["field_name_end"],$eFieldValue);
						}
						break;
					case "not null":
						$extraFeildValue=trim(Yii::app()->request->getParam($feildDefvalue["field_name"],''));
						if(empty($extraFeildValue)==false){
							$PageShowUrl.=sprintf("&%s=%s",$feildDefvalue["field_name"],$extraFeildValue);
						}
						break;
					default:
						break;
				}
			}
		}else if($this->_APPEND_SERACH_FIELD_IF_NOT_SEARCH){
			if(stripos($pageUrl,"?")==false){
				$PageShowUrl=sprintf("%s?%s=%s&search=1",$pageUrl,$searchFieldName,$value);
			}else{
				$PageShowUrl=sprintf("%s%s=%s&search=1",$pageUrl,$searchFieldName,$value);
			}				
		}		
		$page_str=Util::showPager($PageShowUrl,$page_no,$page_size,$row_count);
		$evnList =$this->getPageRowsByFieldName($tableName, $searchFieldName, $value, $page_size, $start,$orderFiledName);
		
		$evnList=$this->getPageRowsExtentData($evnList);
	
		$this->renderData[$renderName]			= $evnList;
		$this->renderData['page']				= $page_str;
		$this->renderData['page_no'] 			= $page_no;
		$this->render($viewName,$this->renderData);
	}
	
	protected function deleteRow($tableName,$primaryKey,$value)
	{
		$sql=sprintf("delete  from %s where %s=?",$tableName,$primaryKey);
		$params=array($value);
		$rowCount=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
		return $rowCount;
	}
	
	protected function updateRow($data,$tableName,$primaryKey,$value)
	{
		$params=array();
		$sqlFields="";
		foreach ($data as $fieldName => $fieldValue){
			if(empty($sqlFields)){
				$sqlFields=sprintf("%s=:%s",$fieldName,$fieldName);
			}else{
				$sqlFields=sprintf("%s,%s=:%s",$sqlFields,$fieldName,$fieldName);
			}
			$params[sprintf(":%s",$fieldName)]=$fieldValue;
		}
		$sql=sprintf("update %s set %s where %s=:%s",$tableName,$sqlFields,$primaryKey,$primaryKey);
		$params[sprintf(":%s",$primaryKey)]=$value;
		$rowCount=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
		return $rowCount;
	}
	
	protected function insertRow($data,$tableName)
	{
		$params=array();
		$sqlFields="";
		$sqlParamFields="";
		foreach ($data as $fieldName => $fieldValue){
			if(empty($sqlFields)){
				$sqlFields=$fieldName;
				$sqlParamFields=sprintf(":%s",$fieldName);
			}else{
				$sqlFields=sprintf("%s,%s",$sqlFields,$fieldName);
				$sqlParamFields=sprintf("%s,:%s",$sqlParamFields,$fieldName);
			}
			$params[sprintf(":%s",$fieldName)]=$fieldValue;
		}
		$sql=sprintf("insert into %s (%s) values (%s)",$tableName,$sqlFields,$sqlParamFields);
		$rowCount=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
		return $rowCount;
	}
	
	protected function getOneRowByFieldName($tableName,$fieldName,$value)
	{
		$sql=sprintf("select * from %s where %s=?",$tableName,$fieldName);
		$params=array($value);
		$evnDefine=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($evnDefine) && is_array($evnDefine) && count($evnDefine)>0){
			return $evnDefine[0];
		}
		return false;
	}
	
	protected function getRowsByFieldName($tableName,$fieldName,$value)
	{
		$sql=sprintf("select * from %s where %s=?",$tableName,$fieldName);
		$params=array($value);
		$evnDefine=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($evnDefine) && is_array($evnDefine) && count($evnDefine)>0){
			return $evnDefine;
		}
		return array();
	}
	private  function getFieldFormatByFieldName($fieldName)
	{
		if($this->_SERACH_FIELD_COMPARE_TYPE!=null && is_array($this->_SERACH_FIELD_COMPARE_TYPE)){
			foreach ($this->_SERACH_FIELD_COMPARE_TYPE as $key => $value){
				if($key==$fieldName){
					switch ($value){
						case "like":
							return "%s where %s like ?";
						case "not equal":
							return "%s where %s <> ?";
						default:
							break;
					}	
				}				
			}
		}
		return "%s where %s=?";
	}
	protected function getRowsCountByFieldName($tableName,$fieldName,$value)
	{
		$sql=sprintf("select count(*) as num from %s",$tableName);
		$params=array();
		if(empty($value)==false || "0" == $value){
			$sqlFormat=$this->getFieldFormatByFieldName($fieldName);
			$sql=sprintf($sqlFormat,$sql,$fieldName);
			$params=array($value);
			if($sqlFormat=="%s where %s like ?"){
				$params=array("%".$value."%");
			}
		}
		if(!stripos($sql, " where ")){
			$sql.=" where 1=1 ";
		}
		
		foreach ($this->_EXTRA_SEARCH_FIELDS as $key => $value){
			switch ($value["compartion_type"]){
				case "like":
					$extraFeildValue=trim(Yii::app()->request->getParam($value["field_name"],''));
					if(empty($extraFeildValue)==false){
						$sql.=sprintf(" and %s like ? ",$key);
						$params[]="%".$extraFeildValue."%";
					}
					break;
				case "between":
					$sFieldValue=trim(Yii::app()->request->getParam($value["field_name_start"],''));
					$eFieldValue=trim(Yii::app()->request->getParam($value["field_name_end"],''));
					if(empty($sFieldValue)==false && empty($eFieldValue)==false){
						$sql.=sprintf(" and %s between ? and ? ",$key);
						$params[]=$sFieldValue;
						$params[]=$eFieldValue;
					}else if(empty($sFieldValue)==false){
						$sql.=sprintf(" and %s >= ? ",$key);
						$params[]=$sFieldValue;
					}else if(empty($eFieldValue)==false){
						$sql.=sprintf(" and %s <= ? ",$key);
						$params[]=$eFieldValue;						
					}
					break;
				case "equal":
					$extraFeildValue=trim(Yii::app()->request->getParam($value["field_name"],''));
					if(empty($extraFeildValue)==false){
						$sql.=sprintf(" and %s = ? ",$key);
						$params[]=$extraFeildValue;
					}						
					break;
				case "not equal":
					$extraFeildValue=trim(Yii::app()->request->getParam($value["field_name"],''));
					if(empty($extraFeildValue)==false){
						$sql.=sprintf(" and %s <> ? ",$key);
						$params[]=$extraFeildValue;
					}
					break;
				case "greater":
					$extraFeildValue=trim(Yii::app()->request->getParam($value["field_name"],''));
					if(empty($extraFeildValue)==false){
						$sql.=sprintf(" and %s > ? ",$key);
						$params[]=$extraFeildValue;
					}
					break;
				case "less":
						$extraFeildValue=trim(Yii::app()->request->getParam($value["field_name"],''));
						if(empty($extraFeildValue)==false){
							$sql.=sprintf(" and %s < ? ",$key);
							$params[]=$extraFeildValue;
						}
						break;						
				default:
					break;						
			}
		}
		$evnDefine=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($evnDefine) && is_array($evnDefine) && count($evnDefine)>0){
			return $evnDefine[0];
		}
		return array("num" => 0);
	}
	
	protected function getPageRowsByFieldName($tableName,$fieldName,$value,$page_size,$offSet,$orderFiledName="")
	{
		$sql=sprintf("select * from %s",$tableName);
		$params=array();
		if(empty($value)==false || "0" == $value){
			$sqlFormat=$this->getFieldFormatByFieldName($fieldName);
			$sql=sprintf($sqlFormat,$sql,$fieldName);
			$params=array($value);
			if($sqlFormat=="%s where %s like ?"){
				$params=array("%".$value."%");
			}
		}
		
		if(!stripos($sql, " where ")){
			$sql.=" where 1=1 ";
		}
		
		foreach ($this->_EXTRA_SEARCH_FIELDS as $key => $value){
			switch ($value["compartion_type"]){
				case "like":
					$extraFeildValue=trim(Yii::app()->request->getParam($value["field_name"],''));
					if(empty($extraFeildValue)==false){
						$sql.=sprintf(" and %s like ? ",$key);
						$params[]="%".$extraFeildValue."%";
					}
					break;
				case "between":
					$sFieldValue=trim(Yii::app()->request->getParam($value["field_name_start"],''));
					$eFieldValue=trim(Yii::app()->request->getParam($value["field_name_end"],''));
					if(empty($sFieldValue)==false && empty($eFieldValue)==false){
						$sql.=sprintf(" and %s between ? and ? ",$key);
						$params[]=$sFieldValue;
						$params[]=$eFieldValue;
					}else if(empty($sFieldValue)==false){
						$sql.=sprintf(" and %s >= ? ",$key);
						$params[]=$sFieldValue;
					}else if(empty($eFieldValue)==false){
						$sql.=sprintf(" and %s <= ? ",$key);
						$params[]=$eFieldValue;
					}
					break;
				case "equal":
					$extraFeildValue=trim(Yii::app()->request->getParam($value["field_name"],''));
					if(empty($extraFeildValue)==false){
						$sql.=sprintf(" and %s = ? ",$key);
						$params[]=$extraFeildValue;
					}
					break;
				case "not equal":
					$extraFeildValue=trim(Yii::app()->request->getParam($value["field_name"],''));
					if(empty($extraFeildValue)==false){
						$sql.=sprintf(" and %s <> ? ",$key);
						$params[]=$extraFeildValue;
					}
					break;
				case "greater":
					$extraFeildValue=trim(Yii::app()->request->getParam($value["field_name"],''));
					if(empty($extraFeildValue)==false){
						$sql.=sprintf(" and %s > ? ",$key);
						$params[]=$extraFeildValue;
					}
					break;
				case "less":
					$extraFeildValue=trim(Yii::app()->request->getParam($value["field_name"],''));
					if(empty($extraFeildValue)==false){
						$sql.=sprintf(" and %s < ? ",$key);
						$params[]=$extraFeildValue;
					}
					break;
				case "not null":
					$extraFeildValue=trim(Yii::app()->request->getParam($value["field_name"],''));
					if(empty($extraFeildValue)==false){
						if($extraFeildValue=='not null'){
							$sql.=sprintf(" and %s <> 'null' ",$key);
						}else{
							$sql.=sprintf(" and %s = ",$key);
							$sql.="'".$extraFeildValue."' ";
						}
						
					}
					break;
				default:
					break;
			}
		}		
		
		if(empty($orderFiledName)==false){
			$sql=$sql." order by $orderFiledName ";
		}
		$sql=$sql." limit $offSet,$page_size";
		$evnDefine=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($evnDefine) && is_array($evnDefine) && count($evnDefine)>0){
			return $evnDefine;
		}
		return array();
	}	
	
	protected function getPageRowsExtentData($data)
	{
		return $data;
	}
	
	protected function getDefaultValue($FieldName)
	{
		foreach ($this->_FIELD_DEFAULT_VALUE as $itemName => $itemValue){
			if($FieldName==$itemName){
				return $itemValue;
			}
		}
		return "";
	}
}

?>