<?php
// Handle form submission (store cookie data)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['set_cookies'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $faculty = $_POST['faculty'];

    // Set cookies (expire in 1 hour)
    setcookie('user_name', $name, time() + 3600, "/");
    setcookie('user_email', $email, time() + 3600, "/");
    setcookie('user_faculty', $faculty, time() + 3600, "/");
    setcookie('set_time', date('Y-m-d H:i:s'), time() + 3600, "/");

    $message = "Cookies set successfully!";
}

// Handle cookie deletion (destroy cookie data)
if (isset($_POST['delete_cookies'])) {
    // Set cookies to expire in the past to delete them
    setcookie('user_name', '', time() - 3600, "/");
    setcookie('user_email', '', time() - 3600, "/");
    setcookie('user_faculty', '', time() - 3600, "/");
    setcookie('set_time', '', time() - 3600, "/");

    $message = "Cookies deleted successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cookie Manager</title>
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
            border: 3px solid #252b60;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
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
            border: 1.2px solid rgba(0, 179, 255, 0.8);
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color:rgb(88, 43, 160);
            color: white;
            border: none;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Cookie Manager</h2>

        <?php if (!isset($_COOKIE['user_email'])): ?>
            <!-- Form to Store Cookie Data -->
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
                    <label for="faculty">Faculty:</label>
                    <input type="text" id="faculty" name="faculty" required>
                </div>
                <button type="submit" name="set_cookies">Set Cookies</button>
            </form>
        <?php else: ?>
            <!-- Retrieve and Display Cookie Data -->
            <div class="data-item">
                <span class="label">Email:</span> <?php echo htmlspecialchars($_COOKIE['user_email'] ?? 'Not set'); ?>
            </div>
            <div class="data-item">
                <span class="label">Name:</span> <?php echo htmlspecialchars($_COOKIE['user_name'] ?? 'Not set'); ?>
            </div>
            <div class="data-item">
                <span class="label">Faculty:</span> <?php echo htmlspecialchars($_COOKIE['user_faculty'] ?? 'Not set'); ?>
            </div>
            <div class="data-item">
                <span class="label">Set Time:</span> <?php echo htmlspecialchars($_COOKIE['set_time'] ?? 'Not set'); ?>
            </div>
            <!-- Form to Destroy Cookie Data -->
            <form method="POST">
                <button type="submit" name="delete_cookies">Delete Cookies</button>
            </form>
        <?php endif; ?>

        <div id="server-message" class="<?php echo isset($message) && strpos($message, 'successfully') !== false ? 'success' : ''; ?>">
            <?php echo $message ?? ''; ?>
        </div>
    </div>
</body>
</html>