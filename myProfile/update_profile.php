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
    
    // Check if the form is submitted for profile update
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $fullname = $_POST['fullname'];
        $phonenumber = $_POST['phonenumber'];
        $newPassword = $_POST['newPassword'];
        $visibility = $_POST['visibility'];
        $level = $_POST['level'];
        $oldPassword = $_POST['oldPassword'];

        // Retrieve the logged-in user's username from the session
        $username = $_SESSION['user'];

        // Check if the old password matches the password hash in the database
        $checkPasswordQuery = "SELECT `password` FROM `user` WHERE `username` = '$username'";
        $checkPasswordResult = $conn->query($checkPasswordQuery);

        if ($checkPasswordResult->num_rows > 0) {
            $userData = $checkPasswordResult->fetch_assoc();
            $hashedPasswordFromDB = $userData['password'];
            
            // Compare the hashed old password with the hashed password from the database
            if (password_verify($oldPassword, $hashedPasswordFromDB)) {
                // Old password matches, proceed with the update
                $updateQuery = "UPDATE `user` SET `fullname`='$fullname', `phonenumber`='$phonenumber', `visibility`='$visibility', `level`='$level'";
                
                // Check if a new password is provided
                if (!empty($newPassword)) {
                    // Hash the new password
                    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $updateQuery .= ", `password`='$hashedNewPassword'";
                }
                
                $updateQuery .= " WHERE `username`='$username'";

                // Perform the update
                if ($conn->query($updateQuery) === TRUE) {
                    header("Location: myProfile.php");
                    exit;
                } else {
                    echo "Error updating profile: " . $conn->error;
                }
            } else {
                // Old password does not match
                echo "Old password does not match";

            }
        } else {
            // User not found in the database
            echo "User not found";
        }
    }

    // Fetch user details from the database
    $username = $_SESSION['user'];
    $getUserDetailsQuery = "SELECT * FROM `user` WHERE `username` = '$username'";
    $getUserDetailsResult = $conn->query($getUserDetailsQuery);

    if ($getUserDetailsResult->num_rows > 0) {
        $userDetails = $getUserDetailsResult->fetch_assoc();
    } else {
        echo "Error fetching user details: " . $conn->error;
    }
?>
