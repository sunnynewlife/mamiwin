<?php

/**
 * 根据转账金额计算服务费
 * 上述转账过程中产生的服务费用（0-2万元（不包含2万元）/0.5元每笔；2万元-5万元（不包含5万元）/1元每笔；5万元以上的/3元每笔），从C账户付给支付宝平台。
 * @param  [type] $amount [description]
 * @return [type]         [description]
 */
function transRate($amount){
	if($amount > 0 &&　$amount < 20000)	{
		$fee = 0.5 ; 
	}else if($amount >= 20000 &&　$amount < 50000){
		$fee = 1 ;
	}else if($amount >= 50000){
		$fee = 3;
	}
	return $fee;

}

?>

