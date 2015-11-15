<?php
$top_menu=array(
	'index' => '加V认证列表'	

);
$top_menu_key = array_keys($top_menu);
($method && in_array($method,$top_menu_key)) || $method=$top_menu_key[0];
$tbName = 'verify';
$status = array('待审核', '已审核', '待审核','已拒绝');
switch ($method) {
	case 'index':
		if ($setStatus = (int)$setStatus) {
			checkWrite();
			if ($item = db::one('verify', '*', "id='$sid'")) {
				$uid = $item['uid'];
				if ($item['status'] == 0) {
					$kouchujine = cfg::getFloat('sys', 'next_money') ;
					if(db::update('memberfields', array('isVerify' => $setStatus), "uid='$uid'")){
						db::update('verify', array('utimestamp' => $timestamp, 'status' => $setStatus), "id='$sid'");
						if($setStatus == 3){
							member_base::addMoney($uid, $kouchujine, '拒绝加V认证返回'.$kouchujine.'元');
						}
					}
				}elseif($item['status'] == 1){
					if(db::update('memberfields', array('isVerify' => $setStatus+1), "uid='$uid'")){
						db::update('verify', array('utimestamp' => $timestamp, 'status' => $setStatus+1), "id='$sid'");						
					}
				}
			}
			common::goto_url($referer, true);
		}
		if ($delfile) {
			checkWrite();			
				$yaoshanchuwenjian = '../'.$delfile ;	
				unlink ($yaoshanchuwenjian);
			
			common::goto_url($referer, true);
		}
		admin::getList($tbName, '*', '' ,'ctimestamp desc');
	break;
}
?>