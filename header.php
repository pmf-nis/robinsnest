<?php
session_start();

require_once 'functions.php';

$userstr = " (Guest)";
$loggedin = false;

if(isset($_SESSION['user']))
{
    $id = $_SESSION['id'];
    $user = $_SESSION['user'];
    $userstr = " ($user)";
    $loggedin = true;
}

?>

<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $appname . $userstr?></title>
		<link rel="stylesheet" href="styles.css" type="text/css">
	</head>
	
	<body>
		<center>
			<canvas id="logo" width="624" height="96">
				Robin's Nest</canvas>
		</center>
		<div class="appname">Robin's Nest</div>
		<script src="javascript.js"></script>
		
		<?php
		      if(!$loggedin)
		      {
		?>
			<ul class="menu">
				<li>
					<a href="index.php">Home</a>
				</li>
				<li>
					<a href="signup.php">Sign up</a>
				</li>
				<li>
					<a href="login.php">Log in</a>
				</li>
			</ul>
			<br>
			<span class="info">
				&#8658; You must be logged in to access this site.
			</span>
			<br><br>
		<?php     
		      }
		      else 
		      {
		          if(isset($_COOKIE['last_login_time']))
		          {
		              echo "Welcome, $user! Your last login time: ";
		              echo $_COOKIE['last_login_time'];
		          }
		          
		?>
			<ul class='menu'>
				<li>
					<a href="members.php?id=<?php echo $id?>">Home</a>
				</li>
				<li>
					<a href="members.php">Members</a>
				</li>
				<li>
					<a href="friends.php">Friends</a>
				</li>
				<li>
					<a href="messages.php">Messages</a>
				</li>
				<li>
					<a href="profile.php">Profile</a>
				</li>
				<li>
					<a href="logout.php">Log out</a>
				</li>
			</ul>
		<?php 
		      }
		?>
		