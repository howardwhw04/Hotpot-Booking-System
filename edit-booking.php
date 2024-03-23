<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['userloggedin'])) {
    header("Location: account-login.php");
    exit();
}

// Check if BookingId is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirect to account-page.php if BookingId is not provided
    header("Location: account-page.php");
    exit();
}

// Establish a database connection
$conn = mysqli_connect("localhost", "root", "", "hbs");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve BookingId from URL parameter
$bookingId = $_GET['id'];

// Fetch booking details based on BookingId
$sql = "SELECT * FROM bookings WHERE BookingId = '$bookingId'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    // Redirect to account-page.php if no booking found with provided BookingId
    header("Location: account-page.php");
    exit();
}

// Fetch booking details
$booking = mysqli_fetch_assoc($result);

// Check if the booking belongs to the logged-in user
$userEmail = $_SESSION['userloggedin'];
$sql = "SELECT * FROM userinfo WHERE Email = '$userEmail'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

if ($user['UserID'] != $booking['UserId']) {
    // Redirect to account-page.php if the booking does not belong to the logged-in user
    header("Location: account-page.php");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $menuId = $_POST['menuId'];
    $quantity = $_POST['quantity'];
    $seatNumber = $_POST['seatNumber'];
    $bookDate = $_POST['bookDate'];

    // Update booking details in the database
    $sql = "UPDATE bookings SET menuId = '$menuId', quantity = '$quantity', seatNumber = '$seatNumber', bookDate = '$bookDate' WHERE BookingId = '$bookingId'";
    if (mysqli_query($conn, $sql)) {
        // Redirect to account-page.php after successful update
        header("Location: account-page.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Booking</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
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
    <div class="container">
        <h2>Edit Booking</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $bookingId; ?>" method="post">
            <div class="form-group">
                <label>Menu ID:</label>
                <input type="text" name="menuId" value="<?php echo $booking['menuId']; ?>"required>
            </div>
            <div class="form-group">
                <label>Quantity:</label>
                <input type="number" name="quantity" value="<?php echo $booking['quantity']; ?>"required>
            </div>
            <div class="form-group">
                <label>Seat Number:</label>
                <input type="text" name="seatNumber" value="<?php echo $booking['seatNumber']; ?>"required>
            </div>
            <div class="form-group">
                <label>Booking Date:</label>
                <input type="date" name="bookDate" value="<?php echo $booking['bookDate']; ?>" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Update Booking">
            </div>
        </form>
    </div>
</body>

</html>
