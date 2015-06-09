<?php 
$current_page="groups";
include("inc/header.php"); 


if(isset($_POST['add_group'])){
    //CREATE NEW GROUP
    $name=addslashes($_POST['name']);
    $new_group .= "INSERT INTO groups (name, created_by) VALUES ( '{$name}', {$_SESSION['user_id']} )";
    $group_added = mysqli_query($connection, $new_group);

    if ($group_added) {
      // Success
        
        //GET GROUP ID
        $get_this_group .= "SELECT * FROM groups WHERE name='{$name}' ORDER BY id DESC LIMIT 1";
        $group_found= mysqli_query($connection, $get_this_group);

        if ($group_found) {
            $group_array=mysqli_fetch_assoc($group_found);
            
             //INSERT USER INTO USER_GROUPS
            $group_avatar="http://lorempixel.com/100/100/technics";
            $new_user_group .= "INSERT INTO user_group (user_id, group_id, avatar) VALUES ( {$_SESSION['user_id']}, {$group_array['id']}, '{$group_avatar}' )";
            $user_group_added = mysqli_query($connection, $new_user_group);

            if ($user_group_added) {
                    $_SESSION["message"] = "New Group Created!";
                    redirect_to("index.php");
            }else{
                $_SESSION["message"] = "Group Created, could not add you to group";
                redirect_to("index.php");
            }
        }else{
            $_SESSION["message"] = "Group Created, could not add you to group";
            redirect_to("index.php");
        }//end get group_id
         
    } else {
      // Failure
      $_SESSION["message"] = "Could Not Create Group";
        redirect_to("index.php");
        
    }//END CREATE GROUP
}elseif(isset($_POST['add_post'])){
    //CREATE NEW POST IN GROUP
    
    $content=addslashes($_POST['content']);
    $group_id=$_POST['group_id'];
    $datetime=date('m/d/Y h:i:s');
     
    $new_post .= "INSERT INTO posts (content, user_id, group_id, datetime) VALUES ( '{$content}', {$_SESSION['user_id']}, {$group_id}, '{$datetime}' )";
    $post_added = mysqli_query($connection, $new_post);

    if ($post_added) {
      // Success
        // Failure
      $_SESSION["message"] = "Post Saved!";
        redirect_to("index.php?group=".$group_id);
        
    } else {
      // Failure
      $_SESSION["message"] = "Could Not Create post";
        redirect_to("index.php?group=".$group_id);
        
    }//END CREATE POST IN GROUP
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
    
    
    
    
    //SPECIAL ACTIONS
    
    if(isset($_GET['edit'])){
        
        echo "<h1><a href=\"index.php?group=".$group_details_array['id']."\"><i class=\"fa fa-arrow-left\"> </i> ".$group_details_array['name']."</a></h1>";
        echo "Edit name, avatar, give admin rights away";
        echo "<img src=\"".$avatar."\" alt=\"Group Avatar\" />";
        
       
        echo "<div class=\"red\"><h3>Danger Zone</h3>";
        if(isset($_GET['delete'])){
        //DELETE GROUP
        ?>
           <h3>Delete This Group</h3>
           <span class="one_third">
            <form action="delete.php" method="POST">
                <p>Please enter your password</p>
                <input type="hidden" name="group_id" value="<?php echo $_GET['group'] ?>"><br/>
                <input type="password" name="password" placeholder="Password"><br/>
                <input type="submit" name="group" value="Delete" >
                <a href="index.php?group=<?php echo $group_details_array['id']; ?>&edit">Cancel</a>
            </form>
            </span>
            <span class="two_thirds">
                <p># Users will be kicked out</p>
                <p># Posts Will be deleted</p>
            </span>
            </div>
        <?php
        }else{
            
            echo " <a onclick=\"return confirm('DELETE group?');\" href=\"index.php?group=".$_GET['group']."&edit&delete\"><i class=\"fa fa-trash-o\"></i> Delete this group</a></div>";
        }//END DELETE
        
    }elseif(isset($_GET['leave'])){
    //DELETE GROUP
   
    $remove_user  = "DELETE FROM user_group WHERE group_id = {$_GET['group']} AND user_id={$_SESSION['user_id']} LIMIT 1";
    $user_result = mysqli_query($connection, $remove_user);

      if ($user_result && mysqli_affected_rows($connection) == 1) {
            $_SESSION["message"] = "You Left ".$group_name.". You must be invited to join again."; 
            redirect_to("index.php");
      }
    
    }elseif(isset($_GET['members'])){
             
        
        //INVITE MEMBERS TO THIS GROUP
        echo "<h1><a href=\"index.php?group=".$_GET['group']."\"><i class=\"fa fa-arrow-left\"> </i> ".$group_name."</a></h1>";
        
        
        echo "<span class=\"half\"><h2>Members</h2>";
        //GET GROUP MEMBERS
        $get_users .= "SELECT * FROM user_group WHERE group_id={$_GET['group']}";
        $group_users= mysqli_query($connection, $get_users);
        if ($group_users) {
            echo "<ul>";
            foreach($group_users as $user){
                $user_details=find_user_by_id($user['user_id']);
                //REMOVE USER IF ADMIN
//                if($user_details['id']==$_SESSION['user_id']){
//                    
//                }else{
//                
//                }
                echo "</li><a href=\"profile.php?user=".$user_details['id']."\"><img src=\"".$user_details['avatar']."\" alt=\"User Avatar\" /> ".$user_details['username']."</a></li>";
            }   
            echo "</ul>";
        } //END GET GROUP MEMBERS
        echo "</span>";
        
        
        
        echo "<span class=\"half\"><h2>Invite</h2>";
                //GET CONTACTS
        $get_contacts .= "SELECT * FROM contacts WHERE (user1={$_SESSION['user_id']} OR user2={$_SESSION['user_id']}) AND accepted=1";
        $group_contacts= mysqli_query($connection, $get_contacts);
        if ($group_contacts) {
            echo "<ul>";
            foreach($group_contacts as $contact){
                //GET USER THAT IS NOT YOU
                if($_SESSION['user_id']==$contact['user1']){
                    $contact_id=$contact['user2'];
                }else{
                    $contact_id=$contact['user1'];
                }
                $contact_details=find_user_by_id($contact_id);
                //REMOVE contact IF ADMIN
//                if($contact_details['id']==$_SESSION['contact_id']){
//                    
//                }else{
//                
//                }
                
                //SEE IF USER IS IN THIS GROUP
                
                //...
                echo "</li><a href=\"profile.php?user=".$contact_details['id']."\"><img src=\"".$contact_details['avatar']."\" alt=\"contact Avatar\" /> ".$contact_details['username']."</a></li>";
            }   
            echo "</ul>";
        }else{
            echo "You have no contacts!";
        } //END GET GROUP MEMBERS
        echo "</span>";
        
        
        
    
    }else{
        
        
        
        
        
        
    //get all posts from this group
        
        

    
    //GET GROUP MEMBERS
    $get_users .= "SELECT * FROM user_group WHERE group_id={$_GET['group']}";
    $group_users= mysqli_query($connection, $get_users);
    if ($group_users) {
        $num_users=0;
        foreach($group_users as $user){
            $num_users++;
        }
        $num_users="<a href=\"index.php?group=".$_GET['group']."&members\">".$num_users." <i class=\"fa fa-users\"></i></a>";  
    } 
    
    
       if($_SESSION['user_id']==$created_by){
                //GIVE GROUP ADMIN EDIT AND DELETE RIGHTS
                $can_edit= "<a href=\"index.php?group=".$_GET['group']."&edit\"><i class=\"fa fa-pencil\"></i></a>";
                $can_leave="";
            }else{
                $can_edit="";
                $can_leave= " <a onclick=\"return confirm('LEAVE group? You must be invited to join again.');\" href=\"index.php?group=".$_GET['group']."&leave\"><i class=\"fa fa-sign-out\"></i></a>"; 
            }  ?> 
    <h1><?php echo $group_name." ".$can_edit; ?> <span class="right group_actions"><?php echo $num_users." ".$can_leave;  ?> 
    </span></h1>
    <?php
       
 
         

     
      
        ?>
     
    <h3 id="show-hidden-addpost"><i class="fa fa-plus-circle"></i> New Post</h3>
    <form class="hidden-addpost" style="display: none;" method="POST" enctype="multipart/form-data">  
         
        <textarea cols="80" rows="5" name="content" placeholder="Say Something..."></textarea><br/>
<!-- ADD IMAGE FUNCTIONALITY LATER
        <label for="image">Upload an image</label>
        <input type="file" name="image" id="fileToUpload"><br/>
-->
        <input type="hidden" value="<?php echo $_GET['group']; ?>" name="group_id">
        <input type="submit" value="Post" name="add_post">
    </form>
    
    <?php 
    //DETERMINE POST STYLE
    if(!isset($_GET['grid'])){ ?>
    <span class="right"><a href="index.php?group=<?php echo $_GET['group']; ?>&grid"><i class="fa fa-2x fa-picture-o"></i></a></span>
    <?php
    $get_posts .= "SELECT * FROM posts WHERE group_id={$_GET['group']} ORDER BY id DESC";
     }else{ ?>
    <span class="right"><a href="index.php?group=<?php echo $_GET['group']; ?>"><i class="fa fa-2x fa-bars"></i></a></span>
    <?php   
    $get_posts .= "SELECT * FROM posts WHERE group_id={$_GET['group']} AND image!='' ORDER BY id DESC";   } ?>
    
     
    <br>
    <br>
<!--    CHOOSE GROUP, UPLOAD PHOTO, SAY SOMETHING-->
    
    <?php
    
    $posts_found= mysqli_query($connection, $get_posts);
    if ($posts_found) {
        foreach($posts_found as $post){
            $posted_by=find_user_by_id($post['user_id']);
            echo "<div class=\"post_container\">
            <span class=\"one_third user_panel\"><img src=\"".$posted_by['avatar']."\" alt=\"User avatar\" /> <br/>".$posted_by['username']."</span><span class=\"two_thirds\"><span class=\"right\">".$post['datetime']."</span> <br/><img src=\"".$post['image']."\" alt=\"Upload\" /><br/>".$post['content']."</span></div>";
        }//end foreach post
        
    }else{
    
        echo "No Posts Found!";
    }//end show each post in group
    
    
    }//end check if this is viewing or deleting group
}else{ 
//    Show Group names
    ?>
    
        <h3>Groups<span class="right" id="show-hidden-form"><i class="fa fa-plus-circle"></i> New Group</span></h3>
    
        
    <form class="hidden-form" style="display: none;" method="POST" enctype="multipart/form-data">  
        <label for="name">Group Name</label>
        <input type="text" name="name" placeholder="Group Name"><br/>
        <input type="submit" value="Create Group" name="add_group">
    </form>
    
       <?php
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