<?php
session_start();
if (!isset($_SESSION["user"])) {
   header("Location: login.php");
   exit(); // Add exit to prevent further execution if not logged in
}

// Check if the "username" key exists in the session
$username = isset($_SESSION["username"]) ? $_SESSION["username"] : "Roy Dumasig";

// Database connection
include 'database.php'; // Include your database connection file

// Function to fetch sitin student records based on filters
function fetchSitinStudents($idnumber, $purpose, $lab, $date, $conn) {
    $sql = "SELECT * FROM sitin_student WHERE 1=1"; // Base SQL query

    // Add conditions based on filters
    if (!empty($idnumber)) {
        $sql .= " AND idnumber = '$idnumber'";
    }
    if (!empty($purpose)) {
        $sql .= " AND purpose = '$purpose'";
    }
    if (!empty($lab)) {
        $sql .= " AND lab = '$lab'";
    }
    if (!empty($date)) {
        $sql .= " AND date = '$date'";
    }

    // Execute SQL query
    $result = $conn->query($sql);

    // Check if query was successful
    if ($result && $result->num_rows > 0) {
        // Fetch and return records
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return array(); // Return an empty array if no records found
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $idnumber = $_POST["idnumber"];
    $purpose = $_POST["purpose"];
    $lab = $_POST["lab"];
    $date = $_POST["date"];

    // Fetch sitin student records based on filters
    $sitin_students = fetchSitinStudents($idnumber, $purpose, $lab, $date, $conn);
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
    <div class="container-fluid"> <!-- Changed container to container-fluid for wider navbar -->
        <a class="navbar-brand" href="#">Generate Reports</a>
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
    <div class="row">
        <div class="col-md-6">
            <form method="post">
                <div class="mb-3">
                    <label for="idnumber" class="form-label">ID Number:</label>
                    <input type="text" class="form-control" id="idnumber" name="idnumber">
                </div>
                <div class="mb-3">
                    <label for="purpose" class="form-label">Purpose:</label>
                    <select class="form-select" id="purpose" name="purpose">
                        <option value="">Select Purpose</option>
                        <option value="Python">Python</option>
                        <option value="Java">Java</option>
                        <option value="Elnet">Elnet</option>
                        <option value="C#">C#</option>
                        <option value="Android">Android</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="lab" class="form-label">Lab:</label>
                    <select class="form-select" id="lab" name="lab">
                        <option value="">Select Lab</option>
                        <option value="524">524</option>
                        <option value="526">526</option>
                        <option value="528">528</option>
                        <option value="527">527</option>
                        <option value="542">542</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">Date:</label>
                    <input type="date" class="form-control" id="date" name="date">
                </div>
                <button type="submit" class="btn btn-primary">View Report</button>
            </form>
        </div>
    </div>
</div>

<?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($sitin_students) && !empty($sitin_students)) : ?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h3>Sit-in Student Records</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID Number</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Year Level</th>
                            
                            <th>Purpose</th>
                            <th>Lab</th>
                            <th>Remaining Sessions</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sitin_students as $student) : ?>
                            <tr>
                                <td><?php echo $student["idnumber"]; ?></td>
                                <td><?php echo $student["full_name"]; ?></td>
                                <td><?php echo $student["email"]; ?></td>
                                <td><?php echo $student["yearlevel"]; ?></td>
                                <td><?php echo $student["purpose"]; ?></td>
                                <td><?php echo $student["lab"]; ?></td>
                                <td><?php echo $student["remaining_session"]; ?></td>
                                <td><?php echo $student["time_out"]; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
<?php endif; ?>

</body>
</html>
