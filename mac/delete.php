<?php
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $servername = "localhost";
    $username = "root"
    $password = "";
    $database = "mcodb"

    // Creating connection
    $connection = new mysqli($servername, $username, $password, $database);

    $sql = "DELETE FROM techlog WHERE id=$id";
    $connection->query($sql);
}

header("location: /mac/index.php");
exit;
?>