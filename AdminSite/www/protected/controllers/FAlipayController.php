<?php
require_once(dirname(__FILE__) . '/../config/ConfAlipay.php');
require_once(dirname(__FILE__) . '/../modules/ModuleTrans.php');
require_once(dirname(__FILE__) . '/../script/config/alipay.config.php');
require_once(dirname(__FILE__) . '/../script/lib/alipay_notify.class.php');

LunaLoader::import("luna_lib.http.HttpInterface");


class FAlipayController extends Controller 
{	
    static private $_detail_version = '';
    static private $_alipay_service = 'batch_trans_notify_no_pwd';

    static private $_alipay_config = array(
        'sign_type' => 'MD5' ,          //签名方式 不需修改
        'input_charset' => 'utf-8' ,                //字符编码格式 目前支持 gbk 或 utf-8
        // 'cacert' => getcwd().'\\cacert.pem' ,       //ca证书路径地址，用于curl中ssl校验     //请保证cacert.pem文件在当前文件夹目录中
        'transport' => 'https' ,
        );



	public function actionIndex(){
		$this->render('index');
	}

	public function actionTestAlipay(){
        // $applyId =  trim(Yii::app()->request->getParam('applyId',""));
        // $state = trim(Yii::app()->request->getParam('state',""));
        // $amount = 0;
        // $memo = '提现成功';
        // if($state == 4){
        //     $ret = ModuleTrans::promoterAlipayNotify($applyId,$state ,$amount,$memo);    
        // }else if($state == 5){
        //     $ret = ModuleTrans::promoterAlipayFailNotify($applyId,$state ,$memo);
        // }
        // var_dump($ret);
        // die();
        $clientAppVersion = "1.0.0";
          $clientAppVersion = str_replace('.', '', $clientAppVersion);
          $allowAppVersion = ConfAlipay::ALLOWFAILMSGAPPVERSION ;
          for($i = 1 ; $i < strlen($clientAppVersion) ; $i++){
            $allowAppVersion = $allowAppVersion * 10 ;  
          }
        
          var_dump($clientAppVersion);
          var_dump($allowAppVersion);
          var_dump($clientAppVersion < $allowAppVersion);


        // $ret = ModuleTrans::RechargeAlert();
        die();
        // $applyId = 904;
        // $state = 1 ;
        // $memo= "reject";
        // $ret = ModuleTrans::promoterAlipayFailNotify($applyId,$state,$memo);
        // var_dump($ret)        ;
        // die();
        // $memo = "ACCOUN_NAME_NOT_MATCHd";
        //     $fAppData=new FAppData();
        //     $failMesaage = $fAppData->getSystemConfigItem('apply.pay.error.map','',true);
        //     $failReson = '';
        //     if(is_array($failMesaage)){
        //         if(is_array($failMesaage['error_map']) && array_key_exists($memo,$failMesaage['error_map'])){
        //             $failReson = $failMesaage['error_map'][$memo];
        //         }
        //         if(empty($failReson) && array_key_exists($memo, $failMesaage)){
        //             $failReson = $failMesaage[$memo];      
        //         }   
        //         if(empty($failReson)){
        //             $failReson = $failMesaage['default'];      
        //         }           
        //     }

        // var_dump($failReson);

        // die();



        // $this->render('testAlipay');
        // $begin = $this->getCurrentTime();  
        // usleep(10000);
        // $end = $this->getCurrentTime();  
        // $spend = $end-$begin;  
        // var_dump("cost time:".$spend);        
        // die();




        // $amount = 17.60;
        // $camount = 5.62;
        // $appamount = 11.14;

        // $transBAmounts = $this->countBAmount($amount,$camount,$appamount);
        // var_dump($transBAmounts);
        // $transBAmounts = number_format($transBAmounts,2);
        // var_dump($transBAmounts);
        // die();
        // $applyId = 903;
        // $state = 4 ;
        // $amount = 12.33;
        // $memo = '提现成功';
        // $ret = ModuleTrans::promoterAlipayNotify($applyId,$state ,$amount,$memo);
        // var_dump($ret);
		// $fail_details = '1^voiline@gmail.com^\u4f55\u745c^3.00^F^^20141129442250812^20141129162542|4^13817674865^\u5f6d\u6653\u8f89^0.00^F^RECEIVE_MONEY_ERROR^20141129442250813^20141129162542|4^13817674865^\u5f6d\u6653\u8f89^0.00^F^RECEIVE_MONEY_ERROR^20141129442250814^20141129162542|';

		// $successDetails = '1^voiline@gmail.com^\u4f55\u745c^3.00^S^^20141130442514600^20141130003230|4^13817674865^\u5f6d\u6653\u8f89^0.50^S^^20141130442514601^20141130003230|5^17091266263^\u5f90\u6625\u71d5^1.50^S^^20141130442514602^20141130003230|113000322948615400UMAc^fh_platform@shandagames.com^\u4e0a\u6d77\u6570\u5409\u8ba1\u7b97\u673a\u79d1\u6280\u6709\u9650\u516c\u53f8^0.50^S^^20141130442514603^20141130003230|';
		// $LibAlipay = new LibAlipay;
		// $failDetail = $LibAlipay->analyseAlipayNotifySuccess($successDetails);
		// var_dump($failDetail);
		die();
		// $applyid=1;
		// $state=4;
		// $amount=12;
		// $memo='转账给推广员:';
		// $ret = ModuleTrans::promoterAlipayNotify($applyid,$state ,$amount,$memo);
		// var_dump($ret);
	}


