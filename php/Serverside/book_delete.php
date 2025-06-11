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

// Handle delete request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $delete_id = (int)$_POST['delete_id'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
        $stmt->execute([$delete_id]);
        $message = "Book deleted successfully!";
    } catch (PDOException $e) {
        $error = "Failed to delete book: " . $e->getMessage();
    }
}

// Fetch all books
try {
    $stmt = $pdo->query("SELECT * FROM books ORDER BY title");
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Failed to retrieve books: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Books Data</title>
    
    <style>
        body {
            font-family: Courier, monospace;
            background-color: #f4f4f4;
            max-width: auto;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-top: 0;
        }
        .message {
            color: green;
            text-align: center;
            margin: 10px 0;
            font-weight: bold;
        }
        .error {
            color: red;
            text-align: center;
            margin: 10px 0;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color:#12461e;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #e9ecef;
        }
        .no-data {
            text-align: center;
            color: #666;
            margin: 20px 0;
        }
        .delete-btn {
            padding: 5px 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
        @media screen and (max-width: 800px) {
            body {
                padding: 10px;
            }
            table {
                font-size: 14px;
            }
            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Delete Books Data</h1>
        
        <!-- Display messages -->
        <?php if (isset($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <!-- Display books with delete option -->
        <?php if (!empty($books)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Publisher</th>
                        <th>Author</th>
                        <th>Edition</th>
                        <th>Pages</th>
                        <th>Price ($)</th>
                        <th>Publish Date</th>
                        <th>ISBN</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($book['id']); ?></td>
                            <td><?php echo htmlspecialchars($book['title']); ?></td>
                            <td><?php echo htmlspecialchars($book['publisher']); ?></td>
                            <td><?php echo htmlspecialchars($book['author']); ?></td>
                            <td><?php echo htmlspecialchars($book['edition'] ?: 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($book['no_of_page']); ?></td>
                            <td><?php echo number_format($book['price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($book['publish_date']); ?></td>
                            <td><?php echo htmlspecialchars($book['isbn']); ?></td>
                            <td>
                                <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this book?');">
                                    <input type="hidden" name="delete_id" value="<?php echo $book['id']; ?>">
                                    <button type="submit" class="delete-btn">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">No books found in the database.</p>
        <?php endif; ?>
    </div>
</body>
</html>