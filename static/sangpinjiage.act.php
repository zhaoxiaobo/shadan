<?php
$p=trim($_GET["p"]);
$url='http://www.fan800.net.cn/mod/zhuaqu/sangpin.act.php?p='.$p;
$content=curl_file_get_contents($url);
echo $content;
function curl_file_get_contents($durl){ 
$cookie_file = dirname(__FILE__)."/cookie.txt"; 
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, $durl); 
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
$r = curl_exec($ch); 
curl_close($ch); 
return $r; 
}
?>