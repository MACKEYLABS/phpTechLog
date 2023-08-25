<?php
include 'auth_check.php';
include 'config.php';

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$id = "";
$dateOf = "";
$lane = "";
$reportTime = "";
$startTime = "";
$stopTime = "";
$reportProb = "";
$actualProb = "";
$actionTaken = "";
$techNum = "";
$skidata = "";
$isOpen = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Get method: Show the data of the client
    if (!isset($_GET["id"])) {
        header("location: /mac/techlog.php");
        exit;
    }

    $id = $_GET["id"];

    /*
    //read the row of the selected client from the database table
    $sql = "SELECT * FROM techlog WHERE id=$id";
    $result = $connection->query($sql);
    */

    // Reading the row of the selected client from the database table
    $stmt = $connection->prepare("SELECT * FROM techlog WHERE id=?");
    $stmt->bind_param("i", $id); // "i" means integer
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        header("location: /mac/techlog.php");
        exit;
    }

    $dateOf = $row["dateOf"];
    $lane = $row["lane"];
    $reportTime = $row["reportTime"];
    $startTime = $row["startTime"];
    $stopTime = $row["stopTime"];
    $reportProb = $row["reportProb"];
    $actualProb = $row["actualProb"];
    $actionTaken = $row["actionTaken"];
    $techNum = $row["techNum"];
    $skidata = $row["skidata"];
    $isOpen = $row["isOpen"];
}
else {
    //Post Method: Update the data of the client
    $id = $_POST["id"];
    $dateOf = $_POST["dateOf"];
    $lane = $_POST["lane"];
    $reportTime = $_POST["reportTime"];
    $startTime = $_POST["startTime"];
    $stopTime = $_POST["stopTime"];
    $reportProb = $_POST["reportProb"];
    $actualProb = $_POST["actualProb"];
    $actionTaken = $_POST["actionTaken"];
    $techNum = $_POST["techNum"];
    $skidata = $_POST["skidata"];
    $isOpen = $_POST["isOpen"];

    // Check if skidata value is neither 0 nor 1
    if ($skidata !== "0" && $skidata !== "1") {
        $errorMessage = "Invalid data for skidata";
    }

    do {
        if ($dateOf === '' || $lane === '' || $reportTime === '' || $startTime === '' || $stopTime === ''
        || $reportProb === '' || $actualProb === '' || $actionTaken === '' || $techNum === ''
        || $skidata === '' || $isOpen === '') {
            $errorMessage = "All the fields are required";
            break;
        }

        /*
        // Adding new client to DB
        $sql = "UPDATE techlog " . 
            "SET dateOf = '$dateOf', lane = '$lane', reportTime = '$reportTime', startTime = '$startTime', 
            stopTime = '$stopTime', reportProb = '$reportProb', actualProb = '$actualProb', 
            actionTaken = '$actionTaken', techNum = '$techNum', skidata = '$skidata', isOpen = '$isOpen' " . 
            "WHERE id = $id";
        // Check to see if query was successfully ran    
        $result = $connection->query($sql);
        */

        $stmt = $connection->prepare(
            "UPDATE techlog SET dateOf=?, lane=?, reportTime=?, startTime=?, 
            stopTime=?, reportProb=?, actualProb=?, actionTaken=?, techNum=?, skidata=?, isOpen=? WHERE id=?"
        );
        $stmt->bind_param(
            "ssssssssssii", 
            $dateOf, $lane, $reportTime, $startTime, $stopTime, $reportProb, $actualProb, 
            $actionTaken, $techNum, $skidata, $isOpen, $id
        );
        $result = $stmt->execute();
        $stmt->close();

        if (!$result) {
            $errorMessage = "Invalid query: " . $connection->error;
            break;
        }

        $successMessage = "Client updated successfully!";

        // Send user back to techlog.php
        header("location: /mac/techlog.php");
        exit;

    } while (true);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GOAA Tech Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
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
                    <a class="nav-link" href="/mac/index.php">Home</a>
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
    <div class = "container my-5">
        <h2>Edit Service Details:</h2>

        <?php
        if (!empty ($errorMessage)) {
            echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong>$errorMessage</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' arial-label='Close'></button>
            </div>
            ";
        }
        ?>

        <form method="post">
            <!-- Added for edit.php file-->
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <!-- Date Input -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Date</label>
                <div class="col-sm-6">
                    <input type="date" class="form-control" name="dateOf" value="<?php echo $dateOf; ?>">
                </div>
            </div>
            <!-- Lane # Input -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Lane #</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="lane" value="<?php echo $lane; ?>">
                </div>
            </div>
            <!-- Time Reported Input -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Time Reported</label>
                <div class="col-sm-6">
                    <input type="time" class="form-control" name="reportTime" value="<?php echo $reportTime; ?>">
                </div>
            </div>
            <!-- Start Time Input -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Start Time</label>
                <div class="col-sm-6">
                    <input type="time" class="form-control" name="startTime" value="<?php echo $startTime; ?>">
                </div>
            </div>
            <!-- Stop Time Input -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Stop Time</label>
                <div class="col-sm-6">
                    <input type="time" class="form-control" name="stopTime" value="<?php echo $stopTime; ?>">
                </div>
            </div>
            <!-- Reported Problem Input -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Reported Problem</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="reportProb" value="<?php echo $reportProb; ?>">
                </div>
            </div>
            <!-- Actual Problem Input -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Actual Problem</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="actualProb" value="<?php echo $actualProb; ?>">
                </div>
            </div>
            <!-- Action Taken Input -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Action Taken</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="actionTaken" value="<?php echo $actionTaken; ?>">
                </div>
            </div>
            <!-- Tech Number Input -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Tech Number</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="techNum" value="<?php echo $techNum; ?>">
                </div>
            </div>
            <!-- skidata Dropdown -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Skidata Contacted</label>
                <div class="col-sm-6">
                    <select class="form-control" name="skidata">
                        <option value="1" <?php echo ($skidata == '1') ? 'selected' : ''; ?>>Yes</option>
                        <option value="0" <?php echo ($skidata == '0') ? 'selected' : ''; ?>>No</option>
                    </select>
                </div>
            </div>
            <!-- Ticket Open Dropdown -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Ticket Status</label>
                <div class="col-sm-6">
                    <select class="form-control" name="isOpen">
                        <option value="1" <?php echo ($isOpen == '1') ? 'selected' : ''; ?>>Open</option>
                        <option value="0" <?php echo ($isOpen == '0') ? 'selected' : ''; ?>>Closed</option>
                    </select>
                </div>
            </div>

            <?php
            if (!empty($successMessage)) {
                echo "
                <div class='row mb-3'>
                    <div class='offset-sm-3 col-sm-6'>
                        <div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <strong>$successMessage</strong>
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label></button>
                        </div>
                    </div>
                </div>
                ";
            }
            ?>
            
            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="/mac/techlog.php" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>