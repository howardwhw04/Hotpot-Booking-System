<?php
session_start();
// Establish a database connection
$conn = mysqli_connect("localhost", "root", "", "hbs");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to list food menu items with input fields for quantities
function ListMenu($conn) {
    // Query to fetch menu items from the database
    $query = "SELECT * FROM menu ORDER BY menuId";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Output data of each row
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>
                    <td><h3>' . $row['menuId'] . '</h3></td>
                    <td><img src="' . $row['image_url'] . '" alt="menu item" style="width: 150px;"></td>
                    <td><p>' . $row['item_name'] . '</p></td>
                    <td><p>' . $row['description'] . '</p></td>            
                    <td><p>' . $row['price'] . '</p></td>
                    <td><input type="number" name="quantity[' . $row['menuId'] . ']" value="0" min="0" style="width: 50px;"></td>
                </tr>';
        }
    } else {
        echo "No menu items available.";
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
    </head>

    <body>
        <div>
            <header>
                <a href="homepage.php"><img class="logo" src="image/Hotpot-Logo.png" alt="Hotpot-Logo"></a>
                <a href="homepage.php"><h1>YUMMY HOTPOT</h1></a>
            </header>
        </div>

        <div class="topnav">
            <a href="homepage.php">Home</a>
            <a href="menu.php" class="active">Menu</a>
            <a href="account-page.php"><?php if (isset($_SESSION['userloggedin'])) {
                                            echo "Logged in as " . htmlspecialchars($_SESSION['userloggedin']);
                                        } else {
                                            echo "Account";
                                        } ?></a>
            <?php if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == true) { ?>
                <a href="admin-page.php" class="active" style="float: right;"><i class="fa fa-unlock-alt" aria-hidden="true"></i> ADMIN</a>
            <?php } ?>
        </div>

        <style>
            table{
                margin-left: 20%;
                margin-right: 20%
            }
            tr{
                

            }
            th, td {
                    padding: 8px;
                    border-bottom: 1px solid #ddd;
                    text-align: left;
                }

        </style>
        <div class="content">
            <div class="content-container">
                <h2>Menu</h2>
                <form action="order-confirmation.php" method="post">
                    <table>
                        <tr>
                            <th>Menu Id</th>
                            <th></th>
                            <th>Item Name</th>
                            <th>Description</th>
                            <th>Price(RM)</th>
                            <th>Quantity</th>
                        </tr>
                        <?php ListMenu($conn) ?> 
                        
                    </table>
                    <center>
                        <button type="submit" name="pay-now" formaction="pay-now.php">Pay Now</button>
                        <button type="submit" name="book-now" formaction="book-now.php">Book Now</button>
                    </center>
                                    
                </form>
            </div>
        </div>
       
        <footer>
            <a>Copyright <?php echo date('Y'); ?> @ Yummy Hotpot</a>
        </footer>

    </body>
</html>
