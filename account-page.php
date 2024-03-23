<?php
//FINAL
session_start();

$conn = mysqli_connect("localhost", "root", "", "hbs");

$email = @$_SESSION['userloggedin'];
if (@$_SESSION['isAdmin'] == false) {
    $sql = "SELECT * FROM userinfo WHERE Email = '$email'";
    $result = mysqli_query($conn, $sql);
    $target = mysqli_fetch_assoc($result);

    //Get information in database
    $_SESSION['username'] = $target['UserName'];
    $_SESSION['gender'] = $target['Gender'];
    $_SESSION['phonenumber'] = $target['PhoneNumber'];
} else {
    $sql = "SELECT * FROM admininfo WHERE AdminEmail = '$email'";
    $result = mysqli_query($conn, $sql);
    $target = mysqli_fetch_assoc($result);

    //Get information in database
    $_SESSION['username'] = $target['AdminName'];
    $_SESSION['gender'] = $target['AdminGender'];
    $_SESSION['phonenumber'] = $target['AdminPhoneNum'];
}

if (!isset($_SESSION['userloggedin'])) {
    header("Location:  account-Login.php");
}

function GetRequestData($conn)
{
    $sql = "SELECT * FROM donationinfo WHERE UserEmail = '$_SESSION[userloggedin]' ORDER BY RequestID";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        //There is data in the table
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr style="
                    border: 2px black solid;
                    border-collapse: collapse;">
                    <td>' . $row['Address'] . '</td>
                    <td>' . $row['FoodbankName'] . '</td>
                    <td>' . $row['ItemType'] . '</td>
                    <td>' . $row['PreferredDate'] . '</td>
                    <td>' . $row['PreferredTime'] . '</td>
                    <td>' . $row['Status'] . '</td>
                </tr>';
        }
    } else {
        echo '<tr><td colspan="6">No Data</td>';
    }
}

// Function to get user's order details
function getUserOrders($conn)
{
    $userEmail = $_SESSION['userloggedin'];
    $sql = "SELECT * FROM orders WHERE UserId = (SELECT UserID FROM userinfo WHERE Email = '$userEmail')";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<h2>User's Orders</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Order ID</th><th>Menu ID</th><th>Quantity</th><th>Seat Number</th><th>Order Date</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['OrderId'] . "</td>";
            echo "<td>" . $row['menuId'] . "</td>";
            echo "<td>" . $row['quantity'] . "</td>";
            echo "<td>" . $row['seatNumber'] . "</td>";
            echo "<td>" . $row['orderDate'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No orders found for this user.</p>";
    }
}

// Function to get user's booking details
function getUserBookings($conn)
{
    $userEmail = $_SESSION['userloggedin'];
    $sql = "SELECT * FROM bookings WHERE UserId = (SELECT UserID FROM userinfo WHERE Email = '$userEmail')";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<h2>User's Bookings</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Booking ID</th><th>Menu ID</th><th>Quantity</th><th>Seat Number</th><th>Booking Date</th><th>Action</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['BookingId'] . "</td>";
            echo "<td>" . $row['menuId'] . "</td>";
            echo "<td>" . $row['quantity'] . "</td>";
            echo "<td>" . $row['seatNumber'] . "</td>";
            echo "<td>" . $row['bookDate'] . "</td>";
            // Add edit and delete buttons
            echo "<td><a href='edit-booking.php?id=" . $row['BookingId'] . "'>Edit</a> | <a href='delete-booking.php?id=" . $row['BookingId'] . "'>Delete</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No bookings found for this user.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Yummy Hotpot</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="Hotpot.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>

    <style>
        /* Your CSS styles */
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px; /* Add margin between tables */
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div>
        <header>
            <a href="homepage.php"><img class="logo" src="image/Hotpot-Logo.png" alt="Hotpot-Logo"></a>
            <a href="homepage.php">
                <h1>YUMMY HOTPOT</h1>
            </a>
        </header>


        <div class="topnav">
            <a href="homepage.php">Home</a>
            <a href="<?php echo isset($_SESSION['userloggedin']) ? "menu.php" : "account-login.php"; ?>">Menu</a>
            <a href="account-page.php" class="active"><?php echo isset($_SESSION['userloggedin']) ? "Logged in as " . $_SESSION['userloggedin'] : "Account"; ?></a>
            <?php if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']) { ?>
                <a href="admin-page.php" class="active" style="float: right;"><i class="fa fa-unlock-alt" aria-hidden="true"></i> ADMIN</a>
            <?php } ?>
        </div>
    </div>
    <center>
        <div class="content">

            <div class="content-container">
                <a href="homepage.php"><-Back to Home</a>
                <!--<img style="max-width: 24.5em; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);" src="<?php  echo $target['PictureName']   ?>">-->
                <h2>Welcome, <?php echo $_SESSION['username'] ?></h2>

                <table>
                    <tr>
                        <td>
                            <p>User Email:</p>
                        </td>
                        <td>
                            <p><?php echo $_SESSION['userloggedin'] ?></p>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <p>Gender:</p>
                        </td>
                        <td>
                            <p><?php echo $_SESSION['gender'] ?></p>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <p>Phone Number:</p>
                        </td>
                        <td>
                            <p><?php echo $_SESSION['phonenumber'] ?></p>
                        </td>
                    </tr>
                </table>
                <div style="margin-top: 2em;">
                    <a href="account-edit.php">Edit Information</a>
                </div>

                <!-- Display user's orders -->
                <?php getUserOrders($conn); ?>

                <!-- Display user's bookings -->
                <?php getUserBookings($conn); ?>

                <form action="Homepage.php" method="post" style="margin-top: 20px;">
                    <button type="submit" name="logout" value="logout">Log Out</button>
                </form>

            </div>
        </div>
    </center>

    <footer>

        <a>Copyright <?php echo date('Y'); ?> @ Yummy Hotpot</a>
    </footer>
</body>

</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
