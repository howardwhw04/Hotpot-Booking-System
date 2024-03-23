<?php
session_start();

// Establish database connection
$conn = mysqli_connect("localhost", "root", "", "hbs");

// Check if user is logged in
if (!isset($_SESSION['userloggedin'])) {
    header("Location: account-login.php");
    exit();
}

// Check if BookingId is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: account-page.php");
    exit();
}

$bookingId = $_GET['id'];

// Check if the logged-in user has permission to delete this booking
$userEmail = $_SESSION['userloggedin'];
$sql = "SELECT * FROM bookings WHERE BookingId = '$bookingId' AND UserId = (SELECT UserID FROM userinfo WHERE Email = '$userEmail')";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    // The user doesn't have permission to delete this booking
    header("Location: account-page.php");
    exit();
}

// Get booking details
$bookingDetails = mysqli_fetch_assoc($result);

// Get menu details for the booking
$menuId = $bookingDetails['menuId'];
$menuSql = "SELECT * FROM menu WHERE menuId = '$menuId'";
$menuResult = mysqli_query($conn, $menuSql);
$menuDetails = mysqli_fetch_assoc($menuResult);

// Check if form is submitted for deletion confirmation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Delete the booking from the database
    $delete_sql = "DELETE FROM bookings WHERE BookingId = '$bookingId'";
    if (mysqli_query($conn, $delete_sql)) {
        // Booking deleted successfully
        header("Location: account-page.php");
        exit();
    } else {
        // Error occurred while deleting booking
        $error_message = "Error deleting booking: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Booking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        h2 {
            margin-top: 20px;
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
        }

        th, td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        img {
            max-width: 100px;
            height: auto;
        }

        form {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

<h2>Delete Booking</h2>

<?php if (isset($error_message)) { ?>
    <p><?php echo $error_message; ?></p>
<?php } ?>

<p style="text-align:center;">Are you sure you want to delete this booking?</p>

<table>
    <tr>
        <th>Order ID</th>
        <th>Menu ID</th>
        <th>Menu Name</th>
        <th>Menu Image</th>
        <th>Quantity</th>
        <th>Seat Number</th>
        <th>Booking Date</th>
    </tr>
    <tr>
        <td><?php echo $bookingDetails['BookingId']; ?></td>
        <td><?php echo $bookingDetails['menuId']; ?></td>
        <td><?php echo $menuDetails['item_name']; ?></td>
        <td><img src="<?php echo $menuDetails['image_url']; ?>" alt="<?php echo $menuDetails['item_name']; ?>" width="100"></td>
        <td><?php echo $bookingDetails['quantity']; ?></td>
        <td><?php echo $bookingDetails['seatNumber']; ?></td>
        <td><?php echo $bookingDetails['bookDate']; ?></td>
    </tr>
</table>

<form method="post">
    <input type="submit" name="confirm_delete" value="Confirm Delete">
    <a href="account-page.php">Cancel</a>
</form>

</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
