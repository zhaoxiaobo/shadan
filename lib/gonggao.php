<?php
(!defined('IN_JB') || IN_JB!==true) && exit('error');
template::initialize('./templates/default/'.$action.'/', './cache/default/'.$action.'/');//设置BBS模板缓存目录
$ops = array('membertishi');
($operation && in_array($operation, $ops)) || $operation = $ops[0];

loadLib('member.base');
function showmessage($message, $title = '提示', $goto_url = ''){
	common::ob_clean();
	include(template::load('showmessage'));
	exit;
}
function tip($message, $title = '继续操作', $goto_url = ''){
	common::ob_clean();
	include(template::load('tip'));
	exit;
}
switch ($operation) {
	case 'membertishi':
	break;	
}
include(template::load($operation));
?>