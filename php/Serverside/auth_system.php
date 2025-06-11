<?php
// Start session
session_start();

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";
//qsn no 9 complete authentications 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

// Handle registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $faculty = $_POST['faculty'];
    $address = $_POST['address']; 

    $stmt = $conn->prepare("INSERT INTO authentication (name, email, password, phone, gender, faculty, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $email, $password, $phone, $gender, $faculty, $address);

    if ($stmt->execute()) {
        $message = "Registration successful! Please log in.";
        $action = 'login';
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;

    $stmt = $conn->prepare("SELECT email, password, name, address, faculty FROM authentication WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Store session data
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_address'] = $row['address'];
            $_SESSION['user_faculty'] = $row['faculty'];
            $_SESSION['login_time'] = date('Y-m-d H:i:s');

            // Store cookies if "Remember Me" is checked (expire in 7 days)
            if ($remember) {
                setcookie('user_email', $row['email'], time() + (7 * 24 * 60 * 60), "/");
                setcookie('user_name', $row['name'], time() + (7 * 24 * 60 * 60), "/");
                setcookie('user_address', $row['address'], time() + (7 * 24 * 60 * 60), "/");
                setcookie('user_faculty', $row['faculty'], time() + (7 * 24 * 60 * 60), "/");
            }

            $message = "Login successful!";
            $action = 'dashboard';
        } else {
            $message = "Invalid password!";
        }
    } else {
        $message = "Email not found!";
    }

    $stmt->close();
}

// Handle logout
if ($action === 'logout') {
    // Destroy session
    session_unset();
    session_destroy();

    // Destroy cookies
    setcookie('user_email', '', time() - 3600, "/");
    setcookie('user_name', '', time() - 3600, "/");
    setcookie('user_faculty', '', time() - 3600, "/");

    $message = "Logged out successfully!";
    $action = 'login';
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication System</title>
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
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
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
        input, select {
            width: 100%;
            padding: 8px;
            border: 1.2px solid rgba(0, 179, 255, 0.8);
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color:rgb(88, 43, 160);
            color: white;
            border: 2px solid rgb(124,244,256);
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .data-item {
            margin-bottom: 15px;
        }
        .label {
            font-weight: bold;
        }
        #server-message {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
        #server-message.success {
            color: green;
        }
        .link {
            text-align: center;
            margin-top: 10px;
        }
        .link a {
            color: #4CAF50;
            text-decoration: none;
        }
        .link a:hover {
            text-decoration: underline;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            white-space: nowrap;
        }
        .checkbox-group label {
            display: inline-flex;
            align-items: center;
            font-size: 12px;
            cursor: pointer;
            margin: 0;
        }
        .checkbox-group input {
            margin-right: 5px;
            margin-left: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($action === 'register'): ?>
            <!-- Registration Form -->
            <h2>Register</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="faculty">Faculty:</label>
                    <input type="text" id="faculty" name="faculty" required>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" required> <!-- New Address Field -->
                </div>
                <button type="submit" name="register">Register</button>
            </form>
            <div class="link">
                <a href="?action=login">Already have an account? Login here</a>
            </div>

        <?php elseif ($action === 'login'): ?>
            <!-- Login Form -->
            <h2>Login</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($_COOKIE['user_email']) ? htmlspecialchars($_COOKIE['user_email']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group checkbox-group">
                    <label style="font-size: 12px;">
                        <input type="checkbox" name="remember"> Remember Me
                    </label>
                </div>
                <button type="submit" name="login">Login</button>
            </form>
            <div class="link">
                <a href="?action=register">Don't have an account? Register here</a>
            </div>

        <?php elseif ($action === 'dashboard' && isset($_SESSION['user_email'])): ?>
            <!-- Dashboard -->
            <h2>Dashboard</h2>
            <div class="data-item">
                <span class="label">Email:</span> <?php echo htmlspecialchars($_SESSION['user_email']); ?>
            </div>
            <div class="data-item">
                <span class="label">Name:</span> <?php echo htmlspecialchars($_SESSION['user_name']); ?>
            </div>
            <div class="data-item">
                <span class="label">Faculty:</span> <?php echo htmlspecialchars($_SESSION['user_faculty']); ?>
            </div>
            <div class="data-item">
                <span class="label">Login Time:</span> <?php echo htmlspecialchars($_SESSION['login_time']); ?>
            </div>
            <div class="data-item">
                <span class="label">Address:</span> <?php echo htmlspecialchars($_SESSION['user_address'] ?? 'Not available'); ?> <!-- Optional Address -->
            </div>
            <div class="link">
                <a href="?action=logout">Logout</a>
            </div>

        <?php else: ?>
            <!-- Redirect to login if not authenticated -->
            <?php
            $action = 'login';
            $message = "Please log in to access the dashboard.";
            ?>
            <h2>Login</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($_COOKIE['user_email']) ? htmlspecialchars($_COOKIE['user_email']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group checkbox-group">
                    <label>
                        <input type="checkbox" name="remember" style="margin-right: 5px;"> Remember Me
                    </label>
                </div>
                <button type="submit" name="login">Login</button>
            </form>
            <div class="link">
                <a href="?action=register">Don't have an account? Register here</a>
            </div>
        <?php endif; ?>

        <div id="server-message" class="<?php echo $message && strpos($message, 'successful') !== false ? 'success' : ''; ?>">
            <?php echo $message; ?>
        </div>
    </div>
</body>
</html>