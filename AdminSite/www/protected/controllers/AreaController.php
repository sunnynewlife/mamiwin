<?php
/**
 * 服务器区服开始时间
 *
 */
class AreaController extends TableMagtController 
{
	private $tableName="server_start_time";
	private $cachePrefix="start_area_time_%s_%s";// areaid evn_code
	
	public function actionIndex()
	{
		$this->_actionIndex($this->tableName, "evn_code", "/area/index", "area_list","index");
	}
	
	public function actionAdd()
	{
		$columnNames=array("evn_code","area_name","area_id","start_time","within_days");
		$this->_actionAdd($this->tableName, "开服时间", "/area/index", $columnNames,"add",null,$this->getCacheKey());
	}
	
	public function actionModify()
	{
		$columnNames=array("evn_code","area_name","area_id","start_time","within_days");
		$this->_actionModify($this->tableName, "id", "开服时间", "/area/index", "area", $columnNames,"modify",null,$this->getCacheKey());
	}
	
	public function actionDel()
	{
		$this->_actionDel($this->tableName, "id", "开服时间", "/area/index");
	}
	
	/**
	 * 组合CacheKey
	 * @return string
	 */
	public function getCacheKey(){
		$evn=Yii::app()->request->getParam('evn_code');
		$areaId=Yii::app()->request->getParam('area_id');
		if($evn && $areaId){
		 	return sprintf($this->cachePrefix,$areaId,$evn);
		}
		return null;
	}
	

	
}

?>