<?php 
ini_set('zlib.output_compression', 'off');
if(extension_loaded('zlib')) {ob_start('ob_gzhandler');} 
header('Content-type: text/html;charset=utf-8'); 
echo '如果您能看到这行文字就表示你的服务器支持gzip如果显示不了,就不支持。'; 
if(extension_loaded('zlib')) {ob_end_flush();} 
?>
