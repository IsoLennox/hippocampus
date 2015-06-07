<?php include("inc/header.php"); ?>
<?php

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
            $new_user_group .= "INSERT INTO user_group (user_id, group_id) VALUES ( {$_SESSION['user_id']}, {$group_array['id']} )";
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
}

?>
 
<section class="one_third">
    <h1>Groups</h1>
    
       <?php
//GET ALL GROUPS USER IS IN
    $get_my_groups .= "SELECT * FROM user_group WHERE user_id={$_SESSION['user_id']}";
    $groups_found= mysqli_query($connection, $get_my_groups);

    if ($groups_found) {
        echo "<ul>";
        echo "<li><a href=\"index.php\">Activity</a></li>";
        foreach($groups_found as $group_found){
             //GET GROUP DETAILS
                $show_groups .= "SELECT * FROM groups WHERE id={$group_found['group_id']}";
                $groups= mysqli_query($connection, $show_groups);

                if ($groups) {
                    foreach($groups as $group){
                        
                        if(isset($_GET['group'])){
                            $group_on=$_GET['group'];
                            if($group_on==$group['id']){ $active="active";}else{ $active="";}
                        }
                        echo "<li class=\"$active\"><a href=\"index.php?group=".$group['id']."\">".$group['name']."</a></li>";
                    }//END DISPLAY GROUP DETAILS
                }//END GET GROUP DETAILS
        }//END LOOP THROUGH GROUPS
        echo "</ul>";
    }else{
        echo "You have no groups! Create one now! Then, You can invite members to join your group!";
    }//END FIND GROUPS USER IS IN



    
    ?>
     
 

    <h3 id="show-hidden-form"><i class="fa fa-plus-circle"></i> New Group</h3>
    <form class="hidden-form" style="display: none;" method="POST" enctype="multipart/form-data">  
        <label for="name">Group Name</label>
        <input type="text" name="name" placeholder="Group Name"><br/>
<!-- ADD AVATAR FUNCTIONALITY LATER
        <label for="image">Upload a group avatar</label>
        <input type="file" name="image" id="fileToUpload"><br/>
-->
        <input type="submit" value="Create Group" name="add_group">
    </form>
</section>







<!-- ****  -->
<!-- FEED -->
<!-- ****  -->





<section class="two_thirds">
   
   <?php

//GET GROUP ID CHOSEN, ELSE SHOW FEED FROM ALL GROUPS USER IS IN
if(isset($_GET['group'])){
    //get all posts from this group
    $group="WHERE group_id={$_GET['group']}";
    
    //GET GROUP NAME
    $this_group .= "SELECT * FROM groups WHERE id={$_GET['group']}";
    $group_details= mysqli_query($connection, $this_group);
    if ($group_details) {
        $group_details_array=mysqli_fetch_assoc($group_details);       
        $group_name=$group_details_array['name'];
    }else{
        $group_name="Undefined";
    }
    
    //GET GROUP MEMBERS
    $get_users .= "SELECT * FROM user_group WHERE group_id={$_GET['group']}";
    $group_users= mysqli_query($connection, $get_users);
    if ($group_users) {
        $num_users=0;
        foreach($group_users as $user){
            $num_users++;
        }
        $num_users=$num_users." Members";  
    } ?>
    

    <h1><?php echo $group_name; ?> Feed <span class="right"><?php echo $num_users; ?></span></h1>
    <a href="#">New Post</a><br/><hr/><br/>
<!--    CHOOSE GROUP, UPLOAD PHOTO, SAY SOMETHING-->
    
    <?php
    $get_posts .= "SELECT * FROM posts $group ORDER BY id DESC";
    $posts_found= mysqli_query($connection, $get_posts);
    if ($posts_found) {
        foreach($posts_found as $post){
            echo $post['content']."<br/>";
        }//end foreach post
        
    }else{
    
        echo "No Posts Found!";
    }
    
    
}else{ ?>
    <h1>Activity</h1>
    <ul>
        <li>New group invitations</li>
        <li>New Friend Requests/Unfriended by whom/Removed from groups by whom</li>
        <li>Latest Post in each of your groups</li>
    </ul>
<?php
}

?>

 
</section>
        
        
        
        
        
        
      
<script>
$(document).ready(function() {
      $('#show-hidden-form').click(function() {
        $('.hidden-form').slideToggle("slow");
        // Alternative animation for example
        // slideToggle("fast");
      }); //END SHOW HIDDEN ADD GROUP FORM
});
</script>
        
<?php include("inc/footer.php"); ?>