<?php
$top_menu=array(
	'index' 	   => '卡密列表',
	'add'   	   => '添加卡密',		
	'bianji' => array('name' => '编辑', 'isHide' => true)
);
if($edit=(int)$edit)$method='edit';
$top_menu_key = array_keys($top_menu);
($method && in_array($method,$top_menu_key)) || $method=$top_menu_key[0];
				
switch($method){
	case 'index':
		admin::getList('ipbuy', '*', '', 'status asc');
	break;
	case 'add':
		if (form::is_form_hash()) {
			$datas = form::get('ruanjian','type','kahao','mima','danjia');
			$datas && extract($datas);		
			if(db::insert('ipbuy', array('ruanjian' => $ruanjian,'type' => $type, 'kahao' => $kahao, 'mima' => $mima, 'danjia' => $danjia))){
				admin::show_message('添加成功');
			}
		}
	break;
	case 'bianji':
		if ($sid) {
			$ipbuy = db::one('ipbuy', '*', "id='$sid'");			
			if (form::is_form_hash()) {				
				$datas = form::get('ruanjian','type','kahao','mima','danjia','qid');
				$datas && extract($datas);
				db::update('ipbuy', array('ruanjian' => $ruanjian,'type' => $type, 'kahao' => $kahao, 'mima' => $mima, 'danjia' => $danjia), "id='$qid'");
				admin::show_message('更新成功');
			}
		}							
	break;	
}
?>