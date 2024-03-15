<?php
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
        $username = $_SESSION['user'];
        if(isset($_POST['delete_report'])) {
    $report_id = $_POST['report_id'];
    
    // Prepare and execute SQL to delete the report
    $stmt = $conn->prepare("DELETE FROM reports WHERE id = ?");
    $stmt->bind_param("i", $report_id);
    
    if ($stmt->execute()) {
        // Redirect to the same page after deletion
        header("Location: myReport.php");
        exit();
    } else {
        echo "Error deleting report.";
    }
}
    }

    // Close the database connection
    $conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reports</title>
    <link rel="stylesheet" href="myReport.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
            <a href="../Report/Stock Data Report.php">
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
        <section class="reports">
            <div class="heading">
                <h1>My Reports</h1>
            </div>
            <div class="table">
                <div class="table-body">
                    <table>
                        <thead>
                            <tr>
                                <th>Stock</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Created at</th>
                                <th>Link</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
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
                                    $username = $_SESSION['user'];
                                    
                                    // Prepare the SQL statement to fetch reports for the current user
                                    $stmt = $conn->prepare("SELECT * FROM reports WHERE username = ? and friend_name = ''");
                                    $stmt->bind_param("s", $username);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                            
                                    // Check if there are any reports found
                                    if ($result->num_rows > 0) {
                                        // Output data of each row
                                        while ($row = $result->fetch_assoc()) {
                                            $stock_symbol = $row["stock_symbol"];
                                            $start_date = $row["start_date"];
                                            $end_date = $row["end_date"];
                                            $created_at = $row["current_date_time"];
                                            $creator_name = $row["username"];
                                            $notes = $row["notes"];
                                            
                                                     // Convert dates to DateTime objects
                                                    $dateTime1 = new DateTime($start_date);
                                                    $dateTime2 = new DateTime($end_date);
                                                
                                                    // Calculate the difference in days
                                                    $interval = $dateTime1->diff($dateTime2);
                                                    $daysDifference = $interval->days;
                                                
                                                    // Define the options
                                                    $options = [1, 30, 90, 365];
                                                
                                                    // Find the nearest option
                                                	$closest = null;
                                                	  foreach ($options as $item) {
                                                	     if ($daysDifference === null || abs($daysDifference - $closest) > abs($item - $daysDifference)) {
                                                	         $closest = $item;
                                                	      }
                                                	   }

                                                	if ($closest == 1){$closest = '1D';};
                                                	if ($closest == 30){$closest = '1M';};
                                                	if ($closest == 90){$closest = '3M';};
                                                	if ($closest == 365) {$closest = '1Y';};
                                            
                                            $interval = $closest;
                                            $imgurl = "https://api.chart-img.com/v1/tradingview/mini-chart/storage?symbol=NASDAQ:{$stock_symbol}&interval={$interval}?width=400&height=150&key=YJxjBfJ1Pt8BVkUaewdgp3jZNfUuRKUw52jzS4ga";
                                            $api_result_img = file_get_contents($imgurl);
                                            $data_img = json_decode($api_result_img, true);
                                            $img_url = $data_img["url"];
                                            
                                            if ($img_url ==''){$img_url = '../Report/default.png';};
                                            
                                            $reportURL = 'anotherpage.html?stock_symbol=' .$stock_symbol . '&start_date=' . $start_date . '&end_date=' . $end_date . '&created_at=' . $created_at . '&creator_name=' . $creator_name . '&notes=' . $notes . '&img_url=' . $img_url;                                            
                                            echo "<tr>";
                                            echo "<td>" . $row["stock_symbol"] . "</td>";
                                            echo "<td>" . $row["start_date"] . "</td>";
                                            echo "<td>" . $row["end_date"] . "</td>";
                                            echo "<td>" . $row["current_date_time"] . "</td>";
                                            echo "<td><a href='$reportURL'>Full Report</a></td>";
                                            echo "<td class='delete-btn'>";
                                            echo "<form method='post'>";
                                            echo "<input type='hidden' name='report_id' value='".$row["id"]."'>";
                                            echo "<button type='submit' name='delete_report' style='border: none; background: none; cursor: pointer;'><i class='bx bx-x delete' style='width:24px;'></i></button>";
                                            echo "</form>";
                                            echo "</td>";                           
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>No reports found.</td></tr>";
                                    }
                            
                                    // Close the database connection
                                    $stmt->close();
                                    $conn->close();
                                }
                            ?>
                        </tbody>
                    </table> 
                </div>
            </div>
        </section>
        
        <section class="reports friends">
            <div class="heading">
                <h1>Shared With Me Reports</h1>
            </div>
            <div class="table">
                <div class="table-body">
                    <table>
                        <thead>
                            <tr>
                                <th>From</th>
                                <th>Stock</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Link</th>
                                <th></th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
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
                                    $username = $_SESSION['user'];
                                    
                                    // Prepare the SQL statement to fetch reports for the current user
                                    $stmt = $conn->prepare("SELECT * FROM `reports` WHERE `friend_name` = ?");
                                    $stmt->bind_param("s", $username);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                            
                                    // Check if there are any reports found
                                    if ($result->num_rows > 0) {
                                        // Output data of each row
                                        while ($row = $result->fetch_assoc()) {
                                             $stock_symbol = $row["stock_symbol"];
                                            $start_date = $row["start_date"];
                                            $end_date = $row["end_date"];
                                            $created_at = $row["current_date_time"];
                                            $creator_name = $row["username"];
                                                                                        $notes = $row["notes"];
                                            
                                                     // Convert dates to DateTime objects
                                                    $dateTime1 = new DateTime($start_date);
                                                    $dateTime2 = new DateTime($end_date);
                                                
                                                    // Calculate the difference in days
                                                    $interval = $dateTime1->diff($dateTime2);
                                                    $daysDifference = $interval->days;
                                                
                                                    // Define the options
                                                    $options = [1, 30, 90, 365];
                                                
                                                    // Find the nearest option
                                                	$closest = null;
                                                	  foreach ($options as $item) {
                                                	     if ($daysDifference === null || abs($daysDifference - $closest) > abs($item - $daysDifference)) {
                                                	         $closest = $item;
                                                	      }
                                                	   }

                                                	if ($closest == 1){$closest = '1D';};
                                                	if ($closest == 30){$closest = '1M';};
                                                	if ($closest == 90){$closest = '3M';};
                                                	if ($closest == 365) {$closest = '1Y';};
                                            
                                            $interval = $closest;
                                            $imgurl = "https://api.chart-img.com/v1/tradingview/mini-chart/storage?symbol=NASDAQ:{$stock_symbol}&interval={$interval}?width=400&height=150&key=YJxjBfJ1Pt8BVkUaewdgp3jZNfUuRKUw52jzS4ga";
                                            $api_result_img = file_get_contents($imgurl);
                                            $data_img = json_decode($api_result_img, true);
                                            $img_url = $data_img["url"];
                                            
                                            if ($img_url ==''){$img_url = '../Report/default.png';};
                                            
                                            $reportURL = 'anotherpage.html?stock_symbol=' .$stock_symbol . '&start_date=' . $start_date . '&end_date=' . $end_date . '&created_at=' . $created_at . '&creator_name=' . $creator_name . '&notes=' . $notes . '&img_url=' . $img_url;     
                                            echo "<tr>";
                                            echo "<td>" . $row["username"] . "</td>";
                                            echo "<td>" . $row["stock_symbol"] . "</td>";
                                            echo "<td>" . $row["start_date"] . "</td>";
                                            echo "<td>" . $row["end_date"] . "</td>";
                                            echo "<td><a href='$reportURL'>Full Report</a></td>";
                                            echo "<td class='delete-btn'>";
                                            echo "<form method='post'>";
                                            echo "<input type='hidden' name='report_id' value='".$row["id"]."'>";
                                            echo "<button type='submit' name='delete_report' style='border: none; background: none; cursor: pointer;'><i class='bx bx-x delete' style='width:24px;'></i></button>";
                                            echo "</form>";
                                            echo "</td>"; 
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>No reports found.</td></tr>";
                                    }
                            
                                    // Close the database connection
                                    $stmt->close();
                                    $conn->close();
                                }
                            ?>
                        </tbody>
                    </table> 
                </div>
            </div>
        </section>
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