<?php
    session_start();
    
    if (isset($_GET['logout'])) {
        session_destroy(); // Destroy the session
    
        // Redirect using PHP header
        header("Location: https://galb.mtacloud.co.il/StockMate/login/loginphp.php");
        exit();
    }
?>