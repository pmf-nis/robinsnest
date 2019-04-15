<?php
    require_once 'header.php';  
    
    echo "<div class='main'>
            <h3>Please enter your details to log in.</h3>";
    $user = $pass = $error = "";
    global $connection;
    if(isset($_POST["user"]))
    {
        $user = $connection->real_escape_string($_POST['user']);
        $pass = $connection->real_escape_string($_POST['pass']);
        if($user == "" || $pass == "")
        {
            $error = "Not all fields were entered.<br>";
        }
        else 
        {
            $result =  
                queryMysql("SELECT * FROM members WHERE user='$user'");
            if($result->num_rows == 0)
            {
                $error = "Username invalid";
            }
            else 
            {
                // $hpass = hash('ripemd128', $pass);
                $row = $result->fetch_assoc();
                if(password_verify($pass, $row['pass']))
                // if($row['pass'] == $hpass) 
                {
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['user'] = $row['user'];
                    
                    setcookie('current_login_time', date("d.m.Y. H:i:s"),
                        time() + 60 * 60 * 24 * 30 * 12, '/');
                    
                    die("You are now logged in. Please 
                        <a href='index.php'>click here</a> to continue.
                        <br><br></div></body></html>");
                }
            }
        }
    }
?>
		<form action="login.php" method="post">
			<span class="error"><?php echo $error; ?></span>
			<span class="fieldname">Username: </span>
			<input type="text" name="user" value="">
			<br>
			<span class="fieldname">Password:</span>
			<input type="password" name="pass" value="">
			<br>
			<input type="submit" value="Log in">
		</form>
		</div>
	</body>
</html>