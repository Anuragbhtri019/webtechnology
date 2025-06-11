<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Calculator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        .calculator {
            background-color: #fff;
            height:220px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        input[type="number"] {
            text-align:left;
            min-width: 50px;
            padding: 5px;
            margin: 10px 0;
            border: 2px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 8px 15px;
            margin: 5px;
            min-width: 80px; /* Ensure all buttons are the same width */
            border: 1px solid black; 
            border-radius: 4px;
            background-color: #ddd;
            cursor: pointer;
            box-sizing: border-box; /* Include padding in width */
        }
        button:hover {
            background-color: #ccc;
        }
        .result {
            margin-bottom: 20px; /* Add spacing below the result */
            font-weight: bold;
            margin-left: -35px;
        }
        .error {
            color: red;
            margin-bottom: 20px; /* Add spacing below the error */
             /* Fixed margin-left syntax */
        }
    </style>
</head>
<body>
    <div class="calculator">
        <form method="POST" onsubmit="return validateForm()">
            <input type="number" name="num1" value="<?php echo isset($_POST['num1']) ? htmlspecialchars($_POST['num1']) : ''; ?>" required><br>
            <input type="number" name="num2" value="<?php echo isset($_POST['num2']) ? htmlspecialchars($_POST['num2']) : ''; ?>" required><br>

            <!-- Result box moved above the buttons -->
            <div class="result">
                <?php
                // Initialize variables to avoid undefined variable issues
                $result = '';
                $error = '';

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Retrieve and sanitize inputs
                    $num1 = isset($_POST['num1']) ? floatval($_POST['num1']) : 0;
                    $num2 = isset($_POST['num2']) ? floatval($_POST['num2']) : 0;
                    $operation = isset($_POST['operation']) ? $_POST['operation'] : '';

                    // Perform calculation based on the operation
                    switch ($operation) {
                        case 'add':
                            $result = "Addition: " . ($num1 + $num2);
                            break;
                        case 'subtract':
                            $result = "Subtraction: " . ($num1 - $num2);
                            break;
                        case 'multiply':
                            $result = "Multiplication: " . ($num1 * $num2);
                            break;
                        case 'divide':
                            if ($num2 == 0) {
                                $error = "Error: Cannot divide by zero!";
                            } else {
                                $result = "Division: " . number_format($num1 / $num2, 2); // Round to 2 decimal places
                            }
                            break;
                        default:
                            $error = "Invalid operation!";
                    }
                }

                // Display result or error only if they are set
                if (!empty($error)) {
                    echo "<div class='error'>" . htmlspecialchars($error) . "</div>";
                } elseif (!empty($result)) {
                    echo "<div class='result'>" . htmlspecialchars($result) . "</div>";
                }
                ?>
            </div>

            <!-- Buttons below the result -->
            <button type="submit" name="operation" value="add">Add</button>
            <button type="submit" name="operation" value="subtract">Subtract</button><br>
            <button type="submit" name="operation" value="divide">Divide</button>
            <button type="submit" name="operation" value="multiply">Multiply</button>
        </form>
    </div>

    <script>
        function validateForm() {
            const num1 = document.forms[0].num1.value;
            const num2 = document.forms[0].num2.value;

            if (isNaN(num1) || num1 === '' || isNaN(num2) || num2 === '') {
                alert("Please enter valid numbers in both fields.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>