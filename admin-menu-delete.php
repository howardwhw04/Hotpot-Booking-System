<?php

session_start();

//Establishing connection to the database
$conn = mysqli_connect("localhost", "root", "", "hbs");

// Check if user is logged in and is an admin
if (!(isset($_SESSION['userloggedin']) && isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'])) {
    // Redirect to login page or show unauthorized access message
    header("Location: unauthorized.php");
    exit();
}

// Check if menuId is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirect to menu page or show error message
    header("Location: menu.php");
    exit();
}

$menuId = $_GET['id'];

// Fetch menu details based on menuId
$sql = "SELECT * FROM menu WHERE menuId = '$menuId'";
$result = mysqli_query($conn, $sql);

// Check if menu exists
if (mysqli_num_rows($result) == 0) {
    // Redirect to menu page or show error message
    header("Location: menu.php");
    exit();
}

// Fetch menu details
$menuDetails = mysqli_fetch_assoc($result);

// Check if form is submitted for deletion confirmation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Delete menu item from the database
    $delete_sql = "DELETE FROM menu WHERE menuId = '$menuId'";
    if (mysqli_query($conn, $delete_sql)) {
        // Redirect to menu page or show success message
        header("Location: menu.php");
        exit();
    } else {
        // Show error message
        $error_message = "Error deleting menu item: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Menu Item</title>
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

<h2>Delete Menu Item</h2>

<?php if (isset($error_message)) { ?>
    <p><?php echo $error_message; ?></p>
<?php } ?>

<p style="text-align: center;">Are you sure you want to delete this menu item?</p>

<table border="1">
    <tr>
        <th>Menu ID</th>
        <th>Item Name</th>
        <th>Description</th>
        <th>Price</th>
        <th>Image</th>
    </tr>
    <tr>
        <td><?php echo $menuDetails['menuId']; ?></td>
        <td><?php echo $menuDetails['item_name']; ?></td>
        <td><?php echo $menuDetails['description']; ?></td>
        <td><?php echo $menuDetails['price']; ?></td>
        <td><img src="<?php echo $menuDetails['image_url']; ?>" alt="Menu Image"></td>
    </tr>
</table>

<form method="post">
    <input type="submit" name="confirm_delete" value="Confirm Delete">
    <a href="menu.php">Cancel</a>
</form>

</body>
</html>
