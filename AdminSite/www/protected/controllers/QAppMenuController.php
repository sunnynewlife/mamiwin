<?php

LunaLoader::import("luna_lib.http.HttpInterface");
LunaLoader::import("luna_lib.util.LunaUtil");

class QAppMenuController extends TableMagtController 
{
	private $_title="分红管理后台-微信自定义菜单管理";
	private $_next_url="/qAppMenu/index";
	
	private $_tableName="";
	private $_searchName = "";
	private $_columns=array();
	private $_primaryKey="IDX";
	
	public function init()
	{
		$this->_PDO_NODE_NAME="FHDatabase";
		$this->_MEMCACHE_NODE_NAME="";
		$this->_memcacheKey=array("");
	}

	public function actionIndex()
	{
		$menuData=$this->getCustomMenu();
		$this->renderData["menu"]=$menuData;
		$this->renderData["menu"]=LunaUtil::json_encodeEx($menuData);
		$submit = trim(Yii::app()->request->getParam('submit',0));
		if($submit){
			$http=new HttpInterface("Tencent","MenuCreate");
			$appendUrl=sprintf("?access_token=%s",WxHelper::getAccessToken());
			$data=$http->submit($menuData,true,array(),false,$appendUrl,true);
			$this->renderData["menu"]=$data;
		}
		$this->render("index",$this->renderData);		
	}
	
	private function getCustomMenu()
	{
		return array(
			"button" => array(
				array(
					"name"	=>	"游戏",
					"type"	=>	"view",
					"url"	=>	"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8a417e8a8315d161&redirect_uri=http%3a%2f%2fdd.f.sdo.com%2fwx%2findex&response_type=code&scope=snsapi_base&state=123#wechat_redirect",
				),
				array(
					"name"	=>	"福利",
					"sub_button"	=>	array(
						array(
								"name"	=>	"活动",
								"type"	=>	"view",
								"url"	=>	"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8a417e8a8315d161&redirect_uri=http%3a%2f%2fdd.f.sdo.com%2fwx%2flottery&response_type=code&scope=snsapi_base&state=123#wechat_redirect",
						),
						array(
								"name"	=>	"礼包",
								"type"	=>	"view",
								"url"	=>	"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8a417e8a8315d161&redirect_uri=http%3a%2f%2fdd.f.sdo.com%2fwx%2fgift&response_type=code&scope=snsapi_base&state=123#wechat_redirect",
						),
					),	
				),
					
				array(
					"name"	=>	"我的",
					"sub_button"	=>	array(
						array(
							"name"	=>	"我的礼金",
							"type"	=>	"view",
							"url"	=>	"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8a417e8a8315d161&redirect_uri=http%3a%2f%2fdd.f.sdo.com%2fwx%2fmyMoney&response_type=code&scope=snsapi_base&state=123#wechat_redirect",
						),
						array(
							"name"	=>	"我的礼包",
							"type"	=>	"view",
							"url"	=>	"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8a417e8a8315d161&redirect_uri=http%3a%2f%2fdd.f.sdo.com%2fwx%2fmyGift&response_type=code&scope=snsapi_base&state=123#wechat_redirect",								
						),
						array(
							"name"	=>	"我的礼券",
							"type"	=>	"view",
							"url"	=>	"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8a417e8a8315d161&redirect_uri=http%3a%2f%2fdd.f.sdo.com%2fwx%2fmyCoupon&response_type=code&scope=snsapi_base&state=123#wechat_redirect",
						),
					),		
				),
			),
		);
	}
}
?>