<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit(); // Add exit to prevent further execution if not logged in
}

// Check if the "username" key exists in the session
$username = isset($_SESSION["username"]) ? $_SESSION["username"] : "Roy Dumasig";

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

// Initialize variables
$student_data = null;
$error_message = "";
$remaining_session = 30; // Set default remaining session to 30

if (isset($_POST["idnumber"])) {
    $idnumber = $_POST["idnumber"];

    // Query to check if the student exists
    $sql = "SELECT * FROM users WHERE idnumber = $idnumber";
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            // Student exists, fetch data
            $student_data = $result->fetch_assoc();
        } else {
            // Student not found
            $error_message = "Student with ID $idnumber not found.";
        }
    } else {
        // SQL query failed
        $error_message = "Error: " . $conn->error;
    }
}

// Check if the "View Sitin Records" button has been clicked
if (isset($_POST['view_sitin_records'])) {
    // Redirect to the View Sitin Records page
    header("Location: ViewSitRecords.php");
    exit();
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
    $remaining_session = isset($_POST["remaining_session"]) ? $_POST["remaining_session"] : 30; // Default value 30

    // Insert into sitin_student table
    $sql_insert = "INSERT INTO sitin_student (idnumber, full_name, email, yearlevel, purpose, lab, remaining_session) VALUES ('$idnumber', '$full_name', '$email', '$yearlevel', '$purpose', '$lab', '$remaining_session')";

    if ($conn->query($sql_insert) === TRUE) {
        $success_message = "SITIN record added successfully!";
    } else {
        $error_message = "Error: " . $sql_insert . "<br>" . $conn->error;
    }
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
        .navbar-nav .nav-link:hover {
            color: #007bff; /* Change color on hover */
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
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
                    <form method="post">
                        <button type="submit" class="nav-link" name="view_sitin_records"><i class="fas fa-eye"></i>View Sitin Records</button>
                    </form>
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

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="mt-4">
                <div class="mb-3">
                    <label for="idnumber" class="form-label">Enter Student ID:</label>
                    <input type="text" class="form-control" id="idnumber" name="idnumber" required>
                </div>
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            </form>
        </div>
    </div>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="mt-4">

        <?php if ($student_data): ?>
            <div class="row justify-content-center mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"> Student Information</h5>
                            <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name:</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $student_data['full_name']; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="text" class="form-control" id="email" name="email" value="<?php echo $student_data['email']; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="yearlevel" class="form-label">Year Level:</label>
                            <input type="text" class="form-control" id="yearlevel" name="yearlevel" value="<?php echo $student_data['yearlevel']; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender:</label>
                            <input type="text" class="form-control" id="gender" name="gender" value="<?php echo $student_data['gender']; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address:</label>
                            <input type="text" class="form-control" id="address" name="address" value="<?php echo $student_data['address']; ?>" readonly>
                        </div>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <select name="purpose" style="background-color: #fff;; color: black; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: background-color 0.3s;">
                            <option value="">Select a Purpose</option>
                            <option value="Python">Python</option>
                            <option value="java">Java</option>
                            <option value="Elnet">Elnet</option>
                            <option value="C#">C#</option>
                            <option value="android">Android</option>
                        </select>
                        <select name="lab"  style="background-color: #fff;; color: black; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: background-color 0.3s;">
                            <option value="">Select a Laboratory</option>
                            <option value="524">524</option>
                            <option value="526">526</option>
                            <option value="528">528</option>
                            <option value="527">527</option>
                            <option value="542">542</option>
                        </select>
                        <div class="mb-3">
                            <label for="remaining_session" class="form-label">Remaining Sessions:</label>
                            <?php
                            // Fetch the remaining session from the sitin_student table
                            $sql_remaining_session = "SELECT remaining_session FROM sitin_student WHERE idnumber = $idnumber";
                            $result_remaining_session = $conn->query($sql_remaining_session);
                            if ($result_remaining_session && $result_remaining_session->num_rows > 0) {
                                $row_remaining_session = $result_remaining_session->fetch_assoc();
                                $remaining_session = $row_remaining_session['remaining_session'];
                            }
                            ?>
                            <input type="text" class="form-control" id="remaining_session" name="remaining_session" value="<?php echo $remaining_session; ?>">
                        </div>
                        <input type="hidden" name="idnumber" value="<?php echo $student_data['idnumber'];?>"/>
                        <div style="text-align: center;">
                            <button type="submit" name="sitin_button" style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: background-color 0.3s;">
                                SITIN
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="row justify-content-center mt-4">
                <div class="col-md-6">
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-rq9MUd0I8lWVUgDFBGB3GXMo2mX9X1/nc+SAxK2Ip9wpeGqYTrn7z5SfFbiuoRu/" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/22Vr5ZXvoO4zPwiZfH7p4i/l1iau+9nE9lVNZeCI" crossorigin="anonymous"></script>
</body>
</html>
