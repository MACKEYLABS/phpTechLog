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
            <a class="navbar-brand" href="/">
                <img src="assets/support-ticket.png" alt="MCO Icon"> <!-- Make sure the path to your icon is correct -->
                MCO TECH LOG
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/path_to_new_techlog.php">Start New Techlog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/path_to_reports.php">Reports</a>
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
                    <th>ID</th>
                    <th>DATE</th>
                    <th>LANE</th>
                    <th>TIME REPORTED</th>
                    <th>START TIME</th>
                    <th>STOP TIME</th>
                    <th>REPORTED ISSUE</th>
                    <th>ACTUAL ISSUE</th>
                    <th>ACTION TAKEN</th>
                    <th>TECH</th>
                    <th>CONTACT SKIDATA</th>
                    <th>STATUS</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "mcodb";

                //Create connection
                $connection = new mysqli($servername, $username, $password, $database);

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
                echo "
                <tr>
                    <td>$row[id]</td>
                    <td>$row[dateOf]</td>
                    <td>$row[lane]</td>
                    <td>$row[reportTime]</td>
                    <td>$row[startTime]</td>
                    <td>$row[stopTime]</td>
                    <td>$row[reportProb]</td>
                    <td>$row[actualProb]</td>
                    <td>$row[actionTaken]</td>
                    <td>$row[techNum]</td>
                    <td>" . ($row['skidata'] == '1' ? 'Yes' : 'No') . "</td>
                    <td>" . ($row['isOpen'] == '1' ? 'Open' : 'Closed') . "</td>
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
</body>
</html>