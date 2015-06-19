<?php 
include("inc/header.php");
    $get_posts = "SELECT * FROM posts WHERE id={$_GET['id']} ";   
    $posts_found= mysqli_query($connection, $get_posts);
    $posts_num= mysqli_num_rows($posts_found);
    if ($posts_found) {
        if($posts_num==0){ echo "<div class=\"center\">No Posts Yet!</div>";}
        foreach($posts_found as $post){
            $posted_by=find_user_by_id($post['user_id']);
               if(!empty($post['image'])){
                        $image="<img src=\"".$post['image']."\" />";
                       }else{
                        $image="";
                       }
            
            echo "<div class=\"post_container\"> 
            <span class=\"one_third user_panel\"><img src=\"".$posted_by['avatar']."\" alt=\"User avatar\" /> <br/>".$posted_by['username']."</span><span class=\"two_thirds\"><span class=\"right\">".$post['datetime']."</span> <br/>".$image."<br/>".$post['content']."</span></div>";
        }//end foreach post
        
        
        
        echo "<h2>Comments</h2>";
    }else{
        echo "<div class=\"center\">This post has beel deleted by the user!</div>";
    }//end show each post in group
 ?>