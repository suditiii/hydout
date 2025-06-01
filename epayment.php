<?php
// Start the session
session_start();




// Database connection
$conn = new mysqli('localhost', 'root', '', 'hydout');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}






// Retrieve values from POST request
$eid = isset($_POST['eid']) ? intval($_POST['eid']) : 0;
$num_people = isset($_POST['num_people']) ? intval($_POST['num_people']) : 1;
$eventDate = isset($_POST['eventDate']) ? $_POST['eventDate'] : '';
$eventTime = isset($_POST['eventTime']) ? $_POST['eventTime'] : '';
$finalCost = isset($_POST['final_cost']) ? floatval($_POST['final_cost']) : 0;


// Store them in session for later use
$_SESSION['eventId'] = $eid;
$_SESSION['num_people'] = $num_people;
$_SESSION['eventDate'] = $eventDate;
$_SESSION['eventTime'] = $eventTime;
$_SESSION['final_cost'] = $finalCost;






// Fetch event details
$sql = "SELECT title FROM events WHERE eid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $eventId);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Averia+Serif+Libre&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Averia+Serif+Libre&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Emilys+Candy&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Yeseva+One&display=swap" rel="stylesheet">
   
    <title>Secure Payment</title>
    <style>
        body {
            font-family: 'Averia Serif Libre', serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
           
            align-items: center;
            justify-content: center;
        }


        h3  {
            position: absolute;
            top: 1px;
            left: 20px;
            font-size: 1.5rem;
            font-weight: bold;
            color: #0A3E8C;  


        }


        .main-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #FFD700; /* Gold */
            padding: 20px 40px;
        }


        .main-header h1 {
            font-family: 'Emilys Candy', cursive;
            font-size: 2.5rem;
            color: #0A3E8C; /* Blue */
        }


        nav ul {
            display: flex;
            list-style: none;
        }


        nav ul li {
            margin-right: 25px;
        }


        nav ul li a {
            text-decoration: none;
            color: #333;
            font-weight: 700;
            font-size: 1.1rem;
        }


        nav ul li a:hover {
            color: #0A3E8C; /* Blue */
        }


        .payment-container {
            display: flex;
            background: white;
            width: 970px;
            height: 450px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }


        .payment-options {
            width: 35%;
            background: #0A3E8C;
            color: white;
            padding: 20px;
            align-items: center;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }


        .payment-options button {
            width: 100%;
            padding: 12px;
            border: none;
            background: #FFD700;
            font-family: 'Averia Serif Libre', serif;
            color: #0A3E8C;
            cursor: pointer;
            text-align: left;
            border-radius: 5px;
            font-weight: bold;
        }


        .payment-options button:hover {
            background: #6c757d;
        }


        .payment-form {
            width: 65%;
            padding: 20px;
            position: relative;
        }


        .payable-amount {
            position: absolute;
            top: 25px;
            right: 20px;
            font-size: 1.5rem;
            font-weight: bold;
            color: #0A3E8C;
        }


        .payment-form form {
            display: none;
        }


        .payment-form form.active {
            display: block;
        }


        .payment-form input {
            width: 100%;
            padding: 10px;
            margin: 10px -12px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }


        .pay-btn {
            background: #007bff;
            color: white;
            padding: 12px;
            border: none;
            width: 100%;
            cursor: pointer;
            border-radius: 5px;
        }


        .pay-btn:hover {
            background: #0056b3;
        }
        
        .otp-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            text-align: center;
            width: 300px;
        }
        .otp-popup h3 {
            margin-bottom: 10px;
            color: #0A3E8C;
        }
        .otp-popup input {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
            font-size: 1.2rem;
        }
        .otp-popup button {
            background: #007bff;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
        }
        .otp-popup button:hover {
            background: #0056b3;
        }
        .otp-timer {
            font-size: 1rem;
            color: red;
            margin-top: 5px;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        .main-footer {
        background-color: #0A3E8C; /* Blue */
        padding: 20px 40px;
        text-align: center;
        color: white;
    }

    .footer-content p {
        margin-bottom: 20px;
    }

    .footer-links {
        display: flex;
        justify-content: center;
        list-style: none;
        gap: 20px;
    }

    .footer-links li {
        margin-right: 20px;
    }

    .footer-links li a {
        text-decoration: none;
        color: white;
        font-weight: 700;
    }

    .footer-links li a:hover {
        color: #FFD700; /* Gold */
    }

    </style>
</head>
<body>
<header class="main-header">
    <div class="logo">
        <a href="home.php" style="text-decoration: none;"><h1 style="font-family: 'Emilys Candy', cursive;">HydOut</h1></a>
    </div>
    <nav>
        <ul class="nav-links">
        <li><a href="profile.php">Your Profile</a></li>
            <li><a href="wishlist.php">Wishlist</a></li>
            <li><a href="spotseeker1.php">SpotSeeker</a></li>
            <li><a href="package.php">Packages</a></li>
            <li><a href="events.php">Latest Events</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header><br><br><center>
    <div class="payment-container">
        <div class="payment-options">
            <br><br><br><br><br><br>
            <button onclick="showForm('card')">Credit/Debit Card</button>
            <button onclick="showForm('netbanking')">Net Banking</button>
            <button onclick="showForm('upi')">UPI</button>
        </div>
        <div class="payment-form">
            <div class="payable-amount">Total Amount: ₹<?php echo number_format($finalCost, 2); ?>0</div><br><br>
            <form id="card" action="process_epayment.php" method="POST">
    <input type="hidden" name="eid" value="<?php echo htmlspecialchars($eventId); ?>">
    <input type="hidden" name="num_people" value="<?php echo htmlspecialchars($num_people); ?>">
    <input type="hidden" name="final_cost" value="<?php echo htmlspecialchars($finalCost); ?>">
    <input type="text" name="card_number" placeholder="Card Number" required>
    <input type="text" name="expiry" placeholder="Expiry Date (MM/YY)" required>
    <input type="text" name="cvv" placeholder="CVV" required>
    <button type="submit" class="pay-btn" onclick="showOTP()">Pay Now</button>
</form>
<div class="overlay" id="overlay"></div>
    <div class="otp-popup" id="otpPopup">
        <h3>Enter OTP</h3>
        <p>An OTP has been sent to your registered mobile number.</p>
        <input type="text" id="otpInput" maxlength="6" placeholder="Enter OTP" required>
        <p class="otp-timer" id="otpTimer">Time left: 03:00</p>
        <button onclick="verifyOTP()">Submit</button>
    </div>
    <script>
        let selectedForm = null;
        let otpCountdown;
        
        function showOTP(formId) {
            selectedForm = document.getElementById(formId);
            document.getElementById('otpPopup').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('otpInput').value = '';
            startOTPTimer();
        }
        
        function startOTPTimer() {
            let timeLeft = 180;
            const timerDisplay = document.getElementById('otpTimer');
            timerDisplay.innerText = `Time left: 03:00`;
            clearInterval(otpCountdown);
            
            otpCountdown = setInterval(() => {
                let minutes = Math.floor(timeLeft / 60);
                let seconds = timeLeft % 60;
                timerDisplay.innerText = `Time left: ${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                if (timeLeft <= 0) {
                    clearInterval(otpCountdown);
                    alert('OTP expired. Please try again.');
                    closeOTP();
                }
                timeLeft--;
            }, 1000);
        }
        
        function verifyOTP() {
            const otp = document.getElementById('otpInput').value;
            if (otp.length === 6) {
                clearInterval(otpCountdown);
                alert('OTP Verified! Proceeding with payment.');
                closeOTP();
                if (selectedForm) {
                    selectedForm.submit();
                }
            } else {
                alert('Invalid OTP. Please enter a 6-digit OTP.');
            }
        }
        
        function closeOTP() {
            document.getElementById('otpPopup').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }
        </script>

            <form id="netbanking" class="active" action="process_epayment.php" method="POST">
                <h3>Net Banking</h3>
                <input type="hidden" name="eid" value="<?php echo htmlspecialchars($eventId); ?>">
    <input type="hidden" name="num_people" value="<?php echo htmlspecialchars($num_people); ?>">
    <input type="hidden" name="final_cost" value="<?php echo htmlspecialchars($finalCost); ?>">
                <input type="text" name="bank_name" placeholder="Bank Name" required>
                <input type="hidden" name="payment_method" value="netbanking">
                <button type="submit" class="pay-btn">Proceed to Bank</button>
            </form>
            <form id="upi" action="process_epayment.php" method="POST">
                <h3>UPI Payment</h3>
                <br>
                <img src="upi.jpeg" height="140px" width="140px">
                <input type="hidden" name="eid" value="<?php echo htmlspecialchars($eventId); ?>">
    <input type="hidden" name="num_people" value="<?php echo htmlspecialchars($num_people); ?>">
    <input type="hidden" name="final_cost" value="<?php echo htmlspecialchars($finalCost); ?>">
                <input type="text" name="upi_id" placeholder="UPI ID" required>
                <input type="hidden" name="payment_method" value="upi">
                <button type="submit" class="pay-btn">Pay via UPI</button>
            </form>
        </div>
    </div>
    <script>
        function showForm(formId) {
            document.querySelectorAll('.payment-form form').forEach(form => {
                form.classList.remove('active');
            });
            document.getElementById(formId).classList.add('active');
        }
    </script></center>
    <br><br>
    <footer class="main-footer">
    <div class="footer-content">
        <p>© 2024 HydOut. All rights reserved.</p>
        <nav>
            <ul class="footer-links">
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact Us</a></li>
                <li><a href="privacy.php">Privacy Policy</a></li>
            </ul>
        </nav>
    </div>
</footer>
</body>
</html>



