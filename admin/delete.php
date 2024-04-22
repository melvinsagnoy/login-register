<?php
// Check if the form is submitted
if(isset($_POST['idnumber'])) {
    // Connect to the database
    require_once "database.php";

    // Get the ID of the student to be deleted
    $idnumber = $_POST['idnumber'];

    // Prepare the SQL statement to delete the student from the database
    $sql = "DELETE FROM users WHERE idnumber = ?";

    // Prepare the SQL statement
    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "i", $id);

    // Set the parameter value and execute the statement
    $id = $idnumber;
    if(mysqli_stmt_execute($stmt)) {
        // Student deleted successfully
        echo "<script>alert('Student deleted successfully');</script>";
        // Redirect back to the delete.php page after deletion
        header("Location: delete.php");
        exit();
    } else {
        // Error occurred
        echo "<script>alert('Error: Unable to delete student');</script>";
    }

    // Close the statement and database connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
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
        <a class="navbar-brand" href="#">Delete </a>
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

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Number</th>
                <th>Full Name</th>
                <th>Username</th>
                <th>Gender</th>
                <th>Email</th>
                <th>Year Level</th>
                <th>Address</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Connect to the database
            require_once "database.php";

            // Fetch students from the database
            $sql = "SELECT * FROM users";
            $result = mysqli_query($conn, $sql);

            // Display student data in table rows
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['idnumber'] . "</td>";
                echo "<td>" . $row['full_name'] . "</td>";
                echo "<td>" . $row['username'] . "</td>";
                echo "<td>" . $row['gender'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['yearlevel'] . "</td>";
                echo "<td>" . $row['address'] . "</td>";
                // Add a delete button with a form for each student
                echo "<td>";
                echo "<form action='delete.php' method='POST'>";
                echo "<input type='hidden' name='idnumber' value='" . $row['idnumber'] . "'>";
                echo "<button type='submit' class='btn btn-danger'><i class='fas fa-trash'></i> Delete</button>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
