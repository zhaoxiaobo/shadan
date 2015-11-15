<?php
(!defined('IN_JB') || IN_JB!==true) && exit('error');
template::initialize('./templates/default/'.$action.'/', './cache/default/'.$action.'/');//
$ops = array('index', 'allservice', 'bangpai', 'baobei', 'beisheng', 'collect', 'commission', 'dmseo','plan','sales','sorttuoguan','tuoguan','video','wip');
($operation && in_array($operation, $ops)) || $operation = $ops[0];
switch ($operation) {
	case 'index':
		$nav_bar = array(
			array('title' => $web_name, 'url' => '/'),
			array('title' => '卖家服务'),
		);
		if ($cates = db::get_list2('help_cate', 'id,ico,name', 'sort')) {
			foreach ($cates as $k => $v) {
				$cates[$k]['list'] = db::get_list2('help', 'title,url', "cid='$v[id]'", 'sort,timestamp desc', 1, 6);
			}
			$cates = array_chunk($cates, 2);
		}
	break;
	
	
	case 'allservice':
		$nav_bar = array(
			array('title' => $web_name, 'url' => '/'),
			array('title' => '卖家服务')
		);
	break;
	case 'bangpai':
		$nav_bar = array(
			array('title' => $web_name, 'url' => '/'),
			array('title' => '卖家服务')
		);
	break;
	case 'baobei':
		$nav_bar = array(
			array('title' => $web_name, 'url' => '/'),
			array('title' => '卖家服务')
		);
	break;
	case 'beisheng':
		$nav_bar = array(
			array('title' => $web_name, 'url' => '/'),
			array('title' => '卖家服务')
		);
	break;
	case 'collect':
		$nav_bar = array(
			array('title' => $web_name, 'url' => '/'),
			array('title' => '卖家服务')
		);
	break;
	case 'commission':
		$nav_bar = array(
			array('title' => $web_name, 'url' => '/'),
			array('title' => '卖家服务')
		);
	break;
	case 'dmseo':
		$nav_bar = array(
			array('title' => $web_name, 'url' => '/'),
			array('title' => '卖家服务')
		);
	break;
	case 'plan':
		$nav_bar = array(
			array('title' => $web_name, 'url' => '/'),
			array('title' => '卖家服务')
		);
	break;
	case 'sales':
		$nav_bar = array(
			array('title' => $web_name, 'url' => '/'),
			array('title' => '卖家服务')
		);
	break;
	case 'sorttuoguan':
		$nav_bar = array(
			array('title' => $web_name, 'url' => '/'),
			array('title' => '卖家服务')
		);
	break;
	case 'tuoguan':
		$nav_bar = array(
			array('title' => $web_name, 'url' => '/'),
			array('title' => '卖家服务')
		);
	break;
	case 'video':
		$nav_bar = array(
			array('title' => $web_name, 'url' => '/'),
			array('title' => '卖家服务')
		);
	break;
	case 'wip':
		$nav_bar = array(
			array('title' => $web_name, 'url' => '/'),
			array('title' => '卖家服务')
		);
	break;
	
}
include(template::load($operation));
?>