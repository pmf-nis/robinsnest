<?php
require_once 'header.php';

if(!$loggedin) die();

echo "<div class='main'>";

if(isset($_GET['id']))
{
    $mid = $connection->real_escape_string($_GET['id']);
    $result = queryMysql("SELECT * FROM members WHERE id=$mid");
    if($result->num_rows)
    {
        $row = $result->fetch_assoc();
        $view = $row['user'];
    }
    else 
    {
        $view = "";    
    }
    if($view == $user) $name = "Your";
    else $name = "$view's";
    
    echo "<h3>$name Profile</h3>";
    showProfile($mid, $view);
    echo "<a class='button' href='messages.php?id=$mid'>
        View $name messages</a>";
    die("</div></body></html>");
}

/*
if(isset($_GET['add'])) 
{
    $add = $connection->real_escape_string($_GET['add']);
    $result = queryMysql("SELECT * FROM friends WHERE
        member_id = $id AND friend_id = $add");
    if(!$result->num_rows)
    {
        queryMysql("INSERT INTO friends(member_id, friend_id)
            VALUES ($id, $add)");
    }
}
*/

if(isset($_GET['drop']))
{
    $drop = $connection->real_escape_string($_GET['drop']);
    queryMysql("DELETE FROM friends WHERE member_id=$id
        AND friend_id = $drop");
}

$result = queryMysql("SELECT * FROM members ORDER BY user");

echo "<h3>Other Members</h3><ul>";
while($row = $result->fetch_assoc())
{
    if($row['id'] == $id) 
    {
        continue;
    }
    echo "<li id='" . $row['id'] . "'><a href='members.php?id=" . $row['id'] 
        . "'>" . $row['user'] . "</a>";
    
    $result1 = 
        queryMysql("SELECT * FROM friends WHERE member_id="
            . $row['id'] . " AND friend_id=" . $id);
    $t1 = $result1->num_rows;
    $result2 = 
        queryMysql("SELECT * FROM friends WHERE member_id=" 
            . $id . " AND friend_id=" . $row['id']);
    $t2 = $result2->num_rows;
    
    $follow = 'follow';
    if($t1 + $t2 > 1) {
        echo " &harr; is a mutual friend";
    }
    elseif($t1) {
        echo " &rarr; is following you";
        $follow = 'follow back';
    }
    elseif($t2) {
        echo " &larr; you are following";
    }
    
    if(!$t2) {
        echo " [<a class='add' myid='$id' friendid='" . $row['id'] . "'  
            href='members.php?add=" . $row['id'] . "'>
            $follow</a>]";
    }
    else {
        echo " [<a class='drop' href='members.php?drop="
                . $row['id'] . "'>drop</a>]";
    }
}
?>

	<script type="text/javascript">
		var linksAdd = document.getElementsByClassName('add');
		for(var i = 0; i < linksAdd.length; i++)
		{
			linksAdd[i].addEventListener("click", function(event) {
				event.preventDefault();
				var myid = this.getAttribute('myid');
				var friendid = this.getAttribute('friendid');
				var request = ajaxRequest();
				request.open("POST", "manage_friends.php", true);
				request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				request.onreadystatechange = function()
				{
					if(this.readyState == 4 && this.status == 200) {
						if(this.responseText != null) {
							document.getElementById(friendid).innerHTML = this.responseText;
						}
					} 
				}
				request.send("action=add&member_id=" + myid + "&friend_id=" + friendid);
			});
		}
	
    	function ajaxRequest() 
    	{
    		try {
    			var request = new XMLHttpRequest(); // novi browseri
    		}
    		catch(e1) {
    			try {
    				request = new ActiveXObject("Maxm12.XMLHTTP"); // IE6
    			}
    			catch(e2) {
    				try {
    					request = new ActiveXObject("Microsoft.XMLHTTP"); // IE...
    				}
    				catch(e3) {
    					request = false;
    				}	
    			}				
    		}
    		return request;
    	}
	</script>

	</ul></div>
	</body>
</html>