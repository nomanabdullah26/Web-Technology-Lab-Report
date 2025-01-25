<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "bookinformation"; 


$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch book details for the given ISBN
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['isbn'])) {
    $isbn = $_GET['isbn'];
    $sql = "SELECT * FROM booksinfo WHERE ISBN = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $isbn);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();
    $stmt->close();
}

// Update the book details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $isbn = $_POST['isbn'];
    $name = $_POST['bookname'];
    $author = $_POST['author'];
    $count = $_POST['count'];
    $catagory = $_POST['categ'];

    $sql = "UPDATE booksinfo SET Name = ?, Author = ?, Count = ?, Catagory = ? WHERE ISBN = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdss", $name, $author, $count, $catagory, $isbn);

    if ($stmt->execute()) {
        echo "<div class='success-message'>Record updated successfully! Redirecting...</div>";
        echo "<meta http-equiv='refresh' content='0;url=index.php'>";
    } else {
        echo "<div class='error-message'>Error updating record: " . $conn->error . "</div>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Book</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .update-container {
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            padding: 20px 30px;
            width: 400px;
        }

        h1 {
            text-align: center;
            color: #333333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555555;
        }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .success-message {
            color: green;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }

        .error-message {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="update-container">
        <h1>Update Book</h1>
        <form method="POST" action="update.php">
            <div class="form-group">
                <label for="bookname">Name:</label>
                <input type="text" id="bookname" name="bookname" value="<?php echo htmlspecialchars($book['Name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="author">Author:</label>
                <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($book['Author']); ?>" required>
            </div>
            <div class="form-group">
                <label for="isbn">ISBN:</label>
                <input type="text" id="isbn" name="isbn" value="<?php echo htmlspecialchars($book['ISBN']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="count">Count:</label>
                <input type="number" id="count" name="count" value="<?php echo htmlspecialchars($book['Count']); ?>" required>
            </div>
            <div class="form-group">
                <label for="catag">Catagory:</label>
                <input type="text" id="categ" name="categ" value="<?php echo htmlspecialchars($book['Catagory']); ?>" required>
            </div>
            <input type="submit" value="Update">
        </form>
    </div>
</body>
</html>
