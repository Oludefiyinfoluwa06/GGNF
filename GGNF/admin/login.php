<?php

    session_start();
    if (isset($_SESSION["name"])) {
        header("Location: events.php");
        exit();
    }
    
    include "./config/db_connect.php";

    $name = $password = $input_error = "";

    if (isset($_POST["login"])) {
        $name = $_POST["name"];
        $password = $_POST["password"];

        $sql = "SELECT * FROM ggnf_login";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_array($result);
                if ($name == $row["name"]) {
                    if (password_verify($password, $row["password"])) {
                        $_SESSION["name"] = $row["name"];
                        header("Location: events.php");
                        exit();
                    } else {
                        $input_error = "Incorrect password";
                    }
                } else {
                    $input_error = "Incorrect username";
                }
            } else {
                $input_error = "Account does not exist";
            }

        } else {
            $input_error = "Server Error";
        }

    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GGNF | Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Quicksand:wght@300;400;500;600;700&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');

        * {
            margin: 0;
            padding: 0;
            text-decoration: none;
            box-sizing: border-box;
            list-style: none;
            font-family: "poppins";
        }

        h2 {
            font-size: 35px;
        }

        html {
            scroll-behavior: smooth;
        }

        form {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            width: 500px;
            border-radius: 20px;
            box-shadow: 2px 2px 5px #ccc,
                        -2px -2px 5px #ccc;
        }

        form h1 {
            text-align: center;
        }

        form .input-box {
            margin-top: 10px;
            display: flex;
            align-items: flex-start;
            justify-content: flex-start;
            flex-direction: column;
        }

        .input-box input, .input-box textarea {
            width: 100%;
            border: none;
            border-bottom: 2px solid #000;
            outline: none;
            margin-top: 10px;
        }

        .input-box textarea {
            min-width: 100%;
            max-width: 100%;
            min-height: 100px;
            max-height: 100px;
        }

        button {
            margin-top: 10px;
            width: 100%;
            padding: 9px;
            border-radius: 10px;
            background: #000;
            color: #fff;
            cursor: pointer;
            border: none;
        }

        @media screen and (max-width: 550px) {
            form {
                width: 350px;
            }
        }
    </style>
</head>
<body>
    <form action="" method="post">
        <h1>Login</h1>
        <p style="color: red; text-align: center;"><?php echo $input_error ?></p>
        <div class="input-box">
            <label for="name">Username</label>
            <input type="text" placeholder="Enter your username" name="name" id="name" required>
        </div>
        <div class="input-box">
            <label for="password">Password</label>
            <input type="password" placeholder="Enter your password" name="password" id="password" required>
        </div>
        <button type="submit" name="login">Login</button>
    </form>
</body>
</html>