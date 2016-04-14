<!DOCTYPE html>
<html lang="en">
<head>
 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
 
    <title>InstaFeed.me - Search Instagram profiles</title>
 
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
 
</head>
<body>

<div class="page-header">
    <h1>InstaFeed.me - Search Instagram profiles</h1>

	    <?php

	    /*Get instagram uid of a user*/
		function getUID($username){

		    $username = strtolower($username); // sanitization
		    $access_token = "309165108.d64dad5.0bd360f0dab34b1f9c0e00357ef4d90e";
		    $json_link = "https://api.instagram.com/v1/users/search?q=".$username."&access_token=".$access_token;
		    $get = file_get_contents($json_link);
		    $obj = json_decode($get);

		    foreach($obj->data as $user){
		        if($user->username == $username){
		            return $user->id;
		        }
		    }
		    return '00000000'; // return this if nothing is found
		}

		//$uid = getUID('pewdiepie');
		//echo "<h1>UID: ".$uid."</h1>";

		/*Query to get user data*/
		function makeQuery($uid, $photo_count){
			$uid=$uid;
		 
			// instruction to get your access token here https://www.codeofaninja.com/2015/05/get-instagram-access-token.html
			$access_token="309165108.d64dad5.0bd360f0dab34b1f9c0e00357ef4d90e";
			$photo_count=$photo_count;
			 
			$json_link="https://api.instagram.com/v1/users/{$uid}/media/recent/?";
			$json_link.="access_token={$access_token}&count={$photo_count}";

			$get = file_get_contents($json_link);
			$obj = json_decode($get, true, 512);

			return $obj;
		}

		function searchUser(){
			$username = $_POST['username'];
			$photo_count = $_POST['photo_count'];
			$uid = getUID($username);
			$obj = makeQuery($uid, $photo_count);

			/*Display user profile data (pics, comments etc.)*/
			for($i=0; $i<$length; $i++){
				$created = $obj['data'][$i]['comments']['data'][0]['created_time'];
				$text = $obj['data'][$i]['comments']['data'][0]['text'];
				$user = $obj['data'][$i]['comments']['data'][0]['from']['username'];
				$pic = $obj['data'][$i]['comments']['data'][0]['from']['profile_picture'];

				echo "<img src='".$pic."' alt='".$user."' height='42' width='42'>";
				echo "<p><b>".$user."</b> says: ".$text."</p>";
			}

		}

		$photo_count = 12;
		$obj = makeQuery($photo_count);
		
		/*Print json obj data as an array*/
		
		echo "<pre>";
		print_r($obj);
		echo "</pre>";
		


		//$length = count($obj['data']);

		//echo "<p>Total queries: ".$length."</p>";

		/*Display user profile data (pics, comments etc.)*/
		/*for($i=0; $i<$length; $i++){
			$created = $obj['data'][$i]['comments']['data'][0]['created_time'];
			$text = $obj['data'][$i]['comments']['data'][0]['text'];
			$user = $obj['data'][$i]['comments']['data'][0]['from']['username'];
			$pic = $obj['data'][$i]['comments']['data'][0]['from']['profile_picture'];

			echo "<img src='".$pic."' alt='".$user."' height='42' width='42'>";
			echo "<p><b>".$user."</b> says: ".$text."</p>";

			//echo $obj['data'][0]['comments']['count'];
		}*/

		if(isset($_POST['submit'])){
			$username = $_POST['username'];
			$photo_count = $_POST['photo_count'];
			$uid = getUID($username);

			if($uid){
				$obj = makeQuery($uid, $photo_count);
			}
			/*Display user profile data (pics, comments etc.)*/
			for($i=0; $i<$length; $i++){
				$created = $obj['data'][$i]['comments']['data'][0]['created_time'];
				$text = $obj['data'][$i]['comments']['data'][0]['text'];
				$user = $obj['data'][$i]['comments']['data'][0]['from']['username'];
				$pic = $obj['data'][$i]['comments']['data'][0]['from']['profile_picture'];

				echo "<img src='".$pic."' alt='".$user."' height='42' width='42'>";
				echo "<p><b>".$user."</b> says: ".$text."</p>";
			}
		}

		echo "<form method='post' action='search.php'>";
		echo "<br>";
		echo "Search user<br>";
		echo "<input type='text' name='username' placeholder='Enter username...'>";
		echo "Pictures to show (number)<br>";
		echo "<input type='text' name='photo_count' placeholder='Enter photo count...'>";
		echo "<br><br>";
		echo "<input type='submit' name='submit' value='Search'>";
		echo "</form>";

		/*foreach($obj["data"] as $data){
			echo "<b>".$data."</b>";
			foreach($data as $value){
				$i++;
				$comments = $data["type"];
				echo "<p>Type:".$i." ".$comments."</p>";
				//echo "<p>".$data." => ".$value."</p>";
			}
			
		}*/
		
		/*foreach($obj["data"] as $data => $value){
			echo "<h3>".$data.". ".$value."</h3>";
			foreach($value as $info => $inside){
				echo "<p>".$info." => ".$inside."</p>";				

			}

		}*/



		/*foreach($obj["data"] as $data){
			echo "<p>".$data."</p>";
			foreach($data as $info){
				echo "<p>Info: ".$info."</p>";

				foreach($info as $comments){
					echo "<p>comments: ".$comments["location"]."</p>";
				}
			}

		}*/

/*
		foreach ($obj as $data => $comments) {
			echo "<p>".$data."</p>";
			foreach ($comments as $array => $text) {
				echo "<p>".$array." => ".$text."</p>";

				foreach ($text as $key => $value) {
					echo "<p>".$key." => ".$value."</p>";
				}

			}
		}
*/


		/*foreach($obj['pagination'] as $item) {
		    print $item['next_url'];
		    
		}*/

		?>
</div>
 
<div class="container">

<!-- <form method='post' action="<?php $_PHP_SELF ?>">
		<br>
		Search user<br>
		<input type='text' name='username' placeholder='Enter username...'>
		Pictures to show (number)<br>
		<input type='text' name='photo_count' placeholder='Enter photo count...'>
		<br><br>
		<input type='submit' name='submit' value='Search'>
		</form>
-->

<div class="col-lg-12">
 
<!-- Instagram feed will be here -->
 
</div>
</div>
 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
 
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
 
</body>
</html>