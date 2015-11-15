<?php
$top_menu=array(
	'index' 	   => '小号列表',
	'add'   	   => '添加小号',		
	'bianji' => array('name' => '编辑', 'isHide' => true)
);
if($edit=(int)$edit)$method='edit';
$top_menu_key = array_keys($top_menu);
($method && in_array($method,$top_menu_key)) || $method=$top_menu_key[0];
					
switch($method){
	case 'index':
		admin::getList('xiaohaobuy', '*', '', 'status,regTime asc');
	break;
	case 'add':
		if (form::is_form_hash()) {
			$datas = form::get('taobaohao','type','regTime','xinyuzhi','zhifubao','danjia','taobaomima','zhifubaomima','zhifumima');
			$datas && extract($datas);		
			if(db::insert('xiaohaobuy', array('taobaohao' => $taobaohao,'type' => $type, 'regTime' => $regTime, 'xinyuzhi' => $xinyuzhi, 'zhifubao' => $zhifubao, 'danjia' => $danjia, 'taobaomima' => $taobaomima, 'zhifubaomima' => $zhifubaomima, 'zhifumima' => $zhifumima))){
				admin::show_message('添加成功');
			}
		}
	break;
	case 'bianji':
		if ($sid) {
			$xiaohaobuy = db::one('xiaohaobuy', '*', "id='$sid'");			
			if (form::is_form_hash()) {				
				$datas = form::get('taobaohao','type','regTime','xinyuzhi','zhifubao','danjia','taobaomima','zhifubaomima','zhifumima','qid');
				$datas && extract($datas);
				db::update('xiaohaobuy', array('taobaohao' => $taobaohao,'type' => $type, 'regTime' => $regTime, 'xinyuzhi' => $xinyuzhi, 'zhifubao' => $zhifubao, 'danjia' => $danjia, 'taobaomima' => $taobaomima, 'zhifubaomima' => $zhifubaomima, 'zhifumima' => $zhifumima), "id='$qid'");
				admin::show_message('更新成功');
			}
		}							
	break;	
}
?>