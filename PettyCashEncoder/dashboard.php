<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION["is_login"])) {

    header('Location: ' . 'index.php');

    die();
}

function LoadPettycash()
{
    $conn = open_connection();

    $Userlevel_id = $_SESSION['Userlevel_id'];
    $Admin_id = $_SESSION['Admin_id'];

    $where  = "";
    if ($Userlevel_id == 1) {
        $where  = "";
    } else {
        $where  = " AND tbladmins.Admin_id = '$Admin_id'";
    }

    $showPettyCash = "SELECT DATE_FORMAT(tblpettycash.pc_Date, '%m/%d/%Y') as pc_Date, tblpettycash.pcDV_number, tblpettycash.pc_Name, tblpettycash.pc_Amount, tbladmins.Admin_nickname, tblpettycash.pc_Description, tblpettycash.pc_Project, tblstatus.PettyStatus 
    FROM tblpettycash
    LEFT JOIN tbladmins ON tblpettycash.Admin_id = tbladmins.Admin_id
    LEFT JOIN tblstatus ON tblpettycash.Status_id = tblstatus.Status_id
    WHERE 1 $where
    ORDER BY tblpettycash.Pettycash_id DESC";

    $result = mysqli_query($conn, $showPettyCash);

    if (mysqli_num_rows($result) <= 0) {
        $result = 0;
    }

    $conn->close();
    return $result;
}

function loadrecentpettycashBPI()
{

    $conn = open_connection();

    $Userlevel_id = $_SESSION['Userlevel_id'];
    $Admin_id = $_SESSION['Admin_id'];

    $where  = "";
    if ($Userlevel_id == 1) {
        $where  = "";
    } else {
        $where  = " AND tbladmins.Admin_id = '$Admin_id'";
    }

    $recentQuery = "SELECT DATE_FORMAT(tblpettycash.pc_Date, '%m/%d/%Y') as pc_Date, tblpettycash.pcDV_number, tblpettycash.pc_Name, tblpettycash.pc_Amount, tbladmins.Admin_nickname, 
        tblbank.Bank, tblpettycash.pc_Description, tblpettycash.pc_Project, tblstatus.PettyStatus 
    FROM tblpettycash
    LEFT JOIN tblbank ON tblpettycash.Bank_id = tblbank.Bank_id
    LEFT JOIN tbladmins ON tblpettycash.Admin_id = tbladmins.Admin_id
    LEFT JOIN tblstatus ON tblpettycash.Status_id = tblstatus.Status_id
    WHERE 1 $where AND tblbank.Bank_id = 1
    ORDER BY tblpettycash.Pettycash_id DESC LIMIT 10";

    $result = mysqli_query($conn, $recentQuery);

    if (mysqli_num_rows($result) <= 0) {
        $result = 0;
    }

    $conn->close();

    return $result;
}

function loadrecentpettycashBDO()
{

    $conn = open_connection();

    $Userlevel_id = $_SESSION['Userlevel_id'];
    $Admin_id = $_SESSION['Admin_id'];

    $where  = "";
    if ($Userlevel_id == 1) {
        $where  = "";
    } else {
        $where  = " AND tbladmins.Admin_id = '$Admin_id'";
    }

    $recentQuery = "SELECT DATE_FORMAT(tblpettycash.pc_Date, '%m/%d/%Y') as pc_Date, tblpettycash.pcDV_number, tblpettycash.pc_Name, tblpettycash.pc_Amount,
        tblbank.Bank
    FROM tblpettycash
    LEFT JOIN tbladmins ON tblpettycash.Admin_id = tbladmins.Admin_id
    LEFT JOIN tblstatus ON tblpettycash.Status_id = tblstatus.Status_id
    LEFT JOIN tblbank ON tblpettycash.Bank_id = tblbank.Bank_id
    WHERE 1 $where AND tblbank.Bank_id = 2
    ORDER BY tblpettycash.Pettycash_id DESC LIMIT 10";

    $result = mysqli_query($conn, $recentQuery);

    if (mysqli_num_rows($result) <= 0) {
        $result = 0;
    }

    $conn->close();

    return $result;
}

function totalPettycashencoded()
{
    $conn = open_connection();

    $sql = "SELECT COUNT(*) AS total FROM tblpettycash";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $totalPettycash = $row["total"];
    } else {
        $totalPettycash = 0;
    }
    $conn->close();

    return $totalPettycash;
}

function totalPending()
{
    $conn = open_connection();

    $sql = "SELECT COUNT(*) AS total FROM tblpettycash
    LEFT JOIN tblstatus ON tblpettycash.Status_id = tblstatus.Status_id
    WHERE tblstatus.Status_id = 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $totalPending = $row["total"];
    } else {
        $totalPending = 0;
    }
    $conn->close();

    return $totalPending;
}

