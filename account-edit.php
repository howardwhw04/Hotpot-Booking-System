<?php
    //Essential stuff when loading in
    session_start();
    $conn = mysqli_connect("localhost" , "root" , "" ,  "hbs");
    $url = "account-page.php";
    $error = "account-edit.php?error=contnum";

    //Initialize variables
    $email = $_SESSION['userloggedin'];
    $submit = @$_POST['submit'];

    //Checks if it is submitted, if so submit user details changes to database

    if($submit == "submit")
    {
        //Get values from form
        $editusername = !empty($_POST['editusername']) ? $_POST['editusername'] : $_SESSION['username'];
        $editgender = $_POST['editgender'] ? $_POST['editgender'] : $_SESSION['gender'];
        $editphoneNumber = $_POST['editphonenumber'] ? $_POST['editphonenumber'] : $_SESSION['phonenumber'];

        if(!preg_match('/^\d{10,11}|\s$/' , $editphoneNumber))
        {
            header("Location: " . $error);
            die();
        }
        //Update tables
        if($_SESSION['isAdmin'] == false) //USER SIDED
        {
            $sql = "UPDATE userinfo SET UserName = '$editusername' , Gender = '$editgender' , PhoneNumber = '$editphoneNumber' WHERE Email = '$email'";
        }
        else //ADMIN SIDED
        {
            $sql = "UPDATE admininfo SET AdminName = '$editusername' , AdminGender = '$editgender' , AdminPhoneNum = '$editphoneNumber' WHERE AdminEmail = '$email'";
        }

        mysqli_query($conn , $sql);
        header("Location: " . $url);
        
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
            <a href="account-page.php" class="active"><?php echo isset($_SESSION['userloggedin']) ? "Logged in as " . $_SESSION['userloggedin'] : "Account"; ?></a>
            <a href="admin-page.php" class="active" style="float: right; <?php echo isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] ? "" : "display: none"; ?>"><i class="fa fa-unlock-alt" aria-hidden="true"></i> ADMIN</a>
        </div>
    </div>

    <center>
        <div class="content">
            <div class="content-container">
                <form action="" method="post">
                    <h2>Editing User Details:</h2>

                    <table>
                        <tr>
                            <td><label for="editusername">Username: </label></td>
                            <td><input type="text" name="editusername" value="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>"></td>
                        </tr>
                        <tr>
                            <td><label for="editphonenumber">Phone Number: </label></td>
                            <td><input type="text" name="editphonenumber" value="<?php echo isset($_SESSION['phonenumber']) ? $_SESSION['phonenumber'] : ''; ?>"></td>
                        </tr>
                        <tr>
                            <td><label for="editgender">Gender <?php echo isset($_SESSION['gender']) ? $_SESSION['gender'] : ''; ?>: </label></td>
                            <td>
                                <input type="radio" name="editgender" value="male" <?php echo isset($_SESSION['gender']) && $_SESSION['gender'] == 'male' ? 'checked' : ''; ?>> <label for="male">Male</label><br>
                                <input type="radio" name="editgender" value="female" <?php echo isset($_SESSION['gender']) && $_SESSION['gender'] == 'female' ? 'checked' : ''; ?>> <label for="female">Female</label><br></td>
                        </tr>
                    </table>

                    <?php
                    if (isset($_GET['error']) && $_GET['error'] == 'contnum') {
                        echo "<span style='color:red'>Contact Number is invalid</span><br>";
                    }
                    ?>

                    <button type="submit" name="submit" value="submit">Submit</button>
                </form>

            </div>
        </div>    
    </center>

    <footer>
        <a>Copyright <?php echo date('Y'); ?> @ Yummy Hotpot</a>
    </footer>
</body>

</html>
