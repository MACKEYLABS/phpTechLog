<?php 
// Give output errors on webpage
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    do {
        if ($dateOf === '' || $lane === '' || $reportTime === '' || $startTime === '' || $stopTime === ''
        || $reportProb === '' || $actualProb === '' || $actionTaken === '' || $techNum === ''
        || $skidata === '' || $isOpen === '') {
            $errorMessage = "All the fields are required";
            break;
        }

        // Adding new client to DB
        $sql = "INSERT INTO techlog (dateOf, lane, reportTime, startTime, stopTime, reportProb, actualProb, 
        actionTaken, techNum, skidata, isOpen) " . "VALUES ('$dateOf', '$lane', '$reportTime', '$startTime', '$stopTime', 
        '$reportProb', '$actualProb', '$actionTaken', '$techNum', '$skidata', '$isOpen')";
        $result = $connection->query($sql);

        // Display error message if query fails
        if (!$result) {
            $errorMessage = "Invalid query: " . $connection->error;
            break;
        }

        // Clear the fields after submitting, you might want to handle data insert here
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

        $successMessage = "Client added correctly";

        header("location: /mac/index.php");
        exit;

    } while (false);
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
</head>
<body>
    <div class = "container my-5">
        <h2>New Client</h2>

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
            <!-- Is the ticket still open option -->
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
                    <a class="btn btn-outline-primary" href="/mac/index.php" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>