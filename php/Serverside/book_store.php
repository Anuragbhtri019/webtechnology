<?php
// Database configuration
$host = 'localhost';
$dbname = 'project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $publisher = trim($_POST['publisher']);
    $author = trim($_POST['author']);
    $edition = trim($_POST['edition']);
    $no_of_page = (int)$_POST['no_of_page'];
    $price = (float)$_POST['price'];
    $publish_date = $_POST['publish_date'];
    $isbn = trim($_POST['isbn']);
    
    // Server-side validation
    $errors = [];
    if (empty($title)) $errors[] = "Title-is-required.";
    if (empty($publisher)) $errors[] = "Publisher-is-required.";
    if (empty($author)) $errors[] = "Author-is-required.";
    if ($no_of_page <= 0) $errors[] = "Number-of-pages-must-be-positive.";
    if ($price <= 0) $errors[] = "Price-must-be-positive.";
    if (empty($publish_date)) $errors[] = "Publish-date-is-required.";
    if (!preg_match('/^\d{10}$|^\d{13}$/', $isbn)) $errors[] = "ISBN-must-be-10-or-13-digits.";
    
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO books (title, publisher, author, edition, no_of_page, price, publish_date, isbn)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$title, $publisher, $author, $edition, $no_of_page, $price, $publish_date, $isbn]);
            $message = "Book-data-stored-successfully!";
        } catch (PDOException $e) {
            $errors[] = "Failed to store book: " . ($e->getCode() == 23000 ? "ISBN already exists." : $e->getMessage());
        }
    }
    if (!empty($message)) {
        echo "<div class='message'>$message</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Data Management</title>
    <style>
        body {
            font-family: "Courier New", Courier, monospace;
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 0 10px rgba(43, 37, 37, 0.7);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 10px;
           
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
        input[type="number"],
        input[type="date"] {
            width: 100%;
            padding: 8px;
            border: 2px solid #503486;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            padding: 10px 20px;
            border: 2px solid #503486; 
            background-color: #22572e;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #724d3d;
        }
        .error {
            color: red;
            margin: 10px 0;
           
        }
        .error-list {
            list-style: none;
            padding: 0;
        }
        .error-list li {
            margin-bottom: 5px;
            margin-top: 10px; /* Added margin-top for spacing */
            
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Book Data</h1>
        
        <!-- Form to store book data -->
        <form id="bookForm" method="POST" action="">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="" >
            </div>
            <div class="form-group">
                <label for="publisher">Publisher:</label>
                <input type="text" id="publisher" name="publisher" value="" >
            </div>
            <div class="form-group">
                <label for="author">Author:</label>
                <input type="text" id="author" name="author" value="" >
            </div>
            <div class="form-group">
                <label for="edition">Edition:</label>
                <input type="text" id="edition" name="edition" value=""  >
            </div>
            <div class="form-group">
                <label for="no_of_page">Number of Pages:</label>
                <input type="number" id="no_of_page" name="no_of_page" min="1" value=""  >
            </div>
            <div class="form-group">
                <label for="price">Price ($):</label>
                <input type="number" id="price" name="price" step="0.01" min="0.01" value=""  >
            </div>
            <div class="form-group">
                <label for="publish_date">Publish Date:</label>
                <input type="date" id="publish_date" name="publish_date" value=""  >
            </div>
            <div class="form-group">
                <label for="isbn">ISBN:</label>
                <input type="text" id="isbn" name="isbn" value=""  >
            </div>
            <button type="submit">Store Book Data</button>
        </form>
        
        <!-- Display messages -->
        <?php if (isset($message)): ?>
            <div class="message"> <?php echo htmlspecialchars(str_replace("—", " ", $message)); ?></div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="error">
                <ul class="error-list">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars(str_replace("—", " ", $error)); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Client-side validation
        document.getElementById('bookForm').addEventListener('submit', function(e) {
            const title = document.getElementById('title').value.trim();
            const publisher = document.getElementById('publisher').value.trim();
            const author = document.getElementById('author').value.trim();
            const noOfPage = parseInt(document.getElementById('no_of_page').value);
            const price = parseFloat(document.getElementById('price').value);
            const publishDate = document.getElementById('publish_date').value;
            const isbn = document.getElementById('isbn').value.trim();
            
            let errors = [];
            if (!title) errors.push('Title is required.');
            if (!publisher) errors.push('Publisher is required.');
            if (!author) errors.push('Author is required.');
            if (isNaN(noOfPage) || noOfPage <= 0) errors.push('Number of pages must be positive.');
            if (isNaN(price) || price <= 0) errors.push('Price must be positive.');
            if (!publishDate) errors.push('Publish date is required.');
            if (!/^\d{10}$|^\d{13}$/.test(isbn)) errors.push('ISBN must be 10 or 13 digits.');
            
            if (errors.length > 0) {
                e.preventDefault();
                alert(errors.join('\n'));
            }
        });
    </script>
</body>
</html>