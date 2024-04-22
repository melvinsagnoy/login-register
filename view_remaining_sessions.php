<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['idnumber'])) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

// Define database connection details
$servername = "localhost";
$username = "root";
$password = "";
$database = "login_register";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection and handle errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the logged-in user's idnumber from session
$loggedInIdNumber = $_SESSION['idnumber'];

// Prepare and execute query to fetch remaining session for the logged-in user from sitin_student table
$sql = "SELECT remaining_session 
        FROM sitin_student 
        WHERE idnumber = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $loggedInIdNumber);
$stmt->execute();
$stmt->store_result();

// Initialize variable to hold remaining sessions
$remainingSessions = "N/A";

// Check if there are rows returned
if ($stmt->num_rows > 0) {
    // Bind the result variables
    $stmt->bind_result($remainingSession);

    // Fetch the result
    $stmt->fetch();

    // Set the remaining sessions variable
    $remainingSessions = $remainingSession;
}

// Close statement
$stmt->close();

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css"> <!-- Assuming you have a separate CSS file for custom styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- FontAwesome for icons -->
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
    <div class="container-fluid"> <!-- Changed container to container-fluid for wider navbar -->
        <a class="navbar-brand" href="#">Student Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="edit_profile.php"><i class="fas fa-user-edit"></i> Edit Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="view_remaining_sessions.php"><i class="fas fa-clock"></i> View Remaining Sessions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="history.php"><i class="fas fa-calendar-plus"></i> View History</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-warning" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h3>Remaining Sessions</h3>
    <div class="card">
        <div class="card-body">
            <p><strong>Remaining Sessions:</strong> <?php echo $remainingSessions; ?></p>
        </div>
    </div>
</div>

</body>
</html>