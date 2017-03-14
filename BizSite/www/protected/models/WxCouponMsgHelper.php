<?php

class WxCouponMsgHelper
{
	private function getMsgFieldsByCouponId($CouponIdx)
	{
		$msgFields=array(
			"content"	=>	"恭喜您收到了一张代金礼券！\n",
			"remark"	=>	"\n温馨提示：一笔充值仅可兑换一张代金礼券。",
			"name"		=>	"",
			"provider"	=>	"逗逗游戏",
			"promotion"	=>	"",
			"condition"	=>	"",
			"date_range"=>	"",			
		);
		
		$fAppData=new FAppData();
		$CouponInfo=$fAppData->getQ_AppPromotionCouponByIdx($CouponIdx);
		if(count($CouponInfo)>0){
			if(empty($CouponInfo["MsgFirst"])==false){
				$msgFields["content"]=$CouponInfo["MsgFirst"];
			}
			if(empty($CouponInfo["MsgRemark"])==false){
				$msgFields["remark"]=$CouponInfo["MsgRemark"];
			}
			if($CouponInfo["Type"]=="1"){
				$msgFields["name"]="游戏通用礼券";
			}else{
				$AppIds=explode(",",$CouponInfo["AppIds"]);
				$appInfo=$fAppData->getAppByAppId($AppIds[0]);
				$msgFields["name"]=sprintf("《%s》%s游戏专属礼券 ",$appInfo["AppName"],(count($AppIds)==1?"":"等".count($AppIds)."款"));
			}
			$msgFields["promotion"]=sprintf("单笔充值满%s元返%s元",number_format($CouponInfo["RecharegAmount"],2,".",","),number_format($CouponInfo["ReturnAmount"],2,".",","));
			
			$payDateRange="";
			if(isset($CouponInfo["pay_start_dt"]) && empty($CouponInfo["pay_start_dt"])==false &&  isset($CouponInfo["pay_end_dt"]) && empty($CouponInfo["pay_end_dt"])==false){
				$payDateRange=sprintf("限%s到%s之间的充值可使用",substr($CouponInfo["pay_start_dt"],0,10),substr($CouponInfo["pay_end_dt"],0,10));
			} else if(isset($CouponInfo["pay_start_dt"]) && empty($CouponInfo["pay_start_dt"])==false){
				$payDateRange=sprintf("限%s之后的充值可使用",substr($CouponInfo["pay_start_dt"],0,10));
			} else if(isset($CouponInfo["pay_end_dt"]) && empty($CouponInfo["pay_end_dt"])==false){
				$payDateRange=sprintf("限%s之前的充值可使用",substr($CouponInfo["pay_end_dt"],0,10));
			} else{
				$payDateRange="任意时间的充值均可使用";
			}
			$msgFields["condition"]=$payDateRange;
			$msgFields["date_range"]=(isset($CouponInfo["EndDt"]) && empty($CouponInfo["EndDt"])==false)?substr($CouponInfo["EndDt"], 0,10):"永久有效";
		}
		return $msgFields;
	}
	
	//构造推送用户消息的 变量参数
	private function getNotifyMsgParamsFromFields($MsgFields,$msgParamsMapping,$msgCouponeDef)
	{
		$params=array();
		foreach ($msgParamsMapping as $key => $value){
			if(isset($MsgFields[$value])){
				$paramDef=array("value" =>$MsgFields[$value]);
				$colorKey="color_".$key;
				if(isset($msgCouponeDef[$colorKey]) && empty($msgCouponeDef[$colorKey])==false){
					$paramDef["color"]=$msgCouponeDef[$colorKey];
				}
				$params[$key]=$paramDef;
			}
		}	
		return $params;			
	}
	
	//推送消息
	public function sendNotifyMsg4CouponByIdx($openId,$couponIdx)
	{
		$fAppData=new FAppData();
		$msgCouponDef=$fAppData->getSystemConfigItem("tencent.msg.coupon","",true);
		$this->sendNotifyMsg4Coupon($openId, $msgCouponDef,$couponIdx);
	}
	
	public function sendNotifyMsg4Coupon($openId,$msgCouponeDef,$couponIdx)
	{
		if(isset($msgCouponeDef) && count($msgCouponeDef)>0){
			$params=$this->getNotifyMsgParamsFromFields($this->getMsgFieldsByCouponId($couponIdx),$msgCouponeDef["data"],$msgCouponeDef);
			WxHelper::sendWxPlayerNotifyMsg($openId, $msgCouponeDef["template_id"], $params,$msgCouponeDef["topcolor"]);
		}
	} 
}
