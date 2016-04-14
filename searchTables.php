<?php

      function searchUser($name){
              
                  $name = str_replace(' ', '+', $_GET['search_result']);
                  echo $name;
                 
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

?>