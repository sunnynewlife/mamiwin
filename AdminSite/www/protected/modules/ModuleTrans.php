<?php
require_once(dirname(__FILE__) . '/../models/FAppData.php');
require_once(dirname(__FILE__).'/../../../../luna_sdk/lib/util/CGuidManager.php');
LunaLoader::import("luna_lib.http.HttpInterface");
class ModuleTrans {


	static public function getPromoterAliPayApplyList() {
        $fAppData=new FAppData();
        $ApplyList = $fAppData->getPromoterAliPayApplyList();
        return $ApplyList;

	}

	static public function getCompTransList(){
        $fAppData=new FAppData();
        $compTransList = $fAppData->getCompTransList();
        return $compTransList;
	}

  static public function getCompTransListQueryDate($queryDate){
        $fAppData=new FAppData();
        $compTransList = $fAppData->getCompTransListQueryDate($queryDate);
        return $compTransList;
  }


	/**
	 * 根据转账计费规则 对该 转账给分红员的支付宝接口 进行转账费用 计算 ，以便于预提；
	 * @return [type] [description]
	 */
	static public function getTransCFee(){
        $fAppData = new fAppData();
        $batchTransCount = ConfAlipay::ALIPAYTRANSCOUNT;
        $LibAlipay = new LibAlipay;
        $ApplyList = $fAppData->getPromoterAliPayYesterdayList();
        $applyids = array();
        $i = 0 ;
        $transCFee = ConfAlipay::FEE1 ;

        if(is_array($ApplyList)){
            $fee = 0 ;
            $detail = '';
            foreach ($ApplyList as $key => $value) {
                $amount = $value['Amount'];
                $applyid = $value['ApplyId'];
                $applyids[] = $applyid;
                $alipayNo = $value['AliPayNo'];
                $promoter = $value['PromoterName'];
                $desc = "转账给推广员:".$promoter;
                if(!empty($applyid) && !empty($alipayNo) && !empty($amount) ){
                    $fee = bcadd($fee, $amount,2);
                    $detail .= $applyid.'^'.$alipayNo.'^'.$promoter.'^'.$amount.'^'.$desc.'|';    
                    $i++;

                 	if($i >= $batchTransCount){
                 		$transCFee = bcadd($transCFee, $LibAlipay->getTransationFee(floatval($fee)),2);
                 		
                 	}
                }                
            }
        }
        return $transCFee;
	}

	/**
	 * 分红推广员提现成功后，修改提示申请表、修改推广员表isCashed、记录流水
	 * @return [type] [description]
	 */
	static public function updatePromoterTransSuccess($para){
		$applyid = $para['applyid'];
		$state = $para['state'];
		$memo = $para['memo'];
		$fAppData=new FAppData();
    $ret_Apply = $fAppData->updatePromoterAlipayApply($applyid,$state ,$memo);
    //TODO 记录流水paytransaction表
  }