function totalLiquidated()
{
    $conn = open_connection();

    $sql = "SELECT COUNT(*) AS total FROM tblpettycash
    LEFT JOIN tblstatus ON tblpettycash.Status_id = tblstatus.Status_id
    WHERE tblstatus.Status_id = 3";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $totalLiquidated = $row["total"];
    } else {
        $totalLiquidated = 0;
    }
    $conn->close();

    return $totalLiquidated;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.2.1/css/fontawesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />
    <title>Dashboard | Petty Cash</title>
</head>

<body>

    <?php include "sidebar.php"; ?>

    <div class="main-content">
        <?php include "header.php"; ?>

        <main>
        <div class="cards">
                <div class="card-single">
                    <div>
                        <?php echo totalPettycashencoded(); ?>
                        <h1 id="pettyCount"></h1>
                        <span>Encoded Petty Cash</span>
                    </div>
                    <div>
                        <span class="bi bi-cash"></span>
                    </div>
                </div>

                <div class="card-single">
                    <div>
                        <?php echo totalPending( ); ?>
                        <h1 id="pettyCount"></h1>
                        <span>Pending</span>
                    </div>
                    <div>
                        <span class="bi bi-hourglass"></span>
                    </div>
                </div>

                <div class="card-single">
                    <div>
                        <?php echo totalLiquidated(); ?>
                        <h1 id="pettyCount"></h1>
                        <span>Done</span>
                    </div>
                    <div>
                        <span class="bi bi-check2-circle"></span>
                    </div>
                </div>
            </div>

            <div class="pettycash-list">
                <div class="pettycash">
                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="table table-bordered table-striped" id="pettycashlist">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Series</th>
                                        <th>Name</th>
                                        <th>Amount</th>
                                        <th>Custodian</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $result = LoadPettycash();
                                    if ($result) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $formattedDate = date('m/d/Y', strtotime($row['pc_Date']));
                                            echo "<tr>  
                                                <td>" . $formattedDate . "</td>
                                                <td>" . $row['pcDV_number'] . "</td>
                                                <td>" . $row['pc_Name'] . "</td>
                                                <td>" . $row['pc_Amount'] . "</td>
                                                <td>" . $row['Admin_nickname'] . "</td>
                                                <td>" . $row['PettyStatus'] . "</td>
                                            </tr>";
                                        }
                                    } else {
                                        echo "<tr><colspan='5'>No Petty Cash Found.</tr>>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="recent-grid">
                <!-- FOR BPI -->
                <div class="pettycash">
                    <div class="card">
                        <div class="card-header">
                            <h2>BPI</h2>

                            <!-- <a href="disbursementbpi.php"><button>See All <span class="bi bi-arrow-right"></span></button></a> -->
                        </div>

                        <div class="card-body">
                            <table>
                                <thead>
                                    <tr>
                                        <td>Date</td>
                                        <td>Series</td>
                                        <td>Name</td>
                                        <td>Amount</td>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <?php
                                    $result = loadrecentpettycashBPI();
                                    if ($result) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $formattedDate = date('m/d/Y', strtotime($row['pc_Date']));
                                            echo "<tr>  
                                                <td>" . $formattedDate . "</td>
                                                <td>" . $row['pcDV_number'] . "</td>
                                                <td>" . $row['pc_Name'] . "</td>
                                                <td>" . $row['pc_Amount'] . "</td>
                                            </tr>";
                                        }
                                    } else {
                                        echo "<tr><colspan='5'>No Petty Cash Found.</tr>>";
                                    }
                                    ?>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- FOR BDO -->
                <div class="pettycash">
                    <div class="card">
                        <div class="card-header">
                            <h2>BDO</h2>

                            <!-- <a href="disbursementbdo.php"><button>See All <span class="bi bi-arrow-right"></span></button></a> -->
                        </div>

                        <div class="card-body">
                            <table>
                                <thead>
                                    <tr>
                                        <td>Date</td>
                                        <td>Series</td>
                                        <td>Name</td>
                                        <td>Amount</td>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <?php
                                    $result = loadrecentpettycashBDO();
                                    if ($result) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $formattedDate = date('m/d/Y', strtotime($row['pc_Date']));
                                            echo "<tr>  
                                                <td>" . $formattedDate . "</td>
                                                <td>" . $row['pcDV_number'] . "</td>
                                                <td>" . $row['pc_Name'] . "</td>
                                                <td>" . $row['pc_Amount'] . "</td>
                                            </tr>";
                                        }
                                    } else {
                                        echo "<tr><colspan='5'>No Petty Cash Found.</tr>>";
                                    }
                                    ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- END -->

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

        <script src="js/script.js"></script>
        <script>
            $(document).ready( function () {
                $('#pettycashlist').DataTable();
            });
        </script>
</body>

</html>