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
    <?php $avatar=find_user_by_id($_SESSION['user_id']); ?> 
   
        <ul>
            <a href="profile.php"><li class="left"><img src="<?php echo $avatar['avatar']; ?>" alt="profile img"> <?php echo $_SESSION['username']; ?></li></a>  
            <a href="logout.php"><li class="right"><i class="fa fa-sign-out"></i></li></a> 
 
        </ul>
        
        
    </header>
         
    <nav>
    <?php
    if(isset($current_page)){ ?>
        
        <ul id="nav">
                
                <?php if($current_page=="groups"){ ?>
                 <a class="active" href="index.php"><li><i class="fa fa-comment"></i></li></a>
                <?php }else{ ?>
                 <a href="index.php"><li><i class="fa fa-comment"></i></li></a>
                <?php } ?>
           
            
              
                <?php if($current_page=="contacts"){ ?>
                <a class="active" title="Your Contacts" href="contacts.php?user=<?php echo $_SESSION['user_id'] ?>"><li><i class="fa fa-users"></i></li></a>
                <?php }else{ ?>
                <a title="Your Contacts" href="contacts.php?user=<?php echo $_SESSION['user_id'] ?>"><li><i class="fa fa-users"></i></li></a>
                <?php } ?>
                
                <?php if($current_page=="activity"){ ?>
                <a class="active" href="activity.php"><li><i class="fa fa-bell-o"></i></li></a>
                <?php }else{ ?>
                <a href="activity.php"><li><i class="fa fa-bell-o"></i></li></a>
                <?php } ?>
                
                
                <?php if($current_page=="search"){ ?>
                <a class="active" href="search.php"><li><i class="fa fa-search"></i></li></a> 
                <?php }else{ ?>
                 <a href="search.php"><li><i class="fa fa-search"></i></li></a> 
                <?php } ?>
             
        </ul>
   
    
  <?php }else{ ?>
         <ul id="nav">
            <a href="index.php"><li><i class="fa fa-comment"></i></li></a> 
            <a title="Your Contacts" href="contacts.php?user=<?php echo $_SESSION['user_id'] ?>"><li><i class="fa fa-users"></i></li></a>
            <a href="activity.php"><li><i class="fa fa-bell-o"></i></li></a>
            <a href="search.php"><li><i class="fa fa-search"></i></li></a> 
 
        </ul>
        <?php
    }
?>

    </nav>
          
           
<div class="clearfix" id="page"> 
      <?php echo message(); ?>
      <?php echo form_errors($errors); ?>