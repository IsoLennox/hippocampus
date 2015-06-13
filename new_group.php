<?php

    //CREATE NEW GROUP
    $name=addslashes($_POST['name']);
    $group_avatar="http://lorempixel.com/100/100/technics"; 
    $new_group .= "INSERT INTO groups (name, created_by, avatar) VALUES ( '{$name}', {$_SESSION['user_id']}, '{$group_avatar}' )";
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
    ?>