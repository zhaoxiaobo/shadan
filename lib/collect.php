<?php
(!defined('IN_JB') || IN_JB!==true) && exit('error');
if (!$memberLogined) common::goto_url('/member/login/?referer='.urlencode($nowurl));
template::initialize('./templates/default/'.$action.'/', './cache/default/'.$action.'/');//
$ops = array('collect');
($operation && in_array($operation, $ops)) || $operation = $ops[0];
switch ($operation) {
	case 'collect':
		$nav_bar = array(
			array('title' => $web_name, 'url' => $weburl.'/')
		);
		
		if($member['status']!=''){				
				if ($total = db::data_count('collect')) {
					$collectlist = db::get_list2('collect', '*', "uid='$uid'", 'cs_atime', $page, $pagesize);
					$multipage = multi_page::parse($total, $pagesize, $page, $base_url.'&page={page}', $pagestyle);
				}
				
				if ($total = db::data_count('rate')) {
					$ratelist = db::get_list2('rate', '*',  "uid='$uid'", 'atime', $page, $pagesize);
					$multipage = multi_page::parse($total, $pagesize, $page, $base_url.'&page={page}', $pagestyle);
				}
		}
				
		if ($rs = form::is_form_hash2()){			
		if ($memberLogined){
		    if($member['status']!=''){
                $style=$_POST['style'];	
				
				if($style==1){
				    $datas = form::get('nums', 'cs_url', 'cs_mark');
                    $datas['uid'] = $uid;
					$datas['user_name'] = $member['username'];
					$datas['user_tname']=$member['truename'];
				    $datas['user_tel']=$member['mobilephone'];
				    $datas['user_qq']=$member['qq'];
				    $datas['cs_atime']=$timestamp;
				    $datas['cs_ip']=$ipint;					
					$money = $datas['nums'] * $shouchangjiage ;
					if($money <= $memberfields['money']){
					    member_base::addMoney($uid, -$money,'购买收藏服务');
				        db::insert('collect',$datas);
					    echo "<script>alert('恭喜您购买收藏服务成功！');window.location.href='';</script>";
					}else{
					    echo "<script>alert('余额不足，请先充值！');window.location.href='/member/topup/';</script>";
					}
				}
				if($style==2){
				    $datas = form::get('days', 'url', 'ips');
					if($datas['ips']==200){
					    $price = $jiage_200ip;
					}
					if($datas['ips']==600){
					    $price = $jiage_600ip;
					}
					if($datas['ips']==1000){
					    $price = $jiage_1000ip;
					}
					if($datas['ips']==2000){
					    $price = $jiage_2000ip;
					}
					if($datas['ips']==6000){
					    $price = $jiage_6000ip;
					}
					if($datas['ips']==10000){
					    $price = $jiage_10000ip;
					}
					if($datas['ips']==30000){
					    $price = $jiage_30000ip;
					}

					$money = $datas['days'] * $price ;
					$datas['uid'] = $uid;
					$datas['user_name'] = $member['username'];
					$datas['user_tname']=$member['truename'];
				    $datas['user_tel']=$member['mobilephone'];
					$datas['user_qq']=$member['qq'];
				    $datas['price']=$money;
				    $datas['atime']=$timestamp;
				    $datas['ip']=$ipint;
				    if($datas['price'] <= $memberfields['money']){
					     member_base::addMoney($uid, -$money,'购买流量服务');
				         db::insert('rate',$datas);
					     echo "<script>alert('恭喜您购买流量服务成功！');window.location.href='';</script>";
					}else{
					    echo "<script>alert('余额不足，请先充值！');window.location.href='/member/topup/';</script>";
					}
				
				}
				
			}else{
             echo"<script>alert('您的手机号还未激活，请先激活手机号再购买！');window.location.href='/member/topup/';</script>";
			}
		}else{
		echo"<script>alert('您还未登陆，请先登录再进行操作！');window.location.href='/member/';</script>";
		}
		}
		include(template::load($operation));
	break;
}
?>