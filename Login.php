<?php
session_start();
require_once 'utils/connect.php'; // Adjust this to your actual connection file

// Initialize error and success messages
$error_message = '';
$success_message = '';

// Check if form was submitted for login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    // Check if username and password are set
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Get form data
        $user = $_POST['username'];
        $pass = $_POST['password'];

        // Prepare and execute query
        $sql = "SELECT * FROM registration WHERE username=? OR email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $user, $user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($pass, $row['password'])) {
                $_SESSION['user'] = $row['username'];
                $stmt->close();
                $conn->close();
                header("Location: Dashboard.php");
                exit();
            } else {
                // Invalid password
                $_SESSION['error_message'] = 'Invalid password. Please try again.';
            }
        } else {
            // User not found
            $_SESSION['error_message'] = 'User does not exist.';
        }

        $stmt->close();
    } else {
        // Username or password not set
        $_SESSION['error_message'] = 'Please enter username and password.';
    }
}

// Check if form was submitted for forgot password
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['forgot_password'])) {
    // Check if email is set
    if (isset($_POST['email'])) {
        // Get form data
        $email = $_POST['email'];

        // Prepare and execute query
        $sql = "SELECT * FROM registration WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $password = $row['password'];

            // Send email with password
            $to = $email;
            $subject = "Your Petfinder Password";
            $message = "Your password is: " . $password;
            $headers = "From: no-reply@Petfinder.com";

            if (mail($to, $subject, $message, $headers)) {
                $_SESSION['success_message'] = 'Password sent to your email address.';
            } else {
                $_SESSION['error_message'] = 'Failed to send email. Please try again later.';
            }
        } else {
            // Email not found
            $_SESSION['error_message'] = 'Email not found.';
        }

        $stmt->close();
    } else {
        // Email not set
        $_SESSION['error_message'] = 'Please enter your email address.';
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petfinder - Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <img src="images/logo2.jpg" alt="Petfinder">
                <h1>Petfinder</h1>
            </div>
            <nav>
                <a href="index.php">Home</a>
                <a href="#">Tentang Kami</a>
            </nav>
        </div>
    </header>
    <main>
        <div class="welcome-section">
            <div class="welcome-text">
                <img src="images/paw.jpg" alt="Paw Logo">
                <div>
                    <h2>Welcome Back!</h2>
                    <p>Login to access your account</p>
                </div>
            </div>
            <div class="buttons">
                <button class="forgot-password" onclick="showForgotPassword()">Forgot Password</button>
                <a href="Registration.php" class="sign-up">Create Account</a>
            </div>
        </div>
        <div class="login-section">
            <img src="images/ella.jpg" alt="Dog Image">
            <div class="login-form">
                <h2>Login</h2>
                <?php
                if (isset($_SESSION['error_message']) && $_SESSION['error_message'] != '') {
                    echo '<p class="error">' . $_SESSION['error_message'] . '</p>';
                    unset($_SESSION['error_message']);
                }
                if (isset($_SESSION['success_message']) && $_SESSION['success_message'] != '') {
                    echo '<p class="success">' . $_SESSION['success_message'] . '</p>';
                    unset($_SESSION['success_message']);
                }
                ?>
                <form method="POST" action="login.php">
                    <input type="hidden" name="login" value="1">
                    <label for="username">Username/Email</label><br />
                    <input type="text" id="username" name="username" placeholder="Username or email" required><br />
                    <label for="password">Password</label><br />
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <span id="toggle-password" onclick="togglePassword()">Show/Hide password</span>
                    <br /><br />
                    <button type="submit">Login</button>
                    <p class="error" id="error"></p>
                </form>
                <div class="create-account">
                    <p>Don't have an account? <a href="Registration.php">Create account</a></p>
                </div>
            </div>
        </div>
        <div class="forgot-password-section" style="display: none;">
            <h2>Forgot Password</h2>
            <form method="POST" action="login.php">
                <input type="hidden" name="forgot_password" value="1">
                <label for="email">Enter your email address</label><br />
                <input type="email" id="email" name="email" placeholder="Enter your email" required><br /><br />
                <button type="submit">Send Password</button>
                <p class="error" id="error"></p>
                <p class="success" id="success"></p>
            </form>
        </div>
    </main>
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const togglePassword = document.getElementById('toggle-password');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                togglePassword.innerText = 'Hide password';
            } else {
                passwordField.type = 'password';
                togglePassword.innerText = 'Show password';
            }
        }

        function showForgotPassword() {
            document.querySelector('.login-section').classList.add('slide-out');
            document.querySelector('.forgot-password-section').classList.add('slide-in');
            setTimeout(() => {
                document.querySelector('.login-section').classList.remove('active', 'slide-out');
                document.querySelector('.forgot-password-section').classList.add('active');
            }, 500);
        }

    </script>
</body>
</html>