<?php
// error_reporting(0);

require_once(dirname(__FILE__) . '/../config/ConfAlipay.php');
require_once(dirname(__FILE__) . '/../modules/ModuleTrans.php');
require_once(dirname(__FILE__) . '/../script/config/alipay.config.php');
require_once(dirname(__FILE__) . '/../script/lib/alipay_notify.class.php');

class AlipayController extends CController 
{	
	public function actionTest(){
		$ret = ModuleTrans::getTransCFee();
		var_dump($ret);
		die();

			// $success_details = "201411221122142211270622006Tif^fh_promoters@shandagames.com^\u4e0a\u6d77\u6570\u5409\u8ba1\u7b97\u673a\u79d1\u6280\u6709\u9650\u516c\u53f8^0.01^S^^20141122438873390^20141122142212|"; //DEBUG
			$fAppData=new FAppData();
			$LibAlipay = new LibAlipay;
			//记录支付宝成功信息
			$successDetail = $LibAlipay->analyseAlipayNotifySuccess($success_details);			
			var_dump($successDetail);
			foreach ($successDetail as $key => $detail_value) {
				if(is_array($detail_value)){
					$batchNo = $detail_value['batchNo'];
					$state = $detail_value['state'];
					$memo = $detail_value['memo'];
					$ret = $fAppData->updateCompAlipayApply($batchNo , $state ,$memo);
					var_dump('successret='.$ret);					
				}

			}
			die();
	}

