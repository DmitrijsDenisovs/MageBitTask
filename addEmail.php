<?php 
    ob_start();
    if (session_status() === PHP_SESSION_NONE)
        session_start();	 
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $inputEmail = htmlspecialchars(stripslashes(trim($_POST["email"])));
        if($_SESSION["jsEnabled"] == false){
            if($_POST["terms"] == false){
                $_SESSION["error"] = "You must accept the terms and conditions";
            }
            else{
                if($inputEmail == "" || $inputEmail == null){
                    $_SESSION["error"] = "Email address is required";
                }
                else{ 
                    if(!filter_var($inputEmail, FILTER_VALIDATE_EMAIL)){
                        $_SESSION["error"] = "Please provide a valid e-mail address";
                    }
                    else{
                        if(str_ends_with($inputEmail, ".co")){
                            $_SESSION["error"] = "We are not accepting subscriptions from Colombia emails";
                        }
                    }
                }
            }
        }
        if(!isset($_SESSION["error"])){
			try{
				$mysqli = new mysqli($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"]);
			}
			catch(Exception $e){
			}
            $mysqli->select_db($_SESSION["dbname"]);
            $checkQuery = "SELECT email FROM " . $_SESSION["tbname"] . " WHERE email = '$inputEmail'";
            $queryResult = $mysqli->query($checkQuery);
            echo $queryResult->num_rows;
            if($queryResult->num_rows == 0){
                $insertQuery = "INSERT INTO " . $_SESSION["tbname"] . " (email) VALUES ('$inputEmail')";
                $mysqli->query($insertQuery);
                $_SESSION["success"] = true;
            }
            else{
                $_SESSION["error"] = "Email has already been registered";
            }
        }
    $mysqli->close();
    header("Location: index.php");
    exit();
    }
?>