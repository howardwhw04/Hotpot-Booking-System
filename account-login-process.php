<?php
    //FINAL
    session_start();
        
    $conn = mysqli_connect("localhost" , "root" , "" ,  "hbs");

    $email = $_POST['Email'];
    $password = $_POST['psw'];

    $return = "account-login.php?error=";
    $adminUrl = "admin-page.php";
    $url = "homepage.php";



    //Admin Searching Database
    $sql = "SELECT * FROM admininfo WHERE AdminEmail = '$email'";

    $result = mysqli_query($conn, $sql);
    $target = mysqli_fetch_assoc($result);

    if($target['AdminEmail'] == $email && $target['AdminPassword'] == $password && isset($email))
    {
        $_SESSION['userloggedin'] = $email;
        $_SESSION['username'] = $target['UserName'];
        $_SESSION['gender'] = $target['Gender'];
        $_SESSION['phoneNumber'] = $target['AdminPhoneNum'];

        //Identify admin privilege
        $_SESSION['isAdmin'] = true;
        
        //Redirect
        header("Location: " . $adminUrl);
        mysqli_close($conn);
        

        die();
    }


    //User Database Checking
    $sql = "SELECT * FROM userinfo WHERE Email = '$email'";

    $result = mysqli_query($conn, $sql);
    $target = mysqli_fetch_assoc($result);

    //IF that email does not exist OR password is correct
    if($target['Email'] != $email || $target['Password'] != $password)
    {
        header("Location: " . $return . "error");
        mysqli_close($conn);
        die();
    }

    # localhost/ASSIGNMENT/
    //Give values to session
    $_SESSION['userloggedin'] = $email;
    $_SESSION['username'] = $target['username'];
    $_SESSION['phoneNumber'] = $target['PhoneNumber'];
    $_SESSION['gender'] = $target['Gender'];

    header("Location: " . $url);

?>