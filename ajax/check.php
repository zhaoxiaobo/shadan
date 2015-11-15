<?php
include('../class/index.php');
common::nocache();
switch($action){
	case 'username':
		if(form::is_form_hash2()){
			if($username = $_POST['username']){
				if(preg_match("([/s/S]*)",$username)){
				$rs = db::exists('members', array('username' => $username))?'1':'2';	
				}else
				{
				$rs='3';
				}
			} else 
				 {
				$rs = '-1';
			}
		} else {
			$rs = '0';
		}
	break;
	case 'parent':
		if(form::is_form_hash2()){
			$parent = $_POST['parent'];
			if($parent){
				$rs = db::exists('members', array('username' => $parent))?'1':'2';
			} else 
				 {
				$rs = '-1';
			}
		} else {
			$rs = '-1';
		}
	break;
	case 'phone':
		if(form::is_form_hash2()){
		    $phone = $_POST['phone'];
			if($phone){
			    $rs = db::exists('members', array('mobilephone' => $phone))?'1':'2';
			} else {
				$rs = '-1';
			}
		} else {
			$rs = '-1';
		}
	break;
	case 'vcode':
		if(form::is_form_hash2()){
			if (vcode2::checkPost(false)) {
				$rs = '1';
			} else {
				$rs = '2';
			}
		} else {
			$rs = '-1';
		}
	break;
}
echo $rs;
?>