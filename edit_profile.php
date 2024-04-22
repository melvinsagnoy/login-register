<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit(); // Add exit to prevent further execution if not logged in
}

// Include the database configuration file
include 'database.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $fullname = $_POST["fullname"];
    $username = $_POST["username"];
    $gender = $_POST["gender"];
    $email = $_POST["email"];
    $yearlevel = $_POST["yearlevel"];
    $address = $_POST["address"];
    $password = $_POST["password"];

    // Update user data in the database
    $loggedInIdNumber = $_SESSION['idnumber'];
    $sql = "UPDATE users SET full_name = '$fullname', username = '$username', gender = '$gender', email = '$email', yearlevel = '$yearlevel', address = '$address', password = '$password' WHERE idnumber = '$loggedInIdNumber'";

    if ($conn->query($sql) === TRUE) {
        // Data updated successfully
        echo "Profile updated successfully.";
    } else {
        // Error updating data
        echo "Error updating profile: " . $conn->error;
    }
}

// Retrieve user data from the database
$loggedInIdNumber = $_SESSION['idnumber'];
$sql = "SELECT * FROM users WHERE idnumber = '$loggedInIdNumber'";
$result = $conn->query($sql);

// Check if user data is retrieved successfully
if ($result->num_rows > 0) {
    // Fetch user data
    $userData = $result->fetch_assoc();
    // Set the username in the session
    $_SESSION["username"] = $userData['username'];
} else {
    // No user data found
    $userData = array(
        "full_name" => "",
        "username" => "",
        "gender" => "",
        "email" => "",
        "yearlevel" => "",
        "address" => "",
        "password" => ""
    );
}

// Close the database connection
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
        .form-control {
            margin-bottom: 10px;
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
                    <a class="nav-link" href="history.php"><i class="fas fa-calendar-plus"></i> Make Reservation</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-warning" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="welcome-header">
    <h2>Welcome <?php echo $_SESSION["username"]; ?></h2>
</div>

<div class="container">
    <h3>Edit Profile</h3>
    <form method="POST">
        <div class="mb-3">
            <label for="fullname" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo $userData['full_name']; ?>">
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo $userData['username']; ?>">
        </div>
        <div class="mb-3">
            <label for="gender" class="form-label">Gender</label>
            <input type="text" class="form-control" id="gender" name="gender" value="<?php echo $userData['gender']; ?>">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $userData['email']; ?>">
        </div>
        <div class="mb-3">
            <label for="yearlevel" class="form-label">Year Level</label>
            <input type="text" class="form-control" id="yearlevel" name="yearlevel" value="<?php echo $userData['yearlevel']; ?>">
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address" value="<?php echo $userData['address']; ?>">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" value="<?php echo $userData['password']; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

</body>
</html>
