<?php
	$u="格与道";
	$u=iconv("utf-8","gbk",$u);
	$u1 = urlencode($u);              
    $sc = file_get_contents('http://wwwsoso002.taodake.com/taobao_data.php?callback=jQuery171042517202557064593_1418473983677&nick='.$u1.'&chkid=0&click=373635346667686A63786365&_=1418474033832'); 	
	$sc = unicode_decode($sc);		
    $sc = iconv("utf-8","gbk",$sc);
	preg_match('/bfontstylecolor00d026(.+)fontustylepadding/', $sc, $seller);
	preg_match('/fontcolorFF0000(.+)font[^|]+?bstylecolorFF0000fontsize16px/', $seller[1], $seller2);
	preg_match('/fontstylecolor3400fffontweightbold(.+)font[^|]+?nbspnbspimgs/', $sc, $buyer);	
	echo $seller[1].'<br>';
	echo $seller2[1].'<br>';
	echo $buyer[1].'<br>';	
	$xinyuzhi = (int)$buyer[1];
	echo $xinyuzhi;
	//echo str_replace(',', '', $xinyuzhi);
	echo '<br>';
	echo $sc.'<br>';

		
function unicode_decode($name)
{
    // 转换编码，将Unicode编码转换成可以浏览的utf-8编码
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
                $c = iconv('UCS-2', 'UTF-8', $c);
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

?>