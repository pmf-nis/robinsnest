<?php
require_once 'header.php';

if(!$loggedin) die();

?>

	<div class='main'>
		<form action="">
			<span class="fieldname">Username: </span>
			<input type="text" id="name" name="name" value="">
			<br><br>
			<span class="fieldname">Friendship: </span>
			<input type="radio" name="friend" id="none" 
				value="0" checked> All
			<input type="radio" name="friend" id="following" 
				value="1"> Following
			<input type="radio" name="friend" id="follower"
				value="2"> Follower
			<input type="radio" name="friend" id="mutual"
				value="3">Mutual<br>
			<input type="hidden" name="id" id="id" value="<?php echo $id?>">
		</form>
		<div id="result">
		</div>
		<script type="text/javascript">
			var nameInput = document.getElementById('name');
			var radioButtons = document.getElementsByName('friend');
			var idInput = document.getElementById('id'); 
			
			function search() {
				var nameValue = nameInput.value;
				var radioValue = 0;
				var idValue = idInput.value;
				
				for(var i = 0; i < radioButtons.length; i++) {
					if(radioButtons[i].checked) {
						radioValue = parseInt(radioButtons[i].value);
					}
				}
				var request = ajaxRequest();
				request.open("POST", "searchbycriteria.php", true);
				request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				request.onreadystatechange = function() {
					if(this.readyState == 4 && this.status == 200)
					{
						if(this.responseText != null)
						{
							document.getElementById("result").innerHTML 
								= this.responseText;
						}
					}
				};
				request.send("username=" + nameValue 
						+ "&friend=" + radioValue 
						+ "&id=" + idValue);
			}

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
			
			nameInput.addEventListener('keyup', search);
			for(var i = 0; i < radioButtons.length; i++) {
				radioButtons[i].addEventListener('click', search);
			}
		</script>
		
	</div>
</body>
</html>