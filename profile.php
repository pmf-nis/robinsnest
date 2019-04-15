<?php
require_once 'header.php';
if(!$loggedin)
{
    die();
}
$fname = $email = $gender = $text = "";
$lang = array();
$fnameError = $emailError = $genderError = $textErrror
    = $langError = "";

    if($_SERVER["REQUEST_METHOD"] == "GET")
    {
        $result = queryMysql
            ("SELECT * FROM profiles WHERE member_id=$id");
        if($result->num_rows)
        {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $fname = $row['fname'];
            $email = $row['email'];
            $gender = $row['gender'];
            $text = $row['text'];
        }
    }

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {        
        if(empty($_POST["fname"]))
        {
            $fnameError = "First name is required";
        }
        else 
        {
            $fname = $connection->real_escape_string($_POST["fname"]);
        }
        
        if(empty($_POST["email"]))
        {
            $emailError = "Email is required";
        }
        else {
            $email = $connection->real_escape_string($_POST["email"]);
            if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                $emailError = "Invalid email format";
            }
        }
        
        if(empty($_POST["gender"]))
        {
            $genderError = "Gender is required";
        }
        else 
        {
            $gender = $connection->real_escape_string($_POST['gender']);
        }
        
        if(empty($_POST["lang"]))
        {
            $langError = "You must select 1 or more";
        }
        else 
        {
            foreach ($_POST["lang"] as $l)
            {
                $lang[] = $connection->real_escape_string($l);
            }
        }
        
        if(empty($_POST["text"]))
        {
            $textErrror = "About you is required";
        }
        else 
        {
            $text = $connection->real_escape_string($_POST["text"]);
            $text = preg_replace("/\s\s+/", " ", $text);
        }
        
        if($fnameError == "" && $emailError == "" && $genderError == ""
           && $langError == "" && $textErrror == "")
        {
            $result = queryMysql
                ("SELECT * FROM profiles WHERE member_id = $id");
            if($result->num_rows)
            {
                queryMysql("UPDATE profiles SET fname='$fname',
                     email='$email', gender='$gender', text='$text' 
                        WHERE member_id=$id");
                //die("Ovde sam");
                $row = $result->fetch_assoc();
                $profileId = $row['id'];
                queryMysql
                    ("DELETE FROM profile_lang WHERE profile_id=$profileId");
                foreach ($lang as $l)
                {
                    queryMysql("INSERT INTO profile_lang(profile_id, lang_id)
                        VALUES($profileId, $l)");
                }
            }
            else 
            {
                queryMysql("INSERT INTO profiles(member_id, fname, email,
                    gender, text) VALUES ($id, '$fname', '$email',
                    '$gender', '$text')");
                $result1 = queryMysql
                    ("SELECT * FROM profiles WHERE member_id = $id");
                $row = $result1->fetch_assoc();
                $profileId = $row['id'];
                foreach ($lang as $l)
                {
                    queryMysql("INSERT INTO profile_lang(profile_id, lang_id)
                        VALUES($profileId, $l)");
                }
            }
        }
            
    }
    
    if(isset($_FILES['image']['name']))
    {
        if(!file_exists('images/'))
        {
            mkdir('images/');
        }
        $saveto = "images/$id.jpg";
        move_uploaded_file($_FILES['image']['tmp_name'], $saveto);
        $typeok = true;
        
        switch($_FILES['image']['type'])
        {
            case "image/gif":
                $src = imagecreatefromgif($saveto);
                break;
            case "image/jpeg":
            case "image/pjpeg":
                $src = imagecreatefromjpeg($saveto);
                break;
            case "image/png":
                $src = imagecreatefrompng($saveto);
                break;
            default:
                $typeok = false;
                break;
        }
        if($typeok)
        {
            list($w, $h) = getimagesize($saveto);
            $max = 100;
            $tw = $w;
            $th = $h;
            if($w > $h) 
            {
                if($w > $max) {
                    $tw = $max;
                    $th = $max / $w * $h;
                }
                else 
                {
                    $tw = $th = $max;
                }
            }
            else
            {
                if($h > $max) {
                    $th = $max;
                    $tw = $max / $h * $w;
                }
                else
                {
                    $tw = $th = $max;
                }
            }
            
            $tmp = imagecreatetruecolor($tw, $th);
            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
            imageconvolution($tmp, array(array(-1, -1, -1),
                    array(-1, 16, -1), array(-1, -1, -1)
                ), 8, 0);
            imagejpeg($tmp, $saveto);
            imagedestroy($tmp);
            imagedestroy($src);
        }
        
    }
    showProfile($id, $user);
?>

<div class='main'>
	<h3>Your profile</h3>
	
	<form method='post' action='profile.php' 
		enctype='multipart/form-data'>
		<h3>Enter or edit your details and/or upload image</h3>
		
		<label for="fname">First name:</label>
		<input type="text" name="fname" id="fname" value="<?php echo $fname?>">
		<span class="error">
			* <?php echo $fnameError ?>
		</span>
		<br><br>
		
		<label for="email">Email:</label>
		<input type="text" name="email" id="email" value="<?php echo $email?>">
		<span class="error">
			* <?php echo $emailError ?>
		</span>
		<br><br>
		
		<label for="gender">Gender:</label>
		<input type="radio" name="gender" value="female" 
			class="radio" <?php echo (isset($gender) && $gender == "female") ? "checked" : "" ?>> Female &nbsp;
		<input type="radio" name="gender" value="male"
			class="radio" <?php echo (isset($gender) && $gender == "male") ? "checked" : "" ?>> Male
		<span class="error">
			* <?php echo $genderError ?>
		</span>
		<br><br>
		
		<label for="lang">Favorite programming language:</label>
		<select id="lang" name="lang[]" multiple="" size="4">
		<?php
		  //$options = array("php", "c", "c++", "java", "python");
		  $result = queryMysql("SELECT * FROM languages");
		  while($row = $result->fetch_assoc())
		  {
		      echo "<option value='" . $row['id'] . "'" .
		        (in_array($row['id'], $lang) ? "selected" : "") .">" .
		      ucfirst($row['language']) .
		      "</option>";
		  }
		?>
		</select>
		<span class="error">
			* <?php echo $langError ?>
		</span>
		<br><br>
		
		<label for="text">About you:</label>
		<textarea rows="3" cols="50" name="text" id="text">
			<?php echo $text ?>
		</textarea>
		<span class="error">
			* <?php echo $textErrror ?>
		</span>
		<br><br>
		
		<label for="image">Image:</label>
		<input type="file" id="image" name="image">
		<br><br>
		
		<input type="submit" value="Save Profile">
	</form>
</div>