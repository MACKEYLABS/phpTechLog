<?php
// Database connection
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php';

// Initialize results to null
$result = null;

if (isset($_POST['search'])) {
    $startDate = mysqli_real_escape_string($connection, $_POST['startDate']);
    $endDate = mysqli_real_escape_string($connection, $_POST['endDate']);
    $lane = mysqli_real_escape_string($connection, $_POST['lane']);
    $techNum = mysqli_real_escape_string($connection, $_POST['techNum']);
    
    // Build the query dynamically
    $sql = "SELECT * FROM techlog_archive WHERE 1"; 

    if (!empty($startDate) && !empty($endDate)) {
        $sql .= " AND dateOf BETWEEN '$startDate' AND '$endDate'";
    }
    if (!empty($lane)) {
        $sql .= " AND lane = '$lane'";
    }
    if (!empty($techNum)) {
        $sql .= " AND techNum = '$techNum'";
    }
    
    // Then, run the query as before.
    $result = $connection->query($sql);
    if (!$result) {
        die("Invalid query: " . $connection->error);
    }
}
    if ($result && $result->num_rows > 0 && isset($_POST['export']) && $_POST['export'] === 'csv') {
        header('Content-Type: text/csv');
        $filename = "MCO TECHLOG " . date('m-d-Y') . ".csv";
        header("Content-Disposition: attachment; filename=\"$filename\"");

        $output = fopen('php://output', 'w');
        fputcsv($output, ['STATUS', 'DATE', 'LANE', 'REPORT TIME', 'START TIME', 'STOP TIME', 'REPORTED ISSUE', 'ACTUAL ISSUE', 'ACTION TAKEN', 'TECH', 'CONTACT SKIDATA']);

        while ($row = $result->fetch_assoc()) {
            // ... Same code for extracting and writing data ...
            }

    fclose($output);
    exit;
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
                    <!--  Link to creting new tech log -->
                    <li class="nav-item">
                    <a class="nav-link" href="/mac/newtechlog.php" onclick="return confirm('Are you sure you want to start a new tech log? This will move current records to the historical archive.');">Start New Techlog</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/reports.php">Reports</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container my-4">
    <h2>Search Reports</h2>
    <form action="/mac/reports.php" method="POST">
        <div class="row">
            <div class="col-md-3">
                <label for="startDate">Start Date:</label>
                <input type="date" name="startDate" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="endDate">End Date:</label>
                <input type="date" name="endDate" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="lane">Lane:</label>
                <input type="text" name="lane" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="tech">Tech:</label>
                <input type="text" name="techNum" class="form-control">
            </div>
        </div>
        <br>
        <input type="submit" name="search" value="Search" class="btn btn-primary">
    </form>
    </div>

    <div class="container my-4">
    <?php if (!is_null($result) && $result->num_rows > 0): ?>
        <h3>Search Results:</h3>
        <table class="table table-bordered table-striped" id="resultsTable">
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
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <?php 
            // Check the 'isOpen' value to determine row class
            $rowClass = ($row['isOpen'] == '1') ? 'bg-warning' : 'bg-success'; 
            ?>
            <tr class="<?php echo $rowClass; ?>">
            <td>
            <?php echo ($row['isOpen'] == '1') ? '<span class="badge bg-warning">Open</span>' : '<span class="badge bg-success">Closed</span>'; ?>
            </td>
            <td><?php echo $row["dateOf"]; ?></td>
            <td><?php echo $row["lane"]; ?></td>
            <td><?php echo $row['reportTime']; ?></td>
            <td><?php echo $row['startTime']; ?></td>  
            <td><?php echo $row['stopTime']; ?></td>   
            <td><?php echo $row["reportProb"]; ?></td>
            <td><?php echo $row["actualProb"]; ?></td>
            <td><?php echo $row["actionTaken"]; ?></td>
            <td><?php echo $row["techNum"]; ?></td>
            <td><?php echo ($row['skidata'] == '1') ? 'Yes' : 'No'; ?></td>
        </tr>
    <?php endwhile; ?>
        </tbody>
    </table>

    <?php elseif (isset($result)): ?>
        <p>No results found for your search criteria.</p>
    <?php endif; ?>

    <!-- ... Your table code ... -->
    <?php if (!is_null($result) && $result->num_rows > 0): ?>
        <div class="my-3">
        <label for="exportFormat">Export as:</label>
            <select id="exportFormat" name="exportFormat">
                <option value="csv">CSV</option>
                <option value="pdf">PDF</option>
            </select>
            <button onclick="exportTable()">Export</button>
        </div>
    <?php endif; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>

    <script>
    function exportTable() {
        const format = document.getElementById('exportFormat').value;
        if (format === 'csv') {
        
            let form = document.createElement("form");
            form.method = "POST";
            form.action = "/mac/reports.php";

            let input = document.createElement("input");
            input.name = "export";
            input.value = "csv";
            form.appendChild(input);

            // Add the existing search values as hidden inputs
            ['startDate', 'endDate', 'lane', 'techNum'].forEach(name => {
            let hiddenInput = document.createElement("input");
            hiddenInput.type = "hidden";
            hiddenInput.name = name;
            hiddenInput.value = document.querySelector(`[name="${name}"]`).value;
            form.appendChild(hiddenInput);
            });

        document.body.appendChild(form);
        form.submit();

        } else if (format === 'pdf') {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4'); // 'l' stands for landscape
        
        // Get the current date
        let currentDate = new Date();
        let formattedDate = (currentDate.getMonth() + 1).toString().padStart(2, '0') + '-' + currentDate.getDate().toString().padStart(2, '0') + '-' + currentDate.getFullYear();
        
        // Create a title string
        let title = 'MCO TECHLOG ' + formattedDate;

        // Add title to the PDF at position (15, 15) which is 15mm from left and 15mm from top
        doc.setFontSize(18);
        doc.text(title, 15, 15);
        
        // Move the table down a bit to avoid overlap with the title
        doc.autoTable({ html: '#resultsTable', startY: 25 }); 

        doc.save(title + '.pdf');
        }
    }
</script>
</body>
</html>