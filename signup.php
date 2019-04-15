<?php
    require_once 'functions.php';
    /*
     * require - prekida u sluc. greske
     * include - nastavlja u sluc. greske
     * require_once - i jos provera da li je fajl vec ukljucen
     * include_once - isto
     */
    
    $error = $user = $pass = "";
    if(isset($_POST["user"]))
    {
        $user = $connection->real_escape_string($_POST["user"]);
        $pass = $connection->real_escape_string($_POST["pass"]);
        if($user == "" || $pass == "")
        {
            $error = "Not all fields were entered.<br>";
        }
        else 
        {
            $result = queryMysql("SELECT * FROM members WHERE user='$user'");
            if($result->num_rows)
            {
                $error = "That username is taken.<br>";
            }
            else 
            {
                $hpass = password_hash($pass, PASSWORD_BCRYPT); // >= PHP 5.5
                // $hpass = hash("ripemd128", $pass); // < PHP 5.5
                queryMysql("INSERT INTO members(user,pass) 
                    VALUES('$user', '$hpass')");
                //die("Account created, please login");
                header('Location: index.php');
            }
        }
    }
    require_once 'header.php';
?>
		<form action="signup.php" method="post">
			<span class="error"><?php echo $error; ?></span>
			<span class="fieldname">Username: </span>
			<input type="text" name="user" value="">
			<br>
			<span class="fieldname">Password:</span>
			<input type="password" name="pass" value="">
			<br>
			<input type="submit" value="Sign up">
		</form>
	</body>
</html>