  /**
   * 支付宝回调失败后，改写状态 ,返还推广员可提现金额
   * 记录服务端拉取消息、发客户端消息
   * 2015-2-3，判断用户登录方式：web登录发短信，app登录发app消息  
   * @param  [type] $applyid [description]
   * @param  [type] $state   [description]
   * @param  [type] $memo    [description]
   * @return [type]          [description]
   */
  static function promoterAlipayFailNotify($applyId , $state ,$memo){
    $fAppData=new FAppData();
    $LibAlipay = new LibAlipay;
    $State = 0 ;
    $notifyInfo = $fAppData->getAlipayNotifyInfoByApplyid($applyId);
    if($notifyInfo){
      $appId = ConfAlipay::ALIPAYNOTIFYAPPID;
      $amount=$notifyInfo[0]['Amount'];
      $playerName=$notifyInfo[0]['AliPayNo'];
      $playerName = $LibAlipay->coverAlipayNo($playerName);
      $Fee=$notifyInfo[0]['Fee'];
      $inAmount = bcadd($amount, $Fee,2);
      $State=$notifyInfo[0]['State'];
      $phone=$notifyInfo[0]['PhoneNo'];
      $phones = array($phone);
      $promoterId=$notifyInfo[0]['PromoterId'];
      $dest = $phone.'@'.$appId;
      $clientAppVersion=$notifyInfo[0]['ClientAppVersion'];
      $clientType=$notifyInfo[0]['ClientType'];


      //增加判断，如果提现申请表记录状态为 提交到支付宝 ，才进行改状态、发消息 操作
      if($State == ConfAlipay::ALIAPYFAIL){
        return true;
      }else if($state == ConfAlipay::ALIAPYFAIL || $state == ConfAlipay::ALIPAYAPPLYREFUSE){
        $ret1 = $fAppData->updateProAlipayApply($applyId , $state ,$memo);
        if($ret1 >  0 ){
          // $ret2 = ModuleTrans::returnPromoterAmount($applyId);
          // 退回时要连同手续费一同退回
          $ret2 = $fAppData->returnPromoterFailAmount($promoterId , $inAmount); 
          if($ret2 > 0 ){
            $title="提现失败";
            $detailUrl="";
            $abstract="提现失败";
            $content="提现失败:".$amount;
            $imgUrl="";
            $channelId=ConfAlipay::ALIPAYNOTIFYCHANNELID;
            $channelType = ConfAlipay::ALIPAYNOTIFYCHANNELTYPEID;
            $msgTypeId=ConfAlipay::ALIPAYFAILNOTIFYMSGTYPEID;
            $appId = ConfAlipay::ALIPAYNOTIFYAPPID;
            $notifyTitle="提现失败";
            $strongTitle  = "退回";
            $image = "";
            $ticket = "";
            $gameName = "";
            $playerName = "";
            $transactDt = date('Y-m-d H:i:s');
            $scheduleTime = '';
            $clientTime = '';

            $failMesaage = $fAppData->getSystemConfigItem('apply.pay.error.map','',true);
            $failReson = '';
            if(is_array($failMesaage)){
                if(is_array($failMesaage['error_map']) && array_key_exists($memo,$failMesaage['error_map'])){
                    $failReson = $failMesaage['error_map'][$memo];
                }
                if(empty($failReson) && array_key_exists($memo, $failMesaage)){
                    $failReson = $failMesaage[$memo];      
                }   
                if(empty($failReson)){
                    $failReson = $failMesaage['default'];      
                }           
            }

            $msgBody = array(
              'title'=>$title,
              'detailUrl'=>$detailUrl,
              'abstract'=>$abstract,
              'notifyTitle'=>$notifyTitle,
              'image'=>$image,
              'scheduleTime'=>$scheduleTime,
              'typeId'=>$msgTypeId,
              'content'=>$content,
              'dest'=>$dest,
              'appId'=>$appId,
              'ticket'=>$ticket,
              'gameName'=>$gameName,
              'playerName'=>$playerName,    //支付宝账号
              'transactDt'=>$transactDt,
              'gameAmount'=>$inAmount,   //提现金额
              'amount'=>$amount,           //到账金额
              'clientTime'=>$clientTime,
              'StrongTitle'=>$strongTitle,
              'failReson'=>$failReson
              );
              //写入消息列表
              $cGuidManager = new CGuidManager();
              $sessionId = $cGuidManager->GetGuid();
              $ret31 = ModuleTrans::insertPromoterAmountMsg($promoterId ,$sessionId ,$msgTypeId ,$msgBody ,$title ,$detailUrl ,$abstract ,$content,$imgUrl,$notifyTitle,$phones,$scheduleTime);


              //如果是web登录，则发短信消息给用户  $memo == "reject"
              if(ModuleTrans::isWebLogin($clientType)){
                if($state == ConfAlipay::ALIPAYAPPLYREFUSE ){ 
                  $hps = new HttpInterface("SmsSubmit","FHApproveFail");  
                }else{
                  $hps = new HttpInterface("SmsSubmit","FHTransFail");
                }
                
                if(!empty($phone)){
                        $seq = ModuleTrans::getBatchNo();
                        $param = array(
                                'seq'=>$seq,
                                'phone'=>$phone,
                                'msg'=>$inAmount,
                                'timeStamp'=>time(),
                            ) ;
                        $data=$hps->submit($param);                             
                }
                return true ;
              }            

              //如果是app登录，则发app消息 
              //客户端版本为空，不发提现失败消息 
              if(empty($clientAppVersion)){
                return true ;
              }
              $clientAppVersions = explode('.',$clientAppVersion);
              $allowAppVersion = ConfAlipay::ALLOWFAILMSGAPPVERSION ;
              for($i = 1 ; $i <  count($clientAppVersions)  ; $i++){
                $allowAppVersion = $allowAppVersion * 10 ;  
              }
              $clientAppVersion = str_replace('.', '', $clientAppVersion);
                        
              //具有版本的且大于可接收提现失败消息的版本,才进行下发消息操作 
              if($clientAppVersion < $allowAppVersion){
                return true ;
              }

              $content = $ret31;

              $ret3 = ModuleTrans::sendAppMsg($promoterId ,$sessionId,$channelId, $msgTypeId ,$msgBody ,$title ,$detailUrl ,$abstract ,$content,$imgUrl,$notifyTitle,$phones,$scheduleTime);
              if($ret3){
                return true;
              }
              return false ;  
          }      
        }
      }

    }
    

    return false ;
  }

