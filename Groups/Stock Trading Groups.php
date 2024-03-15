<?php
// Check if the session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

$sql = "SELECT id, name, members, Index_Performance, Objective, Trading_Instruments FROM `groups`";
// Execute the SQL query
$result = $conn->query($sql);

// Check if the form is submitted and action is provided
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    // Handle form submission
    // Receive action and groupId from POST request
    $action = $_POST['action'];
    $groupId = $_POST['groupId'];

    // Check if the session variable is set
    if (isset($_SESSION['user'])) {
        $username = $_SESSION['user'];

        // Perform membership check only if the action is join or leave
        if ($action == 'join' || $action == 'leave') {
            // Perform membership check for the user in the group
            $sql_check_membership = "SELECT COUNT(*) AS count FROM `groups` WHERE id = '$groupId' AND FIND_IN_SET(' $username', members)";
            $result_check_membership = mysqli_query($conn, $sql_check_membership);
            // Check if the query execution was successful
            if ($result_check_membership === false) {
                // Error handling if the query failed
                echo "Error executing SQL query: " . mysqli_error($conn);
            } else {
                // Fetch the first row of the result set as an associative array
                $row_check_membership = mysqli_fetch_assoc($result_check_membership);

                // Retrieve the 'count' value from the associative array
                $count = $row_check_membership['count'];

                if ($action == 'join') {
                    // If the user is joining
                    if ($count > 0) {
                  
                    } else {
                        // Proceed with joining the group
                        $sql_update_membership = "UPDATE `groups` SET members = CONCAT(members, ', $username') WHERE id = '$groupId'";
                        $result_update_membership = mysqli_query($conn, $sql_update_membership);

                        if (!$result_update_membership) {
                            // Error handling if the query for joining the group fails
                            echo "Error joining group: " . mysqli_error($conn);
                        } else {
                            // Membership successfully updated
                            echo "<script>alert('You have successfully joined the group.');window.location.reload();window.location.reload();</script>";
                        }
                    }
                } elseif ($action == 'leave') {
                    // If the user is leaving
                    if ($count == 0) {
                    } else {
                        // Proceed with removing the user from the group
                        $sql_update_membership = "UPDATE `groups` SET members = TRIM(BOTH ',' FROM REPLACE(CONCAT(',', members, ','), ', $username,', ',')) WHERE id = '$groupId'";
                        $result_update_membership = mysqli_query($conn, $sql_update_membership);

                        if (!$result_update_membership) {
                            // Error handling if the query for removing the user fails
                            echo "Error removing user from group: " . mysqli_error($conn);
                        } else {
                            // User successfully removed from the group
                            echo "<script>alert('You have been successfully removed from the group.');window.location.reload();window.location.reload();</script>";
                        }
                    }
                } else {
                    // Invalid action
                    echo "Invalid action.";
                }
            }
        } else {
            // Action is not join or leave, handle it accordingly
            echo "Invalid action.";
        }
    } else {
        // User is not logged in
        echo "User is not logged in.";
    }
} else {
    // Action is not provided or form is not submitted
}

// Fetch user's groups from the database
$userGroups = [];
if (isset($_SESSION['user'])) {
    $username = $_SESSION['user'];
    $sql_user_groups = "SELECT id, name FROM `groups` WHERE FIND_IN_SET(' $username', members)";
    $result_user_groups = mysqli_query($conn, $sql_user_groups);
    if ($result_user_groups) {
        while ($row = mysqli_fetch_assoc($result_user_groups)) {
            $userGroups[] = $row;
        }
    } else {
        echo "Error fetching user groups: " . mysqli_error($conn);
    }
}

// Pass user's groups to JavaScript
echo "<script>";
echo "var userGroups = " . json_encode($userGroups) . ";";
echo "</script>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Trading Groups</title>
	<link href='Stock Trading Groups.css' rel='stylesheet'>
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src='Stock Trading Groups.js'></script>
        

</head>
<body>
    
