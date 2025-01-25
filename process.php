<?php
$errors = [];
$warning = ''; // New variable for the warning message
$jsonFile = 'token.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form values safely
    $fulname = htmlspecialchars($_POST['fulname'] ?? '', ENT_QUOTES, 'UTF-8');
    $stu_id = htmlspecialchars($_POST['stu-id'] ?? '', ENT_QUOTES, 'UTF-8');
    $book_title = htmlspecialchars($_POST['book_title'] ?? '', ENT_QUOTES, 'UTF-8');
    $date = htmlspecialchars($_POST['date'] ?? '', ENT_QUOTES, 'UTF-8');
    $token = htmlspecialchars($_POST['token'] ?? '', ENT_QUOTES, 'UTF-8');
    $return_date = htmlspecialchars($_POST['return_date'] ?? '', ENT_QUOTES, 'UTF-8');
    $fees = htmlspecialchars($_POST['fees'] ?? '', ENT_QUOTES, 'UTF-8');
    $paid = htmlspecialchars($_POST['paid'] ?? '', ENT_QUOTES, 'UTF-8');

    // Load the JSON file
    $availableTokens = [];
    $usedTokens = [];

    if (file_exists($jsonFile)) {
        $jsonData = file_get_contents($jsonFile);
        $data = json_decode($jsonData, true);

        // Handle errors in JSON decoding
        if (json_last_error() !== JSON_ERROR_NONE) {
            die('Error decoding JSON: ' . json_last_error_msg());
        }

        $availableTokens = $data['availableTokens'] ?? [];
        $usedTokens = $data['usedTokens'] ?? [];
    } else {
        die("Token file not found!");
    }

    // Check if the token is provided and valid
    $isTokenValid = !empty($token) && in_array($token, $availableTokens);

    // If a valid token is used, remove it from availableTokens and add to usedTokens
    if ($isTokenValid) {
        $availableTokens = array_diff($availableTokens, [$token]);
        $usedTokens[] = $token;

        // Save the updated JSON file
        $updatedData = [
            'availableTokens' => array_values($availableTokens), // Reset array indexes
            'usedTokens' => $usedTokens
        ];
        file_put_contents($jsonFile, json_encode($updatedData, JSON_PRETTY_PRINT));
    } elseif (!empty($token)) {
        // If an invalid token is entered, add an error
        $errors[] = "";
    }

    // Validation for Full Name (No special characters or digits)
    if (!preg_match("/^[A-Za-z\s]+$/", $fulname)) {
        $errors[] = "";
    }

    // Validation for Student ID
    if (!preg_match("/^\d{2}-\d{5}-\d{1}$/", $stu_id)) {
        $errors[] = "Student ID must be in the format XX-XXXXX-X, where X is a digit.";
    }

    // Date comparison to check for backdate (return date before issue date)
    $issue_date = strtotime($date); // Convert issue date to timestamp
    $return_date_timestamp = strtotime($return_date); // Convert return date to timestamp

    // Calculate the difference in days between issue and return date
    $date_difference = ($return_date_timestamp - $issue_date) / (60 * 60 * 24);

    // Check if borrowing period exceeds 10 days for invalid token, or any limit if valid token
    if (!$isTokenValid && $date_difference > 10) {
        $warning = "You must return the book within 10 days if the token is invalid.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            width: 300px;
            background-color:rgb(144, 127, 136);
            color: #ffe7b2;
        }
        h1 {
            text-align: center;
        }
        .receipt-item {
            margin: 10px 0;
        }
        .print-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .print-button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            font-weight: bold;
            text-align: center;
        }
        .warning {
            color: orange;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <h1>Receipt</h1>
        <div><strong>Full Name:</strong> <?php echo $fulname; ?></div>
        <div><strong>Student ID:</strong> <?php echo $stu_id; ?></div>
        <div><strong>Book Title:</strong> <?php echo $book_title; ?></div>
        <div><strong>Issue Date:</strong> <?php echo $date; ?></div>
        <div><strong>Token:</strong> <?php echo $token ?: "No Token Used"; ?></div>
        <div><strong>Return Date:</strong> <?php echo $return_date; ?></div>
        <div><strong>Fees:</strong> Tk <?php echo $fees; ?></div>
        <div><strong>Paid:</strong> <?php echo $paid; ?></div>
        <hr>
        <h1>Thank You</h1>
        
        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($warning)): ?>
            <div class="warning">
                <p><?php echo $warning; ?></p>
            </div>
        <?php endif; ?>
        
        <button class="print-button" onclick="window.print()">Print Receipt</button>
    <?php endif; ?>
</body>
</html>
