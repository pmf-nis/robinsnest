<?php

require_once 'header.php';
if(!$loggedin) die();

echo "<a href='viewmes.php?recip_id=$id'><h3>View my messages</h3></a>";

echo "<h3>View other members' messages</h3>";
$result = queryMysql("SELECT * FROM members ORDER BY user");
echo "<ul>";
while($row = $result->fetch_assoc()) 
{
    if($row['id'] == $id) continue;
    echo "<li><a href='viewmes.php?recip_id=". $row['id'] . 
        "'>Talk to " . $row['user'] . " and see his public messages</a></li>";
}
echo "</ul>";
?>

</div>
</body>
</html>