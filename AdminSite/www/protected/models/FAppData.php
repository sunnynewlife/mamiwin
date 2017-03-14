<?php
LunaLoader::import("luna_lib.util.LunaPdo");
class FAppData
{
	private $_PDO_NODE_NAME="FHDatabase";
	
	public function getLastInsertId($name=null)
	{
		LunaPdo::GetInstance($this->_PDO_NODE_NAME)->lastInsertId($name);
	}
	public function getLastInsertAppVersionIdByAppId($appId)
	{
		$sql="select * from AppVersion where AppId=? order by AppVersionId desc limit 1";
		$gameVersion=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,array($appId),PDO::FETCH_ASSOC);
		if(isset($gameVersion) && is_array($gameVersion) && count($gameVersion)>0){
			return $gameVersion[0]["AppVersionId"];
		}	
		return "";
	}
	
	public function getGameTypeDefinition()
	{
		return $this->getSystemConfigItem("dict.gametype",array(),true);
	}
	
	public function getApp()
	{
		$sql="select * from App";
		$games=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,array(),PDO::FETCH_ASSOC);
		if(isset($games) && is_array($games) && count($games)>0){
			return $games;
		}
		return array();		
	}
	
	public function insertFile($Scene,$FileName,$OriginalName,$FileExtendName)
	{
		$sql="insert into File (Scene,FileType,FileName,FileUrl,CreateDt) values (?,?,?,?,NOW())";
		$params=array($Scene,$FileExtendName,$OriginalName,$FileName);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	public function getFiles()
	{
		$sql="select * from File";
		$files=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,array(),PDO::FETCH_ASSOC);
		if(isset($files) && is_array($files) && count($files)>0){
			return $files;
		}
		return array();
	}
	public function insertApp($appId,$appLogo,$appName,$appType,$developer,$publisher,$appIntro,$appDetail,$appStatus,$testPhone,$promptTitle,$weixinTitle,$weixinPic,$weixinContent,$weiBoContent,$weiBoPic,$phoneMsg,$profitUrl,$developerProrate,$prefixName,$appNameEn,$costFeePercent,$sortIndex,$minPackingPoolSize,$EventUrl,$RecommendIndex,$PopularIndex,$RemainIndex,$PayIndex,$BeginnerLevel,$LabelTag1,$LabelTag2,$LabelTag3,$LabelTag4,$LabelTag5)
	{
		$sql="insert into App (AppId,FileId,AppName,AppType,Developer,Publisher,AppIntroduct,AppDetail,AppStatus,TestPhone,PromptTitle,WeixinTitle,WeixinPic,WeixinContent,WeiBoContent,WeiBoPic,PhoneMsg,CreateDt,UpdateDt,ProfitUrl,DeveloperProrate,PackagePrefixName,RegistState,AppNameEn,SortIndex,CostFeePercent,MinPackingPoolSize,EventUrl,RecommendIndex,PopularIndex,RemainIndex,PayIndex,BeginnerLevel,LabelTag1,LabelTag2,LabelTag3,LabelTag4,LabelTag5) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW(),NOW(),?,?,?,0,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$params=array($appId,$appLogo,$appName,$appType,$developer,$publisher,$appIntro,$appDetail,$appStatus,$testPhone,$promptTitle,$weixinTitle,$weixinPic,$weixinContent,$weiBoContent,$weiBoPic,$phoneMsg,$profitUrl,$developerProrate,$prefixName,$appNameEn,$sortIndex,$costFeePercent,$minPackingPoolSize,$EventUrl,$RecommendIndex,$PopularIndex,$RemainIndex,$PayIndex,$BeginnerLevel,$LabelTag1,$LabelTag2,$LabelTag3,$LabelTag4,$LabelTag5);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	public function updateApp($appId,$appLogo,$appName,$appType,$developer,$publisher,$appIntro,$appDetail,$appStatus,$testPhone,$promptTitle,$weixinTitle,$weixinPic,$weixinContent,$weiBoContent,$weiBoPic,$phoneMsg,$profitUrl,$developerProrate,$prefixName,$appNameEn,$costFeePercent,$sortIndex,$minPackingPoolSize,$EventUrl,$RecommendIndex,$PopularIndex,$RemainIndex,$PayIndex,$BeginnerLevel,$LabelTag1,$LabelTag2,$LabelTag3,$LabelTag4,$LabelTag5)
	{
		$sql="update App set FileId=?,AppName=?,AppType=?,Developer=?,Publisher=?,AppIntroduct=?,AppDetail=?,AppStatus=?,TestPhone=?,PromptTitle=?,WeixinTitle=?,WeixinPic=?,WeixinContent=?,WeiBoContent=?,WeiBoPic=?,PhoneMsg=?,UpdateDt=NOW(),ProfitUrl=?,DeveloperProrate=?, PackagePrefixName=?,AppNameEn=?,SortIndex=?, CostFeePercent=?, MinPackingPoolSize=?,EventUrl=?,RecommendIndex=?,PopularIndex=?,RemainIndex=?,PayIndex=?,BeginnerLevel=?,LabelTag1=?,LabelTag2=?,LabelTag3=?,LabelTag4=?,LabelTag5=? where AppId=?";
		$params=array($appLogo,$appName,$appType,$developer,$publisher,$appIntro,$appDetail,$appStatus,$testPhone,$promptTitle,$weixinTitle,$weixinPic,$weixinContent,$weiBoContent,$weiBoPic,$phoneMsg,$profitUrl,$developerProrate,$prefixName,$appNameEn,$sortIndex,$costFeePercent,$minPackingPoolSize,$EventUrl,$RecommendIndex,$PopularIndex,$RemainIndex,$PayIndex,$BeginnerLevel,$LabelTag1,$LabelTag2,$LabelTag3,$LabelTag4,$LabelTag5,$appId);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);		
	}
	public function insertAppVersion($appId,$versionName,$gamePics,$gameSize,$testPhone)
	{
		$sql="insert into AppVersion (AppId,VersionName,GamePics,GameSize,CreateDt,UpdateDt,State,TestPhone,PackageState,IsPublishVersion) values (?,?,?,?,NOW(),NOW(),0,?,0,0)";
		$params=array($appId,$versionName,$gamePics,$gameSize,$testPhone);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	public function updateAppVersion($appId,$versionName,$gamePics,$gameSize,$appVersionId,$testPhone)
	{
		$sql="update AppVersion set AppId=?,VersionName=?,GamePics=?,GameSize=?,UpdateDt=NOW() where AppVersionId=? ";
		$params=array($appId,$versionName,$gamePics,$gameSize,$appVersionId);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
		
	}
	public function insertProrateHistory($appId,$promoterProrate,$startDt,$endDt)
	{
		$sql="insert into ProrateHistory (AppId,PromoterProrate,StartDt,EndDt,CreateDt,UpdateDt,IsDelete) values (?,?,?,?,NOW(),NOW(),0)";
		$params=array($appId,$promoterProrate,$startDt,$endDt);
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
	public function getCurrentProrate($appId)
	{
		$sql="select * from ProrateHistory where StartDt <=NOW() and EndDt>Now() and IsDelete=0 and AppId=? order by ProrateId desc limit 1";
		$params=array($appId);
		$prorateHistory=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($prorateHistory) && is_array($prorateHistory) && count($prorateHistory)>0){
			return $prorateHistory[0];
		}
		return array();		
	}
	public function updateProrateHistoryStatusAsDelete($appId)
	{
		$sql="update ProrateHistory set IsDelete=1,UpdateDt=NOW() where AppId=?";
		$params=array($appId);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	public function getProrateHistory($appId)
	{
		$sql="select * from ProrateHistory where IsDelete=0 and AppId=? order by StartDt";
		$params=array($appId);
		$prorateHistory=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($prorateHistory) && is_array($prorateHistory) && count($prorateHistory)>0){
			return $prorateHistory;
		}
		return array();		
	}
	public function getViewApp($appId)
	{
		$sql="select * from ViewApp where AppId=?";
		$params=array($appId);
		$viewApp=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($viewApp) && is_array($viewApp) && count($viewApp)>0){
			return $viewApp[0];
		}
		return array();
	}
	//安装包申请量
	public function getAppPackageCount($appId)
	{
		$sql="select count(distinct(PromoterId)) as PackageCount from AppPackage where PromoterId>0 and PromotionNo <>'' and AppVersionId in (select AppVersionId from AppVersion where AppId=?)";
		$params=array($appId);
		$appPackage=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($appPackage) && is_array($appPackage) && count($appPackage)>0){
			return $appPackage[0]["PackageCount"];
		}
		return 0;
	}
	//游戏累计消费人数（去重）
	public function  getAppPlayDistinctCount($appId)
	{
		$sql="select count(distinct(PlayerId)) as DepositPlayCount from PayTransaction where AppId=? and TransactType=1";
		$params=array($appId);
		$appDeposit=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($appDeposit) && is_array($appDeposit) && count($appDeposit)>0){
			return $appDeposit[0]["DepositPlayCount"];
		}
		return 0;
	}
	//获取游戏的分红比例设置历史
	public function getProrateHistoryByAppId($appId)
	{
		$sql="select * from ProrateHistory where IsDelete=0 and AppId=? order by StartDt";
		$params=array($appId);
		$prorateHistory=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($prorateHistory) && is_array($prorateHistory) && count($prorateHistory)>0){
			return $prorateHistory;
		}
		return array();
	}	
	//统计一段时间内推广收益
	public function getProfit($startDt,$endDt,$appId,$prorate)
	{
		$data=array("play_count" => 0,"promoter_count" =>0,"deposit"=> 0, "withdraw" => 0);
		$sql="select sum(GameAmount) as deposit,sum(Amount) as  withdraw from PayTransaction where AppId=? and PromoterProrate=? and TransactDt>=? and TransactDt<=? and TransactType=1";
		$params=array($appId,$prorate,$startDt,$endDt);
		$profitHistory=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($profitHistory) && is_array($profitHistory) && count($profitHistory)>0){
			$data["deposit"]=	($profitHistory[0]["deposit"]==null?0:$profitHistory[0]["deposit"]);
			$data["withdraw"]=($profitHistory[0]["withdraw"]==null?0:$profitHistory[0]["withdraw"]);
		}
		
		$sql="select count(distinct(PlayerId)) as play_count from PayTransaction where AppId=? and PromoterProrate=? and TransactDt>=? and TransactDt<=? and TransactType=1";
		$profitHistory=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($profitHistory) && is_array($profitHistory) && count($profitHistory)>0){
			$data["play_count"]=$profitHistory[0]["play_count"];
		}
		
		$sql="select count(distinct(PromoterId)) as promoter_count from PayTransaction where AppId=? and PromoterProrate=? and TransactDt>=? and TransactDt<=? and TransactType=1";
		$profitHistory=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($profitHistory) && is_array($profitHistory) && count($profitHistory)>0){
			$data["promoter_count"]=$profitHistory[0]["promoter_count"];
		}
		return $data;
	}
	public function getViewApps($conditionColumn,$conditionValue)
	{
		$sql="select * from ViewApp where AppId=?";
		if($conditionColumn=="AppName"){
			$sql="select * from ViewApp where AppName like ?";
			$conditionValue="%".$conditionValue."%";
		}
		$params=array($conditionValue);
		if(empty($conditionColumn)){
			$sql="select * from ViewApp";
			$params=array();
		}
		$viewApp=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($viewApp) && is_array($viewApp) && count($viewApp)>0){
			return $viewApp;
		}
		return array();
	}
	//统计游戏申请推广员人数
	public function getAppApply($appId)
	{
		$sql="select count(distinct(PromoterId)) as ApplyCount from AppPackage where PromoterId>0 and AppVersionId in (select AppVersionId from AppVersion where AppId=?)";
		$params=array($appId);
		$appApply=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($appApply) && is_array($appApply) && count($appApply)>0){
			return $appApply[0]["ApplyCount"];
		}
		return 0;
	}
	//统计游戏下载次数
	public function getAppDownloadCount($appId)
	{
		$sql="select sum(DownloadCount) as DownloadCount from AppPackage where PromoterId>0 and AppVersionId in (select AppVersionId from AppVersion where AppId=?)";
		$params=array($appId);
		$appApply=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($appApply) && is_array($appApply) && count($appApply)>0){
			return $appApply[0]["DownloadCount"]==null?0:$appApply[0]["DownloadCount"];
		}
		return 0;
	}
	//统计游戏登陆人数 
	public function getAppLoginCount($appId)
	{
		$sql="select count(distinct(Sdid)) as LoginCount from PlayLoginHistory where AppId=?";
		$params=array($appId);
		$appApply=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($appApply) && is_array($appApply) && count($appApply)>0){
			return $appApply[0]["LoginCount"];
		}
		return 0;
	}
	//统计冲值人数
	public function getAppPayCount($appId)
	{
		$sql="select count(distinct(PlayerId)) as play_count from PayTransaction where AppId=? and TransactType=1";
		$params=array($appId);
		$profitHistory=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($profitHistory) && is_array($profitHistory) && count($profitHistory)>0){
			return $profitHistory[0]["play_count"];
		}
		return 0;
	}


	//提现、支付宝转帐相关************************************************************************************/
	//统计 前1天 分红下家 发生的冲值总金额  
	public function getPayTransactionAmount(){
		$sql = " SELECT SUM(BINARY Amount) as amounts FROM PayTransaction WHERE TransactType = 1 and  TO_DAYS(NOW()) - to_days(TransactDt) = 1 ;";
		$sql = " SELECT SUM(BINARY Amount) as amounts FROM PayTransaction WHERE TransactType = 1;";
		$params=array();
		$payTransactionAmount=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($payTransactionAmount) && is_array($payTransactionAmount) && count($payTransactionAmount)>0 && !is_null($payTransactionAmount[0]["amounts"])){
			return $payTransactionAmount[0]["amounts"];
		}
		return 0;
	}

	//获取前一天分红下家发生的冲值记录，包括金额、游戏厂商的分成比例,游戏厂商的分成基数比例
	public function getCompTransList(){
		$sql = "select BINARY  GameAmount as amount ,  BINARY (Amount+TaxFee) AS alipayAmount ,BINARY PromoterProrate  as rate , PromotionNo  FROM PayTransaction WHERE  TransactType=1 AND  TO_DAYS(NOW()) - to_days(TransactDt) = 1; "; 
		// DEBUG
		$params=array();
		$compTransList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($compTransList) && is_array($compTransList) && count($compTransList) > 0){
			return $compTransList;
		}
		return array();		
	}


	//获取前一天分红下家发生的冲值记录，包括金额、游戏厂商的分成比例,游戏厂商的分成基数比例
	public function getCompTransListQueryDate($queryDate){
		//$sql = "select BINARY  GameAmount as amount ,  BINARY (Amount+TaxFee) AS alipayAmount ,BINARY PromoterProrate  as rate , PromotionNo , AppId FROM PayTransaction WHERE  TransactType=1 AND  TO_DAYS(NOW()) - to_days(TransactDt) = ?; ";
        $sql = "SELECT a.TransactionId,BINARY  a.GameAmount AS amount ,  BINARY (a.Amount+a.TaxFee) AS alipayAmount ,BINARY a.PromoterProrate  AS rate ,a.PromotionNo , a.AppId,b.PhoneNo,b.Mid FROM PayTransaction a,Promoter b WHERE  a.PromoterId = b.PromoterId AND a.TransactType=1 AND  TO_DAYS(NOW()) - TO_DAYS(a.TransactDt) = ?;";  
		$params=array($queryDate);
		$compTransList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($compTransList) && is_array($compTransList) && count($compTransList) > 0){
			return $compTransList;
		}
		return array();		
	}
    //记录转账日志
	public function insertComTrans($transDt,$queryDate,$totalAmount,$appAmount,$platformAmount,$promoterAmount,$customAmount)
	{
		$sql="insert into AlipayCompTrans(TransDt,QueryDt,TotalAmount,AppAmount,PlatformAmount,PromoterAmount,CustomAmount,CreateDt) values (?,?,?,?,?,?,?,NOW())";
		$params=array($transDt,$queryDate,$totalAmount,$appAmount,$platformAmount,$promoterAmount,$customAmount);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}    


	//根据推广渠道号获取游戏厂商分成比例
	//AppPackage.PromotionNo ==> AppVersion.AppVersionId =>App.AppId 
	public function getAppRateByPromotionNo($promotionNo){
		$sql = "SELECT c.DeveloperProrate   FROM AppPackage a , AppVersion b , App c WHERE a.AppVersionId = b.AppVersionId AND b.AppId = c.AppId AND a.PromotionNo = ? ";
		$params=array($promotionNo);
		$dbData =LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($dbData) && is_array($dbData) && count($dbData)>0 && !is_null($dbData[0]["DeveloperProrate"])){
			return $dbData[0]["DeveloperProrate"];
		}
		return 0;		
	}	

	//根据推广渠道号获取游戏厂商分成比例
	//AppPackage.PromotionNo ==> AppVersion.AppVersionId =>App.AppId 
	public function getAppInfoByPromotionNo($promotionNo){
		$sql = "SELECT c.DeveloperProrate ,c.CostFeePercent  FROM AppPackage a , AppVersion b , App c WHERE a.AppVersionId = b.AppVersionId AND b.AppId = c.AppId AND a.PromotionNo = ? ";
		$params=array($promotionNo);
		$dbData =LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($dbData) && is_array($dbData) && count($dbData)>0){
			return $dbData[0];
		}
		return array();		
	}


	//统计 前1天 分红下家 发生的提现请求总金额   PromoterAliPayApply
	public function getPromoterAliPayApplyAmount(){
		$sql = " SELECT SUM(BINARY Amount) as amounts FROM PromoterAliPayApply WHERE TransactType = 1 and  TO_DAYS(NOW()) - to_days(TransactDt) = 1 ;";
		$params=array();
		$promoterAliPayApplyAmount=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($promoterAliPayApplyAmount) && is_array($promoterAliPayApplyAmount) && count($promoterAliPayApplyAmount)>0 && !is_null($promoterAliPayApplyAmount[0]["amounts"])){
			return $promoterAliPayApplyAmount[0]["amounts"];
		}
		return 0;		
	}


	//统计前一天所有已审核的末提交支付宝接口的提现请求 ，且分红员为可提现状态 
	//2014-12-15 修改为统计昨天之前的提现申请，且已审核，且分红员为可提现状态 
	public function getPromoterAliPayYesterdayList(){
		// $sql = " SELECT a.ApplyId ,a.Amount ,a.Fee ,a.AliPayNo,b.PromoterName,b.AliPayName  FROM PromoterAliPayApply  a ,Promoter b  WHERE a.PromoterId = b.PromoterId  AND b.PayState = 1 AND a.State = 2  AND  TO_DAYS(NOW()) - to_days(ApprovalDt) = 1 ;"; 
		$sql = " SELECT a.ApplyId ,a.Amount ,a.Fee ,a.AliPayNo,b.PromoterName,b.AliPayName  FROM PromoterAliPayApply  a ,Promoter b  WHERE a.PromoterId = b.PromoterId  AND b.PayState = 1 AND a.State = 2  AND  TO_DAYS(NOW()) - TO_DAYS(a.CreateDt) >= 1 ;"; 
		$params=array();
		$promoterAliPayApplyList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($promoterAliPayApplyList) && is_array($promoterAliPayApplyList) && count($promoterAliPayApplyList)>0 ){
			return $promoterAliPayApplyList;
		}
		return array();		
	}


	//修改提现申请表状态 ，同时提现的钱即时回到推广员帐户
	//0: 待审核
	// 1: 审核不通过，拒绝提现申请
	// 2: 审核通过，待提交到支付宝
	// 3: 已提交到支付宝
	// 4: 支付宝已到帐
	// 5: 支付宝处理失败
	public function updatePromoterAlipayApply($applyid,$state ,$memo){
		$applyids = implode('\',\'',$applyid);
		$sql="update PromoterAliPayApply set State = ? ,ResultMemo = ?,ApprovalDt=NOW() where ApplyId in ('" . $applyids . "') ";
		$params=array($state,$memo);
		$ret = LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);

		return $ret ;
	}

	/**
	 * 如果是提现审核末成功 ，则修改Promoter表Amount、NetAmount ，分别+PayTransaction.Amount+Fee; 
	 * @param  [type] $promoterId [description]
	 * @param  [type] $amount     [description]
	 * @return [type]             [description]
	 */
	public function returnPromoterAmount($promoterId , $amount , $netAmount){
		$sql="update Promoter set Amount = ? ,NetAmount  = ? where PromoterId = ?";
		$params=array($amount,$netAmount,$promoterId);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}

	/**如果是提现支付宝回调提示末转账成功  ，则修改Promoter表Amount、NetAmount ，分别+PayTransaction.Amount+Fee; 
	 * [returnPromoterFailAmount description]
	 * @param  [type] $promoterId [推广员ID]
	 * @param  [type] $amount     [需要回加的金额]
	 * @return [type]             [description]
	 */
	public function returnPromoterFailAmount($promoterId , $amount){
		$sql="update Promoter set Amount = Amount + ? ,NetAmount  = NetAmount + ? where PromoterId = ?";
		$params=array($amount,$amount,$promoterId);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}

	////审核或支付宝回调修改提现申请表状态
	public function updateProAlipayApply($applyid,$state ,$memo){
		if(in_array($state,array(3,4,5))){
			$sql="update PromoterAliPayApply set State = ? ,ResultMemo = ?,ReplyDt=NOW() where ApplyId = ?";
		}else if(in_array($state, array(0,1,2))){
			$sql="update PromoterAliPayApply set State = ? ,ResultMemo = ?,ReplyDt=NOW() where ApplyId = ?";
		}

		$params=array($state,$memo,$applyid);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}

	////支付宝回调修改提现申请表状态
	public function updateCompAlipayApply($batchNo , $state ,$memo){
		$sql="update CompAlipayApply set State = ? ,ResultMemo = ?,ReplyDt=NOW() where BatchNo =? ";
		$params=array($state,$memo,$batchNo);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}

	/**
	 * 获取申请提现列表
	 *
	 * @param int $state [0未审核|1审核不通过|2审核通过|3已提交至支付宝|4支付宝已到账|5支付宝处理失败]
	 *
	 */
	public function getPromoterAlipayApplyLists($state = 0) {
		if (false === in_array($state, array(0, 1, 2, 3, 4, 5), true)) {
			return array();
		}
		$sql = " SELECT * FROM PromoterAliPayApply WHERE State = $state  limit 1000 ;";
		$params=array();
		$promoterAliPayApplyList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($promoterAliPayApplyList) && is_array($promoterAliPayApplyList) && count($promoterAliPayApplyList)>0 ){
			return $promoterAliPayApplyList;
		}
		return array();		
	}

	/**
	 * 企业帐户间转帐时记录流水
	 * @param  [type] $para [description]
	 * @return [type]       [description]
	 */
	public function insertCompAlipayApply($para){
		$OutAccount = $para['OutAccount'];
		$InAccount = $para['InAccount'];
		$Amount = $para['Amount'];
		$BatchNo = $para['BatchNo'];
		$State = $para['State'];
		$ResultMemo = $para['ResultMemo'];
		
		$sql="insert into CompAlipayApply ( OutAccount,InAccount,Amount,BatchNo,State , ResultMemo ,CreateDt) values (?,?,?,?,?,?,NOW() )";
		$params=array( $OutAccount,$InAccount,$Amount,$BatchNo,$State,$ResultMemo);

		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}

    /**
     * 查询是否已经转账过
     */
    public function queryCompAlipayApply($queryDate){
        $sql = "SELECT * FROM CompAlipayApply WHERE TO_DAYS(NOW()) - TO_DAYS(CreateDt) = ?"  ;
        $params = array($queryDate);
		$dbData=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($dbData) && is_array($dbData) && count($dbData)>0 ){
			return $dbData;
		}
		return array();        
    }

	/**
	 * 根据转账批次号获取企业帐户间转帐详细信息，状态为已提交至支付宝
	 * @param  [type] $batchNo [支付宝转账批次号]
	 * @return [type]          [description]
	 */
	public function getInfoByBatchNo($batchNo){
		$sql = " SELECT * FROM CompAlipayApply    WHERE BatchNo = ? AND　State = 3 ;"; 
		$params=array($batchNo);
		$dbData=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($dbData) && is_array($dbData) && count($dbData)>0 ){
			return $dbData;
		}
		return array();	
	}

	/**
	 * 根据转账批次号获取转帐给分红员账务详细信息，状态为已提交至支付宝
	 * @param  [type] $batchNo [description]
	 * @return [type]          [description]
	 */
	public function getPromoterTransByBatchNo($batchNo){
		$sql = " SELECT * FROM PromoterAliPayApply WHERE BatchNo = ? AND　State = 3 ;"; 
		$params=array($batchNo);
		$dbData=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($dbData) && is_array($dbData) && count($dbData)>0 ){
			return $dbData;
		}
		return array();
	}

	/**
	 * 推送消息后，增加推广员消息服务端拉取数据
	 * @param  [type] $para [description]
	 * @return [type]       [description]
	 */
	public function insertPromoterAmountMsg($para){
		$MsgType = $para['MsgType'];
		$PromoterId = $para['PromoterId'];
		$MsgBody = $para['MsgBody'];
		
		$sql="insert into PromoterAmountMsg ( MsgType,PromoterId,MsgBody,CreateDt) values (?,?,?,NOW() )";
		$params=array( $MsgType,$PromoterId,$MsgBody);

		$rowCount = LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
		$msgId =  0 ;
		if($rowCount > 0){
			$sql = "SELECT * FROM PromoterAmountMsg WHERE PromoterId = ? ORDER BY MsgId DESC LIMIT 1 ;";
			$params=array( $PromoterId);
			$dbData=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
			if(isset($dbData) && is_array($dbData) && count($dbData)>0 && !is_null($dbData[0]["MsgId"])){
				$msgId = $dbData[0]["MsgId"];
			}
			
		}
		return $msgId;
	}

	public function updatePromoterAmountMsg($para){
		$MsgType = $para['MsgType'];
		$PromoterId = $para['PromoterId'];
		$MsgBody = $para['MsgBody'];
		$MsgId = $para['MsgId'];
		$sql="update PromoterAmountMsg set MsgBody = ? where MsgId =? ";
		$params=array($MsgBody,$MsgId);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}

	/**
	 * 根据支付宝提现applyid获取分红推广员信息
	 * @param  [type] $applyid [description]
	 * @return [type]          [description]
	 */
	public function getAlipayNotifyInfoByApplyid($applyid){
		$sql = "select b.PhoneNo,a.PromoterId as PromoterId,a.Amount ,a.Fee,a.AliPayNo,a.State ,b.ClientAppVersion AS ClientAppVersion , b.ClientType AS ClientType from PromoterAliPayApply a , Promoter b where a.PromoterId = b.PromoterId and a.ApplyId = ? ";
		$params=array($applyid);
		$dbData =LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($dbData) && is_array($dbData) && count($dbData)>0 ){
			return $dbData;
		}
		return 0;		
	}

	public function getProAlipayApplyInfoByApplyid($applyid){
		$sql = "select * from PromoterAliPayApply where ApplyId = ? ";
		$params=array($applyid);
		$dbData =LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($dbData) && is_array($dbData) && count($dbData)>0 ){
			return $dbData;
		}
		return 0;			
	}


	public function getPromoterByPromoterId($promoterId)
	{
		$sql="select * from Promoter where PromoterId=?";
		$params=array($promoterId);
		$promoter=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($promoter) && is_array($promoter) && count($promoter)>0){
			return $promoter[0];
		}
		return array();
	}

	//无上家充值的情况下，需要用appId来获取游戏厂商的分成基数比例、分成比例
	public function getAppInfoByAppId($appId){
		$sql = "SELECT * from  App c WHERE AppId  = ? ";
		$params=array($appId);
		$dbData =LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($dbData) && is_array($dbData) && count($dbData)>0){
			return $dbData[0];
		}
		return array();			
	}


	//END支付宝转帐相关************************************************************************************/


	public function updateAppRegisterState($appId,$registerState)
	{
		$sql="update App set RegistState=?,UpdateDt=NOW() where AppId=? ";
		$params=array($registerState,$appId);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	public function getFeedbackById($feedbackId)
	{
		$sql="select a.*,b.PhoneNo from Feedback a left join Promoter b on b.PromoterId=a.PromoterId where a.FeedbackId=?";
		$params=array($feedbackId);
		$feedback=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($feedback) && is_array($feedback) && count($feedback)>0){
			return $feedback[0];
		}
		return false;
	}
	public function getFeedbackCount($startDt, $endDt, $Phone, $State)
	{
		$sql="select a.*,b.PhoneNo from Feedback a left join Promoter b on b.PromoterId=a.PromoterId where a.CreateDt>=? and a.CreateDt<=?";
		$params=array(sprintf("%s 00:00:00",$startDt),sprintf("%s 23:59:59",$endDt));
		if($State!="ALL"){
			$sql.=" and a.State=?";
			$params[]=$State;
		}
		if(empty($Phone)==false){
			$sql.=" and b.PhoneNo like ?";
			$params[]="%".$Phone."%";
		}
		$sql=sprintf("select count(1) as num from ( %s ) PNumInfo ",$sql);
		$feedback=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($feedback) && is_array($feedback) && count($feedback)>0){
			return $feedback[0]["num"];
		}
		return 0;
	}
	
	public function getFeedback($startDt, $endDt, $Phone, $State,$offSet,$page_size)
	{
		$sql="select a.*,b.PhoneNo from Feedback a left join Promoter b on b.PromoterId=a.PromoterId where a.CreateDt>=? and a.CreateDt<=?";
		$params=array(sprintf("%s 00:00:00",$startDt),sprintf("%s 23:59:59",$endDt));
		if($State!="ALL"){
			$sql.=" and a.State=?";
			$params[]=$State;
		}
		if(empty($Phone)==false){
			$sql.=" and b.PhoneNo like ?";
			$params[]="%".$Phone."%";
		}
		$sql.=" order by a.CreateDt desc ";
		if($offSet>=0) {
			$sql=$sql." limit $offSet,$page_size";
		}		
		$feedback=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($feedback) && is_array($feedback) && count($feedback)>0){
			return $feedback;
		}
		return array();
	}
	public function updateFeedbackReplyContent($replyContent, $feedbackId)
	{
		$sql="update Feedback set ReplyContent=?,State=1,UpdateDt=NOW() where FeedbackId=?";
		$params=array($replyContent, $feedbackId);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	public function getCDNAppVersion()
	{
		$sql="select AppVersionId,count(1) as PackageCount from AppPackagePoolCDN where State=0 group by AppVersionId";
		$params=array();
		$cdnPackageNums=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($cdnPackageNums) && is_array($cdnPackageNums) && count($cdnPackageNums)>0){
			return $cdnPackageNums;
		}
		return array();
	}
	public function getAppVersion($appId,$versionName)
	{
		$sql="select a.*,b.PackageNum from AppVersion a left join AppPackageNum b on b.AppVersionId=a.AppVersionId where a.AppId=?";
		$params=array($appId);
		if(empty($versionName)==false){
			$sql.=" and VersionName like ?";
			$params[]="%".$versionName."%";
		}
		$versions=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($versions) && is_array($versions) && count($versions)>0){
			return $versions;
		}
		return array();		
	}
	public function getAppVersionPackageNums($AppVersionId)
	{
		$sql="select a.AppVersionId,b.PackageNum,c.AppName,c.MinPackingPoolSize from AppVersion a left join AppPackageNum b on b.AppVersionId=a.AppVersionId left join App c on c.AppId=a.AppId where a.AppVersionId= ? ";
		$params=array($AppVersionId);
		$versions=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($versions) && is_array($versions) && count($versions)>0){
			return $versions[0];
		}
		return array();
	}
	public function getOtherVersions($appId,$excludeAppVersionId)
	{
		$sql="select * from AppVersion where AppId=? and App_AppVersionId is null and AppVersionId <> ?";
		$params=array($appId,$excludeAppVersionId);
		$versions=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($versions) && is_array($versions) && count($versions)>0){
			return $versions;
		}
		return array();
	}
	public function updateAppVersionWithPackage($VersionName, $GameSize, $GamePics, $State, $TestPhone, $appVersionId,$fileName,$IsPublishVersion,$App_AppVersionId,$PackageMd5)
	{
		$sql="update AppVersion set VersionName=?,GameSize=?,GamePics=?,State=?,TestPhone=?,IsPublishVersion=?,UpdateDt=NOW(),App_AppVersionId=? where AppVersionId=?";
		$params=array($VersionName, $GameSize, $GamePics, $State, $TestPhone,$IsPublishVersion,($App_AppVersionId=="NO"?null:$App_AppVersionId),$appVersionId);

		$ret=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
		if(empty($fileName)==false){
			$sql="update AppVersion set PackagePath=?,PackageMd5=?,PackageState=1,UpdateDt=NOW() where AppVersionId=? and PackageState  in (0,1)";
			$params=array($fileName,$PackageMd5,$appVersionId);
			LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
		}
		return $ret;
	}
	public function insertAppVersionWithPackage($VersionName, $GameSize, $GamePics, $State, $TestPhone, $fileName,$IsPublishVersion,$PackageMd5,$AppId)
	{
		$sql="insert into AppVersion ( AppId,VersionName,GameSize,GamePics,State,TestPhone,IsPublishVersion,CreateDt,UpdateDt,PackageState) values (?,?,?,?,?,?,?,NOW(),NOW(),0 )";
		$params=array($AppId,$VersionName, $GameSize, $GamePics, $State, $TestPhone, $IsPublishVersion);
		if(empty($fileName)==false){
			$sql="insert into AppVersion ( AppId,VersionName,GameSize,GamePics,State,TestPhone,IsPublishVersion,CreateDt,UpdateDt,PackageState,PackagePath,PackageMd5) values (?,?,?,?,?,?,?,NOW(),NOW(),1,?,? )";
			$params=array($AppId,$VersionName, $GameSize, $GamePics, $State, $TestPhone, $IsPublishVersion,$fileName,$PackageMd5);
		}
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	public function updateAppVersionPackageState($appVersionId, $packageState)
	{
		$sql="update AppVersion set PackageState=?,UpdateDt=NOW() where AppVersionId=?";
		$params=array($packageState,$appVersionId);
		$ret=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	public function getTestingPackNum()
	{
		return $this->getSystemConfigItem("package.testing.min",3);
	}
	public function getAppPackingLog($AppVersionId)
	{
		$sql="select * from AppPackaging_Log where AppVersionId=? order by CreateDt desc";
		$params=array($AppVersionId);
		$appPackingLog=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($appPackingLog) && is_array($appPackingLog) && count($appPackingLog)>0){
			return $appPackingLog;
		}
		return array();
	}
	
	public function logPackingRequest($AppVersionId,$RequestCount)
	{
		$logReturn=false;
		$pdo=LunaPdo::GetInstance($this->_PDO_NODE_NAME);
		try {
			$sql="select  * from AppPackaging_Log where AppVersionId=? order by CreateDt desc FOR UPDATE ";
			$params=array($AppVersionId);
			$rowItem=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
			
			$beginId=1;
			$endId=$RequestCount;
			
			if(isset($rowItem) && is_array($rowItem) && count($rowItem)>0){
				$lastId=$rowItem[0]["PromotionNoEnd"];
				$beginId	=$lastId+1;
				$endId		=$lastId+$RequestCount;
			}
			$sql="insert into AppPackaging_Log (AppVersionId,PromotionNoStart,PromotionNoEnd,RequestCount,FinishCount,CreateDt,UpdateDt) values (?,?,?,?,0,NOW(),NOW())";
			$params=array($AppVersionId,$beginId,$endId,$RequestCount);
			$rowCount=$pdo->exec_with_prepare($sql,$params);
			if($rowCount>0){
				$logReturn=$beginId;
			}
		}catch (Exception $ex){
			LunaLogger::getInstance()->error($ex->getMessage());
		}
		return $logReturn;
	}
	public function insertAppPackage($appVersionId,$PromotionNo,$downloadUrl,$isCDNPool=false)
	{
		$tablePostfix= ($isCDNPool?"CDN":"");
		$sql="insert into AppPackagePool".$tablePostfix." (AppVersionId,PromotionNo,DownloadUrl,State,CreateDt,UpdateDt) values (?,?,?,0,NOW(),NOW())";
		$params=array($appVersionId,$PromotionNo,$downloadUrl);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	public function updateAppPackaging_Log($appVersionId,$PromotionNo)
	{
		$sql="update AppPackaging_Log set FinishCount=FinishCount+1,UpdateDt=NOW() where AppVersionId=? and PromotionNoStart<= ? and PromotionNoEnd>=?";
		$params=array($appVersionId,$PromotionNo,$PromotionNo);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
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
	public function getFileIdByFileName($fielName)
	{
		$sql="select * from File where FileUrl=?";
		$params=array($fielName);
		$files=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($files) && is_array($files) && count($files)>0){
			return $files[0];
		}
		return array();		
	}
	public function getFileNameByFielId($fileId)
	{
		$sql="select * from File where FileId=?";
		$params=array($fileId);
		$files=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($files) && is_array($files) && count($files)>0){
			return $files[0]["FileUrl"];
		}
		return "";		
	}
	
	public function updateAppVersionTaskStatus($appId,$appVersionId,$packageUrl)
	{
		$sql="update AppVersion set PackageState=3,PackageUrl=? where AppVersionId=? ";
		$params=array($packageUrl,$appVersionId);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	public function getMsgChannels()
	{
		$sql="select * from MsgChannels";
		$msgChannels=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,array(),PDO::FETCH_ASSOC);
		if(isset($msgChannels) && is_array($msgChannels) && count($msgChannels)>0){
			return $msgChannels;
		}
		return array();		
	}
	public function getDefaultPayPeriod()
	{
		return $this->getSystemConfigItem("promoter.tixian.min.days","7");
	}
	public function getDefaultAlipayLimit()
	{
		return $this->getSystemConfigItem("promoter.tixian.max.month","3000");
	}
	public function getAdGameListDef()
	{
		return $this->getSystemConfigItem("ad.gamelist",array(),true);
	}
	public function updateAdGameListDef($adGameList)
	{
		return $this->updateSystemConfigItem("ad.gamelist", $adGameList);
	}
	public function getAdStartListDef()
	{
		return $this->getSystemConfigItem("ad.app.start",array(),true);
	}
	public function updateAdStartListDef($adAppStart)
	{
		return $this->updateSystemConfigItem("ad.app.start", $adAppStart);
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
	public function updateSystemConfigItem($configKey,$configValue)
	{
		$sql="update SystemConfig set ConfigValue=? where ConfigKey=?";
		$params=array($configValue,$configKey);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	
	public function insertInformation($ChannelId,$TypeId,$Title,$NotifyTitle,$Abstract,$ImageUrl,$DetailUrl,$Content,$WeixinTitle,$WeixinPic,$WeixinContent,$WeiBoContent,$WeiBoPic,$PhoneMsg,$msgSessionId,$SourceType)
	{
		$sql="insert into Information (ChannelId,ChannelTypeId,Title,NotifyTitle,Abstract,ImageUrl,DetailUrl,Content,WeixinTitle,WeixinPic,WeixinContent,WeiBoContent,WeiBoPic,PhoneMsg,SessionId,SourceType,State,CreateDt) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,0,NOW()) ";
		$params=array($ChannelId,$TypeId,$Title,$NotifyTitle,$Abstract,$ImageUrl,$DetailUrl,$Content,$WeixinTitle,$WeixinPic,$WeixinContent,$WeiBoContent,$WeiBoPic,$PhoneMsg,$msgSessionId,$SourceType);				
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);		
	}
	public function updateInformation($ChannelId,$TypeId,$Title,$NotifyTitle,$Abstract,$ImageUrl,$DetailUrl,$Content,$WeixinTitle,$WeixinPic,$WeixinContent,$WeiBoContent,$WeiBoPic,$PhoneMsg,$InformationId)
	{
		$sql="update Information set ChannelId=?,ChannelTypeId=?,Title=?,NotifyTitle=?,Abstract=?,ImageUrl=?,DetailUrl=?,Content=?,WeixinTitle=?,WeixinPic=?,WeixinContent=?,WeiBoContent=?,WeiBoPic=?,PhoneMsg=? where InformationId=? ";
		$params=array($ChannelId,$TypeId,$Title,$NotifyTitle,$Abstract,$ImageUrl,$DetailUrl,$Content,$WeixinTitle,$WeixinPic,$WeixinContent,$WeiBoContent,$WeiBoPic,$PhoneMsg,$InformationId);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	
	public function getLastInsertInformationId($Title)
	{
		$sql="select * from Information where Title=? order by InformationId desc limit 1";
		$gameVersion=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,array($Title),PDO::FETCH_ASSOC);
		if(isset($gameVersion) && is_array($gameVersion) && count($gameVersion)>0){
			return $gameVersion[0]["InformationId"];
		}
		return "";
	}
	
	public function getInformationList($startDt,$endDT,$state,$title="")
	{
		$sql="select * from Information where State=? and CreateDt>=? and CreateDt<=? and SourceType='MsgMagt' ";
		$params=array($state,$startDt,$endDT);
		if(empty($title)==false){
			$sql.=" and Title like ?";
			$params[]="%".$title."%";
		}
		$informationList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($informationList) && is_array($informationList) && count($informationList)>0){
			return $informationList;
		}
		return array();
	}
	public function getInformationById($InformationId)
	{
		$sql="select * from Information where InformationId=? ";
		$params=array($InformationId);		
		$informationList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($informationList) && is_array($informationList) && count($informationList)>0){
			return $informationList[0];
		}
		return array();
	}
	public function updateInfomationPhoneList($InformationId,$scheduleTime,$phoneList,$phoneListCount,$needUpdatePhoneList)
	{
		$sql="update Information set ScheduleTime=? where InformationId=?";
		$params=array(empty($scheduleTime)?null:$scheduleTime,$InformationId);
		if($needUpdatePhoneList){
			$sql="update Information set ScheduleTime=?,PhoneList=?,PhoneListFileCounts=? where InformationId=?";
			$params=array(empty($scheduleTime)?null:$scheduleTime,$phoneList,$phoneListCount,$InformationId);
		}
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	public  function getPromoterIdByPhones($phones)
	{
		$phoneTxt="";
		foreach ($phones as $p){
			$phoneTxt.=",".$p;
		}
		$phoneTxt=substr($phoneTxt,1);
		$sql="select PromoterId,PhoneNo from Promoter where PhoneNo in (".$phoneTxt.")";
		$promoters=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,array(),PDO::FETCH_ASSOC);
		if(isset($promoters) && is_array($promoters) && count($promoters)>0){
			return $promoters;
		}
		return array();
	}
	public function insertPromoterMsg($promoterId,$InformationId)
	{
		$sql="insert into Information_Promoter (PromoterId,InformationId,State,CreateDt) values (?,?,0,NOW())";
		$params=array($promoterId,$InformationId);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	public function updateInformationState($state,$InformationId,$SentPhoneCount)
	{
		$sql="update Information set State=?,SentPhoneCount=? where InformationId=?";
		$params=array($state,$SentPhoneCount,$InformationId);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}	
	
	public function getSentCountByInformationId($InformationId)
	{
		$sql="select count(*) as num_count from Information_Promoter where InformationId=?";
		$params=array($InformationId);
		$sentRows=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($sentRows) && is_array($sentRows) && count($sentRows)>0){
			return $sentRows[0]["num_count"];
		}
		return 0;
	}
	public function getPromoterByPhone($phone)
	{
		$sql="select * from Promoter where PhoneNo=?";
		$params=array($phone);
		$promoter=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($promoter) && is_array($promoter) && count($promoter)>0){
			return $promoter[0];
		}
		return array();
	}
	public function getPromoterPhoneById($PromoterId)
	{
		$sql="select PhoneNo from Promoter  where PromoterId=?";
		$params=array($PromoterId);
		$promoter=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($promoter) && is_array($promoter) && count($promoter)>0){
			return $promoter[0]["PhoneNo"];
		}
		return "";						
	}
	public function updatePromoterPayState($PromoterId,$PayState)
	{
		$sql="update Promoter set PayState=? where PromoterId=? ";
		$params=array($PayState,$PromoterId);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	public function updatePromoterPayStateByPhone($Phone,$PayState)
	{
		$sql="update Promoter set PayState=? where PhoneNo=? ";
		$params=array($PayState,$Phone);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	
	public function getUserChannelList($phone,$channelId)
	{
		$sql="select * from   PromoterChannelInformation where PhoneNo=? and ChannelId=? order by CreateDt desc";
		$params=array($phone,$channelId);
		$informations=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($informations) && is_array($informations) && count($informations)>0){
			return $informations;
		}
		return array();
	}
	public function getChannelById($channelId)
	{
		$sql="select * from MsgChannelDefinition where ChannelId=?";
		$params=array($channelId);
		$channnels=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($channnels) && is_array($channnels) && count($channnels)>0){
			return $channnels[0];
		}
		return array();
	}
	public function syncInsertChannelId($channelId,$channelIcon,$channelName)
	{
		$sql="insert into MsgChannelDefinition (ChannelId,Name,Icon,ListType,UpdateDt,SortOrder,Status) values (?,?,?,1,NOW(),0,1)";
		$params=array($channelId,$channelName,$channelIcon);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	public function insertChannelDef($channelName,$channelIcon,$channelDesc,$SortOrder,$ListType,$ListUrl,$channelId,$Status,$TestPhone)
	{
		$sql="insert MsgChannelDefinition (Name,Icon,DescContent,SortOrder,ListType,ListUrl,UpdateDt,ChannelId,Status,TestPhone) values (?,?,?,?,?,?,NOW(),?,?,?) ";
		$params=array($channelName,$channelIcon,$channelDesc,$SortOrder,$ListType,$ListUrl,$channelId,$Status,$TestPhone);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	
	public function updateChannelDef($channelName,$channelIcon,$channelDesc,$SortOrder,$ListType,$ListUrl,$channelId,$Status,$TestPhone)
	{
		$sql="update MsgChannelDefinition set Name=?,Icon=?,DescContent=?,SortOrder=?,ListType=?,ListUrl=?,Status=?,TestPhone=?,UpdateDt=NOW() where ChannelId=?";
		$params=array($channelName,$channelIcon,$channelDesc,$SortOrder,$ListType,$ListUrl,$Status,$TestPhone,$channelId);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	public function getChannelDefList()
	{
		$sql="select * from MsgChannelDefinition";
		$channnels=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,array(),PDO::FETCH_ASSOC);
		if(isset($channnels) && is_array($channnels) && count($channnels)>0){
			return $channnels;
		}
		return array();		
	}
	public function delChannel($channelId)
	{
		$sql="delete from MsgChannelDefinition where ChannelId=?";
		$params=array($channelId);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	
	//推广员累积申请提现
	public function getPromoterAliPaySummary($PromoterId)
	{
		$sql="select sum(Amount+Fee)  as PayedSummary from PromoterAliPayApply where PromoterId=? and State in (0,2,3,4)";
		$params=array($PromoterId);
		$PromoterRow=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($PromoterRow) && is_array($PromoterRow) && count($PromoterRow)>0){
			return $PromoterRow[0]["PayedSummary"];
		}
		return 0;
	}
	//推广员累积分红充值
	public function getPromoterGameAmountSummary($PromoterId)
	{
		$sql="select sum(GameAmount) as GameAmount from PayTransaction where TransactType=1 and PromoterId=?";
		$params=array($PromoterId);
		$PromoterRow=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($PromoterRow) && is_array($PromoterRow) && count($PromoterRow)>0){
			return $PromoterRow[0]["GameAmount"];
		}
		return 0;		
	}
	//推广员游戏下载统计
	public function getPromoterSummaryByGame($PromoterId)
	{
		$sql="select a.PromoterId,a.CreateDt,a.PromotionNo,a.AppVersionId,c.AppId,c.AppName,a.DownloadCount from AppPackage a left join AppVersion b on b.AppVersionId=a.AppVersionId left join App c on c.AppId=b.AppId where a.PromoterId=?";
		$params=array($PromoterId);
		$gameSumm=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($gameSumm) && is_array($gameSumm) && count($gameSumm)>0){
			return $gameSumm;
		}
		return array();		
	}
	//推广员游戏累积收入 
	public function getPromoterPayTransctionByGame($PromoterId)
	{
		$sql="select AppId, sum(Amount) as Amount,sum(TaxFee) as TaxFee,sum(GameAmount) as GameAmount from PayTransaction where TransactType=1 and PromoterId=? group by AppId";
		$params=array($PromoterId);
		$gameSumm=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($gameSumm) && is_array($gameSumm) && count($gameSumm)>0){
			return $gameSumm;
		}
		return array();		
	}
	
	//推广包库存量
	public function getPackagesNum()
	{
		$sql="select a.AppId,a.AppVersionId,a.State,a.IsPublishVersion,b.PackageNum,c.AppStatus from AppVersion a left join PackageNum b on b.AppVersionId=a.AppVersionId left join App c on c.AppId=a.AppId where a.PackageState=3 and a.State in(0,1) and a.App_AppVersionId is null";
		$gameSumm=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,array(),PDO::FETCH_ASSOC);
		if(isset($gameSumm) && is_array($gameSumm) && count($gameSumm)>0){
			return $gameSumm;
		}
		return array();
	}
	//推广员申请提现记录
	public function getPromoterAliPayApply($PromoterId)
	{
		$sql="select * from PromoterAliPayApply where PromoterId=? order by ApplyId desc";
		$params=array($PromoterId);
		$gameSumm=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($gameSumm) && is_array($gameSumm) && count($gameSumm)>0){
			return $gameSumm;
		}
		return array();		
	}
	//下家充值记录
	public function getPlayPayHistory($StartDt,$EndDt,$PlayPhone="",$PromoterPhone="",$AppId="",$offSet=0,$page_size=10,$OrderId="")
	{
		$sql="select  a.PlayerId,a.PromoterId,a.TransactDt,a.GameAmount,a.AppId, a.PromoterProrate,a.Amount,a.TaxFee,a.CheckingID ,b.AppName,c.PhoneNo, d.PhoneNo as PlayPhone,c.MinPayReturn from PayTransaction  a left join App b on b.AppId=a.AppId left join Promoter c on c.PromoterId=a.PromoterId left join Player d on d.PlayerId=a.PlayerId where a.TransactType=1 and a.TransactDt>=? and a.TransactDt<=? ";
		$params=array($StartDt,$EndDt);
		if(empty($PlayPhone)==false){
			$sql.=" and d.PhoneNo like ? ";
			$params[]="%".$PlayPhone."%";
		}		
		if(is_array($PromoterPhone)){
			$phoneTxt=implode("','", $PromoterPhone);
			$sql.=" and c.PhoneNo in ('".$phoneTxt."') ";
		}else{
			if(empty($PromoterPhone)==false){
				$sql.=" and c.PhoneNo like ? ";
				$params[]="%".$PromoterPhone."%";
			}
		}
		if(empty($AppId)==false){
			$sql.=" and a.AppId = ?";
			$params[]=$AppId;
		}
		if(empty($OrderId)==false){
			$sql.=" and a.CheckingID like '%".$OrderId."%' ";
		}
		
		$sql=$sql." order by a.TransactDt desc ";
		if($offSet>=0) {
			$sql=$sql." limit $offSet,$page_size";
		}
		
		$payList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($payList) && is_array($payList) && count($payList)>0){
			return $payList;
		}
		return array();
	}
	public function getPlayPayHistoryCount($StartDt,$EndDt,$PlayPhone="",$PromoterPhone="",$AppId="",$OrderId="")
	{
		$sql="select  a.PlayerId,a.PromoterId,a.TransactDt,a.GameAmount,a.AppId, a.PromoterProrate,a.Amount,a.TaxFee ,b.AppName,c.PhoneNo, d.PhoneNo as PlayPhone from PayTransaction  a left join App b on b.AppId=a.AppId left join Promoter c on c.PromoterId=a.PromoterId left join Player d on d.PlayerId=a.PlayerId where a.TransactType=1 and a.TransactDt>=? and a.TransactDt<=? ";
		$params=array($StartDt,$EndDt);
		if(empty($PlayPhone)==false){
			$sql.=" and d.PhoneNo like ? ";
			$params[]="%".$PlayPhone."%";
		}
		if(is_array($PromoterPhone)){
			$phoneTxt=implode("','", $PromoterPhone);
			$sql.=" and c.PhoneNo in ('".$phoneTxt."') ";
		}else{
			if(empty($PromoterPhone)==false){
				$sql.=" and c.PhoneNo like ? ";
				$params[]="%".$PromoterPhone."%";
			}
		}
		if(empty($AppId)==false){
			$sql.=" and a.AppId = ?";
			$params[]=$AppId;
		}
		if(empty($OrderId)==false){
			$sql.=" and a.CheckingID like '%".$OrderId."%' ";
		}
		
		$sql=sprintf("select count(1) as num from ( %s ) PNumInfo ",$sql);
		$payList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($payList) && is_array($payList) && count($payList)>0){
			return $payList[0]["num"];
		}
		return 0;
	}
	
	public function getPayReturnHistory($StartDt,$EndDt,$PlayPhone="", $PromoterPhone="",$AppId="")
	{
		$sql="select a.PlayerId,a.PromoterId,a.TransactDt,a.Amount,a.GameAmount,a.AppId,a.CheckingID,a.Status,b.AppName
				from PayReturn a
				left join App b on b.AppId=a.AppId 
				left join Promoter c on c.PromoterId=a.PromoterId 
				left join Player d on d.PlayerId=a.PlayerId 
				where a.TransactDt>=? and a.TransactDt<=? ";
		$params=array($StartDt,$EndDt);
		if(empty($PlayPhone)==false){
			$sql.=" and d.PhoneNo like ? ";
			$params[]="%".$PlayPhone."%";
		}
		if(is_array($PromoterPhone)){
			$phoneTxt=implode("','", $PromoterPhone);
			$sql.=" and c.PhoneNo in ('".$phoneTxt."') ";
		}else{
			if(empty($PromoterPhone)==false){
				$sql.=" and c.PhoneNo like ? ";
				$params[]="%".$PromoterPhone."%";
			}
		}
		if(empty($AppId)==false){
			$sql.=" and a.AppId = ?";
			$params[]=$AppId;
		}
		$payList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($payList) && is_array($payList) && count($payList)>0){
			return $payList;
		}
		return array();
	}
	
	
	public function getAppByAppId($appId)
	{
		$sql="select * from App where AppId=?";
		$params=array($appId);
		$appInfo=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($appInfo) && is_array($appInfo) && count($appInfo)>0){
			return $appInfo[0];
		}
		return array();
	}
	public function getPhoneVersionReportDataCount($AppId,$phone="")
	{
		$sql="select c.PhoneNo from AppPackage a left join AppVersion b on b.AppVersionId=a.AppVersionId left join Promoter c on c.PromoterId=a.PromoterId left join PromotionNoLoginCount  d on d.PromotionNo= a.PromotionNo and d.AppId=b.AppId left join PromotionNoPayView e on e.PromotionNo=a.PromotionNo where a.PromoterId is not null and a.PromotionNo is not null and b.AppId=?";
		$params=array($AppId);
		if(empty($phone)==false){
			$sql.=" and c.PhoneNo like ? ";
			$params[]="%".$phone."%";
		}
		$sql.=" group by PhoneNo,VersionName";
		
		$sql=sprintf("select count(1) as num from ( %s ) PNumInfo ",$sql);
		$PhoneVersionReport=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($PhoneVersionReport) && is_array($PhoneVersionReport) && count($PhoneVersionReport)>0){
			return $PhoneVersionReport[0]["num"];
		}
		return 0;
	}
	public function getPhoneVersionReportData($AppId,$phone="",$offSet=0,$page_size=10)
	{
		$sql="select c.PhoneNo,Min(a.UpdateDt) AS UpdateDt,SUM(d.LoginCount) as LoginCount, b.VersionName,SUM(a.DownloadCount) as DowloadCount,e.PayCount,e.GameAmount,e.PromoterAmount from AppPackage a left join AppVersion b on b.AppVersionId=a.AppVersionId left join Promoter c on c.PromoterId=a.PromoterId left join PromotionNoLoginCount  d on d.PromotionNo= a.PromotionNo and d.AppId=b.AppId left join PromotionNoPayView e on e.PromotionNo=a.PromotionNo where a.PromoterId is not null and a.PromotionNo is not null and b.AppId=?";
		$params=array($AppId);
		if(empty($phone)==false){
			$sql.=" and c.PhoneNo like ? ";
			$params[]="%".$phone."%";
		}
		$sql.=" group by PhoneNo,VersionName order by Min(a.UpdateDt) desc ";
		if($offSet>=0){		 
			$sql=$sql." limit $offSet,$page_size";
		}
		
		$PhoneVersionReport=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($PhoneVersionReport) && is_array($PhoneVersionReport) && count($PhoneVersionReport)>0){
			return $PhoneVersionReport;
		}
		return array();
	}
	public function getPromoterPhoneHistoryByPhone($phone)
	{
		$sql="select a.* from PromoterPhoneHistory a left join Promoter b on b.PromoterId=a.PromoterId  where b.PhoneNo=? order by LoginDt desc limit 0,1";
		$params=array($phone);
		$promoter=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($promoter) && is_array($promoter) && count($promoter)>0){
			return $promoter[0];
		}
		return array();
	}
	public function getPlayPayLoginHistoryCount($startDt, $endDt,$phoneNo,$promoterPhone,$appId="")
	{
		$sql="select a.loginDt,b.AppName,c.PhoneNo as PlayerPhoneNo,e.PhoneNo from PlayLoginHistory a left join App b on b.AppId=a.AppId left join Player c on c.Sdid=a.Sdid left join AppPackage d on d.PromotionNo=a.PromotionNo left join Promoter e on e.PromoterId=d.PromoterId where a.loginDt between ? and ? ";
		$params=array($startDt,$endDt);
		if(empty($phoneNo)==false){
			$sql.=" and c.PhoneNo like ? ";
			$params[]="%".$phoneNo."%";
		}
		
		if(is_array($promoterPhone)){
			$phoneTxt=implode("','", $promoterPhone);
			$sql.=" and e.PhoneNo in ('".$phoneTxt."') ";
		}else{
			if(empty($promoterPhone)==false){
				$sql.=" and e.PhoneNo like ? ";
				$params[]="%".$promoterPhone."%";
			}
		}
		
		if(empty($appId)==false){
			$sql.=" and a.AppId = ? ";
			$params[]=$appId;
		}
		
		$sql=sprintf("select count(1) as num from ( %s ) PNumInfo ",$sql);
		$loginList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($loginList) && is_array($loginList) && count($loginList)>0){
			return $loginList[0]["num"];
		}
		return 0;
	}
	public function getPlayPayLoginHistory($startDt, $endDt,$phoneNo,$promoterPhone,$offSet=0,$page_size=10,$appId="")
	{
		$sql="select a.loginDt,b.AppName,c.PhoneNo as PlayerPhoneNo,e.PhoneNo from PlayLoginHistory a left join App b on b.AppId=a.AppId left join Player c on c.Sdid=a.Sdid left join AppPackage d on d.PromotionNo=a.PromotionNo left join Promoter e on e.PromoterId=d.PromoterId where a.loginDt between ? and ? ";
		$params=array($startDt,$endDt);
		if(empty($phoneNo)==false){
			$sql.=" and c.PhoneNo like ? ";
			$params[]="%".$phoneNo."%";
		}
		
		if(is_array($promoterPhone)){
			$phoneTxt=implode("','", $promoterPhone);
			$sql.=" and e.PhoneNo in ('".$phoneTxt."') ";
		}else{
			if(empty($promoterPhone)==false){
				$sql.=" and e.PhoneNo like ? ";
				$params[]="%".$promoterPhone."%";
			}
		}

		if(empty($appId)==false){
			$sql.=" and a.AppId = ? ";
			$params[]=$appId;
		}
		
		
		if($offSet>=0){
			$sql=$sql." limit $offSet,$page_size";
		}
		
		$loginList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($loginList) && is_array($loginList) && count($loginList)>0){
			return $loginList;
		}
		return array();
	}	
	
	function updatePromoterTask($PromotionNo,$OldUrl,$NewUrl)
	{
		$sql="update AppPackagePool set DownloadUrl=?  where PromotionNo=? and DownloadUrl=?";
		$params=array($NewUrl,$PromotionNo,$OldUrl);
		LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
		$sql="update AppPackage set DownloadUrl=?  where PromotionNo=? and DownloadUrl=?";
		LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	//删除推广员记税记录
	public function deletePromoterTax($Period,$PromoterId="")
	{
		$sql="delete from PromoterTax where TaxPeriod=? ";
		$params=array($Period);
		if(empty($PromoterId)==false){
			$sql.=" and PromoterId=? ";
			$params[]=$PromoterId;
		}
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	//统计 记/不记 税 收入
	public function getPromoterAmountTax($startDt,$endDt,$taxStartDate,$includeTax=true,$PromoterId="")
	{
		$sql="select PromoterId,sum(Amount) as Amount,sum(TaxFee) as TaxFee from PayTransaction where TransactType=1 and TransactDt between ? and ? ";
		if($includeTax){
			$sql.=" and TransactDt >= ? ";
		}else {
			$sql.=" and TransactDt < ? ";
		}
		$parmas=array($startDt,$endDt,$taxStartDate);
		if(empty($PromoterId)==false){
			$sql.=" and PromoterId=? ";
			$parmas[]=$PromoterId;
		}
		$sql.=" group by PromoterId";
	
		$promoterTaxList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$parmas,PDO::FETCH_ASSOC);
		if(isset($promoterTaxList) && is_array($promoterTaxList) && count($promoterTaxList)>0){
			return $promoterTaxList;
		}
		return array();
	}
	//增加记税记录
	function insertPromoterAmountTax($promoterId,$TaxPeriod,$AmountTax,$AmountNoTax,$PreTaxPercent,$PreTaxFee,$TaxPercent,$TaxFee,$Refund)
	{
		$sql="insert into  PromoterTax (PromoterId,TaxPeriod,AmountTax,AmountNoTax,PreTaxPercent,PreTaxFee,TaxPercent,TaxFee,Refund,CreateDt ) values (?,?,?,?,?,?,?,?,?,NOW()) ";
		$params=array($promoterId,$TaxPeriod,$AmountTax,$AmountNoTax,$PreTaxPercent,$PreTaxFee,$TaxPercent,$TaxFee,$Refund);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	function getPromoterTaxCount($PhoneNo, $Period)
	{
		$sql="select count(1) as num_count from PromoterTax a left join Promoter b on b.PromoterId=a.PromoterId where 1=1 ";
		$params=array();
		if(empty($PhoneNo)==false){
			$sql.=" and b.PhoneNo like ? ";
			$params[]="%".$PhoneNo."%";
		}
		if(empty($Period)==false){
			$sql.=" and a.TaxPeriod = ?";
			$params[]=$Period;
		}
		$promterTax=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($promterTax) && is_array($promterTax) && count($promterTax)>0){
			return $promterTax[0]["num_count"];
		}
		return 0;
	}
	function getPromoterTax($PhoneNo, $Period,$offSet=0,$page_size=10)
	{
		$sql="select a.*,b.PhoneNo from PromoterTax a left join Promoter b on b.PromoterId=a.PromoterId where 1=1 ";
		$params=array();
		if(empty($PhoneNo)==false){
			$sql.=" and b.PhoneNo like ? ";
			$params[]="%".$PhoneNo."%";
		}
		if(empty($Period)==false){
			$sql.=" and a.TaxPeriod = ?";
			$params[]=$Period;
		}
		if($offSet>=0){
			$sql=$sql." limit $offSet,$page_size";
		}
		$promterTax=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($promterTax) && is_array($promterTax) && count($promterTax)>0){
			return $promterTax;
		}
		return array();
	}
	public function getPackageForDeleting($AppVersionId,$Num)
	{
		$PromotionNo=array();
		$pdo=LunaPdo::GetInstance($this->_PDO_NODE_NAME);
		$pdo->beginTransaction();
		
		$sql="select * from AppPackagePool where AppVersionId=? and  State=0 order by PoolId desc limit ".$Num." for update";
		$params=array($AppVersionId);
		$packagePool=$pdo->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($packagePool) && is_array($packagePool) && count($packagePool)>0){
			foreach ($packagePool as $row){
				$PromotionNo[]=$row["PromotionNo"];
			}
		}
		if(count($PromotionNo)){
			$conditionSql= implode("','",$PromotionNo);
			$sql="delete from AppPackagePool where AppVersionId=? and  State=0 and PromotionNo in ('".$conditionSql."')";
			$pdo->exec_with_prepare($sql,$params);
		}
		$pdo->commit();
		return $PromotionNo;
	}	
	public function getAgencyPromoterNum($agencyName="",$agencyCode="",$telphone="",$channelType="",$isFrozen="")
	{
		$sql="select count(1) as num from PromoterAgency where 1=1 ";
		$params=array();
		if(empty($agencyName)==false){
			$sql.=" and Name like ?";
			$params[]="%".$agencyName."%";
		}
		if(empty($agencyCode)==false){
			$sql.=" and Code like ?";
			$params[]="%".$agencyCode."%";
		}
		if(empty($telphone)==false){
			$sql.=" and Telphone like ?";
			$params[]="%".$telphone."%";
		}
		if(empty($channelType)==false){
			$sql.=" and ChannelType =? ";
			$params[]=$channelType;
		}
		if(empty($isFrozen)==false){
			$sql.=" and IsFrozen =? ";
			$params[]=($isFrozen==1?1:0);
		}
		
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0];
		}
		return array("num" => 0);
	}
	public function getAgencyPromoter($agencyName="",$agencyCode="",$telphone="",$channelType="",$isFrozen="",$orderField="a.CreateDt",$offSet=0,$page_size=10)
	{
		$sql="select a.*,b.Amount,b.GameAmount from  PromoterAgency a left join ViewAgencyPay b on b.AgencyId=a.AgencyId where 1=1 ";
		$params=array();
		if(empty($agencyName)==false){
			$sql.=" and a.Name like ?";
			$params[]="%".$agencyName."%";
		}
		if(empty($agencyCode)==false){
			$sql.=" and a.Code like ?";
			$params[]="%".$agencyCode."%";
		}
		if(empty($telphone)==false){
			$sql.=" and a.Telphone like ?";
			$params[]="%".$telphone."%";
		}
		if(empty($channelType)==false){
			$sql.=" and a.ChannelType =? ";
			$params[]=$channelType;
		}
		if(empty($isFrozen)==false){
			$sql.=" and a.IsFrozen =? ";
			$params[]=($isFrozen==1?1:0);
		}
		$sql.=" order by ".$orderField." desc ";
		
		if($offSet>=0){
			$sql=$sql." limit $offSet,$page_size";
		}
		
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info;
		}
		return array();
	}
	public function insertAgencyPromoter($agencyName,$agencyCode,$agencyLoginId,$agencyLoginPwd,$agencyStartDt,$agencyEndDt,$agencyPrefix,$agencyMaxNum,$agencyTelphone,$agencyChannelType)
	{
		$sql="insert into PromoterAgency (Name,LoginName,LoginPwd,Telphone,Code,BeginDt,EndDt,MaxPromoterNum,PromoterPrefixName,CreateDt,ChannelType,IsFrozen) values (?,?,?,?,?,?,?,?,?,NOW(),?,0)";
		$params=array($agencyName,$agencyLoginId,$agencyLoginPwd,$agencyTelphone,$agencyCode,$agencyStartDt,$agencyEndDt,$agencyMaxNum,$agencyPrefix,$agencyChannelType);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	public function getAgencyIdByLoginName($agencyLoginId)
	{
		$sql="select * from PromoterAgency where LoginName=?";
		$params=array($agencyLoginId);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0]["AgencyId"];
		}
		return "";
	}
	public function updateAgencyPromoter($agencyName,$agencyCode,$agencyLoginId,$agencyLoginPwd,$agencyStartDt,$agencyEndDt,$agencyPrefix,$agencyMaxNum,$agencyTelphone,$agencyId)
	{
		if(false==empty($agencyLoginPwd)){
			$sql="update PromoterAgency set Name=?,LoginName=?,LoginPwd=?,Telphone=?,Code=?,BeginDt=?,EndDt=?,MaxPromoterNum=?,PromoterPrefixName=?,UpdateDt=NOW() where AgencyId=? ";
			$params=array($agencyName,$agencyLoginId,$agencyLoginPwd,$agencyTelphone,$agencyCode,$agencyStartDt,$agencyEndDt,$agencyMaxNum,$agencyPrefix,$agencyId);
		}else{
			$sql="update PromoterAgency set Name=?,LoginName=?,Telphone=?,Code=?,BeginDt=?,EndDt=?,MaxPromoterNum=?,PromoterPrefixName=?,UpdateDt=NOW() where AgencyId=? ";
			$params=array($agencyName,$agencyLoginId,$agencyTelphone,$agencyCode,$agencyStartDt,$agencyEndDt,$agencyMaxNum,$agencyPrefix,$agencyId);				
		}
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}	
	public function getAgencyAmount($agencyId)
	{
		$sql="select sum(GameAmount) as GameAmount,sum(Amount) as NetAmount,sum(TaxFee) as TaxFee from PayTransaction where TransactType=1 and PromoterId in (select PromoterId from AgencyPrompterPlay where AgencyId=? ) ";
		$params=array($agencyId);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0];
		}
		return array("GameAmount" =>"0.00","NetAmount" => "0.00","TaxFee"=>"0.00");
	}
	public function getAgencyPromoterById($agencyId)
	{
		$sql="select * from  PromoterAgency where AgencyId = ?";
		$params=array($agencyId);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0];
		}
		return array();
	}
	public function getPromoterListByPhoneList($phoneList)
	{
		$sql="select * from Promoter where PhoneNo in (".$phoneList.")";
		$params=array();
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info;
		}
		return array();
	}
	public function insertAgencyPrompterPlay($agencyId,$promoterId)
	{
		$sql="insert into AgencyPrompterPlay (AgencyId,PromoterId,CreateDt) values (?,?,NOW()) ";
		$params=array($agencyId,$promoterId);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	public function getAgencyPromoterPhoneInfoById($agencyId)
	{
		$sql="select b.PhoneNo,a.CreateDt,a.PromoterId from AgencyPrompterPlay a left join Promoter b on b.PromoterId=a.PromoterId where a.AgencyId=?";
		$params=array($agencyId);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info;
		}
		return array();
	}
	public function deleteAgencyPrompterPlay($agencyId)
	{
		$sql="delete from AgencyPrompterPlay where AgencyId=?";
		$params=array($agencyId);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	public function updateAgencyPwd($loginPwd,$agencyId)
	{
		$sql="update PromoterAgency set LoginPwd=?,UpdateDt=NOW() where AgencyId=?";
		$params=array($loginPwd,$agencyId);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	public function updateAgencyLoginState($IsFrozen, $agencyId)
	{
		$sql="update PromoterAgency set IsFrozen=?,UpdateDt=NOW() where AgencyId=?";
		$params=array($IsFrozen,$agencyId);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);		
	}
	public function updateAgencyPromoterCount($promoterCount,$agencyId)
	{
		$sql="update PromoterAgency set MaxPromoterNum=?,UpdateDt=NOW() where AgencyId=?";
		$params=array($promoterCount,$agencyId);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);		
	}
	public function getPromoterInfoCount($startDt, $endDt,$promoterPhone,$Channel)
	{
		$sql="select count(1) as num from Promoter where 1=1  and PhoneNo like '1%' ";
		$params=array();
		if(empty($promoterPhone)==false){
			$sql.=" and PhoneNo like ?";
			$params[]="%".$promoterPhone."%";
		}
		if(empty($startDt)==false){
			$sql.=" and CreateDt >= ?";
			$params[]=$startDt;
		}
		if(empty($endDt)==false){
			$sql.=" and CreateDt <= ?";
			$params[]=$endDt;
		}
		if(empty($Channel)==false){
			$sql.=" and Channel = ?";
			$params[]=$Channel;
		}
		
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0]["num"];
		}
		return 0;
	}
	
	public function getPromoterRegSrcCount($startDt, $endDt,$promoterPhone,$promoterSrc)
	{
		$sql="select count(1) as num from Promoter where 1=1 and PhoneNo like '1%' ";
		$params=array();
		if(empty($promoterPhone)==false){
			$sql.=" and PhoneNo like ?";
			$params[]="%".$promoterPhone."%";
		}
		if(empty($startDt)==false){
			$sql.=" and CreateDt >= ?";
			$params[]=sprintf("%s 00:00:00",$startDt);
		}
		if(empty($endDt)==false){
			$sql.=" and CreateDt <= ?";
			$params[]=sprintf("%s 23:59:59",$endDt);
		}
		if($promoterSrc!="ALL"){
			if(empty($promoterSrc)){
				$sql.=" and (Channel='' or  Channel is null)";
			}else{
				$sql.=" and Channel = ? ";
				$params[]=$promoterSrc;
			}
		}
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0]["num"];
		}
		return 0;
	}
	public function getPromoterRegSrc($startDt, $endDt,$promoterPhone,$promoterSrc,$offSet,$page_size)
	{
		$sql="select * from Promoter where 1=1 and PhoneNo like '1%' ";
		$params=array();
		if(empty($promoterPhone)==false){
			$sql.=" and PhoneNo like ?";
			$params[]="%".$promoterPhone."%";
		}
		if(empty($startDt)==false){
			$sql.=" and CreateDt >= ?";
			$params[]=sprintf("%s 00:00:00",$startDt);
		}
		if(empty($endDt)==false){
			$sql.=" and CreateDt <= ?";
			$params[]=sprintf("%s 23:59:59",$endDt);
		}
		if($promoterSrc!="ALL"){
			if(empty($promoterSrc)){
				$sql.=" and (Channel='' or  Channel is null)";
			}else{
				$sql.=" and Channel = ? ";
				$params[]=$promoterSrc;
			}
		}
		if($offSet>=0){
			$sql=$sql." limit $offSet,$page_size";
		}
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info;
		}
		return array();
	}
	
	
	public function getPromoterInfo($startDt, $endDt,$promoterPhone,$Channel,$offSet,$page_size)
	{
		$sql="select * from Promoter where 1=1 and PhoneNo like '1%' ";
		$params=array();
		if(empty($promoterPhone)==false){
			$sql.=" and PhoneNo like ?";
			$params[]="%".$promoterPhone."%";
		}
		if(empty($startDt)==false){
			$sql.=" and CreateDt >= ?";
			$params[]=$startDt;
		}
		if(empty($endDt)==false){
			$sql.=" and CreateDt <= ?";
			$params[]=$endDt;
		}
		if(empty($Channel)==false){
			$sql.=" and Channel = ?";
			$params[]=$Channel;
		}
		
		if($offSet>=0){
			$sql=$sql." limit $offSet,$page_size";
		}
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info;
		}
		return array();
	}
	public function getAppNavigationDefs()
	{
		$sql="select * from AppNavigationDef order by PositionIndex asc";
		$params=array();
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info;
		}
		return array();		
	}
	public function insertAppNavigationDef($CategoryId,$FileId,$PositionIndex,$Games)
	{
		$sql="insert into AppNavigationDef (FileId,CategoryId,PositionIndex,Games,CreateDt,UpdateDt) values (?,?,?,?,NOW(),NOW()) ";
		$params=array($FileId,$CategoryId,$PositionIndex,$Games);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	public function getAppNavigationDefById($recId)
	{
		$sql="select * from AppNavigationDef where RecId=?";
		$params=array($recId);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0];
		}
		return array();
	}
	public function delAppNavigationDefById($recId)
	{
		$sql="delete from  AppNavigationDef where RecId=?";
		$params=array($recId);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	public function updateAppNavigationDefById($CategoryId,$FileId,$PositionIndex,$Games,$recId)
	{
		$sql="update AppNavigationDef set FileId=?,CategoryId=?,PositionIndex=?,Games=?,UpdateDt=NOW() where RecId=?";
		$params=array($FileId,$CategoryId,$PositionIndex,$Games,$recId);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	public function getAppEntryDate($appId)
	{
		$sql="select * from AppVersion where AppId=? order by AppId asc limit 1";
		$params=array($appId);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0]["CreateDt"];
		}
		return "";
	}
	public function getAppProfitRange($appId,$startDate,$endDate)
	{
		$sql="select sum(GameAmount) as GameAmount,left(TransactDt,10) as SumDt from PayTransaction where AppId=? and TransactDt>=? and TransactDt<? group by SumDt";
		$params=array($appId,$startDate,$endDate);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info;
		}
		return array();
	}
	public function getAppPromoterPlayCount($AppId="",$startDt="",$endDt="",$phoneNo="",$promoterPhone="")
	{
		$sql="select count(1) as PlayCount
				from ViewPlayerAppFirstLogin a
				left join App b on b.AppId=a.AppId
				left join Player c on c.`Sdid`=a.`Sdid` and c.`CompanyCode`=a.`CompanyCode`
				left join Promoter d on d.`PromoterId`=a.`PromoterId` where 1=1 ";
		$params=array();
		if(empty($startDt)==false){
			$sql.=" and a.loginDt>=?";
			$params[]=$startDt;
		}
		if(empty($endDt)==false){
			$sql.=" and a.loginDt<=?";
			$params[]=$endDt;
		}
		if(empty($phoneNo)==false){
			$sql.=" and c.PhoneNo like ?";
			$params[]="%".$phoneNo."%";
		}
		if(empty($promoterPhone)==false){
			$sql.=" and d.PhoneNo like ?";
			$params[]="%".$promoterPhone."%";
		}
		if(empty($AppId)==false){
			$sql.=" and a.AppId=?";
			$params[]=$AppId;
		}		
		
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0]["PlayCount"];
		}
		return 0;
		
		
	}
	public function getAppPromoterPlayCountNoUsing($AppId,$startDt="",$endDt="",$phoneNo="",$promoterPhone="")
	{
		$sql="select count(1) as PlayCount
				from AppPromoterPlay a
				left join Player b on b.PlayerId=a.PlayerId
				left join PromotePlay c on c.CreateDt=a.CreateDt and c.PlayerId=a.PlayerId
				left join Promoter d on d.PromoterId=c.PromoterId  where a.AppId=? ";
		$params=array($AppId);
		if(empty($startDt)==false){
			$sql.=" and a.CreateDt>=?";
			$params[]=$startDt;
		}
		if(empty($endDt)==false){
			$sql.=" and a.CreateDt<=?";
			$params[]=$endDt;
		}
		if(empty($phoneNo)==false){
			$sql.=" and b.PhoneNo like ?";
			$params[]="%".$phoneNo."%";
		}
		if(empty($promoterPhone)==false){
			$sql.=" and d.PhoneNo like ?";
			$params[]="%".$promoterPhone."%";
		}
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0]["PlayCount"];
		}
		return 0;		
	}
	public function getAppPromoterPlay($AppId="",$startDt="",$endDt="",$phoneNo="",$promoterPhone="",$offSet=0,$page_size=10)
	{
		$sql="select a.loginDt as CreateDt,c.PhoneNo as PlayPhone,d.PhoneNo as PromoterPhone,a.CompanyCode,b.AppName,c.DisplayId 
 				from ViewPlayerAppFirstLogin a
				left join App b on b.AppId=a.AppId
				left join Player c on c.Sdid=a.Sdid and c.CompanyCode=a.CompanyCode
				left join Promoter d on d.PromoterId=a.PromoterId where 1=1 ";
		
		$params=array();
		if(empty($startDt)==false){
			$sql.=" and a.loginDt>=?";
			$params[]=$startDt;
		}
		if(empty($endDt)==false){
			$sql.=" and a.loginDt<=?";
			$params[]=$endDt;
		}
		if(empty($phoneNo)==false){
			$sql.=" and c.PhoneNo like ?";
			$params[]="%".$phoneNo."%";
		}
		if(empty($promoterPhone)==false){
			$sql.=" and d.PhoneNo like ?";
			$params[]="%".$promoterPhone."%";
		}
		if(empty($AppId)==false){
			$sql.=" and a.AppId=?";
			$params[]=$AppId;
		}
		
		$sql=$sql." order by a.loginDt desc ";
		if($offSet>=0) {
			$sql=$sql." limit $offSet,$page_size";
		}
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info;
		}
		return array();		
	}
	
	public function getAppPromoterPlayNoUsing($AppId,$startDt="",$endDt="",$phoneNo="",$promoterPhone="",$offSet=0,$page_size=10)
	{
		$sql="select a.CreateDt,b.PhoneNo as PlayPhone,d.PhoneNo as PromoterPhone 
				from AppPromoterPlay a 
				left join Player b on b.PlayerId=a.PlayerId 
				left join PromotePlay c on c.CreateDt=a.CreateDt and c.PlayerId=a.PlayerId 
				left join Promoter d on d.PromoterId=c.PromoterId  where a.AppId=? ";
		$params=array($AppId);
		if(empty($startDt)==false){
			$sql.=" and a.CreateDt>=?";
			$params[]=$startDt;
		}
		if(empty($endDt)==false){
			$sql.=" and a.CreateDt<=?";
			$params[]=$endDt;				
		}
		if(empty($phoneNo)==false){
			$sql.=" and b.PhoneNo like ?";
			$params[]="%".$phoneNo."%";
		}
		if(empty($promoterPhone)==false){
			$sql.=" and d.PhoneNo like ?";
			$params[]="%".$promoterPhone."%";
		}
		$sql=$sql." order by a.CreateDt desc ";
		if($offSet>=0) {
			$sql=$sql." limit $offSet,$page_size";
		}		
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info;
		}
		return array();
	}
	
	public function QueryInfo($PhoneNo)
	{
		$sql="select  a.PromoterId,a.PhoneNo,a.AliPayNo,a.AliPayName,a.ClientType,a.ClientAppVersion,a.Channel,b.LoginDt,c.LoginIp,c.PhoneType,c.DeviceId,c.Memo
				from Promoter   a
				left join  PromoterLastLogin  b on b.PromoterId=a.PromoterId
				left join PromoterPhoneHistory c on c.PromoterId=a.PromoterId and c.LoginDt=b.LoginDt
				where a.PhoneNo = ?";
		$params=array($PhoneNo);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0];
		}
		return array();
	}
	
	public function QueryPublishCfg($CfgKey)
	{
		$sql="select * from PublishCfg where CfgKey=?";
		$params=array($CfgKey);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0];
		}
		return array();		
	}
	public function AddPublishCfg($CfgKey,$CfgValue)
	{
		$sql="insert PublishCfg (CfgKey,CfgValue,CreateDt,UpdateDt) values (?,?,NOW(),NOW()) ";
		$params=array($CfgKey,$CfgValue);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	public function UpdatePublishCfg($CfgKey,$CfgValue)
	{
		$sql="update PublishCfg set CfgValue=?,UpdateDt=NOW() where CfgKey=? ";
		$params=array($CfgValue,$CfgKey);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);		
	}
	public function AddPublishServerStat($ServerState)
	{
		$sql="insert into PublishServerStat (ServerState,CreateDt) values (?,NOW())";
		$params=array($ServerState);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);		
	}
	public function GetPublishiServerStatLastReport()
	{
		$sql="select * from  PublishServerStat  order by CreateDt desc limit 1";
		$params=array();
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0];
		}
		return array();
	}
	public function GetPayReturnByCheckingId($checkingId)
	{
		$sql="select * from PayReturn where CheckingID=?";
		$params=array($checkingId);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0];
		}
		return array();
	}
	
	public function FonzenByCheckingIdExist($checkingId)
	{
		$pdo=LunaPdo::GetInstance($this->_PDO_NODE_NAME);
		$pdo->beginTransaction();
		
		$sql="select * from PayReturn where CheckingID=? and Status=0";
		$params=array($checkingId);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);		
		if(isset($info) && is_array($info) && count($info)>0){
			$sql="update PayReturn set Status=1,UpdateDt=NOW() where CheckingID=?";
			if($pdo->exec_with_prepare($sql,$params)==1){
				$amount=$info[0]["Amount"];
				$sql="update Promoter set FrozenAmt=FrozenAmt+".$amount." where PromoterId=?";
				$params=array($info[0]["PromoterId"]);
				if($pdo->exec_with_prepare($sql,$params)==1){
					$pdo->commit();
				}else{
					$pdo->rollBack();
				}
			}else{
				$pdo->rollBack();
			}
			
		}else{
			$pdo->rollBack();
		}
	}
	
	public function FonzenByCheckingIdFirst($checkingId)
	{
		$pdo=LunaPdo::GetInstance($this->_PDO_NODE_NAME);
		$pdo->beginTransaction();
		$sql="select * from PayTransaction where CheckingID=?";
		$params=array($checkingId);
		$payInfo=$pdo->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($payInfo) && is_array($payInfo) && count($payInfo)>0){
			$Amount=$payInfo[0]["Amount"]+(isset($payInfo[0]["TaxFee"])?$payInfo[0]["TaxFee"]:0);
			$sql="insert PayReturn (PromoterId,CheckingID,AppId,GameAmount,TransactDt,Amount,PlayerId,Status,CreateDt,UpdateDt) values (?,?,?,?,?,?,?,1,NOW(),NOW()) ";
			$params=array($payInfo[0]["PromoterId"],$checkingId,$payInfo[0]["AppId"],$payInfo[0]["GameAmount"],$payInfo[0]["TransactDt"],$Amount,$payInfo[0]["PlayerId"]);
			if($pdo->exec_with_prepare($sql,$params)==1){
				$sql="update Promoter set FrozenAmt=FrozenAmt+".$Amount." where PromoterId=?";
				$params=array($payInfo[0]["PromoterId"]);
				if($pdo->exec_with_prepare($sql,$params)==1){
					$pdo->commit();
				}else{
					$pdo->rollBack();
				}
			}else{
				$pdo->rollBack();
			}			
		}else{
			$pdo->rollBack();
		}
	}
	
	public function PayReturnByCheckingIdFirst($checkingId)
	{
		$pdo=LunaPdo::GetInstance($this->_PDO_NODE_NAME);
		$pdo->beginTransaction();
		$sql="select * from PayTransaction where CheckingID=?";
		$params=array($checkingId);
		$payInfo=$pdo->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($payInfo) && is_array($payInfo) && count($payInfo)>0){
			$Amount=$payInfo[0]["Amount"]+(isset($payInfo[0]["TaxFee"])?$payInfo[0]["TaxFee"]:0);
			$sql="insert PayReturn (PromoterId,CheckingID,AppId,GameAmount,TransactDt,Amount,PlayerId,Status,CreateDt,UpdateDt) values (?,?,?,?,?,?,?,2,NOW(),NOW()) ";
			$params=array($payInfo[0]["PromoterId"],$checkingId,$payInfo[0]["AppId"],$payInfo[0]["GameAmount"],$payInfo[0]["TransactDt"],$Amount,$payInfo[0]["PlayerId"]);
			if($pdo->exec_with_prepare($sql,$params)==1){
				$sql="update Promoter set Amount=Amount-".$Amount.",NetAmount=NetAmount-".$Amount.",IncomeSummary=IncomeSummary-".$Amount." where PromoterId=?";
				$params=array($payInfo[0]["PromoterId"]);
				if($pdo->exec_with_prepare($sql,$params)==1){
					$pdo->commit();
					return 1;
				}else{
					$pdo->rollBack();
					return 0;
				}
			}else{
				$pdo->rollBack();
				return 0;
			}
		}else{
			$pdo->rollBack();
			return 0;
		}		
	}
	public function PayReturnByCheckingIdExist($checkingId)
	{
		$pdo=LunaPdo::GetInstance($this->_PDO_NODE_NAME);
		$pdo->beginTransaction();
		$sql="select * from PayReturn where CheckingID=? and Status<>2";
		$params=array($checkingId);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			$sql="update PayReturn set Status=2,UpdateDt=NOW() where CheckingID=? ";
			if($pdo->exec_with_prepare($sql,$params)==1){		//更新状态
				$amount=$info[0]["Amount"];
				if($info[0]["Status"]==1){			//已冻结
					$sql="update Promoter set Amount=Amount-".$amount.",NetAmount=NetAmount-".$amount.",FrozenAmt=FrozenAmt-".$amount.",IncomeSummary=IncomeSummary-".$amount. " where PromoterId=?";
					
				}else {		//未冻结过
					$sql="update Promoter set Amount=Amount-".$amount.",NetAmount=NetAmount-".$amount.",IncomeSummary=IncomeSummary-".$amount." where PromoterId=?";
				}
				$params=array($info[0]["PromoterId"]);
				if($pdo->exec_with_prepare($sql,$params)==1){
					$pdo->commit();
					return 1;
				}else{
					$pdo->rollBack();
					return 0;
				}				
			}else{
				$pdo->rollBack();
				return 0;
			}
		}
		else{
			$pdo->rollBack();
			return 0;
		}
	}
	
	public function UnFronzeByCheckingId($checkingId)
	{
		$pdo=LunaPdo::GetInstance($this->_PDO_NODE_NAME);
		$pdo->beginTransaction();
		
		$sql="update PayReturn set Status=0,UpdateDt=NOW() where CheckingID=?";
		$params=array($checkingId);
		if($pdo->exec_with_prepare($sql,$params)==1){
			$sql="select * from PayReturn where CheckingID=?";
			$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
			if(isset($info) && is_array($info) && count($info)>0){
				$amount=$info[0]["Amount"];
				$sql="update Promoter set FrozenAmt=FrozenAmt-".$amount." where  PromoterId=?";
				$params=array($info[0]["PromoterId"]);
				if($pdo->exec_with_prepare($sql,$params)==1){
					$pdo->commit();
				}else{
					$pdo->rollBack();
				}				
			}else{
				$pdo->rollBack();
			}
		}else{
			$pdo->rollBack();	
		}		
	}

	public function getNotifyMessageFields($checkingId)
	{
		$sql="select a.PromoterId,a.TransactDt,a.GameAmount,a.Amount,b.PhoneNo,c.AppName,d.PhoneNo as PromoterPhone,d.ClientType    
				from PayReturn a
				left join  Player b on b.PlayerId=a.PlayerId
				left join  App c on c.AppId=a.AppId 
				left join  Promoter d on d.PromoterId=a.PromoterId
				where a.CheckingID=? ";
		$params=array($checkingId);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0];
		}
		return array();
	}
	
	public function getAppInfos()
	{
		$sql="select a.AppId,a.AppName,b.PromoterProrate 
				from App  a
				left join  (
					select AppId,PromoterProrate  
						from ProrateHistory
						where startDt<=NOW() and endDt>=NOW() and IsDelete=0
				) b on b.AppId=a.AppId
				where (a.AppStatus=0 or a.AppStatus=1) and b.PromoterProrate>0 ";
		
		$params=array();
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info;
		}
		return array();
	}
	
	public function insertQ_AppPromotionEvn($AppId,$picId,$envName,$evnType,$evnStart,$envEnd,$joinType,$evnContent,$evnRandMin,$evnRandMax,$evnOrder,$evnQty,$prorate,$advProrate,$status,$aclList,$baseProrate,$evnIntro)
	{
		$sql="insert into Q_AppPromotionEvn (
				AppId,FileId,EvnName,EvnType,EvnStart,EvnEnd,EvnJoinType,EvnContent,EvnRandMin,EvnRandMax,EvnOrder,EvnQty,Prorate,EvnAdvProrate,Status,AclList,
				EvnJoinQty,EvnJoinRandQty,CreateDt,UpdateDt,BaseProrate,EvnIntro 
				) values (
				?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,
				0,0,NOW(),NOW(),?,? 
				) ";
		$params=array(
					$AppId,$picId,$envName,$evnType,$evnStart,$envEnd,$joinType,$evnContent,$evnRandMin,$evnRandMax,$evnOrder,$evnQty,$prorate,$advProrate,$status,$aclList,$baseProrate,$evnIntro
		);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	
	public function updateQ_AppPromotionEvn($AppId,$picId,$envName,$evnType,$evnStart,$envEnd,$joinType,$evnContent,$evnRandMin,$evnRandMax,$evnOrder,$evnQty,$prorate,$advProrate,$status,$aclList,$idx,$baseProrate,$evnIntro)
	{
		$sql="update Q_AppPromotionEvn set AppId=?,FileId=?,EvnName=?,EvnType=?,EvnStart=?,EvnEnd=?,EvnJoinType=?,EvnContent=?,EvnRandMin=?,EvnRandMax=?,
				EvnOrder=?,EvnQty=?,Prorate=?,EvnAdvProrate=?,Status=?,AclList=?,UpdateDt=NOW(),BaseProrate=?,EvnIntro=?   
			where IDX=?";
		$params=array(
				$AppId,$picId,$envName,$evnType,$evnStart,$envEnd,$joinType,$evnContent,$evnRandMin,$evnRandMax,$evnOrder,$evnQty,$prorate,$advProrate,$status,$aclList,$baseProrate,$evnIntro,$idx
		);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);		
	} 
	
	
	public function isConfictedNew($AppId,$startDt,$endDt)
	{
		$sql="select * from Q_AppPromotionEvn where Status in (1,2) and  AppId=? and (
				(EvnStart<=? and EvnEnd>=?) or (EvnStart<=? and EvnEnd>=?) or(EvnStart>? and EvnEnd<?) ) ";
		$params=array($AppId,$startDt,$startDt,$endDt,$endDt,$startDt,$endDt);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return true;
		}
		return false;
	}
	
	public function isConfictedNewExcludeSelf($AppId,$startDt,$endDt,$idx)
	{
		$sql="select * from Q_AppPromotionEvn where Status in (1,2) and IDX<>? and  AppId=? and (
				(EvnStart<=? and EvnEnd>=?) or (EvnStart<=? and EvnEnd>=?) or(EvnStart>? and EvnEnd<?) ) ";
		$params=array($idx,$AppId,$startDt,$startDt,$endDt,$endDt,$startDt,$endDt);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return true;
		}
		return false;		
	}
	
	public function getQ_AppPromotionEvnList($startDt="",$endDt="",$appName="",$evnName="",$evnType="")
	{
		$sql="select a.IDX,b.AppName,a.EvnStart,a.EvnEnd,a.EvnName,a.EvnOrder,a.EvnType,a.EvnQty,a.Prorate,a.EvnAdvProrate,a.EvnJoinQty,a.EvnJoinRandQty,a.Status 
				from Q_AppPromotionEvn a
				left join App b on b.AppId=a.AppId 
				where 1=1";
		$params=array();
		if(empty($startDt)==false){
			$sql.=" and a.EvnStart=?";
			$params[]=sprintf("%s 00:00:00",$startDt);	
		}
		if(empty($endDt)==false){
			$sql.=" and a.EvnEnd=?";
			$params[]=sprintf("%s 23:59:59",$endDt);
		}
		if(empty($appName)==false){
			$sql.=" and b.AppName like ?";
			$params[]="%".$appName."%";
		}
		if(empty($evnName)==false){
			$sql.=" and a.EvnName like ?";
			$params[]="%".$evnName."%";
		}
		if(empty($evnType)==false){
			$sql.=" and a.EvnType = ?";
			$params[]=$evnType;
		}
		$sql.=" order by a.EvnOrder asc ";
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
			if(isset($info) && is_array($info)){
			return $info;
		}
		return array();
	}
	
	public function getQ_AppPromotionEvnByIdx($idx)
	{
		$sql="select a.IDX,a.AppId,b.AppName,a.EvnStart,a.EvnEnd,a.EvnName,a.EvnOrder,a.EvnType,a.EvnQty,a.Prorate,a.EvnAdvProrate,a.EvnJoinQty,a.EvnJoinRandQty,a.Status,a.AclList,a.FileId,a.EvnRandMin,a.EvnRandMax,a.EvnContent,a.EvnJoinType,a.BaseProrate,a.EvnIntro    
				from Q_AppPromotionEvn a
				left join App b on b.AppId=a.AppId 
				where a.IDX=?";
		$params=array($idx);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0];
		}
		return array();
	}
	
	public function getQ_AppPromotionEvnJoinerCount($idx,$phoneNo)
	{
		$sql="select count(1) as num from Q_EvnJoinList where EvnIdx=? ";
		$params=array($idx);
		
		if(empty($phoneNo)==false){
			$sql.=" and PhoneNo like ?";
			$params[]="%".$phoneNo."%";
		}
		$joinerList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($joinerList) && is_array($joinerList) && count($joinerList)>0){
			return $joinerList[0]["num"];
		}
		return 0;		
	}
	
	public function getQ_AppPromotionEvnJoiner($idx,$phoneNo,$offSet,$page_size)
	{
		$sql="select * from Q_EvnJoinList where EvnIdx=? ";
		$params=array($idx);

		if(empty($phoneNo)==false){
			$sql.=" and PhoneNo like ?";
			$params[]="%".$phoneNo."%";
		}
		
		$sql.=" order by CreateDt desc ";
		if($offSet>=0) {
			$sql=$sql." limit $offSet,$page_size";
		}
		$joinerList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($joinerList) && is_array($joinerList) && count($joinerList)>0){
			return $joinerList;
		}
		return array();
	}
	
	public function insertQ_AppPromotionGift($appId,$picId,$giftStatus,$openDt,$aclList,$Category,$giftOrder,$TagType1,$TagType2,$TagType3,$giftName,
					$TotalCount,$RestCount,$giftStart,$giftEnd,$giftContent,$giftGuide)
	{
		$sql="insert Q_AppPromotionGift (AppId,FileId,Name,Category,Content,Guide,TagType1,TagType2,TagType3,GiftOrder,Status,AclList,CreateDt,UpdateDt%s) 
				values (?,?,?,?,?,?,?,?,?,?,?,?,NOW(),NOW()%s)";
		
		$params=array($appId,$picId,$giftName,$Category,$giftContent,$giftGuide,$TagType1,$TagType2,$TagType3,
				$giftOrder,$giftStatus,$aclList);
		
		$optionalColumns="";
		$optionalVal="";
		if(empty($giftStart)==false){
			$optionalColumns=$optionalColumns.",StartDt";
			$optionalVal=$optionalVal.",?";
			$params[]=$giftStart;
		}
		if(empty($giftEnd)==false){
			$optionalColumns=$optionalColumns.",EndDt";
			$optionalVal=$optionalVal.",?";
			$params[]=$giftEnd;
		}
		if(empty($openDt)==false){
			$optionalColumns=$optionalColumns.",OpenDt";
			$optionalVal=$optionalVal.",?";
			$params[]=$openDt;
		}
		if(empty($TotalCount)==false && $TotalCount!="null"){
			$optionalColumns=$optionalColumns.",TotalCount";
			$optionalVal=$optionalVal.",?";
			$params[]=$TotalCount;
		}
		if(empty($RestCount)==false && $RestCount!="null"){
			$optionalColumns=$optionalColumns.",RestCount";
			$optionalVal=$optionalVal.",?";
			$params[]=$RestCount;
		}
		
		$sql=sprintf($sql,$optionalColumns,$optionalVal);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	
	public function updateQ_AppPromotionGift($appId,$picId,$giftStatus,$openDt,$aclList,$Category,$giftOrder,$TagType1,$TagType2,$TagType3,$giftName,
					$TotalCount,$RestCount,$giftStart,$giftEnd,$giftContent,$giftGuide,$idx)
	{
		$sql="update Q_AppPromotionGift set AppId=?,FileId=?,
				Name=?,Category=?,Content=?,
				Guide=?,TagType1=?,TagType2=?,TagType3=?,GiftOrder=?,Status=?,AclList=?,UpdateDt=NOW() ";
		
		$params=array($appId,$picId,$giftName,$Category,$giftContent,$giftGuide,$TagType1,$TagType2,$TagType3,
				$giftOrder,$giftStatus,$aclList);
		
		if(empty($giftStart)==false){
			$sql.=",StartDt=?";
			$params[]=$giftStart;
		}
		if(empty($giftEnd)==false){
			$sql.=",EndDt=?";
			$params[]=$giftEnd;
		}
		if(empty($openDt)==false){
			$sql.=",OpenDt=?";
			$params[]=$openDt;
		}
		
		if(empty($TotalCount) || $TotalCount=="null"){
			$sql.=",TotalCount=null";
		}else{
			$sql.=",TotalCount=?";
			$params[]=$TotalCount;
		}
		if(empty($RestCount) || $RestCount=="null"){
			$sql.=",RestCount=null";
		}else{
			$sql.=",RestCount=?";
			$params[]=$RestCount;
		}
		
		$sql.=" where IDX=? ";
		$params[]=$idx;
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
		
	
	public function getQ_AppPromotionGiftListCount($appName,$status,$giftIdx,$giftName)
	{
		$sql="select count(1) as num from Q_AppPromotionGift a left join App b on b.AppId=a.AppId where 1=1 ";
		$params=array();
		
		if(empty($appName)==false){
			$sql.=" and b.AppName like ?";
			$params[]="%".$appName."%";
		}
		
		if(empty($status)==false){
			$sql.=" and a.Status=?";
			$params[]=$status;
		}

		if(empty($giftIdx)==false){
			$sql.=" and a.IDX like ?";
			$params[]="%".$giftIdx."%";
		}
		
		if(empty($giftName)==false){
			$sql.=" and a.Name like ?";
			$params[]="%".$giftName."%";
		}
		$giftList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($giftList) && is_array($giftList) && count($giftList)>0){
			return $giftList[0]["num"];
		}
		return 0;
	}
	public function getQ_AppPromotionGiftList($appName,$status,$giftIdx,$giftName,$offSet,$page_size)
	{
		$sql="select a.IDX,a.Name,a.Status,a.GiftOrder,a.Category,a.TagType1,a.TagType2,a.TagType3,
    			b.AppName,c.TotalCount,d.RestCount 
			from Q_AppPromotionGift a 
			left join App b on b.AppId=a.AppId 
			left join (
						select count(1) as TotalCount,GiftIdx from Q_AppGiftCode 
						group by GiftIdx
				) c  on c.GiftIdx=a.IDX
			left join (
						select count(1) as RestCount,GiftIdx from Q_AppGiftCode where PhoneNo is null or PhoneNo=''
						group by GiftIdx
				)  d  on d.GiftIdx=a.IDX
			where 1=1 ";
		
		$params=array();
		
		if(empty($appName)==false){
			$sql.=" and b.AppName like ?";
			$params[]="%".$appName."%";
		}
		
		if(empty($status)==false){
			$sql.=" and a.Status=?";
			$params[]=$status;
		}
		
		if(empty($giftIdx)==false){
			$sql.=" and a.IDX like ?";
			$params[]="%".$giftIdx."%";
		}
		
		if(empty($giftName)==false){
			$sql.=" and a.Name like ?";
			$params[]="%".$giftName."%";
		}
		$sql.=" order by a.Category asc,a.GiftOrder asc,a.CreateDt desc ";
		if($offSet>=0) {
			$sql=$sql." limit $offSet,$page_size";
		}		
		
		$giftList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($giftList) && is_array($giftList) && count($giftList)>0){
			return $giftList;
		}
		return array();
	}
	
	public function getQ_AppPromotionGiftByIdx($idx)
	{
		$sql="select a.FileId,a.AppId,a.Name,a.TotalCount,a.RestCount,a.StartDt,a.EndDt,a.Category,
					a.Content,a.Guide,a.TagType1,a.TagType2,a.TagType3,a.GiftOrder,a.Status,a.OpenDt,a.AclList,a.IDX,
					b.AppName 
				from Q_AppPromotionGift a 
				left join App b on b.AppId=a.AppId
				where a.IDX=?
				";
		$params=array($idx);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0];
		}
		return array();
	}
	
	public function insertQ_AppGiftCode($idx,$giftCode)
	{
		$sql="insert into Q_AppGiftCode (GiftIdx,Code,CreateDt) values (?,?,NOW())";
		$params=array($idx,$giftCode);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	
	public function getGiftTotalCountByGiftIdx($idx)
	{
		$sql="select count(1) as num from Q_AppGiftCode where GiftIdx=? ";
		$params=array($idx);
		$giftList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($giftList) && is_array($giftList) && count($giftList)>0){
			return $giftList[0]["num"];
		}
		return 0;
	}
	public function getGiftRestCountByGiftIdx($idx)
	{
		$sql="select count(1) as num from Q_AppGiftCode where GiftIdx=? and (PhoneNo is null or PhoneNo='') ";
		$params=array($idx);
		$giftList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($giftList) && is_array($giftList) && count($giftList)>0){
			return $giftList[0]["num"];
		}
		return 0;
	}
	
	public function getGiftCodeListByGiftIdx($giftIdx)
	{
		$sql="select * from Q_AppGiftCode where GiftIdx=? ";
		$params=array($giftIdx);
		$giftList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($giftList) && is_array($giftList) && count($giftList)>0){
			return $giftList;
		}
		return array();
	}
	
	public function getPayApplyByIdx($idx)
	{
		$sql="select * from Q_PlayerPayApply where IDX=?";
		$params=array($idx);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0];
		}
		return array();		
	}
	
	public function updateEnvelopeInfoByIdx($envelopeId,$idx,$ourOrderId)
	{
		$sql="update Q_PlayerPayApply set State=2,EnvelopeId=?,OurOrderId=?,ApprovalDt=NOW() where IDX=?";
		$params=array($envelopeId,$ourOrderId,$idx);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	
	public function rollbackRedEnvelopeByIdx($idx,$outOfEnvelopePeriod=false)
	{
		$pdo=LunaPdo::GetInstance($this->_PDO_NODE_NAME);
		$pdo->beginTransaction();
	
		$sql="select * from Q_PlayerPayApply where IDX=? for update";
		$params=array($idx);
		$info=$pdo->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			$sql="update Q_MicroPlayer set NetAmount=NetAmount+?,UpdateDt=NOW()  where OpenId=?";
			$params=array($info[0]["Amount"],$info[0]["OpenId"]);
			if($pdo->exec_with_prepare($sql,$params)>0){
				$sql="update Q_PlayerPayApply set State=?,ApprovalDt=NOW() where IDX=?";
				$params=array( ($outOfEnvelopePeriod?"3":"1") ,$idx);
				if($pdo->exec_with_prepare($sql,$params)>0){
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

	public function rollbackRedEnvelopeByEnvelopId($envelopeId)
	{
		$pdo=LunaPdo::GetInstance($this->_PDO_NODE_NAME);
		$pdo->beginTransaction();
		
		$sql="select * from Q_PlayerPayApply where EnvelopeId=? FOR UPDATE";
		$params=array($envelopeId);
		
		$info=$pdo->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		
		if(isset($info) && is_array($info) && count($info)>0 && $info[0]["State"]=="2"){
			$sql="update Q_MicroPlayer set NetAmount=NetAmount+?,UpdateDt=NOW()  where OpenId=?";
			$params=array($info[0]["Amount"],$info[0]["OpenId"]);
			
			if($pdo->exec_with_prepare($sql,$params)>0){
				$sql="update Q_PlayerPayApply set State=3,ApprovalDt=NOW() where IDX=?";
				$params=array($info[0]["IDX"]);
				
				if($pdo->exec_with_prepare($sql,$params)>0){
					$pdo->commit();
					return 1;
				}else{
					$pdo->rollBack();
				}
			}else{
				$pdo->rollBack();
			}
		}else{
			$pdo->rollBack();
		}
		return 0;
	}
	
	public function getEnvListByPhoneNo($phone)
	{
		$sql="select  a.CreateDt,a.EvnIdx,b.EvnStart,b.EvnEnd,b.EvnType,b.EvnName,c.AppName 
    		  from Q_EvnJoinList a 
    				left join Q_AppPromotionEvn b on b.IDX=a.EvnIdx 
    				left join App c on c.AppId=b.AppId 
    		where a.PhoneNo= ? 
    		order by b.EvnOrder asc";
		
		$params=array($phone);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		
		if(isset($info) && is_array($info) && count($info)>0){
			return $info;
		}
		return array();
	}
	
	public function getGiftByPhoneNo($phone)
	{
		$sql="select a.Code,a.UpdateDt,b.Name,c.AppName
    			from Q_AppGiftCode a 
    				left join Q_AppPromotionGift b on b.IDX=a.GiftIdx 
    				left join App c on c.AppId=b.AppId 
    			where a.PhoneNo=?
				order by a.UpdateDt desc";
		
		$params=array($phone);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		
		if(isset($info) && is_array($info) && count($info)>0){
			return $info;
		}
		return array();
	}
	
	public function updateMicroPayState($payState,$idx)
	{
		$sql="update Q_MicroPlayer set PayState=?,UpdateDt=NOW() where Idx=?";
		$params=array($payState,$idx);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}


	/**
	 * 获取礼券列表数量
	 * @param  [type] $appName  [description]
	 * @param  [type] $status   [description]
	 * @param  [type] $giftIdx  [description]
	 * @param  [type] $giftName [description]
	 * @return [type]           [description]
	 */
	public function getQ_AppPromotionCouponListCount($appId,$type,$status,$CouponIdx,$IsExpired)
	{
		$sql="
			SELECT count(*) as num FROM Q_AppPromotionCoupon a  
				LEFT JOIN (SELECT CouponIdx,COUNT(*)  AS GrantCount FROM Q_CouponPlayer GROUP BY CouponIdx ) b ON a.IDX = b.CouponIdx
				LEFT JOIN (SELECT CouponIdx,COUNT(*)  AS DrawCount FROM Q_CouponPlayer WHERE PhoneNo IS NOT NULL AND PhoneNo != '' GROUP BY CouponIdx ) c ON a.IDX = c.CouponIdx
				LEFT JOIN (SELECT CouponIdx,COUNT(*)  AS CashCount FROM Q_CouponPlayer  WHERE PayIdx IS NOT NULL AND PayIdx != '' AND PhoneNo IS NOT NULL AND PhoneNo != ''  GROUP BY CouponIdx ) d ON a.IDX = d.CouponIdx
			where 1=1 
			";
		$params=array();
		
		if(empty($appId)==false){
			$sql.=" and (a.AppIds like ? or a.AppIds is NULL or a.AppIds = '' )";
			$params[]="%".$appId."%";
		}
		if(empty($type)==false){
			$sql.=" and a.Type=?";
			$params[]=$type;
		}		if(empty($status)==false){
			$sql.=" and a.Status=?";
			$params[]=$status;
		}
		if(empty($CouponIdx)==false){
			$sql.=" and a.IDX like ?";
			$params[]="%".$CouponIdx."%";
		}
		if(empty($IsExpired)==false){
			if($IsExpired == "1"){
				$sql.=" and a.EndDt >= NOW()";				
			}else if($IsExpired == "2"){
				$sql.=" and a.EndDt < NOW()";				
			}
		}
		$couponList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($couponList) && is_array($couponList) && count($couponList)>0){
			return $couponList[0]["num"];
		}
		return 0;
	}
	/**
	 * 获取礼券列表
	 * @param  [type] $appName   [description]
	 * @param  [type] $status    [description]
	 * @param  [type] $giftIdx   [description]
	 * @param  [type] $giftName  [description]
	 * @param  [type] $offSet    [description]
	 * @param  [type] $page_size [description]
	 * @return [type]            [description]
	 */
	public function getQ_AppPromotionCouponList($appId,$type,$status,$CouponIdx,$IsExpired,$offSet,$page_size)
	{
		$sql="
			SELECT * 	FROM Q_AppPromotionCoupon a  
				LEFT JOIN (SELECT CouponIdx,COUNT(*)  AS GrantCount FROM Q_CouponPlayer GROUP BY CouponIdx ) b ON a.IDX = b.CouponIdx
				LEFT JOIN (SELECT CouponIdx,COUNT(*)  AS DrawCount FROM Q_CouponPlayer WHERE PhoneNo IS NOT NULL AND PhoneNo != '' GROUP BY CouponIdx ) c ON a.IDX = c.CouponIdx
				LEFT JOIN (SELECT CouponIdx,COUNT(*)  AS CashCount FROM Q_CouponPlayer  WHERE PayIdx IS NOT NULL AND PayIdx != '' AND PhoneNo IS NOT NULL AND PhoneNo != ''  GROUP BY CouponIdx ) d ON a.IDX = d.CouponIdx
			where 1=1 
			";
		
		$params=array();
		
		if(empty($appId)==false){
			$sql.=" and (a.AppIds like ? or a.AppIds is NULL  or a.AppIds = '')";
			$params[]="%".$appId."%";
		}
		if(empty($type)==false){
			$sql.=" and a.Type=?";
			$params[]=$type;
		}
		if(empty($status)==false){
			$sql.=" and a.Status=?";
			$params[]=$status;
		}
		
		if(empty($CouponIdx)==false){
			$sql.=" and a.IDX like ?";
			$params[]="%".$CouponIdx."%";
		}
		
		if(empty($IsExpired)==false){
			if($IsExpired == "1"){
				$sql.=" and a.EndDt >= NOW()";				
			}else if($IsExpired == "2"){
				$sql.=" and a.EndDt < NOW()";				
			}
		}
		$sql.=" order by a.CreateDt desc ";
		if($offSet>=0) {
			$sql=$sql." limit $offSet,$page_size";
		}		
		
		$Coupon_list=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($Coupon_list) && is_array($Coupon_list) && count($Coupon_list)>0){
			return $Coupon_list;
		}
		return array();
	}


	public function getQ_AppCouponGrantListCount($GrantStartDt,$GrantEndDt,$PhoneNo,$CouponIdx)
	{
		$sql="SELECT count(*) as num FROM Q_CouponPlayer a 
			LEFT JOIN Q_MicroPhonePayReturn b ON a.PayIdx = b.IDX 
			LEFT JOIN App c ON b.AppId = c.AppId 
			where 1=1 ";
		
		$params=array();
		
		if(empty($GrantStartDt)==false){
			$sql.=" and (a.CreateDt > ? )";
			$params[]= $GrantStartDt;
		}
		if(empty($GrantEndDt)==false){
			$sql.=" and (a.CreateDt < ? )";
			$params[]= $GrantEndDt;
		}
		if(empty($PhoneNo)==false){
			$sql.=" and a.PhoneNo in (".$PhoneNo.")";
		}
		
		if(empty($CouponIdx)==false){
			$sql.=" and a.CouponIdx = ?";
			// $params[]="%".$CouponIdx."%";
			$params[]= $CouponIdx;
		}
		$couponList=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($couponList) && is_array($couponList) && count($couponList)>0){
			return $couponList[0]["num"];
		}
		return 0;
	}

	public function getQ_AppCouponGrantList($GrantStartDt,$GrantEndDt,$PhoneNo,$CouponIdx,$offSet,$page_size)
	{
		$sql="SELECT a.*,b.AppId,c.AppName,b.GameAmount,b.CheckingID,b.TransactDt FROM Q_CouponPlayer a 
			LEFT JOIN Q_MicroPhonePayReturn b ON a.PayIdx = b.IDX 
			LEFT JOIN App c ON b.AppId = c.AppId 
			where 1=1 ";
		
		$params=array();
		
		if(empty($GrantStartDt)==false){
			$sql.=" and (a.CreateDt > ? )";
			$params[]= $GrantStartDt;
		}
		if(empty($GrantEndDt)==false){
			$sql.=" and (a.CreateDt < ? )";
			$params[]= $GrantEndDt;
		}
		if(empty($PhoneNo)==false){
			$sql.=" and a.PhoneNo in (".$PhoneNo.")";
		}
		
		if(empty($CouponIdx)==false){
			$sql.=" and a.CouponIdx = ?";
			// $params[]="%".$CouponIdx."%";
			$params[]= $CouponIdx;
		}
		$sql.=" order by a.CreateDt desc ,a.PhoneNo ";
		if($offSet>=0) {
			$sql=$sql." limit $offSet,$page_size";
		}		
		
		$Coupon_list=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($Coupon_list) && is_array($Coupon_list) && count($Coupon_list)>0){
			return $Coupon_list;
		}
		return array();
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
	 * 按ID获取礼券领取、兑现信息
	 * @param  [type] $idx [description]
	 * @return [type]      [description]
	 */
	public function getQ_AppCouponDrawByIdx($idx)
	{
		$sql="SELECT a.*,b.AppId,c.AppName FROM Q_CouponPlayer a 
			LEFT JOIN Q_MicroPhonePayReturn b ON a.PayIdx = b.IDX 
			LEFT JOIN App c ON b.AppId = c.AppId 
			WHERE a.CouponIdx = ? ";
		$params=array($idx);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info;
		}
		return array();
	}

	/**
	 * 新增礼券
	 * @param  [type] $appId          [description]
	 * @param  [type] $Status         [description]
	 * @param  [type] $aclList        [description]
	 * @param  [type] $Type           [description]
	 * @param  [type] $RecharegAmount [description]
	 * @param  [type] $ReturnAmount   [description]
	 * @param  [type] $PayStartDt     [description]
	 * @param  [type] $PayEndDt       [description]
	 * @param  [type] $Quantity       [description]
	 * @param  [type] $startDt        [description]
	 * @param  [type] $endDt          [description]
	 * @param  [type] $IsNewBind      [description]
	 * @param  [type] $BindStartDt    [description]
	 * @param  [type] $BindEndDt      [description]
	 * @return [type]                 [description]
	 */
	public function insertQ_AppPromotionCoupon($appId,$Status,$aclList,$Type,$RecharegAmount,$ReturnAmount,$PayStartDt,$PayEndDt,$Quantity,
					$StartDt,$EndDt,$IsNewBind,$BindStartDt,$BindEndDt,$MsgFirst,$MsgRemark,$CouponName)
	{
		$sql="insert Q_AppPromotionCoupon (AppIds,Status,AclList,Type,RecharegAmount,
			ReturnAmount,Quantity,
			IsNewBind,MsgFirst,MsgRemark,CouponName,
			CreateDt,UpdateDt%s) 
				values (?,?,?,?,?,?,?,?,?,?,?,NOW(),NOW()%s)";
		
		$params=array($appId,$Status,$aclList,$Type,$RecharegAmount,$ReturnAmount,$Quantity,$IsNewBind,$MsgFirst,$MsgRemark,$CouponName);
		
		$optionalColumns="";
		$optionalVal="";
		if(empty($PayStartDt)==false){
			$optionalColumns=$optionalColumns.",PayStartDt";
			$optionalVal=$optionalVal.",?";
			$params[]=$PayStartDt;
		}
		if(empty($PayEndDt)==false){
			$optionalColumns=$optionalColumns.",PayEndDt";
			$optionalVal=$optionalVal.",?";
			$params[]=$PayEndDt;
		}
		if(empty($StartDt)==false){
			$optionalColumns=$optionalColumns.",StartDt";
			$optionalVal=$optionalVal.",?";
			$params[]=$StartDt;
		}
		if(empty($EndDt)==false){
			$optionalColumns=$optionalColumns.",EndDt";
			$optionalVal=$optionalVal.",?";
			$params[]=$EndDt;
		}
		if(empty($BindStartDt)==false){
			$optionalColumns=$optionalColumns.",BindStartDt";
			$optionalVal=$optionalVal.",?";
			$params[]=$BindStartDt;
		}
		if(empty($BindEndDt)==false){
			$optionalColumns=$optionalColumns.",BindEndDt";
			$optionalVal=$optionalVal.",?";
			$params[]=$BindEndDt;
		}
		$sql=sprintf($sql,$optionalColumns,$optionalVal);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}	


	public function updateQ_AppPromotionCoupon($appId,$Status,$aclList,$Type,$RecharegAmount,$ReturnAmount,$PayStartDt,$PayEndDt,$Quantity,
					$StartDt,$EndDt,$IsNewBind,$BindStartDt,$BindEndDt,$idx,$MsgFirst,$MsgRemark,$CouponName)
	{
		$sql="update Q_AppPromotionCoupon set AppIds=?,Status=?,
				AclList=?,Type=?,RecharegAmount=?,
				ReturnAmount=?,Quantity=?,IsNewBind=?,MsgFirst=?,MsgRemark=?,CouponName=?,UpdateDt=NOW() ";
		
		$params=array($appId,$Status,$aclList,$Type,$RecharegAmount,$ReturnAmount,$Quantity,
					$IsNewBind,$MsgFirst,$MsgRemark,$CouponName);
		
		if(empty($PayStartDt)==false){
			$sql.=",PayStartDt=?";
			$params[]=$PayStartDt;
		}
		if(empty($PayEndDt)==false){
			$sql.=",PayEndDt=?";
			$params[]=$PayEndDt;
		}
		if(empty($StartDt)==false){
			$sql.=",StartDt=?";
			$params[]=$StartDt;
		}
		if(empty($EndDt)==false){
			$sql.=",EndDt=?";
			$params[]=$EndDt;
		}
		if(empty($BindStartDt)==false){
			$sql.=",BindStartDt=?";
			$params[]=$BindStartDt;
		}
		if(empty($BindEndDt)==false){
			$sql.=",BindEndDt=?";
			$params[]=$BindEndDt;
		}
		$sql.=" where IDX=? ";
		$params[]=$idx;
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	} 
	


	/**
	 * 发送礼券给手机用户--上传文件方式 ,确定该手机号在Q_MicroPlayer表中的微信用户
	 * 如礼券状态为白名单 ，则只导入白单用户手机
	 * @param  [type] $idx      [description]
	 * @param  [type] $giftCode [description]
	 * @return [type]           [description]
	 */
	public function insertQ_AppCouponPhoneNo($idx,$phoneNo)
	{
		// $sql="insert into Q_CouponPlayer (CouponIdx,PhoneNo,CreateDt) values (?,?,NOW())";
		$sql = " INSERT INTO Q_CouponPlayer (CouponIdx,PhoneNo,CreateDt)
			SELECT ? ,?,NOW() FROM  DUAL WHERE  EXISTS 
			(SELECT 1 FROM Q_MicroPlayer WHERE PhoneNo = ?) ;";
		$params=array($idx,$phoneNo,$phoneNo);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}

	/**
	 * 发送礼券给手机用户--上传文件方式 ,确定该手机号在Q_MicroPlayer表中的微信用户
	 * 如礼券状态为白名单 ，则只导入白单用户手机
	 * @param  [type] $idx      [description]
	 * @param  [type] $phoneNos [description]
	 * @return [type]           [description]
	 */
	public function insertQ_AppCouponPhoneNos($idx,$phoneNos){
		$sql = " INSERT INTO Q_CouponPlayer (CouponIdx,PhoneNo,CreateDt) VALUES " ;
		foreach ($phoneNos as $phoneNo) {
			$sql .= "(". $idx .",'". $phoneNo ."',NOW()),";	
		}
		if(substr($sql, -1) == ","){
			$sql = substr($sql , 0 , (strlen($sql) -1 ));
		}
		$params=array();
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	

	/**
	 * 给指定时间内绑定的用户发送礼券
	 * @param  [type] $idx         [description]
	 * @param  [type] $bindStartDt [description]
	 * @param  [type] $bindEndDt   [description]
	 * @return [type]              [description]
	 */
	public function insertQ_AppCouponPhoneNoBatch($idx,$bindStartDt,$bindEndDt,$grantCount){
		// $sql="INSERT INTO Q_CouponPlayer (PhoneNo,CouponIdx,CreateDt) SELECT DISTINCT PhoneNo ,?,NOW() FROM Q_MicroPlayer  WHERE BindDt BETWEEN ? AND ? AND PhoneNo NOT IN (SELECT PhoneNo FROM Q_CouponPlayer WHERE CouponIdx = ?) limit ?;";
		$sql="INSERT INTO Q_CouponPlayer (PhoneNo,CouponIdx,CreateDt) SELECT DISTINCT PhoneNo ,?,NOW() FROM Q_MicroPlayer  WHERE BindDt BETWEEN ? AND ? AND PhoneNo NOT IN (SELECT PhoneNo FROM Q_CouponPlayer WHERE CouponIdx = ?) ;";
		// $params=array($idx,$bindStartDt,$bindEndDt,$idx,$grantCount);
		$params=array($idx,$bindStartDt,$bindEndDt,$idx);
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}

	public function selectQ_AppCouponPhoneNoBatch($idx,$bindStartDt,$bindEndDt,$grantCount)
	{
		$sql="SELECT PhoneNo,OpenId FROM Q_MicroPlayer  WHERE BindDt BETWEEN ? AND ? AND PhoneNo is not null and PhoneNo<>''  AND PhoneNo NOT IN (SELECT PhoneNo FROM Q_CouponPlayer WHERE CouponIdx = ?) order by BindDt asc limit ".$grantCount;
		$params=array($bindStartDt,$bindEndDt,$idx);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info;
		}
		return array();		
	}
	

	/**
	 * 获取礼券已发送的数量 
	 * @param  [type] $idx [description]
	 * @return [type]      [description]
	 */
	public function getCouponGrantCount($idx){
		$sql = "SELECT a.Quantity,IFNULL(b.GrantCount,0) AS GrantCount FROM Q_AppPromotionCoupon a 	
			LEFT JOIN (
				SELECT COUNT(DISTINCT PhoneNo) AS GrantCount,CouponIdx FROM Q_CouponPlayer WHERE CouponIdx = ? GROUP BY CouponIdx
			) b ON a.IDX = b.CouponIdx 
			WHERE a.IDX = ?";
		$params = array($idx,$idx);
		$couponGrantCount=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($couponGrantCount) && is_array($couponGrantCount) && count($couponGrantCount)>0){
			return $couponGrantCount[0]["GrantCount"];
		}
		return 0;		
	}

	/**
	 * 返回已在微信用用户表中的手机号
	 * @param  [type] $phoneNo [description]
	 * @return [type]          [description]
	 */
	public function getMicroPlayerByPhoneNos($phoneNo){
		$sql = " SELECT PhoneNo FROM Q_MicroPlayer WHERE PhoneNo IN (". $phoneNo .");";
		$params = array();
		$phone_list=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($phone_list) && is_array($phone_list) && count($phone_list)>0){
			return $phone_list;
		}
		return array();		
	}

	/**
	 * 如果礼券状态为白名单，则返回在白名单内的手机号
	 * @param  [type] $idx     [description]
	 * @param  [type] $phoneNo [description]
	 * @return [type]          [description]
	 */
	public function getCouponAclListByPhoneNos($idx){
		$sql = " SELECT AclList FROM Q_AppPromotionCoupon WHERE IDX = ?";
		$params = array($idx);
		$phone_list=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($phone_list) && is_array($phone_list) && count($phone_list)>0){
			return $phone_list;
		}
		return array();		
	}

	/**
	 * 根据多个appId返回app信息
	 * @param  [type] $appIds [description]
	 * @return [type]         [description]
	 */
	public function getAppByAppids($appIds){
		$sql="select * from App where AppId in (". $appIds .")";
		$params=array();
		$appInfo=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($appInfo) && is_array($appInfo) && count($appInfo)>0){
			return $appInfo;
		}
		return array();
	}


	public function getQ_AppLotteryPlayerListCount($eventId,$lotteryDt,$pageId){
		$sql="SELECT a.* FROM Q_LotteryPlayer a 
			LEFT JOIN Q_AppPromotionLottery d ON a.LotteryIdx = d.IDX and d.EventId = ? 
			LEFT JOIN `Q_MicroPlayer` b  ON a.PhoneNo = b.PhoneNo
			LEFT JOIN `Q_ShareHistory` c ON b.OpenId = c.OpenId and c.SharePageId = ?  GROUP BY c.OpenId ";
		$params=array($eventId,$pageId);
		
		if(empty($lotteryDt)==false){
			$sql.=" and TO_DAYS(a.CreatDt) >= TO_DAYS(?)";
			$params[]=$lotteryDt;
		}
		
		$LotteryPlayer_list=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($LotteryPlayer_list) && is_array($LotteryPlayer_list) && count($LotteryPlayer_list)>0){
			return count($LotteryPlayer_list);
		}
		return 0;
	}

	/**
	 * 根据时间查询用户中奖信息
	 * @param  [type] $lotteryDt [description]
	 * @return [type]            [description]
	 */
	public function getQ_AppLotteryPlayerList($eventId,$lotteryDt,$pageId,$offSet,$page_size){
		$sql = "SELECT a.LotteryIdx , a.PhoneNo,a.CreateDt AS DrawDt ,d.Award ,b.BindDt,c.ShareDt
			FROM Q_LotteryPlayer a 
			LEFT JOIN Q_AppPromotionLottery d ON a.LotteryIdx = d.IDX and d.EventId = ?  
			LEFT JOIN `Q_MicroPlayer` b  ON a.PhoneNo = b.PhoneNo
			LEFT JOIN `Q_ShareHistory` c ON b.OpenId = c.OpenId  and c.SharePageId = ? GROUP BY c.OpenId
			";
		$params=array($eventId,$pageId);
		if(empty($lotteryDt)==false){
			$sql.=" and TO_DAYS(a.CreatDt) >= TO_DAYS(?)";
			$params[]=$lotteryDt;
		}
		
		$sql.=" order by a.CreateDt desc ";
		if($offSet>=0) {
			$sql=$sql." limit $offSet,$page_size";
		}		
		
		$LotteryPlayer_list=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($LotteryPlayer_list) && is_array($LotteryPlayer_list) && count($LotteryPlayer_list)>0){
			return $LotteryPlayer_list;
		}
		return array();		
	}

	/**
	 * 获取抽奖发奖情况 
	 * @return [type] [description]
	 */
	public function getLotteryInfo($evnetId){
		$sql = " SELECT a.Award ,a.Quantity ,IFNULL(b.DrawCount ,0) AS DrawCount 
			 FROM `Q_AppPromotionLottery` a 
			 LEFT JOIN (SELECT COUNT(*) AS DrawCount , LotteryIdx FROM `Q_LotteryPlayer` WHERE PhoneNo IS NOT NULL AND PhoneNo != ''  GROUP BY LotteryIdx) b 
			 ON a.IDX = b.`LotteryIdx`   WHERE a.`EventId` = ? ";
		$params = array($evnetId);
		$LotteryInfo=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($LotteryInfo) && is_array($LotteryInfo) && count($LotteryInfo)>0){
			return $LotteryInfo;
		}
		return array();				 
	}


	public function getFocusInfoCount($evnetId){
		$sql = "SELECT Count(*) as count
				FROM Q_Transaction a 
				LEFT JOIN `Q_MicroPlayer` b  ON a.PhoneNo = b.PhoneNo
				WHERE  a.EventId = ? ";
		$params = array($evnetId);
		$LotteryInfo=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($LotteryInfo) && is_array($LotteryInfo) && count($LotteryInfo)>0){
			return $LotteryInfo[0]["count"];
		}
		return 0;			 
	}	
	/**
	 * 查询新用记关注中奖情况 
	 * @return [type] [description]
	 */
	public function getFocusInfo($evnetId,$pageId,$offSet,$page_size){
		$sql = "SELECT a.PhoneNo,a.TransactionDt, b.BindDt 
				FROM Q_Transaction a 
				LEFT JOIN `Q_MicroPlayer` b  ON a.PhoneNo = b.PhoneNo
				WHERE  a.EventId = ? ";
		$sql.=" order by a.TransactionDt desc ";		
		if($offSet>=0) {
			$sql=$sql." limit $offSet,$page_size";
		}		
		$params = array($evnetId);
		$LotteryInfo=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($LotteryInfo) && is_array($LotteryInfo) && count($LotteryInfo)>0){
			return $LotteryInfo;
		}
		return array();				 
	}


	public function getOpenIdByPhone($phone)
	{
		$sql="select OpenId from Q_MicroPlayer where PhoneNo=?";
		$params=array($phone);
		$info=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($info) && is_array($info) && count($info)>0){
			return $info[0]["OpenId"];
		}
		return "";				 
	}
	
	public function insertQ_App($AppId,$Status,$AclList,$AppOrder,$IsFirstPublish,$BaseProrate,$PromoteProrate,$PromoteDt,$IsSinglePublish,$IsOtherPublish,$EvnUrl1,$EvnUrl2,$EvnUrl3,$EvnUrl4,$EvnUrl5,$EvnTitle1,$EvnTitle2,$EvnTitle3,$EvnTitle4,$EvnTitle5,$PromoteStartDt)
	{
		if(empty($PromoteProrate)){
			$sql="insert into Q_App (AppId,Status,AclList,AppOrder,BaseProrate,IsFirstPublish,CreateDt,UpdateDt,IsSinglePublish,IsOtherPublish,EvnUrl1,EvnUrl2,EvnUrl3,EvnUrl4,EvnUrl5,EvnTitle1,EvnTitle2,EvnTitle3,EvnTitle4,EvnTitle5) values (?,?,?,?,?,?,NOW(),NOW(),?,?,?,?,?,?,?,?,?,?,?,?) ";
			$params=array($AppId,$Status,$AclList,$AppOrder,$BaseProrate,$IsFirstPublish,$IsSinglePublish,$IsOtherPublish,$EvnUrl1,$EvnUrl2,$EvnUrl3,$EvnUrl4,$EvnUrl5,$EvnTitle1,$EvnTitle2,$EvnTitle3,$EvnTitle4,$EvnTitle5);
		}else{
			$sql="insert into Q_App (AppId,Status,AclList,AppOrder,BaseProrate,IsFirstPublish,PromoteProrate,PromoteDt,CreateDt,UpdateDt,IsSinglePublish,IsOtherPublish,EvnUrl1,EvnUrl2,EvnUrl3,EvnUrl4,EvnUrl5,EvnTitle1,EvnTitle2,EvnTitle3,EvnTitle4,EvnTitle5,PromoteStartDt) values (?,?,?,?,?,?,?,?,NOW(),NOW(),?,?,?,?,?,?,?,?,?,?,?,?,?) ";
			$params=array($AppId,$Status,$AclList,$AppOrder,$BaseProrate,$IsFirstPublish,$PromoteProrate,$PromoteDt,$IsSinglePublish,$IsOtherPublish,$EvnUrl1,$EvnUrl2,$EvnUrl3,$EvnUrl4,$EvnUrl5,$EvnTitle1,$EvnTitle2,$EvnTitle3,$EvnTitle4,$EvnTitle5,$PromoteStartDt);				
		}
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	
	public function modifyQ_App($Status,$AclList,$AppOrder,$IsFirstPublish,$BaseProrate,$PromoteProrate,$PromoteDt,$idx,$IsSinglePublish,$IsOtherPublish,$EvnUrl1,$EvnUrl2,$EvnUrl3,$EvnUrl4,$EvnUrl5,$EvnTitle1,$EvnTitle2,$EvnTitle3,$EvnTitle4,$EvnTitle5,$PromoteStartDt)
	{
		if(empty($PromoteProrate)){
			$sql="update Q_App set Status=?,AclList=?,AppOrder=?,BaseProrate=?,IsFirstPublish=?,PromoteProrate=null,PromoteDt=null,PromoteStartDt=null,UpdateDt=NOW(),IsSinglePublish=?,IsOtherPublish=?,EvnUrl1=?,EvnUrl2=?,EvnUrl3=?,EvnUrl4=?,EvnUrl5=?,EvnTitle1=?,EvnTitle2=?,EvnTitle3=?,EvnTitle4=?,EvnTitle5=? where IDX=?";
			$params=array($Status,$AclList,$AppOrder,$BaseProrate,$IsFirstPublish,$IsSinglePublish,$IsOtherPublish,$EvnUrl1,$EvnUrl2,$EvnUrl3,$EvnUrl4,$EvnUrl5,$EvnTitle1,$EvnTitle2,$EvnTitle3,$EvnTitle4,$EvnTitle5,$idx);
		}else{
			$sql="update Q_App set Status=?,AclList=?,AppOrder=?,BaseProrate=?,IsFirstPublish=?,PromoteProrate=?,PromoteDt=?,UpdateDt=NOW(),IsSinglePublish=?,IsOtherPublish=?,EvnUrl1=?,EvnUrl2=?,EvnUrl3=?,EvnUrl4=?,EvnUrl5=?,EvnTitle1=?,EvnTitle2=?,EvnTitle3=?,EvnTitle4=?,EvnTitle5=?,PromoteStartDt=? where IDX=?";
			$params=array($Status,$AclList,$AppOrder,$BaseProrate,$IsFirstPublish,$PromoteProrate,$PromoteDt,$IsSinglePublish,$IsOtherPublish,$EvnUrl1,$EvnUrl2,$EvnUrl3,$EvnUrl4,$EvnUrl5,$EvnTitle1,$EvnTitle2,$EvnTitle3,$EvnTitle4,$EvnTitle5,$PromoteStartDt,$idx);
		}
		return LunaPdo::GetInstance($this->_PDO_NODE_NAME)->exec_with_prepare($sql,$params);
	}
	
	public function getQ_App()
	{
		$sql="select a.AppOrder,a.AppId,a.Status,a.BaseProrate,a.PromoteProrate,a.PromoteDt,a.IsFirstPublish,a.IDX,b.AppName,a.IsSinglePublish,a.IsOtherPublish,a.EvnUrl1,a.EvnUrl2,a.EvnUrl3,a.EvnUrl4,a.EvnUrl5  
				from Q_App a
				left join App b on b.AppId=a.AppId  
			  order by a.AppOrder desc";
		$params=array();
		$AppInfo=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($AppInfo) && is_array($AppInfo) && count($AppInfo)>0){
			return $AppInfo;
		}
		return array();		
	}
	public function getQ_AppByIdx($idx)
	{
		$sql="select a.AppOrder,a.AppId,a.Status,a.BaseProrate,a.PromoteProrate,a.PromoteDt,a.IsFirstPublish,a.IDX,b.AppName,a.AclList,a.IsSinglePublish,a.IsOtherPublish,a.EvnUrl1,a.EvnUrl2,a.EvnUrl3,a.EvnUrl4,a.EvnUrl5,
					 a.PromoteStartDt,a.EvnTitle1,a.EvnTitle2,a.EvnTitle3,a.EvnTitle4,a.EvnTitle5   
				from Q_App a
				left join App b on b.AppId=a.AppId
				where a.IDX=? ";
		$params=array($idx);
		$AppInfo=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($AppInfo) && is_array($AppInfo) && count($AppInfo)>0){
			return $AppInfo[0];
		}
		return array();		
	}
	public function getQ_AppProfitSummary()
	{
		$sql="select AppId,sum(GameAmount) as GameAmount,sum(Amount) as Amount 
				from Q_MicroPhonePayReturn 
			  where ReturnStatus=1
			  group by AppId";
		$params=array();
		$AppInfo=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($AppInfo) && is_array($AppInfo) && count($AppInfo)>0){
			return $AppInfo;
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
	public function getAgencyIdByPromoterId($promoterId)
	{
		$sql="select * from AgencyPrompterPlay where PromoterId=?";
		$params=array($promoterId);
		$AppInfo=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($AppInfo) && is_array($AppInfo) && count($AppInfo)>0){
			return $AppInfo[0]["AgencyId"];
		}
		return "";		
	}
	public function getQ_MicroPlayer($PhoneNo,$OpenId,$sCreateDt,$eCreateDt,$sBindDt,$eBindDt)
	{
		$sql="select * from Q_MicroPlayer where 1=1 ";
		$params=array();
		
		if(empty($PhoneNo)==false){
			$sql.=" and PhoneNo like ?";
			$params[]= "%".$PhoneNo."%";			
		}
		if(empty($OpenId)==false){
			$sql.=" and OpenId like ?";
			$params[]= "%".$OpenId."%";
		}
		if(empty($sCreateDt)==false){
			$sql.=" and CreateDt >= ?";
			$params[]=$sCreateDt;
		}
		if(empty($eCreateDt)==false){
			$sql.=" and CreateDt <= ?";
			$params[]=$eCreateDt;
		}
		if(empty($sBindDt)==false){
			$sql.=" and BindDt >= ?";
			$params[]=$sBindDt;
		}
		if(empty($eBindDt)==false){
			$sql.=" and BindDt <= ?";
			$params[]=$eBindDt;
		}
		$AppInfo=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($AppInfo) && is_array($AppInfo) && count($AppInfo)>0){
			return $AppInfo;
		}
		return array();
	}
	
	public function getAppVersions($AppId)
	{
		$sql="select * from AppVersion where AppId=? order by AppVersionId desc";
		$params=array($AppId);
		$AppInfo=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($AppInfo) && is_array($AppInfo) && count($AppInfo)>0){
			return $AppInfo;
		}
		return array();
	}
	
	public function getQ_AppEvn1PlayerListCount(){
		$sql="SELECT count(*) as counts FROM Q_Evn_1 where ActId<>'OpLock' ";
		$params=array();		
		$Evn1Player_list=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($Evn1Player_list) && is_array($Evn1Player_list) && count($Evn1Player_list)>0){
			return count($Evn1Player_list[0]['counts']);
		}
		return 0;
	}

	/**
	 * 根据时间查询用户中奖信息
	 * @param  [type] $lotteryDt [description]
	 * @return [type]            [description]
	 */
	public function getQ_AppEvn1PlayerList($offSet,$page_size){
		$sql="SELECT a.* FROM Q_Evn_1 a where a.ActId<>'OpLock'";
		$params=array();
		$sql.=" order by a.CreateDt desc ";
		if($offSet>=0) {
			$sql=$sql." limit $offSet,$page_size";
		}
		$Evn1Player_list=LunaPdo::GetInstance($this->_PDO_NODE_NAME)->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($Evn1Player_list) && is_array($Evn1Player_list) && count($Evn1Player_list)>0){
			return $Evn1Player_list;
		}
		return array();		
	}
	
	public function queryWithSql($sql)
	{
		$params=array();
		$Info=LunaPdo::GetInstance("LunaLog")->query_with_prepare($sql,$params,PDO::FETCH_ASSOC);
		if(isset($Info) && is_array($Info) && count($Info)>0){
			return $Info;
		}
		return array();		
	}

}
