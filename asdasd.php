<?php 

	function showProfile($username){
			//str_replace(" ","+",$username); //Replace spaces with a plus sign
			$uid = getUID($username);

			// instruction to get your access token here https://www.codeofaninja.com/2015/05/get-instagram-access-token.html
			$access_token="309165108.d64dad5.0bd360f0dab34b1f9c0e00357ef4d90e";
			$photo_count=300;

			$json_link="https://api.instagram.com/v1/users/{$uid}/media/recent/?";
			$json_link.="access_token={$access_token}&count={$photo_count}";

			$get = @file_get_contents($json_link); //@ toggles error message to not be shown
			$obj = json_decode($get, true, 512);
			$isValid = $obj['meta']['code'];
			$length = count($obj['data']);

			if($isValid){

				/*User info*/
				$userInfo = getUserInfo($username);
				$profile_username = $userInfo['data']['username'];
				$profile_picture = $userInfo['data']['profile_picture'];
				$profile_full_name = $userInfo['data']['full_name'];

				$bio = $userInfo['data']['bio'];
				$bio = preg_replace("/#(\w+)/", "<a class='blue' a href='/instafeed/tag.php?tag=$1&submit=Search'>#$1</a>", $bio);
				$bio = preg_replace("/@([^\s]+)/", "<a class='blue' href='/instafeed/profile.php?username=$1&photo_count=12&submit=Search'>@$1</a>", $bio);
				$website = $userInfo['data']['website'];
				$media_count = $userInfo['data']['counts']['media'];
				$follows = $userInfo['data']['counts']['follows'];
				$followed_by = $userInfo['data']['counts']['followed_by'];


				echo "<div class='photo-feed-wrapper'>";

				echo "<div class='user-info'>";

						echo "<div class='user-picture'>";
							echo "<a href='/instafeed/profile.php?username={$profile_username}&photo_count=12&submit=Search'><img src='".$profile_picture."' alt='".$profile_username."' class='img-circle' height='120' width='120'></a>";
						echo "</div>";  

						echo "<div class='user-left'>";
							if($profile_full_name){
								echo "<h4>".$profile_full_name."</h4>";
							}
							echo "<a href='/instafeed/profile.php?username={$profile_username}&photo_count=12&submit=Search'>@".$profile_username."</a>";						
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
					$text = preg_replace("/#(\w+)/", "<a class='blue' a href='/instafeed/tag.php?tag=$1&submit=Search'>#$1</a>", $text);
					$text = preg_replace("/@([^\s]+)/", "<a class='blue' href='/instafeed/profile.php?username=$1&photo_count=12&submit=Search'>@$1</a>", $text);
					
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
							    		echo "<a href='/instafeed/profile.php?username={$profile_username}&photo_count=12&submit=Search'><img src='".$profile_picture."' alt='".$profile_username."' class='img-circle' height='40' width='40'></a>";
								    echo "</div>";   	
							      	echo "<div class='profile-top'>";
									echo "<span class='time'>".$time."</span>";
									echo "<a href='/instafeed/profile.php?username={$profile_username}&photo_count=12&submit=Search'><span class='name'>@".$profile_username."</span></a>";							
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
									    echo "<a class='video' href='/instafeed/photo.php?photo={$id}&submit=Search'><span></span><img src='".$img."' alt='".$profile_username."' id='".$id."' height='320' width='320'></a>";
									echo "</div>";	
							    }else{
									echo "<a href='/instafeed/photo.php?photo={$id}&submit=Search'><img src='".$img."' alt='".$profile_username."' id='".$id."' height='320' width='320'></a>";
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
								
									echo "<a href='/instafeed/profile.php?username={$likes_username}&photo_count=12&submit=Search'>@".$likes_username."</a>";
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










						/*Comment box*/
						echo "<div class='comment-box'>";

						echo "<div class='profile-box'>";
						echo "<div class='title-box'>";

							echo "<div class='profile-picture'>";
								echo "<a href='/instafeed/profile.php?username={$profile_username}&photo_count=12&submit=Search'><img src='".$profile_picture."' alt='".$profile_username."' class='img-circle' height='32' width='32'></a>";
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
								$text = preg_replace("/#(\w+)/", "<a class='blue' a href='/instafeed/tag.php?tag=$1&submit=Search'>#$1</a>", $text);
								$text = preg_replace("/@([^\s]+)/", "<a class='blue' href='/instafeed/profile.php?username=$1&photo_count=12&submit=Search'>@$1</a>", $text);
								$comment_username = $obj['data'][$i]['comments']['data'][$j]['from']['username'];
								$comment_picture = $obj['data'][$i]['comments']['data'][$j]['from']['profile_picture'];
								$comment_id = $obj['data'][$i]['comments']['data'][$j]['from']['id'];

								$time = getTime($created_time);
								echo "<li>";
					                echo "<div class='commenterImage'>";
										echo "<a href='/instafeed/profile.php?username={$comment_username}&photo_count=12&submit=Search'><img src='".$comment_picture."' alt='".$user."' height='32' width='32'></a>";
					                echo "</div>";

					                echo "<div class='commentText'>";
					                    echo "<p class=''><a href='/instafeed/profile.php?username={$comment_username}&photo_count=12&submit=Search'>@".$comment_username."</a> ".$text."</p> <span class='date sub-text'>".$time."</span>";
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
					/*<-- photo-wrapper end -->*/

				}
				echo "</div>";
				/*<-- photo-feed-wrapper end -->*/

				/*echo "<pre>";
				print_r($obj);
				echo "</pre>";*/
			}else if($isValid = 400){

			$access_token="309165108.d64dad5.0bd360f0dab34b1f9c0e00357ef4d90e";
			 
			$json_link="https://api.instagram.com/v1/users/search?q={$username}&";
			$json_link.="access_token={$access_token}";

			$get = @file_get_contents($json_link); //@ toggles error message to not be shown
			$userInfo = json_decode($get, true, 512);

			$key = array_search($username, $userInfo); //Search array to find matching username

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
								echo "<a href='/instafeed/profile.php?username={$profile_username}&photo_count=12&submit=Search'><img src='".$profile_picture."' alt='".$profile_username."' class='img-circle' height='120' width='120'></a>";
							echo "</div>";  

							echo "<div class='user-left'>";
								if($profile_full_name){
									echo "<h4>".$profile_full_name."</h4>";
								}
								echo "<a href='/instafeed/profile.php?username={$profile_username}&photo_count=12&submit=Search'>@".$profile_username."</a>";						
							echo "<div class='user-bottom'>";
								echo "<h3>This user is private</h3>";
							echo "</div>";
							echo "</div>";

					echo "</div>";
				}
				$i++;
			}
			

			}else if($isValid === FALSE){
				echo "Could not find user. Please try searching something else.";
			}
	}
?>