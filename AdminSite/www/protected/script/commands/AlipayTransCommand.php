<?php
/* 
 * 2014-11-06 13:54 
 * 1.每日0:05 统计前一日所以审核通过的提现申请，转账给分红员；
 * 2.每日0:10统计前一日分红员申请并审核通过的提现申请，转给分红员帐户、平台帐户 ；
 * 
 * yiic.bat help test
 * yiic.bat test
 */

defined('LUNA_SDK_PATH') || define('LUNA_SDK_PATH', dirname(__FILE__).'/../../../../../luna_sdk');
defined('LUNA_CONF_PATH') || define('LUNA_CONF_PATH', dirname(__FILE__).'/../../../../adminEvnCfgScript.xml');
require(LUNA_SDK_PATH . DIRECTORY_SEPARATOR . 'LUNA.php');
LunaLoader::$_FORCE_INCLUDE = true;
LunaLoader::import("luna_lib.util.LunaPdo");
LunaLoader::import("luna_lib.log.LunaLogger");

require_once(dirname(__FILE__).'/../../../../../luna_sdk/lib/util/CGuidManager.php');
require_once(dirname(__FILE__) . '/../../models/FAppData.php');
require_once(dirname(__FILE__) . '/../../modules/ModuleTrans.php');

require_once(dirname(__FILE__) . '/../../config/ConfAlipay.php');
require_once(dirname(__FILE__) . '/../../libraries/LibAlipay.php');
require_once(dirname(__FILE__) . '/../lib/alipay_submit.class.php');



class AlipayTransCommand extends ScriptBase
{
    static private $_detail_version = '';
    static private $_alipay_service = 'batch_trans_notify_no_pwd';

    static private $_alipay_config = array(
        'sign_type' => 'MD5' ,          //签名方式 不需修改
        'input_charset' => 'utf-8' ,                //字符编码格式 目前支持 gbk 或 utf-8
        // 'cacert' => getcwd().'\\cacert.pem' ,       //ca证书路径地址，用于curl中ssl校验     //请保证cacert.pem文件在当前文件夹目录中
        'transport' => 'https' ,
        );


    public function init()
    {

    }

    public function run($args)
    {
        //根据传入参数进行调用不同脚本
        if(!empty($args)){
            $arg = $args[0];
            switch ($arg){
                case "COM":                 //企业帐户间转帐
                    $this->CompTrans();
                    break;
                case "PROD":  //每日定时计算分红员分红，并转账
                    $this->PromoterTrans();
                    break;
                case "TEST" :               //测试支付宝接口
                    $this->testAlipay($args[1]);
                    break;
                default :
                    echo("wrong args.");
                    break;
            }            
        }
    }

