<?php $current_page="contacts";
include("inc/header.php");  ?>
<!--//AJAX SERACH THROUGH CONTACTS-->
<!--
     <form class="search" id="search" action="#" method="post">
        <input name="query" value="" placeholder="Search Contacts" autocomplete="off" name="author" id="author" value="<?php echo $name; ?>" type="text"  />
        <input type="submit" name="submit" value="&#xf002;" />
    </form> 
-->
    <br> 
    <br> 
    <br> 
<?php
        $get_contacts .= "SELECT * FROM contacts WHERE (user1={$_SESSION['user_id']} OR user2={$_SESSION['user_id']}) AND accepted=1";
        $group_contacts= mysqli_query($connection, $get_contacts);
        if ($group_contacts) {
            echo "<div class=\"grid\">";
            foreach($group_contacts as $contact){
                if($_SESSION['user_id']==$contact['user1']){
                    $contact_id=$contact['user2'];
                }else{
                    $contact_id=$contact['user1'];
                }
                $contact_details=find_user_by_id($contact_id); 
                echo "<span class=\"grid_item\"><a href=\"profile.php?user=".$contact_details['id']."\"><img src=\"".$contact_details['avatar']."\" alt=\"contact Avatar\" /> <br/>".$contact_details['username']."</a><br/><br/>";
                    
                    
//               echo "<a href=\"profile.php?remove=".$contact_details['id']."\"><i class=\"fa fa-user-times\"></i> Remove Contact</a>";
                echo "</span>";
            }   
            echo "</div>";
        }else{
            echo "You have no contacts!";
        } //END GET GROUP MEMBERS
        
include("inc/footer.php"); ?>