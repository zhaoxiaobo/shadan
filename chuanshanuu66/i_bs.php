<?php
$top_menu=array(
	'buyers1' => '淘宝买号管理',
	'buyers3' => '流量买号管理',	
	'sellers1'   => '淘宝掌柜管理',
	'sellers3'   => '流量掌柜管理',	
	'view' => array('name' => '小号详情', 'hide' => true),
	'tianjia' => array('name' => '添加淘宝小号', 'hide' => true)
);
if($edit=(int)$edit)$method='edit';
$top_menu_key = array_keys($top_menu);
($method && in_array($method,$top_menu_key)) || $method=$top_menu_key[0];
$buyerStatus = array('正常', '危险', '挂起', '失效', '锁定', '暂停', '收藏', '删除');
$sellerStatus = array('未激活', '已经激活');
switch($method){
	case 'buyers1':
		$tbName = 'buyers';
		if ($stop = (int)$stop) {
			db::update('buyers', "status='2'", "id='$stop'");
			admin::show_message('挂起成功', $referer);
		}
		if ($unstop = (int)$unstop) {
			db::update('buyers', "status='0'", "id='$unstop'");
			admin::show_message('恢复挂起成功', $referer);
		}
		if ($lock = (int)$lock) {
			db::update('buyers', "status='4'", "id='$lock'");
			admin::show_message('锁定成功', $referer);
		}
		if ($unlock = (int)$unlock) {
			db::update('buyers', "status='0'", "id='$unlock'");
			admin::show_message('恢复锁定成功', $referer);
		}
		if ($undel = (int)$undel) {
			if ($buyer = db::one('buyers', '*', "id='$undel'")) {
				db::update('buyers', "status='0'", "id='$undel'");
				db::update('memberfields', 'buyers'.$buyer['type'].'=buyers'.$buyer['type'].'+1', "uid='$buyer[uid]'");
			}
			admin::show_message('恢复删除成功', $referer);
		}
		if (form::is_form_hash()) {
			$_POST && extract($_POST);
			$nickname = trim($nickname);
			$username = trim($username);
			if ($m == 'search') {
				if($username && $nickname){
				 $chawhere = "type='1' and nickname='$nickname' and username='$username'";
				}elseif($nickname && $username==null){
					$chawhere = "type='1' and nickname='$nickname'";
				}elseif($username && $nickname==null){
					$chawhere = "type='1' and username='$username'";
				}
				$list = db::get_list($tbName, '*', $chawhere);
			} else {
				if ($del = $_POST['del']) {
					foreach ($del as $id) {
						if ($buyer = db::one($tbName, '*', "id='$id'")) {
							$bnickname = $buyer['nickname'];
							$buyercishu = db::data_count('task', "bnickname='$bnickname' and type='1' and status<10 and status>0");
							if ($buyercishu < 1) {
								db::del_id($tbName, $id);
								db::update('memberfields', $method.'='.$method.'-1', "uid='$buyer[uid]'");
							}
						}
					}
					admin::show_message('删除成功', $base_url.'&method='.$method);
				}
			}
		}
		if ($m != 'search') {
			if ($total = db::data_count($tbName, "type=1")) {
				$list = db::get_list2($tbName, '*', "type='1'", 'timestamp desc', $page, $pagesize);
				$multipage = multi_page::parse($total, $pagesize, $page, $base_url.'&method='.$method.'&page={page}', $pagestyle);
			}
		}
	break;
	case 'buyers2':
		$tbName = 'buyers';
		if ($stop = (int)$stop) {
			db::update('buyers', "status='2'", "id='$stop'");
			admin::show_message('挂起成功', $referer);
		}
		if ($unstop = (int)$unstop) {
			db::update('buyers', "status='0'", "id='$unstop'");
			admin::show_message('恢复挂起成功', $referer);
		}
		if ($lock = (int)$lock) {
			db::update('buyers', "status='4'", "id='$lock'");
			admin::show_message('锁定成功', $referer);
		}
		if ($unlock = (int)$unlock) {
			db::update('buyers', "status='0'", "id='$unlock'");
			admin::show_message('恢复锁定成功', $referer);
		}
		if ($undel = (int)$undel) {
			if ($buyer = db::one('buyers', '*', "id='$undel'")) {
				db::update('buyers', "status='0'", "id='$undel'");
				db::update('memberfields', 'buyers'.$buyer['type'].'=buyers'.$buyer['type'].'+1', "uid='$buyer[uid]'");
			}
			admin::show_message('恢复删除成功', $referer);
		}
		if (form::is_form_hash()) {
			$_POST && extract($_POST);
			$nickname = trim($nickname);
			$username = trim($username);
			if ($m == 'search') {
				if($username && $nickname){
				 $chawhere = "type='2' and nickname='$nickname' and username='$username'";
				}elseif($nickname && $username==null){
					$chawhere = "type='2' and nickname='$nickname'";
				}elseif($username && $nickname==null){
					$chawhere = "type='2' and username='$username'";
				}
				$list = db::get_list($tbName, '*', $chawhere);
			} else {
				if ($del = $_POST['del']) {
					foreach ($del as $id) {
						if ($buyer = db::one($tbName, '*', "id='$id'")) {
							$bnickname = $buyer['nickname'];
							$buyercishu = db::data_count('task', "bnickname='$bnickname' and type='2' and status<10 and status>0");
							if ($buyercishu < 1) {
								db::del_id($tbName, $id);
								db::update('memberfields', $method.'='.$method.'-1', "uid='$buyer[uid]'");
							}
						}
					}
					admin::show_message('删除成功', $base_url.'&method='.$method);
				}
			}
		}
		if ($m != 'search') {
			if ($total = db::data_count($tbName, "type=2")) {
				$list = db::get_list2($tbName, '*', "type='2'", 'timestamp desc', $page, $pagesize);
				$multipage = multi_page::parse($total, $pagesize, $page, $base_url.'&method='.$method.'&page={page}', $pagestyle);
			}
		}
	break;
	case 'buyers3':
		$tbName = 'buyers';
		if ($stop = (int)$stop) {
			db::update('buyers', "status='2'", "id='$stop'");
			admin::show_message('挂起成功', $referer);
		}
		if ($unstop = (int)$unstop) {
			db::update('buyers', "status='0'", "id='$unstop'");
			admin::show_message('恢复挂起成功', $referer);
		}
		if ($lock = (int)$lock) {
			db::update('buyers', "status='4'", "id='$lock'");
			admin::show_message('锁定成功', $referer);
		}
		if ($unlock = (int)$unlock) {
			db::update('buyers', "status='0'", "id='$unlock'");
			admin::show_message('恢复锁定成功', $referer);
		}
		if ($undel = (int)$undel) {
			if ($buyer = db::one('buyers', '*', "id='$undel'")) {
				db::update('buyers', "status='0'", "id='$undel'");
				db::update('memberfields', 'buyers'.$buyer['type'].'=buyers'.$buyer['type'].'+1', "uid='$buyer[uid]'");
			}
			admin::show_message('恢复删除成功', $referer);
		}
		if (form::is_form_hash()) {
			$_POST && extract($_POST);
			$nickname = trim($nickname);
			$username = trim($username);
			if ($m == 'search') {
				if($username && $nickname){
				 $chawhere = "type='3' and nickname='$nickname' and username='$username'";
				}elseif($nickname && $username==null){
					$chawhere = "type='3' and nickname='$nickname'";
				}elseif($username && $nickname==null){
					$chawhere = "type='3' and username='$username'";
				}
				$list = db::get_list($tbName, '*', $chawhere);
			} else {
				if ($del = $_POST['del']) {
					foreach ($del as $id) {
						if ($buyer = db::one($tbName, '*', "id='$id'")) {
							$bnickname = $buyer['nickname'];
							$buyercishu = db::data_count('task', "bnickname='$bnickname' and type='3' and status<10 and status>0");
							if ($buyercishu < 1) {
								db::del_id($tbName, $id);
								db::update('memberfields', $method.'='.$method.'-1', "uid='$buyer[uid]'");
							}
						}
					}
					admin::show_message('删除成功', $base_url.'&method='.$method);
				}
			}
		}
		if ($m != 'search') {
			if ($total = db::data_count($tbName, "type=3")) {
				$list = db::get_list2($tbName, '*', "type='3'", 'timestamp desc', $page, $pagesize);
				$multipage = multi_page::parse($total, $pagesize, $page, $base_url.'&method='.$method.'&page={page}', $pagestyle);
			}
		}
	break;
	case 'sellers1':
		$tbName = 'sellers';
		if (form::is_form_hash()) {
			$_POST && extract($_POST);
			$nickname = trim($nickname);
			$username = trim($username);
			if ($m == 'search') {
				if($username && $nickname){
				 $chawhere = "type='1' and nickname='$nickname' and username='$username'";
				}elseif($nickname && $username==null){
					$chawhere = "type='1' and nickname='$nickname'";
				}elseif($username && $nickname==null){
					$chawhere = "type='1' and username='$username'";
				}
				$list = db::get_list($tbName, '*', $chawhere);
			}else {
				if ($del = $_POST['del']) {
					foreach ($del as $id) {
						if ($seller = db::one($tbName, '*', "id='$id'")) {
							$snickname = $seller['nickname'];
							$sellercishu = db::data_count('task', "nickname='$snickname' and type='1' and status<10 and status>0");
							if ($sellercishu < 1) {
								db::del_id($tbName, $id);
								db::update('memberfields', $method.'='.$method.'-1', "uid='$seller[uid]'");
							}
						}
					}
					admin::show_message('删除成功', $base_url.'&method='.$method);
				}
			}
		}
		if ($m != 'search') {
			if ($total = db::data_count($tbName, "type=1")) {
				$list = db::get_list2($tbName, '*', "type='1'", 'timestamp1 desc', $page, $pagesize);
				$multipage = multi_page::parse($total, $pagesize, $page, $base_url.'&method='.$method.'&page={page}', $pagestyle);
			}
		}
	break;
	case 'sellers2':
		$tbName = 'sellers';
		if (form::is_form_hash()) {
			$_POST && extract($_POST);
			$nickname = trim($nickname);
			$username = trim($username);
			if ($m == 'search') {
				if($username && $nickname){
				 $chawhere = "type='2' and nickname='$nickname' and username='$username'";
				}elseif($nickname && $username==null){
					$chawhere = "type='2' and nickname='$nickname'";
				}elseif($username && $nickname==null){
					$chawhere = "type='2' and username='$username'";
				}
				$list = db::get_list($tbName, '*', $chawhere);
			} else {
				if ($del = $_POST['del']) {
					foreach ($del as $id) {
						if ($seller = db::one($tbName, '*', "id='$id'")) {
							$snickname = $seller['nickname'];
							$sellercishu = db::data_count('task', "nickname='$snickname' and type='2' and status<10 and status>0");
							if ($sellercishu < 1) {
								db::del_id($tbName, $id);
								db::update('memberfields', $method.'='.$method.'-1', "uid='$seller[uid]'");
							}
						}
					}
					admin::show_message('删除成功', $base_url.'&method='.$method);
				}
			}
		}
		if ($m != 'search') {
			if ($total = db::data_count($tbName, "type=2")) {
				$list = db::get_list2($tbName, '*', "type='2'", 'timestamp1 desc', $page, $pagesize);
				$multipage = multi_page::parse($total, $pagesize, $page, $base_url.'&method='.$method.'&page={page}', $pagestyle);
			}
		}
	break;
	case 'sellers3':
		$tbName = 'sellers';
		if (form::is_form_hash()) {
			$_POST && extract($_POST);
			$nickname = trim($nickname);
			$username = trim($username);
			if ($m == 'search') {
				if($username && $nickname){
				 $chawhere = "type='3' and nickname='$nickname' and username='$username'";
				}elseif($nickname && $username==null){
					$chawhere = "type='3' and nickname='$nickname'";
				}elseif($username && $nickname==null){
					$chawhere = "type='3' and username='$username'";
				}
				$list = db::get_list($tbName, '*', $chawhere);
			} else {
				if ($del = $_POST['del']) {
					foreach ($del as $id) {
						if ($seller = db::one($tbName, '*', "id='$id'")) {
							$snickname = $seller['nickname'];
							$sellercishu = db::data_count('task', "nickname='$snickname' and type='1' and status<10 and status>0");
							if ($sellercishu < 1) {
								db::del_id($tbName, $id);
								db::update('memberfields', $method.'='.$method.'-1', "uid='$seller[uid]'");
							}
						}
					}
					admin::show_message('删除成功', $base_url.'&method='.$method);
				}
			}
		}
		if ($m != 'search') {
			if ($total = db::data_count($tbName, "type=3")) {
				$list = db::get_list2($tbName, '*', "type='3'", 'timestamp1 desc', $page, $pagesize);
				$multipage = multi_page::parse($total, $pagesize, $page, $base_url.'&method='.$method.'&page={page}', $pagestyle);
			}
		}
	break;
	case 'view':
		if ($bid) {
			$item = db::one('buyers', '*', "id='$bid'");
			if (form::is_form_hash()) {
				if ($item['status']==0) {
				$score0 = $_POST['score0'];
				$score = $_POST['score'];
				$isreal = $_POST['isreal'];
				$id = $_POST['xiaohaoid'];
				$maxScore = $_POST['maxScore'];
					db::update('buyers', array('score0' => $score0,'score' => $score,'maxScore' => $maxScore,'isreal' => $isreal), "id='$id'");
					admin::show_message('小号'.$item['nickname'].'信息修改成功！',$base_url.'&method='.$fanhui);
				}else{
					admin::show_message('小号不是正常状态，请先修改状态！',$base_url.'&method='.$fanhui);
				}
			}
		}  
	break;
	case 'tianjia':
		if (form::is_form_hash()) {				
			$score0 = trim($_POST['score0']);
			$score = trim($_POST['score']);
			$isreal = trim($_POST['isreal']);
			$username = trim($_POST['username']);
			$nickname = trim($_POST['nickname']);
			$type = trim($_POST['type']);
			
			$utype = 0;
			$maxScore = 0;
			if($uid = member_base::getUid($username)){						
				if (!db::exists('buyers', array('type' => $type, 'nickname' => $nickname)) && !db::exists('sellers', array('type' => $type, 'nickname' => $nickName))) {	

		
					if ($maxScore == 0) $maxScore = $score + rand(500, 900);
					
					$datas = array(
						'type'      => $type,
						'uid'       => $uid,
						'username'  => $username,
						'nickname'  => $nickname,
						'password'  => '',
						'isreal'    => $isreal,
						'score0'    => $score0,
						'score'     => $score,
						'maxScore'  => $maxScore,
						'utype'     => $utype,
						'status'    => 0,
						'timestamp' => $timestamp,
						'express'   => 0
					);
					if (db::insert('buyers', $datas)) {
						db::update('memberfields', 'buyers'.$type.'=buyers'.$type.'+1', "uid='$uid'");
						admin::show_message('小号添加成功！',$base_url.'&method='.$fanhui);
					}
				}else{
					admin::show_message('该小号已经被绑定或者已经绑定了同名掌柜！',$base_url.'&method='.$fanhui);					
				}
			}else{
				admin::show_message('您添加的用户名不存在，请查证后再加！',$base_url.'&method='.$fanhui);
			}
		}		 
	break;
}
?>