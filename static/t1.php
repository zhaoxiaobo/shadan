<?php	
	$w=trim($_GET["w"]);
	$a=trim($_GET["a"]);
	echo base64_encode($w).'<br>';	
	echo base64_encode($a).'<br>';	

?>