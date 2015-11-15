<?php

(!defined('IN_JB') || IN_JB!==true) && exit('error');
$m || $m = 'index';
$ms = array('index', 'add', 'tieBuyer', 'tieSeller', 'taskOut','upload', 'taskIn', 'tpl', 'viprob','CreateLaiLuMission','CreateMealMission','CreateCartMission','CreateBatchMission');
($m && in_array($m, $ms)) || $m = $ms[0];
$tplName.= '_'.$m;
$pName = '手机大厅';
$title = $pName;
$lanP = $lanP0.'tao_';
$minCredit = 0;
$taskStatus = true;
$taskId = 3;
if ($memberfields['credits'] < $minCredit) {
	language::get(array('name' => $lanP.'credit_less_than', 'args' => array('x' => $minCredit)));
	$taskStatus = false;
}
$thisUrl = $baseUrl0.'?m='.$m;
$lang = array(
	/*'status'     => array('暂停中', '已发布，等待接手人接手', '已接手，等待接手人绑定买号', '等待发布方审核', '已绑定买号，等待接手方支付', '已支付，等待发布人发货', '已发货，等待收货与好评', '已确认，等待卖家确认', '交易完成'),*/
 	'status'     => array(
		'暂停中', 
		'等待接手', 
		'已接手，等待绑定买号', '等待发布方审核', '等待接手人对商品付款', '已支付，待核对快递地址', '准备发货，等待快递单号', '已支付，等待发布人发货', 
		'已发货，等待收货与好评', '已确认，等待卖家核实货款', '交易完成'),
	'isPriceFit' => array('不需要', '需要'),
	'times'      => array('马上好评', '24小时好评', '48小时好评', '72小时好评','96小时好评','120小时好评','144小时好评','168小时好评'),
	'times_ico'  => array('&nbsp;', '&nbsp;', '<span class=\'task_24\' title=\'24小时好评\'></span>', '<span class=\'task_48\' title=\'48小时好评\'></span>', '<span class=\'task_72\' title=\'72小时好评\'></span>', '<span class=\'task_96\' title=\'96小时好评\'></span>', '<span class=\'task_120\' title=\'120小时好评\'></span>', '<span class=\'task_114\' title=\'114小时好评\'></span>', '<span class=\'task_168\' title=\'168小时好评\'></span>'),
	'scores'     => array('', 1 => '全部打1分', 2 => '全部打2分', 3 => '全部打3分', 4 => '全部打4分', 5 => '全部打5分'),
	'bStatus'    => array('正常', '危险', '挂起', '失效', '锁定', '暂停', '收藏'),
	'cStatus'    => array('双方未评分', '接手方已评分', '发布方已评分', '双方已评分')
);
$allCount = db::data_count('task', "type='$taskId'");
$complateCount = db::data_count('task', "type='$taskId' and status='10'");
if (!($taskBook = task_book::getConfig($taskId, $uid))) {
	$taskBook = array('ing' => 0);
}
//增加的
$flowAll = (int)db::one_one('task_reflow', 'sum(flowComplate)', "type='$taskId'");

if ($m == 'index') {
	$bbsNav[] = $pName;
} else {
	$bbsNav[] = array('name' => $pName, 'url' => $baseUrl0);
}

//增加进来的
task_reflow::upCache();
function __parseLimitCount($str){
	$rs = array();
	foreach (explode(';', $str) as $v) {
		list($count, $point, $check) = explode(',', $v);
		$count = intval($count);
		$point = common::formatMoney($point);
		$check = isset($check) ? ($check == '1' ? true : false) : false;
		$rs[] = array(
			'count' => $count,
			'point' => $point,
			'check' => $check
		);
	}
	return $rs;
}


