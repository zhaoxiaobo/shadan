<?php
(!defined('IN_JB') || IN_JB!==true) && exit('error');
$m || $m = 'index';
$ms = array('index', 'add', 'adds', 'tieBuyer', 'tieSeller', 'taskOut', 'taskIn', 'tpl', 'viprob');
($m && in_array($m, $ms)) || $m = $ms[0];
$tplName.= '_'.$m;
$pName = '拍拍互动区';
$title = $pName;
$lanP = $lanP0.'tao_';
$minCredit = 50;
$taskStatus = true;
$taskId = 2;
if ($memberfields['credits'] < $minCredit) {
	//language::get(array('name' => $lanP.'credit_less_than', 'args' => array('x' => $minCredit)));
	$taskStatus = false;
}
$thisUrl = $baseUrl0.'?m='.$m;
$lang = array(
	/*'status'     => array('暂停中', '已发布，等待接手人接手', '已接手，等待接手人绑定买号', '等待发布方审核', '已绑定买号，等待接手方支付', '已支付，等待发布人发货', '已发货，等待收货与好评', '已确认，等待卖家确认', '交易完成'),*/
	'status'     => array(
		'暂停中', 
		'已发布，等待接手人接手', 
		'已接手，等待接手人绑定买号', '等待发布方审核', '已绑定买号，等待接手方支付', '已支付，待核对快递地址', '准备发货，等待快递单号', '已支付，等待发布人发货', 
		'已发货，等待收货与好评', '已确认，等待卖家核实货款', 
		'交易完成'),
	'isPriceFit' => array('不需要', '需要'),
	'times'      => array('', '马上好评', '24小时好评', '48小时好评', '72小时好评'),
	'times_ico'  => array('&nbsp;', '&nbsp;', '<span class=\'task_24\' title=\'24小时好评\'></span>', '<span class=\'task_48\' title=\'48小时好评\'></span>', '<span class=\'task_72\' title=\'72小时好评\'></span>'),
	'scores'     => array('全部不打分', 5 => '全部打5分'),
	'bStatus'    => array('正常', '危险', '挂起', '失效', '锁定', '暂停', '收藏'),
	'cStatus'    => array('双方未评分', '接手方已评分', '发布方已评分', '双方已评分')
);
$allCount = db::data_count('task', "type='$taskId'");
$complateCount = db::data_count('task', "type='$taskId' and status='10'");
if (!($taskBook = task_book::getConfig($taskId, $uid))) {
	$taskBook = array('ing' => 0);
}
if ($m == 'index') {
	$bbsNav[] = $pName;
} else {
	$bbsNav[] = array('name' => $pName, 'url' => $baseUrl0);
}
switch ($m) {
	case 'index':
		if (!$taskStatus) $_showmessage = '系统提示，您的积分不足'.$minCredit.'分，没有达到拍拍互动区要求，请先到新手区接发任务，熟悉发布流程同时积累积分吧！';
	break;
	case 'add':
		$ensurePoint = 0.3;
		if (!$taskStatus) error::bbsMsg('对不起，您的积分低于'.$minCredit.'分，请先到新手互动区发布任务，如果您是第一次发布任务，平台在新手区还有惊喜等着您');
		if ($memberfields['sellers'.$taskId] == 0 || db::data_count('sellers', "type='$taskId' and status='1'") == 0) {
			common::setMsg('对不起，请先绑定掌柜', 'indexMessage');
			common::goto_url($baseUrl0.'?m=tieSeller');
		}
		checkPwd2();
		if ($rs = form::is_form_hash2()) {
			$datas = form::get('nickname', 'itemurl', 'visitTip', 'visitKey', array('visitWay', 'int'), array('price', 'float'), array('isPriceFit', 'int'), array('pointExt', 'float'), array('times', 'int'),  array('scores', 'int'), array('isRemark', 'int'), 'remark', array('isShare', 'int'), array('share', 'int'), 'tips', array('isVerify', 'int'), array('isLimit', 'int'), array('limit', 'int'), array('isReal', 'int'), array('isChat', 'int'), array('isChatDone', 'int'), 
			array('isStar', 'int'),//是否星级任务
				array('lvlStar', 'int'),//星级别
				array('isEnsure', 'int'),//是否商保
				array('ensurePoint', 'float'),
			array('isScore', 'int'), array('scoreLvl', 'int'), array('isCredit', 'int'), array('creditLvl', 'int'), array('isGood', 'int'), array('goodLvl', 'int'), array('isBlack', 'int'), array('blackLvl', 'int'), array('isFame', 'int'), array('fameLvl', 'int'), array('isPlan', 'int'), 'planDate');
			if ($isHot) $count = (int)$_POST['count'];
			else $count = 1;
			if ($rs === true) {
				$rs = task_pai::add($datas, $uid, $count);
			}
			if ($rs === true) {
				//保存模板
				$datas2 = form::get(array('isTpl', 'int'), 'tplTo');
				$datas2 && extract($datas2);
				if ($isTpl && $tplTo) {
					$tplTo = common::cutstr($tplTo, 64);
					if ($isVip) $maxTplCount = 10;
					elseif ($isVip2) $maxTplCount = 5;
					else $maxTplCount = 3;
					if (db::data_count('task_tpl', "uid='$uid'") < $maxTplCount) {
						db::insert('task_tpl', array(
							'type'      => $taskId,
							'uid'       => $uid,
							'isAdds'    => 0,
							'name'      => $tplTo,
							'datas'     => addslashes(serialize($datas)),
							'timestamp' => $timestamp
						));
					}
				}
				// THE END
				common::setMsg('发布成功');
				common::goto_url($baseUrl0.'?m=taskOut');
			} else {
				$indexMessage = language::get($rs);
			}
		}
		$tplList = db::get_list2('task_tpl', 'id,name', "type='$taskId' and uid='$uid' and isAdds='0'", 'timestamp desc');
		if (($tplId = (int)$tplId) && !$datas) {
			$datas = db::one_one('task_tpl', 'datas', "type='$taskId' and uid='$uid' and isAdds='0' and id='$tplId'");
			$datas && $datas = unserialize($datas);
		}
		$sellers = task_seller::getSellers($uid, 1, $taskId);
	break;
	case 'adds':
		$ensurePoint = 0.5;
		if (!$taskStatus) error::bbsMsg('对不起，您的积分低于'.$minCredit.'分，请先到新手互动区发布任务，如果您是第一次发布任务，平台在新手区还有惊喜等着您');
		if ($memberfields['sellers'.$taskId] == 0 || db::data_count('sellers', "type='$taskId' and status='1'") == 0) {
			common::setMsg('对不起，请先绑定掌柜', 'indexMessage');
			common::goto_url($baseUrl0.'?m=tieSeller');
		}
		checkPwd2();
		if ($rs = form::is_form_hash2()) {
			$datas = form::get2('nickname', 'itemurl', array('price', 'float'), array('isPriceFit', 'int'), array('pointExt', 'float'), array('times', 'int'),  array('scores', 'int'), array('isRemark', 'int'), 'remark', array('isShare', 'int'), array('share', 'int'), 'tips', array('isVerify', 'int'), array('isLimit', 'int'), array('limit', 'int'), array('isCart', 'int'), array('isAddress', 'int'), 'address', array('isExpress', 'int'), array('expressTM', 'int'), array('isReal', 'int'), array('isChat', 'int'), array('isChatDone', 'int'),
			array('isStar', 'int'),//是否星级任务
				array('lvlStar', 'int'),//星级别
				array('isEnsure', 'int'),//是否商保
				array('ensurePoint', 'float'),
			 array('isScore', 'int'), array('scoreLvl', 'int'), array('isCredit', 'int'), array('creditLvl', 'int'), array('isGood', 'int'), array('goodLvl', 'int'), array('isBlack', 'int'), array('blackLvl', 'int'), array('isFame', 'int'), array('fameLvl', 'int'), array('isPlan', 'int'), 'planDate');
			if ($rs === true) {
				$rs = task_pai::adds($datas, $uid);
			}
			if ($rs === true || is_numeric($rs)) {
				//保存模板
				$datas2 = form::get(array('isTpl', 'int'), 'tplTo');
				$datas2 && extract($datas2);
				if ($isTpl && $tplTo) {
					$tplTo = common::cutstr($tplTo, 64);
					if ($isVip) $maxTplCount = 10;
					elseif ($isVip2) $maxTplCount = 5;
					else $maxTplCount = 3;
					if (db::data_count('task_tpl', "uid='$uid'") < $maxTplCount) {
						db::insert('task_tpl', array(
							'type'      => $taskId,
							'uid'       => $uid,
							'isAdds'    => 1,
							'name'      => $tplTo,
							'datas'     => addslashes(serialize($datas)),
							'timestamp' => $timestamp
						));
					}
				}
				// THE END
				if (is_numeric($rs)) common::setMsg('发布成功'.$rs.'个任务');
				else common::setMsg('发布成功');
				common::goto_url($baseUrl0.'?m=taskOut');
			} else {
				$indexMessage = language::get($rs);
			}
		}
		$tplList = db::get_list2('task_tpl', 'id,name', "type='$taskId' and uid='$uid' and isAdds='1'", 'timestamp desc');
		if (($tplId = (int)$tplId) && !$datas) {
			$datas = db::one_one('task_tpl', 'datas', "type='$taskId' and uid='$uid' and isAdds='1' and id='$tplId'");
			$datas && $datas = unserialize($datas);
		}
		$sellers = task_seller::getSellers($uid, 1, $taskId);
		$eList = task_express::getList();
	break;
	case 'taskIn':
		checkPwd2();
		if ($new) {
			if (!$taskStatus && !$memberfields['exam']) {
				common::setMsg('平台认为您的接手经验尚浅，为了您的安全，强烈要求您先通过平台的小小测试');
				common::goto_url('/member/exam/');
			}
			$rs = task_pai::in($new, $uid);
			if ($rs === true) {
				if ($memberTask['inComplate'.$taskId] < 5) common::setcookie('showTaskTip', 1);
				common::goto_url($thisUrl.'&t=ing');
			} else {
				common::goto_url($referer, true, $rs);
			}
		} else {
			$bbsNav[] = '已接任务';
			$t || $t = 'ing';
			if ($out) {
				$rs = task_pai::out($out, $uid);
				if ($rs === true) {
					common::setMsg('退出成功');
					common::goto_url($thisUrl.'&t=ing');
				} else {
					common::setMsg($rs, 'indexMessage');
					common::goto_url($referer, true);
				}
			} elseif ($pay) {
				$rs = task_pai::pay($pay, $uid);
				if ($rs === true) {
					common::setMsg('您已经确认支付，请联系发布方发货！');
					common::goto_url($referer, true);
				} else {
					common::setMsg($rs, 'indexMessage');
					common::goto_url($referer, true);
				}
			} elseif ($unpay) {
				$rs = task_pai::unpay($unpay, $uid);
				if ($rs === true) {
					common::setMsg('任务已经返回未支付状态！');
					common::goto_url($referer, true);
				} else {
					common::setMsg($rs, 'indexMessage');
					common::goto_url($referer, true);
				}
			} elseif ($receive) {
				$rs = task_pai::receive($receive, $uid);
				if ($rs === true) {
					common::setMsg('已经确认收获，等待卖家确认');
					common::goto_url($referer, true);
				} else {
					common::setMsg($rs, 'indexMessage');
					common::goto_url($referer, true);
				}
			}
			if ($sid || $susername || $sqq || $bnickname) $search = true;
			else $search = false;
			if ($search) {
				$where = "type='$taskId' and buid='$uid'";
				switch ($t) {
					case 'all':
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
				if ($sid) {
					(($where && $where .= ' and ') || !$where) && $where .= "id='$sid'";
					$urlSuffix .= '&sid='.$sid;
				}
				if ($susername) {
					(($where && $where .= ' and ') || !$where) && $where .= "susername='$susername'";
					$urlSuffix .= '&susername'.urlencode($susername);
				}
				if ($sqq) {
					$suid = (int)db::one_one('members', 'id', "qq='$sqq'");
					(($where && $where .= ' and ') || !$where) && $where .= "suid='$suid'";
					$urlSuffix .= '&sqq='.$sqq;
				}
				if ($bnickname) {
					(($where && $where .= ' and ') || !$where) && $where .= "bnickname='$bnickname'";
					$urlSuffix .= '&bnickname='.urlencode($bnickname);
				}
				if ($total = db::data_count('task', $where)) {
					$tList = task_pai::getList2($where, 'where');
					$multipage = multi_page::parse($total, $pagesize, $page, $thisUrl.'&t='.$t.$urlSuffix.'&page={page}', $pageStyle, 10, 'member1');
				}
			} else {
				switch ($t) {
					case 'all':
						$total = $memberTask['in'.$taskId];
					break;
					case 'ing':
						if ($showTaskTip = $cookie['showTaskTip']) {
							$_taskId = $cookie['_taskId'];
							common::unsetcookie('showTaskTip');
						}
						$total = $memberTask['ining'.$taskId];
					break;
					case 'expire':
						$total = $memberTask['inExpire'.$taskId];
					break;
					case 'complate':
						$total = $memberTask['inComplate'.$taskId];
					break;	
				}
				if ($total) {
					$tList = task_pai::getList2($uid, $t);
					$multipage = multi_page::parse($total, $pagesize, $page, $thisUrl.'&t='.$t.'&page={page}', $pageStyle, 10, 'member1');
				}
			}
		}
	break;
	case 'taskOut':
		checkPwd2();
		$bbsNav[] = '已发任务';
		$t || $t = 'all';
		$total = 0;
		$where = "type='$taskId' and suid='$uid'";
		if ($resume) {
			task_pai::resume($resume, $uid);
			common::setMsg('恢复成功');
			common::goto_url($thisUrl.'&t=waiting');
		} elseif ($pause) {
			task_pai::pause($pause, $uid);
			common::setMsg('暂停成功');
			common::goto_url($thisUrl.'&t=pause');
		} elseif ($repost) {
			$rs = task_pai::repost($repost, $uid);
			if ($rs === true) {
				common::setMsg('重新发布成功');
				common::goto_url($thisUrl.'&t=waiting');
			} else {
				common::setMsg(language::get($rs), 'indexMessage');
				common::goto_url($referer, true);
			}
		} elseif ($del) {
			$rs = task_pai::del($del, $uid);
			if ($rs === true) {
				common::setMsg('取消成功');
				common::goto_url($thisUrl.'&t=waiting');
			} else {
				common::setMsg(language::get($rs), 'indexMessage');
				common::goto_url($referer, true);
			}
		} elseif ($reject) {
			$rs = task_pai::reject($reject, $uid);
			if ($rs === true) {
				common::setMsg('辞退成功');
				common::goto_url($thisUrl.'&t=waiting');
			} else {
				common::setMsg(language::get($rs), 'indexMessage');
				common::goto_url($referer, true);
			}
		} elseif ($verify) {
			$rs = task_pai::verify($verify, $uid);
			if ($rs === true) {
				common::setMsg('审核成功');
				common::goto_url($referer, true);
			} else {
				common::setMsg(language::get($rs), 'indexMessage');
				common::goto_url($referer, true);
			}
		} elseif ($addtime) {
			$rs = task_pai::addtime($addtime, $uid);
			if ($rs === true) {
				common::setMsg('该任务已成功加时');
				common::goto_url($referer, true);
			} else {
				common::setMsg(language::get($rs), 'indexMessage');
				common::goto_url($referer, true);
			}
		} elseif ($send) {
			$rs = task_pai::send($send, $uid);
			if ($rs === true) {
				common::setMsg('您已发货完毕，请联系接手人确认收货！');
				common::goto_url($thisUrl.'&t=expire');
			} else {
				common::setMsg(language::get($rs), 'indexMessage');
				common::goto_url($referer, true);
			}
		} elseif ($confirm) {
			$rs = task_pai::confirm($confirm, $uid);
			if ($rs === true) {
				common::setMsg('恭喜您，交易完成');
				common::goto_url($thisUrl.'&t=complate');
			} else {
				common::setMsg(language::get($rs), 'indexMessage');
				common::goto_url($referer, true);
			}
		}
		if ($sid || $busername || $bqq || $bnickname) $search = true;
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
			if ($sid) {
				(($where && $where .= ' and ') || !$where) && $where .= "id='$sid'";
				$urlSuffix .= '&sid='.$sid;
			}
			if ($busername) {
				(($where && $where .= ' and ') || !$where) && $where .= "busername='$busername'";
				$urlSuffix .= '&busername'.urlencode($busername);
			}
			if ($bqq) {
				$buid = (int)db::one_one('members', 'id', "qq='$bqq'");
				(($where && $where .= ' and ') || !$where) && $where .= "buid='$buid'";
				$urlSuffix .= '&bqq='.$bqq;
			}
			if ($bnickname) {
				(($where && $where .= ' and ') || !$where) && $where .= "bnickname='$bnickname'";
				$urlSuffix .= '&bnickname='.urlencode($bnickname);
			}
			if ($total = db::data_count('task', $where)) {
				$tList = task_pai::getList($where, 'where');
				$multipage = multi_page::parse($total, $pagesize, $page, $thisUrl.'&t='.$t.$urlSuffix.'&page={page}', $pageStyle, 10, 'member1');
			}
		} else {
			switch ($t) {
				case 'all':
					$total = $memberTask['out'.$taskId];
				break;
				case 'waiting':
					$total = $memberTask['outWaiting'.$taskId];
					if ($pauseAll) {
						task_pai::pauseAll($uid);
						common::setMsg('全部暂停成功');
						common::goto_url($thisUrl.'&t=pause');
					}
				break;
				case 'pause':
					$total = $memberTask['outPause'.$taskId];
					if ($resumeAll) {
						task_pai::resumeAll($uid);
						common::setMsg('全部恢复成功');
						common::goto_url($thisUrl.'&t=waiting');
					}
				break;
				case 'ing':
					$total = $memberTask['outing'.$taskId];
				break;
				case 'expire':
					$total = $memberTask['outExpire'.$taskId];
				break;
				case 'complate':
					$total = $memberTask['outComplate'.$taskId];
				break;	
			}
			if ($total) {
				$tList = task_pai::getList($uid, $t);
				$multipage = multi_page::parse($total, $pagesize, $page, $thisUrl.'&t='.$t.'&page={page}', $pageStyle, 10, 'member1');
			}
		}
	break;
	case 'tieSeller':
		checkPwd2();
		if ($isVip) $maxTieCount = 5;
		elseif ($isVip2) $maxTieCount = 3;
		else $maxTieCount = 2;
		if ($rs = form::is_form_hash2()) {
			if ($rs === true) {
				if ($memberfields['sellers'.$taskId] + 1 > $maxTieCount) $rs = '对不起，您不可以绑定更多的掌柜了';
				else {
					$datas = form::get('nickName', array('isTruth', 'int'));
					$datas && extract($datas);
					//	if (data_paipai::sellerExists($nickName)) {
					if (1==1) {
						if (!db::exists('sellers', array('type' => $taskId, 'nickname' => $nickName))) {
							if (db::insert('sellers', array(
								'type'       => $taskId,
								'uid'        => $uid,
								'username'   => $member['username'],
								'nickname'   => $nickName,
								'truth'      => 0,
								'express'    => '',
								'timestamp1' => $timestamp,
								'status'     => 0
							))) {
								db::update('memberfields', 'sellers'.$taskId.'=sellers'.$taskId.'+1', "uid='$uid'");
							}
						} else {
							$rs = '很抱歉，该掌柜已经被别人绑定了';
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
		$sList = db::get_list2('sellers', '*', "type='$taskId' and uid='$uid'");
	break;
	case 'tieBuyer':
		checkPwd2();
		if ($isVip) $maxTieCount = -1;
		elseif ($isVip2) $maxTieCount = 20;
		else $maxTieCount = 7;
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
					if (is_numeric($nickname)) {
						$rs = task_buyer::tie($uid, $taskId, $nickname);
					} else $rs = '拍拍帐号只能为数字';
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
			$bList = task_buyer::getList($taskId, $uid, $status2);
			$multipage = multi_page::parse($total, $pagesize, $page, $thisUrl, $pageStyle.'&status='.$status.'&page={page}', 10, 'member1');
		}
	break;
	case 'tpl':
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
		if ($rob == 1) {
			if ($isVip) {
				if ($sid = db::one_one('task', 'id', "type='$taskId' and status='1' order by svip desc,stimestamp desc")) {
					common::goto_url($baseUrl0.'?m=taskIn&new='.$sid);
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
}
?>