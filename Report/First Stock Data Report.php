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
    }
    else {
        // Check if the session variable is set
        if(isset($_SESSION['user'])) {
            $username = $_SESSION['user'];
        } else {
            echo "User is not logged on"; 
        }
    }

    // Close the database connection
    $conn->close();
?>
<!DOCTYPE html>

<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
	<link href='Stock Data Report.css' rel='stylesheet'>	
	 <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="https://rawgit.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

     <title>Stock Data Report</title>
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
            <a href="#">
                <i class='bx bxs-report' ></i>
                <span class="nav-item">Create Report</span>
            </a>
            <a href="../Report/myReport.php">
                <i class='bx bxs-report' ></i>
                <span class="nav-item">My Reports</span>
            </a>
            <p>News Feed</p>
            <a href="#">
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
<div class="container">
        <h1>Stock Data Report Generation</h1>
 <form id="stockReportForm" method="GET" action="Stock Data Report.php">
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
    }
    else {
        // Check if the session variable is set
        if(isset($_SESSION['user'])) {
            $username = $_SESSION['user'];
        } else {
           
        }
    }
        $query="SELECT * FROM `stocks`";
        $sql=mysqli_query($conn,$query)
?>
<label for="stockSymbol">Select Stock:</label>
<select id="stockSymbol" name="stockSymbol" required>
    <option value="" selected disabled></option>
     <?php while($row=mysqli_fetch_array($sql)){?>
                            <option><?php echo $row['Symbol']; ?></option>
                        <?php }?>                                               
</select>
    
<div id="dateForm">
<div class="input-group">
    <label for="startDate">Select start date:</label>
    <input type="date" id="startDate" name="startDate" class="date-group" required min="2020-01-01">
</div>

    <div class="input-group">
        <label for="endDate">Select end date:</label>
        <input type="date" id="endDate" name="endDate" class="date-group" required>
    </div>
	
</div>


			  <label for="note">Add Note:</label>
			<textarea id="note" name="note" required></textarea>	
 <div id="friendNameField">
        <label for="friendName">Choose Friend:</label>
        <select id="friendName" name="friendName" style="width: 100%;">
            <?php
            // Start PHP code to fetch and populate usernames
            session_start(); // Start the session to access session variables

            $host = "localhost";
            $user = "galb_StockMate_User";
            $pass = "StockMate";
            $db = "galb_StockMate_db";

            // Create connection
            $conn = new mysqli($host, $user, $pass, $db);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } else {
                $username = $_SESSION['user'];
                $query2 = "SELECT * FROM `follow` WHERE `following` = '$username'";
                $friends = mysqli_query($conn, $query2);

                if ($friends->num_rows > 0) {
                    // Output data of each row
                    echo "<option selected disabled>Select Friend</option>";
                    while ($row = $friends->fetch_assoc()) {
                        echo "<option>" . $row['followed'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No friends available</option>";
                }
            }
            ?>
            <!-- End of PHP code to fetch and populate usernames -->
        </select>
    </div>
<div id="buttonContainer">
    <div class="button-wrapper">
        <button type="submit" class="btn btn-light" name="action" value="save">Save Report</button>
    </div>
    <div class="button-wrapper">
        <button type="submit" class="btn btn-light" name="action" value="send" id="sendBtn">Send report to friend</button>
    </div>
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

<script>
// Function to validate the form before submission
function validateForm() {
    // Check if the user has selected a stock
    var stockSymbol = document.getElementById('stockSymbol').value;
    if (stockSymbol === "") {
        alert("Please select a stock.");
        return false; // Prevent form submission
    }
    
    // Check if the user has selected a start date
    var startDate = document.getElementById('startDate').value;
    if (startDate === "") {
        alert("Please select a start date.");
        return false; // Prevent form submission
    }
    
    // Check if the user has selected an end date
    var endDate = document.getElementById('endDate').value;
    if (endDate === "") {
        alert("Please select an end date.");
        return false; // Prevent form submission
    }
    
    // If all validations pass, return true to allow form submission
    return true;
}

// Variable to track if the report has been sent successfully
var reportSent = false;

// Get the send button, the friend name select element, and the form
const sendBtn = document.getElementById("sendBtn");
const friendNameSelect = document.getElementById("friendName");
const form = document.getElementById("stockReportForm");

// Add event listener to the send button
sendBtn.addEventListener("click", function(event) {
    // Check if the value of the button is "send"
    if (this.value === "send") {
        // Check if the selected value is "Select Friend"
        if (friendNameSelect.value === "Select Friend") {
            // If "Select Friend" is selected, prevent form submission
            event.preventDefault();
            // Show an alert to prompt the user to choose a friend
            alert("Please choose a friend from the list.");
            return; // Exit the function to prevent further execution
        }
        // If the report has already been sent, prevent further actions
        if (reportSent) {
            event.preventDefault();
            return;
        }
        reportSent = true;
        alert('the report has been send successfully ');
    }
});

// Add event listener to the form submission
form.addEventListener("submit", function(event) {
    // Call the validateForm function before form submission
    if (!validateForm()) {
        event.preventDefault(); // Prevent form submission if validation fails
    } else {
        // Check if the value of the submit button is "send"
        if (document.activeElement.id === "sendBtn") {
            if (!reportSent) {
                alert("the report has been send successfully ");
                reportSent = true;
            }
        } else {
            alert("the report has been saved successfully ");
        }
    }
});

// Get today's date
var today = new Date().toISOString().split('T')[0];

// Set max attribute for start date input
document.getElementById('startDate').setAttribute('max', today);

// Set max attribute for end date input
document.getElementById('endDate').setAttribute('max', today);

// Set min attribute for end date input based on start date
document.getElementById('startDate').addEventListener('change', function() {
    document.getElementById('endDate').setAttribute('min', this.value);
});


</script>

</body>
</html>