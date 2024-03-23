<?php

    session_start();

    //Establishing connection to the database
    $conn = mysqli_connect("localhost" , "root" , "" ,  "hbs");

    //Initialization of variables
    $listNumber = 1;

    function GetMenuData($conn)
    {
        $sql = "SELECT * FROM menu ORDER BY menuId";
        $result = mysqli_query($conn, $sql);

        $editUrl = "admin-menu-edit.php?id=";
        $deleteUrl = "admin-menu-delete.php?id=";

        if(mysqli_num_rows($result) > 0)
        {
            //There is data in the table
            while($row = mysqli_fetch_assoc($result))
            {
                echo '<tr>
                        <td>' . $row['menuId'] . '</td>
                        <td>' . $row['item_name'] . '</td>
                        <td>' . $row['description'] . '</td>
                        <td>' . $row['price'] . "</td>
                        <td>
                            <a href=" . $editUrl . $row['menuId']  . ">Edit |</a>
                            <a href=". $deleteUrl . $row['menuId'] .">Delete</a>
                        </td>    
                    </tr>";
            }
        }
    }

    // Function to retrieve order data
    function GetOrderData($conn)
    {
        $sql = "SELECT * FROM orders";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) > 0)
        {
            // Display order data
            while($row = mysqli_fetch_assoc($result))
            {
                echo '<tr>
                        <td>' . $row['OrderId'] . '</td>
                        <td>' . $row['UserId'] . '</td>
                        <td>' . $row['menuId'] . '</td>
                        <td>' . $row['quantity'] . '</td>
                        <td>' . $row['seatNumber'] . '</td>
                        <td>' . $row['orderDate'] . '</td>
                    </tr>';
            }
        }
    }

    // Function to retrieve booking data
    function GetBookingData($conn)
    {
        $sql = "SELECT * FROM bookings";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) > 0)
        {
            // Display booking data
            while($row = mysqli_fetch_assoc($result))
            {
                echo '<tr>
                        <td>' . $row['BookingId'] . '</td>
                        <td>' . $row['UserId'] . '</td>
                        <td>' . $row['menuId'] . '</td>
                        <td>' . $row['quantity'] . '</td>
                        <td>' . $row['seatNumber'] . '</td>
                        <td>' . $row['bookDate'] . '</td>
                    </tr>';
            }
        }
    }

 
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Yummy Hotpot</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="hotpot.css">
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
                <a href="homepage.php"><h1>YUMMY HOTPOT</h1></a>
            </header>
                

            <div class="topnav">
                <a href="homepage.php">Home</a>
                <a href="<?php echo isset($_SESSION['userloggedin']) ? "menu.php" : "account-login.php"; ?>">Menu</a>
                <a href="account-page.php"><?php echo isset($_SESSION['userloggedin']) ? "Logged in as " . $_SESSION['userloggedin'] : "Account"; ?></a>
                <a href="admin-page.php"  class="active" style="float: right; <?php echo isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] ? "" : "display: none"; ?>"><i class="fa fa-unlock-alt" aria-hidden="true"></i> ADMIN</a>
            </div>
        </div>

        <center>
        <div class="content">
            <div class="content-container">
                <!-- Menu Table -->
                <table>
                    <caption>Menu Infomation</caption>
                    <tr>
                        <th>Menu Id</th>
                        <th>Item Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>

                    <?php
                        GetMenuData($conn);
                    ?>
                    
                    <div class="add">
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td bgcolor="">
                                <div class="add">
                                    <a href="admin-menu-add.php">+ ADD</a>
                                </div>
                            </td>   
                        </tr>
                    </div>
                </table>

                <!-- Order table -->
                <table>
                    <caption>Order Information</caption>
                    <tr>
                        <th>Order Id</th>
                        <th>User Id</th>
                        <th>Menu Id</th>
                        <th>Quantity</th>
                        <th>Seat Number</th>
                        <th>Order Date</th>
                    </tr>
                    <?php GetOrderData($conn); ?>
                </table>

                <!-- Booking table -->
                <table>
                    <caption>Booking Information</caption>
                    <tr>
                        <th>Booking Id</th>
                        <th>User Id</th>
                        <th>Menu Id</th>
                        <th>Quantity</th>
                        <th>Seat Number</th>
                        <th>Booking Date</th>
                    </tr>
                    <?php GetBookingData($conn); ?>
                </table>
            </div>
        </div>      
        </center>

        <footer>
            <a>Copyright <?php echo date('Y'); ?> @ Yummy Hotpot</a>
        </footer> 
        
    </body>
</html>