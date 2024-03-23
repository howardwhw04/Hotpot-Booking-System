<?php
    session_start(); // Start the session if not started already

    // SQL Connection
    $conn = mysqli_connect("localhost", "root", "", "hbs");

    // Initialize Variables
    $menuId = isset($_POST['menuId']) ? $_POST['menuId'] : '';
    $itemName = isset($_POST['item_name']) ? $_POST['item_name'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $itemPrice = isset($_POST['price']) ? $_POST['price'] : '';
    $submitted = isset($_POST['submit']) ? $_POST['submit'] : '';

    $url = "admin-Page.php";

    // Function to process uploaded image
    function ImageProcess()
    {
        // Check if file is uploaded
        if(isset($_FILES['fileupload']))
        {
            $fileTmpPath = $_FILES['fileupload']['tmp_name'];
            $fileName = $_FILES['fileupload']['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

            $allowedExtension = array('jpg', 'gif', 'png', 'bmp', 'jpeg');

            if(in_array($fileExtension, $allowedExtension))
            {
                $uploadFileDir = "uploads/";

                $destPath = $uploadFileDir . $newFileName;

                if(move_uploaded_file($fileTmpPath, $destPath))
                {
                    return $destPath;
                }
                else
                {
                    // Error handling if file upload fails
                    return null;
                }
            }
            else
            {
                // Error handling for wrong file type
                return null;
            }
        }
        else
        {
            // Error handling if no file is sent
            return null;
        }
    }

    // Submitted form
    if($submitted == "submit")
    {
        $picName = ImageProcess();

        if($picName !== null) // Check if image processing was successful
        {
            // SQL query with prepared statement to prevent SQL injection
            $sql = "INSERT INTO menu (menuId, item_name, description, price, image_url) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);

            if($stmt)
            {
                // Bind parameters and execute the statement
                mysqli_stmt_bind_param($stmt, "sssss", $menuId, $itemName, $description, $itemPrice, $picName);
                $result = mysqli_stmt_execute($stmt);

                if($result)
                {
                    header("Location: " . $url);
                    exit();
                }
                else
                {
                    // Error handling if SQL execution fails
                    echo "Error: " . mysqli_error($conn);
                }
            }
            else
            {
                // Error handling if prepared statement creation fails
                echo "Error: " . mysqli_error($conn);
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
        else
        {
            // Error handling if image processing fails
            echo "Error processing image.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Menu Item</title>
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
        <a href="menu.php">Menu</a>
        <a href="account-page.php"><?php if (isset($_SESSION['userloggedin'])) {
                                        echo "Logged in as " . htmlspecialchars($_SESSION['userloggedin']);
                                    } else {
                                        echo "Account";
                                    } ?></a>
        <?php if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == true) { ?>
            <a href="admin-page.php" class="active" style="float: right;"><i class="fa fa-unlock-alt" aria-hidden="true"></i> ADMIN</a>
        <?php } ?>
    </div>

    <center>
        <div class="content">
            <div class="content-container">
                <form action="admin-menu-add.php" method="post" enctype="multipart/form-data">
                    <h2>Add a Menu Item</h2>
                    <table>
                        <tr>
                            <td><label for="menuId">Menu Id: </label></td>
                            <td><input type="text" name="menuId"></td>
                        </tr>

                        <tr>
                            <td><label for="item_name">Item Name: </label></td>
                            <td><input type="text" name="item_name"></td>
                        </tr>

                        <tr>
                            <td><label for="description">Description: </label></td>
                            <td><input type="text" name="description"></td>
                        </tr>

                        <tr>
                            <td><label for="price">Item Price: </label></td>
                            <td><input type="number" name="price"></td>
                        </tr>

                        <tr>
                            <td><label for="fileupload">Image of Foodbank:</label><br></td>
                            <td><input type="file" name="fileupload"></td>
                        </tr>

                    </table>

                    <button type="submit" name="submit" value="submit">Submit</button>
                </form>
            </div>
        </div>
    </center>
</body>
</html>