  /**
   * 支付宝回调成功后，改写状态 、记录服务端拉取消息、发客户端消息
   * @param  [type] $applyid [description]
   * @param  [type] $state   [description]
   * @param  [type] $memo    [description]
   * @return [type]          [description]
   */
  static function promoterAlipayNotify($applyId,$state ,$amount,$memo){
      $fAppMsgDepend=new FAppMsgDepend();
      $fAppData=new FAppData();
      $cGuidManager = new CGuidManager();
      $LibAlipay = new LibAlipay;
      $sessionId = $cGuidManager->GetGuid();
      $title="提现成功";
      $detailUrl="";
      $abstract="提现成功";
      $content="提现成功:".$amount;
      $imgUrl="";
      $channelId=ConfAlipay::ALIPAYNOTIFYCHANNELID;
      $channelType = ConfAlipay::ALIPAYNOTIFYCHANNELTYPEID;
      $msgTypeId=ConfAlipay::ALIPAYNOTIFYMSGTYPEID;
      $appId = ConfAlipay::ALIPAYNOTIFYAPPID;
      $notifyTitle="提现成功";
      $image = "";
      $ticket = "";
      $gameName = "";
      $playerName = "";
      $method = "push.pushMessage";
      $clientTime = "";
      $State = 0 ;
      $clientType = "";
      $notifyInfo = $fAppData->getAlipayNotifyInfoByApplyid($applyId);
      if($notifyInfo){
        $amount=$notifyInfo[0]['Amount'];
        $playerName=$notifyInfo[0]['AliPayNo'];
        $playerName = $LibAlipay->coverAlipayNo($playerName);
        $Fee=$notifyInfo[0]['Fee'];
        $inAmount = bcadd($amount, $Fee,2);
        $State=$notifyInfo[0]['State'];
        $phone=$notifyInfo[0]['PhoneNo'];
        $phones = array($phone);
        $promoterId=$notifyInfo[0]['PromoterId'];
        $dest = $phone.'@'.$appId;
        $clientType=$notifyInfo[0]['ClientType'];
      }
      $transactDt = date('Y-m-d H:i:s');
      $scheduleTime="";

      //增加判断，如果提现申请表记录状态为 提交到支付宝 ，才进行改状态、发消息 操作
      if($State == ConfAlipay::ALIPAYSUCCESS){
        return true;
      }else if($State == ConfAlipay::ALIPAYAPPLIED){
        //1.改写支付状态，如果是转账服务费回B账户，则还要改写CompAlipayApply状态 
        $ret1 = $fAppData->updateProAlipayApply($applyId , $state ,$memo);
        //2.记录服务端拉取消息
        $para = array(
          'MsgType'=>$msgTypeId,
          'PromoterId'=>$promoterId,
          'MsgBody'=>'',
          );
        $msgId = $fAppData->insertPromoterAmountMsg($para);

        if($msgId > 0){
          //修改MsgBody字段内容
          $msg = "提现到账提醒:".$amount;
          $content = json_encode(array(
              'id'=>$msgId,
              'text' => $msg,
              'channelId' =>$channelId,
              'channelType' =>$channelType,
              'type' => 2,
            ));
          
          $msgBody = array(
            'title'=>$title,
            'detailUrl'=>$detailUrl,
            'abstract'=>$abstract,
            'notifyTitle'=>$notifyTitle,
            'image'=>$image,
            'sessionId'=>$sessionId,
            'scheduleTime'=>$scheduleTime,
            'typeId'=>$msgTypeId,
            'content'=>$content,
            'dest'=>$dest,
            'appId'=>$appId,
            'ticket'=>$ticket,
            'method'=>$method,
            'gameName'=>$gameName,
            'playerName'=>$playerName,    //支付宝账号
            'transactDt'=>$transactDt,
            'gameAmount'=>$inAmount,   //提现金额
            'amount'=>$amount,           //到账金额
            'clientTime'=>$clientTime
            );

          $paras = array(
            'MsgType'=>$msgTypeId,
            'PromoterId'=>$promoterId,
            'MsgBody'=>json_encode($msgBody),
            'MsgId'=>$msgId,
          );
          $ret2 = $fAppData->updatePromoterAmountMsg($paras);
          if($ret2 > 0){
              //3.发短信给用户
              if(ModuleTrans::isWebLogin($clientType)){
              $hps = new HttpInterface("SmsSubmit","FHTransSuccess");
              if(!empty($phone)){
                      $seq = ModuleTrans::getBatchNo();
                      $param = array(
                              'seq'=>$seq,
                              'phone'=>$phone,
                              'msg'=>$amount,
                              'timeStamp'=>time(),
                          ) ;
                      $data=$hps->submit($param);                             
              }
              return true ;
            }

            //3.发客户端消息
            $ret3 = $fAppMsgDepend->sendMsg($sessionId,$title,$detailUrl,$abstract,$content,$imgUrl,$channelId,$msgTypeId,$notifyTitle,$phones,$scheduleTime);  
            if($ret3 && $ret2>0 && $ret1>0){
              return true;  
            }
          }
        }
      }  
      return false;
  }


