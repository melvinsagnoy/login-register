<?php
session_start();

if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $role = $_POST["role"]; // Get the selected role

    require_once "database.php";

    // Select the appropriate table based on the role
    if ($role === "student") {
        $table = "users";
    } elseif ($role === "admin") {
        $table = "admin_users";
    } else {
        echo "<div class='alert alert-danger'>Invalid role selected</div>";
        exit();
    }

    // Prepare the SQL query to retrieve user data
    $sql = "SELECT * FROM $table WHERE username = ?";

    // Use prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        // Fetch user data
        $user = mysqli_fetch_assoc($result);
        if ($password === $user["password"]) { // Check password directly
            // Password matches, set session and redirect
            $_SESSION["user"] = $role; // Store role in session
            $_SESSION["idnumber"] = $user["idnumber"];
            if ($role === "student") {
                header("Location: student_dashboard.php");
                exit();
            } elseif ($role === "admin") {
                header("Location: admin_dashboard.php");
                exit();
            }
        } else {
            // Password does not match
            echo "<div class='alert alert-danger'>Password does not match</div>";
        }
    } else {
        // Username does not exist
        echo "<div class='alert alert-danger'>Username does not exist</div>";
        // Debugging: Uncomment the next line to see the SQL query being executed
        // echo "<div class='alert alert-danger'>SQL Query: $sql</div>";
    }

    // Close the statement and connection
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
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-image: url('images/log-in.jpg');
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
    <div class="imgcontainer" style="float: left;">
        <img src="images/profile.png" alt="Profile" width="600">
    </div>
    <h1 style="color: black;">CCS SIT-IN MONITORING SYSTEM</h1>
    <div class="container-fluid">
        <div class="row justify-content-end">
            <div class="col-md-6">
                <div class="container">
                    <div class="imgcontainer center">
                        <img src="images/user-icon.png" alt="User" class="user" width="100" height="100">
                    </div>
                    <form action="login.php" method="post">
                        <input type="hidden" name="login" value="1"> <!-- Add hidden input for login -->
                        <div class="form-group">
                            <label for="role">Select Role:</label>
                            <select id="role" name="role" class="form-control">
                                <option value="student">Student</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" id="username" name="username" placeholder="Enter Username"
                                class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" placeholder="Enter Password"
                                class="form-control">
                        </div>
                        <div class="form-btn">
                            <input type="submit" value="Login" name="login" class="btn btn-primary">
                        </div>
                    </form>
                    <div class="center">
                        <p>Not registered yet <a href="registration.php">Register Here</a></p>
                        <style>
                            .center {
                                text-align: center;
                            }
                        </style>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
