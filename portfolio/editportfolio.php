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

            $name = $_POST['Portfolio'];
            $stock = $_POST['stock'];
            $quantity = $_POST['quantity'];
            $date = $_POST['date'];
            $username = $_SESSION['user'];
            
            var_dump($_POST);
            
            foreach($stock as $key => $value){
                
            
                    $quantityValue = isset($quantity[$key]) ? intval($quantity[$key]) : 0;
                    $dateValue = isset($date[$key]) && !empty($date[$key]) ? "'" . date('Y-m-d', strtotime(mysqli_real_escape_string($conn, $date[$key]))) . "'" : "'1970-01-01'";
                    
                    echo "Quantity Value: " . $quantityValue . "<br>";
                    echo "Date Value: " . $dateValue . "<br>";
                    
                    // Insert data into the database
                    $save = "INSERT INTO `portfolios`(`username`, `name`, `stock`, `quantity`, `date`) VALUES ('$username','$name','$value',$quantityValue, $dateValue)";
                    $query = mysqli_query($conn, $save);
            
                    if (!$query) {
                        // Handle the error if the query fails
                        echo "Error: " . mysqli_error($conn);
                    
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