<aside>
        <div class="top">
            <a href="../home/home.php"><h1 style="font-size: 24px; font-weight: bold; color: aliceblue;">StockMate</h1></a>
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
        <header>
            <h1>Stock Trading Groups</h1>
        </header>
        <section>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $isMember = strpos($row['members'], $_SESSION['user']) !== false;
                echo '<div class="group-card" id="group' . $row['id'] . '" data-id="' . $row['id'] . '" data-name="' . $row['name'] . '" data-members="' . $row['members'] . '" data-index-performance="' . $row['Index_Performance'] . '" data-objective="' . $row['Objective'] . '" data-trading-instruments="' . $row['Trading_Instruments'] . '">';
                echo '<h3>' . $row['name'] . '</h3>';
                echo '<p id="group' . $row['id'] . '-Industry and Market Trends">Industry and Market Trends: Innovation</p>';
                echo '<p>Objective: ' . $row['Objective'] . '</p>';
                echo '<p>Trading Instruments: ' . $row['Trading_Instruments'] . '</p>';
                echo '<button class="button" onclick="showMore(' . $row['id'] . ')">Show More</button>';
                echo '<div class="more-content" style="display: none;">';
                echo '<p>Index Performance: ' . $row['Index_Performance'] . '</p>';
                echo '<p>Market Members: ' . $row['members'] . '</p>';
                echo '</div>';
                echo '<button class="button" onclick="openChatBox(&#39;group' . $row['id'] . '&#39;)">Chat Box</button>';
echo '<form id="groupForm_' . $row['id'] . '" method="post" action="">';
echo '<input type="hidden" name="groupId" value="' . $row['id'] . '">';


// Button for joining group
if ($isMember) {
    echo '<button id="joinBtn_' . $row['id'] . '" class="button" style="display: none;" name="action" value="join">Join Group</button>';
} else {
    echo '<button id="joinBtn_' . $row['id'] . '" class="button" name="action" value="join">Join Group</button>';
}

// Button for leaving group
if ($isMember) {
    echo '<button id="leaveBtn_' . $row['id'] . '" class="button" name="action" value="leave">Leave Group</button>';
} else {
    echo '<button id="leaveBtn_' . $row['id'] . '" class="button" style="display: none;" name="action" value="leave">Leave Group</button>';
}



echo '</form>';


                echo '</div>';
            }
        } else {
            die("Error in SQL query: " . $conn->error);
        }
        ?>

  </section>
  
   
	<!-- Chat Box Modal -->
<div id="chatBoxModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeChatBox()">×</span>
        <h3>Group Chat</h3>
        <div id="chatMessages"></div>
    </div>
</div>

    
    <!-- Add a container for the buttons -->
<div class="centered-buttons">
    <!-- Show my Groups button -->
    <button id="show-groups-button" onclick="showMyGroups()">Show my Groups</button>
</div>

    <!-- The Modal -->
    <div id="myGroupsModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">×</span>
            <h3 class="my-groups-header">Your Groups are:</h3>
            <div id="my-groups-container"><div class="group-name">a</div><div class="group-name">Global Innovators</div></div>
        </div>
    </div>

    <!-- Notification for My Groups -->
    <div id="myGroupsNotification" class="notification"></div>

    <!-- Container to display your groups -->
    <div id="my-groups-dropdown">
        <div id="my-groups-container"></div>
    </div>

    <!-- My Groups Dropdown -->
    <div id="my-groups-dropdown" class="my-groups">
        <!-- Example group links -->
    </div>

    <!-- New Group Form -->
    <div id="new-group-form" style="display: none;">
        <label for="group-name">Group Name:</label>
        <input type="text" id="group-name" name="group-name" required="">

        <label for="group-objective">Objective:</label>
        <textarea id="group-objective" name="group-objective" rows="4" required=""></textarea>

        <label for="group-instruments">Trading Instruments:</label>
        <textarea id="group-instruments" name="group-instruments" rows="4" required=""></textarea>

        <button onclick="createGroup()">Create Group</button>
    </div>
	<div id="my-groups-container"></div>
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

 <script>

function showMore(groupId) {
    var groupCard = document.getElementById('group' + groupId);

    if (groupCard) {
        var moreContent = groupCard.querySelector('.more-content');

        if (moreContent) {
            // Check if the additional information container exists
            var additionalInfoContainer = groupCard.querySelector('.additional-info');

            if (!additionalInfoContainer) {
                // Retrieve data from the attributes of the group card
                var id = groupCard.getAttribute('data-id');
                var name = groupCard.getAttribute('data-name');
                var members = groupCard.getAttribute('data-members');
                var indexPerformance = groupCard.getAttribute('data-index-performance');

                // Additional information to be displayed
                var additionalInfo = `
                    <div class="additional-info">
                        <p>Group Members: ${members}</p>
                        <p>Index Performance: ${indexPerformance}</p>
                        <!-- Add more details as needed -->
                    </div>
                `;

                // Append the additional information
                groupCard.insertAdjacentHTML('beforeend', additionalInfo);
            } else {
                // Additional information container exists, remove it
                additionalInfoContainer.remove();
            }
        } else {
            console.error('Error: More content not found.');
        }
    } else {
        console.error('Error: Group card not found.');
    }
}

</script>

</body></html>