	/**
	 * 企业帐户间转账支付宝回调
	 * @return [type] [description]
	 */
	public function actionAlipayComNotify(){
		LunaLogger::getInstance()->info('post='.json_encode($_POST));
		$alipay_config['partner']		= ConfAlipay::PARTNERA;
		//安全检验码，以数字和字母组成的32位字符
		$alipay_config['key']			=  ConfAlipay::KEYA;
		//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
		//签名方式 不需修改
		$alipay_config['sign_type']    = strtoupper('MD5');
		//字符编码格式 目前支持 gbk 或 utf-8
		$alipay_config['input_charset']= strtolower('utf-8');
		//ca证书路径地址，用于curl中ssl校验
		//请保证cacert.pem文件在当前文件夹目录中
		$alipay_config['cacert']    = getcwd().'\\cacert.pem';
		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$alipay_config['transport']    = 'http';


		//计算得出通知验证结果
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyNotify();
		LunaLogger::getInstance()->info('verify_result='.$verify_result);

		if($verify_result) {//验证成功
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//请在这里加上商户的业务逻辑程序代

			//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
			
		    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
			
			//批次号
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
			LunaLogger::getInstance()->info('successDetail='.json_encode($successDetail));

			foreach ($successDetail as $key => $detail_value) {
				$batchNo = $detail_value['batchNo'];
				$state = $detail_value['state'];
				$memo = $detail_value['memo'];
				$ret = $fAppData->updateCompAlipayApply($batchNo , $state ,$memo);
				LunaLogger::getInstance()->info('successret='.$ret);
			}

			//记录支付宝失败信息
			$failDetail = $LibAlipay->analyseAlipayNotifyFail($fail_details);			
			LunaLogger::getInstance()->info('failDetail='.json_encode($failDetail));
			foreach ($failDetail as $key => $detail_value) {
				$batchNo = $detail_value['batchNo'];
				$state = $detail_value['state'];
				$memo = $detail_value['memo'];
				$ret = $fAppData->updateCompAlipayApply($batchNo , $state ,$memo);
				LunaLogger::getInstance()->info('failret='.$ret);
			}

			echo "success";		//请不要修改或删除

			//调试用，写文本函数记录程序运行情况是否正常
			

			//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
			
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		else {
		    //验证失败
		    LunaLogger::getInstance()->info("验证失败");
		    echo "fail";

		    //调试用，写文本函数记录程序运行情况是否正常
		    //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
		}		
	}

	/**
	 * 转账给分红员支付宝回调
	 * @return [type] [description]
	 */
	public function actionAlipayProNotify(){
		// logResult('post='.json_encode($_POST));
		LunaLogger::getInstance()->info('post='.json_encode($_POST));
		$alipay_config['partner']		= ConfAlipay::PARTNERC;
		//安全检验码，以数字和字母组成的32位字符
		$alipay_config['key']			=  ConfAlipay::KEYC;
		//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
		//签名方式 不需修改
		$alipay_config['sign_type']    = strtoupper('MD5');
		//字符编码格式 目前支持 gbk 或 utf-8
		$alipay_config['input_charset']= strtolower('utf-8');
		//ca证书路径地址，用于curl中ssl校验
		//请保证cacert.pem文件在当前文件夹目录中
		$alipay_config['cacert']    = getcwd().'\\cacert.pem';
		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$alipay_config['transport']    = 'http';


		//计算得出通知验证结果
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyNotify();
		if($verify_result) {//验证成功
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//请在这里加上商户的业务逻辑程序代

			//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
			
		    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
			
			//批次号
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
            $state = ConfAlipay::ALIPAYAPPLIED;
			$LibAlipay = new LibAlipay;
			// $success_details = '20^15221535061^\u674e\u9752^1.50^S^^20141205445435664^20141205140003|22^yuanbest@hotmail.com^\u8881\u94ba^2.00^S^^20141205445435666^20141205140003|23^wljsss@126.com^\u738b\u73b2^1.50^S^^20141205445435667^20141205140003|24^dumingest@163.com^\u675c\u660e^1.50^S^^20141205445435668^20141205140003|25^13764902990^\u9a6c\u6c5f\u6d9b^2.00^S^^20141205445435669^20141205140003|26^1064518998@qq.com^\u8d75\u9e4f\u5a1f^10.50^S^^20141205445435670^20141205140003|27^13764902990^\u9a6c\u6c5f\u6d9b^0.40^S^^20141205445435671^20141205140003|34^1124568904@qq.com^\u590f\u4fca\u6ce2^1.50^S^^20141205445435676^20141205140004|35^ufo549@sina.com^\u5305\u65b9\u4fca^1.50^S^^20141205445435677^20141205140004|36^1048620294@qq.com^\u7530\u536b\u91d1^1.50^S^^20141205445435678^20141205140004|37^18795807724^\u5b63\u5149\u5b87^1.50^S^^20141205445435679^20141205140004|38^killerguys01@163.com^\u9f9a\u65e6^1.50^S^^20141205445435680^20141205140004|40^18360577324^\u5f20\u4f1f^1.50^S^^20141205445435682^20141205140004|42^15812805743^\u83ab\u6c9b\u6866^1.50^S^^20141205445435684^20141205140004|44^18750204089^\u738b\u798f\u65b0^1.50^S^^20141205445435685^20141205140004|45^18232661149^\u4ed8\u6d77\u5b87^1.50^S^^20141205445435686^20141205140005|51^13914559866^\u738b\u8d85^1.50^S^^20141205445435688^20141205140005|52^18318101031^\u5f20\u9521\u777f^1.50^S^^20141205445435689^20141205140005|53^17839230530^\u738b\u6676^1.50^S^^20141205445435690^20141205140005|54^krq1227@hotmail.com^\u67ef\u6da6\u537f^1.50^S^^20141205445435691^20141205140005|55^646338161@qq.com^\u5f90\u653f^1.50^S^^20141205445435692^20141205140005|56^15004756802^\u8bb8\u6d77\u5a03^1.50^S^^20141205445435693^20141205140005|58^249333522@qq.com^\u5b5f\u4fca\u6587^1.50^S^^20141205445435695^20141205140006|59^erickelong816@hotmail.com^\u65bd\u514b\u9f99^1.50^S^^20141205445435696^20141205140006|60^931368036@qq.com^\u674e\u4e16\u6690^1.50^S^^20141205445435697^20141205140006|3^13817674865^\u5f6d\u6653\u8f89^0.50^S^^20141205445435656^20141205140006|10^voiline@gmail.com^\u4f55\u745c^10.00^S^^20141205445435657^20141205140006|11^17002101718^\u4f55\u745c^0.77^S^^20141205445435658^20141205140006|14^17002101718^\u4f55\u745c^2.50^S^^20141205445435659^20141205140006|16^jack___wang@163.com^\u738b\u4fca^9.00^S^^20141205445435661^20141205140006|17^juju550@163.com^\u5468\u7b60^1.50^S^^20141205445435662^20141205140007|19^iastar@21cn.com^\u5434\u6708\u7434^1.00^S^^20141205445435663^20141205140007|61^13468833711^\u9648\u5d07\u9633^1.50^S^^20141205445435698^20141205140007|62^15640525730^\u5218\u5e05^1.50^S^^20141205445435699^20141205140007|64^931368036@qq.com^\u674e\u4e16\u6690^1.50^S^^20141205445435701^20141205140007|65^18260886717^\u9ec4\u7ef4\u5fe0^1.50^S^^20141205445435702^20141205140007|67^18817805655^\u6731\u5a67\u96ef^1.50^S^^20141205445435703^20141205140008|70^13697987200^\u6f58\u6377^1.50^S^^20141205445435705^20141205140008|71^13576766210^\u9648\u5b9d\u9716^1.50^S^^20141205445435706^20141205140008|72^15676705770^\u9ec4\u5fd7\u5f3a^1.50^S^^20141205445435707^20141205140008|73^15221535061^\u674e\u9752^0.50^S^^20141205445435708^20141205140008|120514000126718800YqUQ^fh_platform@shandagames.com^\u4e0a\u6d77\u6570\u5409\u8ba1\u7b97\u673a\u79d1\u6280\u6709\u9650\u516c\u53f8^3.50^S^^20141205445435709^20141205140008|';
			// $fail_details = '21^liaofang02002@hotmail.com^\u5ed6\u65b9^1.50^F^ACCOUN_NAME_NOT_MATCH^20141205445435665^20141205140003|28^15801752968^\u8ff7\u96fe\u5566^1.50^F^ACCOUN_NAME_NOT_MATCH^20141205445435672^20141205140003|29^13501990818^\u859b\u6d77\u6d9b\u554a^1.50^F^ACCOUN_NAME_NOT_MATCH^20141205445435673^20141205140003|32^13361832763^\u4f55\u4e9a\u6797^3.50^F^ERROR_OTHER_NOT_REALNAMED^20141205445435674^20141205140003|33^xjl1985@163.com^xjl^1.50^F^ACCOUN_NAME_NOT_MATCH^20141205445435675^20141205140004|39^18221035160^\u8d75\u9e4f\u5a1f^8.50^F^ERROR_OTHER_NOT_REALNAMED^20141205445435681^20141205140004|41^18221035160^\u8d75\u9e4f\u5a1f^4.00^F^ERROR_OTHER_NOT_REALNAMED^20141205445435683^20141205140004|46^13946999600^\u51af\u5e7f\u6210^1.50^F^RECEIVE_USER_NOT_EXIST^20141205445435687^20141205140005|57^15952114544^\u89e3\u8363\u65ed^1.50^F^ACCOUN_NAME_NOT_MATCH^20141205445435694^20141205140006|15^13764902990^Tom Ma^1.50^F^ACCOUN_NAME_NOT_MATCH^20141205445435660^20141205140006|63^305680084@qq.com^\u5f20\u73b2^1.50^F^ACCOUN_NAME_NOT_MATCH^20141205445435700^20141205140007|69^13597379848^\u51af\u4e39^1.50^F^RECEIVE_USER_NOT_EXIST^20141205445435704^20141205140008|';
			//记录支付宝成功信息
			$successDetail = $LibAlipay->analyseAlipayNotifySuccess($success_details);			
			LunaLogger::getInstance()->info('successDetail='.json_encode($successDetail));

			foreach ($successDetail as $key => $detail_value) {
				if(is_array($detail_value) && !empty($detail_value)){
					$applyId = $detail_value['batchNo'];
					$state = $detail_value['state'];
					$memo = $detail_value['memo'];
					$amount = $detail_value['amount'];
					// $ret = $fAppData->updateProAlipayApply($applyId , $state ,$memo);
					if($batch_no == $applyId){
						//C转B 回调
						$ret = $fAppData->updateCompAlipayApply($applyId , $state ,$memo);
					}else{
						$ret = ModuleTrans::promoterAlipayNotify($applyId,$state ,$amount,$memo);
					}
					LunaLogger::getInstance()->info('successret='.json_encode($ret));
					usleep(1000);
				}				
			}

			//记录支付宝失败信息
			$failDetail = $LibAlipay->analyseAlipayNotifyFail($fail_details);
			LunaLogger::getInstance()->info('failDetail='.json_encode($failDetail));

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
					}				
					
					LunaLogger::getInstance()->info('failret='.json_encode($ret));
					usleep(1000);
				}
			}


			echo "success";		//请不要修改或删除

			//调试用，写文本函数记录程序运行情况是否正常

			//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
			
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		else {
		    //验证失败
		    LunaLogger::getInstance()->info("验证失败");
		    echo "fail";

		    //调试用，写文本函数记录程序运行情况是否正常
		    //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
		}		
	}
}
