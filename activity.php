<?php include("inc/header.php"); ?>
   
   
    <h1>Activity</h1>
    <ul>

       
<!--       FRIEND REQUESTS -->
        <?php
        $get_reuests .= "SELECT * FROM contacts WHERE sent_to={$_SESSION['user_id']} AND accepted=0";
        $request_found= mysqli_query($connection, $get_reuests);
        $num_reuests=mysqli_num_rows($request_found);
//        if ($request_found) {
        if ($num_reuests>=1) {
              
            echo "<h2>New Friend Requests</h2>";
            foreach($request_found as $user){ 
                
            if($_SESSION['user_id']==$user['user1']){
                $contact_id=$user['user2'];
            }else{
                $contact_id=$user['user1'];
            }  
                $contact_details=find_user_by_id($contact_id);
                echo "<a href=\"profile.php?user=".$contact_details['id']."\">".$contact_details['username']."</a> <a href=\"profile.php?user=".$contact_details['id']."&accept\">Accept</a> <a href=\"profile.php?user=".$contact_details['id']."&remove=".$contact_details['id']."\">Deny</a>";
            }
        }    ?>
        
<!--        accepted or denied -->
        <!--        <li>New group invitations</li>-->
<!--
        <li>New Friend Requests/Unfriended by whom/Removed from groups by whom</li>
        <li>Latest Post in each of your groups</li>
-->
    </ul>
        
<?php include("inc/footer.php"); ?>