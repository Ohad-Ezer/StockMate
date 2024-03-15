<?php
session_start();

$host = "localhost";
$user = "galb_StockMate_User";
$pass = "StockMate";
$db = "galb_StockMate_db";

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted for portfolio deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['portfolio_name'])) {
    // Retrieve the logged-in user's username from the session
    $username = $_SESSION['user'];
    
    // Retrieve the portfolio name from the form
    $portfolioName = $_POST['portfolio_name'];

    // Delete all rows in the portfolios table with the name of the specific portfolio related to the logged-in user
    $deleteQuery = "DELETE FROM `portfolios` WHERE `username` = '$username' AND `name` = '$portfolioName'";
    $deleteResult = $conn->query($deleteQuery);

    if ($deleteResult) {
        header("Location: myProfile.php");
        exit; // Make sure to exit after redirection
        
    } else {
        echo "Error deleting portfolio: " . $conn->error;
    }
} else {
    echo "Portfolio name not provided";
}
?>