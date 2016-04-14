<?php

	define("accessToken", cookieToken());
	define("username", cookieUsername());
	define("UID", cookieUID());

	/*-------------------------------------
	---------------------------------------
	-------- Connection functions ---------
	---------------------------------------
	-------------------------------------*/
	/*Connection passes url to fetch data from Instagram*/
	function connectToInstagram($url){
		$ch = curl_init();

		curl_setopt_array($ch, array (
			CURLOPT_URL				=>	$url,
			CURLOPT_RETURNTRANSFER 	=>	true,
			CURLOPT_SSL_VERIFYPEER	=>	false, //farligt enl. säkerhet?
			CURLOPT_SSL_VERIFYHOST	=>	2
		));

		$result = curl_exec($ch);

		curl_close($ch);

		return $result;
	}

	/*-------------------------------------
	---------------------------------------
	-------- Basic get functions ----------
	---------------------------------------
	-------------------------------------*/
	function cookieToken(){
		if(isset($_COOKIE['testToken'])){
			return decryptIt($_COOKIE['testToken']);
		}else{
			$accessToken = '309165108.d64dad5.0bd360f0dab34b1f9c0e00357ef4d90e';
			return $accessToken;
		}
	}

	function cookieUsername(){
		if(isset($_COOKIE['testUsername'])){
			return decryptIt($_COOKIE['testUsername']);
		}
	}

	function cookieUID(){
		if(isset($_COOKIE['testUsername'])){
			return decryptIt($_COOKIE['testUsername']);
		}
	}

	/*Get time for text printing*/
	function getTime($timeStamp){
		/*
		seconds?
		60 min * 24h = 1440 min
		1440 min = 1 day
		1440 min * 7 days = 10080 min
		10080 min * 4 weeks = 40320 min
		40320 min * 12 months = 483840 min
		*/
		$timeStamp = $timeStamp;
		$timeNow = time();
		$timeBetween = round(abs($timeNow - $timeStamp)/60);
		if($timeBetween<=1){
			$time = "1 min";
		}
		else if($timeBetween<=59){
			$time = round(abs($timeBetween))." min";
		}
		else if($timeBetween>=60 && $timeBetween<1440){
			$time = round(abs($timeBetween/60))."h";
		}
		else if($timeBetween>1440 && $timeBetween<10080){
			$time = round(abs($timeBetween/1440))."d";
		}
		else if($timeBetween>10080 && $timeBetween<40320){
			$time = round(abs($timeBetween/10080))."w";
		}
		else if($timeBetween>40320 && $timeBetween<483840){
			$time = round(abs($timeBetween/40320))."mon";
		}
		else if($timeBetween>483840){
			$time = round(abs($timeBetween/483840))."y";
		}

		return $time;
	}

	/*Get instagram uid of a user*/
	function getUID($username){
		$username = strtolower($username); // sanitization
		$url = "https://api.instagram.com/v1/users/search?q=".$username."&access_token=".accessToken; /*To be deleted!!*/
		$get = connectToInstagram($url);
		$obj = json_decode($get);

			foreach($obj->data as $user){
		        if($user->username == $username){
		            return $user->id;
		        }
		    }
	    return '00000000'; // return this if nothing is found
	}


	/*-------------------------------------
	---------------------------------------
	---------- Login functions ------------
	---------------------------------------
	-------------------------------------*/

	/*Check whether a user is logged in or not, if so, display a user menu*/
	function isLoggedIn(){
		if(isset($_COOKIE['testToken']) && isset($_COOKIE['testUsername'])){
           	$accessToken = decryptIt($_COOKIE['testToken']);
			$uid = decryptIt($_COOKIE['testUsername']);

			$url="https://api.instagram.com/v1/users/{$uid}/?access_token=".$accessToken;

			$get = connectToInstagram($url);
			$obj = json_decode($get, true, 512);

			$isValid = $obj['meta']['code'];
			
			if($isValid !== 400){
				$name = $obj['data']['username'];
				$full_name = $obj['data']['full_name'];
				$profile_picture = $obj['data']['profile_picture'];
				$bio = $obj['data']['bio'];
				$website = $obj['data']['website'];
				$media_count = $obj['data']['counts']['media'];
				$follows = $obj['data']['counts']['follows'];
				$followed_by = $obj['data']['counts']['followed_by'];

	           	echo "<li class='dropdown'><a href='' class='dropdown-toggle' data-toggle='dropdown'>";
	           		echo "<span></span>".$name."<b class='caret'></b>";
	           	echo "</a>";
	           	echo "<ul class='dropdown-menu'>";
	           		echo "<li><a href='/instafeed/profile.php?username={$name}'>Profile</a></li>";
	             	echo "<li><a href='/instafeed/index.php'>Feed</a></li>";
	           		echo "<li><a href='#'>Liked</a></li>";
	           		echo "<li><a href='#'>Followers</a></li>";
	           		echo "<li><img src='http://instagram.com/accounts/logout/' width='0' height='0' /><a href='logout.php'>Logout</a></li>";
	           	echo "</ul></li>";
	        }
		}else{
			echo "<li><a href='login.php'>";
        		echo "<span></span> Login with Instagram<b class='caret'></b>";
        	echo "</a></li>";
		}
	}

	/*function verifyLogin(){
		if(isset($_COOKIE['testToken']) && isset($_COOKIE['testUsername'])){
			$accessToken = decryptIt($_COOKIE['testToken']);
			$username = decryptIt($_COOKIE['testUsername']);
			$uid = getUID($username);
			$url="https://api.instagram.com/v1/users/{$uid}/media/recent/?access_token=".$accessToken;
				
			$get = connectToInstagram($url);
			$obj = json_decode($get, true, 512);

			$isValid = $obj['meta']['code'];

			if($isValid !== 400){
				//echo "Valid token!";
				return true;
			}else{
				//echo $obj['meta']['error_message'];
				//Try login again
				return false;
			}
		}else{
			return false;
		}
	}*/

	/*-------------------------------------
	---------------------------------------
	-------- Encryption functions ---------
	---------------------------------------
	-------------------------------------*/
	/*Encrypt token or a text string*/
	function encryptIt($q){
	    $cryptKey = '76ED5A2E712753F556BDDE9DEBC87';
	    $qEncoded = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($cryptKey), $q, MCRYPT_MODE_CBC, md5(md5($cryptKey))));
	    return($qEncoded);
	}

	/*Decrypt token or a text string*/
	function decryptIt($q){
	    $cryptKey = '76ED5A2E712753F556BDDE9DEBC87';
	    $qDecoded = rtrim( mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($cryptKey), base64_decode($q), MCRYPT_MODE_CBC, md5(md5($cryptKey))), "\0");
	    return($qDecoded);
	}

	/*-------------------------------------
	---------------------------------------
	------------ Set functions ------------
	---------------------------------------
	-------------------------------------*/
    /*Connection passes url to set a like on media*/
	function setLike($media_id){
   		$url = "https://api.instagram.com/v1/media/{$media_id}/likes";
		$ch = curl_init();

		curl_setopt_array($ch, array (
			CURLOPT_URL				=>	$url,
			CURLOPT_POST 	=>	true,
			CURLOPT_POSTFIELDS	=>	http_build_query(accessToken), //farligt enl. säkerhet?
			CURLOPT_RETURNTRANSFER	=>	true
		));

		$result = curl_exec($ch);

		curl_close($ch);

		return $result;
	}

?>