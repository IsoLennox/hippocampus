<?php require_once("inc/session.php"); ?>
<?php require_once("inc/functions.php"); ?>
<?php require_once("inc/db_connection.php"); ?>
<?php confirm_logged_in(); ?>
<?php


 
 


if(isset($_GET["avatar"])){

//DELETE avatar
    
  $user_id=$_GET["avatar"];
    
    //REMOVE FROM DIR
    $get_avatar  = "SELECT * FROM users WHERE id = {$user_id}";  
    $avatar_result = mysqli_query($connection, $get_avatar);
    if($avatar_result){
      
      
      $array=mysqli_fetch_assoc($avatar_result);
      $book_id=$array['book_id'];
        
   
               
    //REMOVE FROM DB
    $reset  = "UPDATE users SET ";
    $reset .= "avatar = '' ";
    $reset .= "WHERE id = {$user_id} ";
    $result = mysqli_query($connection, $reset);

  if ($result && mysqli_affected_rows($connection) == 1) {
    // Success 
    $_SESSION["message"] = "avatar deleted.";
    redirect_to("edit_profile.php");
     
  } else{
    // Failure
    $_SESSION["message"] = "avatar deletion failed.";
    redirect_to("edit_profile.php");
  }
     
  } else{
    
    $_SESSION["message"] = "avatar Not Found"; 
        redirect_to("edit_profile.php");
  }
   

}//END DELETE avatar






if(isset($_POST["group"])){

    //ASK FOR PASSWORD
 $password=$_POST["password"]; 
  $checked=check_password($_SESSION['user_id'], $password);
    
    if($checked){
    
  $group_id=$_POST["group_id"]; 
    //REMOVE ALL POSTS 
    $remove_posts  = "DELETE FROM posts WHERE group_id = {$group_id} ";
    $post_result = mysqli_query($connection, $remove_posts);

  if ($post_result) {
      
      //REMOVE ALL USER_GROUPS
    $remove_users  = "DELETE FROM user_group WHERE group_id = {$group_id} ";
    $user_result = mysqli_query($connection, $remove_users);

      if ($user_result && mysqli_affected_rows($connection) == 1) {

          //DELETE group
            $remove_groups  = "DELETE FROM groups WHERE id = {$group_id} ";
            $group_result = mysqli_query($connection, $remove_groups);

              if ($group_result && mysqli_affected_rows($connection) == 1) {
                    $_SESSION["message"] = "Group Deleted"; 
                    redirect_to("index.php");

              }else{
            $_SESSION["message"] = "Posts and Users Deleted. Could not delete Group"; 
            redirect_to("index.php");
            }

      }else{
    $_SESSION["message"] = "Posts Deleted. Could not delete users"; 
    redirect_to("index.php");
    }
  }else{
    $_SESSION["message"] = "Could not delete posts"; 
    redirect_to("index.php");
  }
    
    }else{
        $_SESSION["message"] = "Password Incorrect. Did not delete group."; 
        redirect_to("index.php");
    
    }//end check password
 
   

}//END DELETE group

 

?>