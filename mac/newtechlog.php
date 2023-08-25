<?php
include 'auth_check.php';
include 'config.php';

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Step 1: Transfer all CLOSED records to techlog_archive
$transfer_closed_query = "
    INSERT INTO techlog_archive (dateOf, lane, reportTime, startTime, stopTime, reportProb, actualProb, actionTaken, techNum, skidata, isOpen)
    SELECT dateOf, lane, reportTime, startTime, stopTime, reportProb, actualProb, actionTaken, techNum, skidata, isOpen
    FROM techlog WHERE isOpen = 0";

if (!$connection->query($transfer_closed_query)) {
    die("Error transferring closed tickets to archive: " . $connection->error);
}

// Step 2: Delete all CLOSED records from the techlog table
$delete_closed_query = "DELETE FROM techlog WHERE isOpen = 0";
if (!$connection->query($delete_closed_query)) {
    die("Error deleting closed tickets: " . $connection->error);
}

header("Location: /mac/techlog.php");
exit;
?>

