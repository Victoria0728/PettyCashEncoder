<?php
date_default_timezone_set('Asia/Manila');
session_start();

if (!isset($_SESSION["is_login"])) {
    header('Location: index.php');
    exit();
}

$currentDate = date('Y-m-d');
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
    <title>Request | Petty Cash</title>
</head>

<body>

    <?php include "sidebar.php"; ?>

    <div class="main-content">
        <?php include "header.php"; ?>

        <main>
            <div class="liquidated-list">
                <div class="pettycash">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="donelist">
                                <thead>
                                    <tr>
                                        <th>Series</th>
                                        <th>Date</th>
                                        <th>Name</th>
                                        <th>Amount</th>
                                        <th>Actual Expenses</th>
                                        <th>Returnables(Reimbursement)</th>
                                        <th>Bank</th>
                                        <th>Description</th>
                                        <th>Project</th>
                                        <th>Custodian</th>
                                        <th>Remarks</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="donedata">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
        <script src="js/script.js"></script>
        <script>
            $(document).ready( function () {
                var table = $('#donelist');
                if (table.length > 0) {
                    table.DataTable({
                        responsive: true,
                        "order": [[1, 'desc']]
                    });
                }
            });
        </script>
    </div>
</body>
</html>
