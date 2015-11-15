<?php
$top_menu=array(	
	'tao' => '淘宝区任务管理',
	'you' => '远程区任务管理',	
	'view' => array('name' => '商品详情', 'isHide' => true),
	'ganyu' => array('name' => '后台干预', 'isHide' => true)
);
if($edit=(int)$edit)$method='edit';
$top_menu_key = array_keys($top_menu);
($method && in_array($method,$top_menu_key)) || $method=$top_menu_key[0];
$top_menu2 = array(
	'all'         => '全部',
	'pause'       => '暂停中的任务',
	'waiting'     => '等待接手任务',
	'ing'         => '进行中的任务',
	'shenhe'      => '等待审核的任务',
	'fahuo'       => '等待发货的任务',
	'queren'      => '等待确认的任务',
	'waitExpress' => '等待快递单号',
	'complate'    => '已完成的任务'
);
$top_menu_key2 = array_keys($top_menu2);
($method2 && in_array($method2,$top_menu_key2)) || $method2 = $top_menu_key2[0];
$status     = array(
		'暂停中', 
		'已发布，等待接手人接手', 
		'已接手，等待接手人绑定买号', '等待发布方审核', '已绑定买号，等待接手方支付', '已支付，待核对快递地址', '准备发货，等待快递单号', '已支付，等待发布人发货', 
		'已发货，等待收货与好评', '已确认，等待卖家核实货款', 
		'交易完成');
