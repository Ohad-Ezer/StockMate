<?php
    $host = "localhost";
    $user = "galb_StockMate_User";
    $pass = "StockMate";
    $db = "galb_StockMate_db";

    // create connection
    $conn = new mysqli($host, $user, $pass, $db);

    // check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        $fullname = $_POST['fullname'];
        $username = $_POST['username'];
        $phonenumber = $_POST['phonenumber'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];
        $visibility = $_POST['visibility'];
        $level = $_POST['level'];

        $check_username = "SELECT * FROM `user` WHERE `username` = '$username'";
        $check_email = "SELECT * FROM `user` WHERE `email` = '$email'";
        $result_username = $conn->query($check_username);
        $result_email = $conn->query($check_email);

        if ($result_username->num_rows > 0) {
            echo '<script type="text/javascript">window.location.href = "username_fail.html";</script>';
            exit;
        }
        
        elseif ($result_email->num_rows > 0) {
            echo '<script type="text/javascript">window.location.href = "email_fail.html";</script>';
            exit;
        }

        else {
            // Use prepared statement to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO `user`(`fullname`, `username`, `phonenumber`, `email`, `password`, `confirmPassword`, `visibility`, `level`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            
            // Hash the password before storing it
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Bind parameters
            $stmt->bind_param("ssssssss", $fullname, $username, $phonenumber, $email, $hashedPassword, $hashedPassword, $visibility, $level);
            
            // Execute the statement
            if ($stmt->execute()) {
                header("Location: thankyou.html");
                exit;
            } else {
                if ($conn->errno === 1062) {
                    // Check if the error code is for duplicate entry (email already taken)
                    die("Email already taken");
                } else {
                    die($conn->error . " " . $conn->errno);
                }
            }
    
            $stmt->close();
            $conn->close();
        }
    }
?>
