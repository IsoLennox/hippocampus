<?php 
$current_page="groups";
include("inc/header.php"); 


if(isset($_POST['add_group'])){

    //CREATE NEW GROUP
    include('new_group.php');
    
}elseif(isset($_GET['join'])){
    
    $group_id=$_GET['join'];
    //INSERT USER INTO USER_GROUP
    $new_join .= "INSERT INTO user_group ( user_id, group_id ) VALUES ( {$_SESSION['user_id']}, {$group_id} )";
    $user_joind = mysqli_query($connection, $new_join);

    if ($user_joind) {
      // Success 
        //REMOVE FROM INVITE
            $remove_invite = "DELETE FROM invites WHERE user_id={$_SESSION['user_id']} AND group_id={$group_id} LIMIT 1";
            $invite_removed = mysqli_query($connection, $remove_invite);

            if ($invite_removed) {
                //SUccess
                //SEND ADMIN NOTIFICATION
                    $datetime=addslashes(date('m/d/Y H:i:s'));
                    $group=find_group_by_id($group_id);
                    $content= $_SESSION['username']." has joined your group, ".$group['name']."!"; 
                    $send_to=$group['created_by'];
                    $new_join_alert = "INSERT INTO alerts ( user_id, group_id, content, datetime ) VALUES ( {$send_to}, {$group_id}, '{$content}', '{$datetime}' )";
                    $alert_created= mysqli_query($connection, $new_join_alert);

                    if ($alert_created) {

                            $_SESSION["message"] = "Joined This group!";
                            redirect_to("index.php?group=".$group_id);
                    }else{
                        $_SESSION["message"] = "Joined This group! Could not notify the admin".$new_join_alert;
                        redirect_to("index.php?group=".$group_id);
                    }//end send group admin alert
                
            }else{
                $_SESSION["message"] = "Joined This group! Could Not remove Invite";
                redirect_to("index.php?group=".$group_id);
            }//END REMOVE FROM INVITE
        
    }else{
        $_SESSION["message"] = "Could not join this group!";
        redirect_to("index.php?group=".$group_id);
    }
    
    
    
}elseif(isset($_POST['add_post'])){
    //CREATE NEW POST IN GROUP
    
    $content=addslashes($_POST['content']);
    $group_id=$_POST['group_id'];
    $datetime=date('m/d/Y h:i:s');
     
    $new_post .= "INSERT INTO posts (content, user_id, group_id, datetime) VALUES ( '{$content}', {$_SESSION['user_id']}, {$group_id}, '{$datetime}' )";
    $post_added = mysqli_query($connection, $new_post);

    if ($post_added) {
      // Success 
        $_SESSION["message"] = "Post Saved!";
        redirect_to("index.php?group=".$group_id);
        
    } else {
      // Failure
        $_SESSION["message"] = "Could Not Create post";
        redirect_to("index.php?group=".$group_id);
        
    }//END CREATE POST IN GROUP
}elseif(isset($_GET['invite'])){
    
    //    //SEND INVITATION TO USERS CHECK BOXES
    $user_array=$_POST['users'];
    $group_id=$_POST['group']; 
    
    
    foreach($user_array as $user_id){ 
        $new_invite = "INSERT INTO invites ( user_id, group_id, invited_by) VALUES ( {$user_id}, {$group_id}, {$_SESSION['user_id']})";
        $user_invited = mysqli_query($connection, $new_invite); 
    }//end foreach user in array, invite to group
    
    echo "Users Invited!"; 

}

?>
 
<!-- ****  -->
<!-- FEED -->
<!-- ****  --> 
  
<?php

