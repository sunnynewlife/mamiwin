<?php
LunaLoader::import("luna_lib.util.LunaPdo");

class Menu extends ModelBase
{
	public static function getInstance()
	{
		return parent::instance(__CLASS__);
	}
	
	public function getMenuInfo($mid)
	{
		$sql="select * from myadmin_menu_url where menu_id=?";
		$params=array($mid);
		$menu=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($menu) && is_array($menu) && count($menu)>0){
			return $menu[0];
		}
		return false;
	}
	
	public function getMenuByModuleId($module_id,$type ='bar')
	{
		$where=" where 1=1 ";
		switch ($type)
		{
			case 'bar':
				$where =$where. " and is_show  = 1 and online =1 ";
				break;
			case 'permission':
				$where =$where. " and online =1 ";
				break;
		}
		$sql="select * from myadmin_menu_url ".$where." and module_id=?";
		$params=array($module_id);
		$menu=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($menu) && is_array($menu) && count($menu)>0){
			return $menu;
		}
		return false;
	}
	
	
	public function getMenuByUserPermission($permission)
	{
		$sql="select a.*,b.* from myadmin_menu_url a left join myadmin_module b on b.module_id=a.module_id where a.is_show  = 1 and a.online =1 and a.menu_id in (".$permission.")";
		$params=array();
		$menu=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($menu) && is_array($menu) && count($menu)>0){
			return $menu;
		}
		return false;
	}
	
	
	public function getAllMenu($mid,$menu_name,$limit,$offset)
	{
		$where = ' where 1=1  ';
		if ($mid) $where .=' and a.module_id = '.$mid;
		if ($menu_name) $where .=' and a.menu_name = "'.$menu_name.'"';
		$sql="select a.* ,b.module_name,c.menu_name as fater_menu_name from myadmin_menu_url a left join myadmin_module b on b.module_id=a.module_id left join myadmin_menu_url c on c.menu_id =a.father_menu";
		$sql=sprintf("%s %s limit %s,%s",$sql,$where,$offset,$limit);
		$params=array();
		$menu=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($menu) && is_array($menu) && count($menu)>0){
			return $menu;
		}
		return false;
	}
		
	public function getMenuCount($mid,$menu_name)
	{
		$where = ' where 1=1 ';
		if ($mid) $where .=' and module_id = '.$mid;
		if ($menu_name) $where .=' and menu_name = "'.$menu_name.'"';
		$sql="select count(*) as num from myadmin_menu_url ".$where;
		$params=array();
		$menu=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($menu) && is_array($menu) && count($menu)>0){
			return $menu[0];
		}
		return false;		
	}
	
	public function getAllMenuByModuleId($module_id)
	{
		$sql="select * from myadmin_menu_url where module_id=?";
		$params=array($module_id);
		$menu=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($menu) && is_array($menu) && count($menu)>0){
			return $menu;
		}
		return false;		
	}
	
	public function changeModuleId($ids,$module_id)
	{
		if(is_array($ids) && count($ids)>0){
			$conditons="";
			foreach ($ids as $menuId){
				if(empty($conditons)){
					$conditons=$menuId;
				}else{
					$conditons=$conditons.",".$menuId;
				}
			}
			$sql=sprintf("update myadmin_menu_url set module_id=%s where menu_id in (%s)",$module_id,$conditons);
			$params=array();
			$rowCount=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->exec_with_prepare($sql,$params);
			return $rowCount;				
		}
		return false;		
	}
	
	
	public function insertMenu($data)
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
		$sql=sprintf("insert into myadmin_menu_url (%s) values (%s)",$sqlFields,$sqlParamFields);
		$rowCount=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->exec_with_prepare($sql,$params);
		return $rowCount;		
	}
	
	public function updateMenu($data,$menu_id)
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
		$sql=sprintf("update myadmin_menu_url set %s where menu_id=:menu_id",$sqlFields);
		$params[":menu_id"]=$menu_id;
		$rowCount=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->exec_with_prepare($sql,$params);
		return $rowCount;
	}
	
	public function delMenu($menu_id)
	{
		$sql="delete  from myadmin_menu_url where menu_id=?";
		$params=array($menu_id);
		$rowCount=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->exec_with_prepare($sql,$params);
		return $rowCount;
	}
	
	public function getMenuByUrl($url)
	{
		$sql="select a.*,b.menu_name as father_name,b.menu_url as father_url from myadmin_menu_url a left join myadmin_menu_url b on b.menu_id = a.father_menu where a.menu_url=?";
		$params=array($url);
		$menu=LunaPdo::GetInstance(self::DB_CFG_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($menu) && is_array($menu) && count($menu)>0){
			return $menu[0];
		}
		return false;
	}
}