<?php
require_once 'header.php';
if(!$loggedin) die();

if(isset($_GET['recip_id'])) {
    $recipId = $connection->real_escape_string($_GET['recip_id']);
}
/*else {
    $recipId = $id;
}*/

if(isset($_POST['text'])) {
    $text = $connection->real_escape_string($_POST['text']);
    $pm = substr($connection->real_escape_string($_POST['pm']), 0, 1);
    $time = time();
    if($text != "") {
        queryMysql("INSERT INTO messages VALUES(NULL, $id, $recipId, '$pm', $time, '$text')");
    }
}

?>

<form method="post" action="viewmes.php?recip_id=<?php echo $recipId ?>">
	Type here to type a message: <br>
	<textarea rows="3" cols="40" name="text"></textarea> <br>
	<input type="radio" name="pm" value="0" checked> Public <br>
	<input type="radio" name="pm" value="1"> Private <br>
	<input type="submit" value="Post Message">
</form>

<?php
if(isset($_GET['erase'])) {
    $erase = $connection->real_escape_string($_GET['erase']);
    queryMysql("DELETE FROM messages WHERE id=$erase AND recip_id=$id");
}

$result = queryMysql("SELECT * FROM messages WHERE recip_id = $recipId ORDER BY time DESC");
$num = $result->num_rows;

while($row = $result->fetch_assoc()) {
    if($row['pm'] == 0 || $row['auth_id'] == $id || $row['recip_id'] == $id) {
        echo date("M jS \'y g:ia:", $row['time']);
        $result1 = queryMysql("SELECT * FROM members WHERE id = " . $row['auth_id']);
        $row1 = $result1->fetch_assoc();
        echo "<a href='viewmes.php?recip_id=" . $row['auth_id'] . "'>"
            . $row1['user'] . "</a> ";
        if($row['pm'] == 0) {
            echo "wrote: &quot;" . $row['message'] . "&quot;";    
        }
        else {
            echo "whispered: <span class='whisper'>&quot;" . $row['message'] . "&quot;</span>";
        }
        if($row['recip_id'] == $id) {
            echo " [<a href='viewmes.php?recip_id=$recipId&erase=" . $row['id'] . "'>erase</a>]";
        }
        echo "<br>";
    }
}

if(!$num) {
    echo "<br><span class='info'>No messages yet.</span><br><br>";
}

echo "<br><a class='button' href='viewmes.php?recip_id=$recipId'>Refresh messages</a>";
?>

</div>
</body>
</html>

