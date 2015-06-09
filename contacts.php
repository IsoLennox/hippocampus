<?php $current_page="contacts";
include("inc/header.php");  

        $get_contacts .= "SELECT * FROM contacts WHERE (user1={$_SESSION['user_id']} OR user2={$_SESSION['user_id']}) AND accepted=1";
        $group_contacts= mysqli_query($connection, $get_contacts);
        if ($group_contacts) {
            echo "<ul>";
            foreach($group_contacts as $contact){
                if($_SESSION['user_id']==$contact['user1']){
                    $contact_id=$contact['user2'];
                }else{
                    $contact_id=$contact['user1'];
                }
                $contact_details=find_user_by_id($contact_id); 
                echo "<li><a href=\"profile.php?user=".$contact_details['id']."\"><img src=\"".$contact_details['avatar']."\" alt=\"contact Avatar\" /> ".$contact_details['username']."</a><a class=\"right\" href=\"profile.php?remove=".$user_id."\"><i class=\"fa fa-user-times\"></i>
                         Remove Contact</a></li><hr/>";
            }   
            echo "</ul>";
        }else{
            echo "You have no contacts!";
        } //END GET GROUP MEMBERS
        
include("inc/footer.php"); ?>