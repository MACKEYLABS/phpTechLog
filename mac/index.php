<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>GOAA Technician Log</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
        
        <style>
            
            table {
                width: 100%;
            }

        </style>

    </head>
    <body>
        <div class="container my-5">
        <h2>MCO TECH LOG</h2>
        <a class="btn btn-primary" href="/mac/create.php" role="button" style="background-color: green;">Create Ticket</a>
        <br>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Lane #</th>
                    <th>Time Reported</th>
                    <th>Start Time</th>
                    <th>Stop Time</th>
                    <th>Reported Problem</th>
                    <th>Actual Probelm</th>
                    <th>Action Taken</th>
                    <th>Tech Number</th>
                    <th>Skidata Contacted</th>
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
                        <td>
                            <a class='btn btn-primary btn-sm' href='/mac/edit.php?id=$row[id]'>Edit</a>
                            <a class='btn btn-danger btn-sm' href='/mac/delete.php?id=$row[id]'>Delete</a>
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