    //http://dev.magt.centre.sdo.com/fAlipay/testComTrans
	public function actionTestComTrans(){
        
        if(!isset($_GET['d'])){
            $queryDate = 1 ;
        }else{
            $queryDate = $_GET['d'];
        }
        $begin = $this->getCurrentTime(); 
        $transInfo = ModuleTrans::comCompTrans($queryDate);
        if(is_array($transInfo)){
            if(array_key_exists('transAmounts', $transInfo) && array_key_exists('batchNum', $transInfo) && array_key_exists('detail', $transInfo)){
                $transAmounts = $transInfo['transAmounts'];
                $batchNum = $transInfo['batchNum'];
                $detail = $transInfo['detail'];
                if($transAmounts > 0 && $batchNum > 0 ){
                    var_dump($transAmounts);
                    var_dump($batchNum);
                    var_dump($detail);
                }                
                
            }
        }    	
	}



    public function actionTestProdTrans(){
        $begin = $this->getCurrentTime();  
        
        $fAppData = new fAppData();
        $ApplyList = $fAppData->getPromoterAliPayYesterdayList();
        $applyids = array();
        $batchCount = ConfAlipay::ALIPAYTRANSCOUNT;
        $i = 0 ;
        $pay_date = date("Ymd");
        $batch_no = $pay_date . $this->getBatchNo();    
        if(is_array($ApplyList)){
            $transAmount = 0 ;          //转给分红员
            $transFee = 0 ;             //提现手续费转给平台账户
            $detail = '';
            $transI = 0 ;
            foreach ($ApplyList as $key => $value) {
                $amount = $value['Amount'];
                $fee = $value['Fee'];
                $applyid = $value['ApplyId'];
                $applyids[] = $applyid;
                $alipayNo = $value['AliPayNo'];
                $promoter = $value['AliPayName'];
                $desc = "转账给推广员:".$promoter;
                // var_dump("applyid=".$applyid);
                // var_dump("alipayNo=".$alipayNo);
                // var_dump("amount=".$amount);
                // var_dump("fee=".$fee);

                if(!empty($applyid) && !empty($alipayNo) && !empty($amount) && !empty($fee) ){
                    $netAmount = $amount ;            //bcsub($amount,$fee,2);
                    $transAmount = bcadd($transAmount, $netAmount,2);
                    $transFee = bcadd($transFee, $fee,2);
                    $detail .= $applyid.'^'.$alipayNo.'^'.$promoter.'^'.$netAmount.'^'.$desc.'|';
                    
                    $i++;
                    $transI++;
                    if($i % $batchCount == 0 || $i == count($ApplyList)){
                        //如果提现手续费超过0元，则增加一笔转账数据detail
                        if($transFee > 0){
                            $detail .= $this->getBatchNo().'^'.ConfAlipay::WIDEMAILB.'^'.ConfAlipay::WIDACCOUNT_NAME.'^'.$transFee.'^提现手续费|';
                            $transAmount = bcadd($transAmount, $transFee,2);        //batch_fee 加上转账服务费
                            $transI++;
                        }                        
                        //按最大拼单数分别支付宝转账
                        var_dump("batch_no=".$batch_no);
                        var_dump("applyids=".$applyids);
                        var_dump("transAmount=".$transAmount);
                        var_dump("transI=".$transI);
                        var_dump("detail=".$detail);
                        var_dump("transFee=".$transFee);
                        $this->CTransP($batch_no,$applyids,$transAmount,$transI,$detail,$transFee);
                        $transFee = 0 ;
                        $transI = 0 ;
                        $detail = '';
                    }
                }                
            }

        }
        $end = $this->getCurrentTime();  
        $spend = $end-$begin;  
        var_dump("cost time:".$spend);
        LunaLogger::getInstance()->info("C2P cost time:".$spend);             
    }
    public function CTransP($batch_no, $applyids,$fee,$batch_num,$detail,$transFee){

        $email = ConfAlipay::WIDEMAILC;
        $account_name = ConfAlipay::WIDACCOUNT_NAME ;
        $pay_date = date("Ymd");
        $batch_fee = $fee;
        $notify_url = ConfAlipay::NOTIFY_PRO_URL ;
        
        //安全检验码，以数字和字母组成的32位字符
        // $alipay_config['key']           = ConfAlipay::KEYC;    
        // $alipay_config['sign_type']    = self::$_alipay_config['sign_type'];    
        // $alipay_config['input_charset']= self::$_alipay_config['input_charset'];
        // $alipay_config['cacert']    = getcwd().'//cacert.pem';

        //构造要请求的参数数组，无需改动
        $parameter = array(
                "service" => self::$_alipay_service ,
                "partner" => ConfAlipay::PARTNERC,
                "notify_url"    => $notify_url,
                "email" => $email,
                "account_name"  => $account_name,
                "pay_date"  => $pay_date,
                "batch_no"  => $batch_no,
                "batch_fee" => $batch_fee,
                "batch_num" => $batch_num,                
                "detail_version"   => self::$_detail_version,
                "detail_data"   => $detail,
        );
        var_export("parameter=".json_encode($parameter));
        LunaLogger::getInstance()->info("C2P parameter:".json_encode($parameter));        

        //建立请求
        // $alipaySubmit = new AlipaySubmit($alipay_config);
        // $sResult = $alipaySubmit->buildRequestHttp($parameter);
        // $syncResult = $this->getSyncResult($sResult);
        // $alipay = $syncResult['alipay'];
        // $is_success = $syncResult['is_success'];
        // $error = $syncResult['error'];        

        // if($alipay && $is_success == "T"){
        //     $fAppData = new fAppData();
        //     $state = ConfAlipay::ALIPAYAPPLIED;
        //     $memo = '已提交到支付宝';
        //     $rowCount = $fAppData->updatePromoterAlipayApply($applyids , $state , $memo );
        //     logResult("CTransP rowCount :".$rowCount);
        //     LunaLogger::getInstance()->info("C2P rowCount:".($rowCount));
        //     //记录提现手续费，企业账户间转账流水
        //     if($transFee > 0 ){
        //         $para = array(
        //             'OutAccount' => $email ,
        //             'InAccount' => ConfAlipay::WIDEMAILB ,
        //             'Amount' => $transFee ,
        //             'BatchNo' => $batch_no, 
        //             'State' => $state ,
        //             'ResultMemo' => '提现手续费转到平台账户B' ,
        //             );
        //         $rowCount = $fAppData->insertCompAlipayApply($para);                
        //     }

        // }else{
        //     logResult("C2P error :".$parameter . " result:" . $syncResult);
        //     LunaLogger::getInstance()->info("C2P error :".$parameter . " result:" . $syncResult);
        // }
    }



