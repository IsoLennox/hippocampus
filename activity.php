<?php $current_page="activity";
include("inc/header.php"); 

if(isset($_GET['remove'])){
    $remove_invite  = "DELETE FROM alerts WHERE id={$_GET['remove']} LIMIT 1";
            $invite_result = mysqli_query($connection, $remove_invite);
          if ($invite_result && mysqli_affected_rows($connection) == 1) { 
                redirect_to("activity.php");
          }
}


?>
   
   
    <h1>Activity</h1>
    <ul>

       
<!--       FRIEND REQUESTS -->
        <?php
        $get_reuests = "SELECT * FROM contacts WHERE sent_to={$_SESSION['user_id']} AND accepted=0";
        $request_found= mysqli_query($connection, $get_reuests);
        $num_reuests=mysqli_num_rows($request_found);
//        if ($request_found) {
        if ($num_reuests>=1) {
               
            foreach($request_found as $user){ 
                
            if($_SESSION['user_id']==$user['user1']){
                $contact_id=$user['user2'];
            }else{
                $contact_id=$user['user1'];
            }  
                $contact_details=find_user_by_id($contact_id);
                echo "<div class=\"notification\"><a href=\"profile.php?user=".$contact_details['id']."\">".$contact_details['username']."</a> would like to be your friend!<br/><br/> <a class=\"green\" href=\"profile.php?user=".$contact_details['id']."&accept\">Accept</a> <a class=\"red\" href=\"profile.php?user=".$contact_details['id']."&remove=".$contact_details['id']."\">Deny</a><br/><br/></div>";
            }
        }    ?>
        
        
        
        
        
        <!--    GROUP INVITES  -->
        <?php
        $get_invites = "SELECT * FROM invites WHERE user_id={$_SESSION['user_id']} ORDER BY id DESC";
        $invites_found= mysqli_query($connection, $get_invites);
        $num_invites=mysqli_num_rows($invites_found);
//        if ($invites_found) {
        if ($invites_found>=1) {
              
            foreach($invites_found as $invite){ 
                 
                $invited_by=$invite['invited_by'];
                $group_id=$invite['group_id'];
             
                $invited_by=find_user_by_id($invited_by);
                $group=find_group_by_id($group_id);
                echo "<div class=\"notification\">You have been invited by ".$invited_by['username']." To join the group: <a href=\"index.php?group=".$group['id']."\">".$group['name']."</a>! <br/><br/><a class=\"green\"  href=\"index.php?join=".$group_id."\">Accept</a>   <a class=\"red\"  href=\"index.php?group=".$group_id."&decline\">Deny</a><br/><br/></div>";
            }
        }    ?>
        
        
        
        
        
        
<!--        accepted or denied friends or groups -->  
        
        <?php
        $get_alerts = "SELECT * FROM alerts WHERE user_id={$_SESSION['user_id']} ORDER BY id DESC";
        $alerts_found= mysqli_query($connection, $get_alerts);
        $num_alerts=mysqli_num_rows($alerts_found); 
        if ($alerts_found>=1) {
              
            foreach($alerts_found as $alert){  
              
                echo "<div class=\"notification\">".$alert['content']."<br/>".$alert['datetime']."<br/><br/><a class=\"red\" href=\"activity.php?remove=".$alert['id']."\">Thanks</a><br/><br/></div>";
            }
        }    ?>
<!--
        <li>New Friend Requests/Unfriended by whom/Removed from groups by whom</li>
        <li>Latest Post in each of your groups</li>
-->
    </ul>
        
<?php include("inc/footer.php"); ?>