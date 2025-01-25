<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$database = "bookinformation";

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['bookname'] ?? '';
    $author = $_POST['author'] ?? '';
    $isbn = $_POST['isbn'] ?? '';
    $count = $_POST['count'] ?? '';
    $catagory = $_POST['categ'] ?? '';

    // Validate inputs
    if (!empty($name) && !empty($author) && !empty($isbn) && !empty($count) && !empty($catagory)) {
        // Prepare SQL query
        $sql = "INSERT INTO booksinfo (Name, Author, ISBN, Count, 
        ) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("sssds", $name, $author, $isbn, $count, $catagory);

            // Execute and provide feedback
            if ($stmt->execute()) {
                echo "New record created successfully";
                echo "<meta http-equiv='refresh' content='3;url=index.php'>";
            } else {
                echo "Error executing query: " . $stmt->error;
            }

            // Close statement
            $stmt->close();
        } else {
            echo "Error preparing query: " . $conn->error;
        }
    } else {
        echo "All fields are required.";
    }
}

// Close connection
$conn->close();
?>
