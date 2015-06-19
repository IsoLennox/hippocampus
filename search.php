<?php $current_page="search";
include("inc/header.php"); ?> 
    <a href="#"><i class="fa fa-user-plus"></i>Invite New Members</a>
    <br>
    <br>
    <br>
     <form class="search" id="search" action="search.php" method="post">
        <input name="query" value="" placeholder="Search Users and Memories..." autocomplete="off" name="author" id="author" value="<?php echo $name; ?>" type="text"  />
        <input type="submit" name="submit" value="&#xf002;" />
    </form>  
<?php 

if(isset($_POST['submit'])){
           
    

//    HEADER SEARCH  
    
        if(preg_match("/^[_a-zA-Z0-9- ]+$/", $_POST['query'])){
         
           // get string entered
                $string=$_POST['query'];
                
//            //if space separated, explode string
//            $string_array= explode(" ",$string);
            
                 //if comma separated, explode string
                $string_array= explode(",",$string);
         
            //prepare variable for no results message
            $no_results=4;
            
            
        foreach($string_array as $word){ 
            $query_string=$word; 
       
 
 
            
 //            =======================================================
            
                        //USERNAME SEARCH
            
//            =======================================================
            
                  
     
            $username_search="SELECT * FROM users WHERE username LIKE '%" . $query_string ."%' ";
            //-run  the query against the mysql query function
            $username_result=mysqli_query($connection, $username_search);
            if($username_result){
                $username_result_array=mysqli_fetch_assoc($username_result);
                if(!empty($username_result_array)){
            
            
                echo "<h2 class=\"clear\">Usernames that contain \"". $query_string ."\":</h2>";
                   foreach($username_result as $user_match){
                        $username  =$user_match['username']; 
                        $user_id  =$user_match['id'];
  
                 //STYLE OUTPUT      
                       echo "<div class=\"results\"><a href=\"profile.php?user=$user_id\"> <img src=\"".$user_match['avatar']."\" /> ".$username."</a>";
                       
                       //SEE IF IS CONTACT
                           $contact_query  = "SELECT * FROM contacts WHERE (user1={$_SESSION['user_id']} AND user2={$user_id}) OR (user2={$_SESSION['user_id']} AND user1={$user_id}) LIMIT 1";  
                            $contact_result = mysqli_query($connection, $contact_query); 
                            $num_contacts=mysqli_num_rows($contact_result); 
                            if($num_contacts==1){
                                $array=mysqli_fetch_assoc($contact_result);
                                if($array['accepted']==1){
                                echo "<a href=\"profile.php?remove=".$user_id."\"><i class=\"fa fa-user-times\"></i>
                         Remove Contact</a></div>";
                                }else{ 
                                    echo "<i class=\"fa fa-spinner fa-spin\"></i> Friend Request Pending";
                                    //ALLOW ONLY USER TO APPROVE OR DENY IF THIS WAS SENT TO THEM, NOT IF THEY SENT IT
                                    if($user_id!==$_SESSION['user_id'] && $array['sent_to']==$_SESSION['user_id']){
                                        echo "<a href=\"profile.php?user=".$user_id."&accept\">Accept</a> <a href=\"profile.php?user=".$contact_details['id']."&remove\">Deny</a>";
                                    }
                                    echo "</div> "; 
                                }
                            }else{
                                echo "<a href=\"profile.php?add=".$user_id."\"><i class=\"fa fa-user-plus\"></i> Add Contact</a></div>";
                            }//END SEE IF IS CONTACT
                       
                       
                    }//end foreach username found with name match
                }else{
//                echo "<br/>No usernames contain '". $query_string."'<br/>";
                }
            }else{
                echo "No usernames match this query";
               // mark no results variable
                $no_results-=1;
            }// END SEARCH USERNAME!
            
 
            
            
            
            
            
            
            
            
                        
 //            =======================================================
            
                        //POST SEARCH
            
//            =======================================================
            
                  
     
            $post_search="SELECT * FROM posts WHERE content LIKE '%" . $query_string ."%' AND user_id={$_SESSION['user_id']} ";
            //-run  the query against the mysql query function
            $post_result=mysqli_query($connection, $post_search);
            if($post_result){
                $post_result_array=mysqli_fetch_assoc($post_result);
                if(!empty($post_result_array)){
            
            
                echo "<h2 class=\"clear\">Posts that contain \"". $query_string ."\":</h2>";
                   foreach($post_result as $post_match){
                        $post  =$post_match['content']; 
                        $post_id  =$post_match['id'];
                       
                       if(!empty($post_match['image'])){
                        $image="<img src=\"".$post_match['image']."\" />";
                       }else{
                        $image="";
                       }
  
                 //STYLE OUTPUT      
                       echo "<div class=\"post_sontainer\"><a href=\"post.php?id=$post_id\">".$image." ".$post."</a>";
                        
                    }//end foreach post found with name match
                }else{
//                echo "<br/>No posts contain '". $query_string."'<br/>";
                }
            }else{
                echo "No posts match this query";
               // mark no results variable
                $no_results-=1;
            }// END SEARCH post!
            
            
            
            
            
            
            
            
            }//end foreach string
        }//end PREG MATCH
   
    
     
    
}//END SUBMIT
 
include("inc/footer.php"); ?> 