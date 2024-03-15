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
        $query = "SELECT DISTINCT name FROM `portfolios` WHERE `username` = '$username'";
        $result = $conn->query($query);

        // Check if the query was successful
        if ($result) {
            // Store portfolio names in an array
            $portfolioNames = array();
            while ($row = $result->fetch_assoc()) {
                $portfolioNames[] = $row['name'];
            }

            // Close the result set
            $result->close();
        } else {
            die("Query failed: " . $conn->error);
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
    <title>Home</title>
    <link rel="stylesheet" href="homecss.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
</head>

<body>
    
    <aside>
        <div class="top">
            <h1>StockMate</h1>
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
            <a href="logout.php?logout">
                <i class='bx bx-log-out' ></i>
                <span class="nav-item">Logout</span>
            </a>
        </nav>

    </aside>
     <main>
        <section class="heading">
            <h1><?php echo $_SESSION['user']; ?>'s watchlist</h1>
        </section>
        <section class="portfolios">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <select name="portfolioname" id="portfolioname">
                    <option value="" disabled selected>Select Portfolio</option>
                    <?php
                    // Populate the <select> element with portfolio names
                    foreach ($portfolioNames as $portfolio) {
                        echo "<option value='$portfolio'>$portfolio</option>";
                    }
                    ?>
                </select>
                <button name="showbtn" type="submit" class="button btn-light">Show</button>
            </form>
        </section>
        <section class="stocks">
            <div class="table">
                <div class="table-body">
                    <table>
                        <thead>
                            <tr>
                                <th>Markets</th>
                                <th>Change 1D</th>
                                <th>Sell</th>
                                <th>Buy</th>
                                <th>High</th>
                                <th>Low</th>


                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $host = "localhost";
                                $user = "galb_StockMate_User";
                                $pass = "StockMate";
                                $db = "galb_StockMate_db";
                            
                                $conn = new mysqli($host, $user, $pass, $db);

                            
                                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['showbtn'])) {
                                    $selectedPortfolio = $_POST['portfolioname'];
                            
                                    // Check if the connection is established
                                    if ($conn->connect_error) {
                                        die("Connection failed: " . $conn->connect_error);
                                    }
                            
                                    // Prepare the query to avoid SQL injection
                                    $select = $conn->prepare("SELECT stock FROM `portfolios` WHERE `username` = ? AND `name` = ?");
                                    
                                    // Check if the prepare statement was successful
                                    if (!$select) {
                                        die("Prepare failed: " . $conn->error);
                                    }
                            
                                    // Bind parameters and execute the query
                                    $select->bind_param("ss", $username, $selectedPortfolio);
                                    $select->execute();
                            
                                    // Check for SQL errors
                                    $results = $select->get_result();
                                    if (!$results) {
                                        die("Query failed: " . $select->error);
                                    }
                                    
                                    // Get last trading day date
                                    function getLastNonWeekendDate() {
                                        
                                        $today = new DateTime();
                                        
                                        $today->sub(new DateInterval("P1D"));
                                    
                                        // Check if today is a weekend (Saturday or Sunday)
                                        if ($today->format('N') >= 6) {
                                            // If today is Saturday or Sunday, subtract the appropriate number of days
                                            $daysToSubtract = $today->format('N') == 6 ? 1 : 2;
                                            $today->sub(new DateInterval("P{$daysToSubtract}D"));
                                        }
                                    
                                        // Keep subtracting days until a non-weekend day is reached
                                        while ($today->format('N') >= 6) {
                                            $today->sub(new DateInterval('P1D'));
                                        }
                                    
                                        // Return the last non-weekend date
                                        return $today->format('Y-m-d');
                                        }
                                        
                                     // Get last trading day date minus 1

                                        function getLastNonWeekendDate2($date) {
                                            // Create a DateTime object from the provided date
                                            $dateTime = new DateTime($date);
                                        
                                            // Subtract one day to get the previous date
                                            $dateTime->sub(new DateInterval("P1D"));
                                        
                                            // Check if the adjusted date is a weekend (Saturday or Sunday)
                                            if ($dateTime->format('N') >= 6) {
                                                // If the adjusted date is Saturday or Sunday, subtract the appropriate number of days
                                                $daysToSubtract = $dateTime->format('N') == 6 ? 1 : 2;
                                                $dateTime->sub(new DateInterval("P{$daysToSubtract}D"));
                                            }
                                        
                                            // Keep subtracting days until a non-weekend day is reached
                                            while ($dateTime->format('N') >= 6) {
                                                $dateTime->sub(new DateInterval('P1D'));
                                            }
                                        
                                            // Return the final adjusted date
                                            return $dateTime->format('Y-m-d');
                                        }
                            
                                    // Fetch and display results
                                    while ($row = $results->fetch_assoc()) {
                                        
                                    // Extract values from the current row
                                    $ticker = $row['stock'];
                                    

                                    
                                    // Get today's date
                                    $todayDate = getLastNonWeekendDate();
                                    //$todayDate = '2024-02-16';

                                    
                                    // Get yesterdaydate
                                    $yesterdayDate = getLastNonWeekendDate2($todayDate);
                                    //$yesterdayDate = '2024-02-15';


                                    // Construct the API URL with the dynamic values
                                    $api_url = "https://api.polygon.io/v2/aggs/ticker/{$ticker}/range/1/day/{$todayDate}/{$todayDate}?adjusted=true&sort=asc&limit=120&apiKey=HFtFizTW_Lvqn6Rjf3UgZeYE0czT5lHd";
                                    $api_result = file_get_contents($api_url);
                                    $data = json_decode($api_result, true);
                                    
                                    $api_url_yesterday = "https://api.polygon.io/v2/aggs/ticker/{$ticker}/range/1/day/{$yesterdayDate}/{$yesterdayDate}?adjusted=true&sort=asc&limit=120&apiKey=HFtFizTW_Lvqn6Rjf3UgZeYE0czT5lHd";
                                    $api_result_yesterday = file_get_contents($api_url_yesterday);
                                    $data_yesterday = json_decode($api_result_yesterday, true);
                                    
                                    $closeValue_yesterday = $data_yesterday["results"][0]["c"];
                                    $closeValue = $data["results"][0]["c"];
                                    $openValue = $data["results"][0]["o"];
                                    $lowValue = $data["results"][0]["h"];
                                    $highValue = $data["results"][0]["l"];
                                    $change_1_d = $closeValue_yesterday-$closeValue;
                                    $change_1_d = round($change_1_d,2);

                                    echo "<tr>";
                                    echo "<td>{$row['stock']}</td>";
                                    echo "<td>$change_1_d</td>";
                                    echo "<td>$closeValue</td>";
                                    echo "<td>$openValue</td>";
                                    echo "<td>$highValue</td>";
                                    echo "<td>$lowValue</td>";
                                    echo "</tr>";
                                    }
                            
                                    // Close prepared statement
                                    $select->close();
                                }
                                
                            ?>  
                        </tbody>
                    </table> 
                </div>
            </div>
        </section>

        <section class="friends">
            <section class="heading">
                <h1 class="friend-h">My Friends</h1>
            </section>
            <div class="table">
                <div class="table-body">
                    <table>
                        <thead>
                            <tr>
                                <th>Friend</th>
                                <th>Portfolio</th>
                                <th>Risk</th>
                                <th>Worth</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
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
                            
                            // Check if the connection is established
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }
                            
                            $username = $_SESSION['user'];
                            
                            // Initialize an empty array to store stocks
                            $stocks_array = array();
                            
                            // Fetch friends' information including portfolio name and risk
                            $sql = "SELECT DISTINCT f.followed AS friend, p.name AS portfolio, p.risk
                                    FROM follow f
                                    JOIN portfolios p ON f.followed = p.username
                                    WHERE f.following = ?";
                            
                            // Prepare the statement
                            $stmt = $conn->prepare($sql);
                            
                            // Bind parameters
                            $stmt->bind_param("s", $username);
                            
                            // Execute the query
                            $stmt->execute();
                            
                            // Check for errors
                            if ($stmt->error) {
                                die("Query failed: " . $stmt->error);
                            }
                            
                            // Get the result
                            $result = $stmt->get_result();
                            
                            // Fetch and display results
                            while ($row = $result->fetch_assoc()) {
                                // Reset $stocks_array for each friend
                                $stocks_array = array(); // Initialize an empty array to store stocks
                                $quantity_array = array(); // Initialize an empty array to store quantity of each stocks
                                // Fetch stocks for the current friend's portfolio
                                $stocks_query = "SELECT `stock`, `quantity` FROM portfolios WHERE `username` = ? AND name = ?";
                                $stmt2 = $conn->prepare($stocks_query);
                                $stmt2->bind_param("ss", $row['friend'], $row['portfolio']);
                                
                                // Execute the query
                                $stmt2->execute();

                                // Check for errors
                                if ($stmt2->error) {
                                    die("Query failed: " . $stmt2->error);
                                }

                                // Get the result
                                $stocks_result = $stmt2->get_result();

                                // Fetch and store stocks and quantity in the array
                                while ($stock_row = $stocks_result->fetch_assoc()) {
                                    $stocks_array[] = $stock_row['stock'];
                                    $quantity_array[]= $stock_row['quantity'];
                                    
                                }
                                
                                // Close the statement
                                $stmt2->close();
                                
                                $portfolio_worth = 0;
                                
                                // Loop through stocks and quantities
                                for ($i = 0; $i < count($stocks_array); $i++) {
                                    

                                        $today = new DateTime();
                                        
                                        $today->sub(new DateInterval("P1D"));
                                    
                                        // Check if today is a weekend (Saturday or Sunday)
                                        if ($today->format('N') >= 6) {
                                            // If today is Saturday or Sunday, subtract the appropriate number of days
                                            $daysToSubtract = $today->format('N') == 6 ? 1 : 2;
                                            $today->sub(new DateInterval("P{$daysToSubtract}D"));
                                        }
                                    
                                        // Keep subtracting days until a non-weekend day is reached
                                        while ($today->format('N') >= 6) {
                                            $today->sub(new DateInterval('P1D'));
                                        }
                                    
                                        // Return the last non-weekend date
                                        $todayDate =  $today->format('Y-m-d');

                                    // Make API call to get stock price
                                
                                    $stock_symbol = $stocks_array[$i];

                                    $api_url_l = "https://api.polygon.io/v2/aggs/ticker/{$stock_symbol}/range/1/day/{$todayDate}/{$todayDate}?adjusted=true&sort=asc&limit=120&apiKey=HFtFizTW_Lvqn6Rjf3UgZeYE0czT5lHd";
                                    $api_result_l = file_get_contents($api_url_l);
                                    $data_l = json_decode($api_result_l, true);
                                    $stock_price = $data_l["results"][0]["c"];
                                    
                                    // Update portfolio worth
                                    $portfolio_worth += $stock_price * $quantity_array[$i];
                                }
                                


                                
                                // Output the friend's portfolio details
                                echo "<tr>";
                                echo "<td>{$row['friend']}</td>";
                                echo "<td>{$row['portfolio']}</td>";
                                echo "<td>{$row['risk']}</td>";
                                echo "<td>$portfolio_worth</td>"; // Start a table cell for stocks
                                echo "</tr>";
                                
                                /* Output each stock as a list item
                                foreach ($stocks_array as $stock) {
                                    echo "<p>{$row['friend']} $stock</p>";
                                }
                                 foreach ($quantity_array as $quantity) {
                                    echo "<p>{$row['friend']} $quantity</p>";
                                }*/
                                
                            }

