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
    echo "<li><a href='members.php?id=" . $row['id'] 
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
        echo " [<a href='members.php?add=" 
                . $row['id'] . "'>$follow</a>]";
    }
    else {
        echo " [<a href='members.php?drop="
                . $row['id'] . "'>drop</a>]";
    }
}
?>

	</ul></div>
	</body>
</html>