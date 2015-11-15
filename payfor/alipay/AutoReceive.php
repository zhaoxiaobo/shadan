<?php
include(dirname(__FILE__).DIRECTORY_SEPARATOR.'../../class/index.php');
$miyao="916826";

$id = $_POST["PayMore"];
$pay_money = $_POST["PayJe"];
$key = $_GET["key"];
if($key==$miyao)
{

	if($id){
		payfor_topup::complateOrderalipay($id,$pay_money,'通过支付宝充值'.$name);
		echo "Success";
	}else{
        echo "信息验证失败！";
    }
}

?>