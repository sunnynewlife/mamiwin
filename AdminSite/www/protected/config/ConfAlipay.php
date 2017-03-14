<?php 


class ConfAlipay{
	const WIDACCOUNT_NAME = '上海数吉计算机科技有限公司';		//付款账户名
	
	const WIDEMAILA = 'fh_all@shandagames.com';			//分红流入账号A
	const WIDEMAILB = 'fh_platform@shandagames.com';	//平台自留账号B
	const WIDEMAILC = 'fh_promoters@shandagames.com';	//分红大客户推广员分润账号C
    const WIDEMAILD = 'c2cplatform@souzhuangbei.com';   //手机分红员GBAO总账户，用于提现
	
	const PARTNERA = '2088711023905844';							//合作身份者ID 以2088开头的16位纯数字组成
	const PARTNERB = '2088711025803494';							//合作身份者ID 以2088开头的16位纯数字组成
	const PARTNERC = '2088711025621025';							//合作身份者ID 以2088开头的16位纯数字组成

	const KEYA = '99ymjo5llo3fr0jx1zcvzeear15h6s27';
	const KEYB = 'yeloah8expdyginq3dhwigratk2o59ko';
	const KEYC = '6u2xtonv8cnc3eqlt3scl3utdhzcbksr';

	const RATE_A = 0.985;
	// const RATE_B = 0 ;
	// const RATE_C = 0.3 ;

	const ALIPAYAPPLYNOTYET = 0 ;
	const ALIPAYAPPLYREFUSE = 1 ;				//审核不通过，拒绝提现申请
	const ALIPAYAPPLYAPPROVED = 2; 				//审核通过，待提交到支付宝
	const ALIPAYAPPLIED = 3 ;					//已提交到支付宝；
	const ALIPAYSUCCESS = 4 ;					//支付宝已到帐；
	const ALIAPYFAIL = 5 ;						//支付宝处理失败

 	const ALLOWFAILMSGAPPVERSION = 0.97 ;		//客户端允许接收提现失败消息版本
	const ALIPAYTRANSCOUNT = 2999; 			//拼单的最大笔数 ，留一笔为 转手续费给平台账户B

	const FEE1 = 0.5;						//0-2万元（不包含2万元）/0.5元每笔
	const FEE2 = 1;							//2万元-5万元（不包含5万元）/1元每笔
	const FEE3 = 3;							//5万元以上的/3元每笔

	const NOTIFY_COMP_URL		= "http://res.f.sdo.com/alipay/alipaycomnotify";    //企业间转账，交易过程中服务器通知的页面 要用 http://格式的完整路径，不允许加?id=123这类自定义参数
	const NOTIFY_PRO_URL		= "http://res.f.sdo.com/alipay/alipaypronotify";    //转账给分红员，交易过程中服务器通知的页面 要用 http://格式的完整路径，不允许加?id=123这类自定义参数
	//http://test.admin.f.sdo.com 		//测试域名

	static $partner		= "";					//合作身份者ID
	static $security_code 	= "";					//安全检验码
	static $email    		= "";					//付款支付宝账号
	static $account_name	= ""	;				//付款人真实姓名，参数email对应的真实姓名


	static $_input_charset	= "utf-8";						       //字符编码格式 目前支持 GBK 或 utf-8
	static $transport		= "http";						       //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
	static $sign_type		= "MD5";						       //加密方式 不需修改

	const ALIPAYNOTIFYCHANNELID = 13;			
	const ALIPAYNOTIFYMSGTYPEID = 2;			// 提现到支付宝提醒
	const ALIPAYFAILNOTIFYMSGTYPEID  = 3 ;		// 提现失败
	const ALIPAYNOTIFYCHANNELTYPEID = 2;			
	const ALIPAYNOTIFYAPPID = 10000;
}