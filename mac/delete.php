<?php
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    include 'config.php';

    $sql = "DELETE FROM techlog WHERE id=$id";
    $connection->query($sql);
}

header("location: /mac/techlog.php");
exit;
?>