<?php
include 'auth_check.php';
include 'config.php';

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Check if the user already exists
    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 0) { // If user does not exist
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ss", $username, $hashedPassword);

        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];
        
            if($password !== $confirmPassword) {
                $errorMessage = "Passwords do not match!";
            } else {
                // Your existing logic here ...
            }
        }
        
        
        if($stmt->execute()) {
            $successMessage = "Account created successfully!";
        } else {
            $errorMessage = "There was an error creating your account!";
        }

    } else {
        $errorMessage = "Username already exists!";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GOAA Technician Log</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* Updated Styling for the navbar */
        .navbar {
            background: linear-gradient(45deg, #0052D4, #65C7F7);
            box-shadow: 0 4px 12px 0 rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            color: #fff !important; /* Override default bootstrap styles */
        }

        .navbar-brand img {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }

        /* Hover Animation */
        .navbar-nav .nav-link {
            color: white !important; /* Override default bootstrap styles */
            transition: color 0.3s ease-in-out, padding 0.3s ease-in-out;
            margin: 0 5px;  /* Added for spacing */
            padding: 0 15px;  /* Increased padding for more spacing */
        }

        .navbar-nav .nav-link:not(:last-child)::after {
            content: '|';
            color: rgba(255, 255, 255, 0.5);
            margin-left: 5px;  /* Space out the divider a bit more */
        }

        .navbar-nav .nav-link:hover {
            color: #ddd !important; /* Override default bootstrap styles */
            padding-bottom: 2px;  /* Added a slight move effect on hover */
            border-bottom: 2px solid #ddd;  /* Added an underline effect on hover */
        }

        table {
            width: 100%;
        }

    </style>
</head>
<body>
    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/mac/index.php">
                <img src="assets/support-ticket.png" alt="MCO Icon"> 
                MCO TECH LOG
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <!-- Home button -->
                    <li class="nav-item">
                    <a class="nav-link" href="/mac/techlog.php">Home</a>
                    </li>
                    <!--  Link to creting new tech log -->
                    <li class="nav-item">
                    <a class="nav-link" href="/mac/newtechlog.php" onclick="return confirm('Are you sure you want to start a new tech log? This will move current records to the historical archive.');">Start New Techlog</a>
                    </li>
                    <!-- Link to Reports -->
                    <li class="nav-item">
                        <a class="nav-link" href="/mac/reports.php">Reports</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Create Account for MCO Techlog</h4>
                    </div>
                    <div class="card-body">
                        <form action="register.php" method="post">
                        <?php
                        if(isset($errorMessage)) {
                        echo "<div class='alert alert-danger'>$errorMessage</div>";
                        }
                        if(isset($successMessage)) {
                        echo "<div class='alert alert-success'>$successMessage</div>";
                        }
                        ?>

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
        <div class="mb-3">
            <input type="submit" value="Register" class="btn btn-primary w-100">
        </div>
    </form>
</div>

                </div>
            </div>
        </div>
    </div>
    <!-- ... scripts ... -->
</body>
</html>