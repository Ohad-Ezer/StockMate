<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the session is not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $host = "localhost";
    $user = "galb_StockMate_User";
    $pass = "StockMate";
    $db = "galb_StockMate_db";

    $conn = new mysqli($host, $user, $pass, $db);

    // check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        // Get the follower (current session user)
        $followed = $_POST['followedUsername'];

        // Get the following username from the form submission
        $followingUsername = $_SESSION['user'];
        

        // Get the current date
        $followDate = date("Y-m-d H:i:s");

        // Insert into the follow table
        $sql = "DELETE FROM follow WHERE followed = '$followed' AND following = '$followingUsername'";
        $result = $conn->query($sql);

        // Close the database connection
        $conn->close();

        // Redirect back to the original page (you can customize the URL)
        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit();
    }
} else {
    // Handle other types of requests if needed
    echo "Invalid request";
}
?>