    // 测试支付宝接口
    private function testAlipay($days){
        var_dump($days);
        
        $begin = $this->getCurrentTime();  
        $transInfo = ModuleTrans::comCompTrans($days,1);
        if(is_array($transInfo)){
            if(array_key_exists('transAmounts', $transInfo) && array_key_exists('batchNum', $transInfo) && array_key_exists('detail', $transInfo)){
                $transAmounts = $transInfo['transAmounts'];
                $batchNum = $transInfo['batchNum'];
                $detail = $transInfo['detail'];
                if($transAmounts > 0 && $batchNum > 0 ){
                    var_dump("transAmounts=".$transAmounts);
                    var_dump("batchNum=".$batchNum);
                    var_dump("detail=".$detail);
                    //$this->ATrans($transAmounts ,$batchNum ,$detail);
                }                
                
            }
        }

        $end = $this->getCurrentTime();  
        $spend = $end-$begin;  
        var_dump("A2BC cost time:".$spend);
        LunaLogger::getInstance()->info("A2BC cost time:".$spend);            
        die();  


    //     $begin = $this->getCurrentTime();  
        
    //     $email = ConfAlipay::WIDEMAILC ;
    //     $buyer_account_name = $email;
    //     $account_name = ConfAlipay::WIDACCOUNT_NAME ;
    //     $pay_date = date("Ymd");
    //     $batch_no = $pay_date . $this->getBatchNo();
    //     $batch_fee = 0.01;
    //     $batch_num = 1;
    //     // $detail_data = '201511170012^sunnynewlife@hotmail.com^杨恩睿^0.01^hello|';
    //     $detail_data = '201511170012^sunnynewlife@hotmail.com^杨恩睿^0.01^转账给推广员:郭新安';
    //     // $detail_version = '1.1';
    //     // $detail_data = $pay_date . $this->getBatchNo().'^'.ConfAlipay::WIDEMAILB.'^'.ConfAlipay::WIDACCOUNT_NAME.'^'.$batch_fee.'^转账给平台自留';
    //     LunaLogger::getInstance()->info("detail_data:".$detail_data);
    //     // $detail_data = $pay_date . $this->getBatchNo().'^'.'sunnynewlife@hotmail.com'.'^'.$batch_fee.'^转账给平台自留';
    //     //构造要请求的参数数组，无需改动
    //     $parameter = array(
    //             "service" => self::$_alipay_service ,
    //             "partner" => ConfAlipay::PARTNERC,
    //             "notify_url"    => ConfAlipay::NOTIFY_COMP_URL ,
    //             "email" => $email,
    //             // "buyer_account_name" => $buyer_account_name,
    //             "account_name"  => $account_name,
    //             "pay_date"  => $pay_date,
    //             "batch_no"  => $batch_no,
    //             "batch_fee" => $batch_fee,
    //             "batch_num" => $batch_num,
    //             "detail_version"   => self::$_detail_version,
    //             "detail_data"   => $detail_data
    //     );
    // LunaLogger::getInstance()->info("parameter:".print_r($parameter,true));
    // //安全检验码，以数字和字母组成的32位字符
    // $alipay_config['key']           = ConfAlipay::KEYC;    
    // $alipay_config['sign_type']    = self::$_alipay_config['sign_type'];    
    // // $alipay_config['input_charset']= strtolower('utf-8');
    // $alipay_config['input_charset']= self::$_alipay_config['input_charset'];
    // $alipay_config['cacert']    = getcwd().'//cacert.pem';


    // //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
    // // $alipay_config['transport']    = 'https';
       
    //    var_dump($alipay_config);


    //     //建立请求
    //     $alipaySubmit = new AlipaySubmit($alipay_config);

    //     $sResult = $alipaySubmit->buildRequestHttp(($parameter));
    //     LunaLogger::getInstance()->info("sResult:".$sResult);
    //     $result = $this->getSyncResult($sResult);
    //     $end = $this->getCurrentTime();  
    //     $spend = $end-$begin;  
    //     var_dump("cost time:".$spend);
    //     LunaLogger::getInstance()->info("cost time:".$spend);
    //     die();
        
    }

