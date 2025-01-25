<?php
// Read the token data from token.json file
$tokenFile = 'token.json';
$availableTokens = [];
$usedTokens = [];

if (file_exists($tokenFile)) {
    $jsonData = file_get_contents($tokenFile);
    $data = json_decode($jsonData, true);

    // Handle errors in JSON decoding
    if (json_last_error() !== JSON_ERROR_NONE) {
        die('Error decoding JSON: ' . json_last_error_msg());
    }

    $availableTokens = $data['availableTokens'] ?? []; // Access available tokens array
    $usedTokens = $data['usedTokens'] ?? []; // Access used tokens array
} else {
    die('Token file not found!');
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>library Mng.</title>
</head>
<body>
<img src="img/ID.png" alt="Student ID">
<h1 style="text-align: center;">Library Management</h1>
    

    <div class="topclass">
        <div class="container">
        <div class="topclass">
    <div class="container">
        <!-- Box 1: Display all book information -->
        <div class="widebox">
            <h2>All Book Information</h2>
            <?php
            $servername = "localhost"; 
            $username = "root"; 
            $password = ""; 
            $database = "bookinformation"; 

            // Connect to the database
            $conn = new mysqli($servername, $username, $password, $database);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch all book records
            $sql = "SELECT * FROM booksinfo";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table style='width: 100%; border-collapse: collapse;'>";
                echo "<tr>
                        <th style='border: 1px solid black; padding: 8px;'>Name</th>
                        <th style='border: 1px solid black; padding: 8px;'>Author</th>
                        <th style='border: 1px solid black; padding: 8px;'>ISBN</th>
                        <th style='border: 1px solid black; padding: 8px;'>Count</th>
                        <th style='border: 1px solid black; padding: 8px;'>Catagory</th>
                      </tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td style='border: 1px solid black; padding: 8px;'>" . htmlspecialchars($row['Name']) . "</td>";
                    echo "<td style='border: 1px solid black; padding: 8px;'>" . htmlspecialchars($row['Author']) . "</td>";
                    echo "<td style='border: 1px solid black; padding: 8px;'>" . htmlspecialchars($row['ISBN']) . "</td>";
                    echo "<td style='border: 1px solid black; padding: 8px;'>" . htmlspecialchars($row['Count']) . "</td>";
                    echo "<td style='border: 1px solid black; padding: 8px;'>" . htmlspecialchars($row['Catagory']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No records found.";
            }

            $conn->close();
            ?>
        </div>


        <div class="widebox">
    <h2>Manage Books</h2>
    <?php
    $servername = "localhost"; 
    $username = "root"; 
    $password = ""; 
    $database = "bookinformation"; 

    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch all book records
    $sql = "SELECT * FROM booksinfo";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table style='width: 100%; border-collapse: collapse;'>";
        echo "<tr>
                <th style='border: 1px solid black; padding: 8px;'>Name</th>
                <th style='border: 1px solid black; padding: 8px;'>Author</th>
                <th style='border: 1px solid black; padding: 8px;'>ISBN</th>
                <th style='border: 1px solid black; padding: 8px;'>Count</th>
                <th style='border: 1px solid black; padding: 8px;'>Catagory</th>
                <th style='border: 1px solid black; padding: 8px;'>Actions</th>
              </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td style='border: 1px solid black; padding: 8px;'>" . htmlspecialchars($row['Name']) . "</td>";
            echo "<td style='border: 1px solid black; padding: 8px;'>" . htmlspecialchars($row['Author']) . "</td>";
            echo "<td style='border: 1px solid black; padding: 8px;'>" . htmlspecialchars($row['ISBN']) . "</td>";
            echo "<td style='border: 1px solid black; padding: 8px;'>" . htmlspecialchars($row['Count']) . "</td>";
            echo "<td style='border: 1px solid black; padding: 8px;'>" . htmlspecialchars($row['Catagory']) . "</td>";
            echo "<td style='border: 1px solid black; padding: 8px;'>
                    <a href='update.php?isbn=" . urlencode($row['ISBN']) . "' style='margin-right: 10px;'>Update</a>
                    <a href='delete.php?isbn=" . urlencode($row['ISBN']) . "' onclick='return confirm(\"Are you sure you want to delete this record?\");'>Delete</a>
                  </td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No records found.";
    }

    $conn->close();
    ?>