  /**
   * 根据提现申请ID，提现失败情况下返回推广员可提现金额 
   * @param  [type] $applyId [提现申请ID]
   * @return [type]          [description]
   */
  static public function returnPromoterAmount($applyId){
        $fAppData=new FAppData();
        $applyInfo = $fAppData->getProAlipayApplyInfoByApplyid($applyId);
        if($applyInfo){
          $promoterId = $applyInfo[0]['PromoterId'];
          $transAmount = $applyInfo[0]['Amount'];
          $transFee = $applyInfo[0]['Fee'];
          $transAmounts = bcadd($transAmount, $transFee,2);
          $promoterInfo = $fAppData->getPromoterByPromoterId($promoterId);

          if($promoterInfo){
            $amount = $promoterInfo['Amount'];
            $netAmount = $promoterInfo['NetAmount'];
            $amount = bcadd($amount, $transAmounts,2);
            $netAmount = bcadd($netAmount, $transAmounts,2);
            $returnRowCount = $fAppData->returnPromoterAmount($promoterId , $amount , $netAmount);  
            if($returnRowCount > 0 ){
              return true ;            
            }            
          }
        }    
        return false;
  }

  /**
   * 退回推广员末提现成功的金额，并发消息 
   * @param  [type] $promoterId [description]
   * @param  [type] $amount     [description]
   * @return [type]             [description]
   */
  static public function returnPromoterFailAmount($promoterId , $amount){

  }



/**
 * 发消息给app，并添加服务端消息内容
 * @param  [type] $promoterId [推广员ID]
 * @param  [type] $msgBody    [消息体]
 *           $msgBody = array(
            'title'=>$title,
            'detailUrl'=>$detailUrl,
            'abstract'=>$abstract,
            'notifyTitle'=>$notifyTitle,
            'image'=>$image,
            'sessionId'=>$sessionId,
            'scheduleTime'=>$scheduleTime,
            'typeId'=>$msgTypeId,
            'content'=>$content,
            'dest'=>$dest,
            'appId'=>$appId,
            'ticket'=>$ticket,
            'method'=>$method,
            'gameName'=>$gameName,
            'playerName'=>$playerName,    //支付宝账号
            'transactDt'=>$transactDt,
            'gameAmount'=>$inAmount,   //提现金额
            'amount'=>$amount,           //到账金额
            'clientTime'=>$clientTime
            );
 * @param  [type] $msgTypeId  [消息类型]
 * @param  [type] $title  [消息类型]
 * @param  [type] $detailUrl  [消息类型]
 * @param  [type] $abstract  [消息类型]
 * @param  [type] $content  [消息类型]
 * @return [type]             [description]
 */
  static public function sendAppMsg($promoterId ,$sessionId,$channelId, $msgTypeId ,$msgBody ,$title ,$detailUrl ,$abstract ,$content,$imgUrl,$notifyTitle,$phones,$scheduleTime){
    //3.发客户端消息
    $fAppMsgDepend=new FAppMsgDepend();
    $ret3 = $fAppMsgDepend->sendMsg($sessionId,$title,$detailUrl,$abstract,$content,$imgUrl,$channelId,$msgTypeId,$notifyTitle,$phones,$scheduleTime);  
    if($ret3){
      return true;  
    }
    return false;
  }

/**
 * 根据不同情况 发不同短信模版给用户
 * @return [type] [description]
 */
  static public function sendMobileMsg(){

  }

/**
 * 写入消息列表，以备客户端拉取消息
 * @param  [type] $promoterId   [description]
 * @param  [type] $msgTypeId    [description]
 * @param  [type] $msgBody      [description]
 * @param  [type] $title        [description]
 * @param  [type] $detailUrl    [description]
 * @param  [type] $abstract     [description]
 * @param  [type] $content      [description]
 * @param  [type] $imgUrl       [description]
 * @param  [type] $notifyTitle  [description]
 * @param  [type] $phones       [description]
 * @param  [type] $scheduleTime [description]
 * @return [type]               [description]
 */
  static public function insertPromoterAmountMsg($promoterId ,$sessionId,$msgTypeId ,$msgBody ,$title ,$detailUrl ,$abstract ,$content,$imgUrl,$notifyTitle,$phones,$scheduleTime){
      $channelId=ConfAlipay::ALIPAYNOTIFYCHANNELID;
      $channelType = ConfAlipay::ALIPAYNOTIFYCHANNELTYPEID;
      $cGuidManager = new CGuidManager();
      
      $method = "push.pushMessage";
      $fAppData=new FAppData();
      $para = array(
          'MsgType'=>$msgTypeId,
          'PromoterId'=>$promoterId,
          'MsgBody'=>'',
          );
        $msgId = $fAppData->insertPromoterAmountMsg($para);

        if($msgId > 0){
          //修改MsgBody字段内容
          $msg = "";
          $content = json_encode(array(
              'id'=>$msgId,
              'text' => $msg,
              'channelId' =>$channelId,
              'channelType' =>$channelType,
              'type' => 3,
            ));
          $msgBody = array_merge(array(
              'sessionId'=>$sessionId,
              'method'=>$method,
            ),$msgBody);
          $paras = array(
            'MsgType'=>$msgTypeId,
            'PromoterId'=>$promoterId,
            'MsgBody'=>json_encode($msgBody),
            'MsgId'=>$msgId,
          );
          $ret2 = $fAppData->updatePromoterAmountMsg($paras);
          if($ret2 > 0){
            return $content;  
          }
        }
        return false;
  }

