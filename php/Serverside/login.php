<?php
// Start session
session_start();

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$message = "";

//follows the registration database  (qsn 6 )

// Process login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        $message = "Connection failed: " . $conn->connect_error;
    } else {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and bind
        $stmt = $conn->prepare("SELECT email, password FROM registrations WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            // Verify password
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_email'] = $row['email'];
                $message = "Login successful! Redirecting to dashboard in 3 seconds...";
                header("Refresh: 3; url=dashboard.php"); // Redirect to a dashboard page
            } else {
                $message = "Invalid password!";
            }
        } else {
            $message = "Email not found!";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
             color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 17px;
            background-color: #1b0bd2;
        }

        button:hover {
            background-color: #45a049;
        }

        #error-message, #server-message {
            color: red;
            margin-top: 10px;
            text-align: center;
        }

        #server-message.success {
            color: green;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login Form</h2>
        <form id="loginForm" method="POST" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit">Login</button>
        </form>
        <div id="error-message"></div>
        <div id="server-message" class="<?php echo $message && strpos($message, 'successful') !== false ? 'success' : ''; ?>">
            <?php echo $message; ?>
        </div>
        <p style="text-align: center; margin-top: 10px;">
            Don't have an account? <a href="registration.php">Register here</a>
        </p>
    </div>

    <script>
        function validateForm() {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const errorMessage = document.getElementById('error-message');
            
            errorMessage.textContent = '';
            
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!emailPattern.test(email)) {
                errorMessage.textContent = 'Please enter a valid email address';
                return false;
            }
            
            if (password.length < 6) {
                errorMessage.textContent = 'Password must be at least 6 characters long';
                return false;
            }
            
            return true;
        }
    </script>
</body>
</html>