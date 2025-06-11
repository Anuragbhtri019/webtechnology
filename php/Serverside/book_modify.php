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

// Initialize variables
$errors = [];
$message = '';

// Handle form submission for updating
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_id'])) {
    $id = (int)$_POST['update_id'];

    // Validate that POST data exists for the given ID
    if (!isset($_POST['title'][$id]) || !isset($_POST['publisher'][$id]) || !isset($_POST['author'][$id]) ||
        !isset($_POST['edition'][$id]) || !isset($_POST['no_of_page'][$id]) || !isset($_POST['price'][$id]) ||
        !isset($_POST['publish_date'][$id]) || !isset($_POST['isbn'][$id])) {
        $errors[] = "Invalid form data submitted for book ID: $id.";
    } else {
        $title = trim($_POST['title'][$id]);
        $publisher = trim($_POST['publisher'][$id]);
        $author = trim($_POST['author'][$id]);
        $edition = trim($_POST['edition'][$id]);
        $no_of_page = (int)$_POST['no_of_page'][$id];
        $price = (float)$_POST['price'][$id];
        $publish_date = $_POST['publish_date'][$id];
        $isbn = trim($_POST['isbn'][$id]);

        // Server-side validation
        if (empty($title)) $errors[] = "Title is required.";
        if (empty($publisher)) $errors[] = "Publisher is required.";
        if (empty($author)) $errors[] = "Author is required.";
        if ($no_of_page <= 0) $errors[] = "Number of pages must be positive.";
        if ($price <= 0) $errors[] = "Price must be positive.";
        if (empty($publish_date)) $errors[] = "Publish date is required.";
        if (!preg_match('/^\d{10}$|^\d{13}$/', $isbn)) $errors[] = "ISBN must be 10 or 13 digits.";

        if (empty($errors)) {
            try {
                // Directly update the record in the database
                $stmt = $pdo->prepare("
                    UPDATE books 
                    SET title = ?, publisher = ?, author = ?, edition = ?, no_of_page = ?, price = ?, publish_date = ?, isbn = ?
                    WHERE id = ?
                ");
                $stmt->execute([$title, $publisher, $author, $edition, $no_of_page, $price, $publish_date, $isbn, $id]);
                $message = "Book data updated successfully!";
            } catch (PDOException $e) {
                $errors[] = "Failed to update book: " . $e->getMessage();
            }
        }
    }
}

// Fetch all books
try {
    $stmt = $pdo->query("SELECT * FROM books ORDER BY title");
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errors[] = "Failed to retrieve books: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Books Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-height: 700px;
            max-width: auto;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.6);
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            text-align: center;
      }
        h1:hover {
            color: #f99d1a;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #4d4d4d;
            text-align: left;
        }
        th {
            background-color: #350ec5;
            color: white;
        }
        th:hover {
            background-color:#a99d1a;
        }
        td:hover {
            background-color: #705e32;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
       
        .form-control {
            width: 100%;
            padding: 5px;
            box-sizing: border-box;
        }
        .btn {
            padding: 5px 10px;
            background-color: #143a03;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #883042;
        }
        .message {
            color: green;
            text-align: center;
            margin: 5px 0;
        }
        .error {
            color: red;
            text-align: center;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Modify Books Data</h1>

        <?php if (!empty($message)): ?>
            <div class="message">
                <?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($books)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Publisher</th>
                        <th>Author</th>
                        <th>Edition</th>
                        <th>Pages</th>
                        <th>Price</th>
                        <th>Publish Date</th>
                        <th>ISBN</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                        <form method="POST" action="">
                            <tr>
                                <td><input type="text" name="title[<?php echo $book['id']; ?>]" 
                                class="form-control" value="<?php 
                                echo htmlspecialchars($book['title']); ?>"></td>
                                <td><input type="text" name="publisher[<?php echo $book['id']; ?>]" 
                                class="form-control" value="<?php
                                 echo htmlspecialchars($book['publisher']); ?>"></td>

                                <td><input type="text" name="author[<?php echo $book['id']; ?>]"
                                class="form-control" value="<?php
                                 echo htmlspecialchars($book['author']); ?>"></td>

                                <td><input type="text" name="edition[<?php echo $book['id']; ?>]"
                                 class="form-control" value="<?php 
                                 echo htmlspecialchars($book['edition']); ?>"></td>

                                <td><input type="number" name="no_of_page[<?php echo $book['id']; ?>]"
                                 class="form-control" value="<?php
                                  echo htmlspecialchars($book['no_of_page']); ?>"></td>

                                <td><input type="number" name="price[<?php echo $book['id']; ?>]" 
                                class="form-control" value="<?php
                                 echo htmlspecialchars($book['price']); ?>" step="0.01"></td>

                                <td><input type="date" name="publish_date[<?php echo $book['id']; ?>]" 
                                class="form-control" value="<?php 
                                echo htmlspecialchars($book['publish_date']); ?>"></td>
                                
                                <td><input type="text" name="isbn[<?php echo $book['id']; ?>]" 
                                class="form-control" value="<?php 
                                echo htmlspecialchars($book['isbn']); ?>"></td>
                                <td>
                                    <button type="submit" name="update_id" value="<?php echo $book['id']; ?>" class="btn">Save</button>
                                </td>
                            </tr>
                        </form>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="error">No books found in the database.</p>
        <?php endif; ?>
    </div>

    <script>
        // Client-side validation for each form
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const id = this.querySelector('button[name="update_id"]').value;
                const title = this.querySelector(`input[name="title[${id}]"]`).value.trim();
                const publisher = this.querySelector(`input[name="publisher[${id}]"]`).value.trim();
                const author = this.querySelector(`input[name="author[${id}]"]`).value.trim();
                const noOfPage = parseInt(this.querySelector(`input[name="no_of_page[${id}]"]`).value);
                const price = parseFloat(this.querySelector(`input[name="price[${id}]"]`).value);
                const publishDate = this.querySelector(`input[name="publish_date[${id}]"]`).value;
                const isbn = this.querySelector(`input[name="isbn[${id}]"]`).value.trim();

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
        });
    </script>
</body>
</html>