  /**
   * 计算每日分账数据
   * 有手机号且mid不为空转账给GBAO账户，其他的转C账户
   * // TODO: 避免重复转账，应做转账成功标记；
   * @return [type] [description]
   */
  static public function comCompTrans($queryDate,$isLog){

        $totalAmounts = 0 ;                     //用户充值总金额
        $alipayTotalAmounts = 0 ;               //支付宝充值总金额
        $transBAmounts = 0 ;                    //留存于平台总金额
        $transCAmounts = 0 ;                    //待转账给大客户总金额
        $transDAmounts = 0 ;                    //待转账给分红员GBAO总金额
        $appAmounts = 0 ;                       //游戏厂商总金额
        $SwitchTransactionId = 13454;               //最后一条充值记录ID  ，切换时用于判断
        
        $fAppData=new FAppData();
        $compTransList = ModuleTrans::getCompTransListQueryDate($queryDate);
        $LibAlipay = new LibAlipay;
        $cSumRateArray=array();
        $appSumRateArray=array();
        
        foreach ($compTransList as $key => &$value) {
            foreach ($value as $transkey => &$transvalue) {
                if($transkey == "alipayAmount"){
                    $alipayAmount = $transvalue;
                }else if($transkey == "PhoneNo"){
                    $phoneNo = $transvalue;
                }else if($transkey == "Mid"){
                    $mid = $transvalue;
                }else if($transkey == "amount"){
                    $amount = $transvalue;
                }else if($transkey == "TransactionId"){
                    $TransactionId = $transvalue;
                }else if($transkey == "rate"){
                    $promoterRate = $transvalue;
                // }else if($transkey == "PromotionNo"){
                //     $promotionNo = $transvalue;
                //     $appInfo = $fAppData->getAppInfoByPromotionNo($promotionNo);
                //     if(is_array($appInfo) && count($appInfo) > 0 ){
                //         $appRate = $appInfo['DeveloperProrate'];
                //         $appCostFeePercent = $appInfo['CostFeePercent'];
                //     }
                }else if($transkey == "AppId"){
                    $appId = $transvalue;
                    $appInfo = $fAppData->getAppInfoByAppId($appId);
                    if(is_array($appInfo) && count($appInfo) > 0 ){
                        $appRate = $appInfo['DeveloperProrate'];
                        $appCostFeePercent = $appInfo['CostFeePercent'];
                    }
                }
            }
            if(isset($alipayAmount)){
                //echo("充值金额=".$amount." ");
                //echo("<br>");
                //提现转BAO，根据TranscationId来判断，切换正常后取消此判断 
                if(empty($mid)==false && preg_match("/^1[0-9]{10}$/",trim($phoneNo)) && ($TransactionId > $SwitchTransactionId )){
                    //echo("转给推广员GBAO账户金额=".$alipayAmount." ");
                    //echo("<br>");
                    $transDAmounts = bcadd($transDAmounts,$alipayAmount,2);
                    $value["transC"] = 0;
                    $value["transD"] = $alipayAmount;
                }else{
                    //echo("转给大客户账户总金额=".$alipayAmount." ");
                    //echo("<br>");
                    $transCAmounts = bcadd($transCAmounts,$alipayAmount,2);
                    $value["transC"] = $alipayAmount;
                    $value["transD"] = 0;
                }
            }

            //计算转帐金额
            if(isset($amount) ){
                $totalAmounts = bcadd($totalAmounts,$amount,2);
                //echo("游戏厂商分成基数比例=".bcsub(1,$appCostFeePercent,4)." ");
                //echo("<br>");
                //echo("游戏厂商分成比例=".$appRate." ");
                //echo("<br>");
                //按每笔计算游戏厂商分成金额 12-18，调整为从表中取 costFeePercent
                $alipayIncome  = bcmul($amount,bcsub(1,$appCostFeePercent,4),2);
                $alipayTotalAmounts = bcadd($alipayTotalAmounts,$alipayIncome,2);
                if(isset($appSumRateArray[$appRate])==false){
                    $appSumRateArray[$appRate]=0.00;
                }
                $appSumRateArray[$appRate]=bcadd($alipayIncome,$appSumRateArray[$appRate],2);  
                
                $value["appCostFeePercent"] = bcsub(1,$appCostFeePercent,4);
                $value["appRate"] = $appRate;
                $value["alipayIncome"] = bcmul($alipayIncome,$appRate,5);;              
            }
            //echo("<br>");
        }
        foreach ($appSumRateArray as $app_key => $app_value) {
                $appAmount = bcmul($app_value,$app_key,5);
                //echo("游戏厂商分成金额=".$appAmount);
                //echo("<br>");
                $appAmounts = bcadd($appAmounts,$appAmount,5);
        }
        
        $appAmounts = number_format($appAmounts,2,'.','');     //最后取小数点后二位
        $transCDAmounts = bcadd($transCAmounts,$transDAmounts,2);   //计算手机推广员、大客户推广员总金额
        $transBAmounts = ModuleTrans::countBAmount($alipayTotalAmounts,$transCDAmounts,$appAmounts);
        $transBAccount = ConfAlipay::WIDEMAILB;
        $transBName = ConfAlipay::WIDACCOUNT_NAME;
      
        //echo("B账户转入金额（无FEE）=".$transBAmounts);
        //echo("<br>");
        //echo("C账户转入金额（无FEE）=".$transCAmounts);
        //echo("<br>");
      
        
        //******************************************************************************
        //2015/12/7，分红员提现改gbao，则不发生提现手续费
        //$transCFee = ModuleTrans::getTransCFee();       //计算C账户转推广员手续费;
        //$transCAmounts = bcadd($transCAmounts, $transCFee,2);   //C账户转推广员手续费
        //$transBAmounts = bcsub($transBAmounts, $transCFee,2);   //C账户转账手续费由B出  
        
        
        //echo("C账户转推广员手续费=".$transCFee);
//        echo("<br>");
//        echo("C账户转入金额=".$transCAmounts);
//        echo("<br>");
        //******************************************************************************
        $tmpTransAmounts = bcadd($transBAmounts, $transCDAmounts,2);
        $transBCFee = $LibAlipay->getTransationFee($tmpTransAmounts);   //计算A转账BCD时发生的手续费
        $transBAmounts = bcsub($transBAmounts, $transBCFee,2);          //A转账BCD手续费由B出
        //echo("A账户BCD手续费=".$transBCFee);
//        echo("<br>");
//        echo("B账户转入金额=".$transBAmounts);
//        echo("<br>");

        $transCAccount = ConfAlipay::WIDEMAILC;
        $transCName = ConfAlipay::WIDACCOUNT_NAME;

        $transDAccount = ConfAlipay::WIDEMAILD;
        $transDName = ConfAlipay::WIDACCOUNT_NAME;        
        
        $transAmounts = bcadd($transBAmounts, $transCDAmounts,2);
        $pay_date = date("Ymd");
        $batch_no = $pay_date . ModuleTrans::getBatchNo();
        
        $batchNum = 0 ;
        $detail="";
        
        if($transBAmounts>0.0){
        	$batchNum++;
        	$detail = $batch_no.$batchNum.'^'.$transBAccount.'^'.$transBName.'^'.$transBAmounts.'^'.'转账给平台账户';
        }
        if($transCAmounts>0.0){
        	$batchNum++;
        	if(empty($detail)==false){
        		$detail.="|";
        	}
        	$detail .= $batch_no.$batchNum.'^'.$transCAccount.'^'.$transCName.'^'.$transCAmounts.'^'.'转账给大客户总账户';
        }
        if($transDAmounts>0.0){
        	$batchNum++;
        	if(empty($detail)==false){
        		$detail.="|";
        	}
        	$detail .= $batch_no.$batchNum.'^'.$transDAccount.'^'.$transDName.'^'.$transDAmounts.'^'.'转账给推广员总账户';        	 
        }        
/*        
        $batchNum = 3 ;
        $detail = $batch_no.'1^'.$transBAccount.'^'.$transBName.'^'.$transBAmounts.'^'.'转账给平台账户'.'|'.$batch_no.'2^'.$transCAccount.'^'.$transCName.'^'.$transCAmounts.'^'.'转账给大客户总账户'.'|'.$batch_no.'3^'.$transDAccount.'^'.$transDName.'^'.$transDAmounts.'^'.'转账给推广员总账户';
*/

        LunaLogger::getInstance()->info("totalAmounts:".$totalAmounts);
        LunaLogger::getInstance()->info("appAmounts:".$appAmounts);
        LunaLogger::getInstance()->info("transBAmounts:".$transBAmounts);
        LunaLogger::getInstance()->info("transCAmounts:".$transCAmounts);
        LunaLogger::getInstance()->info("transDAmounts:".$transDAmounts);
        LunaLogger::getInstance()->info("detail:".$detail);
        //echo("totalAmounts=".$totalAmounts);
//        echo("<br>");
//        echo("appAmounts=".$appAmounts);
//        echo("<br>");
//        echo("transBAmounts=".$transBAmounts);
//        echo("<br>");
//        echo("transCAmounts=".$transCAmounts);
//        echo("<br>");
//        echo("transDAmounts=".$transDAmounts);
//        echo("<br>");
        if($transAmounts > 0 && $batchNum > 0 ){
            //记录数据库，以备对账
            $transDate = date("Y-m-d",time());
            $qDate = date("Y-m-d",strtotime("-$queryDate day"));
            if($isLog == 1 ){
                $ret = $fAppData->insertComTrans($transDate,$qDate,$totalAmounts,$appAmounts,$transBAmounts,$transDAmounts,$transCAmounts);
            }
            return array(
                'transArray'=>$compTransList,
                'totalAmounts'=>$totalAmounts,
                'appAmounts'=>$appAmounts,
                'transBAmounts'=>$transBAmounts,
                'transCAmounts'=>$transCAmounts,
                'transDAmounts'=>$transDAmounts,
                'transAmounts'=>$transAmounts,
                'batchNum'=>$batchNum,
                'detail'=>$detail,
                
              );
        }
  }

