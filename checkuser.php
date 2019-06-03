<?php
require_once 'functions.php';

if(isset($_POST["username"]))
{
    $username = $connection->real_escape_string($_POST['username']);
    $result = 
        queryMysql("SELECT * from members WHERE user='$username'");
    if($result->num_rows)
    {
        echo 
            "<span class='taken'>This username is taken</span>";
    }
    else 
    {
        echo 
            "<span class='available'>This username is available</span>";
    }
}