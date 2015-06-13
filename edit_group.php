<?php

        echo "<h1><a href=\"index.php?group=".$group_details_array['id']."\"><i class=\"fa fa-arrow-left\"> </i> ".$group_details_array['name']."</a></h1>";
        echo "Edit name, avatar, give admin rights away";
        echo "<img src=\"".$avatar."\" alt=\"Group Avatar\" />";
        
       
        echo "<div class=\"red\"><h3>Danger Zone</h3>";
        if(isset($_GET['delete'])){
        //DELETE GROUP
        ?>
           <h3>Delete This Group</h3>
           <span class="one_third">
            <form action="delete.php" method="POST">
                <p>Please enter your password</p>
                <input type="hidden" name="group_id" value="<?php echo $_GET['group'] ?>"><br/>
                <input type="password" name="password" placeholder="Password"><br/>
                <input type="submit" name="group" value="Delete" >
                <a href="index.php?group=<?php echo $group_details_array['id']; ?>&edit">Cancel</a>
            </form>
            </span>
            <span class="two_thirds">
                <p># Users will be kicked out</p>
                <p># Posts Will be deleted</p>
            </span>
            </div>
        <?php
        }else{
            
            echo " <a onclick=\"return confirm('DELETE group?');\" href=\"index.php?group=".$_GET['group']."&edit&delete\"><i class=\"fa fa-trash-o\"></i> Delete this group</a></div>";
        }//END DELETE
        
        ?>