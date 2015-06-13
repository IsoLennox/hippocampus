<?php

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
 
                
                //SEE IF USER IS IN THIS GROUP, if not show user
                
                $get_invite .= "SELECT * FROM user_group WHERE group_id={$_GET['group']} AND user_id={$contact_details['id']}";
                $user_in_group= mysqli_query($connection, $get_invite);
                if ($user_in_group) {
                    $num_user_row=mysqli_num_rows($user_in_group);
                    if($num_user_row==0){
                      echo "</li><a href=\"profile.php?user=".$contact_details['id']."\"><img src=\"".$contact_details['avatar']."\" alt=\"contact Avatar\" /> ".$contact_details['username']."</a> <a href=\"index.php?invite=".$contact_details['id']."&group=".$_GET['group']."\">INVITE</a></li>";
                    }
                }//end show user if they are not in this group
              
            }  //end foreach contact 
            echo "</ul>";
        }else{
            echo "You have no contacts!";
        } //END GET GROUP MEMBERS
        echo "</span>";
        
        
        ?>