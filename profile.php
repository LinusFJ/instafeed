<!DOCTYPE html>
<html lang="en">
<head>
 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
 
    <title>InstaFeed.me - Search Instagram profiles</title>
 
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <!-- Custom styles for this template -->
    <link href="css/stylesheet.css?version=1" rel="stylesheet">
 
</head>
<body>
<?php
include 'functions.php';
?> 

	<div class="wrapper">
      <!-- Static navbar -->
      <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#search" aria-expanded="false" aria-controls="search">
            <span class="sr-only">Toggle navigation</span>
            <span class="glyphicon glyphicon-search"></span>
          </button>
          <a class="navbar-brand" href="index.php">InstaFeed.me</a>
        </div>

      	<div class="photo-feed-wrapper">
        <div id="navbar" class="collapse navbar-collapse">
	        <div class='hidden-xs'>
	        	<ul class="nav navbar-nav">
		            <div class="search-box"> 
						<form method='get' action='search.php'>
		              		<div class="input-group stylish-input-group">
		                    	<input type="text" class="form-control" name='search_result' placeholder="User or Tag" value='pewdiepie'>
		                    	<span class="input-group-addon">
		                        	<button type="submit" name='submit'>
		                            	<span class="glyphicon glyphicon-search"></span>
		                       		</button>  
		                    	</span>
		                	</div>
						</form>
		            </div>
		        </ul>
		    </div>

	        <ul class="nav navbar-nav navbar-right">
	          <?php isLoggedIn(); ?>
	        </ul>
        </div><!--/.nav-collapse -->



        <div class='visible-xs'>
          <div id="search" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li>
	            <div class="search-box" class="collapse navbar-collapse">
	            	<form method='get' action='search.php'>
		              	<div class="input-group stylish-input-group">
		                   	<input type="text" class="form-control" name='search_result' placeholder="User or Tag" value='pewdiepie'>
		                   	<span class="input-group-addon">
		                       	<button type="submit" name='submit'>
		                           	<span class="glyphicon glyphicon-search"></span>
		                   		</button>  
		                   	</span>
		                </div>
					</form>
				</div>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
        </div>

        
        </div><!--/.photo-feed-wrapper-collapse -->
      </div><!--/.container-collapse -->
    </nav>
			

	<div class="container">		
      <!-- Main component for a primary marketing message or call to action -->

		<?php
		  if(isset($_GET['username'])){
      
        $uid = getUID($_GET['username']);

        /*Pagination*/
        if(isset($_GET['page'])) {
          $url="https://api.instagram.com/v1/users/{$uid}/media/recent/?access_token=".accessToken."&max_id=".$_GET['page'];
        }else{
          $url="https://api.instagram.com/v1/users/{$uid}/media/recent/?access_token=".accessToken;
        }

        $get = connectToInstagram($url);
        $obj = json_decode($get, true, 512);

        if(isset($obj['pagination']['next_max_id'])){
          session_start();
          $_SESSION["pagination"] = $obj['pagination']['next_url'];
          $_SESSION["next_page"] = $obj['pagination']['next_max_id'];
        }

        $isValid = $obj['meta']['code'];

        /*Display profile if it exists*/
        if($isValid !== 400){

          /*User info*/
          $bio_url = "https://api.instagram.com/v1/users/{$uid}/?access_token=".accessToken;
          $get = connectToInstagram($bio_url);
          $userInfo = json_decode($get, true, 512);
          
          /*echo "<pre>";
          print_r($userInfo);
          echo "</pre>";*/

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
          ?>
          <div class='photo-feed-wrapper'>
            <div class='user-info'>

              <div class='user-picture'>
                <?php
                echo "<a href='/instafeed/profile.php?username={$profile_username}'><img src='".$profile_picture."' alt='".$profile_username."' class='img-circle' height='120' width='120'></a>";
                ?>
              </div> 

              <div class='user-left'>
              <?php
                if($profile_full_name){
                  echo "<h4>".$profile_full_name."</h4>";
                }
                echo "<a href='/instafeed/profile.php?username={$profile_username}'>@".$profile_username."</a>";            
                ?>
                <div class='user-bottom'>
                  <ul>
                    <li>Followers: <b><?php echo $followed_by ?></b></li>
                    <li>Following: <b><?php echo $follows ?></b></li>
                    <li>Posts <b><?php echo $media_count ?></b></li>
                  </ul>
                </div>
              </div>

              <div class='user-right'>
                <h5><?php echo $bio ?></h5>
                <a href=<?php echo $website ?> target='_blank' rel='nofollow'><?php echo $website ?></a>
              </div>

            </div>

        <?php
        /*Show user profile data (pics, comments etc.)*/
        $length = count($obj['data']);

        for($i=0; $i<$length; $i++){
          $type = $obj['data'][$i]['type'];
          $location = $obj['data'][$i]['location'];
          $comments_count = $obj['data'][$i]['comments']['count'];
          $filter = $obj['data'][$i]['filter'];
          $created_time = $obj['data'][$i]['created_time'];
          $link = $obj['data'][$i]['link']; //instagram link
          $likes_count = $obj['data'][$i]['likes']['count'];
          $img = $obj['data'][$i]['images']['low_resolution']['url'];
          $tags = "";
          $type = $obj['data'][$i]['type'];

          $text = $obj['data'][$i]['caption']['text'];
          $text = preg_replace("/#(\w+)/", "<a class='blue' a href='/instafeed/tag.php?tag=$1'>#$1</a>", $text);
          $text = preg_replace("/@([^\s]+)/", "<a class='blue' href='/instafeed/profile.php?username=$1'>@$1</a>", $text);
  
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

              //echo "<a href='/instafeed/like.php?media_id={$id}'>Like</a>";

                echo "<span>".$likes_count." likes</span>";
                echo "<ul>";
                while($j<$commentsLength){
                  echo "<li>";
                  $likes_username = $obj['data'][$i]['likes']['data'][$j]['username'];
                
                  echo "<a href='/instafeed/profile.php?username={$likes_username}'>@".$likes_username."</a>";
                  $j++;
                  echo "</li>";
                }
                echo "</ul>";
              echo "</div>"; /*<-- end users liked photo-->*/
              echo "</div>"; /*<-- profile-box end -->*/
            echo "</div>"; /*<-- photo-box end -->*/
            






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
                    echo "<a href='/instafeed/profile.php?username={$comment_username}'><img src='".$comment_picture."' alt='".$comment_username."' height='32' width='32'></a>";
                          echo "</div>";

                          echo "<div class='commentText'>";
                              echo "<p class=''><a href='/instafeed/profile.php?username={$comment_username}'>@".$comment_username."</a> ".$text."</p> <span class='date sub-text'>".$time."</span>";
                          echo "</div>";
                      echo "</li>";
                $j++;
              }
                  echo "</ul>";
              echo "</div>";

                  /*echo "<form class='form-inline'' role='form'>";
                    echo "<div class='post-comment'>";
                          echo "<div class='form-group'>";
                              echo "<input class='form-control' type='text' placeholder='Add a comment...' />";
                          echo "</div>";
                          echo "<div class='form-group'>";
                              echo "<button class='btn btn-default'>Add</button>";
                          echo "</div>";
                    echo "</div>";
                  echo "</form>";*/

              echo "</div>";

              $j = 0;
              $commentsLength = count($obj['data'][$i]['likes']['data']);
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

        if(isset($_SESSION["pagination"])){
          echo "<div class='next-page'>";
          echo "<a href='/instafeed/profile.php?username={$profile_username}&page={$_SESSION["next_page"]}'><button class='button'>View more</button></a>";
          echo "</div>";
        }

        /*echo "<pre>";
        print_r($obj);
        echo "</pre>";*/

      }else if($isValid == 400){
      $username = $_GET['username'];

      $url="https://api.instagram.com/v1/users/search?q={$username}&access_token=".accessToken;
      $get = connectToInstagram($url);
      $userInfo = json_decode($get, true, 512);

      $found = FALSE;
      $length = count($userInfo['data']);

      /*Private profile*/
      while($found == FALSE){

        /*Private user was found*/
        if($userInfo['data'][0]['username'] == $username){
          $found = TRUE;

          $profile_username = $userInfo['data'][0]['username'];
          $profile_picture = $userInfo['data'][0]['profile_picture'];
          $profile_full_name = $userInfo['data'][0]['full_name'];

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
      }
        
      }
			}
		?>

    </div> <!-- /container -->
 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
 
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
 
</body>
</html>