$types = array('', '淘宝区', '拍拍区', '京东区');
$times = array('', '马上好评', '24小时好评', '48小时好评', '72小时好评');
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
	'share'      => array('', '非匿名分享', '匿名分享'),
	'bStatus'    => array('正常', '危险', '挂起', '失效', '锁定', '暂停', '收藏'),
	'cStatus'    => array('双方未评分', '接手方已评分', '发布方已评分', '双方已评分')
);
error_reporting(E_ALL & ~E_NOTICE);
switch($method){
	case 'new':
		if (form::is_form_hash()) {
			if ($del = $_POST['del']) {
				$ids = array();
				foreach ($del as $id) {
					$task = db::one('task', '*', "id='$id'");
					$s = $task['status'];
					$t = $task['type'];
					if ($s < 5) {
						if ($s == 0) {
							$f = 'Pause';
						} elseif ($s == 1) {
							$f = 'Waiting';
						} else {
							$f = 'ing';
						}
						db::update('membertask', 'out'.$f.$t.'='.'out'.$f.$t.'-1', "uid='$task[suid]'");
						db::update('sellers', 'tasking=tasking-1', "id='$task[sid]'");
						if ($s > 1) {
							db::update('membertask', 'in'.$f.$t.'='.'in'.$f.$t.'-1', "uid='$task[buid]'");
							db::update('buyers', 'tasking=tasking-1', "id='$task[bid]'");
						}
						if ($task['nopingtaick']==0) {
							member_base::addMoney($task['suid'], $task['price'], '后台删除任务，返还担保金');
						}
						member_base::addFabudian($task['suid'], $task['point'], $task['type'] == 4?1:$task['type'], '后台删除任务，返还发布点');
						member_base::addCredit($task['suid'], -($task['svip']?6:5), '后台删除任务，扣除发布奖励积分');
						$ids[] = $id;
					} elseif($task['status'] == 10) {
						$ids[] = $id;
					}
				}
				if ($ids) db::del_ids('task', $ids);
			}
			admin::show_message('删除成功', $base_url.'&method='.$method.'&method2='.$method2);
		}
		$where = "type='4'";
		switch ($method2) {
			case 'all':
				
			break;
			case 'pause':
				(($where && $where .= ' and ') || !$where) && $where .= "status='0'";
			break;
			case 'waiting':
				(($where && $where .= ' and ') || !$where) && $where .= "status='1'";
			break;
			case 'ing':
				(($where && $where .= ' and ') || !$where) && $where .= "status in('2','3','4','5','6','7','8','9')";
			break;
			case 'shenhe':
				(($where && $where .= ' and ') || !$where) && $where .= "status in('3')";
			break;
			case 'fahuo':
				(($where && $where .= ' and ') || !$where) && $where .= "status in('7')";
			break;
			case 'queren':
				(($where && $where .= ' and ') || !$where) && $where .= "status in('9')";
			break;
			case 'waitExpress':
				(($where && $where .= ' and ') || !$where) && $where .= "status='6'";
			break;
			case 'complate':
				(($where && $where .= ' and ') || !$where) && $where .= "status='10'";
			break;
		}
		if ($total = db::data_count('task', $where)) {
			$taskInfo = db::one('task', 'sum(price) priceAll,sum(point) pointAll', $where);
			$list = db::get_list2('task', '*', $where, 'status,stimestamp desc', $page, $pagesize);
			$multipage = multi_page::parse($total, $pagesize, $page, $base_url.'&method='.$method.'&method2='.$method2.'&page={page}', $pagestyle, 10);
		}
		if (!$taskInfo) $taskInfo = array('priceAll' => 0, 'pointAll' => 0);
	break;
	case 'tao':		
		if (form::is_form_hash()) {
			if ($del = $_POST['del']) {
				$ids = array();
				foreach ($del as $id) {
					$task = db::one('task', '*', "id='$id'");
					$s = $task['status'];
					$t = $task['type'];
					if ($s < 5) {
						if ($s == 0) {
							$f = 'Pause';
						} elseif ($s == 1) {
							$f = 'Waiting';
						} else {
							$f = 'ing';
						}
						//db::update('membertask', 'out'.$f.$t.'='.'out'.$f.$t.'-1', "uid='$task[suid]'");
						$taskingzongshu = db::one('sellers', '*', "id='$task[sid]'");
						if ($task['sid'] && $taskingzongshu > 0){
							db::update('sellers', 'tasking=tasking-1', "id='$task[sid]'");
						}
						if ($s > 1) {
							//db::update('membertask', 'in'.$f.$t.'='.'in'.$f.$t.'-1', "uid='$task[buid]'");
							$taskingzongshu = db::one('buyers', '*', "id='$task[bid]'");
							if ($task['bid'] && $taskingzongshu > 0){
								db::update('buyers', 'tasking=tasking-1', "id='$task[bid]'");
							}
						}
						if ($task['nopingtaick']==0) {
							member_base::addMoney($task['suid'], $task['price'], '后台删除任务，返还担保金');
						}
						member_base::addFabudian($task['suid'], $task['point'], $task['type'] == 4?1:$task['type'], '后台删除任务，返还佣金币');
						//member_base::addCredit($task['suid'], -($task['svip']?6:5), '后台删除任务，扣除发布奖励积分');
						$ids[] = $id;
					} elseif($task['status'] == 10) {
						$ids[] = $id;
					}
				}
				if ($ids) db::del_ids('task', $ids);
				admin::show_message('删除成功', $base_url.'&method='.$method.'&method2='.$method2);
			}
			
		}
		
		$_POST && extract($_POST);
		if ($m == 'search')
		{
			if ($bianhaoid){
				$where = "type='1' and id='$bianhaoid'";
			}
			if ($susername){
				$where = "type='1' and susername='$susername'";
			}
			if ($busername){
				$where = "type='1' and busername='$busername'";
			}
			switch ($method2) {
				case 'all':
					
				break;
				case 'pause':
					(($where && $where .= ' and ') || !$where) && $where .= "status='0'";
				break;
				case 'waiting':
					(($where && $where .= ' and ') || !$where) && $where .= "status='1'";
				break;
				case 'ing':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('2','3','4','5','6','7','8','9')";
				break;
				case 'shenhe':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('3')";
				break;
				case 'fahuo':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('7')";
				break;
				case 'shouhuo':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('8')";
				break;
				case 'queren':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('9')";
				break;
				case 'waitExpress':
					(($where && $where .= ' and ') || !$where) && $where .= "status='6'";
				break;
				case 'complate':
					(($where && $where .= ' and ') || !$where) && $where .= "status='10'";
				break;
				case 'yisuoding':
					(($where && $where .= ' and ') || !$where) && $where .= "status='13'";
				break;
			}
			if ($total = db::data_count('task', $where)) {
				$taskInfo = db::one('task', 'sum(price) priceAll,sum(point) pointAll', $where);
				$list = db::get_list4('task', '*', $where, 'status,stimestamp desc', $page, 3000);
				$multipage = multi_page::parse($total, 3000, $page, $base_url.'&method='.$method.'&method2='.$method2.'&page={page}', $pagestyle, 10);
			}
			if (!$taskInfo) $taskInfo = array('priceAll' => 0, 'pointAll' => 0);
		}
		else
		{
		
			$where = "type='1'";
			switch ($method2) {
				case 'all':
					
				break;
				case 'pause':
					(($where && $where .= ' and ') || !$where) && $where .= "status='0'";
				break;
				case 'waiting':
					(($where && $where .= ' and ') || !$where) && $where .= "status='1'";
				break;
				case 'ing':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('2','3','4','5','6','7','8','9')";
				break;
				case 'shenhe':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('3')";
				break;
				case 'fahuo':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('7')";
				break;
				case 'shouhuo':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('8')";
				break;
				case 'queren':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('9')";
				break;
				case 'waitExpress':
					(($where && $where .= ' and ') || !$where) && $where .= "status='6'";
				break;
				case 'complate':
					(($where && $where .= ' and ') || !$where) && $where .= "status='10'";
				break;
				case 'yisuoding':
					(($where && $where .= ' and ') || !$where) && $where .= "status='13'";
				break;
			}
			if ($total = db::data_count('task', $where)) {
				$taskInfo = db::one('task', 'sum(price) priceAll,sum(point) pointAll', $where);
				$list = db::get_list2('task', '*', $where, 'status,stimestamp desc', $page, $pagesize);
				$multipage = multi_page::parse($total, $pagesize, $page, $base_url.'&method='.$method.'&method2='.$method2.'&page={page}', $pagestyle, 10);
			}
			if (!$taskInfo) $taskInfo = array('priceAll' => 0, 'pointAll' => 0);
		}
	break;
	case 'pai':
		if (form::is_form_hash()) {
			if ($del = $_POST['del']) {
				$ids = array();
				foreach ($del as $id) {
					$task = db::one('task', '*', "id='$id'");
					$s = $task['status'];
					$t = $task['type'];
					if ($s < 5) {
						if ($s == 0) {
							$f = 'Pause';
						} elseif ($s == 1) {
							$f = 'Waiting';
						} else {
							$f = 'ing';
						}
						//db::update('membertask', 'out'.$f.$t.'='.'out'.$f.$t.'-1', "uid='$task[suid]'");
						$taskingzongshu = db::one('sellers', '*', "id='$task[sid]'");
						if ($task['sid'] && $taskingzongshu > 0){
							db::update('sellers', 'tasking=tasking-1', "id='$task[sid]'");
						}
						if ($s > 1) {
							//db::update('membertask', 'in'.$f.$t.'='.'in'.$f.$t.'-1', "uid='$task[buid]'");
							$taskingzongshu = db::one('buyers', '*', "id='$task[bid]'");
							if ($task['bid'] && $taskingzongshu > 0){
								db::update('buyers', 'tasking=tasking-1', "id='$task[bid]'");
							}
						}
						if ($task['nopingtaick']==0) {
							member_base::addMoney($task['suid'], $task['price'], '后台删除任务，返还担保金');
						}
						if ($task['isawb'] && $task['nopingtaick']==0) {
							member_base::addMoney($uid, 3, '后台删除任务返还快递费');
						}
						
						member_base::addFabudian3($task['suid'], $task['point'], $task['type'] == 2?1:$task['type'], '后台删除任务，返还佣金币');
						//member_base::addCredit($task['suid'], -($task['svip']?6:5), '后台删除任务，扣除发布奖励积分');
						$ids[] = $id;
					} elseif($task['status'] == 10) {
						$ids[] = $id;
					}
				}
				if ($ids) db::del_ids('task', $ids);
				admin::show_message('删除成功', $base_url.'&method='.$method.'&method2='.$method2);
			}
			
		}
		$_POST && extract($_POST);
		if ($m == 'search')
		{
			if ($bianhaoid){
				$where = "type='2' and id='$bianhaoid'";
			}
			if ($susername){
				$where = "type='2' and susername='$susername'";
			}
			if ($busername){
				$where = "type='2' and busername='$busername'";
			}			
			switch ($method2) {
				case 'all':
					
				break;
				case 'pause':
					(($where && $where .= ' and ') || !$where) && $where .= "status='0'";
				break;
				case 'waiting':
					(($where && $where .= ' and ') || !$where) && $where .= "status='1'";
				break;
				case 'ing':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('2','3','4','5','6','7','8','9')";
				break;
				case 'shenhe':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('3')";
				break;
				case 'fahuo':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('7')";
				break;
				case 'shouhuo':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('8')";
				break;
				case 'queren':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('9')";
				break;
				case 'waitExpress':
					(($where && $where .= ' and ') || !$where) && $where .= "status='6'";
				break;
				case 'complate':
					(($where && $where .= ' and ') || !$where) && $where .= "status='10'";
				break;
				case 'yisuoding':
					(($where && $where .= ' and ') || !$where) && $where .= "status='13'";
				break;
			}
			if ($total = db::data_count('task', $where)) {
				$taskInfo = db::one('task', 'sum(price) priceAll,sum(point) pointAll', $where);
				$list = db::get_list2('task', '*', $where, 'status,stimestamp desc', $page, $pagesize);
				$multipage = multi_page::parse($total, $pagesize, $page, $base_url.'&method='.$method.'&method2='.$method2.'&page={page}', $pagestyle, 10);
			}
			if (!$taskInfo) $taskInfo = array('priceAll' => 0, 'pointAll' => 0);		
		}else{
			$where = "type='2'";
			switch ($method2) {
				case 'all':
					
				break;
				case 'pause':
					(($where && $where .= ' and ') || !$where) && $where .= "status='0'";
				break;
				case 'waiting':
					(($where && $where .= ' and ') || !$where) && $where .= "status='1'";
				break;
				case 'ing':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('2','3','4','5','6','7','8','9')";
				break;
				case 'shenhe':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('3')";
				break;
				case 'fahuo':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('7')";
				break;
				case 'shouhuo':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('8')";
				break;
				case 'queren':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('9')";
				break;
				case 'waitExpress':
					(($where && $where .= ' and ') || !$where) && $where .= "status='6'";
				break;
				case 'complate':
					(($where && $where .= ' and ') || !$where) && $where .= "status='10'";
				break;
				case 'yisuoding':
					(($where && $where .= ' and ') || !$where) && $where .= "status='13'";
				break;
			}
			if ($total = db::data_count('task', $where)) {
				$taskInfo = db::one('task', 'sum(price) priceAll,sum(point) pointAll', $where);
				$list = db::get_list2('task', '*', $where, 'status,stimestamp desc', $page, $pagesize);
				$multipage = multi_page::parse($total, $pagesize, $page, $base_url.'&method='.$method.'&method2='.$method2.'&page={page}', $pagestyle, 10);
			}
			if (!$taskInfo) $taskInfo = array('priceAll' => 0, 'pointAll' => 0);

		}
	break;
	case 'you':
		if (form::is_form_hash()) {
			if ($del = $_POST['del']) {
				$ids = array();
				foreach ($del as $id) {
					$task = db::one('task', '*', "id='$id'");
					$s = $task['status'];
					$t = $task['type'];
					if ($s < 5) {
						if ($s == 0) {
							$f = 'Pause';
						} elseif ($s == 1) {
							$f = 'Waiting';
						} else {
							$f = 'ing';
						}
						//db::update('membertask', 'out'.$f.$t.'='.'out'.$f.$t.'-1', "uid='$task[suid]'");
						$taskingzongshu = db::one('sellers', '*', "id='$task[sid]'");
						if ($task['sid'] && $taskingzongshu > 0){
							db::update('sellers', 'tasking=tasking-1', "id='$task[sid]'");
						}
						if ($s > 1) {
							//db::update('membertask', 'in'.$f.$t.'='.'in'.$f.$t.'-1', "uid='$task[buid]'");
							$taskingzongshu = db::one('buyers', '*', "id='$task[bid]'");
							if ($task['bid'] && $taskingzongshu > 0){
								db::update('buyers', 'tasking=tasking-1', "id='$task[bid]'");
							}
						}
						if ($task['nopingtaick']==0) {
							member_base::addMoney($task['suid'], $task['price'], '后台删除任务，返还担保金');
						}
						if ($task['isawb'] && $task['nopingtaick']==0) {
							member_base::addMoney($uid, 3, '后台删除任务返还快递费');
						}
						
						member_base::addFabudian3($task['suid'], $task['point'], $task['type'] == 4?1:$task['type'], '后台删除任务，返还佣金币');
						//member_base::addCredit($task['suid'], -($task['svip']?6:5), '后台删除任务，扣除发布奖励积分');
						$ids[] = $id;
					} elseif($task['status'] == 10) {
						$ids[] = $id;
					}
				}
				if ($ids) db::del_ids('task', $ids);
				admin::show_message('删除成功', $base_url.'&method='.$method.'&method2='.$method2);
			}
			
		}
		$_POST && extract($_POST);
		if ($m == 'search')
		{
			if ($bianhaoid){
				$where = "type='3' and id='$bianhaoid'";
			}
			if ($susername){
				$where = "type='3' and susername='$susername'";
			}
			if ($busername){
				$where = "type='3' and busername='$busername'";
			}			
			switch ($method2) {
				case 'all':
					
				break;
				case 'pause':
					(($where && $where .= ' and ') || !$where) && $where .= "status='0'";
				break;
				case 'waiting':
					(($where && $where .= ' and ') || !$where) && $where .= "status='1'";
				break;
				case 'ing':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('2','3','4','5','6','7','8','9')";
				break;
				case 'shenhe':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('3')";
				break;
				case 'fahuo':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('7')";
				break;
				case 'shouhuo':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('8')";
				break;
				case 'queren':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('9')";
				break;
				case 'waitExpress':
					(($where && $where .= ' and ') || !$where) && $where .= "status='6'";
				break;
				case 'complate':
					(($where && $where .= ' and ') || !$where) && $where .= "status='10'";
				break;
				case 'yisuoding':
					(($where && $where .= ' and ') || !$where) && $where .= "status='13'";
				break;
			}
			if ($total = db::data_count('task', $where)) {
				$taskInfo = db::one('task', 'sum(price) priceAll,sum(point) pointAll', $where);
				$list = db::get_list2('task', '*', $where, 'status,stimestamp desc', $page, $pagesize);
				$multipage = multi_page::parse($total, $pagesize, $page, $base_url.'&method='.$method.'&method2='.$method2.'&page={page}', $pagestyle, 10);
			}
			if (!$taskInfo) $taskInfo = array('priceAll' => 0, 'pointAll' => 0);		
		}else{
			$where = "type='3'";
			switch ($method2) {
				case 'all':
					
				break;
				case 'pause':
					(($where && $where .= ' and ') || !$where) && $where .= "status='0'";
				break;
				case 'waiting':
					(($where && $where .= ' and ') || !$where) && $where .= "status='1'";
				break;
				case 'ing':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('2','3','4','5','6','7','8','9')";
				break;
				case 'shenhe':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('3')";
				break;
				case 'fahuo':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('7')";
				break;
				case 'shouhuo':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('8')";
				break;
				case 'queren':
					(($where && $where .= ' and ') || !$where) && $where .= "status in('9')";
				break;
				case 'waitExpress':
					(($where && $where .= ' and ') || !$where) && $where .= "status='6'";
				break;
				case 'complate':
					(($where && $where .= ' and ') || !$where) && $where .= "status='10'";
				break;
				case 'yisuoding':
					(($where && $where .= ' and ') || !$where) && $where .= "status='13'";
				break;
			}
			if ($total = db::data_count('task', $where)) {
				$taskInfo = db::one('task', 'sum(price) priceAll,sum(point) pointAll', $where);
				$list = db::get_list2('task', '*', $where, 'status,stimestamp desc', $page, $pagesize);
				$multipage = multi_page::parse($total, $pagesize, $page, $base_url.'&method='.$method.'&method2='.$method2.'&page={page}', $pagestyle, 10);
			}
			if (!$taskInfo) $taskInfo = array('priceAll' => 0, 'pointAll' => 0);

		}
	break;
	case 'view':
		if ($sid) {
			if (form::is_form_hash()) {
				$expressNum = $_POST['expressNum'];
				db::update('task', array('expressNum' => $expressNum, 'status' => 7), "id='$sid' and status='6'");
				admin::show_message('更新成功');
			}
			if ($task = db::one('task', '*', "id='$sid'")) {
				if ($task['isCart'] || $task['isExpress']) {
					$shops = db::get_list('taskshops', '*', "tid='$task[id]'");
					if ($task['isExpress']) {
						if ($sellerExpress = task_seller::getExpress($task['sid'], $task['expressTM'])) {
							$sellerExpress['area'] = str_replace(' ', '', $sellerExpress['area']);
						}
						if ($buyerExpress = task_buyer::getExpress($task['bid'], $task['expressTM'])) {
							$buyerExpress['area'] = str_replace(' ', '', $buyerExpress['area']);
						}
					}
				}
			}
		}
	break;
	case 'ganyu':
		if ($sid) {
			switch ($confirmh) {				
					case 'shenhe':						
						$task = db::one('task', '*', "id='$sid'");
						if ($task['status']==3) {
							if (db::update('task', array('ttimestamp' => $timestamp + 900, 'status' => 4), "id='$sid'")) {
								task_base::addLog($sid, '任务审核', '后台管理员'.$querenren.'审核了任务{id}的接手方{busername}');
								$msg = '您在'.language::get('qu'.$task['type']).'区接手的任务“'.$task['id'].'”，已通过审核';
								member_base::sendPm($task['buid'], $msg, '接手的任务“'.$task['id'].'”已通过审核', 'in_verify');
								member_base::sendSms($task['buid'], $msg, 'in_verify');
								//return true;
								admin::show_message('单号为'.$sid.'审核成功');	
							}
							
						}						
						admin::show_message('单号为'.$sid.'已经审核过了，请勿重复审核');			
											
					break;
					case 'qinglimaijia':						
						$task = db::one('task', '*', "id='$sid'");
						if ($task['status']<10) {
							if (db::update('task', array('btimestamp' => 0,'etimestamp' => 0,'ttimestamp' => 0,'buid' => 0,'busername'  => '', 'bnickname'  => '', 'pinimage'  => '','bip'=> 0,'status' => 1), "id='$sid'")) {
								task_base::addLog($sid, '任务清理', '后台管理员'.$querenren.'清理了任务{id}的接手方{busername}');
								$msg = '您在'.language::get('qu'.$task['type']).'区接手的任务“'.$task['id'].'”，已被后台管理清理';
								member_base::sendPm($task['buid'], $msg, '接手的任务“'.$task['id'].'”已被后台管理员清理', 'in_verify');
								member_base::sendSms($task['buid'], $msg, 'in_verify');								
								admin::show_message('单号为'.$sid.'被后台管理员清理成功');	
							}
							
						}						
						admin::show_message('单号为'.$sid.'已经被后台管理员清理过了，请勿重复清理');			
											
					break;
					case 'fahuo':						
					$task = db::one('task', '*', "id='$sid'");
					global $timestamp;						
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
								), "id='$sid'")) {
									db::update('membertask', 'outing1=outing1-1,outExpire1=outExpire1+1', "uid='$task[suid]'");
									db::update('membertask', 'ining1=ining1-1,inExpire1=inExpire1+1', "uid='$task[buid]'");
									task_base::addLog($sid, '确认发布', '后台管理员'.$querenren.'确认了任务{id}的发货');
									$msg = '您在'.language::get('qu'.$task['type']).'区接手的任务“'.$task['id'].'”，卖家已发货';
									member_base::sendPm($task['buid'], $msg, '接手的任务“'.$task['id'].'”卖家已发货', 'in_send');
									member_base::sendSms($task['buid'], $msg, 'in_send');
									//return true;
									admin::show_message('单号为'.$sid.'发货成功');	
								}
								
							}
							admin::show_message('单号为'.$sid.'已发过货了，请勿重复发货');
							
														
					break;
					case 'queren':						
						$task = db::one('task', '*', "id='$sid'");
						global $timestamp;
						if ($task['status']==9) {
							if (db::update('task', array(
								'ctimestamp' => $timestamp,
								'status'     => 10
							), "id='$sid'")) {

								db::update('membertask', 'outExpire1=outExpire1-1,outComplate1=outComplate1+1,outComplate=outComplate+1', "uid='$task[suid]'");
								db::update('membertask', 'inExpire1=inExpire1-1,inComplate1=inComplate1+1,inComplate=inComplate+1', "uid='$task[buid]'");
								task_seller::addComplate($task['sid']);
								task_buyer::addComplate($task['bid']);
								member_base::addMoney($task['buid'], $task['price'], '完成任务'.$task['id']);
								member_base::addPoint($sid);
								member_base::addCredit($task['buid'], member_base::isVip($task['buid'])?6:5, '完成任务'.$task['id']);
								task_base::addLog($sid, '核实货款', '后台管理员'.$querenren.'已核实货款任务{id}');

								$msg = '您在'.language::get('qu'.$task['type']).'区接手的任务“'.$task['id'].'”，卖家已核实货款';
								member_base::sendPm($task['buid'], $msg, '接手的任务“'.$task['id'].'”卖家已核实货款', 'in_confirm');
								member_base::sendSms($task['buid'], $msg, 'in_confirm');
								//return true;
								admin::show_message('单号为'.$sid.'确认成功，交易完成');
							}
							
						}
						admin::show_message('单号为'.$sid.'已经完成交易,后台'.$querenren.'确认了，请勿重复确认');					
						
					break;				
			}
		}
	break;
}
?>