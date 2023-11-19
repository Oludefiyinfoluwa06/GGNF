<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
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

        .success {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .success i {
            font-size: 50px;
            color: green;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 5px solid green;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            margin: auto;
        }
    </style>
</head>
<body>
    <div class="success">
        <div class="check">
            <i class="fa fa-check"></i>
        </div>
        <h2>Donation Successful</h2>
        <p>Thank you for your donation</p>
        <p>Back to <a href="index.php">Homepage</a></p>
    </div>
</body>
</html>