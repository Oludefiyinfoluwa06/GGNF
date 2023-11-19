<?php

    include "./config/db_connect.php";

    $event_sql = "SELECT * FROM events";
    $event_result = mysqli_query($conn, $event_sql);

    if (isset($_POST["pay"])) {
        $curl = curl_init();

        $email = $_POST["email"];
        $amount = $_POST["amount"] * 100;  //the amount in kobo. This value is actually NGN 300

        // url to go to after payment
        $callback_url = 'http://localhost/php_projects/ggnf/client/payment_success.php';  

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'amount'=>$amount,
                'email'=>$email,
                'callback_url' => $callback_url
            ]),
            CURLOPT_HTTPHEADER => [
                "authorization: Bearer sk_live_a8af1e438570351e8f6afdbf4ecc3c6dae84f8a0",
                "content-type: application/json",
                "cache-control: no-cache"
            ],
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err){
            die('Curl returned error: ' . $err);
        }

        $tranx = json_decode($response, true);

        if(!$tranx['status']){
            print_r('API returned error: ' . $tranx['message']);
        }

        print_r($tranx);
        header('Location: ' . $tranx['data']['authorization_url']);
    }

    if (isset($_POST["test"])) {
        $name = mysqli_real_escape_string($conn, $_POST["name"]);
        $testimonial = mysqli_real_escape_string($conn, $_POST["testimonial"]);

        $test_sql = "INSERT INTO testimonials (name, testimonial) VALUES ('$name', '$testimonial')";
        $test_result = mysqli_query($conn, $test_sql);

        if ($test_result) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error";
        }

    }
    
    $testimonial_sql = "SELECT * FROM testimonials ORDER BY created_at DESC LIMIT 3";
    $testimonial_result = mysqli_query($conn, $testimonial_sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GGNF - Good God Never Fails</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- <script src="https://js.paystack.co/v1/inline.js"></script> -->
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

        /* Navbar */
        #navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 5px 30px;
            border-bottom: 2px solid #d40f0f;
            position: fixed;
            top: 0;
            left: 0;
            background: white;
            width: 100vw;
            z-index: 100;
        }

        #navbar img {
            width: 50px;
            height: 50px;
        }

        #navbar ul {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 2rem;
            text-transform: uppercase;
        }

        #navbar ul li a {
            color: black;
        }

        #navbar ul li a:hover {
            color: #d40f0f;
            transition: .5s;
        }

        #navbar .menu {
            display: none;
            cursor: pointer;
        }

        /* Welcome */
        .welcome {
            background: linear-gradient(rgba(0,0,0,0.65), rgba(0,0,0,0.65)),url('./assets/children.jpg') no-repeat center/cover;
            height: 500px;
            color: #fff;
            display: flex;
            align-items: flex-start;
            justify-content: flex-start;
            flex-direction: column;
            padding: 90px 50px;
        }


        .welcome h2 {
            margin-top: 50px;
        }

        .welcome p {
            width: 450px;
            margin-top: 17px;
            font-size: 20px;
            line-height: 30px;
        }

        .welcome a {
            padding: 10px 16px;
            margin-top: 17px;
            border-radius: 50px;
            background: #d40f0f;
            color: white;
        }

        /* About Us & What we do */
        .about, .wwd {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 3rem;
            margin-top: 40px;
            padding: 25px;
        }

        .about .about-text, .wwd .wwd-text {
            width: 600px;
        }

        .about .about-text p, .wwd .wwd-text p {
            line-height: 25px;
            margin-top: 20px;
        }

        .about img, .wwd img {
            width: 350px;
            height: 300px;
            object-fit: contain;
            object-position: center;
            border-radius: 14px;
        }

        .wwd {
            flex-wrap: wrap-reverse;
        }

        .wwd img {
            object-fit: cover;
        }

        /* Mission, Vision and Values */
        .miss-vis {
            width: 100%;
            margin-top: 14px;
            padding: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 4rem;
        }

        .miss-vis .mission, .miss-vis .vision, .miss-vis .values {
            width: 350px;
            text-align: center;
            border-radius: 15px;
            box-shadow: 3px 3px 20px #ddd,
                        -3px -3px 20px #ddd;
            margin-top: 30px;
            padding: 20px;
        }

        .miss-vis h1 {
            text-transform: uppercase;
        }

        .miss-vis p {
            font-size: 17px;
            margin-top: 10px;
            line-height: 25px;
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

        /* Call to Action */
        .cta {
            background-color: #d40f0f;
            color: white;
            padding: 50px;
            text-align: center;
        }

        .cta-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .cta h2 {
            font-size: 30px;
            margin-bottom: 20px;
        }

        .cta p {
            font-size: 17px;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .cta-button {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 50px;
            border: none;
            outline: none;
            background: white;
            color: #d40f0f;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.3s ease;
            cursor: pointer;
        }

        .cta-button:hover {
            background: #fff3f3;
            color: #d40f0f;
        }

        /* Testimonials */
        .testimonials {
            text-align: center;
            padding: 50px;
        }

        .testimonial-cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .testimonial {
            max-width: 300px;
            background: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
            text-align: left;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .testimonial img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            object-position: center;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .stars {
            color: #ffd700;
            margin-top: 10px;
        }

        .testimonial-content {
            width: 100%;
            padding-top: 20px;
            padding-bottom: 20px;
        }

        /* Contact */
        .contact-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .contact-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .contact-details {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .location, .email, .call {
            text-align: center;
            margin-bottom: 15px;
        }

        form {
            margin-top: 20px;
        }

        .input-box {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input, textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        textarea {
            max-width: 100%;
            height: 150px;
        }

        input[type="submit"] {
            background-color: #d40f0f;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-transform: uppercase;
        }

        /* Footer */
        .footer {
            width: 100%;
            text-align: center;
            padding: 20px;
            background: #d40f0f;
            color: #fff;
        }

        .footer a {
            color: #007aff;
        }

        .feedback i {
            z-index: 200;
        }

        .feedback i.fa-comments {
            position: fixed;
            bottom: 30px;
            right: 50px;
            cursor: pointer;
            font-size: 25px;
            color: #d40f0f;
            background: #ff6666;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            border-radius: 50%;
        }

        .feedback i.fa-close {
            position: fixed;
            bottom: 30px;
            right: 50px;
            cursor: pointer;
            font-size: 25px;
            color: #d40f0f;
            background: #ff6666;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 10px 12.5px;
            border-radius: 50%;
        }

        /* Donate Form */
        .donate-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: none;
        }

        form.donate-form {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            width: 60%;
            padding: 30px;
            border-radius: 20px;
        }

        form.donate-form .form-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 20px;
        }

        .form-header legend {
            font-weight: bold;
        }

        .form-header i {
            cursor: pointer;
        }

        .donate-form p {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 14px 0;
        }

        input, textarea {
            outline: none;
        }

        input:focus, textarea:focus {
            border: 2px solid #d40f0f;
        }

        .donate-form button {
            width: 100%;
            padding: 8px;
            border-radius: 8px;
            background: #d40f0f;
            color: #fff;
            outline: none;
            border: none;
            cursor: pointer;
        }

        /* Tesimonial Form */
        .test-form {
            position: fixed;
            bottom: 100px;
            right: 50px;
            background: rgba(0, 0, 0, 0.35);
            backdrop-filter: blur(7px);
            padding: 20px;
            color: #fff;
            width: 400px;
            border-radius: 20px;
            z-index: 101;
            display: none;
        }

        .test-form input {
            width: 100%;
        }

        .test-form textarea {
            max-width: 360px;
            max-height: 150px;
            min-width: 360px;
            min-height: 150px;
        }

        .test-form button {
            width: 100%;
            padding: 7px;
            background: #d40f0f;
            border-radius: 10px;
            border: none;
            color: #fff;
            text-transform: uppercase;
            cursor: pointer;
        }

        /* Media queries for responsiveness */
        @media screen and (max-width: 600px) {
            .contact-details {
                flex-direction: column;
            }

            .location, .email, .call {
                width: 100%;
            }
            .test-form {
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.6);
                border-radius: 0;
                backdrop-filter: blur(0);
            }
            .test-form form {
                width: 80%;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: #fff;
                color: #000;
                padding: 20px;
                border-radius: 20px;
            }
            .test-form form textarea {
                max-width: 100%;
                max-height: 80px;
                min-width: 100%;
                min-height: 80px;
            }
        }

        /* Media query for responsive design */
        @media screen and (max-width: 500px) {
            .testimonial {
                max-width: 100%;
            }
        }
        @media screen and (max-width: 700px) {
            #navbar ul {
                position: absolute;
                top: 65px;
                background-color: rgba(255, 255, 255, 0.35);
                backdrop-filter: blur(6px);
                right: -100%;
                align-items: flex-end;
                justify-content: flex-start;
                flex-direction: column;
                padding: 30px;
                height: calc(100vh - 60px);
                transition: .5s;
                width: 200px;
            }
            #navbar .menu {
                display: flex;
                align-items: center;
                justify-content: flex-end;
            }
            #navbar .menu i.fa-close {
                display: none;
            }
            .welcome {
                width: 100%;
                justify-content: center;
            }
            .welcome h2 {
                margin: auto;
                text-align: center;
                margin-bottom: -7px;
                font-size: 25px;
            }
            .welcome p {
                font-size: 14px;
                line-height: 25px;
                width: 100%;
                text-align: center;
                margin-bottom: 10px;
            }
            .welcome a {
                margin: auto;
            }
            .about .about-text, .wwd .wwd-text {
                text-align: center;
            }
            .about img, .wwd img {
                width: 100%;
            }
            .events {
                text-align: center;
            }
            .event-card {
                justify-content: center;
                background: transparent;
                box-shadow: 0 0 0;
            }
            .cta {
                padding: 30px;
            }
            .cta h2 {
                font-size: 20px;
            }

            .cta p {
                font-size: 15px;
            }
        }

        @media screen and (max-width: 1200px) {
            .about, .wwd {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar section -->
    <section id="navbar">
        <img src="./assets/logo.jpg" alt="">
        <ul class="nav-links">
            <li><a href="#">Home</a></li>
            <li><a href="#about">About</a></li>
            <?php if (mysqli_num_rows($event_result) > 0): ?>
                <li><a href="#events">Events</a></li>
            <?php endif; ?>
            <?php if (mysqli_num_rows($testimonial_result) > 0): ?>
                <li><a href="#testimonials">Testimonials</a></li>
            <?php endif; ?>
            <li><a href="#contact">Contact us</a></li>
        </ul>
        <div class="menu">
            <i class="fa fa-bars"></i>
            <i class="fa fa-close"></i>
        </div>
    </section>

    <!-- Main Content -->
    <section id="content">
        <!-- Welcome container -->
        <div class="welcome">
            <h2>GGNF Foundation</h2> 
            <p>At GGNF, we believe in the magic of every child's potential. We are here to create a world where every child feels loved, supported, and empowered to dream big. Here, we embark on exciting adventures of learning, growth, and compassion.</p>
            <a href="#about">Know More</a>
        </div>

        <!-- About Us -->
        <div class="about" id="about">
            <div class="about-text">
                <h2>About Us</h2>
                <p>Welcome to GGNF, where every child's journey begins with love, laughter, and limitless possibilities. We are passionate about creating a world where every child, regardless of background, has the opportunity to thrive. Our foundation was founded on the belief that by nurturing the unique talents and dreams of each child, we contribute to building a brighter, more compassionate future for all. What sets us apart is our commitment to fostering a supportive and inclusive environment. We strive to provide educational resources, healthcare initiatives, and a loving community that empowers children to discover their strengths and reach for the stars.</p>
            </div>
            <img src="./assets/logo.jpg" alt="Children">
        </div>

        <!-- What we do -->
        <div class="wwd" id="wwd">
            <img src="./assets/what-we-do.jpg" alt="Children">
            <div class="wwd-text">
                <h2>What we do</h2>
                <p>At GGNF, we're on a mission to positively impact children's lives. Through educational empowerment, health and wellness programs, community building, and special projects, we aim to create a world where every child's dreams can flourish. Our commitment is to provide access to quality education, promote well-being, foster a supportive community, and spark creativity. Join us in making a lasting difference in the lives of our little ones.</p>
            </div>
        </div>

        <!-- Mission, Vision and Values -->
        <div class="miss-vis">
            <div class="mission">
                <img src="./assets/mission.png" alt="Mission" width="100">
                <h1>Our Mission</h1>
                <p>Our mission is simple yet profound - to empower every child with the tools they need to thrive. We are committed to fostering an environment of education, health, and community support, laying the foundation for a brighter future filled with possibilities.</p>
            </div>
            <div class="vision">
                <img src="./assets/vision.png" alt="Vision" width="100">
                <h1>Our Vision</h1>
                <p>Our vision is a world where every child's potential is recognized, nurtured, and celebrated. We envision a future where children grow into confident, compassionate individuals, equipped with the skills to make a positive impact on their communities and beyond.</p>
            </div>
            <div class="values">
                <img src="./assets/values.png" alt="Values" width="100">
                <h1>Our Values</h1>
                <p>Rooted in love, compassion, and inclusivity, our values guide everything we do. We believe in the transformative power of education, the importance of health and wellness, the strength of community, and the joy found in creativity.</p>
            </div>
        </div>

        <!-- Events and Programs -->
        <div class="events" id="events">
            <h2>Our Events</h2>

            <?php 
                if ($event_result) {
                    while ($row = mysqli_fetch_assoc($event_result)) { ?>
                        <div class="event-card">
                            <img src="<?php echo $row["event_img"] ?>" alt="<?php echo $row["event_title"] ?>">
                            <div class="event-info">
                                <h3><?php echo $row["event_title"] ?></h3>
                                <p>Date: <?php echo $row["event_date"] ?></p>
                                <p>Location: <?php echo $row["event_location"] ?></p>
                                <p>Description: <?php echo $row["event_description"] ?></p>
                            </div>
                        </div>
                    <?php }
                }        
            ?>
        </div>

        <!-- Call to Action -->
        <div class="cta">
            <div class="cta-content">
                <h2>Support Our Cause</h2>
                <p>Your contribution can make a significant impact. Help us continue our mission by making a donation today.</p>
                <button type="button" class="cta-button">Donate now</button>
            </div>
        </div>

        <!-- Testimonials -->
        <?php if ($testimonial_result) { 
            while ($row = mysqli_fetch_assoc($testimonial_result)) { ?>
                <div class="testimonials" id="testimonials">
                    <h2>Testimonials</h2>
                    <div class="testimonial-content">
                        <div class="testimonial-cards">
                            <div class="testimonial">
                                <i class="fa fa-quote-left" style="font-size: 30px;"></i>
                                <h3><?php echo $row["name"] ?></h3>
                                <div class="testimonial-content">
                                    <p style="margin-bottom: 0px"><?php echo $row["testimonial"] ?></p>
                                    <div class="stars">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } 
            } ?>

        <!-- Contact -->
        <div class="contact-container" id="contact">
            <h2>Contact Us</h2>
            <p style="text-align: center; margin-bottom: 20px">We're delighted to connect with you. Whether you have questions, ideas, or just want to say hello, we'd love to hear from you.</p>
            <div class="contact">
                <div class="contact-details">
                    <div class="location">
                        <i class="fa fa-map-marker"></i>
                        <div class="location-text">
                            <h3>Location</h3>
                            <p>Abuja, FCT</p>
                        </div>
                    </div>
                    <div class="email">
                        <i class="fa fa-envelope"></i>
                        <div class="email-text">
                            <h3>Email</h3>
                            <p>coggnf@gmail.com</p>
                        </div>
                    </div>
                    <div class="call">
                        <i class="fa fa-phone"></i>
                        <div class="call-text">
                            <h3>Call</h3>
                            <p>+234 800 000 0000</p>
                        </div>
                    </div>
                </div>
                <form action="https://formsubmit.io/send/39b9fae6-d311-440d-9db3-42ab6579901c" id="contact-form">
                    <input name="_redirect" type="hidden" id="name" value="http://localhost/php_projects/ggnf/client/index.php">
                    <div class="input-box">
                        <label for="name">Your name</label>
                        <input type="text" name="name" id="name" required>
                    </div>
                    <div class="input-box">
                        <label for="email">Your email</label>
                        <input type="email" name="email" id="email" required>
                    </div>
                    <div class="input-box">
                        <label for="subject">Subject</label>
                        <input type="text" name="subject" id="subject" required>
                    </div>
                    <div class="input-box">
                        <label for="message">Message</label>
                        <textarea name="message" id="message" required></textarea>
                    </div>
                    <input type="submit" value="Submit">
                </form>
            </div>
        </div>
    </section>

    <!-- Donate Form -->
    <section class="donate-container">
        <form action="" method="post" class="donate-form">
            <div class="form-header">
                <legend>Donation Form</legend>
                <i class="fa fa-close" id="close-donate-form"></i>
            </div>
            <p>Give a helping hand to those who need it</p>
            <div class="input-box">
                <label for="amount">Amount</label>
                <input type="number" name="amount" id="amount" placeholder="Enter an amount" required>
            </div>
            <div class="input-box">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" placeholder="Enter your name" required>
            </div>
            <div class="input-box">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter your email address" required>
            </div>
            <button name="pay" onclick="payWithPaystack()">Donate now</button>
        </form>
    </section>
    
    <!-- Footer section -->
    <section class="footer" id="footer">
        <p>&copy; 2023 GGNF Foundation. All Rights Reserved | Designed by <a href="https://oludefiyin.web.app/">Ofto Technologies</a></p>
    </section>

    <!-- Testimonial Form Section -->
    <section class="test-form" id="test-form">
        <form action="" method="post">
            <h3 style="text-align: center; font-size: 20px; text-transform: uppercase">Give Feedback</h3>
            <p style="text-align: center; font-size: 15px; margin: 7px 0;">We're delighted to know what you say about us</p>
            <div class="input-box">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="input-box">
                <label for="testimonial">Your testimony</label>
                <textarea name="testimonial" id="testimonial" required></textarea>
            </div>
            <button name="test">Submit</button>
        </form>
    </section>

    <div class="feedback">
        <i class="fa fa-comments" title="Feedback"></i>
        <i class="fa fa-close" id="close-comment" title="Close"></i>
    </div>

    <script>
        const menuBar = document.querySelector('.fa-bars');
        const closeIcon = document.querySelector('.fa-close');
        const navLinks = document.querySelector('.nav-links');

        menuBar.addEventListener('click', () => {
            closeIcon.style.display = 'block';
            menuBar.style.display = 'none';
            navLinks.style.right = 0;
        });
        
        closeIcon.addEventListener('click', () => {
            closeIcon.style.display = 'none';
            menuBar.style.display = 'block';
            navLinks.style.right = '-100%';
        });

        const openComment = document.querySelector('.fa-comments');
        const closeComment = document.querySelector('#close-comment');
        const testimonialForm = document.querySelector('#test-form');

        openComment.addEventListener('click', () => {
            closeComment.style.display = 'flex';
            openComment.style.display = 'none';
            testimonialForm.style.display = 'block';
        });
        
        closeComment.addEventListener('click', () => {
            closeComment.style.display = 'none';
            openComment.style.display = 'flex';
            testimonialForm.style.display = 'none';
        });
        
        const showDonateForm = document.querySelector('.cta-button');
        const closeDonateForm = document.querySelector('#close-donate-form');
        const donateContainer = document.querySelector('.donate-container');

        showDonateForm.addEventListener('click', () => {
            donateContainer.style.display = 'block';
        });

        closeDonateForm.addEventListener('click', () => {
            donateContainer.style.display = 'none';
        });

        const custEmail = document.getElementById('email');
        const custName = document.getElementById('name');
        const amount = document.getElementById('amount');
    </script>
</body>
</html>