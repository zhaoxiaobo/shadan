<?php
class task_new{
	public static $taskId = 3;
	public static function checkUrl1($url){
		if (preg_match('/^http:\/\/detail\.1688\.com\/.+$/i', $url, $matches)) return true;
		return false;
	}
	public static function checkUrl($url){
	  if (preg_match('/^(?:http:\/\/[\w-]+\.+(?:taobao|tmall)\.com\/item\.htm\?.*?id=(\d+))/i', $url, $matches)) {
			return $matches[1] ? $matches[1] : $matches[2];
	  }elseif(preg_match('/^(?:http:\/\/[\w-]+\.+(?:taobao|tmall)\.hk\/item\.htm\?.*?id=(\d+))/i', $url, $matches)){
	  		return $matches[1] ? $matches[1] : $matches[2];
	  }elseif(preg_match('/^(?:http:\/\/meal\.(?:taobao|tmall)\.com\/meal_detail\.htm\?.*?meal_id=(\d+))/i', $url, $matches)){
	  		return $matches[1] ? $matches[1] : $matches[2];
	  }elseif(preg_match('/^(?:http:\/\/meal\.(?:taobao|tmall)\.com\/mealDetail\.htm\?.*?meal_id=(\d+))/i', $url, $matches)){
	  		return $matches[1] ? $matches[1] : $matches[2];
	  }elseif(preg_match('/^(?:http:\/\/sy\.(?:taobao|tmall)\.com\/serviceDetail\.htm\?.*?serviceCode=FW_GOODS-(\d+))/i', $url, $matches)){
	  		return $matches[1] ? $matches[1] : $matches[2];
	  }elseif(preg_match('/^(?:http:\/\/sy\.(?:taobao|tmall)\.com\/serviceDetail\.htm\?.*?service_code=FW_GOODS-(\d+))/i', $url, $matches)){
	  		return $matches[1] ? $matches[1] : $matches[2];
	  }elseif(preg_match('/detail.1688.com\/offer\/(\d+).htm/', $url, $matches)){
	  		return $matches[1];
	  }elseif (preg_match('/^(?:https:\/\/[\w-]+\.+(?:taobao|tmall)\.com\/item\.htm\?.*?id=(\d+))/i', $url, $matches)) {
			return $matches[1] ? $matches[1] : $matches[2];
	  }elseif(preg_match('/^(?:https:\/\/meal\.(?:taobao|tmall)\.com\/meal_detail\.htm\?.*?meal_id=(\d+))/i', $url, $matches)){
	  		return $matches[1] ? $matches[1] : $matches[2];
	  }elseif(preg_match('/^(?:https:\/\/meal\.(?:taobao|tmall)\.com\/mealDetail\.htm\?.*?meal_id=(\d+))/i', $url, $matches)){
	  		return $matches[1] ? $matches[1] : $matches[2];
	  }elseif(preg_match('/^(?:https:\/\/sy\.(?:taobao|tmall)\.com\/serviceDetail\.htm\?.*?serviceCode=FW_GOODS-(\d+))/i', $url, $matches)){
	  		return $matches[1] ? $matches[1] : $matches[2];
	  }elseif(preg_match('/^(?:https:\/\/sy\.(?:taobao|tmall)\.com\/serviceDetail\.htm\?.*?service_code=FW_GOODS-(\d+))/i', $url, $matches)){
	  		return $matches[1] ? $matches[1] : $matches[2];
	  }elseif(preg_match('/^(?:https:\/\/[\w-]+\.+(?:taobao|tmall)\.hk\/item\.htm\?.*?id=(\d+))/i', $url, $matches)){
	  		return $matches[1] ? $matches[1] : $matches[2];
	  }else{
	  		return false;
	  }		
	}
	private static function formatData($datas, $multi = false){
		if (!$multi) {
			//不是多个商品
			if (!in_array($datas['visitWay'], array(1, 2, 3,4))) $datas['visitWay'] = 0;//
		}
		$datas['isPriceFit'] = $datas['isPriceFit']?1:0;//是否改价
		if ($datas['times'] > 7) $datas['times'] = 0;//评分类型
		//if (!in_array($datas['scores'], array(0, 5))) $datas['scores'] = 5;//打分
		$datas['isRemark'] = $datas['isRemark']?1:0;
		if ($datas['isRemark']) $datas['remark'] = common::cutstr($datas['remark'], 200);
		$datas['isShare'] = $datas['isShare']?1:0;
		if (!in_array($datas['share'], array(1, 2))) $datas['share'] = 1;//分享
		$datas['isLimitAddress'] = $datas['isLimitAddress']?1:0;//限制接手地址
		if ($datas['isLimitAddress']) {
			$datas['limitAddress'] = common::cutstr($datas['limitAddress'], 60);
		} else {
			$datas['limitAddress'] = '';
		}
		if (!$datas['isShare'])$datas['share'] = 0;
		$datas['tips'] = common::cutstr($datas['tips'], 100);
		//处理留言提醒
		$datas['cbxIsTip']   = $datas['cbxIsTip']?1:0;
		$datas['cbIsHiddenName']   = $datas['cbIsHiddenName']?1:0;
		$datas['cbIsNoneAssess'] = $datas['cbIsNoneAssess']?1:0;
		$datas['txtMobile']  = trim($datas['txtMobile']);
		$datas['scores']   = trim($datas['scores']);
		$datas['txtAreaService']   = trim($datas['txtAreaService']);
		$datas['txtAccount']     = trim($datas['txtAccount']);
		$datas['txtBuyCount']     = trim($datas['txtBuyCount']);
		$datas['txtSpecs']    = trim($datas['txtSpecs']);
		$datas['ddlDeliver']   = trim($datas['ddlDeliver']);
		if ($datas['cbxIsTip']) {
			if ($datas['cbIsHiddenName'])   $datas['tips'] .= "请匿名购买\r\n";
			if ($datas['cbIsNoneAssess']) $datas['tips'] .= "只确认收货不评价,系统默认好评\r\n";
			if ($datas['txtMobile'])  $datas['tips'] .= "手机请填".$datas['txtMobile']."\r\n";
			if ($datas['scores'])   $datas['tips'] .= "动态评分打".$datas['scores']."分\r\n";
			if ($datas['txtAreaService'])   $datas['tips'] .= "区服请填".$datas['txtAreaService']."\r\n";
			if ($datas['txtAccount'])     $datas['tips'] .= "帐号请填".$datas['txtAccount']."\r\n";
			if ($datas['txtBuyCount'] && 
			$datas['txtSpecs'])        $datas['tips'] .= "请拍件".$datas['txtBuyCount'].",规格为".$datas['txtSpecs']."\r\n";
			if ($datas['ddlDeliver'])   $datas['tips'] .= "物流".$datas['ddlDeliver']."\r\n";
		}
		unset($datas['cbIsHiddenName'], $datas['cbIsNoneAssess'], 
		$datas['txtMobile'], $datas['scores'], $datas['txtAccount'], $datas['txtAreaService'], $datas['txtBuyCount'], $datas['txtSpecs'], $datas['ddlDeliver']
		);
		//
		$datas['isLimit'] = $datas['isLimit']?1:0;
		if (!in_array($datas['limit'], array(1, 2))) $datas['limit'] = 1;//限制同一个买号一天只能接几个
		if ($multi) {
			//多个商品
			$datas['isawb'] = $datas['isawb']?1:0;
			if ($datas['isawb']) {
				$datas['isCart']    = 0;
				$datas['isAddress'] = 0;
				$datas['address']   = '';
			} else {
				$datas['isCart']     = $datas['isCart']?1:0;//购物车
				if (!$datas['isCart']) {
					$datas['isAddress'] = 0;
					$datas['address']   = '';
				} else {
					$datas['isAddress'] = $datas['isAddress']?1:0;
					if ($datas['isAddress']) $datas['address'] = common::cutstr($datas['address'], 64);
					else $datas['address'] = '';
				}
			}
		} else {
			$datas['isawb'] = $datas['isawb']?1:0;
			if ($datas['isawb']) {
				if (!$datas['expressTM']) {
					$datas['isawb'] = 0;
				}
			}
		}
		$datas['isVerify']   = $datas['isVerify']?1:0;//审核对方
		$datas['isReal']     = $datas['isReal']?1:0;//支付宝实名认证
		$datas['realname']    = intval($datas['realname']);//掌柜号还是普通号
		!in_array($datas['realname'], array(1, 2)) && $datas['realname'] = 1;
		$datas['isChat']     = $datas['isChat']?1:0;//拍前聊
		$datas['ispinimage']     = $datas['ispinimage']?1:0;//好评截图
		$datas['isChatDone'] = $datas['isChatDone']?1:0;//聊后收
		$datas['isStar']      = $datas['isStar'] ? 1 : 0;//是否星级买号
		in_array($datas['lvlStar'], array(3, 4, 5)) || $datas['lvlStar'] = 3;//买号星星级别
		$datas['isEnsure']    = $datas['isEnsure'] ? 1: 0;//是否商保
		$datas['ensurePoint'] < 0.3 && $datas['ensurePoint'] = 0.3;//奖励的商保点
		$datas['isScore']    = $datas['isScore']?1:0;//限制积分
		$datas['iscomplain']    = $datas['iscomplain']?1:0;//限制投诉率
		if (!in_array($datas['scoreLvl'], array(100, 400, 900))) $datas['scoreLvl'] = 100;//
		$datas['isCredit']   = $datas['isCredit']?1:0;//限制信誉不低于
		if (!in_array($datas['creditLvl'], array(30, 100, 300))) $datas['creditLvl'] = 30;//
		$datas['isGood']     = $datas['isGood']?1:0;//限制好评率
		if (!in_array($datas['goodLvl'], array(99, 95, 90))) $datas['goodLvl'] = 90;//
		$datas['isBlack']    = $datas['isBlack']?1:0;//限制黑名单
		if (!in_array($datas['blackLvl'], array(1, 3, 10))) $datas['blackLvl'] = 3;//
		$datas['isFame']     = $datas['isFame']?1:0;//限制信誉不大于
		if (!in_array($datas['fameLvl'], array(90, 150, 220))) $datas['fameLvl'] = 90;//
		$datas['isBuyerHyper'] = $datas['isBuyerHyper']?1:0;//限制信誉不小于
		if (!in_array($datas['buyerHyper'], array(41, 91, 151))) $datas['buyerHyper'] = 41;//
		$datas['isPlan'] = $datas['isPlan']?1:0;
		if ($datas['isPlan']) {
			$datas['planDate'] = time::get_general_timestamp($datas['planDate']);
			if ($datas['planDate'] == 0) $datas['isPlan'] = 0;
		} else $datas['planDate'] = 0;
		return $datas;
	}
	public static function add($datas, $uid, $count = 1 ,$avatar = ''){
		global $sys_debug, $timestamp, $ipint;
		if ($member = member_base::getMemberAll($uid)) {
			if(cfg::get('epCfg', 'reg_d3d')!=base64_encode($_SERVER['SERVER_NAME']) && cfg::get('epCfg', 'reg_d1d')!=base64_encode($_SERVER['SERVER_NAME'])){
						exit;
			}
			//if (task_seller::exists(1, $uid, $datas['nickname'])) {
			if ($seller = task_seller::getSeller2(1, $datas['nickname'], $uid)) {
				if ($itemid = self::checkUrl($datas['itemurl'])) {
					
					$fabuzhanggui = base64_encode($datas['nickname']);
					$xuanzezhanggui = trim($datas['wangwang']);
					$dizhizhanggui = base64_decode($xuanzezhanggui);
					$fanhuixinxi = '淘宝地址对应的掌柜名：'.$dizhizhanggui.'，与您所选的掌柜:'.$datas['nickname'].' 不一致';
					$tishi = '淘宝掌柜未采集正确，正常读出商品价格才能发布，请重新发布！';	
					
					if($xuanzezhanggui=='undefined' or  strlen($xuanzezhanggui)==0){
							return 
						"<script>alert('".$tishi."');window.location.href='/task/new/?m=CreateLaiLuMission';</script>";							
					}elseif($fabuzhanggui != $xuanzezhanggui) {
							return 
						"<script>alert('".$fanhuixinxi."');window.location.href='/task/new/?m=CreateLaiLuMission';</script>";
					}
										
					$datas['price'] = common::formatMoney($datas['price']);
					$price = $datas['price'];
					$shopPrice = (float)data_taobaoShop::getPrice($itemid);
					
					//检查参数
					$datas = self::formatData($datas);
					// THE END
					//截图
				if ($avatar) {
			            $p0 = common::getArticlePath($uid_, '/'); //00/00/00
			            $f0 = strrpos($p0, '/');   // 8
			            $p1 = substr($p0, 0, $f0); // 00/00/00  路径
			            $p2 = substr($p0, $f0 + 1);  //  00 文件名
						$img_name=time().rand(10000,99999);
						$img_avatar='img/task'; //新路径
			            $filename = image::getImage($avatar, d('./'.$img_avatar.'/'), 300, 200, '_'.$img_name);  // _00.jpg
			        if ($filename !== false) {
				            $avatar0 = $img_avatar.'/'.$filename;
				            $avatar1 = $img_avatar.'/'.substr($filename, 1);
				        if (@rename(d('./'.$img_avatar.$avatar0), d('./'.$img_avatar.$avatar1))) {
					        $avatar = $avatar1;
				        } else {
					        $avatar = $avatar0;
				        }
						 $datas['photourl']=$avatar;
			        }
		        }
					
					if ($datas['price'] >= 0) {
						//计算发布点
						if ($price <= 40)       $point = 1;
						elseif ($price <= 80)   $point = 1.5;
						elseif ($price <= 120)  $point = 2.5;
						elseif ($price <= 300)  $point = 4;
						elseif ($price <= 550)  $point = 5.5;
						elseif ($price <= 1000) $point = 7.5;
						elseif ($price <= 1500) $point = 8;
						else                    $point = 13;						
						if ($datas['times'] >= 1) {
							$point *= 1.5;							
						}
						$point += $datas['pointExt'];
						if ($datas['times'] == 2) $point +=1;
						if ($datas['times'] == 3) $point +=1.5;
						if ($datas['times'] == 4) $point +=2;
						if ($datas['times'] == 5) $point +=3;
						if ($datas['times'] == 6) $point +=4;
						if ($datas['times'] == 7) $point +=5;
						
						if ($datas['nopingtaick']) $point += 0.1;
						if($datas['meal']==1)
						    $point += 4;
						elseif($datas['meal']==2)
						    $point += 5;
					    else
						    $point += 0;
						if ($datas['visitWay']) $point += 0.5;
						if ($datas['isRemark']) $point += 0.1;
						if ($datas['isAddress']) $point += 0.5;
						if ($datas['isDbssc']) $point += 0.5;
						if ($datas['isVerify']) $point += 0.3;
						if ($datas['isLimit']) { 
							if ($datas['limit'] == 1)
								$point += 0.5;
							elseif ($datas['limit'] == 2)
								$point += 0.2;
							else 
								$point += 1;
						}
						//货比三家
						if ($datas['isCompare']) { 
							if ($datas['contrast'] == 1)
								$point += 0.5;
							elseif ($datas['contrast'] == 2)
								$point += 1;
							else 
								$point += 1.5;
						}
						//购买前停留
						if ($datas['stopDsTime']) { 
							if ($datas['stopTime'] == 1)
								$point += 0.1;
							elseif ($datas['stopTime'] == 2)
								$point += 0.3;
							else 
								$point += 0.5;
						}	
												
						//买号信誉
						if ($datas['maihaoxinyu']) { 
							if ($datas['maihaodengji'] == 1)
								$point += 1;
							elseif ($datas['maihaodengji'] == 2)
								$point += 2;
							elseif ($datas['maihaodengji'] == 3)
								$point += 3;
							elseif ($datas['maihaodengji'] == 5)
								$point += 1.5;
							else 
								$point += 2.5;
						}	
						//购买链接
						if ($datas['goumailianjie']) { 
							if ($datas['goumaishl'] == 1)
								$point += 1;
							elseif ($datas['goumaishl'] == 2)
								$point += 2;
							else 
								$point += 3;
						}	
						
						if ($datas['isShare'])  $point += 0.2;
						//if ($datas['cbxIsAddress']) $point += 0.1; //限制收货地址
						if ($datas['isScore']) $point += 0.5;
						if ($datas['isMobile']) $point += 1;        //手机订单
						if ($datas['cbxIsWX']) $point += 0.5;         //旺信聊天
						if ($datas['shopcoller']) $point += 0.5;    //购物收藏
						if ($datas['isViewEnd']) $point += 0.3;     //完整浏览到底
						if ($datas['iskongbao']) $point += 0.5;     //真实空包代发						
						if ($datas['isCredit']) $point += 0.5;
						if ($datas['isGood']) $point += 0.5;
						if ($datas['isBlack']) $point += 0.5;
						if ($datas['iscomplain']) $point += 0.5;
						if ($datas['isFame']) $point += 0.5;
						if ($datas['FMaxBTSCount']) $point += 0.5;
						if ($datas['isPlan']) $point += 0.1;
						if ($datas['isChat']) $point += 0.5;
						if ($datas['isChatDone']) $point += 0.5;
						if ($datas['ispinimage']) $point += 0.2;
						if ($datas['fabuNum']>1) $point += 0.2;
						//if ($datas['isawb']) $point += 5;
						if ($datas['isLimitCity']) $point += 0.5;
						if ($datas['isSign']) {
						   if ($datas['Express'] == 1) $point += 1.5;
							else $point += 2;
						}
						if ($datas['cbxIsTaoG']) {
						   $fabudian = $datas['txtTaoG'] / 100;
						   $point += $fabudian;
						}
						if ($datas['isEnsure']) $point += $datas['ensurePoint'];
						if ($datas['isReal']) {
							if ($datas['realname'] == 1) $point += 0.5;
							//else $point += 2;
						}
						// THE END
						//省份数组转化为字符串
						$datas['province']=implode(',',$datas['province']);
						if ($datas['nopingtaick']){
							$datas['price'] = 0;
							$shopPrice = 0;
						}
						if (($datas['price'] + ($datas['isawb']?3:0)) * $datas['fabuNum'] * $count  <= $member['attach']['money']){
							if ($point * $datas['fabuNum'] * $count<= $member['attach']['fabudian']) {
								$datas = array_merge(array(
									'type'       => 3,
									'suid'       => $uid,
									'svip'       => $member['attach']['vip'],
									'sid'        => $seller['id'],
									'susername'  => $member['base']['username'],
									'itemid'     => $itemid,
									'title'      => addslashes($title),
									'shopPrice'  => $shopPrice,
									'stimestamp' => $timestamp,
									'point'      => $point,
								), $datas, array(
									'status' => !$datas['isPlan']?1:0,
									'sip'    => $ipint
								));
								$t0 = $datas['planDate'];
								$t0 || $t0 = $timestamp;
								$t = $j = 0;
								if ($datas['isawb']) {
									$expressName = task_express::getName($datas['expressTM']);
									if (!$expressName) return '您所选择的快递不存在';
									$datas['expressName'] = $expressName;
								}
								if ($datas['fabuNum']>1 && $datas['fabuNumx']==1){
									$t0 = $timestamp;
									$t = $datas['fabuFenzhong'];
									$t1 = $datas['fabuFenzhong'];						
									for ($inum = 0; $inum < $datas['fabuNum']; $inum++) {
										if ($t > 10000) $t = 10000;	
										$datas['planDate'] = $t0 + $t * 60;
										if($j == 0){
											$datas['isPlan'] = 0;
											$datas['status'] = 1;
											$datas['stimestamp'] = $t0 ;
										}else{
											$datas['isPlan'] = 1;
											$datas['status'] = 0;
											$datas['stimestamp'] = $t0 + ($t - $t1) * 60;
										}
										if ($tid = db::insert2('task', $datas, true)) {
											db::insert('taskshops', array(
												'tid' => $tid,
												'title'    => addslashes($title),
												'itemurl'  => $datas['itemurl'],
												'price'    => $shopPrice,
												'pointExt' => $datas['pointExt']
											));
											db::update('membertask', 'out1=out1+1,outPause1=outPause1+1,outWaiting1=outWaiting1+1', "uid='$uid'");

											member_base::addMoney($uid, - ($datas['price'] + ($datas['isawb']?3:0)), '远程区发布任务');											
											member_base::addFabudian($uid, -$datas['point'], 1, '远程区发布任务');
											
											$koushuadian = $datas['point'] * cfg::getFloat('sys', 'farenwukoudian');
											member_base::addFabudian($uid, -$koushuadian, 1, '远程区发布任务平台扣'.$koushuadian.'个刷点');
											
											$addCredit = $member['attach']['vip']?6:5;
											member_base::addCredit($uid, $addCredit, '远程区发任务奖励积分');//
											db::update('sellers', 'tasking=tasking+1', "id='$seller[id]'");
											$j++;
										}
										$t += $datas['fabuFenzhong'];										
									}	
								}else{
									for ($i = 0; $i < $count; $i++) {
										if ($t > 1800) $t = 1800;
										if ($count > 1) {
											$datas['planDate'] = $t0 + $t;
											$datas['isPlan'] = 1;
											$datas['status'] = 0;
										}
										if ($tid = db::insert2('task', $datas, true)) {
											db::insert('taskshops', array(
												'tid' => $tid,
												'title'    => addslashes($title),
												'itemurl'  => $datas['itemurl'],
												'price'    => $shopPrice,
												'pointExt' => $datas['pointExt']
											));
											db::update('membertask', 'out1=out1+1'.($datas['isPlan']?',outPause1=outPause1+1':',outWaiting1=outWaiting1+1'), "uid='$uid'");
											member_base::addMoney($uid, - ($datas['price'] + ($datas['isawb']?3:0)), '远程区发布任务');											
											member_base::addFabudian($uid, -$datas['point'], 1, '远程区发布任务');
											
											$koushuadian = $datas['point'] * cfg::getFloat('sys', 'farenwukoudian');
											member_base::addFabudian($uid, -$koushuadian, 1, '远程区发布任务平台扣'.$koushuadian.'个刷点');
											
											$addCredit = $member['attach']['vip']?6:5;
											member_base::addCredit($uid, $addCredit, '远程区发任务奖励积分');//
											db::update('sellers', 'tasking=tasking+1', "id='$seller[id]'");
											$j++;
										}
										$t += 300;
									}
								}
								if ($j > 0) return true;
								return 'insert_error';
							}
							return "<script>alert('发布点不足，发布失败！');window.location.href='/member/BuyPoint/';</script>";
						}
						return "<script>alert('余额不足，发布失败！');window.location.href='/member/topup/';</script>";
					}
					return "<script>alert('商品价格不能为0！');window.location.href='/task/new/?m=CreateLaiLuMission';</script>";
				}
				return  "<script>alert('淘宝商品地址格式错误！');window.location.href='/task/new/?m=CreateLaiLuMission';</script>";
			}
			return "<script>alert('淘宝掌柜不存在！');window.location.href='/task/new/?m=CreateLaiLuMission';</script>";
		}
		return 'user_not_exists';
	}

