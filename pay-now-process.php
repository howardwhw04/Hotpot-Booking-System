<?php
session_start();

// Establish a database connection
$conn = mysqli_connect("localhost", "root", "", "hbs");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if user is logged in
if (isset($_SESSION['userloggedin'])) {
    // Retrieve user ID from session
    $userEmail = $_SESSION['userloggedin'];
    $sql = "SELECT UserID FROM userinfo WHERE Email = '$userEmail'";
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        echo "Error: " . mysqli_error($conn);
    } else {
        $row = mysqli_fetch_assoc($result);
        $userId = $row['UserID'];
        
        // Check if quantities were selected
        if (isset($_POST['quantity']) && !empty($_POST['quantity'])) {
            // Retrieve selected items and quantities
            $selectedItems = $_POST['quantity'];
            
            // Flag to track if order was successfully placed
            $orderPlaced = false;
            
            // Iterate through selected items and insert into orders table
            foreach ($selectedItems as $menuId => $quantity) {
                if ($quantity > 0) {
                    // Retrieve seat number from form
                    $seatNumber = $_POST['seat'];
                    
                    // Generate unique order ID (you can use any method you prefer)
                    $orderId = uniqid('order_');
                    
                    // Insert order into orders table
                    $query = "INSERT INTO orders (OrderId, UserId, menuId, quantity, seatNumber, orderDate) VALUES ('$orderId', '$userId', '$menuId', '$quantity', '$seatNumber', NOW())";
                    $result = mysqli_query($conn, $query);
                    
                    if ($result) {
                        $orderPlaced = true;
                    } else {
                        echo "Error: " . mysqli_error($conn);
                    }
                }
            }
            
            if ($orderPlaced) {
                echo "Order placed successfully! <br>";
                echo "<button onclick=\"window.location.href = 'homepage.php';\">OK</button>";
            } else {
                echo "Failed to place the order.";
            }
        } else {
            echo "No items selected.";
        }
    }
} else {
    echo "User not logged in.";
}

// Close the database connection
mysqli_close($conn);
?>