switch ($m) {
	case 'index':
	
		if ($memberfields['vip']==1) {
			$paymentshangbi = cfg::getFloat('payment', 'vip1shangbi');//一级会员信币价格
		} elseif ($memberfields['vip']==2) {
			$paymentshangbi = cfg::getFloat('payment', 'vip2shangbi');//钻石会员信币价格
		}elseif ($memberfields['vip']==3) {
			$paymentshangbi = cfg::getFloat('payment', 'vip3shangbi');//皇冠会员信币价格
		} else {
			$paymentshangbi = cfg::getFloat('payment', 'membershangbi');//普通会员信币价格
		}
	
		if ($total = task_buyer::total(1, $status2)) {
		    $isreal = db::data_count('buyers', "type='1' and uid='$uid' and isreal='1' and status='0'");
			$real = db::data_count('buyers', "type='1' and uid='$uid' and isreal='0' and status='0'");
			$bList = task_buyer::getList(1, $uid, $status2);
			$multipage = multi_page::parse($total, $pagesize, $page, $thisUrl.'&status='.$status.'&page={page}', $pageStyle, 10, 'member1');
		}
		$dangqianhuiyuanshu = db::data_count('members', "");
		$todayjierenwucount = db::data_count('task', "btimestamp>=$today_start and btimestamp<=$today_end and type='3'");
		$yest1 = $today_start - 86400;
		$yest2 = $today_start;
		$yestedayjierenwucount = db::data_count('task', "btimestamp>=$yest1 and btimestamp<=$yest2 and type='3'");
		$leijicount = db::data_count('task', "status='10' and type='3' and buid='$uid'");
		$alipay = db::get_list2('payfor_interface', '*', "ename='alipay'", 'time desc');
		$tenpay = db::get_list2('payfor_interface', '*', "ename='tenpay'", 'time desc');
		if($memberfields[credits]==0){
		$credits='新手会员';
		}elseif($memberfields[credits] < 100){
		$credits='金牌会员';
		}elseif($memberfields[credits] < 1000){
		$credits='白金会员';
		}elseif($memberfields[credits] < 5000){
		$credits='黄金会员';
		}elseif($memberfields[credits] < 10000){
		$credits='钻石会员';
		}elseif($memberfields[credits] < 100000){
		$credits='皇冠会员';
		}
		if($memberfields[vip]==1){
		 $vip='一级VIP';
		}elseif($memberfields[vip]==2){
		 $vip='钻石VIP';
		}elseif($memberfields[vip]==3){
		 $vip='皇冠VIP';
		}
		//自动任务参数
		$type = 3;
	    $data = task_book::getConfig($type, $uid);
		if(!$data['point']){
		    $data['point']=0.00;
		}
		if($data['priceHigh']<=0){
		    $data['priceHigh']=999999.00;
		}
		if(!$data['priceLow']){
		    $data['priceLow']=0.00;
		}
		if($data['times']==''){
		    $data['times']=10;
		}
		if($data['isAuto']==''){
		    $data['isAuto']=3;
		}
		$now_data = date('Y-m-d',$today_start);
		$auto_data = date('Y-m-d',$data['time']);
		if($now_data != $auto_data){
		db::update('task_book', "isAuto=3,time='$timestamp'", "uid='$uid'");
		}
		
	break;
	case 'add':
		$alipay = db::get_list2('payfor_interface', '*', "ename='alipay'", 'time desc');
		$tenpay = db::get_list2('payfor_interface', '*', "ename='tenpay'", 'time desc');
		if ($memberfields['vip']==1) {
			$paymentshangbi = cfg::getFloat('payment', 'vip1shangbi');//一级会员信币价格
		} elseif ($memberfields['vip']==2) {
			$paymentshangbi = cfg::getFloat('payment', 'vip2shangbi');//钻石会员信币价格
		}elseif ($memberfields['vip']==3) {
			$paymentshangbi = cfg::getFloat('payment', 'vip3shangbi');//皇冠会员信币价格
		} else {
			$paymentshangbi = cfg::getFloat('payment', 'membershangbi');//普通会员信币价格
		}
		
		$ensurePoint = 0.3;
		if ($memberfields['sellers1'] == 0 || db::data_count('sellers', "type='1' and status='1'") == 0) {
			common::setMsg('对不起，请先绑定掌柜', 'indexMessage');
			common::goto_url('/task/tao/?m=tieSeller');
		}
		checkPwd2();
		$members = member_base::getMember($uid);
		if ($isHot && !$isVip) error::bbsMsg('对不起，只有VIP会员才可以使用掌柜热卖发布功能');
		if ($rs = form::is_form_hash2()) {
			$datas = form::get('nickname', 'itemurl', 'wangwang','visitTip', 'visitKey', array('visitWay', 'int'), array('price', 'float'), array('isPriceFit', 'int'), array('pointExt', 'float'), array('times', 'int'),  array('scores', 'int'), array('isRemark', 'int'), 'remark', array('isMobile', 'int'), array('cbxIsWX', 'int'), array('isShare', 'int'),array('isAddress', 'int'), 'address',array('share', 'int'), 'tips', array('isDbssc', 'int'),array('isVerify', 'int'), array('issphb', 'float'),'sphbdz',array('isLimit', 'int'), array('limit', 'int'), array('chssp', 'int'),array('isNoword', 'int'),
				array('isReal', 'int'), array('cbxIsTip', 'int'),'txtBuyCount','cbIsHiddenName','cbIsNoneAssess','txtAreaService','txtAccount','txtMobile','txtSpecs','ddlDeliver','cbxName','cbxMobile','cbxcode',
				array('realname', 'int'),'FMaxBTSCount','cbxIsTaoG','txtTaoG','isawb','expressfull','isSign',
				array('isChat', 'int'), array('isChatDone', 'int'),'Express','isLimitCity','isMultiple','province',
				array('isStar', 'int'),'qq','cbxAddress',array('expressTM', 'int'),
				array('lvlStar', 'int'),//星级别
				array('isEnsure', 'int'),//是否商保
				array('shopcoller', 'int'),array('fabuNum', 'int'),array('fabuNumx', 'int'),array('fabuFenzhong', 'int'),
				array('ispinimage', 'int'),array('iskongbao', 'int'),array('txtMinMPrice', 'float'),
				array('ensurePoint', 'float'),array('maihaoxinyu', 'int'),array('nopingtaick', 'int'),
				array('maihaodengji', 'int'), array('goumailianjie', 'int'),array('goumaishl', 'int'),
				array('isScore', 'int'), array('scoreLvl', 'int'),array('isCompare', 'int'), array('contrast', 'int'),array('stopDsTime', 'int'), array('stopTime', 'int'),array('isViewEnd', 'int'),array('iscomplain', 'int'), array('complain', 'int'), array('isCredit', 'int'), array('creditLvl', 'int'), array('isGood', 'int'), array('goodLvl', 'int'), array('isBlack', 'int'), array('blackLvl', 'int'), array('isFame', 'int'), array('fameLvl', 'int'), array('isPlan', 'int'), 'planDate');
			
			if ($isHot) $count = (int)$_POST['count'];
			else $count = 1;
			if ($rs === true) {
				$rs = task_new::add($datas, $uid, $count);
			}
			if ($rs === true) {
				//保存模板
				$datas2 = form::get(array('isTpl', 'int'), 'tplTo');
				$datas2 && extract($datas2);
				if ($isTpl && $tplTo) {
					$tplTo = common::cutstr($tplTo, 64);

		$membersfieldstie = member_base::getMemberFields($uid);		
		if ($membersfieldstie[vip]==1) $maxTplCount = cfg::getInt('payment', 'vip1maxTpl');
		if ($membersfieldstie[vip]==2) $maxTplCount = cfg::getInt('payment', 'vip2maxTpl');
		if ($membersfieldstie[vip]==3) $maxTplCount = cfg::getInt('payment', 'vip3maxTpl');
		if ($membersfieldstie[vip]==0) $maxTplCount = cfg::getInt('payment', 'vip0maxTpl');
					
					if (db::data_count('task_tpl', "uid='$uid'") < $maxTplCount) {
						db::insert('task_tpl', array(
							'type'      => $taskId,
							'uid'       => $uid,
							'isAdds'    => 0,
							'stype'      => 1,
							'name'      => $tplTo,
							'datas'     => addslashes(serialize($datas)),
							'timestamp' => $timestamp
						));
					}
				}
				// THE END
				common::setMsg('发布成功，需要购买快递单的卖家，请购买快递单号！不需要的请点击<a href="/task/new/?m=add"  style="color:red;">返回继续发布任务</a>或者<a href="/task/new/?m=taskOut&t=all"  style="color:red;">查看已发布任务</a>');
				common::goto_url($baseUrl0.'?m=taskOut&t=all');
			} else {
				$indexMessage = language::get($rs);
			}
		}
		$tplList = db::get_list2('task_tpl', 'id,name', "type='$taskId' and uid='$uid' and stype='1'", 'timestamp desc');
		if (($tplId = (int)$tplId) && !$datas) {
			$datas = db::one_one('task_tpl', 'datas', "type='$taskId' and uid='$uid' and stype='1' and id='$tplId'");
			$datas && $datas = unserialize($datas);
		}
		$sellers = task_seller::getSellers($uid, 1, 1);
		$eList = task_express::getList();
	break;
	
	case 'taskIn':
		$dangqianhuiyuanshu = db::data_count('members', "");
		$todayjierenwucount = db::data_count('task', "btimestamp>=$today_start and btimestamp<=$today_end and type='3'");
		$yest1 = $today_start - 86400;
		$yest2 = $today_start;
		$yestedayjierenwucount = db::data_count('task', "btimestamp>=$yest1 and btimestamp<=$yest2 and type='3'");
		$leijicount = db::data_count('task', "status='10' and type='3' and buid='$uid'");
		$alipay = db::get_list2('payfor_interface', '*', "ename='alipay'", 'time desc');
		$tenpay = db::get_list2('payfor_interface', '*', "ename='tenpay'", 'time desc');
		if ($memberfields['vip']==1) {
			$paymentshangbi = cfg::getFloat('payment', 'vip1shangbi');//一级会员信币价格
		} elseif ($memberfields['vip']==2) {
			$paymentshangbi = cfg::getFloat('payment', 'vip2shangbi');//钻石会员信币价格
		}elseif ($memberfields['vip']==3) {
			$paymentshangbi = cfg::getFloat('payment', 'vip3shangbi');//皇冠会员信币价格
		} else {
			$paymentshangbi = cfg::getFloat('payment', 'membershangbi');//普通会员信币价格
		}
		
		checkPwd2();
		if ($new) {
			if (!$taskStatus && !$memberfields['exam']) {
				common::setMsg('平台认为您的接手经验尚浅，为了您的安全，强烈要求您先通过平台的小小测试');
				common::goto_url('/member/exam/');
			}
			$rs = task_new::in($new, $uid);
			if ($rs === true) {
				if ($memberTask['inComplate'.$taskId] < 5) common::setcookie('showTaskTip', 1);
				common::goto_url($thisUrl.'&t=ing');
			} else {
				common::goto_url($referer, true, $rs);
			}
		} else {
			$bbsNav[] = '已接任务';
			$t || $t = 'ing';
			if ($out){
				$rs = task_new::out($out, $uid);
				if ($rs === true){
					common::setMsg('退出成功');
					common::goto_url($thisUrl.'&t=ing');
				} else{
					common::setMsg($rs, 'indexMessage');
					common::goto_url($referer, true);
				}
			} elseif ($pay){
				$rs = task_new::pay($pay, $uid);
				if ($rs === true){
					common::setMsg('您已经确认支付，请联系发布方发货！');
					common::goto_url($referer, true);
				} else{
					common::setMsg($rs, 'indexMessage');
					common::goto_url($referer, true);
				}
			} elseif ($shenhe){
				$shenhetimexq = cfg::getInt('sys', 'shenhetime');				
				$task = db::one('task', '*', "id='$shenhe'");
				if((($timestamp-$task['btimestamp'])-($shenhetimexq*60))>0){				
					if ($task['status']==3) {
						if (db::update('task', array('ttimestamp' => $timestamp + 900, 'status' => 4), "id='$shenhe'")) {
							task_base::addLog($shenhe, '任务审核', '发布方'.$task['susername'].'超时未审核，系统默认审核了任务{id}的接手方{busername}');
							$msg = '您在'.language::get('qu'.$task['type']).'区接手的任务“'.$task['id'].'”，已通过审核';
							member_base::sendPm($task['buid'], $msg, '接手的任务“'.$task['id'].'”已通过审核', 'in_verify');
							member_base::sendSms($task['buid'], $msg, 'in_verify');
							
							common::setMsg('超时未审核，系统默认审核了该任务，请进行下一步付款工作！');
							common::goto_url($baseUrl0.'?m=taskIn&t=all');
						}
						
					}
				}
			}elseif ($fahuo){
				global $timestamp;
				$task = db::one('task', '*', "id='$fahuo'");
				$fahuotimexq = cfg::getInt('sys', 'fahuotime');
				if ($task['ttimestamp']>0){
					$timefahuojisuan=(($timestamp-$task['ttimestamp'])-($fahuotimexq*60));
				}else
				{
					$timefahuojisuan=(($timestamp-$task['btimestamp'])-($fahuotimexq*60));
				}
				if($timefahuojisuan>0){
										
						if ($task['status']==7) {
							$etimestamp = $timestamp;
							if ($task['times'] == 1) $etimestamp += 24 * 3600;
							elseif ($task['times'] == 2) $etimestamp += 48 * 3600;
							elseif ($task['times'] == 3) $etimestamp += 72 * 3600;
							elseif ($task['times'] == 4) $etimestamp += 96 * 3600;
							elseif ($task['times'] == 5) $etimestamp += 120 * 3600;
							elseif ($task['times'] == 6) $etimestamp += 144 * 3600;
							elseif ($task['times'] == 7) $etimestamp += 168 * 3600;
							if (db::update('task', array(
								'etimestamp' => $etimestamp,
								'status'     => 8
							), "id='$fahuo'")) {
								//db::update('membertask', 'outing3=outing3-1,outExpire3=outExpire3+1', "uid='$task[suid]'");
								//db::update('membertask', 'ining3=ining3-1,inExpire3=inExpire3+1', "uid='$task[buid]'");
								task_base::addLog($fahuo, '确认发布', '发布方'.$task['susername'].'超时未发货，系统默认发货了任务{id}的发货');
								$msg = '您在'.language::get('qu'.$task['type']).'区接手的任务“'.$task['id'].'”，卖家已发货';
								member_base::sendPm($task['buid'], $msg, '接手的任务“'.$task['id'].'”卖家已发货', 'in_send');
								member_base::sendSms($task['buid'], $msg, 'in_send');
								common::setMsg('超时未发货，系统默认发货了该任务，请进行下一步操作！');
								common::goto_url($baseUrl0.'?m=taskIn&t=all');
							}
							common::goto_url($baseUrl0.'?m=taskIn&t=all');
						}
				}
			}  elseif ($unpay){
				$rs = task_new::unpay($unpay, $uid);
				if ($rs === true) {
					common::setMsg('任务已经返回未支付状态！');
					common::goto_url($referer, true);
				} else {
					common::setMsg($rs, 'indexMessage');
					common::goto_url($referer, true);
				}
			} elseif ($receive){
				$rs = task_new::receive($receive, $uid);
				if ($rs === true){
					common::setMsg('已经确认收货，等待卖家确认');
					common::goto_url($referer, true);
				} else {
					common::setMsg($rs, 'indexMessage');
					common::goto_url($referer, true);
				}
			}elseif ($queren){
				$task = db::one('task', '*', "id='$queren'");
				global $timestamp;
				$querentimexq = cfg::getInt('sys', 'querentime');				
				if ($task['status']==9 && $task['shoutimestamp']>0) {
					$timequerenjisuan = (($timestamp-$task['shoutimestamp'])-($querentimexq*60));
					if($timequerenjisuan>0){
						if (db::update('task', array(
							'ctimestamp' => $timestamp,
							'status'     => 10
						), "id='$queren'")) {
	
							//db::update('membertask', 'outExpire3=outExpire3-1,outComplate3=outComplate3+1,outComplate=outComplate+1', "uid='$task[suid]'");
							//db::update('membertask', 'inExpire3=inExpire3-1,inComplate3=inComplate3+1,inComplate=inComplate+1', "uid='$task[buid]'");
							task_seller::addComplate($task['sid']);
							task_buyer::addComplate($task['bid']);
							if ($task['nopingtaick']==0) {
								member_base::addMoney($task['buid'], $task['price'], '完成任务'.$task['id']);
							}
							member_base::addPoinths($queren);
							member_base::addCredit($task['buid'], member_base::isVip($task['buid'])?6:5, '完成任务'.$task['id']);
							task_base::addLog($queren, '核实货款', '发布方'.$task['susername'].'超时未核实货款，系统默认核实货款任务{id}');
	
							$msg = '您在'.language::get('qu'.$task['type']).'区接手的任务“'.$task['id'].'”，卖家已核实货款';
							member_base::sendPm($task['buid'], $msg, '接手的任务“'.$task['id'].'”卖家已核实货款', 'in_confirm');
							member_base::sendSms($task['buid'], $msg, 'in_confirm');
							common::setMsg('超时未核实货款，系统默认核实货款！');
							common::goto_url($baseUrl0.'?m=taskIn&t=all');
						}
					}
				}
			}
			if ($keys) $search = true;
			else $search = false;
			if ($search) {
				$where = "type='$taskId' and buid='$uid'";
				switch ($t) {
					case 'all': 
					    (($where && $where .= ' and ') || !$where) && $where .= "status in('8','9')";
					break;
					case 'tWatting2':
					    (($where && $where .= ' and ') || !$where) && $where .= "status in('8','9')";
					break;
					case 'ing':
						(($where && $where .= ' and ') || !$where) && $where .= "status in('2','3','4','5','6','7')";
					break;
					case 'expire':
						(($where && $where .= ' and ') || !$where) && $where .= "status in('8','9')";
					break;
					case 'complate':
						(($where && $where .= ' and ') || !$where) && $where .= "status='10'";
					break;	
				}
				$urlSuffix = '';
				if ($keys) {
					(($where && $where .= ' and ') || !$where) && $where .= " (sid='$keys' or nickname='$keys' or qq='$keys' or bnickname='$keys' or id='$keys')";
					$urlSuffix .= '&sid='.$keys;
				}
				if ($total = db::data_count('task', $where)) {
					$tList = task_new::getList2($where, 'where');
					$multipage = multi_page::parse($total, $pagesize, $page, $thisUrl.'&t='.$t.$urlSuffix.'&page={page}', $pageStyle, 10, 'member1');
				}
			} else {
				switch ($t) {
					case 'all':
						//$total = $memberTask['in'.$taskId];
						$total = db::data_count('task', "type='$taskId' and buid = '$uid'");
					break;
					case 'ing':
						if ($showTaskTip = $cookie['showTaskTip']) {
							$_taskId = $cookie['_taskId'];
							common::unsetcookie('showTaskTip', '_taskId');
						}
						//$total = $memberTask['ining'.$taskId];
						$total = db::data_count('task', "type='$taskId' and buid = '$uid' and status in('2','3','4','5','6','7')");
					break;
					case 'expire':
						//$total = $memberTask['inExpire'.$taskId];
						$total = db::data_count('task', "type='$taskId' and buid = '$uid' and status in('8','9')");
					break;
					case 'complate':
						//$total = $memberTask['inComplate'.$taskId];
						$total = db::data_count('task', "type='$taskId' and buid = '$uid' and status='10'");
					break;	
				}
				if ($total){
					$tList = task_new::getList2($uid, $t);
					$multipage = multi_page::parse($total, $pagesize, $page, $thisUrl.'&t='.$t.'&page={page}', $pageStyle, 10, 'member1');
				}
			}

		}
	break;
	case 'taskOut':
		$dangqianhuiyuanshu = db::data_count('members', "");
		$todayjierenwucount = db::data_count('task', "btimestamp>=$today_start and btimestamp<=$today_end and type='3'");
		$yest1 = $today_start - 86400;
		$yest2 = $today_start;
		$yestedayjierenwucount = db::data_count('task', "btimestamp>=$yest1 and btimestamp<=$yest2 and type='3'");
		$leijicount = db::data_count('task', "status='10' and type='3' and buid='$uid'");
		$alipay = db::get_list2('payfor_interface', '*', "ename='alipay'", 'time desc');
		$tenpay = db::get_list2('payfor_interface', '*', "ename='tenpay'", 'time desc');
		if ($memberfields['vip']==1) {
			$paymentshangbi = cfg::getFloat('payment', 'vip1shangbi');//一级会员信币价格
		} elseif ($memberfields['vip']==2) {
			$paymentshangbi = cfg::getFloat('payment', 'vip2shangbi');//钻石会员信币价格
		}elseif ($memberfields['vip']==3) {
			$paymentshangbi = cfg::getFloat('payment', 'vip3shangbi');//皇冠会员信币价格
		} else {
			$paymentshangbi = cfg::getFloat('payment', 'membershangbi');//普通会员信币价格
		}
		
		checkPwd2();
		$bbsNav[] = '已发任务';
		$t || $t = 'ing';
		$total = 0;
		$where = "type='$taskId' and suid='$uid'";
		if ($resume) {
			task_new::resume($resume, $uid);
			common::setMsg('恢复成功');
			common::goto_url($thisUrl.'&t=waiting');
		} elseif ($pause) {
			task_new::pause($pause, $uid);
			common::setMsg('暂停成功');
			common::goto_url($thisUrl.'&t=pause');
		} elseif ($repost) {
			$rs = task_new::repost($repost, $uid);
			if ($rs === true) {
				common::setMsg('重新发布成功');
				common::goto_url($thisUrl.'&t=waiting');
			} else {
				common::setMsg(language::get($rs), 'indexMessage');
				common::goto_url($referer, true);
			}
		} elseif ($del) {
			$rs = task_new::del($del, $uid);
			if ($rs === true) {
				common::setMsg('取消成功');
				common::goto_url($thisUrl.'&t=waiting');
			} else {
				common::setMsg(language::get($rs), 'indexMessage');
				common::goto_url($referer, true);
			}
		} elseif ($reject) {
			$rs = task_new::reject($reject, $uid);
			if ($rs === true) {
				common::setMsg('辞退成功');
				common::goto_url($thisUrl.'&t=waiting');
			} else {
				common::setMsg(language::get($rs), 'indexMessage');
				common::goto_url($referer, true);
			}
		} elseif ($verify) {
			$rs = task_new::verify($verify, $uid);
			if ($rs === true) {
				common::setMsg('审核成功');
				common::goto_url($referer, true);
			} else {
				common::setMsg(language::get($rs), 'indexMessage');
				common::goto_url($referer, true);
			}
		} elseif ($addtime) {
			$rs = task_new::addtime($addtime, $uid);
			if ($rs === true) {
				common::setMsg('该任务已成功加时');
				common::goto_url($referer, true);
			} else {
				common::setMsg(language::get($rs), 'indexMessage');
				common::goto_url($referer, true);
			}
		} elseif ($send) {
			$rs = task_new::send($send, $uid);
			if ($rs === true) {
				common::setMsg('您已发货完毕，请联系接手人确认收货！');
				common::goto_url($thisUrl.'&t=expire');
			} else {
				common::setMsg(language::get($rs), 'indexMessage');
				common::goto_url($referer, true);
			}
		} elseif ($confirm) {
			$rs = task_new::confirm($confirm, $uid);
			if ($rs === true) {
				common::setMsg('恭喜您，交易完成');
				common::goto_url($thisUrl.'&t=complate');
			} else {
				common::setMsg(language::get($rs), 'indexMessage');
				common::goto_url($referer, true);
			}
		}
		if ($renwuid && $pointExtsj > 0){							
										
				$task = db::one('task', '*', "id='$renwuid'");
				$farenwuid = $task['suid'];
				$zongpoint = $task['point'] + $pointExtsj;
				$farenwuxinxi = db::one('memberfields', 'fabudian', "uid='$farenwuid'");  
				$farenwuxingming = db::one('members', '*', "id='$farenwuid'");
				$farenwuxingmingxj = $farenwuxingming['username'];
				if ($farenwuxinxi['fabudian'] >= $pointExtsj){
						
					if ($task['status']<10) {
						if (db::update('task', array('pointExt' => $pointExtsj, 'point' => $zongpoint), "id='$renwuid'")) {
							member_base::addFabudian($farenwuid, -$pointExtsj, 1, '互刷区追加信币',$farenwuxingmingxj);
							common::setMsg('追加成功');							
							common::goto_url($baseUrl0.'?m=taskOut&t=all');
						}
						
					}
				}
			
		}
		if ($keys) $search = true;
		else $search = false;
		if ($search) {
			$where = "type='$taskId' and suid='$uid'";
			switch ($t) {
				case 'all':
				break;
				case 'waiting':
					(($where && $where .= ' and ') || !$where) && $where .= "status='1'";
				break;
				case 'pause':
					(($where && $where .= ' and ') || !$where) && $where .= "status='0'";
				break;
				case 'ing':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('2','3','4','5','6','7')";
				break;
				case 'expire':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('8','9')";
				break;
				case 'complate':
					(($where && $where .= ' and ') || !$where) && $where .= "status='10'";
				break;
			}
			$urlSuffix = '';
			if ($keys) {
				(($where && $where .= ' and ') || !$where) && $where .= " (sid='$keys' or busername='$keys' or qq='$keys' or bnickname='$keys' or id ='$keys')";
				$urlSuffix .= '&sid='.$keys;
			}
			
			if ($total = db::data_count('task', $where)) {
				$tList = task_new::getList($where, 'where');
				$multipage = multi_page::parse($total, $pagesize, $page, $thisUrl.'&t='.$t.$urlSuffix.'&page={page}', $pageStyle, 10, 'member1');
			}
		} else {
			switch ($t) {
				case 'all':
					//$total = $memberTask['out'.$taskId];
					$total = db::data_count('task', "type='$taskId' and suid = '$uid'");
				break;
				case 'waiting':
					//$total = $memberTask['outWaiting'.$taskId];
					$total = db::data_count('task', "type='$taskId' and suid = '$uid' and status='1'");
					if ($pauseAll) {
						task_new::pauseAll($uid);
						common::setMsg('全部暂停成功');
						common::goto_url($thisUrl.'&t=pause');
					}
				break;
				case 'pause':
					//$total = $memberTask['outPause'.$taskId];
					$total = db::data_count('task', "type='$taskId' and suid = '$uid' and status='0'");
					if ($resumeAll) {
						task_new::resumeAll($uid);
						common::setMsg('全部恢复成功');
						common::goto_url($thisUrl.'&t=waiting');
					}
				break;
				case 'ing':
					//$total = $memberTask['outing'.$taskId];
					$total = db::data_count('task', "type='$taskId' and suid = '$uid' and status in('2','3','4','5','6','7')");
				break;
				case 'expire':
					//$total = $memberTask['outExpire'.$taskId];
					$total = db::data_count('task', "type='$taskId' and suid = '$uid' and status in('8','9')");
				break;
				case 'complate':
					//$total = $memberTask['outComplate'.$taskId];
					$total = db::data_count('task', "type='$taskId' and suid = '$uid' and status='10'");
				break;	
			}
			if ($total) {
				$tList = task_new::getList($uid, $t);
				$multipage = multi_page::parse($total, $pagesize, $page, $thisUrl.'&t='.$t.'&page={page}', $pageStyle, 10, 'member1');
			}
			
		}
	break;
	case 'tieSeller':
		$dangqianhuiyuanshu = db::data_count('members', "");
		$todayjierenwucount = db::data_count('task', "btimestamp>=$today_start and btimestamp<=$today_end and type='3'");
		$yest1 = $today_start - 86400;
		$yest2 = $today_start;
		$yestedayjierenwucount = db::data_count('task', "btimestamp>=$yest1 and btimestamp<=$yest2 and type='3'");
		$leijicount = db::data_count('task', "status='10' and type='3' and buid='$uid'");
		$alipay = db::get_list2('payfor_interface', '*', "ename='alipay'", 'time desc');
		$tenpay = db::get_list2('payfor_interface', '*', "ename='tenpay'", 'time desc');
		if ($memberfields['vip']==1) {
			$paymentshangbi = cfg::getFloat('payment', 'vip1shangbi');//一级会员信币价格
		} elseif ($memberfields['vip']==2) {
			$paymentshangbi = cfg::getFloat('payment', 'vip2shangbi');//钻石会员信币价格
		}elseif ($memberfields['vip']==3) {
			$paymentshangbi = cfg::getFloat('payment', 'vip3shangbi');//皇冠会员信币价格
		} else {
			$paymentshangbi = cfg::getFloat('payment', 'membershangbi');//普通会员信币价格
		}
		checkPwd2();
		$members = member_base::getMember($uid);
		$membersfieldstie = member_base::getMemberFields($uid);
		
		if ($membersfieldstie[vip]==1) $maxTieCount = cfg::getInt('payment', 'vip1maxTieCount');
		if ($membersfieldstie[vip]==2) $maxTieCount = cfg::getInt('payment', 'vip2maxTieCount');
		if ($membersfieldstie[vip]==3) $maxTieCount = cfg::getInt('payment', 'vip3maxTieCount');
		if ($membersfieldstie[vip]==0) $maxTieCount = cfg::getInt('payment', 'vip0maxTieCount');
		$user_group = db::one('user_groups', 'name', "id='$members[groupid]'");
		if ($rs = form::is_form_hash2()) {
			if ($rs === true) {
				if ($memberfields['sellers'.$taskId] + 1 > $maxTieCount) $rs = '对不起，您不可以绑定更多的掌柜了';
				else {
					$datas = form::get('nickName', array('isTruth', 'int'));
					$datas && extract($datas);
					//	if (data_paipai::sellerExists($nickName)) {
					if ($datas) {
						if (!db::exists('sellers', array('type' => $taskId, 'nickname' => $nickName)) && !db::exists('buyers', array('type' => $taskId, 'nickname' => $nickName))) {
							if (db::insert('sellers', array(
								'type'       => $taskId,
								'uid'        => $uid,
								'username'   => $member['username'],
								'nickname'   => $nickName,
								'truth'      => 0,
								'express'    => '',
								'timestamp1' => $timestamp,
								'status'     => 1
							))) {
								db::update('memberfields', 'sellers'.$taskId.'=sellers'.$taskId.'+1', "uid='$uid'");
							}
						} else {
							$rs = '很抱歉，该掌柜已经被别人绑定了或者已经绑定了同名买号！';
						}
					} else {
						$rs = '拍拍平台上没有检测到该用户名';
					}
				}
			}
			if ($rs === true) {
				common::setMsg('添加成功');
				common::refresh();
			} else {
				$indexMessage = language::get($rs);
			}
		}
		$del=$_GET['del'];
		if($del){
			if(task_seller::del($del, $uid)){
			    common::setMsg('删除成功');
			    common::goto_url($thisUrl);
			}else{
			     common::setMsg('删除失败');
			}
			print_r($del);  
	    }
		$sList = db::get_list2('sellers', '*', "type='$taskId' and uid='$uid'");
	break;
	case 'tieBuyer':
		$dangqianhuiyuanshu = db::data_count('members', "");
		$todayjierenwucount = db::data_count('task', "btimestamp>=$today_start and btimestamp<=$today_end and type='3'");
		$yest1 = $today_start - 86400;
		$yest2 = $today_start;
		$yestedayjierenwucount = db::data_count('task', "btimestamp>=$yest1 and btimestamp<=$yest2 and type='3'");
		$leijicount = db::data_count('task', "status='10' and type='3' and buid='$uid'");
		$alipay = db::get_list2('payfor_interface', '*', "ename='alipay'", 'time desc');
		$tenpay = db::get_list2('payfor_interface', '*', "ename='tenpay'", 'time desc');
		if ($memberfields['vip']==1) {
			$paymentshangbi = cfg::getFloat('payment', 'vip1shangbi');//一级会员信币价格
		} elseif ($memberfields['vip']==2) {
			$paymentshangbi = cfg::getFloat('payment', 'vip2shangbi');//钻石会员信币价格
		}elseif ($memberfields['vip']==3) {
			$paymentshangbi = cfg::getFloat('payment', 'vip3shangbi');//皇冠会员信币价格
		} else {
			$paymentshangbi = cfg::getFloat('payment', 'membershangbi');//普通会员信币价格
		}
		checkPwd2();
		$membersfieldstie = member_base::getMemberFields($uid);
		
		if ($membersfieldstie[vip]==1) $maxTieCount = cfg::getInt('payment', 'vip1maxTieCount');
		if ($membersfieldstie[vip]==2) $maxTieCount = cfg::getInt('payment', 'vip2maxTieCount');
		if ($membersfieldstie[vip]==3) $maxTieCount = cfg::getInt('payment', 'vip3maxTieCount');
		if ($membersfieldstie[vip]==0) $maxTieCount = cfg::getInt('payment', 'vip0maxTieCount');
		$status = (int)$status;
		if ($pause = (int)$pause) {
			task_buyer::pause($pause, $uid);
			common::setMsg('暂停成功');
			common::goto_url($thisUrl.'&status=6');
		} elseif ($resume = (int)$resume) {
			task_buyer::resume($resume, $uid);
			common::setMsg('恢复成功');
			common::goto_url($thisUrl.'&status=1');
		} elseif ($resumeMore) {
			task_buyer::resumeMore($resumeMore, $uid);
			common::setMsg('恢复成功');
			common::goto_url($thisUrl.'&status=1');
		} elseif ($del = (int)$del) {
			task_buyer::del($del, $uid);
			common::setMsg('删除成功');
			common::goto_url($thisUrl.'&status=1');
		}
		if ($rs = form::is_form_hash2()) {
			if ($rs === true) {
				if ($maxTieCount > 0 && $memberfields['buyers'.$taskId] + 1 > $maxTieCount) $rs = '对不起，您不可以绑定更多的买号了';
				else {
					$nickname = $_POST['nickname'];
					//if (is_numeric($nickname)) {
						$rs = task_buyer::tie($uid, $taskId, $nickname);
					//} else $rs = '拍拍帐号只能为数字';
				}
			}
			if ($rs === true) {
				common::setMsg('添加成功');
				common::refresh();
			} else {
				$indexMessage = language::get($rs);
			}
		}
		if ($total = $memberfields['buyers'.$taskId]) {
			$status2 = $status - 1;
			//if ($status2 < 0 || $status2 > 6) $status2 = 0;
			if ($status2 > 6) $status2 = -1;
			$bList = task_buyer::getList1($taskId, $uid, $status2);
			$multipage = multi_page::parse($total, $pagesize, $page, $thisUrl, $pageStyle.'&status='.$status.'&page={page}', 10, 'member1');
		}
	break;
	case 'tpl':
		$alipay = db::get_list2('payfor_interface', '*', "ename='alipay'", 'time desc');
		$tenpay = db::get_list2('payfor_interface', '*', "ename='tenpay'", 'time desc');
		$bbsNav[] = '任务模板';
		if ($del = (int)$del) {
			db::delete('task_tpl', "id='$del' and uid='$uid'");
			common::setMsg('删除成功');
			common::goto_url($thisUrl);
		}
		if ($tplList = db::get_list2('task_tpl', '*', "type='$taskId' and uid='$uid'", 'timestamp desc')) {
			foreach ($tplList as $k => $v) {
				$v['datas'] = unserialize($v['datas']);
				$tplList[$k] = $v;
			}
		}
	break;
	case 'viprob':
		$alipay = db::get_list2('payfor_interface', '*', "ename='alipay'", 'time desc');
		$tenpay = db::get_list2('payfor_interface', '*', "ename='tenpay'", 'time desc');
		if ($memberfields['vip']==1) {
			$paymentshangbi = cfg::getFloat('payment', 'vip1shangbi');//一级会员信币价格
		} elseif ($memberfields['vip']==2) {
			$paymentshangbi = cfg::getFloat('payment', 'vip2shangbi');//钻石会员信币价格
		}elseif ($memberfields['vip']==3) {
			$paymentshangbi = cfg::getFloat('payment', 'vip3shangbi');//皇冠会员信币价格
		} else {
			$paymentshangbi = cfg::getFloat('payment', 'membershangbi');//普通会员信币价格
		}
		
		if ($rob == 1) {
			if ($isVip) {
				if ($sid = db::one_one('task', 'id', "type='$taskId' and status='1' order by svip desc,stimestamp desc")) {
					common::goto_url($baseUrl0.'?m=taskIn&new='.$sid.'&referer='.urlencode($referer));
				} else {
					common::setMsg('没有抢到');
					common::goto_url($baseUrl0);
				}
			} else {
				common::setMsg('您当前不是VIP，不能使用此功能，请先到联盟中心申请成为VIP');
				common::goto_url($baseUrl0);
			}
		} else {
			common::setMsg('参数错误');
			common::goto_url($baseUrl0);
		}
	break;
	case 'upload':
		$alipay = db::get_list2('payfor_interface', '*', "ename='alipay'", 'time desc');
		$tenpay = db::get_list2('payfor_interface', '*', "ename='tenpay'", 'time desc');
		if ($memberfields['vip']==1) {
			$paymentshangbi = cfg::getFloat('payment', 'vip1shangbi');//一级会员信币价格
		} elseif ($memberfields['vip']==2) {
			$paymentshangbi = cfg::getFloat('payment', 'vip2shangbi');//钻石会员信币价格
		}elseif ($memberfields['vip']==3) {
			$paymentshangbi = cfg::getFloat('payment', 'vip3shangbi');//皇冠会员信币价格
		} else {
			$paymentshangbi = cfg::getFloat('payment', 'membershangbi');//普通会员信币价格
		}
		
		$id=$_REQUEST['id'];
		if($_POST['task_id']){
			$task_id=$_POST['task_id'];
			$avatar='task_pic';
			if($avatar){
			    if ($avatar) {
			            $p0 = common::getArticlePath($uid_, '/'); //00/00/00
			            $f0 = strrpos($p0, '/');   // 8
			            $p1 = substr($p0, 0, $f0); // 00/00/00  路径
			            $p2 = substr($p0, $f0 + 1);  //  00 文件名
						$img_name=$id;   //time().rand(10000,99999);
						$img_avatar='img/task'; //新路径
			            $filename = image::getImage($avatar, d('./'.$img_avatar.'/'), 300, 200, 'task_'.$img_name);  // _00.jpg
			        if ($filename !== false) {
				            $avatar0 = $img_avatar.'/'.$filename;
				            $avatar1 = $img_avatar.'/'.substr($filename, 1);
				        if (@rename(d('./'.$img_avatar.$avatar0), d('./'.$img_avatar.$avatar1))) {
					        $avatar = $avatar1;
				        } else {
					        $avatar = $avatar0;
				        }
						 $pinimage=$avatar;
			        }
		        }
				
			    $rs=db::update('task', array('pinimage' => $pinimage), "buid='$uid' and id='$task_id'");
                if($rs){
                 return "<script>alert('上传成功！');</script>";
                }else{
                return "<script>alert('上传失败！');</script>";
                }				
			}
		}
	break;
	case 'CreateMealMission':
		$alipay = db::get_list2('payfor_interface', '*', "ename='alipay'", 'time desc');
		$tenpay = db::get_list2('payfor_interface', '*', "ename='tenpay'", 'time desc');
		if ($memberfields['vip']==1) {
			$paymentshangbi = cfg::getFloat('payment', 'vip1shangbi');//一级会员信币价格
		} elseif ($memberfields['vip']==2) {
			$paymentshangbi = cfg::getFloat('payment', 'vip2shangbi');//钻石会员信币价格
		}elseif ($memberfields['vip']==3) {
			$paymentshangbi = cfg::getFloat('payment', 'vip3shangbi');//皇冠会员信币价格
		} else {
			$paymentshangbi = cfg::getFloat('payment', 'membershangbi');//普通会员信币价格
		}
		
		$ensurePoint = 0.3;
		if ($memberfields['sellers'.$taskId] == 0 || db::data_count('sellers', "type='$taskId' and status='1'") == 0) {
			common::setMsg('对不起，请先绑定掌柜', 'indexMessage');
			common::goto_url($baseUrl0.'?m=tieSeller');
		}
		checkPwd2();
		$members = member_base::getMember($uid);
		if ($rs = form::is_form_hash2()) {
			$datas = form::get('nickname', 'itemurl','wangwang', 'visitTip', 'visitKey', array('visitWay', 'int'), array('price', 'float'), array('isPriceFit', 'int'), array('pointExt', 'float'), array('times', 'int'),  array('scores', 'int'), array('isRemark', 'int'), 'remark', array('isShare', 'int'),array('isAddress', 'int'), 'address',array('share', 'int'), 'tips', array('isDbssc', 'int'),array('isVerify', 'int'), array('issphb', 'float'),'meal',array('isLimit', 'int'), array('limit', 'int'), array('chssp', 'int'),array('isNoword', 'int'),
				array('isReal', 'int'), array('cbxIsTip', 'int'),'txtBuyCount','cbIsHiddenName','cbIsNoneAssess','txtAreaService','txtAccount','txtMobile','txtSpecs','ddlDeliver','cbxName','cbxMobile','cbxcode',
				array('realname', 'int'),'FMaxBTSCount','cbxIsTaoG','txtTaoG','isawb','expressfull','isSign',
				array('isChat', 'int'), array('isChatDone', 'int'),'Express','isLimitCity','isMultiple','province',
				array('isStar', 'int'),'qq','cbxAddress',array('expressTM', 'int'),
				array('lvlStar', 'int'),//星级别
				array('isEnsure', 'int'),//是否商保
				array('ispinimage', 'int'),array('iskongbao', 'int'),array('txtMinMPrice', 'float'),
				array('ensurePoint', 'float'),array('isMobile', 'int'), array('cbxIsWX', 'int'),array('shopcoller', 'int'),
				array('isScore', 'int'), array('scoreLvl', 'int'),array('iscomplain', 'int'), array('complain', 'int'), array('isCredit', 'int'), array('creditLvl', 'int'), array('isGood', 'int'), array('goodLvl', 'int'), array('isBlack', 'int'), array('blackLvl', 'int'), array('isFame', 'int'), array('fameLvl', 'int'), array('isPlan', 'int'), 'planDate');
			if ($isHot) $count = (int)$_POST['count'];
			else $count = 1;
			if ($rs === true) {
				$rs = task_new::add($datas, $uid, $count);
			}
			if ($rs === true) {
				//保存模板
				$datas2 = form::get(array('isTpl', 'int'), 'tplTo');
				$datas2 && extract($datas2);
				if ($isTpl && $tplTo) {
					$tplTo = common::cutstr($tplTo, 64);
					$members = member_base::getMember($uid);
		$membersfieldstie = member_base::getMemberFields($uid);		
		if ($membersfieldstie[vip]==1) $maxTplCount = cfg::getInt('payment', 'vip1maxTpl');
		if ($membersfieldstie[vip]==2) $maxTplCount = cfg::getInt('payment', 'vip2maxTpl');
		if ($membersfieldstie[vip]==3) $maxTplCount = cfg::getInt('payment', 'vip3maxTpl');
		if ($membersfieldstie[vip]==0) $maxTplCount = cfg::getInt('payment', 'vip0maxTpl');
					if (db::data_count('task_tpl', "uid='$uid'") < $maxTplCount) {
						db::insert('task_tpl', array(
							'type'      => $taskId,
							'uid'       => $uid,
							'isAdds'    => 0,
							'stype'      => 3,
							'name'      => $tplTo,
							'datas'     => addslashes(serialize($datas)),
							'timestamp' => $timestamp
						));
					}
				}
				// THE END
				common::setMsg('发布成功');
				common::goto_url($baseUrl0.'?m=taskOut&t=all');
			} else {
				$indexMessage = language::get($rs);
			}
		}
		$tplList = db::get_list2('task_tpl', 'id,name', "type='$taskId' and uid='$uid' and stype='3'", 'timestamp desc');
		if (($tplId = (int)$tplId) && !$datas) {
			$datas = db::one_one('task_tpl', 'datas', "type='$taskId' and uid='$uid' and stype='3' and id='$tplId'");
			$datas && $datas = unserialize($datas);
		}
		$sellers = task_seller::getSellers($uid, 1, $taskId);
		$eList = task_express::getList();
	break;
	
	
	case 'CreateLaiLuMission':
		$alipay = db::get_list2('payfor_interface', '*', "ename='alipay'", 'time desc');
		$tenpay = db::get_list2('payfor_interface', '*', "ename='tenpay'", 'time desc');
		if ($memberfields['vip']==1) {
			$paymentshangbi = cfg::getFloat('payment', 'vip1shangbi');//一级会员信币价格
		} elseif ($memberfields['vip']==2) {
			$paymentshangbi = cfg::getFloat('payment', 'vip2shangbi');//钻石会员信币价格
		}elseif ($memberfields['vip']==3) {
			$paymentshangbi = cfg::getFloat('payment', 'vip3shangbi');//皇冠会员信币价格
		} else {
			$paymentshangbi = cfg::getFloat('payment', 'membershangbi');//普通会员信币价格
		}
		
		$ensurePoint = 0.3;
		if ($memberfields['sellers1'] == 0 || db::data_count('sellers', "type='1' and status='1'") == 0) {
			common::setMsg('对不起，请先绑定掌柜', 'indexMessage');
			common::goto_url('/task/tao/?m=tieSeller');
		}
		checkPwd2();
		$members = member_base::getMember($uid);
		if ($rs = form::is_form_hash2()) {
			$datas = form::get('nickname', 'itemurl', 'wangwang','visitTip', 'visitKey', array('visitWay', 'int'), array('price', 'float'), array('isPriceFit', 'int'), array('pointExt', 'float'), array('times', 'int'),  array('scores', 'int'), array('isRemark', 'int'), 'remark', array('isMobile', 'int'), array('cbxIsWX', 'int'), array('isShare', 'int'),array('isAddress', 'int'), 'address',array('share', 'int'), 'tips', array('isDbssc', 'int'),array('isVerify', 'int'), array('issphb', 'float'),'sphbdz',array('isLimit', 'int'), array('limit', 'int'), array('chssp', 'int'),array('isNoword', 'int'),
				array('isReal', 'int'), array('cbxIsTip', 'int'),'txtBuyCount','cbIsHiddenName','cbIsNoneAssess','txtAreaService','txtAccount','txtMobile','txtSpecs','ddlDeliver','cbxName','cbxMobile','cbxcode',
				array('realname', 'int'),'FMaxBTSCount','cbxIsTaoG','txtTaoG','isawb','expressfull','isSign',
				array('isChat', 'int'), array('isChatDone', 'int'),'Express','isLimitCity','isMultiple','province',
				array('isStar', 'int'),'qq','cbxAddress',array('expressTM', 'int'),
				array('lvlStar', 'int'),//星级别
				array('isEnsure', 'int'),//是否商保
				array('shopcoller', 'int'),array('fabuNum', 'int'),array('fabuNumx', 'int'),array('fabuFenzhong', 'int'),
				array('ispinimage', 'int'),array('iskongbao', 'int'),array('txtMinMPrice', 'float'),
				array('ensurePoint', 'float'),array('maihaoxinyu', 'int'),array('nopingtaick', 'int'),
				array('maihaodengji', 'int'), array('goumailianjie', 'int'),array('goumaishl', 'int'),
				array('isScore', 'int'), array('scoreLvl', 'int'),array('isCompare', 'int'), array('contrast', 'int'),array('stopDsTime', 'int'), array('stopTime', 'int'),array('isViewEnd', 'int'),array('iscomplain', 'int'), array('complain', 'int'), array('isCredit', 'int'), array('creditLvl', 'int'), array('isGood', 'int'), array('goodLvl', 'int'), array('isBlack', 'int'), array('blackLvl', 'int'), array('isFame', 'int'), array('fameLvl', 'int'), array('isPlan', 'int'), 'planDate');
			if ($isHot) $count = (int)$_POST['count'];
			else $count = 1;
			if ($rs === true) {

				$rs = task_new::add($datas, $uid, $count ,'photourl');

			}
			if ($rs === true) {
				//保存模板
				$datas2 = form::get(array('isTpl', 'int'), 'tplTo');
				$datas2 && extract($datas2);
				if ($isTpl && $tplTo) {
					$tplTo = common::cutstr($tplTo, 64);
					$members = member_base::getMember($uid);
		$membersfieldstie = member_base::getMemberFields($uid);		
		if ($membersfieldstie[vip]==1) $maxTplCount = cfg::getInt('payment', 'vip1maxTpl');
		if ($membersfieldstie[vip]==2) $maxTplCount = cfg::getInt('payment', 'vip2maxTpl');
		if ($membersfieldstie[vip]==3) $maxTplCount = cfg::getInt('payment', 'vip3maxTpl');
		if ($membersfieldstie[vip]==0) $maxTplCount = cfg::getInt('payment', 'vip0maxTpl');
					if (db::data_count('task_tpl', "uid='$uid'") < $maxTplCount) {
						db::insert('task_tpl', array(
							'type'      => $taskId,
							'uid'       => $uid,
							'isAdds'    => 0,
							'stype'      => 2,
							'name'      => $tplTo,
							'datas'     => addslashes(serialize($datas)),
							'timestamp' => $timestamp
						));
					}
				}
				// THE END
				common::setMsg('发布成功，需要购买快递单的卖家，请购买快递单号！不需要的请点击<a href="/task/new/?m=CreateLaiLuMission"  style="color:red;">返回继续发布任务</a>或者<a href="/task/new/?m=taskOut&t=all"  style="color:red;">查看已发布任务</a>');
				common::goto_url($baseUrl0.'?m=taskOut&t=all');
			} else {
				$indexMessage = language::get($rs);
			}
		}
		$tplList = db::get_list2('task_tpl', 'id,name', "type='$taskId' and uid='$uid' and stype='2'", 'timestamp desc');
		if (($tplId = (int)$tplId) && !$datas) {
			$datas = db::one_one('task_tpl', 'datas', "type='$taskId' and uid='$uid' and stype='2' and id='$tplId'");
			$datas && $datas = unserialize($datas);
		
		}
		$sellers = task_seller::getSellers($uid, 1, 1);
		$eList = task_express::getList();
	break;
	case 'CreateCartMission':
		$alipay = db::get_list2('payfor_interface', '*', "ename='alipay'", 'time desc');
		$tenpay = db::get_list2('payfor_interface', '*', "ename='tenpay'", 'time desc');
		if ($memberfields['vip']==1) {
			$paymentshangbi = cfg::getFloat('payment', 'vip1shangbi');//一级会员信币价格
		} elseif ($memberfields['vip']==2) {
			$paymentshangbi = cfg::getFloat('payment', 'vip2shangbi');//钻石会员信币价格
		}elseif ($memberfields['vip']==3) {
			$paymentshangbi = cfg::getFloat('payment', 'vip3shangbi');//皇冠会员信币价格
		} else {
			$paymentshangbi = cfg::getFloat('payment', 'membershangbi');//普通会员信币价格
		}
		
		$ensurePoint = 0.5;
		checkPwd2();
		$members = member_base::getMember($uid);
		if ($rs = form::is_form_hash2()) {
			$datas = form::get2('nickname', 'itemurl', array('price', 'float'), array('isPriceFit', 'int'), array('pointExt', 'float'), array('times', 'int'),  array('scores', 'int'), array('isRemark', 'int'), 'remark', array('isShare', 'int'), array('share', 'int'), 'tips', array('isVerify', 'int'),
				array('isLimit', 'int'), array('limit', 'int'), array('isCart', 'int'), array('isAddress', 'int'), 'address', array('isExpress', 'int'), array('expressTM', 'int'), 
			array('isReal', 'int'), 'c_text','c_cbhp','c_title','c_chssp','c_price',
			array('realname', 'int'),array('cbxIsTip', 'int'),'txtBuyCount','cbIsHiddenName','cbIsNoneAssess','txtAreaService','txtAccount','txtMobile','txtSpecs','ddlDeliver','cbxName','cbxMobile','cbxcode','province',
				'FMaxBTSCount','cbxIsTaoG','txtTaoG','isawb','expressfull','isSign',
				 'Express','isLimitCity','isMultiple','ispinimage',
				'qq','cbxAddress',
			array('isChat', 'int'), array('isChatDone', 'int'),array('txtMinMPrice', 'float'),
			array('isStar', 'int'),//是否星级任务
				array('lvlStar', 'int'),//星级别
				array('isEnsure', 'int'),//是否商保
				array('ensurePoint', 'float'),array('isMobile', 'int'), array('cbxIsWX', 'int'),array('shopcoller', 'int'),
			 array('isScore', 'int'), array('scoreLvl', 'int'),array('iscomplain', 'int'), array('complain', 'int'), array('isCredit', 'int'), array('creditLvl', 'int'), array('isGood', 'int'), array('goodLvl', 'int'), array('isBlack', 'int'), array('blackLvl', 'int'), array('isFame', 'int'), array('fameLvl', 'int'), array('isPlan', 'int'), 'planDate');
			if ($rs === true) {
				$rs = task_new::adds($datas, $uid);
			}
			if ($rs === true || is_numeric($rs)) {
				//保存模板
				$datas2 = form::get(array('isTpl', 'int'), 'tplTo');
				$datas2 && extract($datas2);
				if ($isTpl && $tplTo) {
					$tplTo = common::cutstr($tplTo, 64);
					$members = member_base::getMember($uid);
		$membersfieldstie = member_base::getMemberFields($uid);		
		if ($membersfieldstie[vip]==1) $maxTplCount = cfg::getInt('payment', 'vip1maxTpl');
		if ($membersfieldstie[vip]==2) $maxTplCount = cfg::getInt('payment', 'vip2maxTpl');
		if ($membersfieldstie[vip]==3) $maxTplCount = cfg::getInt('payment', 'vip3maxTpl');
		if ($membersfieldstie[vip]==0) $maxTplCount = cfg::getInt('payment', 'vip0maxTpl');
					if (db::data_count('task_tpl', "uid='$uid'") < $maxTplCount) {
						db::insert('task_tpl', array(
							'type'      => $taskId,
							'uid'       => $uid,
							'isAdds'    => 1,
							'stype'    => 4,
							'name'      => $tplTo,
							'datas'     => addslashes(serialize($datas)),
							'timestamp' => $timestamp
						));
					}
				}
				// THE END
				if (is_numeric($rs)) common::setMsg('发布成功'.$rs.'个任务');
				else common::setMsg('发布成功');
				common::goto_url($baseUrl0.'?m=taskOut&t=all');
			} else {
				$indexMessage = language::get($rs);
			}
		}
		$tplList = db::get_list2('task_tpl', 'id,name', "type='$taskId' and uid='$uid' and stype='4'", 'timestamp desc');
		if (($tplId = (int)$tplId) && !$datas) {
			$datas = db::one_one('task_tpl', 'datas', "type='$taskId' and uid='$uid' and stype='4' and id='$tplId'");
			$datas && $datas = unserialize($datas);
			$itemurl = explode('*;;;*',trim($datas['itemurl'],"*;;;*"));
			$c_text = explode('*;;;*',trim($datas['c_text'],"*;;;*"));
			$c_title = explode('*;;;*',trim($datas['c_title'],"*;;;*"));
			$c_cbhp = explode('*;;;*',trim($datas['c_cbhp'],"*;;;*"));
			$c_chssp = explode('*;;;*',trim($datas['c_chssp'],"*;;;*"));
			$c_price = explode('*;;;*',trim($datas['c_price'],"*;;;*"));
			foreach ($itemurl as $k => $v){
                $carts[$k]['itemurl']=$v;
            }
			foreach ($c_text as $k => $v){
                $carts[$k]['text']=$v;
            }
			foreach ($c_price as $k => $v){
                $carts[$k]['price']=$v;
            }
			foreach ($c_cbhp as $k => $v){
                $carts[$k]['cbhp']=$v;
            }
			foreach ($c_chssp as $k => $v){
                $carts[$k]['chssp']=$v;
            }
			foreach ($c_title as $k => $v){
                $carts[$k]['title']=$v;
            }
		}
		$sellers = task_seller::getSellers($uid, 1, $taskId);
		$eList = task_express::getList();
}
?>