<?php require_once("inc/session.php"); 
require_once("inc/functions.php"); 
require_once("inc/db_connection.php"); 
require_once("inc/validation_functions.php"); 
confirm_logged_in(); ?>
<!DOCTYPE html>
<html>
    
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Hippocampus</title>
        <meta name="description" content="An Memory Book">
<!--        Main stylesheet-->
        <link rel="stylesheet" href="css/style.css">
<!--        link to font awesome-->
         <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"> 
<!--   JS VERSIONS-->
  <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  <script src="https://code.jquery.com/jquery-2.1.1.js"></script>
  <script src="https://code.jquery.com/jquery-2.1.3.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
   
    </head>
    

<body>
     
    <script>
        //FADE OUT MESSAGES
      setTimeout(function() {
          $(".message").fadeOut(800);
      }, 5000);

//MENU DROPDOWN

$(document).ready(function() {
  $('#show-hidden-menu').click(function() {
    $('.hidden-menu').slideToggle("slow");
    // Alternative animation for example
    // slideToggle("fast");
  });
});


     </script>
 
    <header> 
            <!--   HEADER SEARCH BAR       -->
 
    <form class="search" id="search" action="search.php" method="post">
        <input name="query" value="" placeholder="Search Users and Memories..." autocomplete="off" name="author" id="author" value="<?php echo $name; ?>" type="text"  />
        <input type="submit" name="submit" value="&#xf002;" />
    </form>  

    <span class="logo left"><a href="index.php" title="Hippocampus Home"><i class="fa fa-home"></i></a></span>
    <?php $avatar=find_user_by_id($_SESSION['user_id']); ?>
        <div id="show-hidden-menu"><img src="<?php echo $avatar['avatar']; ?>" alt="profile img"> <?php echo $_SESSION['username']; ?></div>
    <span class="hidden-menu" style="display: none;">
      <ul>
          <li><a title="Your Profile" href="profile.php?user=<?php echo $_SESSION['user_id'] ?>">Profile</a></li>
          <li><a title="Your Profile" href="contacts.php?user=<?php echo $_SESSION['user_id'] ?>">Contacts</a></li>
          <li><a title="Manage Account Settings" href="settings.php?user=<?php echo $_SESSION['user_id'] ?>">Settings</a></li>
        <li><a title="Log Out" href="logout.php">Log Out</a> </li>
      </ul>
        </span> 
    </header>
         
    <nav>
        <ul id="nav">
            <a href="#"><li><i class="fa fa-comment"></i></li></a>
            <a href="#"><li><i class="fa fa-users"></i></li></a>
            <a href="#"><li><i class="fa fa-bell-o"></i></li></a>
            <a href="#"><li><i class="fa fa-search"></i></li></a> 
 
        </ul>
    </nav>
          
           
<div class="clearfix" id="page"> 
      <?php echo message(); ?>
      <?php echo form_errors($errors); ?>