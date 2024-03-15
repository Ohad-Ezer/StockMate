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


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="myProfile.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script>
    
        function validate(){
            var password = document.getElementById("newPassword");
            var newPassword = document.getElementById("newPassword");

            // Check if the password is at least 8 characters
           if (newPassword.value !== '' && newPassword.value.length < 8) {
                alert("Password must be at least 8 characters long.");
                return false;
            }
            console.log("Form validated successfully.");
            return true;
        }
    </script>

</head>

<body>
    
 <aside>
        <div class="top">
            <a href="../home/home.php"><h1 style="font-size: 24px; font-weight: bold;">StockMate</h1></a>
        </div>
        <nav>
            <a href="../myProfile/myProfile.php">
                <i class='bx bx-user-circle'></i>
                <span class="nav-item">My Profile</span>
            </a>
            <a href="../portfolio/portfolio.php">
                <i class='bx bx-briefcase' ></i>
                <span class="nav-item">Create Portfolio</span>
            </a>
            <p>Reports</p>
            <a href="../Report/First Stock Data Report.php">
                <i class='bx bxs-report' ></i>
                <span class="nav-item">Create Report</span>
            </a>
            <a href="../Report/myReport.php">
                <i class='bx bxs-report' ></i>
                <span class="nav-item">My Reports</span>
            </a>
            <p>News Feed</p>
            <a href="../NewsFeed/formpagev2.html">
                <i class='bx bxs-report' ></i>
                <span class="nav-item">Feed Preferences</span>
            </a>
            <a href="../NewsFeed/live_feed.html">
                <i class='bx bx-news' ></i>
                <span class="nav-item">Custom Feed</span>
            </a>
            <p>Social</p>
            <a href="../friends/friends.php">
                <i class='bx bx-user-plus'></i>
                <span class="nav-item">Add Friends</span>
            </a>
            <a href="../Groups/Stock Trading Groups.php">
                <i class='bx bx-group' ></i>
                <span class="nav-item">Groups</span>
            </a>
            <p></p>
            <p></p>
                <a href="../home/logout.php?logout">
                <i class='bx bx-log-out' ></i>
                <span class="nav-item">Logout</span>
            </a>
        </nav>

    </aside>
     <main>
        <div class="wrapper">
            <h1>My Profile</h1>
            <h3>update your details if needed</h3>
            <form action="update_profile.php" method="post" onsubmit="return validate()">
                <div class="input-box">
                    <i class='bx bx-user'></i>
                    <input type="text" placeholder="Full Name" required id="fullname" name="fullname" value="<?php echo $userDetails['fullname']; ?>">    
                </div>
                <div class="input-box">
                    <i class='bx bx-user' style="vertical-align: middle;"></i>
                    <input type="username" placeholder="username" name= "username" disabled value="<?php echo $_SESSION['user']; ?>">    
                    
                </div>
                <div class="input-box">
                    <i class='bx bx-phone' ></i>
                    <input type="number" placeholder="Phone Number" required name="phonenumber" value="<?php echo $userDetails['phonenumber']; ?>">    
                </div>
                <div class="input-box">
                    <i class='bx bx-envelope'></i>
                    <input type="email" placeholder="Email Address" disabled required name="email" value="<?php echo $userDetails['email']; ?>">    
                </div>
                <div class="input-box">
                    <i class='bx bx-lock-alt'></i>
                    <input type="password" placeholder="Old Password" required id="password" name="oldPassword">
                </div>
                <div class="input-box">
                    <i class='bx bx-lock-alt'></i>
                    <input type="password" placeholder="New Password" id="newPassword" name="newPassword"> 
                </div>
                 <div class="buttons-box">                 
                    <div class="radio-input">
                        <i class='bx bx-low-vision'></i>                    
                        <label>Profile Visibility</label>
                        <input class="radiobtn" type="radio" id="Private" name="visibility" value="private" <?php if ($userDetails['visibility'] == 'private') echo 'checked'; ?> required>    
                        <label>Private</label>
                        <input class="radiobtn" type="radio" id="Public" name="visibility" value="Public" <?php if ($userDetails['visibility'] == 'public') echo 'checked'; ?>>    
                        <label>Public</label>
                    </div>
                    <div class="radio-input">
                        <i class='bx bx-signal-4' ></i>                   
                        <label>Knowledge Level</label>
                        <input class="radiobtn" type="radio" id="Beginner" name="level" value="Beginner" <?php echo ($userDetails['level'] === 'beginner') ? 'checked="checked"' : ''; ?> required>    
                        <label>Beginner</label>
                        <input class="radiobtn" type="radio" id="Intermediate" name="level" value="Intermediate" <?php echo ($userDetails['level'] === 'intermediate') ? 'checked="checked"' : ''; ?>>    
                        <label>Intermediate</label>
                        <input class="radiobtn" type="radio" id="Expert" name="level" value="Expert" <?php echo ($userDetails['level'] === 'expert') ? 'checked="checked"' : ''; ?>>    
                        <label>Expert</label>
                    </div>
                </div>
                <div class="btnsub">
                    <button type="submit" class="btn btn-light">Update</button>
                </div>
            </form>
            
        </div>
            <div class="portfolios wrapper" style="margin-bottom: 5%;">
            <h1>My Portfolios</h1>
            <h3>watch, edit and delete your portfolios</h3>
           <?php
            // Fetch unique portfolio names
            $query = "SELECT DISTINCT name FROM `portfolios` WHERE `username` = '$username'";
            $result = $conn->query($query);
        
            // Check if the query was successful
            if ($result) {
                // Output data of each unique portfolio
                while ($row = $result->fetch_assoc()) {
                    $portfolioName = $row['name'];
                    $risk_query = "SELECT risk FROM `portfolios` WHERE `name` = '$portfolioName'";
                    $result_risk = $conn->query($risk_query);
                    $risk_row = $result_risk->fetch_assoc();
                    $risk = $risk_row['risk'];
                    echo "<div class='f_portfolio_box'>";
                    echo "<h2 class='portfolio'>$portfolioName</h2>";
                    echo "<h5 class='portfolio'>$risk</h5>";
                    // Fetch and display stocks related to the current portfolio
                    $getStocksQuery = "SELECT * FROM `portfolios` WHERE `username` = '$username' AND `name` = '$portfolioName'";
                    $getStocksResult = $conn->query($getStocksQuery);
        
                    if ($getStocksResult->num_rows > 0) {
                        // Output data of each stock
                        while ($stock = $getStocksResult->fetch_assoc()) {
                            echo "<div class='f_stocks'>";
                            echo "<label class='stock'>" . $stock['stock'] . "</label>";
                            echo "<form action='delete_stock.php' method='post' class='delete-form' style='display: inline;'>";
                            echo "<input type='hidden' name='portfolio_name' value='" . $portfolioName . "'>";
                            echo "<input type='hidden' name='stock_id' value='" . $stock['id'] . "'>";
                            echo "<button type='submit' class='delete-btn' style='color:#ffffff;width: 20px; font-size:12px; cursor: pointer; background: none; border: none; padding: 0;'>";
                            echo "<i class='bx bx-x delete'></i>";
                            echo "</button>";
                            echo "</form>";
                            echo "</div>";
                        }
                    
                    } else {
                        echo "<div class='f_stocks'>";
                        echo "<label class='stock'>No stocks found for this portfolio.</label>";
                        echo "</div>";
                    }
        
                    echo "<div class='deletebtn'>";
                    echo "<form action='delete_portfolio.php' method='post' onsubmit=\"return confirm('Are you sure you want to delete this portfolio?');\">";
                    echo "<input type='hidden' name='portfolio_name' value='" . $portfolioName . "'>";
                    echo "<button type='submit' name='followbtn'>Delete</button>";
                    echo "</form>";
                    echo "</div>";

        
                    echo "</div>"; // Close f_portfolio_box
                }
        
                // Close the result set
                $result->close();
            } else {
                echo "No portfolios found for this user.";
            }
        ?>
        </div>
    </main>
     <footer>
        <div class="footer-bottom">
            <hr>
            <h3>Follow Us</h3>
            <div class="social-icons">
                <a href="#"><i class="bx bxl-facebook"></i></a>
                <a href="#"><i class="bx bxl-twitter"></i></a>
                <a href="#"><i class="bx bxl-instagram"></i></a>
                <a href=""><i class='bx bxl-linkedin-square'></i></a>
            </div>
            <p>&copy; 2024 StockMate. All Rights Reserved.</p>     
        </div>
    </footer>

    
</body>
</html>