// Close prepared statement
$stmt->close();

// Close the database connection
$conn->close();
                            ?>
                            </tr>
                        </tbody>
                    </table> 
                </div>
            </div>
        </section>
        <section class="friends_portfolios">
            <section class="heading">
                <h1 class="head_portfolios">My Friend's Portfolios</h1>
            </section>

            <?php
            if (session_status() === PHP_SESSION_NONE) {
                                session_start();
                            }
                            
            $host = "localhost";
            $user = "galb_StockMate_User";
            $pass = "StockMate";
            $db = "galb_StockMate_db";
            
            $conn = new mysqli($host, $user, $pass, $db);
            
            // Check if the connection is established
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch friends' information including portfolio name and risk
            $sql = "SELECT DISTINCT  f.followed AS friend, p.name AS portfolio
                    FROM follow f
                    JOIN portfolios p ON f.followed = p.username
                    WHERE f.following = '$username'";
    
            // Prepare the statement
            $stmt = $conn->prepare($sql);
    
            // Bind parameters
            $stmt->bind_param("s", $username);
    
            // Execute the query
            $stmt->execute();
    
            // Check for errors
            if ($stmt->error) {
                die("Query failed: " . $stmt->error);
            }
    
            // Get the result
            $result = $stmt->get_result();
    
            // Fetch and display results
            while ($row = $result->fetch_assoc()) {
                $friendUsername = $row['friend'];
                $portfolioName = $row['portfolio'];
    
                echo "<div class='f_portfolio_box'>";
                echo "<h2 class='username'>$friendUsername</h2>";
                echo "<h4 class='portfolio'>$portfolioName</h4>";
    
                $stocksQuery = "SELECT stock FROM `portfolios` WHERE `username` = '$friendUsername' AND `name` = '$portfolioName'";
                $stocksResult = $conn->query($stocksQuery);
                
                // Check for errors in the stocks query
                if ($stocksResult === false) {
                    die("Stocks query failed: " . $conn->error);
                }
     
                if ($stocksResult->num_rows > 0) {
                   
                    while ($stocksRow = $stocksResult->fetch_assoc()) {
                        echo "<div class='f_stocks'>";
                        $stockName = $stocksRow['stock'];
                        echo "<label class='stock'>$stockName</label>";
                        echo "</div>";

                    }
                } else {
                    echo "<div class='f_stocks'>No stocks found</div>";
                }
                
                // Close the stocks result set
                $stocksResult->close();
    
                //echo "<div class='addbtn'>";
                //echo "<button type='submit' name='followbtn'>Add</button>";
                //echo "</div>";
                echo "</div>";
                
            }

            // Close prepared statement
            $stmt->close();
            ?>
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