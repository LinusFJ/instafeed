<?php

	include 'connect.php';
	include 'encrypt.php';		

	function isLoggedIn(){
			if(isset($_COOKIE['testToken']) && isset($_COOKIE['testUsername']) && verifyLogin()){
            	$accessToken = decryptIt($_COOKIE['testToken']);
				$username = decryptIt($_COOKIE['testUsername']);
				$uid = getUID($username);

				$url="https://api.instagram.com/v1/users/{$uid}/?access_token=".$accessToken;

				//$url="https://api.instagram.com/v1/users/self/feed?access_token=".$accessToken;
				$get = connectToInstagram($url);
				$obj = json_decode($get, true, 512);

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
            		echo "<li><a href='/instafeed/feed.php?feed'>Feed</a></li>";
            		echo "<li><a href='#'>Liked</a></li>";
            		echo "<li><a href='#'>Profile</a></li>";
            		echo "<li><a href='#'>Followers</a></li>";
            		echo "<li><img src='http://instagram.com/accounts/logout/' width='0' height='0' /><a href='logout.php'>Logout</a></li>";
            	echo "</ul></li>";
			}else{
				echo "<li><a href='login.php'>";
              		echo "<span></span> Login with Instagram<b class='caret'></b>";
            	echo "</a></li>";
			}
	}

	function verifyLogin(){
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
	}

?>