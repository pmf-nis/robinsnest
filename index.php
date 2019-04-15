<?php 

    require_once 'header.php';
    echo "<br><span class='main'>Welcome to $appname,";
    if(!$loggedin)
    {
        echo " please sign up and/or log in to continue.";
    }
    echo "</span><br><br>";
?>

	</body>
</html>