    /**
     * 计算游戏厂商应该转的金额
     * A => B    A =>B     TotalAmount *(1-0.015) -  SUMC - TotalAmount *(1-0.015) * 游戏厂商分成比例
     * @param  [type] $amount    [提现总金额]
     * @param  [type] $camount   [转给分红总帐户金额]
     * @param  [type] $appamount [游戏厂商帐户金额]
     * @return [type]            [description]
     */
    static private function countBAmount($amount , $camount , $appamount ){
        return bcsub(bcsub($amount,$camount ,2) ,$appamount,2);
    }


    /**
     * 获取最新的流水号
     */
    static private function getBatchNo(){
        $cGuidManager = new CGuidManager();
        return $cGuidManager->GetGuid();
    }



	/**
	 * T+1 提现 审核时不做拼单提现操作
	 * 拼单转账给分红推广员 cancel 
	 * @return [type] [description]
	 */
	static public function transPromoterBatch(){
		return true; 
		// $batchCount = ConfAlipay::ALIPAYTRANSCOUNT;
		// $fAppData=new FAppData();
  //       $ApplyList = $fAppData->getPromoterAliPayApplyList($batchCount);
  //       if(is_array($ApplyList)){
  //           $fee = 0 ;
  //           $detail = '';        
		// 	foreach ($applyList as $key => $value) {
  //               $amount = $value['Amount'];
  //               $applyid = $value['ApplyId'];
  //               $applyids[] = $applyid;
  //               $alipayNo = $value['AliPayNo'];
  //               $promoter = $value['PromoterName'];
  //               $desc = "转账给推广员:".$promoter;
  //               if(!empty($applyid) && !empty($alipayNo) && !empty($amount) ){
  //                   $fee = bcadd($fee, $amount,2);
  //                   $amount = 0.01;     //DEBUG    
  //                   $fee = 0.01;        //DEBUG
  //                   $alipayNo = "sunnynewlife@hotmail.com";
  //                   $promoter = "杨恩睿";
  //                   $detail .= $applyid.'^'.$alipayNo.'^'.$promoter.'^'.$amount.'^'.$desc.'|';    
  //                   $i++;
  //               }                
		// 	}
		// }

  //       $email = ConfAlipay::WIDEMAILC;
  //       $account_name = ConfAlipay::WIDACCOUNT_NAME ;
  //       $pay_date = date("Ymd");
  //       $batch_no = $pay_date . $this->getBatchNo();
  //       $batch_fee = $fee;
  //       $batch_num = $i;
  //       $notify_url = ConfAlipay::NOTIFY_PRO_URL ;
  //       $inAccount = ConfAlipay::WIDEMAILB;
  //       $name = ConfAlipay::WIDACCOUNT_NAME;
        
  //       //安全检验码，以数字和字母组成的32位字符
  //       $alipay_config['key']           = ConfAlipay::KEYC;    
  //       $alipay_config['sign_type']    = self::$_alipay_config['sign_type'];    
  //       $alipay_config['input_charset']= self::$_alipay_config['input_charset'];
  //       $alipay_config['cacert']    = getcwd().'\\cacert.pem';

  //       //构造要请求的参数数组，无需改动
  //       $parameter = array(
  //               "service" => self::$_alipay_service ,
  //               "partner" => ConfAlipay::PARTNERC,
  //               "notify_url"    => $notify_url,
  //               "email" => $email,
  //               "account_name"  => $account_name,
  //               "pay_date"  => $pay_date,
  //               "batch_no"  => $batch_no,
  //               "batch_fee" => $batch_fee,
  //               "batch_num" => $batch_num,                
  //               "detail_version"   => self::$_detail_version,
  //               "detail_data"   => $detail,
  //       );

  //       //建立请求
  //       $alipaySubmit = new AlipaySubmit($alipay_config);
  //       $sResult = $alipaySubmit->buildRequestHttp($parameter);
  //       $syncResult = $this->getSyncResult($sResult);
  //       $alipay = $syncResult['alipay'];
  //       $is_success = $syncResult['is_success'];
  //       $error = $syncResult['error'];        

  //       if($alipay && $is_success == "T"){
  //           $state = ConfAlipay::ALIPAYAPPLIED;
  //           $memo = '已提交到支付宝';
  //           $rowCount = $fAppData->updatePromoterAlipayApply($applyids , $state , $memo );
  //       }else{
  //           var_dump($sResult);
  //           die();
  //       }

	}



