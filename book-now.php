<?php
session_start();

// Establish a database connection
$conn = mysqli_connect("localhost", "root", "", "hbs");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to display selected items and quantities
function displaySelectedItems($conn, $selectedItems) {
    echo "<h2 style='text-align: center;'>Your Selected Items</h2>";
    echo "<table>";
    echo "<tr><th>Menu Id</th><th>Item Name</th><th>Price</th><th>Quantity</th><th>Total Price</th></tr>";

    $totalPrice = 0;

    foreach ($selectedItems as $menuId => $quantity) {
        if ($quantity > 0) {
            // Retrieve item details from the database based on menuId
            $query = "SELECT * FROM menu WHERE menuId = '$menuId'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);

            // Calculate total price for the item
            $itemPrice = $row['price'];
            $itemTotalPrice = $itemPrice * $quantity;
            $totalPrice += $itemTotalPrice;

            // Display item details along with quantity and total price
            echo "<tr>";
            echo "<td>{$row['menuId']}</td>";
            echo "<td>{$row['item_name']}</td>";
            echo "<td>{$itemPrice}</td>";
            echo "<td>{$quantity}</td>";
            echo "<td>" . number_format($itemTotalPrice, 2) . "</td>"; // Format the total price
            echo "</tr>";
        }
    }

    echo "<tr><td colspan='4' style='text-align:right;'><strong>Total</strong></td><td>" . number_format($totalPrice, 2) . "</td></tr>";
    echo "</table>";

    return $totalPrice;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Now</title>
    <style>
        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        td:last-child {
            text-align: right;
        }
        .user-details {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Booking Information</h1>
    
    <!-- Display user details -->
    <div class="user-details">
    <?php       
    // Fetch user details including User ID based on the email stored in the session
    if (isset($_SESSION['userloggedin'])) {
        $userEmail = $_SESSION['userloggedin'];
        $sql = "SELECT * FROM userinfo WHERE Email = '$userEmail'";
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            echo "Error: " . mysqli_error($conn);
            // You might want to handle this error more gracefully in a production environment
        } elseif (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $userId = $row['UserID']; // Fetch User ID
            $userName = $row['UserName'];
            $userGender = $row['Gender'];
            $userPhoneNumber = $row['PhoneNumber'];

            echo "<p>User ID: " . htmlspecialchars($userId) . "</p>"; // Display User ID
            echo "<p>User: " . htmlspecialchars($userName) . "</p>";
            echo "<p>Gender: " . htmlspecialchars($userGender) . "</p>";
            echo "<p>Phone Number: " . htmlspecialchars($userPhoneNumber) . "</p>";
        } else {
            echo "<p>User: Guest</p>";
        }
    } else {
        echo "<p>User: Guest</p>";
    }
    ?>
    </div>
    
    <form action="book-now-process.php" method="post">
        <?php 
        // Check if quantities were selected
        if (isset($_POST['quantity']) && !empty($_POST['quantity'])) {
            // Display selected items and quantities
            $selectedItems = $_POST['quantity'];
            $totalPrice = displaySelectedItems($conn, $selectedItems);
        } else {
            echo "<p style='text-align:center;'>No items selected.</p>";
        }
        ?>
        
        <!-- Option list for selecting a seat -->
        <div style="text-align: center; margin-top: 20px;">
            <!-- Select a Seat dropdown -->
            <div style="display: inline-block;">
                <label for="seat">Select a Seat:</label>
                <select name="seat" id="seat">
                    <?php
                    // Query to retrieve all available seats from the seat table that are not already occupied
                    $query = "SELECT * FROM seat WHERE SeatNumber NOT IN (SELECT seatNumber FROM orders)";
                    $result = mysqli_query($conn, $query);

                    // Check if query executed successfully
                    if ($result && mysqli_num_rows($result) > 0) {
                        // Iterate through each row in the result set
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Display seat number as an option in the select element
                            echo "<option value='" . htmlspecialchars($row['SeatNumber']) . "'>" . htmlspecialchars($row['SeatNumber']) . "</option>";
                        }
                    } else {
                        echo "<option value='' disabled>No seats available</option>";
                    }
                    ?>
                </select>
            </div>
            
            <!-- Select date -->
            <div style="display: inline-block; margin-left: 20px;">
                <label for="date">Select a Date:</label>
                <input type="date" name="bookDate" required>
            </div>
            
            <!-- Submit button -->
            <div style="margin-top: 10px;">
                <button type="submit">Book Now</button>
            </div>
        </div>
        
        <!-- Hidden input fields to send selected items and their quantities -->
        <?php
        if (isset($_POST['quantity'])) {
            foreach ($_POST['quantity'] as $menuId => $quantity) {
                echo "<input type='hidden' name='quantity[$menuId]' value='$quantity'>";
            }
        }
        ?>
    </form>
    
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
