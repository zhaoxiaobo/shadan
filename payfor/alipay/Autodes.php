<?php
include(dirname(__FILE__).DIRECTORY_SEPARATOR.'../../class/index.php');
$key = 'daojinguangghaj2635895336688';
$data = $_POST["data_digest"];

$t2 = payfor_desjiemi::decrypt($data,$key);

$tsz = json_decode($t2, true);
$id=mb_convert_encoding($tsz['PaymentDesc'],"GB2312", "utf-8");
if(strlen($id)>20){
	$jiaoyihao=explode('-',$id);
	$id = $jiaoyihao[1];
}else{
	$id = $id;
}

$pay_money=mb_convert_encoding($tsz['Amount'],"GB2312", "utf-8");
$pay_money=(float)$pay_money;

$name=mb_convert_encoding($tsz['TradeNo'],"GB2312", "utf-8");
		
if($id){	
	if($pay_money>0){
		payfor_topup::complateOrderalipay($id,$pay_money,'通过支付宝充值'.$name);		
	}
}

$dqpath=dirname(dirname(dirname(__FILE__)));
$path=$dqpath.'\payfor\cs100.txt';
$handle=fopen($path,"a");		
		fwrite($handle,$id);
		fwrite($handle,strip_tags("\r\n"));	
		fwrite($handle,$pay_money);
		fwrite($handle,strip_tags("\r\n"));	
		fwrite($handle,$name);
		fwrite($handle,strip_tags("\r\n"));			
		fclose($handle);
?>