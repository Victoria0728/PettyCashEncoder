<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include "../config.php";
$conn = open_connection();

if (!isset($_SESSION['Userlevel_id']) || !isset($_SESSION['Admin_id'])) {
    die('Session variables are not set');
}

$Userlevel_id = $_SESSION['Userlevel_id'];
$Admin_id = $_SESSION['Admin_id'];

$where = ($Userlevel_id == 1) ? "" : " AND tbladmins.Admin_id = '$Admin_id'";

$selectQuery = "SELECT tblpettycash.Pettycash_id, tblpettycash.pcDV_number, DATE_FORMAT(tblpettycash.pc_Date, '%m/%d/%Y') as pc_Date, tblpettycash.pc_Name, tblpettycash.pc_Amount, tbladmins.Admin_nickname, tblstatus.PettyStatus 
    FROM tblpettycash
    LEFT JOIN tblbank ON tblpettycash.Bank_id = tblbank.Bank_id
    LEFT JOIN tbladmins ON tblpettycash.Admin_id = tbladmins.Admin_id
    LEFT JOIN tblstatus ON tblpettycash.Status_id = tblstatus.Status_id
    WHERE 1 $where AND tblstatus.Status_id = 1";

$result = mysqli_query($conn, $selectQuery);

if (!$result) {
    die('Error in query: ' . mysqli_error($conn));
}

$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);

mysqli_close($conn);
?>
