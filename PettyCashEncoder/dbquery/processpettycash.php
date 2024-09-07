<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "../config.php";

$conn = open_connection();

date_default_timezone_set('Asia/Manila');
session_start();

if (!isset($_SESSION['Admin_id'])) {
    echo json_encode(["status" => 0, "message" => "User not logged in"]);
    exit();
}

if (isset($_POST['editPettycash_id']) && !empty($_POST['editPettycash_id'])) {
    // EDIT
    $editPettycash_id = mysqli_real_escape_string($conn, $_POST['editPettycash_id']);
    
    $sql = "SELECT tblpettycash.*, tblpettycash.pc_Returnables, tblbank.Bank, tblpettycash.pc_Description, tblpettycash.pc_Project, tblpettycash.pc_Lastmodified, tbladmins.Admin_nickname, tblstatus.PettyStatus, tblremarks.Remarks
    FROM tblpettycash
    LEFT JOIN tblbank ON tblpettycash.Bank_id = tblbank.Bank_id
    LEFT JOIN tbladmins ON tblpettycash.Admin_id = tbladmins.Admin_id
    LEFT JOIN tblstatus ON tblpettycash.Status_id = tblstatus.Status_id
    LEFT JOIN tblremarks ON tblpettycash.Remarks_id = tblremarks.Remarks_id
    WHERE tblpettycash.Pettycash_id = '$editPettycash_id'";

    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode([
            'status' => 1,
            'data' => $row
        ]);
    } else {
        echo json_encode(['status' => 0, 'message' => 'No data found']);
    }
    exit();
} else {
    // Handle Add/Update
    $Pettycash_id = isset($_POST["Pettycash_id"]) ? mysqli_real_escape_string($conn, $_POST["Pettycash_id"]) : 0;

    $pcDV_number = isset($_POST["seriesNumber"]) ? mysqli_real_escape_string($conn, $_POST["seriesNumber"]) : '';
    $pc_Date = isset($_POST["requestDate"]) ? mysqli_real_escape_string($conn, $_POST["requestDate"]) : '';
    $pc_Name = isset($_POST["requestName"]) ? mysqli_real_escape_string($conn, $_POST["requestName"]) : '';
    $pc_Amount = isset($_POST["requestAmount"]) ? mysqli_real_escape_string($conn, $_POST["requestAmount"]) : '0';
    $pc_ActualExpenses = isset($_POST["requestActexpenses"]) ? mysqli_real_escape_string($conn, $_POST["requestActexpenses"]) : '0';
    $Bank_id = isset($_POST["pettycashbank"]) ? mysqli_real_escape_string($conn, $_POST["pettycashbank"]) : '';
    $pc_Description = isset($_POST["requestDesc"]) ? mysqli_real_escape_string($conn, $_POST["requestDesc"]) : '';
    $pc_Project = isset($_POST["requestProj"]) ? mysqli_real_escape_string($conn, $_POST["requestProj"]) : '';
    $Admin_id = isset($_POST["pettycustodian"]) ? mysqli_real_escape_string($conn, $_POST["pettycustodian"]) : '';
    $Status_id = isset($_POST["pettycashstatus"]) ? mysqli_real_escape_string($conn, $_POST["pettycashstatus"]) : '';
    $Remarks_id = isset($_POST["pettyremarks"]) ? mysqli_real_escape_string($conn, $_POST["pettyremarks"]) : '';

    $pc_Lastmodified = date('Y-m-d H:i:s'); // Use datetime format for consistency
    $pc_Returnables = null;

    if ($Status_id == 2 || $Status_id == 3) {
        $pc_Returnables = $pc_Amount - $pc_ActualExpenses;
    } 

    if ($Pettycash_id > 0) {
        // UPDATE
        $updatesql = "UPDATE tblpettycash SET pcDV_number = '$pcDV_number', pc_Date = '$pc_Date', pc_Name = '$pc_Name', pc_Amount = '$pc_Amount', pc_ActualExpenses = '$pc_ActualExpenses', 
            pc_Returnables = '$pc_Returnables', Bank_id = '$Bank_id', pc_Description = '$pc_Description', pc_Project = '$pc_Project', pc_Lastmodified = '$pc_Lastmodified', Admin_id = '$Admin_id', 
            Status_id = '$Status_id', Remarks_id = '$Remarks_id' WHERE Pettycash_id = '$Pettycash_id'";
        
        $updateResult = mysqli_query($conn, $updatesql);
        
        if ($updateResult) {
            echo json_encode(["status" => 1, "message" => "Petty Cash Updated Successfully!"]);
        } else {
            echo json_encode(["status" => 0, "message" => "Error updating Petty Cash: " . mysqli_error($conn)]);
        }
    } else {
        // ADD
        $insertSql = "INSERT INTO tblpettycash 
            (pcDV_number, pc_Date, pc_Name, pc_Amount, pc_ActualExpenses, Bank_id, pc_Description, pc_Project, Admin_id, Status_id, Remarks_id) 
            VALUES ('$pcDV_number', '$pc_Date', '$pc_Name', '$pc_Amount', '$pc_ActualExpenses', '$Bank_id', '$pc_Description', '$pc_Project', '$Admin_id', '$Status_id', '$Remarks_id')";
    
        $result = mysqli_query($conn, $insertSql);
        
        if ($result) {
            $Pettycash_id = mysqli_insert_id($conn);
            echo json_encode(["status" => 1, "message" => "Petty Cash Added Successfully!", "Pettycash_id" => $Pettycash_id]);
        } else {
            echo json_encode(["status" => 0, "message" => "Error saving Petty Cash: " . mysqli_error($conn)]);
        }
    }
    
}
?>
