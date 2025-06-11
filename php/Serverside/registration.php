<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$message = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        $message = "Connection failed: " . $conn->connect_error;
    } else {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $phone = $_POST['phone'];
        $gender = $_POST['gender'];
        $faculty = $_POST['faculty'];

        $stmt = $conn->prepare("INSERT INTO registrations (name, email, password, phone, gender, faculty) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $password, $phone, $gender, $faculty);

        if ($stmt->execute()) {
            $message = "Registration successful! Redirecting in 3 seconds...";
            header("Refresh: 3; url=registration.php");
        } else {
            $message = "Error: " . $stmt->error;
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
    <title>Registration Form</title>
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
            border: 3px solid rgba(60, 113, 113, 0.93);
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
            border: 1px solid rgba(65, 10, 120, 0.76);
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color:rgb(37, 77, 37);
            font-size: 16px;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
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
        <h2>Registration Form</h2>
        <form id="registrationForm" method="POST" onsubmit="return validateForm()">
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
            
            <button type="submit">Register</button>
            <p style="text-align: center; margin-top: 10px;">
    Already have an account? <a href="login.php">Login here</a>
</p>
        </form>
        <div id="error-message"></div>
        <div id="server-message" class="<?php echo $message && strpos($message, 'successful') !== false ? 'success' : ''; ?>">
            <?php echo $message; ?>
        </div>
    </div>

    <script>
        function validateForm() {
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const phone = document.getElementById('phone').value;
            const gender = document.getElementById('gender').value;
            const faculty = document.getElementById('faculty').value;
            const errorMessage = document.getElementById('error-message');
            
            errorMessage.textContent = '';
            
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const phonePattern = /^\d{10}$/;
            const namePattern = /^[a-zA-Z\s]{2,}$/;
            
            if (!namePattern.test(name)) {
                errorMessage.textContent = 'Name must be at least 2 characters and contain only letters';
                return false;
            }
            
            if (!emailPattern.test(email)) {
                errorMessage.textContent = 'Please enter a valid email address';
                return false;
            }
            
            if (password.length < 6) {
                errorMessage.textContent = 'Password must be at least 6 characters long';
                return false;
            }
            
            if (!phonePattern.test(phone)) {
                errorMessage.textContent = 'Phone must be a 10-digit number';
                return false;
            }
            
            if (gender === '') {
                errorMessage.textContent = 'Please select a gender';
                return false;
            }
            
            if (faculty.trim() === '') {
                errorMessage.textContent = 'Please enter a faculty';
                return false;
            }
            
            return true;
        }
    </script>
   
</body>
</html>