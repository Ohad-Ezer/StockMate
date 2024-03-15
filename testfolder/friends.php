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
        // Fetch user details from the database where visibility is 'public'
        $sql = "SELECT `fullname`,`username`,`visibility`,`level`,`email` FROM `user` WHERE `visibility` = 'public' AND `username` != '$username'";
        $result = $conn->query($sql);

        // Fetch the data as an associative array
        $usersData = array();
        while ($row = $result->fetch_assoc()) {
            $usersData[] = $row;
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
    <title>Friends</title>
    <link rel="stylesheet" href="friends.css">
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
        
        <div class="friends_zone">
            <div class="heading">
            <h1>Find New Friends!</h1>
        </div>
            <div class="search">
                <input type="text" placeholder="Search Username.." name="search_username" class="search_username" id="searchInput">
                <button type="submit" name="search" id="searchButton">Search</button>
            </div>
            <!-- Loop through user data and create user boxes dynamically -->
            <?php
            // Function to check if the current user is following a specific user
            function checkIfFollowing($follower, $following)
            {
                $host = "localhost";
                $user = "galb_StockMate_User";
                $pass = "StockMate";
                $db = "galb_StockMate_db";
            
                $conn = new mysqli($host, $user, $pass, $db);
            
                // check the connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                } else {
                    // Check if the user is following the specified user
                    $sql = "SELECT * FROM follow WHERE followed = '$following' AND following = '$follower'";
                    $result = $conn->query($sql);
            
                    // Return true if a row exists, indicating that the user is following
                    return $result->num_rows > 0;
                }
            
                // Close the database connection
                $conn->close();
            }
            ?>
            <?php foreach ($usersData as $user) : ?>
            <?php
            // Check if the current user is following this user
            $isFollowing = checkIfFollowing($username, $user['username']);
            ?>
        
            <div class="user-box" data-username="<?php echo $user['username']; ?>">
                <h2 class="fullname"><?php echo $user['fullname']; ?></h2>
                <h4 class="username"><?php echo $user['username']; ?></h4>
                <p class="knowledge"><?php echo $user['level']; ?></p>
        
                <!-- Display "Follow" or "Following" button based on whether the user is already following -->
                <?php if ($isFollowing) : ?>
                    <form action="unfollow.php" method="post">
                        <input type="hidden" name="followedUsername" value="<?php echo $user['username'];?>">
                        <button type="submit" name="followbtn" style="background-color: #333; color: #ffffff; border: none;"onmouseover="this.style.backgroundColor='#555'" onmouseout="this.style.backgroundColor='#333'">Unfollow</button>
                    </form>
                <?php else : ?>
                    <form action="follow.php" method="post">
                        <input type="hidden" name="followedUsername" value="<?php echo $user['username']; ?>">
                        <button type="submit" name="followbtn">Follow</button>
                    </form>
                <?php endif; ?>
                <a class="mail" href="mailto:<?php echo $user['email']; ?>" style="display: block;">Send Mail</a>

            </div>
        <?php endforeach; ?>
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
    
    
    
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        // Function to handle the search
        function handleSearch() {
            var searchTerm = $('#searchInput').val();

            // Show all user-boxes if the search term is empty
            if (searchTerm === '') {
                $('.user-box').show();
                return;
            }

            // Hide all user-boxes
            $('.user-box').hide();

            // Show only user-boxes that match the search term (case-insensitive)
            $('.user-box').each(function () {
                var username = $(this).data('username');
                if (username == searchTerm) {
                    $(this).show();
                }
            });
        }

        // Initial state: show all user-boxes
        $('.user-box').show();

        // Event handler for the search button
        $('#searchButton').on('click', handleSearch);

        // Event handler for the Enter key in the search input
        $('#searchInput').on('keyup', function (event) {
            if (event.key === 'Enter') {
                handleSearch();
            }
        });
    });
</script>
</body>
</html>