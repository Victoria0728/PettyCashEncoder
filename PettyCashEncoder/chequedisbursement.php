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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />
    <title>Request | Petty Cash</title>
</head>

<body>

    <?php include "sidebar.php"; ?>

    <div class="main-content">
        <?php include "header.php"; ?>

        <main>
        <div class="row">
            <div class="col-md-1">
                <button class="filterbank btn-primary active" id="allbanks" data-bank="0" style="border: 1px solid black" onclick>ALL</button>
            </div>
            <div class="col-md-1">
                <button class="filterbank btn-primary" id="bpibank" style="border: 1px solid black" data-bank="1">BPI</button>
            </div>
            <div class="col-md-1">
                <button class="filterbank btn-primary" id="bdobank" style="border: 1px solid black" data-bank="2">BDO</button>
            </div>
        </div>

            <div class="liquidated-list">
                <div class="pettycash">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="liquidatedlist">
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
                                        <th>Option</th>
                                    </tr>
                                </thead>
                                <tbody id="liquidateddata">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-1">
                    <button id="exportToExcel" class="btn btn-success export">Export Excel</button>
                </div>
                <div class="col-md-1">
                    <button id="computeTotal" class="btn btn-success export">Compute</button>
                </div>
                <div class="col-md-3">
                    <input type="text" id="totalExpenses" class="form-control" readonly>
                </div>
            </div>

            <!-- Form -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form class="formpettycash" id="insertForm" method="post">
                            <input type="hidden" id="Pettycash_id" name="Pettycash_id" value="0">
                            <input type="hidden" id="pc_Lastmodified" name="pc_Lastmodified" value="0">                 

                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Petty Cash Request</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="visibleTextbox">
                                    <div class="row">
                                        <div class="col-md-6 mb-1">
                                            <label for="seriesNumber">Series No.:</label>
                                            <input type="number" class="form-control" id="seriesNumber" name="seriesNumber">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-1">
                                            <label for="requestDate">Date:</label>
                                            <input type="date" class="form-control" id="requestDate" name="requestDate" value="<?php echo $currentDate; ?>">
                                        </div>
                                        <div class="col-md-6 mb-1">
                                            <label for="requestAmount">Amount:</label>
                                            <input type="number" class="form-control" id="requestAmount" name="requestAmount">
                                        </div>
                                    </div>

                                    <div class="mb-1">
                                        <label for="requestName">Name:</label>
                                        <input type="text" class="form-control" id="requestName" name="requestName">
                                    </div>

                                    <div class="mb-1">
                                        <label for="pettycustodian">Custodian:</label>
                                        <select class="form-control" id="pettycustodian" name="pettycustodian">
                                            <option value="" disabled selected>Choose Custodian</option>
                                            <?php
                                            $select_query = mysqli_query($conn, "SELECT Admin_id, Admin_name FROM tbladmins");
                                            while ($res = mysqli_fetch_array($select_query)) { ?>
                                                <option value="<?php echo $res['Admin_id'] ?>">
                                                    <?php echo $res['Admin_name']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="hiddenTextbox">
                                    <div class="row">
                                        <div class="col-md-6 mb-1">
                                            <label for="requestActexpenses">Actual Expenses:</label>
                                            <input type="number" class="form-control" id="requestActexpenses" name="requestActexpenses">
                                        </div>

                                        <div class="hiddenreturnables">
                                            <div class="col-md-6 mb-1">
                                                <label for="pc_Returnables">Returnables:</label>
                                                <input id="pc_Returnables" name="pc_Returnables" value="0">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-1">
                                        <label for="requestDesc">Description:</label>
                                        <input type="text" class="form-control" id="requestDesc" name="requestDesc">
                                    </div>
                                    <div class="mb-1">
                                        <label for="requestProj">Project:</label>
                                        <input type="text" class="form-control" id="requestProj" name="requestProj">
                                    </div>

                                    <div class="col-md-6 mb-1">
                                            <label for="pettycashbank">Bank:</label>
                                            <select class="form-control" id="pettycashbank" name="pettycashbank">
                                                <option value="" disabled selected>Choose bank</option>
                                                <?php
                                                $select_query = mysqli_query($conn, "SELECT Bank_id, Bank FROM tblbank");
                                                while ($res = mysqli_fetch_array($select_query)) { ?>
                                                    <option value="<?php echo $res['Bank_id'] ?>">
                                                        <?php echo $res['Bank']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-1">
                                            <label for="pettycashstatus">Status:</label>
                                            <select class="form-control" id="pettycashstatus" name="pettycashstatus">
                                            <?php
                                            $select_query = mysqli_query($conn, "SELECT Status_id, PettyStatus FROM tblstatus");
                                            while ($res = mysqli_fetch_array($select_query)) { ?>
                                                <option value="<?php echo $res['Status_id']; ?>">
                                                    <?php echo $res['PettyStatus']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        </div>

                                    <div class="doneremarks">
                                        <div class="row">
                                            <div   div class="col-md-6 mb-1">
                                                <label for="pettyremarks">Remarks:</label>
                                                <select class="form-control" id="pettyremarks" name="pettyremarks">
                                                    <option value="" disabled selected>Choose Remarks</option>
                                                    <?php
                                                    $select_query = mysqli_query($conn, "SELECT Remarks_id, Remarks FROM tblremarks");
                                                    while ($res = mysqli_fetch_array($select_query)) { ?>
                                                        <option value="<?php echo $res['Remarks_id'] ?>">
                                                            <?php echo $res['Remarks']; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btnClose btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" id="btnSaveCash" class="btnSave btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
        <script src="js/script.js"></script>
        <script>
            $(document).ready( function () {
                var table = $('#liquidatedlist');
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
