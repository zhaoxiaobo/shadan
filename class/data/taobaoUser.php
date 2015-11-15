<?php

!defined('IN_JB') && exit('error');
loadLib($libP . 'taobaoBase');

class data_taobaoUser extends data_taobaoBase
{

    public static function getApiUser($nick)
    {
        $args = array(
            'method' => 'taobao.user.get',
            'fields' => 'uid,nick,sex,buyer_credit,seller_credit,type,promoted_type,consumer_protection',
            'nick' => $nick
        );
        if (($rs = parent::get($args)) && $rs['user'])
        {
            $rs = $rs['user'];
            if (!$rs['promoted_type'])
            {
                $html = winsock::get_html('http://rate.taobao.com/user-rate-' . $rs['uid'] . '.htm');
                if (strpos($html, '支付宝实名认证') !== false)
                    $rs['promoted_type'] = 'authentication';
                else
                    $rs['promoted_type'] = 'not authentication';
                if (preg_match('/<li class="join-status">(.+?)<\/li>/is', $html, $matches))
                {
                    if (strpos($matches[1], '未加入') !== false)
                        $rs['consumer_protection'] = false;
                    else
                        $rs['consumer_protection'] = true;
                }else
                    $rs['consumer_protection'] = false;
            }
            return $rs;
        }else
        {
            return false;
        }
    }

    public static function userExists($nick)
    {
        $args = array(
            'method' => 'taobao.shop.get',
            'fields' => 'sid',
            'nick' => $nick
        );
        if (($rs = parent::get($args)) && $rs['shop']['sid'])
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function getUser($nick)
    {
        return self::getApiUser($nick);
    }

	public static function usercheck($nick)
    {
        $sContent = file_get_contents('http://member1.taobao.com/member/userProfile.jhtml?userID='. $nick);
		$re_shop_title = '/淘宝店铺\：\s*\<a.+\>(.+)\<\/a\>/';
	    $a_shop_title = array();
	    $s_shop_title = 0;
		    if(preg_match($re_shop_title, $sContent, $a_shop_title)){
		        $s_shop_title = $a_shop_title[1];
	        }
            if ($s_shop_title>0)
            {
			print_r($s_shop_title);
            //return true;
            }
            else
            {
			print_r($s_shop_title);
           // return false;
            }
    }
	
	public static function unicode_decode($name)
	{		
		$pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
		preg_match_all($pattern, $name, $matches);
		if (!empty($matches))
		{
			$name = '';
			for ($j = 0; $j < count($matches[0]); $j++)
			{
				$str = $matches[0][$j];
				if (strpos($str, '\\u') === 0)
				{
					$code = base_convert(substr($str, 2, 2), 16, 10);
					$code2 = base_convert(substr($str, 4), 16, 10);
					$c = chr($code).chr($code2);
					$c = iconv('UCS-2BE', 'UTF-8', $c);
					$name .= $c;
				}
				else
				{
					$name .= $str;
				}
			}
		}
		return $name;
	}
	
	public static function nickname($nick)
    {
        $u=$nick;
	    $agent = $_SERVER['HTTP_USER_AGENT']; 
	    if(!strpos($agent,"MSIE") && !strpos($agent,"Chrome")) 
	      $u = iconv("UTF-8","GBK",$u);
	 
        $sc = file_get_contents('http://www.yaodamai.com/look?q='.$u);
        preg_match('/color:#333;">[0-9]\d*([\s\S]*)>查看店铺/is', $sc, $seller_rate);
        $bs=$seller_rate[0];
	
        preg_match('/>[0-9]\d*<\/b>/', $bs, $seller);
        print_r($seller[0]);
    }
	
	public static function realname($nick)
    {
        $u=$nick;
	    $agent = $_SERVER['HTTP_USER_AGENT']; 

		$u=iconv("UTF-8","GBK",$u);
		
		$u1 = urlencode($u);              
		$sc = file_get_contents('http://wwwsoso002.taodake.com/taobao_data.php?callback=jQuery171042517202557064593_1418473983677&nick='.$u1.'&chkid=0&click=373635346667686A63786365&_=1418474033832'); 	
		$sc = self::unicode_decode($sc);

		
		preg_match('/bfontstylecolor00d026(.+)fontustylepadding/', $sc, $seller);
		preg_match('/fontcolorFF0000(.+)font[^|]+?bstylecolorFF0000fontsize16px/', $seller[1], $seller2);
	    //preg_match('/fontstylecolor3400fffontweightbold(.+)font[^|]+?nbspnbspimgs/', $sc, $buyer);
		   
		if(trim($seller[1])=='支付宝个人认证' or trim($seller[1])=='支付宝个人实名认证' or trim($seller[1])=='支付宝个人实名认证已加入消费者保障服务'){
		    return '1';
		}elseif(trim($seller2[1])=='暂无认证' or $buyer[1] != null ){
		    return '2';
		}else{
		    return '0';
		}
    }
	public static function credit($nick)
    {
        $u=$nick;
	    $agent = $_SERVER['HTTP_USER_AGENT']; 

	    $u = iconv("utf-8","gbk",$u);
		$u1 = urlencode($u);              
		$sc = file_get_contents('http://wwwsoso002.taodake.com/taobao_data.php?callback=jQuery171042517202557064593_1418473983677&nick='.$u1.'&chkid=0&click=373635346667686A63786365&_=1418474033832'); 	
		$sc = self::unicode_decode($sc);		
		$sc = iconv("utf-8","gbk",$sc);
		preg_match('/fontstylecolor3400fffontweightbold(.+)font[^|]+?nbspnbspimgs/', $sc, $buyer);		
		$xinyuzhi = (int)$buyer[1];		
		return $xinyuzhi;		
    }
}

?>