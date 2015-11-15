<?php
include(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'index.php');
common::char_set();
common::nocache();
if (!$memberLogined) {
	echo '对不起，请先登陆后再操作！';
	exit;
}
$data = array();
     $maidian=$_POST['andmine'];
    if($maidian){
	        $sale=rand(100,115);
			$zhekousale=$sale/120;
			$num=50;
			$money=$num*$sale/100;
			if($money>$memberfields['money']){
				$data = array(
				'error'   => '余额不足！'
				);
				exit;
			}elseif($memberfields['money']<=0){
				$data = array(
				'error'   => '余额不足！'
				);
				exit;
			}
			member_base::addFabudian($uid, $num, 1,'欢乐猜地雷');
			member_base::addMoney($uid, -$money, '欢乐猜地雷');
			$surplus=$memberfields['money']-$money;
				$data = array(
				'sale'   => $sale,
				'zhekousale'   => common::formatMoney($zhekousale*10),
				'money'   => $money,
				'surplus'  => $surplus,
			);	
	}else{
	            $data = array(
				'error'   => '参数错误！'
			);
	}
echo json_encode($data);
?>