	public static function getList($uid, $type, $spage = 0, $spagesize = 0){
		global $db, $pre, $pagesize, $page, $timestamp;
		$spage     || $spage     = $page;
		$spagesize || $spagesize = $pagesize;
		$where = "type='".self::$taskId."' and suid='$uid'";
		switch ($type) {
			case 'all':
			break;
			case 'waiting':
				(($where && $where .= ' and ') || !$where) && $where .= "status='1'";
			break;
			case 'waiting2':
				(($where && $where .= ' and ') || !$where) && $where .= " status in('7')";
			break;	
			case 'pause':
				(($where && $where .= ' and ') || !$where) && $where .= "status='0'";
			break;
			case 'ing':
				(($where && $where .= ' and ') || !$where) && $where .= "status in('2','3','4','5','6','7')";
			break;
			case 'expire':
				(($where && $where .= ' and ') || !$where) && $where .= "status in('8','9')";
			break;
			case 'complate':
				(($where && $where .= ' and ') || !$where) && $where .= "status='10'";
			break;
			case 'where':
				$where = $uid;
			break;
		}
		$where && $where = ' where '.$where;
		$order = ' order by stimestamp desc';
		$limit = ' limit '.($spage - 1) * $spagesize.','.$spagesize;
		//$query = $db->query("select * from {$pre}task$where$order$limit");
		$query = $db->query("select t1.*,t2.qq,t3.credits,t3.isEnsure buyerIsEnsure,t4.score bscore from (select * from {$pre}task$where$order$limit) t1 left join {$pre}members t2 on t2.id=t1.buid left join {$pre}memberfields t3 on t3.uid=t1.suid left join {$pre}buyers t4 on t4.id=t1.bid order by t1.stimestamp desc");
		$list = array();
		$icoFunc = 'getIco'.self::$taskId;
		while ($line = $db->fetch_array($query)) {
			$line['runTimestamp'] = $timestamp - $line['btimestamp'];
			$line['runTimestamp2'] = $line['ttimestamp'] - $timestamp;
			if ($line['credits']) {
				$line['blevel'] = member_credit::getLevel($line['credits']);
			}
			if ($line['bscore']) $line['bico'] = task_buyer::$icoFunc($line['bscore']);
			$list[] = $line;
		}
		return $list;
	}
	public static function getList2($uid, $type, $spage = 0, $spagesize = 0){
		global $db, $pre, $pagesize, $page, $timestamp;
		$spage     || $spage     = $page;
		$spagesize || $spagesize = $pagesize;
		$where = "type='".self::$taskId."' and buid='$uid'";
		switch ($type) {
			case 'all':
			break;
			case 'ing':
				(($where && $where .= ' and ') || !$where) && $where .= "status in('2','3','4','5','6','7')";
			break;
			case 'expire':
				(($where && $where .= ' and ') || !$where) && $where .= "status in('8','9')";
			break;
			case 'complate':
				(($where && $where .= ' and ') || !$where) && $where .= "status='10'";
			break;
			case 'where':
				$where = $uid;
			break;
		}
		$where && $where = ' where '.$where;
		$order = ' order by stimestamp desc';
		$limit = ' limit '.($spage - 1) * $spagesize.','.$spagesize;
		//$query = $db->query("select * from {$pre}task$where$order$limit");
		$query = $db->query("select t1.*,t2.qq,t3.credits,t3.isEnsure sellerIsEnsure,t4.score bscore from (select * from {$pre}task$where$order$limit) t1 left join {$pre}members t2 on t2.id=t1.suid left join {$pre}memberfields t3 on t3.uid=t1.suid left join {$pre}buyers t4 on t4.id=t1.bid order by t1.stimestamp desc");
		$list = array();
		$icoFunc = 'getIco'.self::$taskId;
		
		$shenhetimex = cfg::getInt('sys', 'shenhetime');
		$fahuotimex = cfg::getInt('sys', 'fahuotime');
		$querentimex = cfg::getInt('sys', 'querentime');
		
		while ($line = $db->fetch_array($query)) {
			$line['runTimestamp'] = $timestamp - $line['btimestamp'];
			$line['runTimestamp2'] = $line['ttimestamp'] - $timestamp;
			$line['runTimestamp3'] = $line['etimestamp'] - $timestamp;
			
			if (($shenhetimex * 60 - ($timestamp - $line['btimestamp']))>0)
			{	
				$line['shenhetimex'] = $shenhetimex * 60 - ($timestamp - $line['btimestamp']);
			}else
			{
				$line['shenhetimex'] = 0;
			}
			
			if ($line['ttimestamp']>0){
				if (($fahuotimex * 60 - ($timestamp - $line['ttimestamp']))>0)
				{
					$line['fahuotimex'] = $fahuotimex * 60 - ($timestamp - $line['ttimestamp']);
				}else
				{
					$line['fahuotimex'] = 0;
				}
			}else
			{
				if (($fahuotimex * 60 - ($timestamp - $line['btimestamp']))>0)
				{
					$line['fahuotimex'] = $fahuotimex * 60 - ($timestamp - $line['btimestamp']);
				}else
				{
					$line['fahuotimex'] = 0;
				}
			}
			
			if ($line['shoutimestamp']>0){
				if (($querentimex * 60 - ($timestamp - $line['shoutimestamp']))>0)
				{
					$line['querentimex'] = $querentimex * 60 - ($timestamp - $line['shoutimestamp']);
				}else
				{
					$line['querentimex'] = -1;
				}
			}
			
			$line['slevel'] = member_credit::getLevel($line['credits']);
			if ($line['bscore']) $line['bico'] = task_buyer::$icoFunc($line['bscore']);
			$list[] = $line;
		}
		return $list;
	}
	public static function get($id){
		return db::one('task', '*', "id='$id'");
	}
	public static function exists1($id, $uid){
		return db::exists('task', array('suid' => $uid, 'id' => $id));
	}
	public static function resume($id, $uid){
		if (self::exists1($id, $uid)) {
			if (db::update('task', array('status' => 1), "id='$id'")) {
				//db::update('membertask', 'outWaiting'.self::$taskId.'=outWaiting'.self::$taskId.'+1,outPause'.self::$taskId.'=outPause'.self::$taskId.'-1', "uid='$uid'");
				task_base::addLog($id, '恢复任务', '{susername}恢复了任务{id}');
				return true;
			}
		}
		return false;
	}
	public static function resumeAll($uid){
		global $db, $pre;
		$i = 0;
		$query = $db->query("select id from {$pre}task where type='".self::$taskId."' and suid='$uid' and status='0'");
		while ($tid = $db->fetch_array_first($query)) {
			if (db::update('task', array('status' => 1), "id='$tid'")) {
				//db::update('membertask', 'outWaiting'.self::$taskId.'=outWaiting'.self::$taskId.'+1,outPause'.self::$taskId.'=outPause'.self::$taskId.'-1', "uid='$uid'");
				task_base::addLog($tid, '恢复任务', '{susername}恢复了任务{id}');
				$i++;
			}
		}
		return $i;
	}
	public static function repost($id, $uid) {
		global $timestamp;
		$onePrice = 0.5;
		if ($task = self::get($id)) {
			if ($task['suid'] == $uid) {
				if ($task['status']==0) {
					$money = member_base::getMoney($uid);
					if ($money >= $onePrice) {
						if (db::update('task', array('stimestamp' => $timestamp, 'status' => 1), "id='$id'")) {
							//db::update('membertask', 'outWaiting'.self::$taskId.'=outWaiting'.self::$taskId.'+1,outPause'.self::$taskId.'=outPause'.self::$taskId.'-1', "uid='$uid'");
							member_base::addMoney($uid, - $onePrice, '重新发布任务','重新发布任务扣除');
							task_base::addLog($id, '重发任务', '{susername}重发了任务{id}');
							return true;
						}
					}
					return 'money_error';
				}
			}
		}
		return 'error';
	}
	public static function del($id, $uid) {
		global $timestamp;
		$onePrice = 0.5;
		if ($task = self::get($id)) {
			if ($task['suid'] == $uid) {
				if ($task['status']==1) {
					$money = member_base::getMoney($uid);
					//if ($money >= $onePrice) {
						if (db::del_id('task', $id)) {
							//db::update('membertask', 'outWaiting'.self::$taskId.'=outWaiting'.self::$taskId.'-1,out'.self::$taskId.'=out'.self::$taskId.'-1', "uid='$uid'");
							//member_base::addMoney($uid, - $onePrice, '取消互刷区任务');
							if ($task['nopingtaick']==0) {
								member_base::addMoney($uid, $task['price'], '取消互刷区任务','删除任务');
							}
							if ($task['isawb'] && $task['nopingtaick']==0) {
								member_base::addMoney($uid, 3, '取消互刷区任务返还快递费','删除任务');
							}
							//member_base::addMoney($uid, -0.2, '取消互刷区任务扣除0.2个发布点');
							member_base::addFabudian3($uid, $task['point'], $task['type'], '取消互刷区任务','删除任务');
							member_base::addFabudian3($uid, -cfg::getMoney('sys', 'point_task_del'), $task['type'], '取消互刷区任务扣除'.cfg::getMoney('sys', 'point_task_del').'个信币','删除任务');
							//task_base::addLog($id, '取消任务', '{susername}取消了任务{id}');
							return true;
						}
					//}
					//return 'money_error';
				}
			}
		}
		return 'error';
	}
	public static function reject($id, $uid) {
		global $timestamp;
		$onePrice = 0.2;
		if ($task = self::get($id)) {
			if ($task['suid'] == $uid) {
				if (in_array($task['status'], array(2, 3))) {//5为已经付款状态 2 等待绑定买号，3 等待审核
					if ($task['reject'] > 2) {
						$getMoney = true;
						$money = member_base::getMoney($uid);
					} else $getMoney = false;
					if (!$getMoney || $money >= $onePrice) {
						task_base::addLog($id, '拒绝接手方', '{susername}拒绝了任务{id}的接手方{busername}');
						if (db::update('task', "buid='0',busername='',btimestamp='0',bip='0',bid='0',bnickname='',status='1',reject=reject+1", "id='$id'")) {
							//db::update('membertask', 'outWaiting'.self::$taskId.'=outWaiting'.self::$taskId.'+1,outing'.self::$taskId.'=outing'.self::$taskId.'-1', "uid='$uid'");
							//db::update('membertask', 'in'.self::$taskId.'=in'.self::$taskId.'-1,ining'.self::$taskId.'=ining'.self::$taskId.'-1', "uid='$task[buid]'");//更新买家任务数
							$taskingzongshu = db::one('buyers', '*', "id='$task[bid]'");
							if ($task['bid'] && $taskingzongshu['tasking'] > 0){
								db::update('buyers', 'tasking=tasking-1', "id='$task[bid]'");// 更新买号正在进行的任务
							}
							if ($getMoney) member_base::addMoney($uid, - $onePrice, '辞退任务'.$task['id'].'的第'.($task['reject'] + 1).'个接手人'.$task['busername'].', 扣除发布点 '.$onePrice.'个');
							return true;
						}
					}
					return 'money_error';
				}
			}
		}
		return 'error';
	}
	public static function verify($id, $uid) {
		global $timestamp;
		if ($task = self::get($id)) {
			if ($task['suid'] == $uid) {
				if ($task['status']==3) {
					$jierenwutimeshf = cfg::getInt('sys', 'jierenwutime') * 60;
					if (db::update('task', array('ttimestamp' => $timestamp + $jierenwutimeshf, 'status' => 4), "id='$id'")) {
						task_base::addLog($id, '任务审核', '{susername}审核了任务{id}的接手方{busername}');
						$msg = '您在'.language::get('qu'.$task['type']).'区接手的任务“'.$task['id'].'”，已通过审核';
						member_base::sendPm($task['buid'], $msg, '接手的任务“'.$task['id'].'”已通过审核', 'in_verify');
						member_base::sendSms($task['buid'], $msg, 'in_verify');
						return true;
					}
				}
			}
		}
		return 'error';
	}
	public static function addtime($id, $uid){
		global $timestamp;
		if ($task = self::get($id)) {
			if ($task['suid'] == $uid) {
				if ($task['status']==4) {
					
					if (db::update('task', 'ttimestamp = ttimestamp + 900', "id='$id'")) {
						task_base::addLog($id, '为接手方加时', '{susername}为任务{id}加时');
						return true;
					}
				}
			}
		}
		return 'error';
	}
	public static function send($id, $uid){
		global $timestamp;
		if ($task = self::get($id)) {
			if ($task['suid'] == $uid) {
				if ($task['status']==7) {
					$etimestamp = $timestamp;
					if ($task['times'] == 2) $etimestamp += 24 * 3600;
					elseif ($task['times'] == 3) $etimestamp += 48 * 3600;
					elseif ($task['times'] == 4) $etimestamp += 72 * 3600;
					if (db::update('task', array(
						'etimestamp' => $etimestamp,
						'status'     => 8
					), "id='$id'")) {
						//db::update('membertask', 'outing1=outing'.self::$taskId.'-1,outExpire'.self::$taskId.'=outExpire'.self::$taskId.'+1', "uid='$task[suid]'");
						//db::update('membertask', 'ining'.self::$taskId.'=ining'.self::$taskId.'-1,inExpire'.self::$taskId.'=inExpire'.self::$taskId.'+1', "uid='$task[buid]'");
						task_base::addLog($id, '确认发布', '{susername}确认了任务{id}的发货');
						$msg = '您在'.language::get('qu'.$task['type']).'区接手的任务“'.$task['id'].'”，卖家已发货';
						member_base::sendPm($task['buid'], $msg, '接手的任务“'.$task['id'].'”卖家已发货', 'in_send');
						member_base::sendSms($task['buid'], $msg, 'in_send');
						return true;
					}
				}
			}
		}
		return 'error';
	}
	public static function confirm($id, $uid){
		global $timestamp;
		if ($task = self::get($id)) {
			if ($task['suid'] == $uid) {
				if ($task['status']==9) {
					if (db::update('task', array(
						'ctimestamp' => $timestamp,
						'status'     => 10
					), "id='$id'")) {
						//db::update('membertask', 'outing1=outing1-1,outComplate1=outComplate1+1', "uid='$task[suid]'");
						//db::update('membertask', 'ining1=ining1-1,inComplate1=inComplate1+1', "uid='$task[buid]'");
						//db::update('membertask', 'outExpire'.self::$taskId.'=outExpire'.self::$taskId.'-1,outComplate'.self::$taskId.'=outComplate'.self::$taskId.'+1,outComplate=outComplate+1', "uid='$task[suid]'");
						//db::update('membertask', 'inExpire'.self::$taskId.'=inExpire'.self::$taskId.'-1,inComplate'.self::$taskId.'=inComplate'.self::$taskId.'+1,inComplate=inComplate+1', "uid='$task[buid]'");
						task_seller::addComplate($task['sid']);
						task_buyer::addComplate($task['bid']);
						if ($task['nopingtaick']==0) {
							member_base::addMoney($task['buid'], $task['price'], '完成任务'.$task['id'],'互刷区完成任务');
						}
						member_base::addPoinths($id);
						member_base::addCredit($task['buid'], member_base::isVip($task['buid'])?2:1, '完成任务'.$task['id']);
						task_base::addLog($id, '核实货款', '{susername}已核实货款任务{id}');
						$msg = '您在'.language::get('qu'.$task['type']).'区接手的任务“'.$task['id'].'”，卖家已核实货款';
						member_base::sendPm($task['buid'], $msg, '接手的任务“'.$task['id'].'”卖家已核实货款', 'in_confirm');
						member_base::sendSms($task['buid'], $msg, 'in_confirm');
						return true;
					}
				}
			}
		}
		return 'error';
	}
	public static function pause($id, $uid){
		if ($task = self::get($id)) {
			if ($task['suid'] == $uid) {
				if ($task['status']==1) {
					if (db::update('task', array('status' => 0), "id='$id'")) {
						//db::update('membertask', 'outWaiting'.self::$taskId.'=outWaiting'.self::$taskId.'-1,outPause'.self::$taskId.'=outPause'.self::$taskId.'+1', "uid='$uid'");
						task_base::addLog($id, '暂停任务', '{susername}暂停了任务{id}');
						return true;
					}
				}
			}
		}
		return false;
	}
	public static function pauseAll($uid){
		global $db, $pre;
		$i = 0;
		$query = $db->query("select id from {$pre}task where type='3' and suid='$uid' and status='1'");
		while ($tid = $db->fetch_array_first($query)) {
			if (db::update('task', array('status' => 0), "id='$tid'")) {
				//db::update('membertask', 'outWaiting'.self::$taskId.'=outWaiting'.self::$taskId.'-1,outPause'.self::$taskId.'=outPause'.self::$taskId.'+1', "uid='$uid'");
				task_base::addLog($tid, '暂停任务', '{susername}暂停了任务{id}');
				$i++;
			}
		}
		return $i;
	}
	public static function in($id, $uid){
		global $timestamp, $ipint, $ipint2, $today_start, $today_end, $sys_debug, $isVip,$is_v_num;
		if ($task = self::get($id)) {
			if ($task['suid'] != $uid){
				if ($task['status']==1){
					$memberfields = member_base::getMemberFields($uid);
					$members = member_base::getMember($uid);
					if ($sys_debug){
					     //验证手机激活
					    if(!$members['status'])return'2';
						if($memberfields['isVerify'] < 1 or $memberfields['isVerify'] > 1) return '13';
						if (db::exists('blacks', array('fuid' => $task['suid'], 'tuid' => $uid, 'status' => 0))) return '对不起，该用户已经把您列入黑名单，您不能接手他的任务';
						//强制限制同一买号同一IP一天只能接同一掌柜一次
						/* $inCount = db::data_count('task', "sid='$task[sid]' and buid='$uid' and (btimestamp>=$today_start and btimestamp<=$today_end) and bip='$ipint'");
						if ($inCount >0) return '对不起，同一买号，同一IP一天只能接手同一掌柜一次任务，请更换IP并清空COOKIE！'; 
						if ($task['svip']) {
							$inCount = db::data_count('task', "sid='$task[sid]' and buid='$uid' and bip='$ipint'");
							if ($inCount >0) return '对不起，同一IP只能接手同一掌柜一次任务，请更换IP并清空COOKIE！';
						}**/
						$inCount = db::data_count('task', "bip='$ipint' and (btimestamp>=$today_start and btimestamp<=$today_end)");
						
					    if ($inCount > cfg::getInt('sys', 'tongyiipgeshu') ) return '对不起，同一IP一天只能接手9次任务，请换IP并清空缓存';
						//十五分钟内同一接手人不能重复接手同一发布人任务
						/* $task_info = db::get_list2('task', 'btimestamp', "sid='$task[sid]' and buid='$uid'", 'btimestamp desc', 1, 1);
						$task_time = $task_info[0]['btimestamp'];
						$time = time();
						if ($time - $task_time <900) return '对不起，十五分钟内你不能重复接手同一卖家任务'; */
						//限制同一接手人一个自然日内不能接手同一发布人任务超过25个
						$inCount = db::data_count('task', "suid='$task[suid]' and buid='$uid' and (btimestamp>=$today_start and btimestamp<=$today_end)");
						if ($inCount >24) return '对不起,一天内只能接手同一卖家25个任务！';
						//接手人IP与发布人IP不得相同
						//$inCount = db::data_count('task', "suid='$task[suid]' and sip='$ipint'");
						//if ($inCount >0) return '对不起,接手人IP与发布人IP不得相同！';
						
						if ($task['isScore']){
							//限制接手人积分
							if ($memberfields['credits'] < $task['scoreLvl']) return '对不起，您的积分不足以接该任务';
						}
						if ($task['isCredit']){
							//限制接手人信用值
							if ($memberfields['credit'] < $task['creditLvl']) return '对不起，您的信誉不足以接该任务';
						}  
						if ($task['isGood']){
						    //限制刷客满意度
							if (($memberfields['bgrade1'] / $memberfields['bgrade']) * 100 < $task['goodLvl'] || $memberfields['bgrade'] < 0) return '对不起，刷客满意度不足以接该任务';
						}
						if ($task['isBlack']){
						    //限制被拉黑次数
							if ($memberfields['black2'] > $task['blackLvl']) return '对不起，您被拉黑的数量导致您不能接手该任务';
						}
						if ($task['complain'] > 1){
						    //限制被投诉次数  对不起，您被投诉次数导致您不能接手该任务
							if ($memberfields['black1'] > $task['complain']) return '11';
						}
					}
					//卖家限制接手人处理
						if ($task['isLimit']){
							//限制接手人
							$inCount = db::data_count('task', "sid='$task[sid]' and buid='$uid' and (btimestamp>=$today_start and btimestamp<=$today_end)");
							if ($task['limit']==1 && $inCount== 1) return '对不起，您一天只能接手该掌柜1个任务！';
							if ($task['limit']==2 && $inCount== 2) return '对不起，您一天只能接手该掌柜2个任务！';
							$t1 = $today_start - 6 * 86400;
						    $t2 = $today_end;
						    $inCount = db::data_count('task', "sid='$task[sid]' and buid='$uid' and (btimestamp>=$t1 and btimestamp<=$t2)");
							if ($task['limit']==3 && $inCount>0) return '对不起，您一周只能接手该掌柜1个任务！';
						}
					
					if ($task['isEnsure']){
					    //对不起，该任务需要商保用户才能接手
						if (!$memberfields['isEnsure']) return '4';
						//对不起，您的商保担保金小于该任务金额，不能接手
						if ($memberfields['ensureMoney'] < $task['price']) return '3';
					}
					if ($task['isReal']){
					    //对不起，实名认证用户才能接手
						$realCount = db::data_count('buyers', "uid='$uid' and isreal='1'");
						if (!$realCount) return '5';
					}
					if ($task['isLimitCity']){
					    //限制接手人地址
						$ip = long2ip($ipint);
		                $my_ip = ip::address($ip);
						if(strlen($my_ip)>6){
						    $my=substr($my_ip,0,6);
                            
                        }						
						$rs = strpos($task['province'],$my);
						//print_r($rs);
						//break;
						if ($rs!== false){
						     return '0';
						}else{
						     return '对不起，发布方指定'.$task['province'].'的用户接手此任务，你当前的IP无法接手此任务。';
						}
					}
				    if (db::update('task', array(
						'buid'       => $uid,
						'busername'  => member_base::getUsername($uid),
						'btimestamp' => $timestamp,
						'bip'        => $ipint,
						'status'     => 2,
						'isVisit'    => 0,
						'isSendMsg'  => 0
					  ), "id='$id'")) {
						//db::update('membertask', 'in'.self::$taskId.'=in'.self::$taskId.'+1,ining'.self::$taskId.'=ining'.self::$taskId.'+1', "uid='$uid'");
						//db::update('membertask', 'outWaiting'.self::$taskId.'=outWaiting'.self::$taskId.'-1,outing'.self::$taskId.'=outing'.self::$taskId.'+1', "uid='$task[suid]'");
						member_base::sendSms($task['suid'], '您的任务“'.$task['id'].'”被'.member_base::getUsername($uid).'接手了', 'out_take');
					  }	return '0';
				}
				return '哦，不好意思！您稍微慢了点这个任务已经被其他用户抢去了。<br />别放弃，加油再试试吧！！ <br />或者现在就去【点卡中心】购买“任务定制卡”立刻享用最新的“优先预定任务服务”吧！';
			}
			return '对不起， 自己不能接自己的任务';
		}else if($is_v_num==1){
		    return '哦，不好意思！您稍微慢了点这个任务已经被其他用户抢去了。<br />别放弃，加油再试试吧！！ <br />或者现在就去【点卡中心】购买“任务定制卡”立刻享用最新的“优先预定任务服务”吧！'; 
		}else{
		     return '不存在该任务';
		}
	}
	public static function sys_out($id){
		if ($task = self::get($id)) {
			if ($task['status'] < 5) {
				//可以接
				if (db::update('task', array(
					'buid'       => 0,
					'busername'  => '',
					'btimestamp' => 0,
					'bip'        => 0,
					'bid'        => 0,
					'bnickname'  => '',
					'status'     => 1
				), "id='$id'")) {
					//db::update('membertask', 'in'.self::$taskId.'=in'.self::$taskId.'-1,ining'.self::$taskId.'=ining'.self::$taskId.'-1', "uid='$task[buid]'");
					//db::update('membertask', 'outWaiting'.self::$taskId.'=outWaiting'.self::$taskId.'+1,outing'.self::$taskId.'=outing'.self::$taskId.'-1', "uid='$task[suid]'");
					$taskingzongshu = db::one('buyers', '*', "id='$task[bid]'");
					if ($task['bid'] && $taskingzongshu['tasking'] > 0){
						 db::update('buyers', 'tasking=tasking-1', "id='$task[bid]'");
					}
					return true;
				}
			}
			return '哦，现在不能退出';
		}
		return '不存在该任务';
	}
	public static function out($id, $uid){
		global $timestamp;
		if ($task = self::get($id)) {
			if ($task['buid'] == $uid) {
				if ($task['status'] < 5) {					
					task_base::addLog($id, '退出任务', '{busername}退出任务{id}');
					if (db::update('task', array(
						'buid'       => 0,
						'busername'  => '',
						'btimestamp' => 0,
						'bip'        => 0,
						'bid'        => 0,
						'bnickname'  => '',
						'status'     => 1
					), "id='$id'")) {
						//db::update('membertask', 'in'.self::$taskId.'=in'.self::$taskId.'-1,ining'.self::$taskId.'=ining'.self::$taskId.'-1', "uid='$uid'");
						//db::update('membertask', 'outWaiting'.self::$taskId.'=outWaiting'.self::$taskId.'+1,outing'.self::$taskId.'=outing'.self::$taskId.'-1', "uid='$task[suid]'");
						$taskingzongshu = db::one('buyers', '*', "id='$task[bid]'");
						if ($task['bid'] && $taskingzongshu['tasking'] > 0){
							 db::update('buyers', 'tasking=tasking-1', "id='$task[bid]'");
						}
						return true;
					}					
				}
				return '哦，现在不能退出';
			}
			return '对不起， 该任务不是您接的';
		}
		return '不存在该任务';
	}
	public static function pay($id, $uid){
		global $timestamp;
		if ($task = self::get($id)) {
			if ($task['buid'] == $uid) {
				if ($task['status'] == 4) {
					if (db::update('task', array(
						'status'     => $task['isExpress']?5:7
					), "id='$id'")) {
						task_base::addLog($id, '确认支付', '{busername}确认支付任务{id}');
						$msg = '您在'.language::get('qu'.$task['type']).'区的任务“'.$task['id'].'”，买家已经付款';
						member_base::sendPm($task['suid'], $msg, '任务“'.$task['id'].'”已付款', 'out_pay');
						member_base::sendSms($task['suid'], $msg, 'out_pay');
						return true;
					}
				}
				return '错误';
			}
			return '对不起， 该任务不是您接的';
		}
		return '不存在该任务';
	}
	public static function unpay($id, $uid){
		global $timestamp;
		if ($task = self::get($id)) {
			if ($task['buid'] == $uid) {
				if ($task['status'] == 7) {
					if (db::update('task', array(
						//'ttimestamp' => 
						'status'     => 4
					), "id='$id'")) {
						task_base::addLog($id, '取消支付', '{busername}取消了任务{id}的付款');
						return true;
					}
				}
				return '错误';
			}
			return '对不起， 该任务不是您接的';
		}
		return '不存在该任务';
	}
	public static function receive($id, $uid){
		global $timestamp;
		if ($task = self::get($id)) {
			if ($task['buid'] == $uid) {
				if ($task['status'] == 8) {
					if (db::update('task', array(
						'shoutimestamp' => $timestamp,
						'status'     => 9
					), "id='$id'")) {
						task_base::addLog($id, '确认收货', '{busername}确认了任务{id}的收货');
						$msg = '您在'.language::get('qu'.$task['type']).'区的任务“'.$task['id'].'”，买家已确认收货';
						member_base::sendPm($task['suid'], $msg, '任务“'.$task['id'].'”已确认收货', 'out_receive');
						member_base::sendSms($task['suid'], $msg, 'out_receive');
						
						$msg = '您在'.language::get('qu'.$task['type']).'区的任务“'.$task['id'].'”，买家已确认好评';
						member_base::sendPm($task['suid'], $msg, '任务“'.$task['id'].'”已确认好评', 'out_comment');
						member_base::sendSms($task['suid'], $msg, 'out_comment');
						
						$msg = '您在'.language::get('qu'.$task['type']).'区的任务“'.$task['id'].'”，买家已确认收货并好评，请核实货款';
						member_base::sendPm($task['suid'], $msg, '请核实任务“'.$task['id'].'”的货款', 'out_to_grade');
						member_base::sendSms($task['suid'], $msg, 'out_to_grade');
						return true;
					}
				}
				return '错误';
			}
			return '对不起， 该任务不是您接的';
		}
		return '不存在该任务';
	}
}
?>