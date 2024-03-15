<?php
session_start();

$host = "localhost";
$user = "galb_StockMate_User";
$pass = "StockMate";
$db = "galb_StockMate_db";

// create connection
$conn = new mysqli($host, $user, $pass, $db);

// check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $stockId = $_POST['stock_id'];
    $portfolioName = $_POST['portfolio_name'];
    $username = $_SESSION['user'];

    // Delete the stock from the specific portfolio for the logged-in user
    $deleteQuery = "DELETE FROM `portfolios` WHERE `username`='$username' AND `name`='$portfolioName' AND `id`='$stockId'";
    $deleteResult = $conn->query($deleteQuery);

    if ($deleteResult) {
    header("Location: myProfile.php");
    exit; // Make sure to exit after redirection
        
    } else {
        echo "Error deleting stock: " . $conn->error;
    }
}

$conn->close();
?>
