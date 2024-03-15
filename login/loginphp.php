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
} else {
   if (isset($_POST['save'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM `user` WHERE `username` = '$username'";
    $result = mysqli_query($conn, $query);

    if ($user = mysqli_fetch_assoc($result)) {
        // Verify the hashed password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $username;
            header("location: https://galb.mtacloud.co.il/StockMate/home/home.php");
            exit();
        } else {
            echo "<script>window.location.href = 'https://galb.mtacloud.co.il/StockMate/login/fail_login.html';</script>";
            exit();
        }
    } else {
        echo "<script>window.location.href = 'https://galb.mtacloud.co.il/StockMate/login/fail_login.html';</script>";
        exit();
    }
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="logincss.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    
    <div class="center col-5">
        <h1>Login</h1>
        <form action="loginphp.php" method="post" >
            <div class="input-box">
                <input type="text" required placeholder="username" name="username"> 
                <i class='bx bxs-user'></i>
            </div>
            <div class="input-box">
                <input type="password" required placeholder="password" name="password">
                <i class='bx bxs-lock-alt' ></i>
            </div>
            <div class="forgot mt-1">
                <a href="#">Forgot password?</a>
            </div>
            <button type="submit" name="save" class="btn btn-light">Login</button>
            <div class="register mt-1">
                <a href="../registration/register.html">Create new account</a>
            </div>
        
        </form>
    </div>
   
</body>
</html>