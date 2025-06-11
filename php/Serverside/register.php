<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();

// Database connection
$dbname = "project";
$servername = "localhost";
$username = "root";
$password = "";
//for using the image 
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle form submission
$response = ['success' => false, 'message' => ''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate inputs
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        // Regex patterns
        $usernamePattern = '/^[a-zA-Z0-9_]{3,20}$/'; // Letters, numbers, underscores, 3-20 chars
        $emailPattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'; // Standard email format
        $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'; 
        // At least 8 chars, 1 uppercase, 1 lowercase, 1 number, 1 special char

        // Validate required fields
        if (empty($username) || empty($email) || empty($password)) {
            throw new Exception('All fields are required');
        }

        // Validate username with regex
        if (!preg_match($usernamePattern, $username)) {
            throw new Exception('Username must be 3-20 characters, letters, numbers, or underscores only');
        }

        // Validate email with regex
        if (!preg_match($emailPattern, $email)) {
            throw new Exception('Invalid email format');
        }

        // Validate password with regex
        if (!preg_match($passwordPattern, $password)) {
            throw new Exception('Password must be at least 8 characters, with 1 uppercase, 1 lowercase, 1 number, and 1 special character');
        }

        // Check if username or email exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->rowCount() > 0) {
            throw new Exception('Username or email already exists');
        }

        // Handle file upload
        if (!isset($_FILES['photo']) || $_FILES['photo']['error'] == UPLOAD_ERR_NO_FILE) {
            throw new Exception('Profile photo is required');
        }

        $file = $_FILES['photo'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Invalid file type. Only JPG, PNG, and GIF allowed');
        }

        if ($file['size'] > $maxSize) {
            throw new Exception('File size must be less than 2MB');
        }

        // Generate unique filename
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        $uploadPath = 'uploads/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            throw new Exception('Failed to upload photo');
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, photo) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $hashedPassword, $filename]);

        $response['success'] = true;
        $response['message'] = 'Registration successful!';
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }

    // Output JSON for AJAX
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
        }

        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
        }

        form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.6);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="file"] {
            width: 100%;
            padding: 8px;
            border: 2px solid rgba(53, 108, 52, 0.7);
            border-radius: 14px;
           
        }

        button {
            width: 100%;
            padding: 10px;
            background: #1877f2;
            color: white;
            border: 2px solid rgba(53, 108, 52, 0.7);
            border-radius: 10px;
            cursor: pointer;
        }

        button:hover {
            background: #166fe5;
        }

        .error {
            color: red;
            margin-top: 10px;
            display: none;
        }

        .success {
            color: green;
            margin-top: 10px;
            display: none;
        }

        #preview {
            max-width: 100px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <form id="registerForm" enctype="multipart/form-data">
            <h2>Register</h2>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="photo">Profile Photo</label>
                <input type="file" id="photo" name="photo" accept="image/*" required>
                <img id="preview" src="#" alt="Preview" style="display: none;">
            </div>
            <button type="submit">Register</button>
            <div id="error" class="error"></div>
            <div id="success" class="success"></div>
        </form>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const errorDiv = document.getElementById('error');
            const successDiv = document.getElementById('success');
            errorDiv.style.display = 'none';
            successDiv.style.display = 'none';

            const formData = new FormData(this);

            try {
                const response = await fetch('', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();

                if (result.success) {
                    successDiv.textContent = result.message;
                    successDiv.style.display = 'block';
                    this.reset();
                    document.getElementById('preview').style.display = 'none';
                } else {
                    errorDiv.textContent = result.message;
                    errorDiv.style.display = 'block';
                }
            } catch (error) {
                errorDiv.textContent = 'An error occurred. Please try again.';
                errorDiv.style.display = 'block';
            }
        });

        // Image preview
        document.getElementById('photo').addEventListener('change', function(e) {
            const preview = document.getElementById('preview');
            const file = e.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>