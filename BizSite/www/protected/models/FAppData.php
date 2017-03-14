<?php
LunaLoader::import("luna_lib.util.LunaPdo");
class FAppData
{
	private $_PDO_NODE_NAME="FHDatabase";
	
	public function getLastInsertId($name=null)
	{
		LunaPdo::GetInstance($this->_PDO_NODE_NAME)->lastInsertId($name);
	}

	public function getQAppEvnList()
	{
		$sql="select 
				a.IDX,a.AppId,a.EvnName,a.EvnType,a.EvnStart,a.EvnEnd,a.EvnJoinType,a.EvnQty, 
    			a.BaseProrate,a.Prorate,a.EvnIntro,a.EvnAdvProrate,a.EvnJoinQty,a.EvnJoinRandQty,
    			a.Status,a.AclList,
    			b.AppName,c.FileUrl
			from Q_AppPromotionEvn a
				left join App b on b.AppId=a.AppId
				left join File c on c.FileId=a.FileId
			where a.EvnStart<=NOW() and a.EvnEnd>=NOW() and a.Status in (1,2)
			order by a.EvnOrder asc ";
		$params=array();
		$evnList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($evnList) && is_array($evnList) && count($evnList)>0){
			return $evnList;
		}
		return array();
	}
	
	public function getUserJoinedQAppEvnList($phone)
	{
		$sql="select * from  Q_EvnJoinList where PhoneNo=?";
		$params=array($phone);
		$evnList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($evnList) && is_array($evnList) && count($evnList)>0){
			return $evnList;
		}
		return array();
	}
	
	public function getIncomeSummary()
	{
		$sql="	select count(1) as num,sum(IncomeSummary)as IncomeSummary from Promoter where IncomeSummary>0          
				union
				select count(1) as num,0 as IncomeSummary from Q_MicroPlayer where IncomeSummary>0";
		$params=array();
		$evnList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($evnList) && is_array($evnList) && count($evnList)>0){
			return $evnList;
		}
		return array();		
	}
	
	public function getQ_AppPromotionEvnByIdx($idx)
	{
		$sql="select a.IDX,a.AppId,b.AppName,a.EvnStart,a.EvnEnd,a.EvnName,a.EvnOrder,a.EvnType,a.EvnQty,a.Prorate,a.EvnAdvProrate,a.EvnJoinQty,a.EvnJoinRandQty,a.Status,a.AclList,a.FileId,a.EvnRandMin,a.EvnRandMax,a.EvnContent,a.EvnJoinType,a.BaseProrate,a.EvnIntro,c.FileUrl
				from Q_AppPromotionEvn a
				left join App b on b.AppId=a.AppId
				left join File c on c.FileId=b.FileId
				where a.IDX=?";
		$params=array($idx);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0];
		}
		return array();
	}

	public function isUserJoinedEvnIdx($phone,$evnIdx)
	{
		$sql="select count(1) as num from Q_EvnJoinList where EvnIdx=? and PhoneNo=?";
		$params=array($evnIdx,$phone);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0]["num"]>0;
		}
		return false;
	}

	/*
	礼包列表：状态在线或白名单，上架时间
		默认以上架时间倒叙方式展示，但后台可设置展示顺序，数字越小则展示位置越靠前
	 */
	public function getQ_AppPromotionGiftList($appId,$status,$giftIdx,$giftName,$offSet,$page_size,$enddt)
	{
		$sql="select a.IDX,a.Name,a.FileId ,a.AppId ,a.Status,a.GiftOrder,a.Category,
			a.TagType1,a.TagType2,a.TagType3,a.StartDt,a.EndDt,a.Content,a.Guide,a.AclList,
			a.GiftOrder,a.RestCount as PageRestCount ,e.DrawCount,f.GiftCount,
			b.AppName,c.TotalCount,d.RestCount,e.FileUrl
			from Q_AppPromotionGift a 
			left join App b on b.AppId=a.AppId 
			left join File e on e.FileId=a.FileId 
			left join (
						select count(1) as TotalCount,GiftIdx from Q_AppGiftCode 
						group by GiftIdx
				) c  on c.GiftIdx=a.IDX
			left join (
						select count(1) as RestCount,GiftIdx from Q_AppGiftCode where PhoneNo is null or PhoneNo=''
						group by GiftIdx
				)  d  on d.GiftIdx=a.IDX
			left join (
						select count(1) as DrawCount,GiftIdx from Q_AppGiftCode where PhoneNo!=''
						group by GiftIdx
				)  e  on e.GiftIdx=a.IDX
			LEFT JOIN (
						SELECT COUNT(1) AS GiftCount,GiftIdx FROM Q_AppGiftCode GROUP BY GiftIdx
				)  f  ON f.GiftIdx=a.IDX
			where 1=1 AND (a.Status = 1  or a.Status = 2 ) AND (a.OpenDt < NOW() or a.OpenDt is null)
			";
		
		$params=array();
		
		if(empty($appId)==false){
			$sql.=" and a.AppId=?";
			$params[]=$appId;
		}
		
		if(empty($status)==false){
			// $sql.=" and a.Status=?";
			// $params[]=$status;
		}
		
		if(empty($giftIdx)==false){
			$sql.=" and a.IDX like ?";
			$params[]="%".$giftIdx."%";
		}
		
		if(empty($giftName)==false){
			$sql.=" and a.Name like ?";
			$params[]="%".$giftName."%";
		}
		if(empty($enddt)==false){
			$sql.=" and (a.EndDt >=  DATE_FORMAT(NOW(),'%Y-%m-%e') or a.EndDt is NULL ) " ;	//不显示已过期礼包
		}
		$sql.=" order by GiftOrder asc , OpenDt desc ";
		if($offSet>=0) {
			// $sql=$sql." limit $offSet,$page_size";
		}	
		$giftList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($giftList) && is_array($giftList) && count($giftList)>0){
			return $giftList;
		}
		return array();
	}
	
	/*
	根据id取礼包

	 */
	public function getQ_AppPromotionGiftByIdx($idx)
	{
		$sql="select a.FileId,a.AppId,a.Name,a.TotalCount,a.RestCount as PageRestCount,a.StartDt,a.EndDt,a.Category,
					a.Content,a.Guide,a.TagType1,a.TagType2,a.TagType3,a.GiftOrder,a.Status,a.OpenDt,a.AclList,a.IDX,
					b.AppName ,c.FileUrl ,d.RestCount,e.DrawCount,f.GiftCount
				from Q_AppPromotionGift a 
				left join App b on b.AppId=a.AppId
				left join File c on c.FileId = a.FileId 
				left join (
						select count(1) as RestCount,GiftIdx from Q_AppGiftCode where PhoneNo is null or PhoneNo=''
						group by GiftIdx
				)  d  on d.GiftIdx=a.IDX	
				LEFT JOIN (
						SELECT COUNT(1) AS DrawCount,GiftIdx FROM Q_AppGiftCode WHERE PhoneNo!=''
						GROUP BY GiftIdx
				)  e  ON e.GiftIdx=a.IDX
				LEFT JOIN (
						SELECT COUNT(1) AS GiftCount,GiftIdx FROM Q_AppGiftCode GROUP BY GiftIdx
				)  f  ON f.GiftIdx=a.IDX
				where a.IDX=?
				";
		$params=array($idx);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0];
		}
		return array();
	}
	


	/*
	获取用户已经领取的礼包码
	 */
	public function getGiftCodeByPhoneNo($idx,$phone)
	{
		$sql="select * from Q_AppGiftCode where GiftIdx=? and PhoneNo = ?";
		$params=array($idx,$phone);
		$giftList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($giftList) && is_array($giftList) && count($giftList)>0){
			return $giftList;
		}
		return 0;
	}

	/*
	获取礼包码领取数据
	 */
	public function getGiftCodeCount($idx,$phone){
		$sql=" SELECT a.DrawCount ,b.LeftCount FROM 
			   (SELECT COUNT(1) AS DrawCount FROM Q_AppGiftCode a WHERE GiftIdx=? AND PhoneNo = ? ) a,
			   (SELECT COUNT(1)  AS LeftCount FROM Q_AppGiftCode a WHERE GiftIdx=? AND PhoneNo IS NULL OR PhoneNo = '' ) b;
			   ";
		$params=array($idx,$phone,$idx);
		$giftList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($giftList) && is_array($giftList) && count($giftList)>0){
			return $giftList;
		}
		return 0;
	}

	/*
	随机取一个码给用户领取活动码
	 */
	public function giftCodeDraw($giftId,$phoneno){
		
		$sql="select count(1) as num from Q_AppGiftCode where  PhoneNo = ? and GiftIdx=? ";
		$params = array($phoneno,$giftId);
		$giftList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($giftList) && is_array($giftList) && count($giftList)>0 && $giftList[0]["num"]==0){
			$sql = "update Q_AppGiftCode set PhoneNo = ? ,UpdateDt = NOW() where GiftIdx = ? and (PhoneNo IS NULL or PhoneNo='') ORDER BY RAND() LIMIT 1;"	;
			return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
		}
		return false;		
	}


	/*
	我的礼包列表
	按照用户领取的时间先后顺序倒序展示
	 */
	public function getMyGiftList($phoneno){
		$sql = "SELECT a.IDX,a.Name,a.FileId ,a.AppId ,a.Status,a.GiftOrder,a.Category,a.TagType1,a.TagType2,a.TagType3,a.StartDt,a.EndDt,
		a.Content,a.Guide,a.GiftOrder ,c.FileUrl,b.Code
		FROM Q_AppPromotionGift a ,Q_AppGiftCode b ,File c 
		WHERE a.IDX = b.GiftIdx and a.Fileid = c.Fileid and b.PhoneNo = ? 
		ORDER BY  b.`UpdateDt` DESC ";
		
		$params = array($phoneno);
		$giftList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($giftList) && is_array($giftList) && count($giftList)>0){
			return $giftList;
		}
		return array();			
	}

	public function getAppInfoByAppId($appId)
	{
		$sql="select * from App where AppId=?";
		$params=array($appId);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0];
		}
		return array();
	}

	public function getCurrentAppGiftListAll()
	{
		$sql="select  a.IDX,a.FileId,a.AppId,a.Name,a.TotalCount,a.RestCount,a.StartDt,a.EndDt,a.Category,a.Content,a.Guide,
					  a.TagType1,a.TagType2,a.TagType3,a.GiftOrder,a.Status,a.OpenDt,a.AclList,a.CreateDt,a.UpdateDt,
					  b.ActRestCount,c.DrawCount
				from Q_AppPromotionGift a
				left join (
							select count(1) as ActRestCount,GiftIdx from Q_AppGiftCode where PhoneNo is null or PhoneNo=''
							group by GiftIdx
					)  b  on b.GiftIdx=a.IDX
				left join (
							select count(1) as DrawCount,GiftIdx from Q_AppGiftCode where PhoneNo!=''
							group by GiftIdx
					)  c  on c.GiftIdx=a.IDX
				where a.Status in (1,2)
				order by a.AppId asc,a.Category asc,a.GiftOrder asc,a.CreateDt desc";
		$params=array();
		$evnList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($evnList) && is_array($evnList) && count($evnList)>0){
			return $evnList;
		}
		return array();
	}
	
	public function getCurrentAppGiftList($AppId)
	{
		$sql="select  a.IDX,a.FileId,a.AppId,a.Name,a.TotalCount,a.RestCount,a.StartDt,a.EndDt,a.Category,a.Content,a.Guide,
					  a.TagType1,a.TagType2,a.TagType3,a.GiftOrder,a.Status,a.OpenDt,a.AclList,a.CreateDt,a.UpdateDt,
					  b.ActRestCount,c.DrawCount 
				from Q_AppPromotionGift a
				left join (
							select count(1) as ActRestCount,GiftIdx from Q_AppGiftCode where PhoneNo is null or PhoneNo=''
							group by GiftIdx
					)  b  on b.GiftIdx=a.IDX
				left join (
							select count(1) as DrawCount,GiftIdx from Q_AppGiftCode where PhoneNo!=''
							group by GiftIdx
					)  c  on c.GiftIdx=a.IDX
				where a.Status in (1,2) and a.AppId=? 
				order by a.Category asc,a.GiftOrder asc,a.CreateDt desc";
		$params=array($AppId);
		$evnList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($evnList) && is_array($evnList) && count($evnList)>0){
			return $evnList;
		}
		return array();
	}
	
	public function getGettenGiftList($phone)
	{
		$sql="select * from Q_AppGiftCode where PhoneNo=?";
		$params=array($phone);
		$evnList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($evnList) && is_array($evnList) && count($evnList)>0){
			return $evnList;
		}
		return array();				
	}
	
	public function getSystemConfigItem($configKey,$defaultValue="",$isJsonValue=false)
	{
		$sql="select * from SystemConfig where ConfigKey=? ";
		$params=array($configKey);
		$itemCfg=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($itemCfg) && is_array($itemCfg) && count($itemCfg)>0){
			if($isJsonValue){
				$ret=json_decode($itemCfg[0]["ConfigValue"],true);
				return $ret==null?array():$ret;
			}
			return $itemCfg[0]["ConfigValue"];
		}
		return $defaultValue;
	}
	
	public function insertQ_EvnJoinList($phone,$evnIdx,$clientIp)
	{
		$sql="insert into Q_EvnJoinList (EvnIdx,PhoneNo,ClientIp,CreateDt) values (?,?,?,NOW())";
		$params=array($evnIdx,$phone,$clientIp);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	
	public function updateQ_AppPromotionEvn($idx,$randJoinQty)
	{
		$sql=sprintf("update Q_AppPromotionEvn set EvnJoinQty=EvnJoinQty+1,EvnJoinRandQty=EvnJoinRandQty+%s,UpdateDt=NOW() where IDX=?",$randJoinQty);
		$params=array($idx);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}

	public function getLatestAppVersion($appId)
	{
		$sql="select * from AppVersion where AppId=? and IsPublishVersion=1";
		$params=array($appId);
		$appVersion=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($appVersion) && is_array($appVersion) && count($appVersion)>0){
			return $appVersion[0];
		}
	
		$sql="select * from AppVersion where AppId=? order by AppVersionId desc limit 1";
		$appVersion=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($appVersion) && is_array($appVersion) && count($appVersion)>0){
			return $appVersion[0];
		}
		return array();
	}
	
	public function getLatestAppVersionWithPublished()
	{
		$sql="select * from AppVersion where IsPublishVersion=1";
		$params=array();
		$appVersion=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($appVersion) && is_array($appVersion) && count($appVersion)>0){
			return $appVersion;
		}
		return array();
	}
	
	public function getLatestAppVersionWithNonePublished()
	{
		$sql="select * from AppVersion order by AppId desc,AppVersionId desc";
		$params=array();
		$appVersion=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($appVersion) && is_array($appVersion) && count($appVersion)>0){
			return $appVersion;
		}
		return array();
	}
	
	
	public function getFileById($fileId)
	{
		$sql="select * from File where FileId=?";
		$params=array($fileId);
		$files=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($files) && is_array($files) && count($files)>0){
			return $files[0];
		}
		return array();
	}
	
	public function updatePhoneByOpenId($phone,$openId)
	{
		$sql="update Q_MicroPlayer set PhoneNo=?,BindDt=NOW(),UpdateDt=NOW() where OpenId=? ";
		$params=array($phone,$openId);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params); 
	}

	public function isPhoneBinded($phone)
	{
		$sql="select count(1) as num from Q_MicroPlayer where PhoneNo=?";
		$params=array($phone);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0]["num"]>0;
		}
		return false;
	}
	
	public function getQ_MicroPlayerByOpenId($openId)
	{
		$sql="select * from Q_MicroPlayer where OpenId=?";
		$params=array($openId);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0];
		}
		return array();		
	}
	
	public function getSentEnvloperCountToday($OpenId,$today)
	{
		$sql="select count(1) as num from Q_PlayerPayApply where OpenId=? and State in (0,2) and CreateDt>=? and CreateDt<=?";
		$params=array($OpenId,$today." 00:00:00",$today." 23:59:59");
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0]["num"];
		}
		return 0;
	}
	
	public function getQ_MicroPhonePayReturn($PhoneNo,$PayReturnDt)
	{
		$sql="select sum(Amount) as Amount from Q_MicroPhonePayReturn where PhoneNo=? and CreateDt>=? and ReturnStatus=1";
		$params=array($PhoneNo,$PayReturnDt);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0]["Amount"];
		}
		return "0.00";
	}
	
	public function getSentEnvloperByOpenId($OpenId)
	{
		$sql="select * from Q_PlayerPayApply where OpenId=? order by CreateDt desc";
		$params=array($OpenId);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info;
		}	
		return array();	
	}
	
	public function makeEnvelopeApply($openId,$amount)
	{
		$pdo=LunaPdo::GetInstance($this->_PDO_NODE_NAME);
		$pdo->beginTransaction();
		$sql="insert into Q_PlayerPayApply (OpenId,Amount,CreateDt,State) values (?,?,NOW(),0) ";
		$params=array($openId,$amount);
		if($pdo->exec_with_prepare($sql,$params)>0){
			$sql="update Q_MicroPlayer set NetAmount=NetAmount-? where OpenId=?";
			$params=array($amount,$openId);
			if($pdo->exec_with_prepare($sql,$params)>0){
				$pdo->commit();
			}else{
				$pdo->rollBack();
			}
		}
	}
	
	public function getPhoneNoByOpenId_InsertIfNotExist($openId,$bUpdateFocusStatus=false,$newFocusStatus=2)
	{
		$pdo=LunaPdo::GetInstance($this->_PDO_NODE_NAME);
		$pdo->beginTransaction();
		$sql="select * from Q_MicroPlayer where OpenId=?";
		$params=array($openId);
		$info=$pdo->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0 ){
			if($bUpdateFocusStatus){
				$sql="update Q_MicroPlayer set FocusStatus=?,FocusDt=NOW() where OpenId=?";
				$params=array($newFocusStatus,$openId);
				$pdo->exec_with_prepare($sql,$params);
			}
			$pdo->commit();
			return (isset($info[0]["PhoneNo"]) && empty($info[0]["PhoneNo"])==false)?$info[0]["PhoneNo"]:"";
		}else{
			$sql="insert into Q_MicroPlayer (OpenId,IncomeSummary,PayState,FrozenAmt,NetAmount,CreateDt,UpdateDt,FocusStatus,FocusDt) values (?,0.00,1,0.00,0.00,NOW(),NOW(),?,NOW()) ";
			$params=array($openId,$newFocusStatus);
			$pdo->exec_with_prepare($sql,$params);
			$pdo->commit();
			return "";
		}
	}
	
	
	public function insertQ_MsgHistory($openId,$msgId,$msgType,$sentTime,$msgContent)
	{
		$sql="insert Q_MsgHistory (OpenId,MsgId,MsgType,SendTime,MsgContent,CreateDt) values (?,?,?,?,?,NOW())";
		$params=array($openId,$msgId,$msgType,$sentTime,$msgContent);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	
	public function getCurrentEvnByAppId($AppId)
	{
		$sql="select * from Q_AppPromotionEvn where AppId=? and EvnStart<=NOW() and EvnEnd>=NOW() and Status in (1,2) ";
		$params=array($AppId);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0];
		}
		return array();
	}
	
	public function updateFocusStatus($openId,$newFocusStatus=2)
	{
		$pdo=LunaPdo::GetInstance($this->_PDO_NODE_NAME);
		$sql="update Q_MicroPlayer set FocusStatus=?,FocusDt=NOW() where OpenId=?";
		$params=array($newFocusStatus,$openId);
		return $pdo->exec_with_prepare($sql,$params);
	}
	
	public function insertShareInfo($openId,$pageId,$shareType)
	{
		$pdo=LunaPdo::GetInstance($this->_PDO_NODE_NAME);
		$sql="insert into Q_ShareHistory (OpenId,SharePageId,ShareType,ShareDt) values (?,?,?,NOW())";
		$params=array($openId,$pageId,$shareType);
		return $pdo->exec_with_prepare($sql,$params);		
	}
	
	public function getAutoPushCouponList()
	{
		$sql="select * from Q_AppPromotionCoupon where IsNewBind=1 and Status in (1,2) ";
		$params=array();
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info;
		}
		return array();
	}
	
	public function getCouponGettenByPhone($PhoneNo)
	{
		$sql="select * from Q_CouponPlayer where PhoneNo=?";
		$params=array($PhoneNo);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info;
		}
		return array();
	}
	
	private function getCouponSentCountByIdx($couponIdx)
	{
		$sql="select count(1) as num from Q_CouponPlayer where CouponIdx=?";
		$params=array($couponIdx);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0]["num"];
		}
		return 0;
	}
	public function sendAutoPushCouponBy($PhoneNo,$couponIdx,$userIdx,$couponQty,$couponStartDt,$couponEndDt)
	{
		$couponQty=$couponQty-$this->getCouponSentCountByIdx($couponIdx);		//考虑后台手动发送量  limit =数量 -已领取的量    
		//指定的礼券  用户处于 可领取列表即可 领取  符合条件未领取过的占坑用户列表  
		$sql="select Idx from Q_MicroPlayer where PhoneNo is not null and PhoneNo<>'' and BindDt between ? and ? and PhoneNo not in (select PhoneNo from Q_CouponPlayer where CouponIdx =? )  order by BindDt asc,idx asc limit ".$couponQty;
		$params=array($couponStartDt,$couponEndDt,$couponIdx);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			$bFoundUserIdx=false;
			foreach ($info as $item){
				if($item["Idx"]==$userIdx){
					$bFoundUserIdx=true;
					break;
				}
			}
			if($bFoundUserIdx){
				$sql="insert into Q_CouponPlayer (CouponIdx,PhoneNo,CreateDt,DrawDt,UpdateDt) values (?,?,NOW(),NOW(),NOW())";
				$params=array($couponIdx,$PhoneNo);
				return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
			}
		}
		return 0;
	}
	
	public function getCouponInfoGettenByPhone($PhoneNo)
	{
		$sql="select a.IDX,a.CouponIdx,a.PayIdx,a.CreateDt,a.DrawDt,a.UpdateDt, 
					b.Type,b.AppIds,b.RecharegAmount,b.ReturnAmount,b.EndDt,b.StartDt,b.PayStartDt,b.PayEndDt 
				from Q_CouponPlayer a  
				left join Q_AppPromotionCoupon b on b.IDX=a.CouponIdx
			where a.PhoneNo=?
				order by a.CreateDt desc,b.EndDt asc ";
		
		$params=array($PhoneNo);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info;
		}
		return array();
	}
	
	public function getAppInfo()
	{
		$sql="select * from App ";
		$params=array();
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info;
		}
		return array();
	}
	
	public function getCouponInfo4UsingByIdxPhone($idx,$phone)
	{
		$sql="select a.CouponIdx,a.PayIdx,
				b.AppIds,b.RecharegAmount,b.ReturnAmount,b.StartDt,b.EndDt,b.PayStartDt,b.PayEndDt,b.Type  
				from Q_CouponPlayer a 
				left join Q_AppPromotionCoupon b on b.IDX=a.CouponIdx 
			  where a.PayIdx is null and a.IDX=? and a.PhoneNo=? ";
		$params=array($idx,$phone);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0];
		}
		return array();
	}
	
	public function usingCoupon($idx,$PhoneNo,$payStart,$payEnd,$payAmount,$returnAmount,$appIds)
	{
		$pdo=LunaPdo::GetInstance($this->_PDO_NODE_NAME);
		$pdo->beginTransaction();
		
		$sql="select * from Q_MicroPhonePayReturn where ReturnStatus=1 and PhoneNo=? and GameAmount>=? and IDX not in ( select PayIdx from Q_CouponPlayer where PayIdx is not null) ";
		$params=array($PhoneNo,$payAmount);
		
		if(empty($appIds)==false ){
			$sql.=" and AppId in (".$appIds.")";
		}
		
		if(isset($payStart) && empty($payStart)==false){
			$sql.=" and TransactDt>=? ";
			$params[]= substr($payStart, 0,10)." 00:00:00";
		}

		if(isset($payEnd) && empty($payEnd)==false){
			$sql.=" and TransactDt<=? ";
			$params[]= substr($payEnd, 0,10)." 23:59:59";
		}
		
		$sql.=" order by GameAmount asc,TransactDt asc limit 1 ";
		
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			
			$sql="update Q_MicroPlayer set IncomeSummary=IncomeSummary+?,NetAmount=NetAmount+? where PhoneNo=? ";
			$params=array($returnAmount,$returnAmount,$PhoneNo);
			if(LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params)>0){
				$sql="update Q_CouponPlayer set PayIdx=?,UpdateDt=NOW() where IDX=?";
				$params=array($info[0]["IDX"],$idx);
				if(LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params)>0){
					$pdo->commit();
					return true;
				}else{
					$pdo->rollBack();
				}				
			}else{
				$pdo->rollBack();
			}
		}else{
			$pdo->rollBack();
		}
		return false;
	}


	/**
	 * 根据ID，用户领取礼券
	 * @param  [type] $couponPlayerIdxs [description]
	 * @return [type]                   [description]
	 */
	public function userDrawCoupon($couponPlayerIdxs){
		$sql = "update Q_CouponPlayer set DrawDt = NOW() where IDX in (". $couponPlayerIdxs .") and ( DrawDt IS NULL OR DrawDt = '' )";
		$params = array();
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}



	/**
	 * 获取本次抽奖详情
	 * @param  [type] $eventId [description]
	 * @return [type]          [description]
	 */
	public function getQAppLotteryInfoByEventId($eventId ,$phoneNo,$pageId){
		$sql="  SELECT a.*,b.LotteryIdx,c.SharePageId FROM Q_MicroPlayer a 
				LEFT JOIN Q_LotteryPlayer b ON a.PhoneNo = b.PhoneNo AND b.LotteryIdx IN (SELECT IDX FROM Q_AppPromotionLottery WHERE EventId = ?)
				LEFT JOIN Q_ShareHistory c ON a.OpenId = c.OpenId  AND  c.`SharePageId` = ? 
				WHERE a.PhoneNo = ?  GROUP BY c.SharePageId  ; ";
		// $sql="  SELECT a.*,b.LotteryIdx,c.SharePageId,d.CouponIdx FROM Q_MicroPlayer a 
		// 		LEFT JOIN Q_LotteryPlayer b ON a.PhoneNo = b.PhoneNo AND b.LotteryIdx IN (SELECT IDX FROM Q_AppPromotionLottery WHERE EventId = ?)
		// 		LEFT JOIN Q_ShareHistory c ON a.OpenId = c.OpenId  AND  c.SharePageId = ? 
		// 		LEFT JOIN Q_AppPromotionLottery d ON b.LotteryIdx = d.IDX
		// 		WHERE a.PhoneNo = ? ; ";
		$params = array($eventId,$pageId,$phoneNo);
		$lotteryInfo =  LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($lotteryInfo) && is_array($lotteryInfo) && count($lotteryInfo)>0){
			return $lotteryInfo;
		}
		return array();			
	}


	/**
	 * 获取当前活动的抽奖信息
	 * @param  [type] $eventId [description]
	 * @return [type]          [description]
	 */
	public function getCurrentLotteryInfo($eventId){
		$sql=" SELECT a.* ,IFNULL(b.DrawCount,0) AS DrawCount FROM Q_AppPromotionLottery  a  
				LEFT JOIN (SELECT LotteryIdx,COUNT(*) AS DrawCount  FROM Q_LotteryPlayer WHERE PhoneNo IS NOT NULL AND PhoneNo != '' group by LotteryIdx ) b ON a.IDX = b.LotteryIdx
				WHERE a.EventId = ? order by a.AwardId asc ; ";
		$params = array($eventId);
		$lotteryInfo = LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($lotteryInfo) && is_array($lotteryInfo) && count($lotteryInfo)>0){
			return $lotteryInfo;
		}
		return array();	
	}

	/**
	 * 获取本次活动的中奖信息
	 * @param  [type] $EventId [description]
	 * @return [type]          [description]
	 */
	public function getQAppLotteryByEventId($eventId){
		$sql=" SELECT a.*,b.PhoneNo,b.CreateDt AS LotteryDt FROM Q_AppPromotionLottery a , Q_LotteryPlayer b WHERE a.EventId = ? and  a.IDX = b.LotteryIdx ";
		$params = array($eventId);
		$lotteryList = LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($lotteryList) && is_array($lotteryList) && count($lotteryList)>0){
			return $lotteryList;
		}
		return array();	
	}

	/**
	 * 给用户发奖,如果是礼券类奖品，同时发礼券到用户账户
	 * @param  [type] $phoneNo    [description]
	 * @param  [type] $eventId    [description]
	 * @param  [type] $awardId    [description]
	 * @param  [type] $lotteryIdx [description]
	 * @return [type]             [description]
	 */
	public function grantPlayerAward($phoneNo,$eventId,$lotteryIdx){
		$sql = " SELECT  * FROM Q_AppPromotionLottery a , Q_LotteryPlayer b 
 			WHERE a.EventId = ? AND a.IDX = b.LotteryIdx AND b.PhoneNo = ? ";
		$params = array($eventId,$phoneNo);
		$data=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($data) && is_array($data) && count($data)>0){
			return 9;
		}else{
			//判断所发奖品是否为礼券，是则自动发放到用户
			$sql = "SELECT IFNULL(CouponIdx,0) AS CouponIdx FROM `Q_AppPromotionLottery` WHERE EventId = ? AND IDX = ? ";
			$params = array($eventId,$lotteryIdx);
			$lotteryInfo = LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
			$canGrantAward = false;
			$pdo=LunaPdo::GetInstance($this->_PDO_NODE_NAME);
			$pdo->beginTransaction();
			if(isset($lotteryInfo) && is_array($lotteryInfo) && count($lotteryInfo)>0){
				$couponIdx = $lotteryInfo[0]['CouponIdx'];
				if($couponIdx != 0){
					$sql_grantCoupon = "INSERT INTO Q_CouponPlayer (CouponIdx,PhoneNo,CreateDt) VALUES(?,?,NOW()) ";
					$params_grantCoupon = array($couponIdx,$phoneNo);
					$count_grantCoupon = LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql_grantCoupon,$params_grantCoupon);		
					if($count_grantCoupon > 0 ){
						$canGrantAward = true;
					}
				}else{
					$canGrantAward = true;
				}
			}
			if($canGrantAward){
				$sql = "insert into Q_LotteryPlayer(LotteryIdx,PhoneNo,CreateDt,UpdateDt) values(?,?,NOW(),NOW());";
				$params = array($lotteryIdx,$phoneNo);
				$count_grantAward = LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
				if($count_grantAward > 0){
					$pdo->commit();
					return $count_grantAward;
				}else{
					$pdo->rollBack();
					return 0 ;
				}
			}else{
				return 0 ;
			}
		}
	}


	/**
	 * 给新关注的用户发放0.5元现金到帐户，前2000名，先到先得
	 * 同时发放充10元返5元礼券
	 * @return [type] [description]
	 */
	public function grantNewFocusMoney($phoneNo,$eventId,$startDt,$endDt,$grantAmount,$grantquantity,$grantcouponid){
		//判断用户是否为新绑定用户，没有领过，且在2000名以内
		$sql = "select * from Q_MicroPlayer where PhoneNo = ? and BindDt BETWEEN ? AND ?";
		$params = array($phoneNo,$startDt,$endDt);
		$data=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);						
		if(!isset($data) || !is_array($data) || empty($data) || count($data)<=0){
			return false;
		}

		$sql = "SELECT a.grantAllCount ,b.grantCount FROM 
			(SELECT COUNT(*)  AS grantAllCount FROM Q_Transaction WHERE EventId = ? ) a ,
			(SELECT COUNT(*)  AS grantCount FROM Q_Transaction WHERE PhoneNo = ? AND EventId = ?) b ";
		$params = array($eventId,$phoneNo,$eventId);
		$data=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($data) && is_array($data) && count($data)>0){
			if($data[0]['grantAllCount'] >= $grantquantity){
				return false;
			}else if($data[0]['grantCount'] > 0){
				return false;
			}else{
				$pdo=LunaPdo::GetInstance($this->_PDO_NODE_NAME);
				$pdo->beginTransaction();
				//记录流水0.50元
				$sql_grantMoney = "insert into Q_Transaction(PhoneNo,EventId,Amount,TransactionDt) values(?,?,?,NOW())";
				$params_grantMoney = array($phoneNo,$eventId,$grantAmount);
				if($pdo->exec_with_prepare($sql_grantMoney,$params_grantMoney)>0){
					//更新用户表金额
					$sql_updateAmount="update Q_MicroPlayer set IncomeSummary=IncomeSummary+?,NetAmount=NetAmount+? where PhoneNo=? ";
					$params_updateAmount = array($grantAmount,$grantAmount,$phoneNo);
					if($pdo->exec_with_prepare($sql_updateAmount,$params_updateAmount)>0){
						//发礼券10元返5元
						$sql_grantCoupon = "INSERT INTO Q_CouponPlayer (CouponIdx,PhoneNo,CreateDt) VALUES(?,?,NOW())";
						$params_grantCoupon = array($grantcouponid,$phoneNo);
						if($pdo->exec_with_prepare($sql_grantCoupon,$params_grantCoupon)>0){
							$pdo->commit();
							return true;	
						}else{
							$pdo->rollBack();
							return false;
						}
					}
					else{
						$pdo->rollBack();
						return false;
					}
				}else{
					$pdo->rollBack();
					return false;
				}	
			}
		}
		return false;
	}

	public function getQ_AppPromotionCouponByIdx($idx){
		$sql="SELECT * FROM Q_AppPromotionCoupon a 	WHERE a.IDX = ? ";
		$params=array($idx);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0];
		}
		return array();		
	}

	/**
	 * 获取用户做为新关注用户领取现金的
	 * @param  [type] $eventId [description]
	 * @param  [type] $phoneNo [description]
	 * @return [type]          [description]
	 */
	public function getNewFocusMoneyByPhoneNo($eventId,$phoneNo,$startDt,$endDt){
		$sql = " SELECT a.DrawCount ,b.AllDrawCount,c.FocusCount,d.LookerCount FROM 
			(SELECT COUNT(*) AS DrawCount FROM Q_Transaction WHERE EventId = ? AND PhoneNo = ? ) a ,
			(SELECT COUNT(*) AS AllDrawCount FROM Q_Transaction WHERE EventId = ?) b ,
			(SELECT COUNT(*) AS FocusCount FROM Q_MicroPlayer WHERE PhoneNo = ? AND BindDt BETWEEN ? AND ? ) c ,
			(SELECT COUNT(*) AS LookerCount FROM Q_MicroPlayer WHERE PhoneNo = ? AND BindDt NOT BETWEEN ? AND ? ) d " ;
		$params = array($eventId,$phoneNo,$eventId,$phoneNo,$startDt,$endDt,$phoneNo,$startDt,$endDt);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0];
		}
		return array();		
	}
	
	public function getMicroPhonePayInfoByIdx($idx)
	{
		$sql="select a.GameAmount,a.AppId,b.AppName,a.TransactDt  
				from Q_MicroPhonePayReturn a
				left join App b on b.AppId=a.AppId
			where IDX=?";
		$params=array($idx);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0];
		}
		return array();
	}
	
	public function getWxGameList($isAscSort=false)
	{
		$sql="select a.AppOrder,a.AppId,a.Status,a.BaseProrate,a.PromoteProrate,a.PromoteDt,a.IsFirstPublish,a.IDX,a.AclList,b.AppName,b.FileId,b.AppType,c.FileUrl,a.IsSinglePublish,a.IsOtherPublish,a.EvnUrl1,a.EvnUrl2,a.EvnUrl3,a.EvnUrl4,a.EvnUrl5,a.PromoteStartDt      
				from Q_App a
				left join App b on b.AppId=a.AppId
				left join File c on c.FileId=b.FileId
			  where a.Status in (1,2)
			  order by a.AppOrder desc ";
		$params=array();
		$AppInfo=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($AppInfo) && is_array($AppInfo) && count($AppInfo)>0){
			return $AppInfo;
		}
		return array();
	}
	
	public function getQ_AppByAppId($appId)
	{
		$sql="select * from  Q_App where AppId=?";
		$params=array($appId);
		$AppInfo=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($AppInfo) && is_array($AppInfo) && count($AppInfo)>0){
			return $AppInfo[0];
		}
		return array();
	}
	
	public function getQ_Evn1ByPhoneNoActId($PhoneNo,$ActId)
	{
		$sql="select * from Q_Evn_1 where PhoneNo=? and ActId=?";
		$params=array($PhoneNo,$ActId);
		$EvnInfo=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($EvnInfo) && is_array($EvnInfo) && count($EvnInfo)>0){
			return $EvnInfo[0];
		}
		return array();
	}
	public function getQ_Evn1JoinedCountByActId($ActId)
	{
		$sql="select count(1) as evnCount from Q_Evn_1 where ActId=?";
		$params=array($ActId);
		$EvnInfo=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($EvnInfo) && is_array($EvnInfo) && count($EvnInfo)>0){
			return $EvnInfo[0]["evnCount"];
		}
		return 0;
	}
	public function getQ_Evn1LoginByPhoneNo($PhoneNo,$PromoterPhone,$startDt,$endDt)
	{
		$sql="select DATE_FORMAT(a.loginDt,'%Y-%m-%d') as loginDay,c.PhoneNo as PlayerPhoneNo
				from PlayLoginHistory a 
				left join App b on b.AppId=a.AppId 
				left join Player c on c.Sdid=a.Sdid 
				left join AppPackage d on d.PromotionNo=a.PromotionNo 
				left join Promoter e on e.PromoterId=d.PromoterId
			where a.AppId in (select AppId from Q_App where Status=1 ) and e.PhoneNo=?  and c.PhoneNo like ? and a.loginDt>=? and a.loginDt<=?   
			group by DATE_FORMAT(a.loginDt,'%Y-%m-%d'),c.PhoneNo
			order by DATE_FORMAT(a.loginDt,'%Y-%m-%d')";
		$params=array($PromoterPhone,"%".$PhoneNo."%",$startDt,$endDt);
		$EvnInfo=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($EvnInfo) && is_array($EvnInfo) && count($EvnInfo)>0){
			return $EvnInfo;
		}
		return array();
	}
	
	//double check 领取过，领取数量
	public function sendQ_Evn1Act($PhoneNo,$ActId,$Amount,$ConditionQuantity)
	{
		$pdo=LunaPdo::GetInstance($this->_PDO_NODE_NAME);
		$pdo->beginTransaction();
		
		//表预存一条记录，用作锁用，领取操作需要获得这把锁
		$sql="select * from Q_Evn_1 where PhoneNo='13916354020' and ActId='OpLock' FOR UPDATE";
		$params=array();
		$info=$pdo->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		
		if(isset($info) && is_array($info) && count($info)>0 ){
			$sql="select * from Q_Evn_1 where PhoneNo=? and ActId=?";
			$params=array($PhoneNo,$ActId);
			$checkInfo=$pdo->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
			if(isset($checkInfo) && is_array($checkInfo) && count($checkInfo)>0 ){
				$pdo->rollBack();
				return -2;		//已领取过	
			}
			$sql="select count(1) as ActCount from Q_Evn_1 where PhoneNo=? and ActId=?";
			$ActCountInfo=$pdo->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
			if(isset($ActCountInfo) && is_array($ActCountInfo) && count($ActCountInfo)>0 && $ActCountInfo[0]["ActCount"]>= $ConditionQuantity ){
				$pdo->rollBack();
				return -3;		//已领取完				
			}
			
			$sql="update Q_MicroPlayer set IncomeSummary=IncomeSummary+?,NetAmount=NetAmount+? where PhoneNo=?";
			$params=array($Amount,$Amount,$PhoneNo);
			if($pdo->exec_with_prepare($sql,$params)>0){
				$sql="select NetAmount from Q_MicroPlayer where PhoneNo=?";
				$params=array($PhoneNo);
				$AmountInfo=$pdo->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
				$EAmount=0;
				if(isset($AmountInfo) && is_array($AmountInfo) && count($AmountInfo)>0 ){
					$EAmount=$AmountInfo[0]["NetAmount"];
				}
				$sql="insert Q_Evn_1 (PhoneNo,ActId,Amount,EAmount,CreateDt) values (?,?,?,?,NOW()) ";
				$params=array($PhoneNo,$ActId,$Amount,$EAmount);
				if($pdo->exec_with_prepare($sql,$params)>0){
					$pdo->commit();
					return 1;
				}			
			}
		}		
		$pdo->rollBack();
		return -1;
	}
}
