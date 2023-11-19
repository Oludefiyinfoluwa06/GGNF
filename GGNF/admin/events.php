<?php

    include "./config/db_connect.php";

    $event_sql = "SELECT * FROM events";
    $event_result = mysqli_query($conn, $event_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GGNF | All events</title>
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

        /* Events */
        .events {
            padding: 30px;
        }

        .events h2 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 30px;
        }

        .event-card {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .event-card img {
            max-width: 100%;
            height: 200px;
            border-radius: 8px;
        }

        .event-info h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .event-info p {
            margin-bottom: 8px;
        }

        .event-info a {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 50px;
            background: #d40f0f;
            color: white;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .event-info a:hover {
            background: #8c0c0c;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logout {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .3rem;
            margin-top: -35px;
            color: #000;
            cursor: pointer;
        }

        @media screen and (max-width: 700px) {
            .events {
                text-align: center;
            }
            .event-card {
                justify-content: center;
                background: transparent;
                box-shadow: 0 0 0;
            }
            .event-info h3 {
                font-size: 20px;
            }
            .event-info p {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <!-- Events and Programs -->
    <div class="events" id="events">
        <div class="header">
            <h2>All Events</h2>
            <a href="logout.php" class="logout">
                <i class="fa fa-sign-out"></i>
                <p>Logout</p>
            </a>
        </div>

        <?php
        if ($event_result) {
            if (mysqli_num_rows($event_result) > 0) {
                while ($row = mysqli_fetch_assoc($event_result)) { ?>
                    <div class="event-card">
                        <img src="../client/<?php echo $row["event_img"] ?>" alt="<?php echo $row["event_title"] ?>">
                        <div class="event-info">
                            <h3><?php echo $row["event_title"] ?></h3>
                            <p>Date: <?php echo $row["event_date"] ?></p>
                            <p>Location: <?php echo $row["event_location"] ?></p>
                            <p>Description: <?php echo $row["event_description"] ?></p>
                        </div>
                    </div>
                <?php }
            } else { ?> 
                <p>There are no events</p>
            <?php }
        }     
        ?>
    </div>
</body>
</html>