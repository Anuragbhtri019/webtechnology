<?php
// Start the session
session_start();

// Handle form submission for storing student data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action == 'store') {
        $student_id = $_POST['student_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $faculty = $_POST['faculty'];
        
        // Basic validation
        if (!empty($student_id) && !empty($name) && !empty($email) && !empty($faculty)) {
            // Store in session
            $_SESSION['student'] = [
                'student_id' => $student_id,
                'name' => $name,
                'email' => $email,
                'faculty' => $faculty
            ];
            $message = "Student data stored successfully!";
        } else {
            $message = "All fields are required!";
        }
    }
    
    if ($action == 'destroy') {
        session_destroy();
        $_SESSION = array();
        $message = "Student session destroyed!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Session Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
           
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.8);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 8px;
            border: 2px solid #ddd;
            border-radius: 18px;
            box-sizing: border-box;
        }
        button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: 2px solid #ddd;
            border-radius: 18px;
            cursor: pointer;
            margin-right: 10px;
        }
        
        button:hover {
            background-color: #0c0684;
        }
        .destroy-btn {
            background-color: #dc3545;
        }
        .destroy-btn:hover {
            background-color: #64f006;
        }
        
        #sessionData {
            margin-top: 20px;
            padding: 15px;
            background: #e9ecef;
            border-radius: 18px;
        }
        .message {
            color: green;
            margin: 10px 0;
            font-weight: bold;
        }
        .error {
            color: red;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        ul li {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Student Session Management</h1>
        
        <!-- Form to store student data -->
        <form id="studentForm" method="POST" action="">
            <div class="form-group">
                <label for="student_id">Student ID:</label>
                <input type="text" id="student_id" name="student_id" required>
            </div>
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
            <input type="hidden" name="action" value="store">
            <button type="submit">Store Student Data</button>
        </form>
        
        <!-- Form to destroy session -->
        <form id="destroyForm" method="POST" action="">
            <input type="hidden" name="action" value="destroy">
            <button type="submit" class="destroy-btn">Destroy Session</button>
        </form>
        
        <!-- Display message -->
        <?php if (isset($message)): ?>
            <div class="message <?php echo strpos($message, 'required') !== false ? 'error' : ''; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <!-- Display session data -->
        <div id="sessionData">
            <h3>Current Student Data:</h3>
            <?php if (isset($_SESSION['student']) && !empty($_SESSION['student'])): ?>
                <ul>
                    <li><strong>Student ID:</strong> <?php echo htmlspecialchars($_SESSION['student']['student_id']); ?></li>
                    <li><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['student']['name']); ?></li>
                    <li><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['student']['email']); ?></li>
                    <li><strong>Faculty:</strong> <?php echo htmlspecialchars($_SESSION['student']['faculty']); ?></li>
                </ul>
            <?php else: ?>
                <p>No student data in session.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Client-side form validation and confirmation
        document.getElementById('studentForm').addEventListener('submit', function(e) {
            const studentId = document.getElementById('student_id').value.trim();
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const faculty = document.getElementById('faculty').value.trim();
            
            if (!studentId || !name || !email || !faculty) {
                e.preventDefault();
                alert('All fields are required!');
                return;
            }
            
            // Basic email format check
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Please enter a valid email address!');
            }
        });

        document.getElementById('destroyForm').addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to destroy the student session?')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>