<?php
class payfor_desjiemi{	
	public static function encrypt($string,$key) {

		$ivArray=array(0x12, 0x34, 0x56, 0x78, 0x90, 0xAB, 0xCD, 0xEF);
		$iv=null;
		foreach ($ivArray as $element)
			$iv.=CHR($element);
 		$size = mcrypt_get_block_size ( MCRYPT_DES, MCRYPT_MODE_CBC );  
        $string = self::pkcs5Pad ( $string, $size );  

		$data =  mcrypt_encrypt(MCRYPT_DES, $key, $string, MCRYPT_MODE_CBC, $iv);

		$data = base64_encode($data);
		return $data;
	}

	public static function decrypt($string,$key) {

		$ivArray=array(0x12, 0x34, 0x56, 0x78, 0x90, 0xAB, 0xCD, 0xEF);
		$iv=null;
		foreach ($ivArray as $element)
			$iv.=CHR($element);
		$string = base64_decode($string);		
		$result =  mcrypt_decrypt(MCRYPT_DES, $key, $string, MCRYPT_MODE_CBC, $iv);
   		$result = self::pkcs5Unpad( $result );  

		return $result;
	}	
	
	public static function pkcs5Pad($text, $blocksize)  
    {  
        $pad = $blocksize - (strlen ( $text ) % $blocksize);  
        return $text . str_repeat ( chr ( $pad ), $pad );  
    }  
  
    public static function pkcs5Unpad($text)  
    {  
        $pad = ord ( $text {strlen ( $text ) - 1} );  
        if ($pad > strlen ( $text ))  
            return false;  
        if (strspn ( $text, chr ( $pad ), strlen ( $text ) - $pad ) != $pad)  
            return false;  
        return substr ( $text, 0, - 1 * $pad );  
    }  
	
}
?>