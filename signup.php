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
			<input type="text" name="user" value="" id="user">
			<span id="info"></span>
			<br>
			<span class="fieldname">Password:</span>
			<input type="password" name="pass" value="">
			<br>
			<input type="submit" value="Sign up">
		</form>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
		<script type="text/javascript">
			/*
			var userInput = document.getElementById("user");
			userInput.addEventListener("blur", function() {
				var username = userInput.value;

				if(username == "")
				{
					document.getElementById("info").innerHTML = "";
					return;
				}
				
				var request = ajaxRequest();
				request.open("POST", "checkuser.php", true);
				request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				request.onreadystatechange = function() {
					if(this.readyState == 4 && this.status == 200)
					{
						if(this.responseText != null)
						{
							document.getElementById("info").innerHTML = this.responseText;
						}
					}
				};
				request.send("username=" + username);
			});
			*/

			$("#user").blur(function() {
				var username = $("#user").val();
				if(username == "") {
					$("#info").html("");
					return;
				}
				$.ajax({
					method : "POST",
					url : "checkuser.php",
					data : {
						'username' : username
					},
					success : function(result) {
						$("#info").html(result);
					}
				});
			});

			function ajaxRequest() 
			{
				try {
					var request = new XMLHttpRequest(); // novi browseri
				}
				catch(e1) {
					try {
						request = new ActiveXObject("Maxm12.XMLHTTP"); // IE6
					}
					catch(e2) {
						try {
							request = new ActiveXObject("Microsoft.XMLHTTP"); // IE...
						}
						catch(e3) {
							request = false;
						}	
					}				
				}
				return request;
			}
				
		</script>
	</body>
</html>