    public function actionTestProdAlipayNotify(){
            $fail_details = $_POST['fail_details'];
            $batch_no = $_POST['batch_no'];
            $fAppData=new FAppData();
            $state = ConfAlipay::ALIPAYAPPLIED;
            $LibAlipay = new LibAlipay;            
            //记录支付宝失败信息
            $failDetail = $LibAlipay->analyseAlipayNotifyFail($fail_details);
            LunaLogger::getInstance()->info('failDetail='.json_encode($failDetail));
            var_dump($failDetail);
            foreach ($failDetail as $key => $detail_value) {
                if(is_array($detail_value) && !empty($detail_value)){
                    $applyId = $detail_value['batchNo'];
                    $state = $detail_value['state'];
                    $memo = $detail_value['memo'];
                    if($batch_no == $applyId){
                        //C转B 回调
                        $ret = $fAppData->updateCompAlipayApply($applyId , $state ,$memo);
                    }else{
                        $ret = ModuleTrans::promoterAlipayFailNotify($applyId,$state,$memo);
                        var_dump("ret=".$ret);
                    }               
                    
                    LunaLogger::getInstance()->info('failret='.json_encode($ret));
                    usleep(1000);
                }
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
    private function countBAmount($amount , $camount , $appamount ){
        // var_dump("amount=".$amount);
        // var_dump("camount=".$camount);
        // var_dump("appamount=".$appamount);
        // die();
        return bcsub(bcsub($amount,$camount ,2) ,$appamount,2);
    }

    /**
     * 获取最新的流水号
     */
    protected function getBatchNo(){
        $cGuidManager = new CGuidManager();
        return $cGuidManager->GetGuid();
    }


	public function actionTestNotify(){

			$batch_no = $_POST['batch_no'];

			//批量付款数据中转账成功的详细信息
			$success_details = $_POST['success_details'];

			//批量付款数据中转账失败的详细信息
			$fail_details = $_POST['fail_details'];

			// logResult('batch_no='.$batch_no);
			// logResult('success_details='.$success_details);
			// logResult('fail_details='.$fail_details);
		
			// TODO ，根据异步返回参数，进行数据库操作

			//判断是否在商户网站中已经做过了这次通知返回的处理
			//如果没有做过处理，那么执行商户的业务程序
			//如果有做过处理，那么不执行商户的业务程序


            $fAppData=new FAppData();

			$LibAlipay = new LibAlipay;
			//记录支付宝成功信息
			$successDetail = $LibAlipay->analyseAlipayNotifySuccess($success_details);	

			logResult('successDetail='.$successDetail);
			foreach ($successDetail as $key => $detail_value) {
				$batchNo = $detail_value['batchNo'];
				$state = $detail_value['state'];
				$memo = $detail_value['memo'];
				var_dump($batchNo);
				var_dump($state);
				var_dump($memo);

				$ret = $fAppData->updateCompAlipayApply($batchNo , $state ,$memo);
				var_dump("ret=".$ret);
				logResult('successret='.$ret);
			}

			//记录支付宝失败信息
			$failDetail = $LibAlipay->analyseAlipayNotifyFail($fail_details);			
			logResult('failDetail='.$failDetail);
			foreach ($failDetail as $key => $detail_value) {
				$batchNo = $detail_value['batchNo'];
				$state = $detail_value['state'];
				$memo = $detail_value['memo'];
				$ret = $fAppData->updateCompAlipayApply($batchNo , $state ,$memo);
				logResult('failret='.$ret);
			}

			echo "success";		//请不要修改或删除
	
	}

    //microtime计算脚本执行时间
    public function getCurrentTime()
    {  
        list ($msec, $sec) = explode(" ", microtime());  
        return (float)$msec + (float)$sec;  
    } 

}