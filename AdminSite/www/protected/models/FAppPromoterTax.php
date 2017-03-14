<?php
/*
 * 计算税
 */

LunaLoader::import("luna_lib.util.LunaPdo");
require_once(dirname(__FILE__) .'/FAppData.php');

class FAppPromoterTax 
{
	private $_PDO_NODE_NAME="FHDatabase";
	
	private $_PRE_TAX_RATE="0.1755";
	private $_START_TAX_DATE="";		//开始计税时间
	private $_TAX_FEE_RATE=array();		//税率
	private $_fApp_Data;
	
	private $_start_date;			//计税周期 开始时间	
	private $_end_date;				//计税周期 结束时间
	private $_period;				//计税周期
	
	
	
	private function __construct()
	{
		date_default_timezone_set("Asia/Shanghai");
		$this->_fApp_Data=new FAppData();
		$taxCfg=$this->_fApp_Data->getSystemConfigItem("promoter.tixian.tax","",true);
		if(count($taxCfg)>0){
			$this->_START_TAX_DATE	=sprintf("%s 00:00:00",$taxCfg["StartDt"]);
			$this->_PRE_TAX_RATE	=$taxCfg["PreTaxedRated"];
			foreach ($taxCfg["Taxs"] as $itemRow){
				$this->_TAX_FEE_RATE[$itemRow["tax"]]=array("start" =>$itemRow["start"],"end" => $itemRow["end"] );
			}
		}
	}

	
	private static $_instance = null;
	public static function getInstance()
	{
		if (!isset(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	private function initDateRange($PeriodYear,$PeriodMonth)
	{
		$this->_period=sprintf("%s-%02d",$PeriodYear,$PeriodMonth);
		$this->_start_date=sprintf("%s-%02d-01 00:00:00",$PeriodYear,$PeriodMonth);
		if($PeriodMonth==12){
			$PeriodYear++;
			$PeriodMonth=1;
		}else{
			$PeriodMonth++;
		}
		$txtDate=sprintf("%s-%02d-01 00:00:00",$PeriodYear,$PeriodMonth);
		$nextMonthDay=date_create($txtDate);
		$this->_end_date=date("Y-m-d 23:59:59.999",$nextMonthDay->getTimestamp()-24*3600);
	}
	
	private function getAmountNoTax($PromoterId,&$PromoterNoTaxList)
	{
		$rowCount=count($PromoterNoTaxList);
		if($rowCount>0){
			for($index=0;$index<$rowCount;$index++){
				$rowItem=$PromoterNoTaxList[$index];
				if($rowItem["PromoterId"]==$PromoterId){
					$NoTaxAmount=$rowItem["Amount"];
					$PromoterNoTaxList=array_splice($PromoterNoTaxList, $index,1);
					return $NoTaxAmount;
				}	
			}
						
		}
		return "0.0";
	}
	
	public function Calculator($PeriodYear,$PeriodMonth,$PromoterId="")
	{
		$this->initDateRange($PeriodYear,$PeriodMonth);
		LunaLogger::getInstance()->info(sprintf("Tax calculator period:%s start:%s end:%s tax start:%s",$this->_period,$this->_start_date,$this->_end_date,$this->_START_TAX_DATE));
		$this->_fApp_Data->deletePromoterTax($this->_period);
		
		$PromoterTaxList	=$this->_fApp_Data->getPromoterAmountTax($this->_start_date,$this->_end_date,$this->_START_TAX_DATE,true,$PromoterId);
		$PromoterNoTaxList	=$this->_fApp_Data->getPromoterAmountTax($this->_start_date,$this->_end_date,$this->_START_TAX_DATE,false,$PromoterId);
		
		$PromoterTaxs=array();
		foreach ($PromoterTaxList as $itemRow){
			$p=array(
					"PromoterId" 	=> 	$itemRow["PromoterId"],
					"TaxPeriod"		=>	$this->_period,															//计税周期
					"PreTaxPercent"	=>	$this->_PRE_TAX_RATE,													//预交纳税利率
					"PreTaxFee"		=>	$itemRow["TaxFee"],														//预交纳税总额
					"AmountTax"		=>	bcadd($itemRow["Amount"], $itemRow["TaxFee"],2),						//应记税收入
 					"AmountNoTax"	=>	$this->getAmountNoTax($itemRow["PromoterId"],$PromoterNoTaxList),		//不记税收入
			 );
			foreach ($this->_TAX_FEE_RATE as $taxRatekey => $taxRateAmountRange){
				if($p["AmountTax"]>=$taxRateAmountRange["start"] && $p["AmountTax"]< $taxRateAmountRange["end"]){
					$p["TaxPercent"]	=	$taxRatekey;														//应执行纳税利率
					$p["TaxFee"]		=	bcmul($p["AmountTax"],$taxRatekey,2);								//应纳入税总额  舍弃厘
					$p["Refund"]		=	bcsub($p["PreTaxFee"], $p["TaxFee"],2);								//应返还纳税金额
				}
			}
			$PromoterTaxs[]=$p;
		}
		foreach ($PromoterNoTaxList as $itemRow){
			$p=array(
					"PromoterId" 	=> 	$itemRow["PromoterId"],
					"TaxPeriod"		=>	$this->_period,															//计税周期
					"PreTaxPercent"	=>	$this->_PRE_TAX_RATE,													//预交纳税利率
					"PreTaxFee"		=>	"0.00",																	//预交纳税总额
					"AmountTax"		=>	"0.00",																	//应记税收入
					"AmountNoTax"	=>	$itemRow["Amount"],														//不记税收入
					"TaxPercent"	=>	"0.0000",																//应执行纳税利率
					"TaxFee"		=>	"0.00",																	//应纳入税总额  舍弃厘
					"Refund"		=>	"0.00",																	//应返还纳税金额
			);
			$PromoterTaxs[]=$p;
		}
		foreach ($PromoterTaxs as $p)
		{
			$this->_fApp_Data->insertPromoterAmountTax($p["PromoterId"], $this->_period, $p["AmountTax"], 
					$p["AmountNoTax"],$this->_PRE_TAX_RATE, $p["PreTaxFee"], $p["TaxPercent"], $p["TaxFee"], $p["Refund"]);
		}
		LunaLogger::getInstance()->info("Tax calculator end.");
	}
	
}

?>