</div>


            <div class="split-box">
                <div class="section" id="availableTokens">
                    <strong>Available Tokens:</strong>
                    <ul>
                        <?php
                        foreach ($availableTokens as $token) {
                            echo '<li>' . htmlspecialchars($token) . '</li>';
                        }
                        ?>
                    </ul>
                </div>
                <div class="section" id="usedTokens">
                    <strong>Used Tokens:</strong>
                    <ul>
                        <?php
                        foreach ($usedTokens as $token) {
                            echo '<li>' . htmlspecialchars($token) . '</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    </div>
    <div class="dwncon">
    <div class="sqbox">
        <img src="img/1.jpg" alt="Book 1">
    </div>
    <div class="sqbox">
        <img src="img/33.jpg" alt="Book 2">
    </div>
    <div class="sqbox">
        <img src="img/4.png" alt="Book 3">
    </div>
    </div>

    <div class="dwnto">
        <div class="boxdown-1">
            <form action="process.php" method="post">
            <h1 class="boxdown-1"><i>Borrow book</i></h1>
                <label for="name">Full Name:</label>
                <input type="text" id="fullname" name="fulname" required>
                <br>

                <label for="id">ID:</label>
                <input type="text" id="stu-id" name="stu-id" required>
                <br>


                <label for="book_title">Book Title:</label>
                    <select id="book_title" name="book_title" required>
                        <option value="">Select a book</option>
                        <option value="Harry Potter and the Sorcerer's Stone">Harry Potter and the Sorcerer's Stone</option>
                        <option value="The Hunger Games">The Hunger Games</option>
                        <option value="Twilight">Twilight</option>
                        <option value="To Kill A Mockingbird">To Kill A Mockingbird</option>
                        <option value="The Great Gatsby">The Great Gatsby</option>
                        <option value="The Fault in Our Stars">The Fault in Our Stars</option>
                        <option value="1984">1984</option>
                        <option value="Pride and Prejudice">Pride and Prejudice</option>
                        <option value="Divergent">Divergent</option>
                        <option value="Harry Potter and the Prisoner of Azkaban">Harry Potter and the Prisoner of Azkaban</option>
                    </select>
                <br>

                <label for="date">Date:</label>
                    <input type="date" id="date" name="date" required><br>

                    <label for="token">Token:</label>
                    <input type="text" id="token" name="token" required><br>

                    <label for="return_date">Return Date:</label>
                    <input type="date" id="return_date" name="return_date" required><br>

                    <label for="fees">Fees:</label>
                    <input type="number" id="fees" name="fees" required><br>

                    <label for="paid">Paid:</label>
                    <select id="paid" name="paid" required>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select><br>

                    <input type="submit" value="Submit">
                </form>


        </div>
        <div class="boxdown-2">
            <form action="submit.php" method="post">
            <h1 class="boxdown-2"><i>Add book</i></h1>
                <label for="name">Book info:</label>
                <input type="text" id="bookname" name="bookname" required>
                <br>

                <label for="id">Author:</label>
                <input type="text" id="author" name="author" required>
                <br>
                <label for="id">Isbn-number:</label>
                <input type="text" id="isbn" name="isbn" required>
                <br>
                <label for="fees">Count:</label>
                <input type="number" id="count" name="count" required><br>
                <br>
                <label for="id">Catagory:</label>
                <input type="text" id="catag" name="catag" required>
                <br>
                    <input type="submit" value="Submit">
                </form>
        </div>
    </div>


</body>
</html>