<?php

LunaLoader::import('luna_lib.hps.HpsModel');

/**
 *	数字账号查询接口
 *
 *	@author xulong <xulong@snda.com>
 *
 *	对SDO HPS 数字账号查询接口协议包装,原始SDO HPS 文档协议请参照
 *	文档中： 。{@link }
 *
 *	<pre><b>请求参数字段协议简述:</b>
 * 	<ol>keyValue		类型,别名。</ol>
 *   -2  除数字账号之外的任意账号，类型和别名之间使用半角逗号分隔
 *	</pre>
 *	<pre><b>响应结果字段协议简述:</b>
 *	<ol>return_code     查询结果，0--成功，其他错误，参阅 HPS错误。</ol>
 *	<ol>return_message  结果参考文字描述。</ol>
 *	<ol>data            查询结果，是一个数组，里面有如下字段：</ol>
 *			sdid     	数字账号
 *	</pre>
 *	<pre><b>使用方式步骤:</b>
 *
 *		1. 通过静态方法实例化
 *		2. 设置必要的字段
 *		3. 提交给服务端，获得结果
 *	</pre>
 * <pre><b>调用代码实例:</b></pre>
 * <code>
 *		$model=AcctQuery::model();
 *		$model->keyValue="-2,neilxu4test";						//查询的账号名
 *		$data=$model->submit();									//获得结果
 *</code>
 */
class AcctQuery extends HpsModel {
	
	/**
	 * 生成的数字账号查询接口Model
	 * @param string  $className
	 * @return object Model的实例
	 */	
	public static function model($className=__CLASS__){
		$param=array(
			//账号别名
			"keyValue" => array(HpsModel::PARAM_KEY_REQUIRED =>true),
		);
		return parent::model("/apl_account/account.querySndaId",$param,true,$className);
	}	
}