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

            $name = $_POST['name'];
            $risk = $_POST['risk'];
            $stock = $_POST['stock'];
            $quantity = $_POST['quantity'];
            $date = $_POST['date'];
    
            $username = $_SESSION['user'];
            
            foreach($stock as $key => $value){
                
                
                // Check if the required fields are not empty for the current stock entry
                if (!empty($username) && !empty($name) && !empty($risk) && !empty($value)) {
                    // Check if optional fields are set, if not, set them to NULL
                    $quantityValue = isset($quantity[$key]) ? intval($quantity[$key]) : "NULL";
                    $dateValue = isset($date[$key]) ? "'" . date('Y-m-d', strtotime(mysqli_real_escape_string($conn, $date[$key]))) . "'" : "NULL";
            
                    // Insert data into the database
                    $save = "INSERT INTO `portfolios`(`username`, `name`, `risk`, `stock`, `quantity`, `date`) VALUES ('$username','$name','$risk','$value',$quantityValue,$dateValue)";
                    $query = mysqli_query($conn, $save);
            
                    if (!$query) {
                        // Handle the error if the query fails
                        echo "Error: " . mysqli_error($conn);
                    }
                } else {
                    // Handle the case where required fields are missing for the current stock entry
                    echo "Skipping incomplete stock entry.";
                }
               
            }
                if ($query) {
                    // Redirect to portfoliosuccess.html
                    echo '<script type="text/javascript">window.location.href = "portfoliosuccess.html";</script>';
                    exit();
                } else {
                    // Handle the case where the database insertion failed
                    echo "Failed to insert data into the database.";
                }
        }
        

?>