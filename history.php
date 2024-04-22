<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit(); // Add exit to prevent further execution if not logged in
}

// Include the database configuration file
include 'database.php';

// Get the current logged-in user's username
$loggedInIdNumber = $_SESSION['idnumber'];

// Query to retrieve data from the sitin_student table for the current user based on idnumber
$sql = "SELECT * FROM sitin_student WHERE idnumber = '$loggedInIdNumber' ORDER BY time_in DESC";
$result = $conn->query($sql);

// Check for errors
if (!$result) {
    // SQL query failed, display error message
    echo "Error: " . $conn->error;
} else {
    // Continue with processing the result
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
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
        .card {
            margin-bottom: 20px;
        }
        /* Custom table styles */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        th {
            background-color: #f8f9fa; /* Light gray background for header */
            font-weight: bold;
        }
        tbody tr:nth-child(even) {
            background-color: #f8f9fa; /* Alternate row background color */
        }
        tbody tr:hover {
            background-color: #f1f1f1; /* Hover effect */
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
    <h3>View History</h3>
    <div class="card">
        <div class="card-body">
            <?php
                if ($result->num_rows > 0) {
                    // Output data of each row in a table
                    echo "<table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Year Level</th>
                                    <th>Purpose</th>
                                    <th>Laboratory</th>
                                    <th>Remaining Sessions</th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                </tr>
                            </thead>
                            <tbody>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["idnumber"] . "</td>
                                <td>" . $row["full_name"] . "</td>
                                <td>" . $row["email"] . "</td>
                                <td>" . $row["yearlevel"] . "</td>
                                <td>" . $row["purpose"] . "</td>
                                <td>" . $row["lab"] . "</td>
                                <td>" . $row["remaining_session"] . "</td>
                                <td>" . $row["time_in"] . "</td>
                                <td>" . $row["time_out"] . "</td>
                              </tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "No data found.";
                }
            ?>
        </div>
    </div>
</div>

</body>
</html>
