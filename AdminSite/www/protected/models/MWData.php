<?php
LunaLoader::import("luna_lib.util.LunaPdo");
class MWData
{
	private $_PDO_NODE_NAME="BizDatabase";
	
	public function getLastInsertId($name=null)
	{
		LunaPdo::GetInstance($this->_PDO_NODE_NAME)->lastInsertId($name);
	}
	
	public function insertMaterial_Files($File_Title,$File_Type,$Location_Type,$Mime_Type,$Original_Name,$File_Size,$Download_Id,$File_Content)
	{
		$sql="insert into Material_Files (File_Title,File_Type,Location_Type,Mime_Type,Original_Name,File_Size,Download_Id,File_Content) values (?,?,?,?,?,?,?,?)";
		$params=array($File_Title,$File_Type,$Location_Type,$Mime_Type,$Original_Name,$File_Size,$Download_Id,$File_Content);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	
	public function updateMaterial_Files($File_Title,$File_Type,$IDX)
	{
		$sql="update Material_Files set File_Title=?,File_Type=?,Update_Time=now() where IDX=?";
		$params=array($File_Title,$File_Type,$IDX);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	
	public function updateMaterial_Files_All($File_Title,$File_Type,$Mime_Type,$Original_Name,$File_Size,$Download_Id,$File_Content,$IDX)
	{
		$sql="update Material_Files set File_Title=?,File_Type=?,Mime_Type=?,Original_Name=?,File_Size=?,Download_Id=?,File_Content=?,Update_Time=now() where IDX=?";
		$params=array($File_Title,$File_Type,$Mime_Type,$Original_Name,$File_Size,$Download_Id,$File_Content,$IDX);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	
}
