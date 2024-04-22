<?php
// Start the session at the beginning of the file
session_start();

if (isset($_POST["submit"])) {
    // Retrieve form data
    $role = $_POST["role"];
    $idnumber = $_POST["idnumber"];
    $fullname = $_POST["fullname"];
    $username = $_POST["username"];
    $gender = $_POST["gender"];
    $email = $_POST["email"];
    $yearlevel = $_POST["yearlevel"];
    $address = $_POST["address"];
    $password = $_POST["password"];
    $repeat_password = $_POST["repeat_password"];

    // Perform validation checks here if needed
    if ($password !== $repeat_password) {
        echo "<div class='alert alert-danger'>Passwords do not match</div>";
        exit();
    }

    // Connect to the database
    require_once "database.php";

    // Prepare and execute the SQL query using prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO " . ($role === "student" ? "users" : "admin_users") . " (idnumber, full_name, username, gender, email, yearlevel, address, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $idnumber, $fullname, $username, $gender, $email, $yearlevel, $address, $password);

    if ($stmt->execute()) {
        // Registration successful, redirect to login page
        header("Location: login.php");
        exit();
    } else {
        // Error handling if the query fails
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-image: url('images/image.jpg');
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 50px;
            background-color: #ffffff;
            box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
        }

        .form-group {
            margin-bottom: 30px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-btn {
            text-align: center;
        }

        .btn {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #408ec6;
        }
    </style>
</head>
<body>
    <div class="container">
        <form action="registration.php" method="post">
            <div class="form-group">
                <label for="role">Select Role:</label>
                <select id="role" name="role" class="form-control">
                    <option value="student">Student</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="idnumber" placeholder="ID Number">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="fullname" placeholder="Full Name">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="username" placeholder="Username">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="gender" placeholder="Gender">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="yearlevel" placeholder="Year Level">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="address" placeholder="Address">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password">
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Register" name="submit">
            </div>
        </form>
        <div class="center">
            <p>Already Registered? <a href="login.php">Login Here</a></p>
        </div>
    </div>
</body>
</html>
