<?php
$host = "localhost";
$user = "galb_StockMate_User";
$pass = "StockMate";
$db = "galb_StockMate_db";

// create connection
$conn = new mysqli($host, $user, $pass, $db);

//check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//get info from html
$username = $_POST['username'];
$get_included = $_POST['get_included'];

$interests_results = $_POST['interests_results'];
$chk = "";
foreach ($interests_results as $chk1) {
    $chk .= $chk1 . ",";
}

$sql = "insert into user_interests (username, interests_list ,allow_get_included) values ('$username', '$chk','$get_included')";

//echo $sql;
if ($conn->query($sql) == FALSE) {
    //echo "Can not add new user.  Error is: " . $conn->error;
    exit();
}

?>