<?php

class FFEvnController extends TableMagtController 
{
	private $_tableName="t_pay_success";
	private $_searchName="uid";
	private $_next_url="/fFEvn/index";
	private $_columns=array("lottery_id","src","uid","pt","sndaid","playerid","player_type");
	private $_title="FF14 公测激活码";
	private $_primaryKey="id";
	
	public function actionIndex()
	{	
		$this->_PDO_NODE_NAME="FF14";	
		$this->_actionIndex($this->_tableName, $this->_searchName, $this->_next_url, $this->_tableName,"index");
	}	
}

?>