<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Interest Calculator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 750px;
           
            margin: 0;
            background-color: #f0f0f0;
        }
        .outer {
            width: 350px;
            background-color: rgb(128, 18, 207);
        }
        .calculator {
            background-color: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            border: 8px solid #6a0dad;
            box-sizing: border-box;
        }
        h2 {
            margin: 0 0 20px;
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        label {
            display: block;
            text-align: left;
            margin: 10px 0 5px;
            font-weight: normal;
            color: #555;
        }
        input[type="number"],
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
            height: 40px;
        }
        input[type="text"] {
            background-color: #f9f9f9;
            color: #333;
        }
        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 4px;
            background-color: rgb(46, 21, 63);
            color: white;
            font-size: 16px;
            cursor: pointer;
            box-sizing: border-box;
        }
        button:hover {
            background-color: #5a0c9d;
        }
        .error {
            color: red;
            margin-bottom: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="outer">
        <div class="calculator">
            <h2>Simple Interest</h2>
            <form method="POST" onsubmit="return validateForm()">
                <label for="principal">Principal</label>
                <input type="number" name="principal" id="principal" value="<?php echo isset($_POST['principal']) ? htmlspecialchars($_POST['principal']) : ''; ?>" required>

                <label for="rate">Rate of Interest</label>
                <input type="number" name="rate" id="rate" step="0.01" value="<?php echo isset($_POST['rate']) ? htmlspecialchars($_POST['rate']) : ''; ?>" required>

                <label for="time">Time</label>
                <input type="number" name="time" id="time" value="<?php echo isset($_POST['time']) ? htmlspecialchars($_POST['time']) : ''; ?>" required>

                <label for="interest">Interest</label>
                <input type="text" name="interest" id="interest" readonly value="<?php
                    $interest = '';
                    $total = '';
                    $error = '';

                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $principal = isset($_POST['principal']) ? floatval($_POST['principal']) : 0;
                        $rate = isset($_POST['rate']) ? floatval($_POST['rate']) : 0;
                        $time = isset($_POST['time']) ? floatval($_POST['time']) : 0;

                        if ($principal <= 0 || $rate <= 0 || $time <= 0) {
                            $error = "Please enter positive values for all fields.";
                        } else {
                            $interest = ($principal * $rate * $time) / 100;
                            echo number_format($interest, 2);
                        }
                    }
                ?>">

                <label for="total">Total + Interest</label>
                <input type="text" name="total" id="total" readonly value="<?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($error)) {
                        $total = $principal + $interest;
                        echo number_format($total, 2);
                    }
                ?>">

                <?php
                if (!empty($error)) {
                    echo "<div class='error'>" . htmlspecialchars($error) . "</div>";
                }
                ?>
                <button type="submit" name="calculate">Calculate</button>
            </form>
        </div>
    </div>

    <script>
        function validateForm() {
            const principal = document.forms[0].principal.value;
            const rate = document.forms[0].rate.value;
            const time = document.forms[0].time.value;

            if (isNaN(principal) || principal === '' || principal <= 0 ||
                isNaN(rate) || rate === '' || rate <= 0 ||
                isNaN(time) || time === '' || time <= 0) {
                alert("Please enter valid positive numbers for Principal, Rate, and Time.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>