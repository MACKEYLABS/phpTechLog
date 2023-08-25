<?php include 'auth_check.php'; ?>

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

        .logout-button {
            background-color: #d43f00; /* You can choose any color */
            border-radius: 5px;
            padding: 5px 15px;
            transition: background-color 0.3s ease; /* Smooth transition effect */
        }

        .logout-button:hover {
            background-color: #b23600; /* A darker shade for hover effect */
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
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Navigation items on the left -->
            <ul class="navbar-nav">
                <!-- Home button -->
                <li class="nav-item">
                    <a class="nav-link" href="/mac/techlog.php">Home</a>
                </li>
                <!-- Link to creating a new tech log -->
                <li class="nav-item">
                    <a class="nav-link" href="/mac/newtechlog.php" onclick="return confirm('Are you sure you want to start a new tech log? This will move current records to the historical archive.');">New Techlog</a>
                </li>
                <!-- Link to Reports -->
                <li class="nav-item">
                    <a class="nav-link" href="/mac/reports.php">Reports</a>
                </li>
                <!-- Add new users (only for Admin) -->
                <?php
                if($_SESSION['loggedin'] == 'admin') {
                    echo '<li class="nav-item"><a class="nav-link" href="/mac/register.php">Register</a></li>';
                }   
                ?>
                </ul>
            <!-- Navigation items on the right -->
            <ul class="navbar-nav ms-auto">
                <!-- Logout button -->
                <li class="nav-item ml-lg-3"> <!-- Added margin-left for some spacing -->
                    <a class="nav-link logout-button" href="/mac/logout.php">Logout</a>
                </li>
            </ul>
        </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="container my-5">
        <a class="btn btn-primary" href="/mac/create.php" role="button" style="background-color: green;">Create Ticket</a>
        <br>
        <table class="table">
            <thead>
                <tr>
                    <th>STATUS</th>
                    <th>DATE</th>
                    <th>LANE</th>
                    <th>REPORT TIME</th>
                    <th>START TIME</th>
                    <th>STOP TIME</th>
                    <th>REPORTED ISSUE</th>
                    <th>ACTUAL ISSUE</th>
                    <th>ACTION TAKEN</th>
                    <th>TECH</th>
                    <th>CONTACT SKIDATA</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
                include 'config.php';

                //Check connection
                if ($connection->connect_error) {
                    die("Connection failed: " . $connection->connect_error);
                }

                //Read all rows from database table
                $sql = "SELECT * FROM techlog";
                $result = $connection->query($sql);

                //Check if query executed correctly
                if (!$result) {
                    die("Invalid query: " . $connection->error);
                }

                //Read data of each row
                while ($row = $result->fetch_assoc()) {
                $rowClass = $row['isOpen'] == '1' ? 'Open' : '';
                echo "
                <tr class='$rowClass'>
                    <td>
                    " . ($row['isOpen'] == '1' ? '<span class="badge bg-warning">Open</span>' : '<span class="badge bg-success">Closed</span>') . "
                    </td>
                    <td>$row[dateOf]</td>
                    <td>$row[lane]</td>
                    <td>" . substr($row['reportTime'], 0, 5) . "</td>
                    <td>" . substr($row['startTime'], 0, 5) . "</td>
                    <td>" . substr($row['stopTime'], 0, 5) . "</td>
                    <td>$row[reportProb]</td>
                    <td>$row[actualProb]</td>
                    <td>$row[actionTaken]</td>
                    <td>$row[techNum]</td>
                    <td>" . ($row['skidata'] == '1' ? 'Yes' : 'No') . "</td>
                    
                    <td>
                        <a class='btn btn-primary btn-sm' href='/mac/edit.php?id=$row[id]'><i class='fas fa-edit'></i></a>
                        <a class='btn btn-danger btn-sm' href='/mac/delete.php?id=$row[id]'><i class='fas fa-trash-alt'></i></a>                        
                    </td>
                </tr>
                ";
                }
                ?>
                
            </tbody>
        </table>
    </div>
    <!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script> 
</body>
</html>