<?php
	if (session_status() === PHP_SESSION_NONE)
		session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF8" name="viewport" content="width=device-width, initial-scale=1.0">
	<link type="text/css" rel="stylesheet" href="css\style.css" >
	<script type="text/javascript" src="scripts\scripts.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
	<title>Welcome</title>

</head>	

	<body onload="checkFormAndJs(); <?php if(isset($_SESSION['success']) && $_SESSION['jsEnabled'] == true) echo 'successMessageOutput()'?>">
	<p style="display: none;">
		<?php
			require "data\conn.txt";
			
			$dataFile = fopen("data\conn.txt", "r");
			$_SESSION["servername"] = trim(fgets($dataFile));
			$_SESSION["username"] = trim(fgets($dataFile));
			$_SESSION["password"] = trim(fgets($dataFile));
			$_SESSION["dbname"] = "emailDatabase";
			fclose($dataFile);
			try{
				$mysqli = new mysqli($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"]);
			}
			catch(Exception $e){
				echo $e->getMessage();
			}
 
			if(isset($_COOKIE["verification"]))
				$_SESSION["jsEnabled"] = true;
			else
				$_SESSION["jsEnabled"] = false; 
			$createQuery = "CREATE DATABASE IF NOT EXISTS " . $_SESSION["dbname"];
			$mysqli->query($createQuery);
			$mysqli->select_db($_SESSION["dbname"]);
			$_SESSION["tbname"] = "tbEmails";
			$createTableQuery = "CREATE TABLE IF NOT EXISTS " . $_SESSION["tbname"] . 
				"( id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY, email VARCHAR(255) NOT NULL, date DATETIME DEFAULT CURRENT_TIMESTAMP)";
			$mysqli->query($createTableQuery);
			
	?> 
	</p>
		<div class="container">
			<div class="topbar">
				<div class="logobox">
					<img class="logo" src="src\img\Union.png">
					<img class="logotext" src="src\img\pineapple..png">
				</div>
				<div class="navpane">
					<a class="textref" href="viewData.php">About</a>
					<a class="textref" href="#">How it works</a>
					<a class="textref" href="#">Contact</a>
				</div>
			</div>
			<div class="content">
				<div class="content-text">
					<?php 
						if($_SESSION["jsEnabled"] == true):
					?>
						<img id="success" class="success" src="src\img\trophy.png">
					<?php 
						elseif(isset($_SESSION["success"])):
					?>
						<img id="success" style="display:block !important" class="success" src="src\img\trophy.png">
					<?php
						endif;
					?>
					<h1 id="heading">
					<?php 
						if(isset($_SESSION["success"]) && $_SESSION["jsEnabled"] == false):
					?>
						Thanks for subscribing!
					<?php
						else:
					?>
						Subscribe to newsletter
					<?php 
						endif;
					?>
					</h1>
					<p id="text">
					<?php 
						if(isset($_SESSION["success"]) && $_SESSION["jsEnabled"] == false):
					?>		
						You have successfully subscribed to our 
						email listing. Check your email for the discount code.
					<?php 
						else:
					?>
						Subscribe to our newsletter 
						and get 10% discount on pineapple glasses.
					<?php
						endif;
					?>
					</p>
				</div>
				<?php
					if($_SESSION["jsEnabled"] == true || $_SESSION["success"] == false):
				?>
				<form <?php if($_SESSION["jsEnabled"]== true) echo 'onsubmit=\'return false;\'';?> action="addEmail.php" method="POST" id="form" class="mainform">
					<input name="email" id="input" class="textfield" type="text" placeholder="Type your email address here..." autocomplete="off">
					<button id="button" class="submit-button" 
						<?php if($_SESSION["jsEnabled"]== true) echo 'onclick="validateInput()"'; else echo 'type="submit"';?>></button>
					<p id="error-message" class="error-message"> 	
					</p>
					<?php 
						if(isset($_SESSION["error"])):
					?>
						<p style="display:inline !important" class="error-message">	
							<?php echo $_SESSION["error"];?>
						</p>
					<?php
							unset($_SESSION["error"]);
						endif;
					?>		
					<input type="checkbox" class="custom-checkbox" id="terms" name="terms" value="accepted">
					<label for="terms" class="custom-checkbox-label">	
						<p>I agree to <a href="#">terms of service</a></p>
					</label>
				</form>
				<?php
					endif;
				?>
				<hr class="line">
				<div class="social-media">
					<a href="#" class="circle facebook"></a>
					<a href="#" class="circle instagram"></a>
					<a href="#" class="circle twitter"></a>
					<a href="#" class="circle youtube"></a>
				</div>
			</div>
		</div>
	</body>
	<?php
		unset($_SESSION["success"]);
		$mysqli->close();
	?>
</html>