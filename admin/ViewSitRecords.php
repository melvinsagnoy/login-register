<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit(); // Add exit to prevent further execution if not logged in
}

// Database connection
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "login_register";

// Create connection
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert data into sitin_student table if SITIN button is clicked
if (isset($_POST['sitin_button'])) {
    // Get data from form
    $idnumber = $_POST["idnumber"];
    $full_name = $_POST["full_name"];
    $email = $_POST["email"];
    $yearlevel = $_POST["yearlevel"];
    $purpose = $_POST["purpose"];
    $lab = $_POST["lab"];
    $remaining_session = $_POST["remaining_session"];
    
    // Insert into sitin_student table
    $sql_insert = "INSERT INTO sitin_student (idnumber, full_name, email, yearlevel, purpose, lab, remaining_session, time_in) 
                   VALUES ('$idnumber', '$full_name', '$email', '$yearlevel', '$purpose', '$lab', '$remaining_session', NOW())";

    if ($conn->query($sql_insert) === TRUE) {
        $success_message = "SITIN record added successfully!";
    } else {
        $error_message = "Error: " . $sql_insert . "<br>" . $conn->error;
    }
}

if (isset($_POST['logout_button'])) {
    $idnumber = $_POST['idnumber'];
    // Update the remaining_session by deducting 1
    $sql_update = "UPDATE sitin_student SET time_out = NOW(), remaining_session = GREATEST(0, remaining_session - 1) WHERE idnumber = '$idnumber'";
    if ($conn->query($sql_update) === TRUE) {
        $success_message = "Logged out successfully!";
    } else {
        $error_message = "Error updating record: " . $conn->error;
    }
}

// Fetch sitin records from database
$sql = "SELECT * FROM sitin_student";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Sitin Records</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <style>
        .navbar {
            background-color: #f8f9fa; /* Light gray background */
            border-bottom: 1px solid #dee2e6; /* Gray border bottom */
            padding-top: 15px; /* Space above links */
            padding-bottom: 15px; /* Space below links */
        }
        .navbar-nav .nav-link {
            margin-right: 15px; /* Space between links */
        }
        .welcome-header {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .navbar-nav .nav-link:hover {
            color: #007bff; /* Change color on hover */
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid"> 
        <a class="navbar-brand" href="#">View Sitin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="search.php"><i class="fas fa-search"></i>Search</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="delete.php"><i class="fas fa-trash"></i>Delete</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="ViewSitRecords.php"><i class="fas fa-eye"></i>View Sitin Records</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="generate.php"><i class="fas fa-file"></i>Generate Reports</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-warning" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2>Sit-in Records</h2>
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success" role="alert">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Year Level</th>
                <th>Purpose</th>
                <th>Lab</th>
                <th>Remaining Sessions</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["idnumber"] . "</td>";
                    echo "<td>" . $row["full_name"] . "</td>";
                    echo "<td>" . $row["email"] . "</td>";
                    echo "<td>" . $row["yearlevel"] . "</td>";
                    echo "<td>" . $row["purpose"] . "</td>";
                    echo "<td>" . $row["lab"] . "</td>";
                    echo "<td>" . $row["remaining_session"] . "</td>";
                    echo "<td>" . $row["time_in"] . "</td>";
                    echo "<td>" . $row["time_out"] . "</td>";
                    echo "<td>";
                    if ($row["time_out"] == NULL) {
                        // Display the logout button only if time_out is not set
                        echo "<form method='POST' >";
                        echo "<input type='hidden' name='idnumber' value='" . $row["idnumber"] . "'>";
                        echo "<button type='submit' name='logout_button' class='btn btn-primary'>Log Out</button>";
                        echo "</form>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='10'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>
