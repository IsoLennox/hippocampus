   <?php

//GET GROUP MEMBERS  COUNT
    $get_users .= "SELECT COUNT(*) as members FROM user_group WHERE group_id={$_GET['group']}";
    $group_users= mysqli_query($connection, $get_users);
    if ($group_users) {
         $members=mysqli_fetch_assoc($group_users);
        $members=$members['members'];
        $num_users="<a href=\"index.php?group=".$_GET['group']."&members\">".$members." <i class=\"fa fa-users\"></i></a>";  
    } 
    
    ?>
        <div id="group_head">
            <?php
       if($_SESSION['user_id']==$created_by){
                //GIVE GROUP ADMIN EDIT AND DELETE RIGHTS
                $can_edit= "<a href=\"index.php?group=".$_GET['group']."&edit\"><i class=\"fa fa-pencil\"></i></a>";
                $can_leave="";
                $member=1;
            }else{
                $can_edit="";
           
                //SEE IF USER IF A MEMBER
           
                //if user is member, option to leave group
                //else, check if user has invitation
                    //if invitation only show join option / else show no permissions error
           
                $get_membership .= "SELECT * FROM user_group WHERE group_id={$_GET['group']} AND user_id={$_SESSION['user_id']} LIMIT 1";
                $member_found= mysqli_query($connection, $get_membership); 
                $num_members=mysqli_num_rows($member_found);
                    if($num_members>=1){
                        $can_leave= " <a onclick=\"return confirm('LEAVE group? You must be invited to join again.');\" href=\"index.php?group=".$_GET['group']."&leave\"><i class=\"fa fa-sign-out\"></i></a>"; 
                        $member=1;
                    }else{
                        $can_edit= "";
                        $can_leave= "";
                        $member=0;

                    }//end check to see if user is member
            }//end check if user is owner

if($member==0){
    
    //NOT A MEMBER, GIVE OPTION TO JOIN IF HAS INVITATION
    echo "<h1><span id=\"group_title\">".$group_name."</span> ".$can_edit;   
    
    //CHECK FOR INVITATION
                $get_invitation .= "SELECT * FROM invites WHERE group_id={$_GET['group']} AND user_id={$_SESSION['user_id']} LIMIT 1";
                $invite_found= mysqli_query($connection, $get_invitation); 
                $num_invites=mysqli_num_rows($invite_found);
                    if($num_invites>=1){
                         $join="<a href=\"index.php?join=".$_GET['group']."\">JOIN</a>";
                    }else{ 
                        $join="You must be invited to join this group";
                    }//end find invite
    
    
    echo "<span class=\"right group_actions\"> ".$join."</span></h1>";   
    
    echo "You must be a member of this group to view content";

}else{
        ?> 
    <h1><?php echo "<span id=\"group_title\">".$group_name."</span> ".$can_edit; ?> <span class="right group_actions"><?php echo $num_users." ".$can_leave;  ?> 
    </span></h1>
   
       
  
 
    <h4 id="show-hidden-addpost"><i class="fa fa-plus-circle"></i> New Post</h4>
    <form class="hidden-addpost" style="display: none;" method="POST" enctype="multipart/form-data">  
         
        <textarea cols="80" rows="5" name="content" placeholder="Say Something..."></textarea><br/>
<!-- ADD IMAGE FUNCTIONALITY LATER
        <label for="image">Upload an image</label>
        <input type="file" name="image" id="fileToUpload"><br/>
-->
        <input type="hidden" value="<?php echo $_GET['group']; ?>" name="group_id">
        <input type="submit" value="Post" name="add_post">
    </form>
    
    </div><!--    end group head-->
    
    
    
    
    <?php 
    //DETERMINE POST STYLE
    if(!isset($_GET['grid'])){
    ?>
    <span class="right"><a href="index.php?group=<?php echo $_GET['group']; ?>&grid"><i class="fa fa-2x fa-picture-o"></i></a></span>
    <?php
    $get_posts .= "SELECT * FROM posts WHERE group_id={$_GET['group']} ORDER BY id DESC";
     }else{ ?>
    <span class="right"><a href="index.php?group=<?php echo $_GET['group']; ?>"><i class="fa fa-2x fa-bars"></i></a></span>
    <?php   
    $get_posts .= "SELECT * FROM posts WHERE group_id={$_GET['group']} AND image!='' ORDER BY id DESC";   
        } ?>
    
     
<!--    CHOOSE GROUP, UPLOAD PHOTO, SAY SOMETHING-->
    
    <?php
    
    $posts_found= mysqli_query($connection, $get_posts);
    $posts_num= mysqli_num_rows($posts_found);
    if ($posts_found) {
        if($posts_num==0){ echo "<div class=\"center\">No Posts Yet!</div>";}
        foreach($posts_found as $post){
            $posted_by=find_user_by_id($post['user_id']);
            echo "<div class=\"post_container\">
            <span class=\"one_third user_panel\"><img src=\"".$posted_by['avatar']."\" alt=\"User avatar\" /> <br/>".$posted_by['username']."</span><span class=\"two_thirds\"><span class=\"right\">".$post['datetime']."</span> <br/><img src=\"".$post['image']."\" alt=\"Upload\" /><br/>".$post['content']."</span></div>";
        }//end foreach post
        
        
    }else{
    
        echo "<div class=\"center\">No Posts Yet!</div>";
    }//end show each post in group

}//end show content to members