<?php
require_once 'header.php';

if(isset($_SESSION['id']))
{
    destroySession();
    echo "<div class='main'>You have been logged out.
Please <a href='index.php'>click here</a> to refresh the
screen.</div>";
}
else 
{
    echo "<div class='main'>You cannot logout because
you are not logged in</div>";
}
?>
</body>
</html>