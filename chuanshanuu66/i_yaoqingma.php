<?php
$top_menu=array(
	'index' => '邀请码列表',
	'create'   => '生成邀请码'
);
$top_menu_key = array_keys($top_menu);
($method && in_array($method,$top_menu_key)) || $method=$top_menu_key[0];
$tbName = 'log';
switch ($method) {
	case 'index':
		admin::getList('yaoqingma', '*', '' ,'status,ctimestamp desc');
	break;
	case 'create':
		if (form::is_form_hash()) {
			$datas = form::get( array('count', 'int'));
			$datas && extract($datas);
			if ($count) {
				$card = new payfor_yaoqingma();
				$count = $card->createCard($count);
				admin::show_message('成功生成了'.$count.'个邀请码', $base_url);
			} else admin::show_message('参数错误');
		}
	break;
}
?>