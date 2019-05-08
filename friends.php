<?php

require_once 'header.php';
if(!$loggedin) die();

$followers = array();
$following = array();

$mid = $id;

$result = queryMysql("SELECT friend_id FROM friends 
        WHERE member_id = $mid");
while($row = $result->fetch_assoc())
{
    $following[] = $row['friend_id'];
}

$result = queryMysql("SELECT member_id FROM friends
        WHERE friend_id = $mid");
while($row = $result->fetch_assoc())
{
    $followers[] = $row['member_id'];
}

$mutual = array_intersect($followers, $following);
$followers = array_diff($followers, $mutual);
$following = array_diff($following, $mutual);
$friends = false;

echo "<div class='main'>";

if(sizeof($mutual))
{
    echo "<span class='sunhead'>mutual friends</span><ul>";
    foreach ($mutual as $friend)
    {
        $result = queryMysql("SELECT * FROM members WHERE id=$friend");
        $row = $result->fetch_assoc();
        $friendName = $row['user'];
        echo "<li><a href='members.php?id=$friend'>$friendName</a>";
    }
    echo "</ul>";
    $friends = true;
}
if(sizeof($followers))
{
    echo "<span class='sunhead'>followers</span><ul>";
    foreach ($followers as $friend)
    {
        $result = queryMysql("SELECT * FROM members WHERE id=$friend");
        $row = $result->fetch_assoc();
        $friendName = $row['user'];
        echo "<li><a href='members.php?id=$friend'>$friendName</a>";
    }
    echo "</ul>";
    $friends = true;
}
if(sizeof($following))
{
    echo "<span class='sunhead'>following</span><ul>";
    foreach ($following as $friend)
    {
        $result = queryMysql("SELECT * FROM members WHERE id=$friend");
        $row = $result->fetch_assoc();
        $friendName = $row['user'];
        echo "<li><a href='members.php?id=$friend'>$friendName</a>";
    }
    echo "</ul>";
    $friends = true;
}

if(!$friends)
{
    echo "<br>You don't have any friends yet.<br><br>";
}

echo "<a class='button' href='messages.php?id=$mid'>
    View messages</a>";

?>
	</div><br></body></html>
	