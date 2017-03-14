<?php
class LibAlipay{

	// //↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
	// //合作身份者id，以2088开头的16位纯数字
	// $alipay_config['partner']		= '';

	// //安全检验码，以数字和字母组成的32位字符
	// $alipay_config['key']			= '';

	//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

	//签名方式 不需修改
	static $alipay_config = array(
		'sign_type'=>'MD5',
		'input_charset'=>'UTF-8',
		// 'cacert'=>getcwd().'\\cacert.pem',
		'transport'=>'http',
		);

	static $_selfInstance;

	public static function getInstance ($alipay_config = null)
	{
	   if (null == self::$_selfInstance) {
	  		self::$_selfInstance = new self($alipay_config);
	   }

	   return self::$_selfInstance;
	}
	   


	public function alipayTrans($amount ,$detail ,$batch_num){

	}


	/**
	 * 根据规则分析支付宝回调接口参数
	 * @param  [type] $postStr [description]
	 * @return [type]          [description]
	 */
	public function analyseAlipayNotify($postStr){
		if(!is_array($postStr)){
			return false;
		}
	}

	/**
	 * 解析支付宝回调接口成功信息
	 * "success_details": "20141121112111213928913700J68j1^fh_platform@shandagames.com^\u4e0a\u6d77\u6570\u5409\u8ba1\u7b97\u673a\u79d1\u6280\u6709\u9650\u516c\u53f8^0.01^S^^20141121438391064^20141121112141|"
	 * 20141121112111213928913700J68j1^fh_platform@shandagames.com^上海数吉计算机科技有限公司^0.01^S^^20141121438391064^20141121112141|
	 * 流水号^收款方账号^收款账号姓名^付款金额^成功标识(S)^成功原因(null)^支付宝内部流水号^完成时间。每条记录以“|”间隔。
	 * @param  [type] $successDetails [description]
	 * @return [type]                 [description]
	 */
	public function analyseAlipayNotifySuccess($successDetails){
		
		$tmp = explode('|', $successDetails);
		$details = array();
		foreach ($tmp as $key => $wholevalue) {
			$detail = array();
			$tmps = explode('^',$wholevalue);
			foreach ($tmps as $key => $value) {
				if($key == 0 && !empty($value)){
					$detail = array_merge($detail,array('batchNo'=>$tmps[0]));
				}
				if($key == 4 && $value == "S"){
					$detail = array_merge($detail,array(
						'state'=>ConfAlipay::ALIPAYSUCCESS,
						));					
				}else if($key == 5){
					$detail = array_merge($detail,array(
						'memo'=>$value,
						));					
				}else if($key == 3){
					$detail = array_merge($detail,array(
						'amount'=>$value,
						));					
				}
			}
			if(!empty($detail)){
				$details[] = $detail;	
			}		
		}
		
		return $details;
	}


	/**
	 * 解析支付宝回调接口失败信息
	 * "fail_details": "20141121112111213928913700J68j1^fh_platform@shandagames.com^\u4e0a\u6d77\u6570\u5409\u8ba1\u7b97\u673a\u79d1\u6280\u6709\u9650\u516c\u53f8^0.01^S^^20141121438391064^20141121112141|"
	 * 20141121112111213928913700J68j1^fh_platform@shandagames.com^上海数吉计算机科技有限公司^0.01^S^^20141121438391064^20141121112141|
	 * 流水号^收款方账号^收款账号姓名^付款金额^失败标识(F)^失败原因^支付宝内部流水号^完成时间。每条记录以“|”间隔。
	 * @param  [type] $fail_details [description]
	 * @return [type]               [description]
	 */
	public function analyseAlipayNotifyFail($failDetails){
		// $failDetails = "0315006^xinjie_xj@163.com^星辰公司1^20.00^F^TXN_RESULT_TRANSFER_OUT_CAN_NOT_EQUAL_IN^200810248427065^20081024143651|";
		$tmp = explode('|', $failDetails);
	
		$details = array();
		foreach ($tmp as $key => $wholevalue) {
			$detail = array();
			$tmps = explode('^',$wholevalue);
			foreach ($tmps as $key => $value) {
				if($key == 0 && !empty($value)){
					$detail = array_merge($detail,array('batchNo'=>$tmps[0]));
				}
				if($key == 4 && $value == "F"){
					$detail = array_merge($detail,array(
						'state'=>ConfAlipay::ALIAPYFAIL,
						));					
				}else if($key == 5){
					$detail = array_merge($detail,array(
						'memo'=>$value,
						));					
				}
			}
			if(!empty($detail)){
				$details[] = $detail;	
			}	
		}
		return $details;
	}


	/**
	 * 解析支付宝企业账户间转账detail，以便于回写入数据库
	 * @param  [type] $details [description]
	 * @return [type]          [description]
	 */
	public function analyseCompDetail($compDetails){
		
		$tmp = explode('|', $compDetails);
		$details = array();
		foreach ($tmp as $key => $wholevalue) {
			$detail = array();
			$tmps = explode('^',$wholevalue);
			foreach ($tmps as $key => $value) {
				if($key == 0 && !empty($value)){
					$detail = array_merge($detail,array('batchNo'=>$tmps[0]));
				}else if($key == 1 && !empty($value)){
					$detail = array_merge($detail,array('inAccount'=>$tmps[1]));
				}else if($key == 2 && !empty($value)){
					$detail = array_merge($detail,array('inName'=>$tmps[2]));
				}else if($key == 3 && !empty($value)){
					$detail = array_merge($detail,array('amount'=>$tmps[3]));
				}else if($key == 4 && !empty($value)){
					$detail = array_merge($detail,array('memo'=>$tmps[4]));
				}
			}
			if(!empty($detail)){
				$details[] = $detail;	
			}		
		}
		
		return $details;
	}


    //根据转账金额计算转账费用 
    public function getTransationFee($amount){
        $fee = ConfAlipay::FEE1;
        if($amount > 0 && $amount < 20000){
            $fee = ConfAlipay::FEE1;
        }else if($amount >= 20000 && $amount < 50000){
            $fee = ConfAlipay::FEE2;
        }else if($amount >= 50000 ){
            $fee = ConfAlipay::FEE3;
        }
        return $fee; 
    }


    //对支付宝账号进行掩码
    //手机号，掩中间4位
    //邮箱，@前3位以上取前3位+***@
    //以下 取前1位+***@
	public function coverAlipayNo($alipayNo){
		$reAlipayNo = "";
		$separator = "@";
		$alipayNos = explode($separator,$alipayNo);
			
		if(count($alipayNos) > 1){
			$count = $alipayNos[0];
			if(strlen($count) > 3){
				$reAlipayNo = substr($count, 0, 3) . "***" . $separator . $alipayNos[1];
			}else{
				$reAlipayNo = substr($count, 0, 1) . "***" . $separator . $alipayNos[1];
			}
		}
		else if(is_numeric($alipayNo) && strlen($alipayNo) == 11 ){
			$reAlipayNo = substr($alipayNo, 0 ,4) . "***" . substr($alipayNo, 7,11);
		}else{
			$reAlipayNo = substr($alipayNo, 0 ,4) . "***" . substr($alipayNo, 7,11);
		}
		return $reAlipayNo ;
	}
}