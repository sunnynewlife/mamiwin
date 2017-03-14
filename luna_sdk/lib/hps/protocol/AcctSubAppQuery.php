<?php

LunaLoader::import('luna_lib.hps.HpsModel');

/**
 *	账号子应用查询接口
 *
 *	@author xulong <xulong@snda.com>
 *
 *	对SDO HPS 账号子应用查询接口协议包装,原始SDO HPS 文档协议请参照
 *	文档中： 。{@link }
 *
 *	<pre><b>请求参数字段协议简述:</b>
 * 	<ol>IDType		账号类型,0数字账号。</ol>
 * 	<ol>POPTangID	账号ID。</ol>
 * 	<ol>StrGameType	类型AppId。</ol>
 *	</pre>
 *	<pre><b>响应结果字段协议简述:</b>
 *	<ol>return_code     查询结果，0--成功，其他错误，参阅 HPS错误。</ol>
 *	<ol>return_message  结果参考文字描述。</ol>
 *	<ol>data            查询结果，是一个数组，里面有如下字段：</ol>
 *		SubAppContent   账号子应用列表
 *	</pre>
 *	<pre><b>使用方式步骤:</b>
 *
 *		1. 通过静态方法实例化
 *		2. 设置必要的字段
 *		3. 提交给服务端，获得结果
 *	</pre>
 * <pre><b>调用代码实例:</b></pre>
 * <code>
 *		$model=AcctSubAppQuery::model();
 *		$model->POPTangID="1001";								//查询的数字账号
 *		$model->StrGameType="299";								//应用AppId
 *		$data=$model->submit();									//获得结果
 *</code>
 */
class AcctSubAppQuery extends HpsModel {
	
	/**
	 * 生成的账号子应用查询接口Model
	 * @param string  $className
	 * @return object Model的实例
	 */	
	public static function model($className=__CLASS__){
		$param=array(
			//查询的接口名称
			"method" => array(HpsModel::PARAM_KEY_CONST =>true,
						HpsModel::PARAM_KEY_VALUE => "authen.QuerySubApp"),
			//历史遗留			
			"GameType" => array(HpsModel::PARAM_KEY_CONST =>true,
						HpsModel::PARAM_KEY_VALUE => "1"),
			//历史遗留
			"AreaCode" => array(HpsModel::PARAM_KEY_CONST =>true,
						HpsModel::PARAM_KEY_VALUE => "1"),
			//历史遗留
			"StrAreaCode" => array(HpsModel::PARAM_KEY_CONST =>true,
						HpsModel::PARAM_KEY_VALUE => "-1"),

			//账号类型 缺省是0，数字帐号
			"IDType" => array(HpsModel::PARAM_KEY_REQUIRED =>true,
						HpsModel::PARAM_KEY_DEFAULTVALUE	=> "0"),
			//账号ID
			"POPTangID" => array(HpsModel::PARAM_KEY_REQUIRED =>true),
			//应用AppId	
			"StrGameType" => array(HpsModel::PARAM_KEY_REQUIRED =>true),
		);
		return parent::model("/apl_authen",$param,true,$className);
	}	
}