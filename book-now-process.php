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
            $bookDate = $_POST['bookDate']; // Collect selected booking date
            
            // Flag to track if booking was successfully placed
            $bookingPlaced = false;
            
            // Iterate through selected items and insert into bookings table
            foreach ($selectedItems as $menuId => $quantity) {
                if ($quantity > 0) {
                    // Retrieve seat number from form
                    $seatNumber = $_POST['seat'];
                    
                    // Generate unique booking ID (you can use any method you prefer)
                    $bookingId = uniqid('booking_');
                    
                    // Insert booking into bookings table
                    $query = "INSERT INTO bookings (BookingId, UserId, menuId, quantity, seatNumber, bookDate) VALUES ('$bookingId', '$userId', '$menuId', '$quantity', '$seatNumber', '$bookDate')";
                    $result = mysqli_query($conn, $query);
                    
                    if ($result) {
                        $bookingPlaced = true;
                    } else {
                        echo "Error: " . mysqli_error($conn);
                    }
                }
            }
            
            if ($bookingPlaced) {
                echo "Booking placed successfully! Please pay at the counter within 72 hours from the booking date.<br>";
                echo "<button onclick=\"window.location.href = 'homepage.php';\">OK</button>";
            } else {
                echo "Failed to place the booking.";
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
