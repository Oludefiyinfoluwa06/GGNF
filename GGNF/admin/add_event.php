<?php

    session_start();
    if (!isset($_SESSION["name"])) {
        header("Location: login.php");
        exit();
    }

    include "./config/db_connect.php";

    $title = $date = $location = $desc = $flyer = "";
    $error = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_event"])) {
        // Validate and sanitize form inputs
        $title = mysqli_real_escape_string($conn, $_POST["title"]);
        $date = mysqli_real_escape_string($conn, $_POST["date"]);
        $location = mysqli_real_escape_string($conn, $_POST["location"]);
        $desc = mysqli_real_escape_string($conn, $_POST["desc"]);

        // Validate and handle file upload
        if (isset($_FILES["flyer"])) {
            $uploadDir = "../client/assets/";
            $filename = basename($_FILES["flyer"]["name"]);
            $flyer_destination = $uploadDir . $filename;

            if (move_uploaded_file($_FILES["flyer"]["tmp_name"], $flyer_destination)) {
                // Insert data into the database
                $sql = "INSERT INTO events (event_img, event_title, event_date, event_location, event_description) VALUES ('$flyer_destination', '$title', '$date', '$location', '$desc')";

                if (mysqli_query($conn, $sql)) {
                    header("Location: event_success.php?event added successfully");
                    exit();
                } else {
                    $error = "Error: " . mysqli_error($conn);
                }
            } else {
                $error = "Failed to upload the flyer.";
            }
        } else {
            $error = "Please select a file.";
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
    <form action="" method="post" enctype="multipart/form-data">
        <h1>Add Event</h1>
        <p style="color: red; text-align: center;"><?php echo $error; ?></p>
        <div class="input-box">
            <label for="title">Event Title</label>
            <input type="text" placeholder="Enter a title" name="title" id="title" required>
        </div>
        <div class="input-box">
            <label for="date">Event Date</label>
            <input type="date" placeholder="Enter a date" name="date" id="date" required>
        </div>
        <div class="input-box">
            <label for="location">Event Location</label>
            <input type="text" placeholder="Enter the location" name="location" id="location" required>
        </div>
        <div class="input-box">
            <label for="desc">Event Description</label>
            <textarea placeholder="Describe your event" name="desc" id="desc" required></textarea>
        </div>
        <div class="input-box">
            <label for="flyer">Event Flyer</label>
            <input type="file" name="flyer" id="flyer" required>
        </div>
        <button type="submit" name="add_event">Add Event</button>
    </form>
</body>
</html>