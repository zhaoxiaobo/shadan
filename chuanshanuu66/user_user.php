<?php

(!defined('IN_ADMIN') || IN_ADMIN !== true) && die('error');
loadLib('member.base');
$top_menu = array(
    'index' => '用户列表',
    /* 'add'   => '添加用户', */
    'add_credits' => '增加积分',
	'jian_credits' => '扣除积分',
    'add_money' => '增加RMB',
	'jian_money' => '扣除RMB',
    'add_fabudian' => '增加信币',
    'minusPoint' => '扣除信币',
    'add_fabudian3' => '增加互刷币',
    'minusPoint3' => '扣除互刷币',
    'edit' => array('name' => '编辑用户组', 'hide' => true),
    'view' => array('name' => '用户详情', 'hide' => true),
    'check' => array('name' => '审核用户', 'hide' => true)
);
if ($edit = (int)$edit)
    $method = 'edit';
if ($view = (int)$view)
    $method = 'view';
if ($check = (int)$check)
    $method = 'check';
$top_menu_key = array_keys($top_menu);
($method && in_array($method, $top_menu_key)) || $method = $top_menu_key[0];
$tbName = 'members';
cache::get_array('questions');
switch ($method)
{
    case 'index':
        if (form::is_form_hash())
        {
            $_POST && extract($_POST);
            if ($m == 'search')
            {
                $list = db::get_list('members', '*', "username='$username' or qq='$qq' or mobilephone='$mobilephone' or   email='$email'");
            }
            else
            {
                if ($del = $_POST['del'])
                {
                    member_base::delete($del);
                    admin::show_message('删除成功', $base_url);
                }
            }
        }
        if ($m != 'search')
        {
            if (($total = member_base::total()) > 0)
            {
                $list = member_base::getList();
                $multipage = multi_page::parse($total, $pagesize, $page, $base_url . '&page={page}', $pagestyle);
            }
        }
        break;
    case 'add_credits':
        if (form::is_form_hash())
        {
            extract($_POST);
            $credits = (int) $credits;
            if ($uid = member_base::getUid($username))
            {
                member_base::addCredit($uid, $credits, '后台增加');
                admin::show_message('操作成功', $base_url);
            }
            else
            {
                admin::show_message('该用户不存在');
            }
        }
        break;
    case 'jian_credits':
        if (form::is_form_hash())
        {
            extract($_POST);
            $credits = (int) $credits;
            if ($uid = member_base::getUid($username))
            {
                member_base::addCredit($uid, -$credits, '后台扣除');
                admin::show_message('操作成功', $base_url);
            }
            else
            {
                admin::show_message('该用户不存在');
            }
        }
        break;
    case 'add_money':
        if (form::is_form_hash())
        {
            extract($_POST);
            $money = (float) $money;
            if ($uid = member_base::getUid($username))
            {
                member_base::addMoney($uid, $money, '后台增加');
                admin::show_message('操作成功', $base_url);
            }
            else
            {
                admin::show_message('该用户不存在');
            }
        }
        break;
    case 'jian_money':
        if (form::is_form_hash())
        {
            extract($_POST);
            $money = (float) $money;
            if ($uid = member_base::getUid($username))
            {
                member_base::addMoney($uid, -$money, '后台扣除');
                admin::show_message('操作成功', $base_url);
            }
            else
            {
                admin::show_message('该用户不存在');
            }
        }
        break;
    case 'add_fabudian':
        if (form::is_form_hash())
        {
            extract($_POST);
            $fabudian = (float) $fabudian;
            if ($uid = member_base::getUid($username))
            {
                member_base::addFabudian($uid, $fabudian, $type, '后台增加信币');
                admin::show_message('操作成功', $base_url);
            }
            else
            {
                admin::show_message('该用户不存在');
            }
        }
        break;
    case 'add_fabudian3':
        if (form::is_form_hash())
        {
            extract($_POST);
            $fabudian3 = (float) $fabudian3;
            if ($uid = member_base::getUid($username))
            {
                member_base::addFabudian3($uid, $fabudian3, $type, '后台增加互刷币');
                admin::show_message('操作成功', $base_url);
            }
            else
            {
                admin::show_message('该用户不存在');
            }
        }
        break;
    case 'minusPoint': 
        if (form::is_form_hash())
        {
            extract($_POST);
            $fabudian = (float) $fabudian;
            if ($uid = member_base::getUid($username))
            {
                member_base::addFabudian($uid, -$fabudian, $type, '后台扣除信币');
                admin::show_message('操作成功', $base_url);
            }
            else
            {
                admin::show_message('该用户不存在');
            }
        }
        break;
    case 'minusPoint3': 
        if (form::is_form_hash())
        {
            extract($_POST);
            $fabudian3 = (float) $fabudian3;
            if ($uid = member_base::getUid($username))
            {
                member_base::addFabudian3($uid, -$fabudian3, $type, '后台扣除互刷币');
                admin::show_message('操作成功', $base_url);
            }
            else
            {
                admin::show_message('该用户不存在');
            }
        }
        break;
    case 'add':
        /* if ($rs = admin::insert($tbName, array('name'=>'f_name','key'=>'f_key'))) {
          admin::updateUserGroups();
          admin::show_message('添加成功', $base_url);
          } elseif ($rs<0) {
          if($rs==-1)admin::show_message('参数错误！');
          if($rs==-2)admin::show_message('参数错误！');
          } */
        break;
    case 'edit':
        /* if ($rs = admin::update($tbName, array('name'=>'f_name','key'=>'f_key'), $edit)) {
          admin::updateUserGroups();
          admin::show_message('编辑成功', $base_url);
          } elseif ($rs<0) {
          if($rs==-1)admin::show_message('参数错误！');
          if($rs==-2)admin::show_message('参数错误！');
          if($rs==-3)admin::show_message('非法操作！');
          } */
        break;
    case 'view':
        $memberInfo = member_base::getMemberAll($view);
        if (form::is_form_hash())
        {
            $uid = $memberInfo['base']['id'];
            $datas = form::get(
			array('groupid', 'int'), 
			array('expressId', 'int'), 
			array('forbidden', 'int'), 
			array('status', 'int'),
			array('exam', 'int'),
			array('password', 'string'), 
			'qq','email','mobilephone',
			array('opt_password', 'string'));
            $datas && extract($datas);
            if ($groupid)
            {
                $oldGroupId = $memberInfo['base']['groupid'];
                if ($groupid != $oldGroupId)
                {
                    db::update('user_groups', 'users=users+1', "id='$groupid'");
                    db::update('user_groups', 'users=users-1', "id='$oldGroupId'");
                    db::update('members', "groupid='$groupid'", "id='$uid'");
                }
				$oldstatus = $memberInfo['base']['status'];
                if ($status != $oldstatus)
                {
                    db::update('members', "status='$status'", "id='$uid'");
                }
				$oldexam = $memberInfo['attach']['exam'];
                if ($exam != $oldexam)
                {
                    db::update('memberfields', "exam='$exam'", "uid='$uid'");
                }
            }
            $updateSql = "expressId='$expressId',forbidden='$forbidden'";
            //修改密码
            if (strlen($password)>5)
            {
                $passwordSalt = common::salt_pwd($memberInfo['base']['salt'], $password);
                $updateSql .= ",password='$passwordSalt'";
            }
            //修改操作码
            if (strlen($opt_password)>5)
            {
                $optPasswordSalt = common::salt_pwd($memberInfo['base']['salt'], $opt_password);
                $updateSql .= ",password2='$optPasswordSalt'";
            }
            db::update('members', $updateSql, "id='$uid'");
            admin::show_message('修改成功', $base_url);
        }
        break;
    case 'check':
        db::update($tbName, 'forbidden=1-forbidden', "`id` = {$check}");
        admin::show_message('操作成功', $base_url);
        break;
}
?>