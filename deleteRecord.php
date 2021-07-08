<?php
    if (session_status() === PHP_SESSION_NONE)
        session_start();
    try{
        $mysqli = new mysqli($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"]);
    }
    catch(Exception $e){
    }
    $mysqli->select_db($_SESSION["dbname"]);
    echo $_GET["email"];
    $email = $_GET["email"];
    $deleteQuery = "DELETE FROM " . $_SESSION['tbname'] . " WHERE email = '$email'";  
    $mysqli->query($deleteQuery);
    $mysqli->close();
    header("Location: viewData.php");
    exit();
?>