<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "bookinformation"; 

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['isbn'])) {
    $isbn = $_GET['isbn'];
    $sql = "DELETE FROM booksinfo WHERE ISBN = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $isbn);

    if ($stmt->execute()) {
        echo "Record deleted successfully";
        echo "<meta http-equiv='refresh' content='3;url=index.php'>";
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
