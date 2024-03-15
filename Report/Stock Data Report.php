<?php
session_start(); // Start the session to access session variables

$host = "localhost";
$user = "galb_StockMate_User";
$pass = "StockMate";
$db = "galb_StockMate_db";

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    // Get form data
    $startDate = $_GET["startDate"];
    $endDate = $_GET["endDate"];
    $stockSymbol = $_GET["stockSymbol"];
    $note = $_GET["note"];
    $action = $_GET["action"]; // Retrieve the action parameter
    $username = $_SESSION['user'];

    // Get the current date and time
    $currentDateTime = date("Y-m-d H:i:s");

    // Echo the values for debugging
    echo "Start Date: " . $startDate . "<br>";
    echo "End Date: " . $endDate . "<br>";
    echo "Stock Symbol: " . $stockSymbol . "<br>";
    echo "Note: " . $note . "<br>";
    echo "Current Date and Time: " . $currentDateTime . "<br>";
    
    if ($action === "send") {
        $friendName = $_GET["friendName"]; // Only retrieve friendName if action is "send"
        echo "Friend Name: " . $friendName . "<br>";
    } else {
        // Set $friendName to empty if action is not "send"
        $friendName = "";
    }

    // Insert data into the database
    $save = "INSERT INTO `reports` (`username`, `start_date`, `end_date`, `stock_symbol`, `notes`, `friend_name`, `current_date_time`) VALUES ('$username', '$startDate', '$endDate', '$stockSymbol', '$note', '$friendName', '$currentDateTime')";
    $query = mysqli_query($conn, $save);
    

    if ($query) {
        echo "Data inserted successfully.";
        // Redirect back to the HTML file
               echo '<script>window.location.href = "First Stock Data Report.php";</script>';
        exit(); // Ensure script stops executing after redirection
    } else {
               echo '<script>window.location.href = "First Stock Data Report.php";</script>';
        exit(); // Ensure script stops executing after redirection
    }
}
?>
