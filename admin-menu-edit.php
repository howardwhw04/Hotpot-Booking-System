<?php

session_start();

//Establishing connection to the database
$conn = mysqli_connect("localhost" , "root" , "" ,  "hbs");

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

$row = mysqli_fetch_assoc($result);

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $item_name = $_POST['item_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Update menu details in the database
    $update_sql = "UPDATE menu SET item_name = '$item_name', description = '$description', price = '$price' WHERE menuId = '$menuId'";
    if (mysqli_query($conn, $update_sql)) {
        // Redirect to menu page or show success message
        header("Location: menu.php");
        exit();
    } else {
        // Show error message
        $error_message = "Error updating menu details: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu</title>
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

<h2>Edit Menu</h2>

<?php if (isset($error_message)) { ?>
    <p><?php echo $error_message; ?></p>
<?php } ?>

<form method="post">
    <label for="item_name">Item Name:</label><br>
    <input type="text" id="item_name" name="item_name" value="<?php echo $row['item_name']; ?>"><br>
    <label for="description">Description:</label><br>
    <textarea id="description" name="description"><?php echo $row['description']; ?></textarea><br>
    <label for="price">Price:</label><br>
    <input type="text" id="price" name="price" value="<?php echo $row['price']; ?>"><br><br>
    <input type="submit" value="Submit">
</form>

</body>
</html>
