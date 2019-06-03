<?php
session_start();
require_once 'functions.php';
if(!$_SESSION['id']) {
    die();
}


if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $connection->real_escape_string($_POST['username']);
    $friend = $connection->real_escape_string($_POST['friend']);
    $id = $connection->real_escape_string($_POST['id']);
    
    $queryString = "SELECT members.id AS id, members.user AS user FROM members ";
    if($friend != 0) {
        $queryString .= "LEFT JOIN friends ON ";
        switch($friend) {
            case 1:
                $queryString .= "members.id = friends.friend_id 
                    WHERE friends.member_id = $id ";
                break;
            case 2: case 3:
                $queryString .= "members.id = friends.member_id
                    WHERE friends.friend_id = $id ";
                break;
        }
    }
    if($username != "") {
        $queryString .= ($friend == 0 ? " WHERE " : " AND ") 
            . " members.user LIKE '$username%'";
    }
    $queryString .= ($friend == 0 && $username == "" ? " WHERE " : " AND ")
                        . "members.id != $id";
    //echo $queryString;
    $result = queryMysql($queryString);
    if($result->num_rows) {
        echo "<ul>";
        while($row = $result->fetch_assoc())
        {
            $userId = $row['id'];
            $userName = $row['user'];
            if($friend == 3) {
                $tempResult = queryMysql("SELECT * FROM friends
                    WHERE member_id = $id AND friend_id = $userId");
                if(!$tempResult->num_rows) {
                    continue;
                }
            }
            echo "<li>$userName</li>";
        }
        echo "</ul>";
    }
}