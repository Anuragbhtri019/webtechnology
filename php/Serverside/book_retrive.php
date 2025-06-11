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

// Fetch all books
try {
    $stmt = $pdo->query("SELECT id, title, publisher, author, edition, no_of_page, price, publish_date, isbn FROM books ORDER BY isbn ");
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
    <title>Retrieve Books Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width:auto;
            margin: 5px auto;
            margin-bottom: 20px;
            padding: 18px;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            padding: 15px;
            border-radius: 18px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.8);
            margin: 10px auto;
            margin-bottom: 20px;
            max-width:auto;
        }
        h1 {
            color: #333;
            text-align: center;
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
            background-color: #104b1f;
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
        <h1>Books Data</h1>
        
        <!-- Display error if any -->
        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <!-- Display books data -->
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

