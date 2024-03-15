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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="portfolio.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

    <title>Portfolio</title>
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
            <h1>Add New Portfolio</h1>
            <form action="formportfolio.php" method="post" id="add_form">
            <?php 
                $query="SELECT * FROM `stocks`";
                $sql=mysqli_query($conn,$query)
            ?>
                <div class="input-box">
                    <i class='bx bxs-user'></i>
                    <input type="username" placeholder="username" disabled name= "username" value="<?php echo $_SESSION['user']; ?>">    
                </div>
                <div class="input-box">
                    <i class='bx bxs-briefcase'></i>
                    <input type="text" placeholder="Portfolio Name" name= "name" required>    
                </div>
                <div class="input-box">
                    <select class="select" name="risk" required>
                        <option selected disabled>Risk Tolerance</option>
                        <option value="1">aggressive</option>
                        <option value="2">moderate</option>
                        <option value="3">conservative</option>
                      </select>
                </div>
                <div class="add">
                    <h3>Add Your Stocks</h3>
                    <p>*Pleae fill Purchased Date and Quantity only if you bought the stock</p>
                    <div class="stockrow mb-1">
                        <select name="stock[]">
                        <option selected disabled>Stock</option>
                        <?php while($row=mysqli_fetch_array($sql)){?>
                            <option><?php echo $row['Symbol']; ?></option>
    
                        <?php }?>
                        </select>
                        <input type="number" placeholder="Quantity" name="quantity[]">
                        <label class="purchased" for="">Purchased Date</label>
                        <input type="date" name="date[]" min="2020-01-01" max="<?php echo date("Y-m-d"); ?>">
                        
                        <button type="button" class="add-more-button" onclick="addStockRow()">Add More</button>
                    </div>
                </div>
    
                <div class="btnsub">
                    <button type="submit" class="btn btn-light" id="submit">Create</button>
                </div>
            </form>
        </div>
        
        <div class="wrapper">
            <h1>Add to Existing Portfolio</h1>
            <form action="editportfolio.php" method="post" id="add_form">
            <?php 
                $query="SELECT * FROM `stocks`";
                $sql=mysqli_query($conn,$query)
            ?>
                <div class="input-box">
                    <i class='bx bxs-user'></i>
                    <input type="username" placeholder="username" name= "username" disabled value="<?php echo $_SESSION['user']; ?>">    
                </div>
            <?php
                
                $user = $_SESSION['user'];
                $portfolios_query = "SELECT DISTINCT `name` FROM `portfolios` WHERE `username` = '$user'";
                $sql2 = mysqli_query($conn,$portfolios_query);
                if (!$sql2) {
                    die('Error in SQL query: ' . mysqli_error($conn));
    }
                
            ?>
                 <div class="input-box">
                    <i class='bx bxs-briefcase'></i>
                    <select class="selectPortfolio" name="Portfolio" required>
                        <option selected disabled>Portfolio Name</option>
                        <?php while($row=mysqli_fetch_array($sql2)){?>
                            <option><?php echo $row['name']; ?></option>
    
                        <?php }?>
                      </select>    
                </div>
                <div class="add">
                    <h3>Add Your Stocks</h3>
                    <p>*Pleae fill Purchased Date and Quantity only if you bought the stock</p>
                    <div class="stockrow mb-1">
                        <select name="stock[]">
                        <option selected disabled>Stock</option>
                        <?php while($row=mysqli_fetch_array($sql)){?>
                            <option><?php echo $row['Symbol']; ?></option>
    
                        <?php }?>
                        </select>
                        <input type="number" placeholder="Quantity" name="quantity[]">
                        <label class="purchased" for="">Purchased Date</label>
                        <input type="date" name="date[]" max="<?php echo date("Y-m-d"); ?>">
                        
                        <button type="button" class="add-more-button" onclick="addStockRow()">Add More</button>

                    </div>
                </div>
    
                <div class="btnsub">
                    <button type="submit" class="btn btn-light" id="submit">Add</button>
                </div>
            </form>
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
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
    $(document).ready(function(){
    $(".add-more-button").click(function(e){
        e.preventDefault();
        
        <?php
            // Fetch stock options from the database
            $query = "SELECT * FROM `stocks`";
            $sql = mysqli_query($conn, $query);
            $options = "";
            while ($row = mysqli_fetch_array($sql)) {
                $options .= '<option>' . $row['Symbol'] . '</option>';
            }
        ?>

        var newStockRow = `<div class="stockrow mb-1">
            <select name="stock[]">
                <option selected disabled>Stock</option>
                <?php echo $options; ?>
            </select>
            <input type="number" placeholder="Quantity" name="quantity[]">
            <label for="">Purchased Date</label>
            <input type="date" name="date[]" max="<?php echo date("Y-m-d"); ?>">
            <button type="button" class="remove-button" onclick="removeStockRow(this)">Remove</button>

        </div>`;
        
        $(this).parent().append(newStockRow);
    });

    });
    function removeStockRow(element) {
        $(element).parent().remove();
    }
</script>
    
</body>
</html>