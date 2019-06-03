<html>
	<head>
		<title>Setting up...</title>
	</head>
	
	<body>
	<?php
	  require_once 'functions.php';
	  
	  require_once 'Role.php';
	  require_once 'PrivilegedMember.php';
	  
	  $ok = false;
	  session_start();
	  if(isset($_SESSION['user'])) {
	      $member = PrivilegedMember::getByUsername($_SESSION['user']);
	      if($member && $member->hasPrivilege("Run SQL")) {
	          $ok = true;
	      }
	  }
	  
	  if($ok) {
		  createTable("members", 
		      "id INT UNSIGNED AUTO_INCREMENT,
                user VARCHAR(16) NOT NULL,
                pass VARCHAR(100) NOT NULL,
                PRIMARY KEY(id),
                INDEX(user(6))");
		  
		  createTable("profiles", 
		      "id INT UNSIGNED AUTO_INCREMENT,
                member_id INT UNSIGNED NOT NULL UNIQUE,
                fname VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                gender VARCHAR(6) NOT NULL,
                text VARCHAR(4096),
                PRIMARY KEY(id),
                FOREIGN KEY(member_id) REFERENCES members(id)
                    ON UPDATE CASCADE ON DELETE NO ACTION
                ");
		  
		  createTable("languages", 
		      "id INT UNSIGNED AUTO_INCREMENT,
                language VARCHAR(255) NOT NULL,
                PRIMARY KEY(id)");
		  
		  createTable("profile_lang", 
		      "id INT UNSIGNED AUTO_INCREMENT,
                profile_id INT UNSIGNED NOT NULL,
                lang_id INT UNSIGNED NOT NULL,
                PRIMARY KEY(id),
                FOREIGN KEY(profile_id) REFERENCES profiles(id)
                    ON UPDATE CASCADE ON DELETE NO ACTION,
                FOREIGN KEY(lang_id) REFERENCES languages(id)
                    ON UPDATE CASCADE ON DELETE NO ACTION
                ");
		  
		  createTable("friends", 
		      "id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                member_id INT UNSIGNED NOT NULL,
                friend_id INT UNSIGNED NOT NULL,
                PRIMARY KEY(id),
                FOREIGN KEY(member_id) REFERENCES members(id)
                    ON UPDATE CASCADE ON DELETE NO ACTION,
                FOREIGN KEY(friend_id) REFERENCES members(id)
                    ON UPDATE CASCADE ON DELETE NO ACTION
                ");
		  
		  createTable("roles", 
		      "id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                role_name VARCHAR(50) NOT NULL,
                PRIMARY KEY(id)");
		  
		  createTable("permissions", 
		      "id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                perm_name VARCHAR(50) NOT NULL,
                PRIMARY KEY(id)");
		  
		  createTable("role_perm", 
		      "id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                role_id INT UNSIGNED NOT NULL,
                perm_id INT UNSIGNED NOT NULL,
                PRIMARY KEY(id),
                FOREIGN KEY(role_id) REFERENCES roles(id)
                    ON UPDATE CASCADE ON DELETE NO ACTION,
                FOREIGN KEY(perm_id) REFERENCES permissions(id)
                    ON UPDATE CASCADE ON DELETE NO ACTION
                ");
		  
		  createTable("member_role",
		      "id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                member_id INT UNSIGNED NOT NULL,
                role_id INT UNSIGNED NOT NULL,
                PRIMARY KEY(id),
                FOREIGN KEY(member_id) REFERENCES members(id)
                    ON UPDATE CASCADE ON DELETE NO ACTION,
                FOREIGN KEY(role_id) REFERENCES roles(id)
                    ON UPDATE CASCADE ON DELETE NO ACTION
                ");
		?>
		<br> ... done.
		<?php 
	  }
	  else {
	      echo "You are not authorized to run this page!";
	  }
	  
		?>
	</body>
</html>