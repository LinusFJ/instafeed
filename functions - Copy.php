<?php

		define("clientID", 'd64dad5dbfc34db1b77e33eff6afd7d8');
		define("clientSecret", 'da7836e88c7347cfaad25fffdb9b92bf');
		define("redirectURI", 'http://localhost/instafeed/index.php');
		define("accessToken", '309165108.d64dad5.0bd360f0dab34b1f9c0e00357ef4d90e');

		/*Login to Instagram*/
		function login($url){
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

		function test(){
			$username = strtolower('pewdiepie'); // sanitization
		    $access_token = "309165108.d64dad5.0bd360f0dab34b1f9c0e00357ef4d90e";
			$url = "https://api.instagram.com/v1/users/search?q=".$username."&access_token=".$access_token;

		    $json_link = 
		    $get = file_get_contents($json_link);
		}

		/*Get username from search box*/
		function getSearchResult(){
			$username = $_GET['search_result'];
			return $username;
		}

		/*Get username from input box*/
		function getUser(){
			$username = $_GET['username'];
			return $username;
		}

		/*Get username from input box*/
		function getTag(){
			$tag = $_GET['tag'];
			return $tag;
		}

		/*Get username from input box*/
		function getPhoto(){
			$photo = $_GET['photo'];
			return $photo;
		}

		

		

	    

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

		/*function logInCheck(){
			//Checks if logged in
			define("clientID", 'd64dad5dbfc34db1b77e33eff6afd7d8');
			define("clientSecret", 'da7836e88c7347cfaad25fffdb9b92bf');
			define("redirectURI", 'http://localhost/instafeed/index.php');

			if($_GET['code']){
			  $code = $_GET['code'];
			  $url = "https://api.instagram.com/oauth/access_token";
			  $access_token_settings = array (
			      'client_id' => clientID,
			      'client_secret' => clientSecret,
			      'grant_type' => 'authorization_code',
			      'redirect_uri' => redirectURI,
			      'code' => $code
			    );

			  $ch = curl_init($url);
			      curl_setopt($ch, CURLOPT_POST, true);
			      curl_setopt($ch, CURLOPT_POSTFIELDS, $access_token_settings);
			      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			      $get = curl_exec($ch);
			      curl_close($ch);

			      $obj = json_decode($get, true);

				  $isValid = $obj['meta']['code'];

			      if($isValid !== 400){
			      	echo "hehehehehe";
					$accessToken = $obj['access_token']; //Personal access token for logged in users
			        $username = $obj['user']['username'];
			        $encToken = encryptIt($accessToken);
			        $encUsername = encryptIt($username);

			        setcookie('testToken', $encToken, time() + (86400 * 30), "/");
			        setcookie('testUsername', $encUsername, time() + (86400 * 30), "/");
					return true;
				  }else{
					return false;
				  }

			      
			}else{
				return false;
			}
		}*/

		








function seoUrl($string) {
    //Lower case everything
    $string = strtolower($string);
    //Make alphanumeric (removes all other characters)
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    //Clean up multiple dashes or whitespaces
    $string = preg_replace("/[\s-]+/", " ", $string);
    //Convert whitespaces and underscore to dash
    $string = preg_replace("/[\s_]/", "-", $string);
    return $string;
}

		function searchUser($name){
			$name = str_replace(' ', '+', $name);
		 
			$url="https://api.instagram.com/v1/users/search?q={$name}&access_token=".accessToken;
			$get = connectToInstagram($url);
			$obj = json_decode($get, true, 512);

			$isValid = $obj['meta']['code'];
			$length = count($obj['data']);

			if($get){
				echo "<div class='search-result'>";

				echo "<table class='table'>";
					echo "<thead class='hidden-xs'><tr>";
						echo "<th>@Users</th>";
					echo "</tr></thead>";
				echo "<tbody>";
				/*Show user profile data (pics, comments etc.)*/
				for($i=0; $i<$length; $i++){
					$profile_username = $obj['data'][$i]['username'];
					$profile_picture = $obj['data'][$i]['profile_picture'];
					$profile_full_name = $obj['data'][$i]['full_name'];


					echo "<tr><th>";

						echo "<div class='profile-picture'>";
							echo "<a href='/instafeed/profile.php?username={$profile_username}'><img src='".$profile_picture."' alt='".$profile_username."' class='img-circle' height='42' width='42'></a>";
						echo "</div>";   	
						echo "<div class='search-user'>";
						    echo "<a href='/instafeed/profile.php?username={$profile_username}'><p class='taskDescription'>@".$profile_username."</a>";
							if($profile_full_name){
								echo "<i> (".$profile_full_name.")</i></p>";
							}else{
								echo "</p></a>";
							}
						echo "</div>";
					echo "</th></tr>";
				}
				echo "</tbody>";
				echo "</table>";

				echo "</div>";
			}else if($get === FALSE){
				echo "Error 404. Please try searching something else.";
			}
		}

		function searchTags($tag){
			$tag = str_replace(' ', '', $tag);
		 
			$url="https://api.instagram.com/v1/tags/search?q={$tag}&access_token=".accessToken;
			$get = connectToInstagram($url);
			$obj = json_decode($get, true, 512);

			$isValid = $obj['meta']['code'];
			$length = count($obj['data']); 

			if($get){

				echo "<div class='search-result'>";

					echo "<table class='table'>";
						echo "<thead class='hidden-xs'><tr>";
							echo "<th>#Tags</th>";
						echo "</tr></thead>";
					echo "<tbody>";
					/*Show user profile data (pics, comments etc.)*/
					for($i=0; $i<$length; $i++){				
						$tag_count = $obj['data'][$i]['media_count'];
						$tag_name = $obj['data'][$i]['name'];
						$profile_full_name = $obj['data'][$i]['full_name'];


						echo "<tr><th>";
	  	
							echo "<div class='profile-top'>";
							    echo "<a href='/instafeed/tag.php?tag={$tag_name}'><p class='taskDescription'>#".$tag_name."</a><b> ".$tag_count." photos</b></p>";
							echo "</div>";
						echo "</th></tr>";
					}
					echo "</tbody>";
					echo "</table>";
				echo "</div>";
			}else if($get === FALSE){
				echo "Error 404. Please try searching something else.";
			}
		}

		function showProfile($username){
			echo "här: ".$_SESSION['accessToken'];

			/*Checks if logged in*/
			if(verifyLogin()){
				echo "Worked logged in!";
				$accessToken = decryptIt($_COOKIE['testToken']);
				//$username = decryptIt($_COOKIE['testUsername']);
			}else{
				$accessToken = accessToken;
			}

			echo "anv: ".$accessToken;

			$uid = getUID($username);

			/*Pagination*/
			if(isset($_GET['page'])) {
				$url="https://api.instagram.com/v1/users/{$uid}/media/recent/?access_token=".$accessToken."&max_id=".$_GET['page'];
			}else{
				$url="https://api.instagram.com/v1/users/{$uid}/media/recent/?access_token=".$accessToken;
			}

			$get = connectToInstagram($url);
			$obj = json_decode($get, true, 512);

			$nextUrl = $obj['pagination']['next_url'];
			$nextPage = $obj['pagination']['next_max_id'];
			$_SESSION["pagination"] = $nextUrl;
			$_SESSION["next_page"] = $nextPage;
			$isValid = $obj['meta']['code'];
			$length = count($obj['data']);

			/*echo "<pre>";
			print_r($obj);
			echo "</pre>";*/	

			

			if($isValid !== 400){

				/*User info*/
				$bio_url = "https://api.instagram.com/v1/users/{$uid}/?access_token=".$accessToken;
				$get = connectToInstagram($bio_url);
				$userInfo = json_decode($get, true, 512);

				$profile_username = $userInfo['data']['username'];
				$profile_picture = $userInfo['data']['profile_picture'];
				$profile_full_name = $userInfo['data']['full_name'];

				$bio = $userInfo['data']['bio'];
				$bio = preg_replace("/#(\w+)/", "<a class='blue' a href='/instafeed/tag.php?tag=$1'>#$1</a>", $bio);
				$bio = preg_replace("/@([^\s]+)/", "<a class='blue' href='/instafeed/profile.php?username=$1'>@$1</a>", $bio);
				$website = $userInfo['data']['website'];
				$media_count = $userInfo['data']['counts']['media'];
				$follows = $userInfo['data']['counts']['follows'];
				$followed_by = $userInfo['data']['counts']['followed_by'];


				echo "<div class='photo-feed-wrapper'>";

				echo "<div class='user-info'>";

						echo "<div class='user-picture'>";
							echo "<a href='/instafeed/profile.php?username={$profile_username}'><img src='".$profile_picture."' alt='".$profile_username."' class='img-circle' height='120' width='120'></a>";
						echo "</div>";  

						echo "<div class='user-left'>";
							if($profile_full_name){
								echo "<h4>".$profile_full_name."</h4>";
							}
							echo "<a href='/instafeed/profile.php?username={$profile_username}'>@".$profile_username."</a>";						
						echo "<div class='user-bottom'>";
							echo "<ul>";
								echo "<li>Followers: <b>".$followed_by."</b></li>";
								echo "<li>Following: <b>".$follows."</b></li>";
								echo "<li>Posts <b>".$media_count."</b></li>";
							echo "</ul>";
						echo "</div>";
						echo "</div>";

						echo "<div class='user-right'>";
							echo "<h5>".$bio."</h5>";
							echo "<a href='{$website}' target='_blank' rel='nofollow'>".$website."</a>";
						echo "</div>";

				echo "</div>";

				/*Show user profile data (pics, comments etc.)*/
				for($i=0; $i<$length; $i++){
					$type = $obj['data'][$i]['type'];
					$location = $obj['data'][$i]['location'];
					$comments_count = $obj['data'][$i]['comments']['count'];
					$filter = $obj['data'][$i]['filter'];
					$created_time = $obj['data'][$i]['created_time'];
					$link = $obj['data'][$i]['link']; //instagram link
					$likes_count = $obj['data'][$i]['likes']['count'];
					$img = $obj['data'][$i]['images']['low_resolution']['url'];
					//$users_in_photo = $obj['data'][$i]['users_in_photo'];
					$tags = "";
					$type = $obj['data'][$i]['type'];

					$text = $obj['data'][$i]['caption']['text'];
					$text = preg_replace("/#(\w+)/", "<a class='blue' a href='/instafeed/tag.php?tag=$1'>#$1</a>", $text);
					$text = preg_replace("/@([^\s]+)/", "<a class='blue' href='/instafeed/profile.php?username=$1'>@$1</a>", $text);
					
					//$profile_username = $obj['data'][$i]['user']['username'];
					//$profile_picture = $obj['data'][$i]['user']['profile_picture'];
					//$profile_full_name = $obj['data'][$i]['user']['full_name'];
					$id = $obj['data'][$i]['id'];
					$time = getTime($created_time);


					echo "<div class='row photo-wrapper'>";

						/*Photo box*/
						echo "<div class='photo-box'>";
							
							/*Profile-box*/
							echo "<div class='profile-box'>";

							echo "<div class='title-box'>";
						    		echo "<div class='profile-picture'>";
							    		echo "<a href='/instafeed/profile.php?username={$profile_username}'><img src='".$profile_picture."' alt='".$profile_username."' class='img-circle' height='40' width='40'></a>";
								    echo "</div>";   	
							      	echo "<div class='profile-top'>";
									echo "<span class='time'>".$time."</span>";
									echo "<a href='/instafeed/profile.php?username={$profile_username}'><span class='name'>@".$profile_username."</span></a>";							
									echo "</div>";

									echo "<div class='profile-bottom'>";
									echo "<span class='time'>".$filter."</span>";
									if($profile_full_name){
										echo "<span class='name'>".$profile_full_name."</span>";
									}
									echo "</div>";
						    echo "</div>";
							
						    echo "<div class='contents'>";
							    /*Users liked photo*/
								$j = 0;
								$commentsLength = count($obj['data'][$i]['likes']['data']);
								echo "<div class='media-content'>";

							    if($type == 'video'){
								    echo "<div class='video'>";
									    echo "<a class='video' href='/instafeed/photo.php?photo={$id}'><span></span><img src='".$img."' alt='".$profile_username."' id='".$id."' height='340' width='340'></a>";
									echo "</div>";	
							    }else{
									echo "<a href='/instafeed/photo.php?photo={$id}'><img src='".$img."' alt='".$profile_username."' id='".$id."' height='340' width='340'></a>";
								}

								echo "</div>";
					    	echo "</div>";

							echo "<div class='liked-box'>";
								echo "<span>".$likes_count." likes</span>";
								echo "<ul>";
								while($j<$commentsLength){
									echo "<li>";
									$likes_username = $obj['data'][$i]['likes']['data'][$j]['username'];
									//$likes_profile_picture = $obj['data'][$i]['likes']['data'][$j]['profile_picture'];
									//$likes_id = $obj['data'][$i]['likes']['data'][$j]['id'];
									//$likes_full_name = $obj['data'][$i]['likes']['data'][$j]['full_name'];
								
									echo "<a href='/instafeed/profile.php?username={$likes_username}'>@".$likes_username."</a>";
									$j++;
									echo "</li>";
								}
								echo "</ul>";
							echo "</div>";
							/*<-- end users liked photo-->*/

						echo "</div>";
						/*<-- profile-box end -->*/
						
						echo "</div>";
						/*<-- photo-box end -->*/








					/*Mobile responsive*/
					echo "<div class='hidden-xs'>";

						/*Comment box*/
						echo "<div class='comment-box'>";

						echo "<div class='profile-box'>";
						echo "<div class='title-box'>";

							echo "<div class='profile-picture'>";
								echo "<a href='/instafeed/profile.php?username={$profile_username}'><img src='".$profile_picture."' alt='".$profile_username."' class='img-circle' height='32' width='32'></a>";
							echo "</div>";   	
							echo "<div class='profile-top'>";
						        echo "<p class='taskDescription'>".$text."</p>";
							echo "</div>";

						    echo "</div>";

						    echo "<div class='comments'>";
						    echo "<p>".$comments_count." comments</p>";
					        echo "<ul class='commentList'>";
					            


					        /*Show comments*/
							$j = 0;
							$commentsLength = count($obj['data'][$i]['comments']['data']);
							//echo "<ul>";
							while($j<$commentsLength){
								$created_time = $obj['data'][$i]['comments']['data'][$j]['created_time'];
								$text = $obj['data'][$i]['comments']['data'][$j]['text'];
								$text = preg_replace("/#(\w+)/", "<a class='blue' a href='/instafeed/tag.php?tag=$1'>#$1</a>", $text);
								$text = preg_replace("/@([^\s]+)/", "<a class='blue' href='/instafeed/profile.php?username=$1'>@$1</a>", $text);
								$comment_username = $obj['data'][$i]['comments']['data'][$j]['from']['username'];
								$comment_picture = $obj['data'][$i]['comments']['data'][$j]['from']['profile_picture'];
								$comment_id = $obj['data'][$i]['comments']['data'][$j]['from']['id'];

								$time = getTime($created_time);
								echo "<li>";
					                echo "<div class='commenterImage'>";
										echo "<a href='/instafeed/profile.php?username={$comment_username}'><img src='".$comment_picture."' alt='".$user."' height='32' width='32'></a>";
					                echo "</div>";

					                echo "<div class='commentText'>";
					                    echo "<p class=''><a href='/instafeed/profile.php?username={$comment_username}'>@".$comment_username."</a> ".$text."</p> <span class='date sub-text'>".$time."</span>";
					                echo "</div>";
					            echo "</li>";
								$j++;
							}
					        echo "</ul>";
					    echo "</div>";

							    echo "<form class='form-inline'' role='form'>";
					    			echo "<div class='post-comment'>";
							            echo "<div class='form-group'>";
							                echo "<input class='form-control' type='text' placeholder='Add a comment...' />";
							            echo "</div>";
							            echo "<div class='form-group'>";
							                echo "<button class='btn btn-default'>Add</button>";
							            echo "</div>";
							    	echo "</div>";
							    echo "</form>";

					    echo "</div>";




							
							//echo "</ul>";

							/*Users liked photo*/
							$j = 0;
							$commentsLength = count($obj['data'][$i]['likes']['data']);
							//echo "<p>Liked by:</p>";
							/*while($j<$commentsLength){
								echo "<li class=comments>";
								$likes_username = $obj['data'][$i]['likes']['data'][$j]['username'];
								//$likes_profile_picture = $obj['data'][$i]['likes']['data'][$j]['profile_picture'];
								//$likes_id = $obj['data'][$i]['likes']['data'][$j]['id'];
								//$likes_full_name = $obj['data'][$i]['likes']['data'][$j]['full_name'];
							
								echo "<p>@".$likes_username."</p>";
								$j++;
							}*/
							echo "</li>";
						echo "</div>";
						/*<-- comments end -->*/

						echo "</div>";
						/*<-- mobile responsive end -->*/

					echo "</div>";
					/*<-- photo-wrapper end -->*/

				}
				echo "</div>";
				/*<-- photo-feed-wrapper end -->*/


				

				/*echo "<form action='/instafeed/profile.php?username={$profile_username}&page={$_SESSION["next_page"]}' method='post'>";
				echo "<input type='submit' name='paginate'>";
				echo "</form>";*/
				echo "<div class='next-page'>";
				echo "<a href='/instafeed/profile.php?username={$profile_username}&page={$_SESSION["next_page"]}'><button class='button'>View more</button></a>";
				echo "</div>";

				/*echo "<pre>";
				print_r($obj);
				echo "</pre>";*/

			}else if($isValid == 400){

			/*Checks if logged in*/
			if(verifyLogin()){
				echo "Worked logged in!";
				$accessToken = decryptIt($_COOKIE['testToken']);
				//$username = decryptIt($_COOKIE['testUsername']);
			}else{
				$accessToken = accessToken;
			}

			$url="https://api.instagram.com/v1/users/search?q={$username}&access_token=".$accessToken;
			$get = connectToInstagram($url);
			$userInfo = json_decode($get, true, 512);

			$found = FALSE;
			$i = 0;
			$length = count($userInfo['data']);

			/*Private profile*/
			while($found == FALSE || $i == $length){

				/*Private user was found*/
				if($userInfo['data'][$i]['username'] == $username){
					$found = TRUE;

					$profile_username = $userInfo['data'][$i]['username'];
					$profile_picture = $userInfo['data'][$i]['profile_picture'];
					$profile_full_name = $userInfo['data'][$i]['full_name'];

					echo "<div class='photo-feed-wrapper'>";

					echo "<div class='user-info'>";

							echo "<div class='user-picture'>";
								echo "<a href='/instafeed/profile.php?username={$profile_username}'><img src='".$profile_picture."' alt='".$profile_username."' class='img-circle' height='120' width='120'></a>";
							echo "</div>";  

							echo "<div class='user-left'>";
								if($profile_full_name){
									echo "<h4>".$profile_full_name."</h4>";
								}
								echo "<a href='/instafeed/profile.php?username={$profile_username}'>@".$profile_username."</a>";						
							echo "<div class='user-bottom'>";
								echo "<h3>This user is private</h3>";
							echo "</div>";
							echo "</div>";

					echo "</div>";
				}
				/*User does not exist*/
				else if($found == FALSE && $i == $length){
					$found = TRUE;
					echo "<div class='photo-feed-wrapper'>";

					echo "<div class='user-info'>";
							echo "<div class='user-bottom'>";
								echo "<h3>User does not exist.</h3>";
							echo "</div>";
					echo "</div>";

					echo "</div>";
				}
				$i++;
			}
				
			}
		}

		function showTags($tag){
			$tag=$tag;

			/*Checks if logged in*/
			if(verifyLogin()){
				echo "Worked logged in!";
				$accessToken = decryptIt($_COOKIE['testToken']);
				//$username = decryptIt($_COOKIE['testUsername']);
			}else{
				$accessToken = accessToken;
			}

			$url="https://api.instagram.com/v1/tags/{$tag}/media/recent?access_token=".$accessToken;
			$get = connectToInstagram($url);
			$obj = json_decode($get, true, 512);

			$length = count($obj['data']); 

			$isValid = $obj['meta']['code'];
			$length = count($obj['data']);

			if($isValid){

				echo "<div class='photo-feed-wrapper'>";

				echo "<div class='tag-info'>";

						echo "<h2>#".$tag."</h2>";

				echo "</div>";

				/*Show user profile data (pics, comments etc.)*/
				for($i=0; $i<$length; $i++){
					

					$bio = $obj['data'][$i]['bio'];
					$website = $obj['data'][$i]['website'];
					$media_count = $obj['data'][$i]['counts']['media'];
					$follows = $obj['data'][$i]['counts']['follows'];
					$followed_by = $obj['data'][$i]['counts']['followed_by'];

					$type = $obj['data'][$i]['type'];
					$location = $obj['data'][$i]['location'];
					$comments_count = $obj['data'][$i]['comments']['count'];
					$filter = $obj['data'][$i]['filter'];
					$created_time = $obj['data'][$i]['created_time'];
					$link = $obj['data'][$i]['link']; //instagram link
					$likes_count = $obj['data'][$i]['likes']['count'];
					$img = $obj['data'][$i]['images']['low_resolution']['url'];
					//$users_in_photo = $obj['data'][$i]['users_in_photo'];
					$tags = "";
					$type = $obj['data'][$i]['type'];

					$text = $obj['data'][$i]['caption']['text'];
					$text = preg_replace("/#(\w+)/", "<a class='blue' a href='/instafeed/tag.php?tag=$1'>#$1</a>", $text);
					$text = preg_replace("/@([^\s]+)/", "<a class='blue' href='/instafeed/profile.php?username=$1'>@$1</a>", $text);

					/*User info*/
					$profile_username = $obj['data'][$i]['caption']['from']['username'];
					$profile_picture = $obj['data'][$i]['caption']['from']['profile_picture'];
					$profile_full_name = $obj['data'][$i]['caption']['from']['full_name'];
					//$profile_username = $obj['data'][$i]['user']['username'];
					//$profile_picture = $obj['data'][$i]['user']['profile_picture'];
					//$profile_full_name = $obj['data'][$i]['user']['full_name'];
					$id = $obj['data'][$i]['id'];
					$time = getTime($created_time);


					echo "<div class='row photo-wrapper'>";

						/*Photo box*/
						echo "<div class='photo-box'>";
							
							/*Profile-box*/
							echo "<div class='profile-box'>";

							echo "<div class='title-box'>";
						    		echo "<div class='profile-picture'>";
							    		echo "<a href='/instafeed/profile.php?username={$profile_username}'><img src='".$profile_picture."' alt='".$profile_username."' class='img-circle' height='40' width='40'></a>";
								    echo "</div>";   	
							      	echo "<div class='profile-top'>";
									echo "<span class='time'>".$time."</span>";
									echo "<a href='/instafeed/profile.php?username={$profile_username}'><span class='name'>@".$profile_username."</span></a>";							
									echo "</div>";

									echo "<div class='profile-bottom'>";
									echo "<span class='time'>".$filter."</span>";
									if($profile_full_name){
										echo "<span class='name'>".$profile_full_name."</span>";
									}
									echo "</div>";
						    echo "</div>";
							
						    echo "<div class='contents'>";
							    /*Users liked photo*/
								$j = 0;
								$commentsLength = count($obj['data'][$i]['likes']['data']);
								echo "<div class='media-content'>";

								if($type == 'video'){
								    echo "<div class='video'>";
									    echo "<a class='video' href='/instafeed/photo.php?photo={$id}'><span></span><img src='".$img."' alt='".$profile_username."' id='".$id."' height='320' width='320'></a>";
									echo "</div>";	
							    }else{
									echo "<a href='/instafeed/photo.php?photo={$id}'><img src='".$img."' alt='".$profile_username."' id='".$id."' height='320' width='320'></a>";
								}
								
									
								echo "</div>";
					    	echo "</div>";

							echo "<div class='liked-box'>";
								echo "<span>".$likes_count." likes</span>";
								echo "<ul>";
								while($j<$commentsLength){
									echo "<li>";
									$likes_username = $obj['data'][$i]['likes']['data'][$j]['username'];
									//$likes_profile_picture = $obj['data'][$i]['likes']['data'][$j]['profile_picture'];
									//$likes_id = $obj['data'][$i]['likes']['data'][$j]['id'];
									//$likes_full_name = $obj['data'][$i]['likes']['data'][$j]['full_name'];
								
									echo "<a href='/instafeed/profile.php?username={$likes_username}'>@".$likes_username."</a>";
									$j++;
									echo "</li>";
								}
								echo "</ul>";
							echo "</div>";
							/*<-- end users liked photo-->*/

						echo "</div>";
						/*<-- profile-box end -->*/

						echo "</div>";
						/*<-- photo-box end -->*/









						/*Mobile responsive*/
						echo "<div class='hidden-xs'>";
						/*Comment box*/
						echo "<div class='comment-box'>";

						echo "<div class='profile-box'>";
						echo "<div class='title-box'>";

							echo "<div class='profile-picture'>";
								echo "<a href='/instafeed/profile.php?username={$profile_username}'><img src='".$profile_picture."' alt='".$profile_username."' class='img-circle' height='32' width='32'></a>";
							echo "</div>";   	
							echo "<div class='profile-top'>";
						        echo "<p class='taskDescription'>".$text."</p>";
							echo "</div>";

						    echo "</div>";

						    echo "<div class='comments'>";
						    echo "<p>".$comments_count." comments</p>";
					        echo "<ul class='commentList'>";
					            


					        /*Show comments*/
							$j = 0;
							$commentsLength = count($obj['data'][$i]['comments']['data']);
							//echo "<ul>";
							while($j<$commentsLength){
								$created_time = $obj['data'][$i]['comments']['data'][$j]['created_time'];
								$text = $obj['data'][$i]['comments']['data'][$j]['text'];
								$text = preg_replace("/#(\w+)/", " <a class='blue' a href='/instafeed/tag.php?tag=$1'>#$1</a>", $text);
								$text = preg_replace("/@([^\s]+)/", " <a class='blue' href='/instafeed/profile.php?username=$1'>@$1</a>", $text);
								$comment_username = $obj['data'][$i]['comments']['data'][$j]['from']['username'];
								$comment_picture = $obj['data'][$i]['comments']['data'][$j]['from']['profile_picture'];
								$comment_id = $obj['data'][$i]['comments']['data'][$j]['from']['id'];

								$time = getTime($created_time);
								echo "<li>";
					                echo "<div class='commenterImage'>";
										echo "<a href='/instafeed/profile.php?username={$comment_username}'><img src='".$comment_picture."' alt='".$user."' height='32' width='32'></a>";
					                echo "</div>";

					                echo "<div class='commentText'>";
					                    echo "<p class=''><a href='/instafeed/profile.php?username={$comment_username}'>@".$comment_username."</a> ".$text."</p> <span class='date sub-text'>".$time."</span>";
					                echo "</div>";
					            echo "</li>";
								$j++;
							}
					        echo "</ul>";
					    echo "</div>";

							    echo "<form class='form-inline'' role='form'>";
					    			echo "<div class='post-comment'>";
							            echo "<div class='form-group'>";
							                echo "<input class='form-control' type='text' placeholder='Add a comment...' />";
							            echo "</div>";
							            echo "<div class='form-group'>";
							                echo "<button class='btn btn-default'>Add</button>";
							            echo "</div>";
							    	echo "</div>";
							    echo "</form>";

					    echo "</div>";




							
							//echo "</ul>";

							/*Users liked photo*/
							$j = 0;
							$commentsLength = count($obj['data'][$i]['likes']['data']);
							echo "</li>";
						echo "</div>";
						/*<-- comments end -->*/
						echo "</div>";
						/*<-- responsive end -->*/

					echo "</div>";
					/*<-- photo-wrapper end -->*/

				}
				echo "</div>";
				/*<-- photo-feed-wrapper end -->*/

			}else if($isValid = 400){


			$url="https://api.instagram.com/v1/users/search?q={$username}&access_token=".accessToken;
			$get = connectToInstagram($url);
			$userInfo = json_decode($get, true, 512);

			$found = FALSE;
			$i = 0;
			$length = count($userInfo['data']);

			/*Private profile*/
			while($found == FALSE || $i == $length){

				if($userInfo['data'][$i]['username'] == $username){
					$found = TRUE;
					/*User info*/
					$profile_username = $userInfo['data'][$i]['username'];
					$profile_picture = $userInfo['data'][$i]['profile_picture'];
					$profile_full_name = $userInfo['data'][$i]['full_name'];

					echo "<div class='photo-feed-wrapper'>";

					echo "<div class='user-info'>";

							echo "<div class='user-picture'>";
								echo "<a href='/instafeed/profile.php?username={$profile_username}'><img src='".$profile_picture."' alt='".$profile_username."' class='img-circle' height='120' width='120'></a>";
							echo "</div>";  

							echo "<div class='user-left'>";
								if($profile_full_name){
									echo "<h4>".$profile_full_name."</h4>";
								}
								echo "<a href='/instafeed/profile.php?username={$profile_username}'>@".$profile_username."</a>";						
							echo "<div class='user-bottom'>";
								echo "<h3>This user is private</h3>";
							echo "</div>";
							echo "</div>";

					echo "</div>";
				}
				$i++;
			}
			

			}
		}

		function showPhoto($id){
			$id=$id;

			/*Checks if logged in*/
			if(verifyLogin()){
				echo "Worked logged in!";
				$accessToken = decryptIt($_COOKIE['testToken']);
				//$username = decryptIt($_COOKIE['testUsername']);
			}else{
				$accessToken = accessToken;
			}

			$url="https://api.instagram.com/v1/media/{$id}?access_token=".$accessToken;
			$get = connectToInstagram($url);
			$obj = json_decode($get, true, 512);

			$isValid = $obj['meta']['code'];
			$error_type = $obj['meta']['error_type'];
			$length = count($obj['data']);

			if($isValid !==400){
				/*Show user profile data (pics, comments etc.)*/
				$type = $obj['data']['type'];
				$img = $obj['data']['images']['standard_resolution']['url'];
				$comments_count = $obj['data']['comments']['count'];
				$filter = $obj['data']['filter'];
				$video = $obj['data']['videos']['standard_resolution']['url'];
				$created_time = $obj['data']['created_time'];
				$likes_count = $obj['data']['likes']['count'];
				//$tag_name = $obj['data'][$i]['tags'][0];
				$filter = $obj['data']['filter'];
				$profile_username = $obj['data']['user']['username'];
				$profile_picture = $obj['data']['user']['profile_picture'];
				$profile_full_name = $obj['data']['user']['full_name'];
				$text = $obj['data']['caption']['text'];
				$text = preg_replace("/#(\w+)/", "<a class='blue' a href='/instafeed/tag.php?tag=$1'>#$1</a>", $text);
				$text = preg_replace("/@([^\s]+)/", "<a class='blue' href='/instafeed/profile.php?username=$1'>@$1</a>", $text);
					
				$time = getTime($created_time);

				echo "<div class='photo-feed-wrapper'>";
					
					echo "<div class='row photo-wrapper'>";

						/*Photo box*/
						echo "<div class='media-box'>";

							/*Profile-box*/
							echo "<div class='profile-box'>";
								
								echo "<div class='title-box-big'>";
						    		echo "<div class='profile-picture'>";
							    		echo "<a href='/instafeed/profile.php?username={$profile_username}'><img src='".$profile_picture."' alt='".$profile_username."' class='img-circle' height='40' width='40'></a>";
								    echo "</div>";   	
							      	echo "<div class='profile-top'>";
									echo "<span class='time'>".$time."</span>";
									echo "<a href='/instafeed/profile.php?username={$profile_username}'><span class='name'>@".$profile_username."</span></a>";							
									echo "</div>";

									echo "<div class='profile-bottom'>";
									echo "<span class='time'>".$filter."</span>";
									if($profile_full_name){
										echo "<span class='name'>".$profile_full_name."</span>";
									}
									echo "</div>";
						    	echo "</div>"; /*<-- title-box end -->*/

								    /*Users liked photo*/
									$j = 0;
									$commentsLength = count($obj['data'][$i]['likes']['data']);
									echo "<div class='media-contents'>";

								    if($type == 'image'){	
										echo "<img class='center' src='".$img."' height='510' width='510'>";
									}else{
										echo "<video id='my-video' class='video-js' controls preload='auto' width='510' height='510' poster='MY_VIDEO_POSTER.jpg' data-setup='{}' style='margin: 0px auto;'>";
										    echo "<source src='".$video."' type='video/mp4'>";
										    echo "<source src='".$video."' type='video/webm'>"; //not .webm, change this?
										    echo "<p class='vjs-no-js'>";
										      echo "To view this video please enable JavaScript, and consider upgrading to a web browser that";
										      echo "<a href='http://videojs.com/html5-video-support/' target='_blank'>supports HTML5 video</a>";
											echo "</p>";
										echo "</video>";
									}

						    	echo "<div class='info-box'>";
									echo "<span>".$likes_count." likes</span>";
								echo "</div>"; /*<-- info-box end -->*/

						/*Comment box*/
						echo "<div class='comments-box'>";

						echo "<div class='title-box'>";

							echo "<div class='profile-picture'>";
								echo "<a href='/instafeed/profile.php?username={$profile_username}'><img src='".$profile_picture."' alt='".$profile_username."' class='img-circle' height='32' width='32'></a>";
							echo "</div>";   	
							echo "<div class='profile-top'>";
						        echo "<h4>".$text."</h4>";
							echo "</div>";

						    echo "</div>";

						    echo "<div class='comments-big'>";
						    echo "<p>".$comments_count." comments</p>";
					        echo "<ul class='commentList'>";
					            


					        /*Show comments*/
							$j = 0;
							$commentsLength = count($obj['data']['comments']['data']);
							//echo "<ul>";
							while($j<$commentsLength){
								$created_time = $obj['data']['comments']['data'][$j]['created_time'];
								$text = $obj['data']['comments']['data'][$j]['text'];
								$text = preg_replace("/#(\w+)/", "<a class='blue' a href='/instafeed/tag.php?tag=$1'>#$1</a>", $text);
								$text = preg_replace("/@([^\s]+)/", "<a class='blue' href='/instafeed/profile.php?username=$1'>@$1</a>", $text);
								$comment_username = $obj['data']['comments']['data'][$j]['from']['username'];
								$comment_picture = $obj['data']['comments']['data'][$j]['from']['profile_picture'];
								$comment_id = $obj['data']['comments']['data'][$j]['from']['id'];

								$time = getTime($created_time);
								echo "<li>";
					                echo "<div class='commenterImage'>";
										echo "<a href='/instafeed/profile.php?username={$comment_username}'><img src='".$comment_picture."' alt='".$user."' height='32' width='32'></a>";
					                echo "</div>";

					                echo "<div class='commentText'>";
					                    echo "<p class=''><a href='/instafeed/profile.php?username={$comment_username}'>@".$comment_username."</a> ".$text."</p> <span class='date sub-text'>".$time."</span>";
					                echo "</div>";
					            echo "</li>";
								$j++;
							}
					        echo "</ul>";
					    echo "</div>";

							    echo "<form class='form-inline'' role='form'>";
					    			echo "<div class='post-comment'>";
							            echo "<div class='form-group'>";
							                echo "<input class='form-control' type='text' placeholder='Add a comment...' />";
							            echo "</div>";
							            echo "<div class='form-group'>";
							                echo "<button class='btn btn-default'>Add</button>";
							            echo "</div>";
							    	echo "</div>";
							    echo "</form>";

					    echo "</div>"; /*<-- comments-box end -->*/

								echo "</div>"; /*<-- media-contents end -->*/

						    echo "</div>"; /*<-- media-content-big end -->*/


						

					echo "</div>"; /*<-- row photo-wrapper end -->*/
				echo "</div>";

				echo "</div>"; /*<-- photo-feed-wrapper end -->*/

			}else if($error_type == 'APINotFoundError'){
				echo "<div class='photo-feed-wrapper'>";
					
					echo "<div class='row photo-wrapper'>";

						/*Photo box*/
						echo "<div class='media-box'>";

							/*Profile-box*/
							echo "<div class='profile-box'>";
								
								echo "<div class='center'>";
									echo "<h2>Private profile, direct to profile by using _309165108 in the photo id</h2>";
						    	echo "</div>"; /*<-- title-box end -->*/

							echo "</div>"; /*<-- profile-box end -->*/

						echo "</div>"; /*<-- media-box end -->*/

					echo "</div>"; /*<-- row photo-wrapper end -->*/

				echo "</div>"; /*<-- photo-feed-wrapper end -->*/
			}else{
				echo "<div class='photo-feed-wrapper'>";
					
					echo "<div class='row photo-wrapper'>";

						/*Photo box*/
						echo "<div class='media-box'>";

							/*Profile-box*/
							echo "<div class='profile-box'>";
								
								echo "<div class='center'>";
									echo "<h2>Media could not be found</h2>";
						    	echo "</div>"; /*<-- title-box end -->*/

							echo "</div>"; /*<-- profile-box end -->*/

						echo "</div>"; /*<-- media-box end -->*/

					echo "</div>"; /*<-- row photo-wrapper end -->*/

				echo "</div>"; /*<-- photo-feed-wrapper end -->*/
			}

			/*echo "<pre>";
			print_r($obj);
			echo "</pre>";*/
		}

		/*User feed*/
		function showFeed(){
			echo "här: ".$_SESSION['accessToken'];

			/*Checks if logged in*/
			if(verifyLogin()){
				echo "Worked logged in!";
				$accessToken = decryptIt($_COOKIE['testToken']);
				//$username = decryptIt($_COOKIE['testUsername']);
			}else{
				$accessToken = accessToken;
			}

			/*Pagination*/
			if(isset($_GET['page'])) {
				$url="https://api.instagram.com/v1/users/self/feed?access_token=".$accessToken."&max_id=".$_GET['page'];
			}else{
				$url="https://api.instagram.com/v1/users/self/feed?access_token=".$accessToken;
			}

			$get = connectToInstagram($url);
			$obj = json_decode($get, true, 512);

			$nextUrl = $obj['pagination']['next_url'];
			$nextPage = $obj['pagination']['next_max_id'];
			$_SESSION["pagination"] = $nextUrl;
			$_SESSION["next_page"] = $nextPage;
			$isValid = $obj['meta']['code'];
			$length = count($obj['data']);

			/*echo "<pre>";
			print_r($obj);
			echo "</pre>";*/	

			

			if($isValid !== 400){

				/*User info*/
				$bio_url = "https://api.instagram.com/v1/users/{$uid}/?access_token=".$accessToken;
				$get = connectToInstagram($bio_url);
				$userInfo = json_decode($get, true, 512);

				$profile_username = $userInfo['data']['username'];
				$profile_picture = $userInfo['data']['profile_picture'];
				$profile_full_name = $userInfo['data']['full_name'];

				$bio = $userInfo['data']['bio'];
				$bio = preg_replace("/#(\w+)/", "<a class='blue' a href='/instafeed/tag.php?tag=$1'>#$1</a>", $bio);
				$bio = preg_replace("/@([^\s]+)/", "<a class='blue' href='/instafeed/profile.php?username=$1'>@$1</a>", $bio);
				$website = $userInfo['data']['website'];
				$media_count = $userInfo['data']['counts']['media'];
				$follows = $userInfo['data']['counts']['follows'];
				$followed_by = $userInfo['data']['counts']['followed_by'];


				echo "<div class='photo-feed-wrapper'>";

				echo "<div class='user-info'>";

						echo "<div class='user-picture'>";
							echo "<a href='/instafeed/profile.php?username={$profile_username}'><img src='".$profile_picture."' alt='".$profile_username."' class='img-circle' height='120' width='120'></a>";
						echo "</div>";  

						echo "<div class='user-left'>";
							if($profile_full_name){
								echo "<h4>".$profile_full_name."</h4>";
							}
							echo "<a href='/instafeed/profile.php?username={$profile_username}'>@".$profile_username."</a>";						
						echo "<div class='user-bottom'>";
							echo "<ul>";
								echo "<li>Followers: <b>".$followed_by."</b></li>";
								echo "<li>Following: <b>".$follows."</b></li>";
								echo "<li>Posts <b>".$media_count."</b></li>";
							echo "</ul>";
						echo "</div>";
						echo "</div>";

						echo "<div class='user-right'>";
							echo "<h5>".$bio."</h5>";
							echo "<a href='{$website}' target='_blank' rel='nofollow'>".$website."</a>";
						echo "</div>";

				echo "</div>";

				/*Show user profile data (pics, comments etc.)*/
				for($i=0; $i<$length; $i++){
					$type = $obj['data'][$i]['type'];
					$location = $obj['data'][$i]['location'];
					$comments_count = $obj['data'][$i]['comments']['count'];
					$filter = $obj['data'][$i]['filter'];
					$created_time = $obj['data'][$i]['created_time'];
					$link = $obj['data'][$i]['link']; //instagram link
					$likes_count = $obj['data'][$i]['likes']['count'];
					$img = $obj['data'][$i]['images']['low_resolution']['url'];
					//$users_in_photo = $obj['data'][$i]['users_in_photo'];
					$tags = "";
					$type = $obj['data'][$i]['type'];

					$text = $obj['data'][$i]['caption']['text'];
					$text = preg_replace("/#(\w+)/", "<a class='blue' a href='/instafeed/tag.php?tag=$1'>#$1</a>", $text);
					$text = preg_replace("/@([^\s]+)/", "<a class='blue' href='/instafeed/profile.php?username=$1'>@$1</a>", $text);
					
					//$profile_username = $obj['data'][$i]['user']['username'];
					//$profile_picture = $obj['data'][$i]['user']['profile_picture'];
					//$profile_full_name = $obj['data'][$i]['user']['full_name'];
					$id = $obj['data'][$i]['id'];
					$time = getTime($created_time);


					echo "<div class='row photo-wrapper'>";

						/*Photo box*/
						echo "<div class='photo-box'>";
							
							/*Profile-box*/
							echo "<div class='profile-box'>";

							echo "<div class='title-box'>";
						    		echo "<div class='profile-picture'>";
							    		echo "<a href='/instafeed/profile.php?username={$profile_username}'><img src='".$profile_picture."' alt='".$profile_username."' class='img-circle' height='40' width='40'></a>";
								    echo "</div>";   	
							      	echo "<div class='profile-top'>";
									echo "<span class='time'>".$time."</span>";
									echo "<a href='/instafeed/profile.php?username={$profile_username}'><span class='name'>@".$profile_username."</span></a>";							
									echo "</div>";

									echo "<div class='profile-bottom'>";
									echo "<span class='time'>".$filter."</span>";
									if($profile_full_name){
										echo "<span class='name'>".$profile_full_name."</span>";
									}
									echo "</div>";
						    echo "</div>";
							
						    echo "<div class='contents'>";
							    /*Users liked photo*/
								$j = 0;
								$commentsLength = count($obj['data'][$i]['likes']['data']);
								echo "<div class='media-content'>";

							    if($type == 'video'){
								    echo "<div class='video'>";
									    echo "<a class='video' href='/instafeed/photo.php?photo={$id}'><span></span><img src='".$img."' alt='".$profile_username."' id='".$id."' height='340' width='340'></a>";
									echo "</div>";	
							    }else{
									echo "<a href='/instafeed/photo.php?photo={$id}'><img src='".$img."' alt='".$profile_username."' id='".$id."' height='340' width='340'></a>";
								}

								echo "</div>";
					    	echo "</div>";

							echo "<div class='liked-box'>";
								echo "<span>".$likes_count." likes</span>";
								echo "<ul>";
								while($j<$commentsLength){
									echo "<li>";
									$likes_username = $obj['data'][$i]['likes']['data'][$j]['username'];
									//$likes_profile_picture = $obj['data'][$i]['likes']['data'][$j]['profile_picture'];
									//$likes_id = $obj['data'][$i]['likes']['data'][$j]['id'];
									//$likes_full_name = $obj['data'][$i]['likes']['data'][$j]['full_name'];
								
									echo "<a href='/instafeed/profile.php?username={$likes_username}'>@".$likes_username."</a>";
									$j++;
									echo "</li>";
								}
								echo "</ul>";
							echo "</div>";
							/*<-- end users liked photo-->*/

						echo "</div>";
						/*<-- profile-box end -->*/
						
						echo "</div>";
						/*<-- photo-box end -->*/








					/*Mobile responsive*/
					echo "<div class='hidden-xs'>";

						/*Comment box*/
						echo "<div class='comment-box'>";

						echo "<div class='profile-box'>";
						echo "<div class='title-box'>";

							echo "<div class='profile-picture'>";
								echo "<a href='/instafeed/profile.php?username={$profile_username}'><img src='".$profile_picture."' alt='".$profile_username."' class='img-circle' height='32' width='32'></a>";
							echo "</div>";   	
							echo "<div class='profile-top'>";
						        echo "<p class='taskDescription'>".$text."</p>";
							echo "</div>";

						    echo "</div>";

						    echo "<div class='comments'>";
						    echo "<p>".$comments_count." comments</p>";
					        echo "<ul class='commentList'>";
					            


					        /*Show comments*/
							$j = 0;
							$commentsLength = count($obj['data'][$i]['comments']['data']);
							//echo "<ul>";
							while($j<$commentsLength){
								$created_time = $obj['data'][$i]['comments']['data'][$j]['created_time'];
								$text = $obj['data'][$i]['comments']['data'][$j]['text'];
								$text = preg_replace("/#(\w+)/", "<a class='blue' a href='/instafeed/tag.php?tag=$1'>#$1</a>", $text);
								$text = preg_replace("/@([^\s]+)/", "<a class='blue' href='/instafeed/profile.php?username=$1'>@$1</a>", $text);
								$comment_username = $obj['data'][$i]['comments']['data'][$j]['from']['username'];
								$comment_picture = $obj['data'][$i]['comments']['data'][$j]['from']['profile_picture'];
								$comment_id = $obj['data'][$i]['comments']['data'][$j]['from']['id'];

								$time = getTime($created_time);
								echo "<li>";
					                echo "<div class='commenterImage'>";
										echo "<a href='/instafeed/profile.php?username={$comment_username}'><img src='".$comment_picture."' alt='".$user."' height='32' width='32'></a>";
					                echo "</div>";

					                echo "<div class='commentText'>";
					                    echo "<p class=''><a href='/instafeed/profile.php?username={$comment_username}'>@".$comment_username."</a> ".$text."</p> <span class='date sub-text'>".$time."</span>";
					                echo "</div>";
					            echo "</li>";
								$j++;
							}
					        echo "</ul>";
					    echo "</div>";

							    echo "<form class='form-inline'' role='form'>";
					    			echo "<div class='post-comment'>";
							            echo "<div class='form-group'>";
							                echo "<input class='form-control' type='text' placeholder='Add a comment...' />";
							            echo "</div>";
							            echo "<div class='form-group'>";
							                echo "<button class='btn btn-default'>Add</button>";
							            echo "</div>";
							    	echo "</div>";
							    echo "</form>";

					    echo "</div>";




							
							//echo "</ul>";

							/*Users liked photo*/
							$j = 0;
							$commentsLength = count($obj['data'][$i]['likes']['data']);
							//echo "<p>Liked by:</p>";
							/*while($j<$commentsLength){
								echo "<li class=comments>";
								$likes_username = $obj['data'][$i]['likes']['data'][$j]['username'];
								//$likes_profile_picture = $obj['data'][$i]['likes']['data'][$j]['profile_picture'];
								//$likes_id = $obj['data'][$i]['likes']['data'][$j]['id'];
								//$likes_full_name = $obj['data'][$i]['likes']['data'][$j]['full_name'];
							
								echo "<p>@".$likes_username."</p>";
								$j++;
							}*/
							echo "</li>";
						echo "</div>";
						/*<-- comments end -->*/

						echo "</div>";
						/*<-- mobile responsive end -->*/

					echo "</div>";
					/*<-- photo-wrapper end -->*/

				}
				echo "</div>";
				/*<-- photo-feed-wrapper end -->*/


				

				/*echo "<form action='/instafeed/profile.php?username={$profile_username}&page={$_SESSION["next_page"]}' method='post'>";
				echo "<input type='submit' name='paginate'>";
				echo "</form>";*/
				echo "<div class='next-page'>";
				echo "<a href='/instafeed/profile.php?username={$profile_username}&page={$_SESSION["next_page"]}'><button class='button'>View more</button></a>";
				echo "</div>";

				/*echo "<pre>";
				print_r($obj);
				echo "</pre>";*/

			}else if($isValid == 400){

			/*Checks if logged in*/
			if(verifyLogin()){
				echo "Worked logged in!";
				$accessToken = decryptIt($_COOKIE['testToken']);
				//$username = decryptIt($_COOKIE['testUsername']);
			}else{
				$accessToken = accessToken;
			}

			$url="https://api.instagram.com/v1/users/search?q={$username}&access_token=".$accessToken;
			$get = connectToInstagram($url);
			$userInfo = json_decode($get, true, 512);

			$found = FALSE;
			$i = 0;
			$length = count($userInfo['data']);

			/*Private profile*/
			while($found == FALSE || $i == $length){

				/*Private user was found*/
				if($userInfo['data'][$i]['username'] == $username){
					$found = TRUE;

					$profile_username = $userInfo['data'][$i]['username'];
					$profile_picture = $userInfo['data'][$i]['profile_picture'];
					$profile_full_name = $userInfo['data'][$i]['full_name'];

					echo "<div class='photo-feed-wrapper'>";

					echo "<div class='user-info'>";

							echo "<div class='user-picture'>";
								echo "<a href='/instafeed/profile.php?username={$profile_username}'><img src='".$profile_picture."' alt='".$profile_username."' class='img-circle' height='120' width='120'></a>";
							echo "</div>";  

							echo "<div class='user-left'>";
								if($profile_full_name){
									echo "<h4>".$profile_full_name."</h4>";
								}
								echo "<a href='/instafeed/profile.php?username={$profile_username}'>@".$profile_username."</a>";						
							echo "<div class='user-bottom'>";
								echo "<h3>This user is private</h3>";
							echo "</div>";
							echo "</div>";

					echo "</div>";
				}
				/*User does not exist*/
				else if($found == FALSE && $i == $length){
					$found = TRUE;
					echo "<div class='photo-feed-wrapper'>";

					echo "<div class='user-info'>";
							echo "<div class='user-bottom'>";
								echo "<h3>User does not exist.</h3>";
							echo "</div>";
					echo "</div>";

					echo "</div>";
				}
				$i++;
			}
				
			}
		}
		
/*Remove to avoid whitespaces*/
?>