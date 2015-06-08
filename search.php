<?php include("inc/header.php"); ?> 

<?php 

if(isset($_POST['submit'])){
           
    

//    HEADER SEARCH  
    
        if(preg_match("/^[_a-zA-Z0-9- ]+$/", $_POST['query'])){
         
           // get string entered
                $string=$_POST['query'];
                
//            //if space separated, explode string
//            $string_array= explode(" ",$string);
            
                 //if comma separated, explode string
                $string_array= explode(",",$string);
         
            //prepare variable for no results message
            $no_results=4;
            
            
        foreach($string_array as $word){ 
            $query_string=$word; 
       
 
 
            
 //            =======================================================
            
                        //USERNAME SEARCH
            
//            =======================================================
            
                  
     
            $username_search="SELECT * FROM users WHERE username LIKE '%" . $query_string ."%' ";
            //-run  the query against the mysql query function
            $username_result=mysqli_query($connection, $username_search);
            if($username_result){
                $username_result_array=mysqli_fetch_assoc($username_result);
                if(!empty($username_result_array)){
            
            
                echo "<h2 class=\"clear\">Usernames that contain \"". $query_string ."\":</h2>";
                   foreach($username_result as $user_match){
                        $username  =$user_match['username']; 
                        $user_id  =$user_match['id'];
  
                 //STYLE OUTPUT      
                       echo "<a href=\"profile.php?user=$user_id\"><h3> <img src=\"".$user_match['avatar']."\" /> ".$username."</h3></a>";
                    }//end foreach username found with name match
                }else{
//                echo "<br/>No usernames contain '". $query_string."'<br/>";
                }
            }else{
                echo "No usernames match this query";
               // mark no results variable
                $no_results-=1;
            }// END SEARCH USERNAME!
            
 
            
            
            }//end foreach string
        }//end PREG MATCH
   
    
     
    
}//END SUBMIT
 
include("inc/footer.php"); ?> 