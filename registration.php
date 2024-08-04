<?php
session_start();
require_once 'utils/connect.php'; // Ensure this file contains the connection to your database

// Initialize error and success messages
$error_message = '';
$success_message = '';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = trim($_POST['fullName']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);
    $phone = trim($_POST['phone_number']);
    $bio = trim($_POST['bio']);
    $userType = trim($_POST['userType']);
    $preferences = isset($_POST['preferences']) ? implode(", ", $_POST['preferences']) : "";
    $gender = isset($_POST['gender']) ? $_POST['gender'] : "";

    // Basic validation
    $errors = [];
    if (empty($fullName)) {
        $errors[] = "Full Name is required.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid Email is required.";
    }
    if (empty($username)) {
        $errors[] = "Username is required.";
    }
    if (empty($password) || strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[^\w]/', $password)) {
        $errors[] = "Password must be at least 8 characters long, contain at least one capital letter, and one special character.";
    }
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }
    if (empty($userType)) {
        $errors[] = "User Type is required.";
    }
    if (empty($gender)) {
        $errors[] = "Gender is required.";
    }

    if (empty($errors)) {
        // Hash the password before saving it
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and bind parameters
        $stmt = $conn->prepare("INSERT INTO registration (full_name, email, username, password, phone_number, bio, user_type, preferences, gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        if ($stmt === false) {
            $error = "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        } else {
            $stmt->bind_param("sssssssss", $fullName, $email, $username, $passwordHash, $phone, $bio, $userType, $preferences, $gender);
            
            if ($stmt->execute()) {
                $success_message = "Registration successful!";
            } else {
                $error = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            }
            
            $stmt->close();
        }
    } else {
        $error = implode("<br>", $errors);
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petfinder Registration</title>
    <link rel="stylesheet" href="css/registration.css">
    <style>
        .logo img {
            height: 50px;
            border-radius: 50%;
        }

       #footer {
            background-color: #333;
            color: #fcf8fb;
            padding: 40px 20px;
            display: flex;
            flex-wrap: wrap;
        }

        .footer-section {
            flex: 1 1 300px;
            margin-bottom: 30px;
        }

        .section-content {
            padding: 0 20px;
        }

        .section-content h2 {
            font-size: 24px;
            margin-bottom: 15px;
            color: #f393ec;
        }

        .section-content p,
        .section-content ul {
            color: #f393ec;
            line-height: 1.6;
        }

        .section-content ul {
            padding: 0;
            list-style: none;
        }

        .section-content ul li {
            margin-bottom: 10px;
        }

        .section-content ul li a {
            color: #f393ec;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .section-content ul li a:hover {
            color: #f393ec;
        }

        .social-icons {
            display: flex;
            align-items: center; /* Center align items vertically */
        }

        .social-icons a {
            margin-right: 15px;
            transition: transform 0.3s ease; /* Smooth transition for hover effect */
        }

        .social-icons a:last-child {
            margin-right: 0; /* Remove margin-right from the last child to prevent extra space */
        }

        .social-icons a img {
            width: 30px; /* Set a specific width for the icons */
            height: 30px; /* Set a specific height for the icons */
            border-radius: 50%; /* Make the icons circular */
            transition: opacity 0.3s ease; /* Smooth transition for opacity */
        }

        .social-icons a:hover {
            transform: translateY(-3px); /* Move the icon up slightly on hover */
        }

        .social-icons a:hover img {
            opacity: 0.7; /* Reduce opacity of the icon on hover */
        }


        .footer-bottom {
            text-align: center;
            padding: 15px 0;
            background-color: #a93ca9;
            color: #e9a2db;
            font-size: 14px;
            width: 100%;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
        }
        .success-message {
            color: green;
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <img src="images/logo2.jpg" alt="Petfinder Logo">
                <h1>Petfinder</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="Dashboard.php">Home</a></li>
                    <li><a href="#">Tentang Kami</a></li>
                    <li><a href="Login.php">Login</a></li>
                    <li><a href="#">Kontak Kami</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <section class="hero">
            <h1>Join Us in Saving Stray Dogs</h1>
            <p>Register to become a part of our community dedicated to rescuing and caring for stray dogs.</p>
        </section>
        <section class="registration-form">
            <h2>Registration Information</h2>
            <p>Fill in your details to get started.</p>
            <?php
            if (!empty($error)) {
                echo '<p class="error">' . $error . '</p>';
            }
            if (!empty($success_message)) {
                echo '<p class="success-message">' . $success_message . '</p>';
            }
            ?>
            <form method="POST" action="registration.php" onsubmit="return validateForm()">
                <div class="form-group">
                    <label for="fullName">Full Name</label>
                    <input type="text" id="fullName" name="fullName" placeholder="Enter your full name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Choose a username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter the password" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone_number" name="phone_number" placeholder="Enter your phone number">
                </div>
                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" placeholder="Write a short bio about yourself"></textarea>
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <div class="radio-group">
                        <label><input type="radio" name="gender" value="Male"> Male</label>
                        <label><input type="radio" name="gender" value="Female"> Female</label>
                        <label><input type="radio" name="gender" value="Other"> Other</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>User Type</label>
                    <div class="radio-group">
                        <label><input type="radio" name="userType" value="General user">General User</label>
                        <label><input type="radio" name="userType" value="Dog Lover"> Dog Lover</label>
                        <label><input type="radio" name="userType" value="Shelter">Shelter</label>
                        <label><input type="radio" name="userType" value="Vet clinic">Vet Clinic</label>
                        <label><input type="radio" name="userType" value="Foster"> Foster</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Preferences</label>
                    <div class="checkbox-group">
                        <label><input type="checkbox" name="preferences[]" value="Email"> Email</label>
                        <label><input type="checkbox" name="preferences[]" value="SMS"> SMS</label>
                        <label><input type="checkbox" name="preferences[]" value="Push notifications"> Push notifications</label>
                    </div>
                </div>
                <button type="submit">Submit</button>
            </form>
        </section>
    </main>
    <div id="footer">
        <div class="footer-section">
            <div class="section-content about">
                <h2>Tentang Kami</h2>
                <p>Petfinder berdedikasi untuk membantu anjing liar menemukan rumah yang penuh kasih sayang dan memberikan dukungan kepada pemilik hewan peliharaan yang membutuhkan. Bergabunglah bersama kami dalam membuat perbedaan.</p>
            </div>
        </div>
        <div class="footer-section">
            <div class="section-content contact">
                <h2>Kontak Kami</h2>
                <ul>
                <li><a href="mailto:kurnia.10121238@mahasiswa.unikom.ac.id">kurnia.10121238@mahasiswa.unikom.ac.id</a></li>
                <li>+94 123 456 789</li>
                    <li>123 Pet Rescue St, Springfield, Western Province, Sri Lanka</li>
                </ul>
            </div>
        </div>
        <div class="footer-section">
            <div class="section-content social">
                <h2>Follow Kami</h2>
                <div class="social-icons">
                    <a href="#"><img src="images/facebook.jpg" alt="Facebook"></a>
                    <a href="#"><img src="images/twitter.jpg" alt="Twitter"></a>
                    <a href="#"><img src="images/instagram.jpg" alt="Instagram"></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
        &copy; 2024 Petfinder. All Rights Reserved.
        </div>
   </div>
    <script>
        function validateForm() {
            let fullName = document.getElementById('fullName').value.trim();
            let email = document.getElementById('email').value.trim();
            let username = document.getElementById('username').value.trim();
            let password = document.getElementById('password').value.trim();
            let confirmPassword = document.getElementById('confirmPassword').value.trim();

            let errorMessages = [];

            if (!fullName) {
                errorMessages.push("Full Name is required.");
            }
            if (!email) {
                errorMessages.push("Email is required.");
            }
            if (!username) {
                errorMessages.push("Username is required.");
            }
            if (!password) {
                errorMessages.push("Password is required.");
            }
            if (password.length < 8) {
                errorMessages.push("Password must be at least 8 characters long.");
            }
            if (!/[A-Z]/.test(password)) {
                errorMessages.push("Password must contain at least one capital letter.");
            }
            if (!/[^\w]/.test(password)) {
                errorMessages.push("Password must contain at least one special character.");
            }
            if (password !== confirmPassword) {
                errorMessages.push("Passwords do not match.");
            }

            if (errorMessages.length > 0) {
                alert(errorMessages.join("\n"));
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
