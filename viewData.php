
<?php
	if (session_status() === PHP_SESSION_NONE)
		session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF8" name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Data</title>
    <style>
        td, th, tr{
            border: 1px solid black;
        }
    </style>
</head>	
<body>
    <?php
        try{
            $mysqli = new mysqli($_SESSION["servername"], $_SESSION["username"], $_SESSION["password"]);
        }
        catch(Exception $e){
        }
        $mysqli->select_db($_SESSION["dbname"]);
        if(isset($_GET["order"])){
            $_SESSION["order"] = $_GET["order"];
        }
        if(isset($_GET["provider"])){
            if(isset($_SESSION["provider"])){
                if($_SESSION["provider"] == $_GET["provider"])
                    unset($_SESSION["provider"]);
                else
                    $_SESSION["provider"] = $_GET["provider"];
            }
            else
                $_SESSION["provider"] = $_GET["provider"];
        }
        if(isset($_GET["filter"]))
            if(trim($_GET["filter"]) != "")
                $_SESSION["filter"] = $_GET["filter"];
            else
                unset($_SESSION["filter"]);
        if(isset($_SESSION["provider"])){
            $provider = $_SESSION["provider"];
            $selectQuery = "SELECT email, date FROM " . $_SESSION["tbname"] . " WHERE email LIKE '%$provider'";
            if(isset($_SESSION["filter"])){
                $filter = $_SESSION["filter"];
                $selectQuery .= " AND email LIKE '$filter'";
            }
        }
        else {
            $selectQuery = "SELECT email, date FROM " . $_SESSION["tbname"];
            if(isset($_SESSION["filter"])){
                $filter = $_SESSION["filter"];
                $selectQuery .= " WHERE email LIKE '$filter'";
            }
        }

        if(isset($_SESSION["order"])){
            if($_SESSION["order"] == "email"){
                $selectQuery .= " ORDER BY email DESC";
            }
            else{
                $selectQuery .=  " ORDER BY date DESC";
            }
        }
        else
            $selectQuery .=  " ORDER BY date ASC";
        $selectQuery .= " LIMIT 10";
        if(isset($_GET["page"]))
            $page = $_GET["page"];
        else
            $page = 1;
        $offsetValue = ($page - 1) * 10;
        $selectQuery .= " OFFSET $offsetValue";
        
    ?>
    <table style="border: 2px solid black; margin: auto; width: 450px;">
        <thead>
            <?php echo $selectQuery;?>
            <th scope="col"><a href = "viewData.php?order=email">Email <br></a>
            <?php
                $selectEmailsQuery = "SELECT DISTINCT email FROM ". $_SESSION["tbname"];
                $resultEmails = $mysqli->query($selectEmailsQuery);
                $emails = array();
                while($row = $resultEmails->fetch_assoc()){
                    $provider = explode("@", $row["email"]);
                    array_push($emails, $provider[1]);
                }
                $emails = array_unique($emails);
                foreach($emails as $provider):
            ?>
                <a href="viewData.php?provider=<?php echo $provider;?>"> <?php echo $provider ?><br></a>
            <?php
                endforeach;
            ?>
            </th>
            <th scope="col"><a href = "viewData.php?order=date">Date</a></th>
            <th scope="col">Delete</th>
        </thead>
        <tbody>
        <?php
            $result = $mysqli->query($selectQuery);
            while($record = $result->fetch_assoc()):
        ?>
            <tr>
                <td> 
                    <?php echo $record["email"];?>
                </td>
                <td>
                    <?php echo $record["date"];?>
                </td>
                <td>
                    <a href="deleteRecord.php?email=<?php echo $record["email"];?>">Delete</a>
                </td>
            </tr>
            
        <?php
            endwhile;
        ?>
        </tbody>
        <?php 
            $mysqli->close();
        ?>
    </table> 
    <form action="viewData.php" method="GET" style="text-align:center">
        <input name="filter" nametype="text">
        <input type="submit">
    </form>
    <div style="text-align:center">
        <?php if($page > 1):?>
            <a href="viewData.php?page=<?php echo $page - 1;?>">Previous page </a>
        <?php endif; ?>
        <?php echo "Current page - " . $page;?>
        <a href="viewData.php?page=<?php echo $page + 1;?>">Next page </a>
    </div>
</body>