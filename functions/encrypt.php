<?php

	function encryptIt( $q ) {
	    $cryptKey  = '76ED5A2E712753F556BDDE9DEBC87';
	    $qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
	    return( $qEncoded );
	}

	function decryptIt( $q ) {
	    $cryptKey  = '76ED5A2E712753F556BDDE9DEBC87';
	    $qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
	    return( $qDecoded );
	}

?>