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
        redirect_to("view.php?book_id=$book_id");
  }
   

}//END DELETE avatar

 

?>