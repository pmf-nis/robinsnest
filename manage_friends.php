<?php
require_once 'functions.php';

if(isset($_POST['action']))
{
    if($_POST['action'] == 'add')
    {
        $id = $connection->real_escape_string($_POST['member_id']);
        $add = $connection->real_escape_string($_POST['friend_id']);
        
        $result = queryMysql("SELECT * FROM friends WHERE
        member_id = $id AND friend_id = $add");
        if(!$result->num_rows)
        {
            queryMysql("INSERT INTO friends(member_id, friend_id)
            VALUES ($id, $add)");
        }
        
        $result = queryMysql("SELECT * FROM members WHERE id=$add");
        $row = $result->fetch_assoc();
        
        echo "<a href='members.php?id=" . $row['id']
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
}