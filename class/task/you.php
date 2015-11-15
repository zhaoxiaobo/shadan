<?php
class task_you{
	public static function add($datas, $uid){
		global $sys_debug, $timestamp;
		if ($member = member_base::getMemberAll($uid)) {
					$datas['price'] = common::formatMoney($datas['price']);
					$price = $datas['price'];
					$geshu = $datas['pointExt'];
					$geshuzhi = $geshu * (1+cfg::getFloat('sys', 'jiaoyikoudian'));
					$jyusername = $member['base']['username'];
					if($price<cfg::getFloat('sys', 'jiaoyijiage1')){
						return '交易价格不能低于'.cfg::getFloat('sys', 'jiaoyijiage1').'元，请重新提交。';
						break;						
					}
					if($price>cfg::getFloat('sys', 'jiaoyijiage2')){
						return '交易价格不能高于'.cfg::getFloat('sys', 'jiaoyijiage2').'元，请重新提交。';
						break;						
					}
					if($geshu<cfg::getFloat('sys', 'zuidijiaoyigeshu')){
						return '最低交易个数不能低于'.cfg::getFloat('sys', 'zuidijiaoyigeshu').'个，请重新提交。';
						break;						
					}
					$memberfields = member_base::getMemberFields($uid);	
					if ($price > 0 && $geshu > 0) {				
							if ($geshuzhi <= $memberfields['fabudian3']) {								
								if (db::insert('jiaoyi', array(					
								'jname'        => '全站通用互刷币',
								'sid'          => $uid,
								'jiaoyijiage'  => $price,
								'jiaoyigeshu'  => $geshu,
								'addTime'      => $timestamp,
								'status'       => 1,
								'username'     => $jyusername			
								))) {
										member_base::addFabudian3($uid, -$geshuzhi, 4, '发布互刷币交易');	
										return true;																		
								}else{
									return '提交的数据失败，请重新提交。';
								}															
								
							}else{							
								return '互刷币不足，发布失败';	
							}
					}else{
						return '交易价格和交易个数必须都大于0';
					}
		}else{
			return 'user_not_exists';
		}
	}
	
	public static function getList($uid, $type, $spage = 0, $spagesize = 0){
		global $db, $pre, $pagesize, $page, $timestamp;
		$spage     || $spage     = $page;
		$spagesize || $spagesize = $pagesize;
		$where = "suid='$uid'";
		switch ($type) {
			case 'all':
			break;
			case 'ing':
				(($where && $where .= ' and ') || !$where) && $where .= "status='1'";
			break;
			case 'complate':
				(($where && $where .= ' and ') || !$where) && $where .= "status='2'";
			break;
			case 'where':
				$where = $uid;
			break;
		}
		$where && $where = ' where '.$where;
		$order = ' order by status,addTime desc';
		$limit = ' limit '.($spage - 1) * $spagesize.','.$spagesize;		
		$query = $db->query("select * from {$pre}jiaoyi where sid='$uid' and status in ('1','2') order by status desc,addTime  desc$limit");
		$list = array();
		
		while ($line = $db->fetch_array($query)) {
			$list[] = $line;
		}
		return $list;
	}
	public static function getList2($uid, $type, $spage = 0, $spagesize = 0){
		global $db, $pre, $pagesize, $page, $timestamp;
		$spage     || $spage     = $page;
		$spagesize || $spagesize = $pagesize;
		$where = "bid='$uid'";
		switch ($type) {
			case 'all':
			break;
			case 'ing':
				(($where && $where .= ' and ') || !$where) && $where .= "status='1'";
			break;
			case 'complate':
				(($where && $where .= ' and ') || !$where) && $where .= "status='2'";
			break;
			case 'where':
				$where = $uid;
			break;
		}
		$where && $where = ' where '.$where;
		$order = ' order by status,addTime desc';
		$limit = ' limit '.($spage - 1) * $spagesize.','.$spagesize;		
		$query = $db->query("select * from {$pre}jiaoyi where bid='$uid' and status in ('1','2') order by status desc,addTime  desc$limit");
		$list = array();		
		while ($line = $db->fetch_array($query)) {
			$list[] = $line;
		}
		return $list;
	}

	public static function del($id, $uid) {
		global $timestamp;
		$task = db::one('jiaoyi', '*', "id='$id'");
		if ($task['sid'] == $uid) {
			if ($task['status']==1) {
					if (db::del_id('jiaoyi', $id)) {
						member_base::addFabudian3($uid, $task['jiaoyigeshu'], 4, '取消互刷币交易');							
						task_base::addLog($id, '取消互刷币交易', '取消互刷币交易{id}');
						return true;
					}
			}
		}
		return 'error';
	}

	public static function in($id, $uid){
		global $timestamp;
		if ($task = db::one('jiaoyi', '*', "id='$id'")) {
			$zongjine = $task['jiaoyijiage'] * $task['jiaoyigeshu'];
			$sid = $task['sid'];
			if ($task['sid'] != $uid){
				if ($task['status']==1){
					$memberfields = member_base::getMemberFields($uid);					
					if($memberfields['money'] > 0 && $zongjine < $memberfields['money'] && $zongjine > 0){					
						if (db::update('jiaoyi', array(
							'bid'        => $uid,
							'busername'  => member_base::getUsername($uid),
							'jiaoyiTime' => $timestamp,						
							'status'     => 2
						), "id='$id'")) {
							member_base::addMoney($uid, -$zongjine, '购买交易大厅互刷币');
							member_base::addFabudian3($uid, $task['jiaoyigeshu'], 4, '购买交易大厅互刷币');	
							member_base::addMoney($sid, $zongjine, '出售互刷币得存款');							
							return'5';
						}
					}else{
						//return '对不起，您的存款不足以购买此交易';
						return'1';
					}
				}else{
					//return '对不起， 交易已完成，请重新选择交易';
					return'2';
				}
			}else{
				//return '对不起， 自己不能接自己的交易';
				return'3';
			}
		}else{
		     //return '不存在该交易';
			 return'4';
		}
	}	
}
?>