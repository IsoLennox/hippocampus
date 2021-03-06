<?php ob_start();

function redirect_to($new_location) {
    header("Location: " . $new_location);
	  exit; }

function logged_in(){
    return isset($_SESSION['user_id']);
}


function attempt_login($username, $password) {
		$user = find_user_by_username($username);
		if ($user) {
			// found user, now check password
			//if (password_check($password, $user["hashed_password"])) {
            if (password_verify($password, $user["password"])){
				// password matches
				return $user;
			} else {
				// password does not match
				return false;
			}
		} else {
			// user not found
			return false;
		}
	}



function check_password($user_id, $password) {
		$user = find_user_by_id($user_id);
		if ($user) {
			// found user, now check password
			//if (password_check($password, $user["hashed_password"])) {
            if (password_verify($password, $user["password"])){
				// password matches
				return $user;
			} else {
				// password does not match
				return false;
			}
		} else {
			// user not found
			return false;
		}
	}


 



function confirm_logged_in(){
    if (!logged_in()){
        redirect_to("login.php");
    }
}	



function mysql_prep($string) {
		global $connection;
		
		$escaped_string = mysqli_real_escape_string($connection, $string);
		return $escaped_string;
	}
	
function confirm_query($result_set) {
		if (!$result_set) {
			die("Database query failed.");
		}
	}

function form_errors($errors=array()) {
		$output = "";
		if (!empty($errors)) {
		  $output .= "<div class=\"error\">";
		  $output .= "Please fix the following errors:";
		  $output .= "<ul>";
		  foreach ($errors as $key => $error) {
		    $output .= "<li>";
				$output .= htmlentities($error);
				$output .= "</li>";
		  }
		  $output .= "</ul>";
		  $output .= "</div>";
		}
		return $output;
	}
	
 

//USERS

function find_user_by_id($user_id) {
    global $connection;

    $safe_user_id = mysqli_real_escape_string($connection, $user_id);

    $query  = "SELECT * ";
    $query .= "FROM users ";
    $query .= "WHERE id = {$safe_user_id} ";
    $query .= "LIMIT 1";
    $user_set = mysqli_query($connection, $query);
    confirm_query($user_set);
    if($user = mysqli_fetch_assoc($user_set)) {
        return $user;
    } else {
        return null;
    }
}

function find_user_by_username($username) {
    global $connection;
 

    $query  = "SELECT * ";
    $query .= "FROM users ";
    $query .= "WHERE username = '{$username}' ";
    $query .= "LIMIT 1";
    $user_set = mysqli_query($connection, $query);
    confirm_query($user_set);
    if($user = mysqli_fetch_assoc($user_set)) {
        return $user;
    } else {
        return null;
    }
}
 

function find_user_by_email($email) {
    global $connection;

    $safe_email = mysqli_real_escape_string($connection, $email);

    $query  = "SELECT * ";
    $query .= "FROM users ";
    $query .= "WHERE email = '{$safe_email}' ";
    $query .= "LIMIT 1";
    $user_set = mysqli_query($connection, $query);
    confirm_query($user_set);
    if($user = mysqli_fetch_assoc($user_set)) {
        return $user;
    } else {
        return null;
    }
}


//GROUPS

function find_group_by_id($group_id) {
    global $connection;

    $safe_group_id = mysqli_real_escape_string($connection, $group_id);

    $query  = "SELECT * ";
    $query .= "FROM groups ";
    $query .= "WHERE id = {$safe_group_id} ";
    $query .= "LIMIT 1";
    $group_set = mysqli_query($connection, $query);
    confirm_query($group_set);
    if($group = mysqli_fetch_assoc($group_set)) {
        return $group;
    } else {
        return null;
    }
}



function find_users_groups($user_id) {
    global $connection;
 

    $query  = "SELECT * ";
    $query .= "FROM user_group ";
    $query .= "WHERE user_id = {$user_id} ";
    $query .= "LIMIT 1";
    $group_set = mysqli_query($connection, $query);
    confirm_query($group_set);
    if($groups = mysqli_fetch_assoc($group_set)) {
        return $groups;
    } else {
        return null;
    }
} 

 
 
	
?>