//GET GROUP ID CHOSEN, ELSE SHOW FEED FROM ALL GROUPS USER IS IN
if(isset($_GET['group'])){ 
    
        //GET GROUP NAME
    $this_group .= "SELECT * FROM groups WHERE id={$_GET['group']}";
    $group_details= mysqli_query($connection, $this_group);
    if ($group_details) {
        $group_details_array=mysqli_fetch_assoc($group_details);       
        $group_name=$group_details_array['name'];
        $created_by=$group_details_array['created_by'];

        $avatar=$group_details_array['avatar'];
        if(empty($avatar)){
            $avatar="http://lorempixel.com/100/100/abstract";
        }
         
    }else{
        $group_name="Undefined";
    }
    
    
    
    
        //SPECIAL ACTIONS/PAGES

        if(isset($_GET['edit'])){

            //EDIT/DELETE GROUP
            include('edit_group.php');

        }elseif(isset($_GET['leave'])){
        //DELETE USER FROM GROUP   
            $group_id=$_GET['group'];
        $remove_user  = "DELETE FROM user_group WHERE group_id = {$group_id} AND user_id={$_SESSION['user_id']} LIMIT 1";
        $user_result = mysqli_query($connection, $remove_user);

          if ($user_result && mysqli_affected_rows($connection) == 1) {

              //SEND NOTIFICATION TO GROUP OWNER  && MEMBERS WITH NOTIFICATIONS ON
                //SEND ADMIN NOTIFICATION
                    $datetime=addslashes(date('m/d/Y H:i:s'));
                    $group=find_group_by_id($group_id);
                    $content= $_SESSION['username']." has left your group, ".$group['name']."!"; 
                    $send_to=$group['created_by'];
                    $new_join_alert = "INSERT INTO alerts ( user_id, group_id, content, datetime ) VALUES ( {$send_to}, {$group_id}, '{$content}', '{$datetime}' )";
                    $alert_created= mysqli_query($connection, $new_join_alert);

                    if ($alert_created) {

                           $_SESSION["message"] = "You Left ".$group_name.". You must be invited to join again."; 
                            redirect_to("index.php");
                    }else{
                        $_SESSION["message"] = "You Left This group! Could not notify the admin";
                        redirect_to("index.php");
                    }//end send group admin alert
              
                
          }//end remove user from group

        }elseif(isset($_GET['decline'])){
        //DELETE USER FROM GROUP   
            $group_id=$_GET['group'];
            $remove_invite  = "DELETE FROM invites WHERE group_id = {$group_id} AND user_id={$_SESSION['user_id']} LIMIT 1";
            $invite_result = mysqli_query($connection, $remove_invite);

          if ($invite_result && mysqli_affected_rows($connection) == 1) {

              //SEND NOTIFICATION TO GROUP OWNER  && MEMBERS WITH NOTIFICATIONS ON
                //SEND ADMIN NOTIFICATION
                    $datetime=addslashes(date('m/d/Y H:i:s'));
                    $group=find_group_by_id($group_id);
                    $content= $_SESSION['username']." has declined the invitation to your group, ".$group['name'].". Don't worry, You can re-invite them."; 
                    $content=addslashes($content);
                    $send_to=$group['created_by'];
                    $new_join_alert = "INSERT INTO alerts ( user_id, group_id, content, datetime ) VALUES ( {$send_to}, {$group_id}, '{$content}', '{$datetime}' )";
                    $alert_created= mysqli_query($connection, $new_join_alert);

                    if ($alert_created) {

                           $_SESSION["message"] = "You declined ".$group_name.". You must be re-invited to join."; 
                            redirect_to("activity.php");
                    }else{
                        $_SESSION["message"] = "You Left This group! Could not notify the admin";
                        redirect_to("index.php");
                    }//end send group admin alert
              
                
          }else{
                $_SESSION["message"] = "Could not decline at this moment, please try again later.";
                 redirect_to("activity.php");
          }//end remove user from group

        }elseif(isset($_GET['members'])){

            //GET ALL MEMBERS AND INVITE OPTIONS
            include('group_members.php');

}else{ 
//get all posts from this group
    include('group_posts.php');

}//end check if this is viewing or deleting group

    
 
    
}elseif(isset($_GET['loved'])){

    //SELECT * FROM LOVED WHERE USER ID == YOU
    echo "Showing posts you love";
    
}else{ ?>
<!--       GET ALL GROUPS USER IS IN  -->
       
    <h3>Groups<span class="right" id="show-hidden-form"><i class="fa fa-plus-circle"></i> New Group</span></h3>
    
    <form class="hidden-form" style="display: none;" method="POST" enctype="multipart/form-data">  
<!--        <label for="name">Group Name</label><br/>-->
        <input type="text" name="name" placeholder="Group Name"> 
        <input type="submit" value="Create Group" name="add_group">
    </form>
    
       <?php
    
    echo "<a href=\"index.php?loved\"><i class=\"fa fa-heart\"></i> Posts You've Loved</a><br/><br/>";
//GET ALL GROUPS USER IS IN
    $get_my_groups .= "SELECT * FROM user_group WHERE user_id={$_SESSION['user_id']}";
    $groups_found= mysqli_query($connection, $get_my_groups);
    if ($groups_found) {
        echo "<ul id=\"group_nav\">"; 
        foreach($groups_found as $group_found){ 
             //GET GROUP DETAILS
                $show_groups = "SELECT * FROM groups WHERE id={$group_found['group_id']}";
                $groups= mysqli_query($connection, $show_groups);

                if ($groups) {
                    foreach($groups as $group){  
                        echo "<li><a href=\"index.php?group=".$group['id']."\"><img src=\"".$group['avatar']."\" alt=\"Group Avatar\" /> ".$group['name']."</a></li>";
                    }//END DISPLAY GROUP DETAILS
                }//END GET GROUP DETAILS
        }//END LOOP THROUGH GROUPS
        echo "</ul>";
    }else{
        echo "You have no groups! Create one now! Then, You can invite members to join your group!";
    }//END FIND GROUPS USER IS IN
    
    
}
?>
 
        
        
      
<script>
$(document).ready(function() {
      $('#show-hidden-form').click(function() {
        $('.hidden-form').slideToggle("slow");
        // Alternative animation for example
        // slideToggle("fast");
      }); //END SHOW HIDDEN ADD GROUP FORM
    
          $('#show-hidden-addpost').click(function() {
        $('.hidden-addpost').slideToggle("slow");
        // Alternative animation for example
        // slideToggle("fast");
      }); //END SHOW HIDDEN ADD POST FORM
});
</script>
        
<?php include("inc/footer.php"); ?>