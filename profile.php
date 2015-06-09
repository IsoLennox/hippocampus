<?php include("inc/header.php"); 

//determine whose profile we are looking at 
if(isset($_GET['user'])){
    $user_id=$_GET['user'];
    
    //GET USERNAME
    $find_user = find_user_by_id($user_id);
    $username= $find_user['username'];
    
    
}elseif(isset($_GET['user_id'])){
    $user_id=$_GET['user_id'];
    
    //GET USERNAME
    $find_user = find_user_by_id($user_id);
    $username= $find_user['username'];
    
    
}else{
    $user_id=$_SESSION['user_id'];
    $username = $_SESSION['username'];
   
}

//ADD OR REMOVE CONTACTS (SEND REQUEST)

if(isset($_GET['add'])){
    $user_id=$_GET['add'];
    $add_contact .= "INSERT INTO contacts (user1, user2, sent_to) VALUES ( {$user_id}, {$_SESSION['user_id']}, {$user_id} )";
    $contact_added = mysqli_query($connection, $add_contact);
    if ($contact_added) {
        $_SESSION["message"] = "Contact Request Sent!"; 
        redirect_to("profile.php?user=".$user_id);
    }else{
        $_SESSION["message"] = "Could Not Request Contact"; 
        redirect_to("profile.php?user=".$user_id);
    }
    
    
}elseif(isset($_GET['remove'])){
    $user_id=$_GET['remove'];
    $remove_contact  = "DELETE FROM contacts WHERE (user1={$_SESSION['user_id']} AND user2={$user_id}) OR (user2={$_SESSION['user_id']} AND user1={$user_id}) LIMIT 1";  
    $contact_removed=mysqli_query($connection, $remove_contact);
    if($contact_removed){
    
        $_SESSION["message"] = "Contact Deleted"; 
        redirect_to("profile.php?user=".$user_id);
    
    }else{
        $_SESSION["message"] = "Oops, Look like you're stuck with this person!"; 
        redirect_to("profile.php?user=".$user_id);
    }
     
}elseif(isset($_GET['accept'])){
     
    $accepted_contact  = "UPDATE contacts SET accepted=1 WHERE sent_to={$_SESSION['user_id']} AND (user1={$user_id} OR user2={$user_id}) LIMIT 1";  
    $contact_accepted=mysqli_query($connection, $accepted_contact);
    if($contact_accepted){
        //INSERT INTO HISTORY??
        $_SESSION["message"] = "Contact Accepted"; 
        redirect_to("profile.php?user=".$user_id);
    
    }else{
        $_SESSION["message"] = "Oops, Look like you cant befriend this person!"; 
        redirect_to("profile.php?user=".$user_id);
    }
     
}//END ADD OR REMOVE CONTACTS





//GET PROFILE IMAGE AND CONTENT
    $query  = "SELECT * FROM users WHERE id={$user_id} LIMIT 1";  
    $result = mysqli_query($connection, $query);
     $num_rows=mysqli_num_rows($result);
//    if($result){
    if($num_rows ==1){
        $profile_array=mysqli_fetch_assoc($result);
        
        $content=$profile_array['profile_content'];
        $avatar="http://lorempixel.com/150/150/cats";

?>
 
    <h2> <?php echo $username; ?>'s Profile </h2>
    
    <div id="profile">
        <section id="avatar" class="left"> <img src="<?php echo $avatar; ?>" alt="profile image">
         </section>
        <section id="profile-content"> <?php echo $content; ?> </section>
    </div>

<?php

if($user_id==$_SESSION['user_id']){
    echo "<br/><span class=\"right\"><a href=\"settings.php\"><i class=\"fa fa-cog\"></i> Settings</a></span><br/>";
}else{
    
    $contact_query  = "SELECT * FROM contacts WHERE (user1={$_SESSION['user_id']} AND user2={$user_id}) OR (user2={$_SESSION['user_id']} AND user1={$user_id}) LIMIT 1";  
    $contact_result = mysqli_query($connection, $contact_query); 
    $num_contacts=mysqli_num_rows($contact_result); 
    if($num_contacts==1){
        $array=mysqli_fetch_assoc($contact_result);
        if($array['accepted']==1){
        echo "<br/><span class=\"right\"><a href=\"profile.php?remove=".$user_id."\"><i class=\"fa fa-user-times\"></i>
 Remove Contact</a></span><br/>";
        }else{ 
            echo "<br/><span class=\"right\"><i class=\"fa fa-spinner fa-spin\"></i> Friend Request Pending<br/>";
            //ALLOW ONLY USER TO APPROVE OR DENY IF THIS WAS SENT TO THEM, NOT IF THEY SENT IT
            if($user_id!==$_SESSION['user_id'] && $array['sent_to']==$_SESSION['user_id']){
                echo "<a href=\"profile.php?user=".$user_id."&accept\">Accept</a> <a href=\"profile.php?user=".$contact_details['id']."&remove\">Deny</a>";
            }
            echo "</span><br/>"; 
        }
    }else{
        echo "<br/><span class=\"right\"><a href=\"profile.php?add=".$user_id."\"><i class=\"fa fa-user-plus\"></i> Add Contact</a></span><br/>";
    }

}
?>
 
  <?php
    }else{
        echo "This user does not exist";
    } ?>
 
        
<?php include("inc/footer.php"); ?>