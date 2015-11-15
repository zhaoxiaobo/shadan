<?php
$top_menu=array(
	'credits'   => '积分日志',
	'money'     => 'RMB日志',
	'fabudian'  => '发布点日志',
	'vipLog'    => 'VIP消费记录',
	'ensureLog' => '商保担保金记录'
);
$top_menu_key = array_keys($top_menu);
($method && in_array($method,$top_menu_key)) || $method=$top_menu_key[0];
$tbName = 'log';
switch ($method) {
	case 'credits':
            $_POST && extract($_POST);
            if ($m == 'search')
            {
				$tmp = $base_url;
				$base_url .= '&method='.$method;
				admin::getList($tbName, '*', "type='$method' and username='$username'", 'timestamp desc');
				$base_url = $tmp;
            }
            else
            {
				$tmp = $base_url;
				$base_url .= '&method='.$method;
				admin::getList($tbName, '*', "type='$method'", 'timestamp desc');
				$base_url = $tmp;
            }
			

	break;
	case 'money':
            $_POST && extract($_POST);
            if ($m == 'search')
            {
				$tmp = $base_url;
				$base_url .= '&method='.$method;
				admin::getList($tbName, '*', "type='$method' and username='$username'", 'timestamp desc');
				$base_url = $tmp;
            }
            else
            {
				$tmp = $base_url;
				$base_url .= '&method='.$method;
				admin::getList($tbName, '*', "type='$method'", 'timestamp desc');
				$base_url = $tmp;
            }

	break;
	case 'fabudian':
            $_POST && extract($_POST);
            if ($m == 'search')
            {
				$tmp = $base_url;
				$base_url .= '&method='.$method;
				admin::getList($tbName, '*', "type='$method' and username='$username'", 'timestamp desc');
				$base_url = $tmp;
            }
            else
            {
				$tmp = $base_url;
				$base_url .= '&method='.$method;
				admin::getList($tbName, '*', "type='$method'", 'timestamp desc');
				$base_url = $tmp;
            }

	break;
	case 'vipLog':
            $_POST && extract($_POST);
            if ($m == 'search')
            {
				$tmp = $base_url;
				$base_url .= '&method='.$method;
				admin::getList('log_vip', '*', "username='$username'", 'timestamp desc');
				$base_url = $tmp;
            }
            else
            {
				$tmp = $base_url;
				$base_url .= '&method='.$method;
				admin::getList('log_vip', '*', '', 'timestamp desc');
				$base_url = $tmp;
            }

	break;
	case 'ensureLog':
            $_POST && extract($_POST);
            if ($m == 'search')
            {
				$tmp = $base_url;
				$base_url .= '&method='.$method;
				admin::getList('ensure_log', '*', "username='$username'", 'timestamp desc');
				$base_url = $tmp;
            }
            else
            {
				$tmp = $base_url;
				$base_url .= '&method='.$method;
				admin::getList('ensure_log', '*', '', 'timestamp desc');
				$base_url = $tmp;
            }

	break;
}
?>