  static public function RechargeAlert(){
        $totalAmounts = 0 ;                     //用户充值总金额
        $fAppData=new FAppData();
        $alertAmount = $fAppData->getSystemConfigItem('paytransaction.alert.min','',true);
        $alertPhones = $fAppData->getSystemConfigItem('paytransaction.alert.phones','',false);
        
        $compTransList = ModuleTrans::getCompTransListQueryDate(0);
        foreach ($compTransList as $key => $value) {
            foreach ($value as $transkey => $transvalue) {
                if($transkey == "amount"){
                    $amount = $transvalue;
                }
            }
            //计算转帐金额
            if(isset($amount) ){
                $totalAmounts = bcadd($totalAmounts,$amount,2);
            }            
        }
        echo("totalAmounts=".$totalAmounts."\r\n");
        echo("alertAmount=".$alertAmount."\r\n");
        if($totalAmounts < $alertAmount){
            $hps = new HttpInterface("SmsSubmit","FHSubmit");
            // var_dump($hps) ;
            if(!empty($alertPhones)){
                $phones = explode(',', $alertPhones);
                if(is_array($phones)){
                  foreach ($phones as $key => $value) {
                    $seq = ModuleTrans::getBatchNo();
                    $phone = $value;
                    $param = array(
                            'seq'=>$seq,
                            'phone'=>$value,
                            'msg'=>$totalAmounts,
                            'timeStamp'=>time(),
                        ) ;
                    
                    $data=$hps->submit($param);    
                    var_dump($data) ;                                        
                  }
                }
            }
        }
  }


  /**
   * 判断用户是web登录还是手机app登录
   * @param  [type]  $ClientType [description]
   * @return boolean             [description]
   */
  static public function isWebLogin($ClientType){
    if(strtolower($ClientType) == "wap"){
      return true;
    }
    return false;
  }
}