    //每天计算出前一天未转帐的分红员提现申请，拼单后转账
    protected function PromoterTrans(){
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
                if(!empty($applyid) && !empty($alipayNo) && !empty($amount) && $amount > 0  && !empty($fee) ){
                    $netAmount = $amount ;            //bcsub($amount,$fee,2);
                    $transAmount = bcadd($transAmount, $netAmount,2);
                    $transFee = bcadd($transFee, $fee,2);
                    $detail .= $applyid.'^'.$alipayNo.'^'.$promoter.'^'.$netAmount.'^'.$desc.'|';
                    
                    $i++;
                    $transI++;
                    if($i % $batchCount == 0 || $i == count($ApplyList)){
                        //如果提现手续费超过0元，则增加一笔转账数据detail
                        if($transFee > 0){
                            $detail .= $batch_no.'^'.ConfAlipay::WIDEMAILB.'^'.ConfAlipay::WIDACCOUNT_NAME.'^'.$transFee.'^提现手续费|';
                            $transAmount = bcadd($transAmount, $transFee,2);        //batch_fee 加上转账服务费
                            $transI++;
                        }                        
                        //按最大拼单数分别支付宝转账
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
    
    //调用支付宝接口从账户C转账至分红推广员
    public function CTransP($batch_no, $applyids,$fee,$batch_num,$detail,$transFee){

        $email = ConfAlipay::WIDEMAILC;
        $account_name = ConfAlipay::WIDACCOUNT_NAME ;
        $pay_date = date("Ymd");
        $batch_fee = $fee;
        $notify_url = ConfAlipay::NOTIFY_PRO_URL ;
        
        //安全检验码，以数字和字母组成的32位字符
        $alipay_config['key']           = ConfAlipay::KEYC;    
        $alipay_config['sign_type']    = self::$_alipay_config['sign_type'];    
        $alipay_config['input_charset']= self::$_alipay_config['input_charset'];
        $alipay_config['cacert']    = getcwd().'//cacert.pem';

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
        LunaLogger::getInstance()->info("C2P parameter:".json_encode($parameter));        
        //建立请求
        $alipaySubmit = new AlipaySubmit($alipay_config);
        $sResult = $alipaySubmit->buildRequestHttp($parameter);
        $syncResult = $this->getSyncResult($sResult);
        $alipay = $syncResult['alipay'];
        $is_success = $syncResult['is_success'];
        $error = $syncResult['error'];        

        if($alipay && $is_success == "T"){
            $fAppData = new fAppData();
            $state = ConfAlipay::ALIPAYAPPLIED;
            $memo = '已提交到支付宝';
            $rowCount = $fAppData->updatePromoterAlipayApply($applyids , $state , $memo );
            logResult("CTransP rowCount :".$rowCount);
            LunaLogger::getInstance()->info("C2P rowCount:".($rowCount));
            //记录提现手续费，企业账户间转账流水
            if($transFee > 0 ){
                $para = array(
                    'OutAccount' => $email ,
                    'InAccount' => ConfAlipay::WIDEMAILB ,
                    'Amount' => $transFee ,
                    'BatchNo' => $batch_no, 
                    'State' => $state ,
                    'ResultMemo' => '提现手续费转到平台账户B' ,
                    );
                $rowCount = $fAppData->insertCompAlipayApply($para);                
            }

        }else{
            logResult("C2P error :".$parameter . " result:" . $syncResult);
            LunaLogger::getInstance()->info("C2P error :".$parameter . " result:" . $syncResult);
        }
    }

    //先统计昨天申请提现的请求，进行拼单操作
    //去Promoter查询推广员支付宝帐号
    protected function getPromoterAliPayApplyList(){
        // $fAppData=new FAppData();
        // $ApplyList = $fAppData->getPromoterAliPayApplyList();
        // if(is_array($ApplyList)){
        //     $i = 0 ;
        //     $fee = 0 ;
        //     $detail = '';
        //     foreach ($ApplyList as $key => $value) {
        //         $i++;
        //         $amount = $value['Amount'];
        //         $applyid = $value['ApplyId'];
        //         $alipayNo = $value['AliPayNo'];
        //         $promoter = $value['PromoterName'];
        //         $fee = bcadd($fee, $amount);
        //         $desc = "转账给推广员".$promoter;
        //         $detail .= $applyid.'^'.$alipayNo.'^'.$amount.'^'.$desc.'|';
        //     }
        // }
        
        // $email = ConfAlipay::WIDEMAILC ;
        // $buyer_account_name = $email;
        // $notify_url = self::$_notify_url;
        // $account_name = ConfAlipay::WIDACCOUNT_NAME ;
        // $pay_date = date("Ymd");
        // $batch_no = $pay_date . $this->getBatchNo();
        // $batch_fee = $fee;
        // $batch_num = $i;
        // $detail_data = $detail;
        // //构造要请求的参数数组，无需改动
        // $parameter = array(
        //         "service" => self::$_alipay_service,
        //         "partner" => ConfAlipay::PARTNERC,
        //         "notify_url"    => self::$_notify_url,
        //         "email" => $email,
        //         "account_name"  => $account_name,
        //         "pay_date"  => $pay_date,
        //         "batch_no"  => $batch_no,
        //         "batch_fee" => $batch_fee,
        //         "batch_num" => $batch_num,
        //         "detail_version"   => self::$_detail_version,
        //         "_input_charset"    => trim(strtolower('utf-8'))
        // );

        // //根据接口同步xml，处理表数据，修改提现状态
        // $ret = $fAppData->updatePromoterAlipayApply($applyid,$state,$memo);
        // var_dump($ret);


    }



    //每日定时job执行，统计 前1天 分红下家 发生的冲值金额 ，从A转入C、B帐户 
    //先判断前日是否已经进行过转账
    public function CompTrans(){
        $queryDate = 1 ;
        $fAppData = new fAppData();
        $ret = $fAppData->queryCompAlipayApply(0);
        if(is_array($ret) && count($ret) <= 0){
            $begin = $this->getCurrentTime();
            $transInfo = ModuleTrans::comCompTrans($queryDate,1);
            if(is_array($transInfo)){
                if(array_key_exists('transAmounts', $transInfo) && array_key_exists('batchNum', $transInfo) && array_key_exists('detail', $transInfo)){
                    $transAmounts = $transInfo['transAmounts'];
                    $batchNum = $transInfo['batchNum'];
                    $detail = $transInfo['detail'];
                    if($transAmounts > 0 && $batchNum > 0 ){
                        var_dump("transAmounts=".$transAmounts);
                        var_dump("batchNum=".$batchNum);
                        var_dump("detail=".$detail);
                        $this->ATrans($transAmounts ,$batchNum ,$detail);
                    }                
                    
                }
            }
            $end = $this->getCurrentTime();  
            $spend = $end-$begin;  
            var_dump("A2BC cost time:".$spend);
            LunaLogger::getInstance()->info("A2BC cost time:".$spend);   
        }else{
            echo("Already Transed");
            LunaLogger::getInstance()->info("Already Transed");
        }
        die();       
    }

    //为节省转账费用，同时转账给B、C
    public function ATrans($amount ,$batchNum ,$detail){
        $email = ConfAlipay::WIDEMAILA;
        $account_name = ConfAlipay::WIDACCOUNT_NAME ;
        $pay_date = date("Ymd");
        $batch_no = $pay_date . $this->getBatchNo();
        $batch_fee = $amount;
        $batch_num = $batchNum;
        $notify_url = ConfAlipay::NOTIFY_COMP_URL ;
        $detail_data = $detail;

        //安全检验码，以数字和字母组成的32位字符
        $alipay_config['key']           = ConfAlipay::KEYA;    
        $alipay_config['sign_type']    = self::$_alipay_config['sign_type'];    
        $alipay_config['input_charset']= self::$_alipay_config['input_charset'];
        $alipay_config['cacert']    = getcwd().'//cacert.pem';

        //构造要请求的参数数组，无需改动
        $parameter = array(
                "service" => self::$_alipay_service ,
                "partner" => ConfAlipay::PARTNERA,
                "notify_url"    => $notify_url,
                "email" => $email,
                "account_name"  => $account_name,
                "pay_date"  => $pay_date,
                "batch_no"  => $batch_no,
                "batch_fee" => $batch_fee,
                "batch_num" => $batch_num,                
                "detail_version"   => self::$_detail_version,
                "detail_data"   => $detail_data,
        );
        LunaLogger::getInstance()->info("A2BC parameter:".json_encode($parameter));
        //建立请求
        $alipaySubmit = new AlipaySubmit($alipay_config);
        $sResult = $alipaySubmit->buildRequestHttp($parameter);
        LunaLogger::getInstance()->info("sResult:".($sResult));
        $syncResult = $this->getSyncResult($sResult);
        $alipay = $syncResult['alipay'];
        $is_success = $syncResult['is_success'];
        $error = $syncResult['error'];
        
        if($alipay && $is_success == "T"){
            $LibAlipay = new LibAlipay;
            $compDetail = $LibAlipay->analyseCompDetail($detail);          
            
            $fAppData=new FAppData();
            $state = ConfAlipay::ALIPAYAPPLIED;
            foreach ($compDetail as $key => $value) {
                $para = array(
                    'OutAccount' => $email ,
                    'InAccount' => $value['inAccount'] ,
                    'Amount' => $value['amount'] ,
                    'BatchNo' => $value['batchNo'] ,
                    'State' => $state ,
                    'ResultMemo' => $value['memo'] ,
                    );
                $rowCount = $fAppData->insertCompAlipayApply($para);
            }
        }else{

        }

    }

    //从总帐户A转帐到平台帐户B
    public function ATransB($fee){
        $email = ConfAlipay::WIDEMAILA;
        $account_name = ConfAlipay::WIDACCOUNT_NAME ;
        $pay_date = date("Ymd");
        $batch_no = $pay_date . $this->getBatchNo();
        $batch_fee = $fee;
        $batch_num = 1;
        $notify_url = ConfAlipay::NOTIFY_COMP_URL ;
        $inAccount = ConfAlipay::WIDEMAILB;
        $name = ConfAlipay::WIDACCOUNT_NAME;
        $detail_data = $batch_no.'^'.$inAccount.'^'.$name.'^'.$batch_fee.'^'.'转账给平台总账户';

        //安全检验码，以数字和字母组成的32位字符
        $alipay_config['key']           = ConfAlipay::KEYA;    
        $alipay_config['sign_type']    = self::$_alipay_config['sign_type'];    
        $alipay_config['input_charset']= self::$_alipay_config['input_charset'];
        $alipay_config['cacert']    = getcwd().'//cacert.pem';

        //构造要请求的参数数组，无需改动
        $parameter = array(
                "service" => self::$_alipay_service ,
                "partner" => ConfAlipay::PARTNERA,
                "notify_url"    => $notify_url,
                "email" => $email,
                "account_name"  => $account_name,
                "pay_date"  => $pay_date,
                "batch_no"  => $batch_no,
                "batch_fee" => $batch_fee,
                "batch_num" => $batch_num,                
                "detail_version"   => self::$_detail_version,
                "detail_data"   => $detail_data,
                // "_input_charset"    => trim(strtolower($alipay_config['input_charset']))
        );

        //建立请求
        $alipaySubmit = new AlipaySubmit($alipay_config);
        $sResult = $alipaySubmit->buildRequestHttp($parameter);
        $syncResult = $this->getSyncResult($sResult);
        $alipay = $syncResult['alipay'];
        $is_success = $syncResult['is_success'];
        $error = $syncResult['error'];
        
        if($alipay && $is_success == "T"){
            $fAppData=new FAppData();
            $state = ConfAlipay::ALIPAYAPPLIED;
            $para = array(
                'OutAccount' => $email ,
                'InAccount' => $inAccount ,
                'Amount' => $batch_fee ,
                'BatchNo' => $batch_no, 
                'State' => $state ,
                'ResultMemo' => $error ,
                );
            $rowCount = $fAppData->insertCompAlipayApply($para);
            logResult("ATransB rowCount :".$rowCount);
        }else{
            logResult("ATransB error :".$parameter . " result:" . $syncResult);
        }


    }

    //从总帐户转帐到分红帐户C
    public function ATransC($fee){
        $email = ConfAlipay::WIDEMAILA;
        $account_name = ConfAlipay::WIDACCOUNT_NAME ;
        $pay_date = date("Ymd");
        $batch_no = $pay_date . $this->getBatchNo();
        $batch_fee = $fee;
        $batch_num = 1;        
        $notify_url = ConfAlipay::NOTIFY_COMP_URL ;
        $inAccount = ConfAlipay::WIDEMAILC; 
        $name = ConfAlipay::WIDACCOUNT_NAME;
        $detail_data = $batch_no.'^'.$inAccount.'^'.$name.'^'.$batch_fee.'^'.'转账给推广员总账户';

        //安全检验码，以数字和字母组成的32位字符
        $alipay_config['key']           = ConfAlipay::KEYA;    
        $alipay_config['sign_type']    = self::$_alipay_config['sign_type'];    
        $alipay_config['input_charset']= self::$_alipay_config['input_charset'];
        $alipay_config['cacert']    = getcwd().'//cacert.pem';

        //构造要请求的参数数组，无需改动
        $parameter = array(
                "service" => self::$_alipay_service,
                "partner" => ConfAlipay::PARTNERA,
                "notify_url"    => $notify_url,
                "email" => $email,
                "account_name"  => $account_name,
                "pay_date"  => $pay_date,
                "batch_no"  => $batch_no,
                "batch_fee" => $batch_fee,
                "batch_num" => $batch_num,
                "detail_data"   => $detail_data,
        );

        //建立请求
        $alipaySubmit = new AlipaySubmit($alipay_config);
        $sResult = $alipaySubmit->buildRequestHttp($parameter);
        $syncResult = $this->getSyncResult($sResult);
        $alipay = $syncResult['alipay'];
        $is_success = $syncResult['is_success'];
        $error = $syncResult['error'];
        
        if($alipay && $is_success == "T"){
            $fAppData=new FAppData();
            $state = ConfAlipay::ALIPAYAPPLIED;
            $para = array(
                'OutAccount' => $email ,
                'InAccount' => $inAccount ,
                'Amount' => $batch_fee ,
                'BatchNo' => $batch_no, 
                'State' => $state ,
                'ResultMemo' => $error ,
                );
            $rowCount = $fAppData->insertCompAlipayApply($para);
            logResult("ATransC rowCount :".$rowCount);
        }else{
            logResult("ATransC error :".$parameter . " result:" . $syncResult);
        }



    }


    /**
     * 获取最新的流水号
     */
    protected function getBatchNo(){
        $cGuidManager = new CGuidManager();
        return $cGuidManager->GetGuid();
    }

    /**
     * 解释支付宝接口同步返回参数
     * @param  [type] $xml [description]
     * @return [type]      [description]
     */
    private function getSyncResult($xml){
        $result = array();
        if(!empty($xml)){
            $doc = new DOMDocument();
            $doc->loadXML($xml);        
            //解析XML
            if( ! empty($doc->getElementsByTagName( "alipay" )->item(0)->nodeValue) ) {
                $alipay = $doc->getElementsByTagName( "alipay" )->item(0)->nodeValue;
                $result['alipay'] = $alipay;
                if( ! empty($doc->getElementsByTagName( "is_success" )->item(0)->nodeValue) ) {
                    $is_success = $doc->getElementsByTagName( "is_success" )->item(0)->nodeValue;
                    $result['is_success'] = $is_success;
                } 
                if( ! empty($doc->getElementsByTagName( "error" )->item(0)->nodeValue) ) {
                    $error = $doc->getElementsByTagName( "error" )->item(0)->nodeValue;
                    $result['error'] = $error;
                } 
            }
        }

        return $result;
    }


    //microtime计算脚本执行时间
    public function getCurrentTime()
    {  
        list ($msec, $sec) = explode(" ", microtime());  
        return (float)$msec + (float)$sec;  
    } 


}
