<?php
$top_menu=array(
	'index' 	   => '快递单号列表',
	'add'   	   => '添加快递单号',
	'daochu'       => '导出快递单号',
	'chaxun'       => '单号查询',
	'dhshengyu'    => '单号剩余数目',
	'bianji' => array('name' => '编辑', 'isHide' => true)
);
if($edit=(int)$edit)$method='edit';
$top_menu_key = array_keys($top_menu);
($method && in_array($method,$top_menu_key)) || $method=$top_menu_key[0];

$__list = cfg::getList('sys','kuaidiType');

					
switch($method){
	case 'index':
		admin::getList('eids', '*', '', 'status,addTime desc');
	break;
	case 'add':
		if (form::is_form_hash()) {
			$datas = form::get('eid','ename','danhaogeshu');
			$datas && extract($datas);
			$eidx = trim($eid);		
			$eid = number_format($eid,0,'','');
			if(strlen($eidx)>1){
				for ($inum = 0; $inum < $danhaogeshu; $inum++) {					
					db::insert('eids', array('eid' => $eid,'ename' => $ename, 'addTime' => $timestamp));
					$eid = $eid + 1 ;
					$timestamp = $timestamp + 1 ; 
					$eid = number_format($eid,0,'','');
				}
			}else{
				for ($inum = 0; $inum < $danhaogeshu; $inum++) {					
					db::insert('eids', array('ename' => $ename, 'addTime' => $timestamp));					
					$timestamp = $timestamp + 1 ; 					
				}
			}
			admin::show_message('添加成功');
		}
	break;
	case 'bianji':
		if ($sid) {
			$eids = db::one('eids', '*', "id='$sid'");			
			if (form::is_form_hash()) {				
				$datas = form::get('eid','qid');
				$datas && extract($datas);
				$eid = trim($eid);
				$qid = trim($qid);
				db::update('eids', array('eid' => $eid,'daochu' => 1), "id='$qid'");
				admin::show_message('更新成功');
			}
		}							
	break;
	case 'daochu':
		if (form::is_form_hash()) {
			$datas = form::get('ename','piliang');
			$datas && extract($datas);
			$ename = trim($ename);
			$piliang = trim($piliang);
			if($piliang==1){
				db::update('eids', array('daochu' => 1), "status='1' and daochu='0' and ename='$ename'");
				echo "<script>alert('批量处理成功，请进行下一步！')</script>";
			}

			if($piliang==3){
				db::update('eids', array('daochu' => 2), "status='1' and daochu='1' and ename='$ename'");
				echo "<script>alert('恭喜，批量完成成功！')</script>";
			}
			if($piliang==2){			
				if($ename=='城市100'){
					checkWrite();
					$dqpath=dirname(dirname(__FILE__));
					$path=$dqpath.'/payfor/cs100.xls';							
					
					$query = $db->query("select * from {$pre}eids where status='1' and daochu='1' and ename='$ename' order by useTime asc");	
	
					$handle=fopen($path,"w");
					$scy = '<table width="1276" border="0" cellpadding="0" cellspacing="0" style="width:957.00pt;border-collapse:collapse;table-layout:fixed;">
   <col width="133" class="xl22" style="mso-width-source:userset;mso-width-alt:4256;"/>
   <col width="72" span="3" style="width:54.00pt;"/>
   <col width="118" style="mso-width-source:userset;mso-width-alt:3776;"/>
   <col width="809" style="mso-width-source:userset;mso-width-alt:25888;"/>
   <tr height="19" style="height:14.25pt;">
    <td class="xl22" height="19" width="133" style="border:.5pt solid blue;height:14.25pt;width:99.75pt;" x:str>快递单号</td>
    <td width="72" style="border:.5pt solid blue;width:54.00pt;" x:str>发货省</td>
    <td width="72" style="border:.5pt solid blue;width:54.00pt;" x:str>发货区</td>
    <td width="72" style="border:.5pt solid blue;width:54.00pt;" x:str>收货市</td>
    <td width="118" style="border:.5pt solid blue;width:88.50pt;" x:str>收货人姓名</td>
    <td width="809" style="border:.5pt solid blue;width:606.75pt;" x:str>收货人地址</td>
   </tr>';
					$scy = iconv("utf-8","gbk",$scy);					
					fwrite($handle,$scy);
					
					fwrite($handle,strip_tags("\r\n"));	
					while ($line = $db->fetch_array($query)) {
						$scx = '<tr height="19" style="height:14.25pt;">
    <td class="xl22" height="19" width="133" style="border:.5pt solid blue;height:14.25pt;width:99.75pt;vnd.ms-excel.numberformat:@;" x:str>'.trim($line['eid']).'</td>
    <td width="72" style="border:.5pt solid blue;width:54.00pt;" x:str>'.trim($line['fasheng']).'</td>
    <td width="72" style="border:.5pt solid blue;width:54.00pt;" x:str>'.trim($line['fashi']).'</td>
    <td width="72" style="border:.5pt solid blue;width:54.00pt;" x:str>'.trim($line['shoushi']).'</td>
    <td width="118" style="border:.5pt solid blue;width:88.50pt;" x:str>'.trim($line['shouname']).'</td>
    <td width="809" style="border:.5pt solid blue;width:606.75pt;" x:str>'.trim($line['shoudizhi']).'</td>
   </tr>';
						
						$scx = iconv("utf-8","gbk",$scx);
						
						fwrite($handle,$scx);	
					fwrite($handle,strip_tags("\r\n"));	
					}
					fwrite($handle,'</table>');
					fclose($handle);
					
					echo "<script>location.href ='/payfor/cs100.xls'</script>";
				
				}
				if($ename=='飞远快递'){
					checkWrite();
					$dqpath=dirname(dirname(__FILE__));
					$path=$dqpath.'/payfor/feiyuan.xls';				
							
		
					$query = $db->query("select * from {$pre}eids where status='1' and daochu='1' and ename='$ename' order by useTime asc");	
	
					$handle=fopen($path,"w");
					$scy ='<table width="1109" border="0" cellpadding="0" cellspacing="0" style="width:831.75pt;border-collapse:collapse;table-layout:fixed;">
   <col width="139" style="mso-width-source:userset;mso-width-alt:4448;"/>
   <col width="181" style="mso-width-source:userset;mso-width-alt:5792;"/>
   <col width="138" style="mso-width-source:userset;mso-width-alt:4416;"/>
   <col width="110" style="mso-width-source:userset;mso-width-alt:3520;"/>
   <col width="188" style="mso-width-source:userset;mso-width-alt:6016;"/>
   <col width="353" style="mso-width-source:userset;mso-width-alt:11296;"/>
   <tr height="29" style="height:21.75pt;mso-height-source:userset;mso-height-alt:435;">
    <td class="xl68" height="69" width="139" rowspan="2" style="height:51.75pt;width:104.25pt;border:.5pt solid windowtext;" x:str>物流单号</td>
    <td class="xl69" width="319" colspan="2" style="width:239.25pt;border:.5pt solid blue;" x:str>发货信息</td>
    <td class="xl71" width="651" colspan="3" style="width:488.25pt;border:.5pt solid blue;" x:str>收货信息</td>
   </tr>
   <tr height="40" style="height:30.00pt;mso-height-source:userset;mso-height-alt:600;">
    <td class="xl74" style="border:.5pt solid blue;" x:str>发货市<span style="mso-spacerun:yes;">&nbsp;</span></td>
    <td class="xl75" style="border:.5pt solid blue;" x:str>发货区、县</td>
    <td class="xl75" style="border:.5pt solid blue;" x:str>收货市</td>
    <td class="xl76" style="border:.5pt solid blue;" x:str>收货区县</td>
    <td class="xl76" style="border:.5pt solid blue;" x:str>收货人</td>
   </tr>';
					$scy = iconv("utf-8","gbk",$scy);
					
					fwrite($handle,$scy);				
					fwrite($handle,strip_tags("\r\n"));	
					while ($line = $db->fetch_array($query)) {
						$scx = '<tr height="40" style="height:30.00pt;mso-height-source:userset;mso-height-alt:600;">
    <td class="xl77" height="40" style="border:.5pt solid blue;height:30.00pt;vnd.ms-excel.numberformat:@;" x:str>'.trim($line['eid']).'</td>
    <td class="xl78" style="border:.5pt solid blue;" x:str>'.trim($line['fashi']).'<span style="mso-spacerun:yes;">&nbsp;</span></td>
    <td class="xl79" style="border:.5pt solid blue;" x:str>'.trim($line['faxian']).'</td>
    <td class="xl79" style="border:.5pt solid blue;" x:str>'.trim($line['shoushi']).'</td>
    <td class="xl79" style="border:.5pt solid blue;" x:str>'.trim($line['shouxian']).'</td>
    <td class="xl79" style="border:.5pt solid blue;" x:str>'.trim($line['shouname']).'</td>
   </tr>';
						$scx = iconv("utf-8","gbk",$scx);	
						fwrite($handle,$scx);				
						
						fwrite($handle,strip_tags("\r\n"));	
					}
					fwrite($handle,'</table>');
					fclose($handle);			
					
					echo "<script>location.href ='/payfor/feiyuan.xls'</script>";
				
				}	
				if($ename=='全峰快递'){
					checkWrite();
					$dqpath=dirname(dirname(__FILE__));
					$path=$dqpath.'/payfor/quanfeng.xls';					
							
		
					$query = $db->query("select * from {$pre}eids where status='1' and daochu='1' and ename='$ename' order by useTime asc");	
	
					$handle=fopen($path,"w");
					$scy = '<table width="671" border="0" cellpadding="0" cellspacing="0" style="width:503.25pt;border-collapse:collapse;table-layout:fixed;">
   <col width="141" class="xl22" style="mso-width-source:userset;mso-width-alt:4512;"/>
   <col width="106" style="mso-width-source:userset;mso-width-alt:3392;"/>
   <col width="129" style="mso-width-source:userset;mso-width-alt:4128;"/>
   <col width="91" style="mso-width-source:userset;mso-width-alt:2912;"/>
   <col width="132" style="mso-width-source:userset;mso-width-alt:4224;"/>
   <col width="72" style="width:54.00pt;"/>
   <tr height="19" style="height:14.25pt;">
    <td class="xl22" height="19" width="141" style="height:14.25pt;width:105.75pt;border:.5pt solid blue" x:str>快递单号</td>
    <td width="106" style="width:79.50pt;border:.5pt solid blue" x:str>发货城市</td>
    <td width="129" style="width:96.75pt;border:.5pt solid blue" x:str>发货区/县</td>
    <td width="91" style="width:68.25pt;border:.5pt solid blue" x:str>收货城市</td>
    <td width="132" style="width:99.00pt;border:.5pt solid blue" x:str>收货区/县</td>
    <td width="72" style="width:54.00pt;border:.5pt solid blue" x:str>签收人</td>
   </tr>' ;
					$scy = iconv("utf-8","gbk",$scy);
					fwrite($handle,$scy);					
					fwrite($handle,strip_tags("\r\n"));	
					while ($line = $db->fetch_array($query)) {
						$scx = '<tr height="19" style="height:14.25pt;">
    <td class="xl22" height="19" style="border:.5pt solid blue;height:14.25pt;vnd.ms-excel.numberformat:@;" x:str>'.trim($line['eid']).'</td>
    <td style="border:.5pt solid blue;" x:str>'.trim($line['fashi']).'</td>
    <td style="border:.5pt solid blue;" x:str>'.trim($line['faxian']).'</td>
    <td style="border:.5pt solid blue;" x:str>'.trim($line['shoushi']).'</td>
    <td style="border:.5pt solid blue;" x:str>'.trim($line['shouxian']).'</td>
    <td style="border:.5pt solid blue;" x:str>'.trim($line['shouname']).'</td>
   </tr>';
						$scx = iconv("utf-8","gbk",$scx);
						fwrite($handle,$scx);				
						
						fwrite($handle,strip_tags("\r\n"));	
					}	
					fwrite($handle,'</table>');
					fclose($handle);				
					
					
					echo "<script>location.href ='/payfor/quanfeng.xls'</script>";
				
				}
			}
		}
	break;
	case 'chaxun':
		$chawhere = '';
		if (form::is_form_hash()) {
			$datas = form::get('eid','ename','fahuoren','planDate','planDate1');
			$datas && extract($datas);
			$eid = trim($eid);
			$fahuoren = trim($fahuoren);
			$ename = trim($ename);
			if($eid && $fahuoren){
				$chawhere = "username = '$fahuoren' and eid = '$eid' and ename = '$ename'";
			}else if(!$eid && !$fahuoren){
				if($ename){
					$chawhere = "ename = '$ename'";
				}
			}
			else{
				if($eid){
					$chawhere = "eid = '$eid' and ename = '$ename'";
				}
				if($fahuoren){
					$chawhere = "username = '$fahuoren' and ename = '$ename'";
				}
			}
			if ($planDate >0 && $planDate1 > 0){
				if ($planDate > $planDate1){
					$rs = '开始时间不能大于结束时间！' ;
				}else{
					$chawhere = "useTime>=unix_timestamp('$planDate') and useTime  <=unix_timestamp('$planDate1')";
				}
			}else{
				$rs = true;
			}
		}
		admin::getList('eids', '*', $chawhere, 'status,addTime desc');
	break;
	case 'dhshengyu':
		$__list = cfg::getList('sys','kuaidiType');
		if($__list){
			foreach($__list as $v){
				$line['kuaidiType'] = $v['key'];
				$enamex = $v['key'];
				$line['kuaidiTypeCount'] = db::data_count('eids', "ename='$enamex' and status = 0");				
            	$sList[] = $line;		
			}
		}
	break;
}
?>