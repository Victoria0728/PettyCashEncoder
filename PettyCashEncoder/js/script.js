$(document).ready(function () {

    // Login
    $("#loginForm").submit(function (event) {
        event.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            url: 'dbquery/processlogin.php',
            type: 'POST',
            data: formData,
            success: function (response) {
                if (response === 'success') {
                    window.location.href = 'dashboard.php';
                } else {
                    alert('Login failed. Please check your credentials.');
                }
            },
            error: function (xhr, status, error) {
                console.error("Error:", error);
            }
        });
    });

    $('.btnRequest').click(function(){
        $('#insertForm')[0].reset();

        $('#exampleModalLabel').text('Add Ticket');
        $('#btnSaveCash').text('Create');
    });

    $('#btnSaveCash').on('click', function() {
        var pettycashId = $('#Pettycash_id').val();
        var formData = $('#insertForm').serialize();
        
        var url = pettycashId == 0 ? 'dbquery/processpettycash.php' : 'dbquery/processpettycash.php';
        var method = 'POST';

        $.ajax({
            url: url,
            type: method,
            data: formData,
            success: function(response) {
                var res = JSON.parse(response);
                if (res.status === 1) {
                    alert(res.message);
                    $('#exampleModal').modal('hide');
                    fetchPettyCashData();
                    fetchPettyCashDataBank(0);
                } else {
                    alert('Error: ' + res.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: ", status, error);
            }
        });
    });

    if (window.location.pathname == '/PettyCashEncoder/request.php' || window.location.pathname == '/request.php') {
        fetchPettyCashData();
    } else if (window.location.pathname == '/PettyCashEncoder/chequedisbursement.php' || window.location.pathname == '/chequedisbursement.php') {
        fetchPettyCashDataBank(0);
    } else if (window.location.pathname == '/PettyCashEncoder/done.php' || window.location.pathname == '/done.php') {
        fetchPettyCashDataDone();
    }

    function fetchPettyCashData() {
        $.ajax({
            url: 'dbquery/viewpettycash.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                var tableBody = $('#requestdata');
                tableBody.empty();

                if (response.length === 0) {
                    tableBody.append('<tr><td colspan="7">No data available</td></tr>');
                } else {
                    $.each(response, function (index, pettycash) {
                        var row = $('<tr>');
                        row.append('<td>' + (pettycash.pcDV_number || '') + '</td>');
                        row.append('<td>' + (pettycash.pc_Date || '') + '</td>');
                        row.append('<td>' + (pettycash.pc_Name || '') + '</td>');
                        row.append('<td>' + (pettycash.pc_Amount || '') + '</td>');
                        row.append('<td>' + (pettycash.Admin_nickname || '') + '</td>');
                        row.append('<td>' + (pettycash.PettyStatus || '') + '</td>');
                        row.append('<td><button class="btn btn-warning btn-sm btnEditPettycash" data-pettycash-id="' + pettycash.Pettycash_id + '">Edit</button></td>');
                        tableBody.append(row);
                    });
                }

                var table = $('#requestlist').DataTable();
                table.clear().rows.add(tableBody.find('tr')).draw();

                append_events();
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error: ", status, error);
            }
        });
    }

        function computeTotalExpenses() {
            var total = 0;
            $('#liquidateddata tr').each(function() {
                var actualExpenses = $(this).find('td').eq(4).text();
                if ($.isNumeric(actualExpenses)) {
                    total += parseFloat(actualExpenses);
                }
            });
            $('#totalExpenses').val(total.toFixed(2));
        }

        function exportToExcel() {
            var wb = XLSX.utils.table_to_book(document.getElementById('liquidatedlist'), { sheet: "Sheet1" });
            XLSX.writeFile(wb, 'PettyCashData.xlsx');
        }

        $('.filterbank').click(function() {
            $('.filterbank').removeClass('active');
            $(this).addClass('active');
            var bank = $(this).data('bank');
            fetchPettyCashDataBank(bank);
        });

        $('#computeTotal').click(function() {
            computeTotalExpenses();
        });

        $('#exportToExcel').click(function() {
            exportToExcel();
        });
        
        function fetchPettyCashDataBank(bank) {
            $.ajax({
                url: 'dbquery/viewpettycashbank.php',
                type: 'GET',
                data: { bankID: bank },
                dataType: 'json',
                success: function(response) {
                    var tableBody = $('#liquidateddata');
                    tableBody.empty();

                    if (response.length === 0) {
                        tableBody.append('<tr><td colspan="11">No data available</td></tr>');
                    } else {
                        $.each(response, function(index, pettycash) {
                            var row = $('<tr>');
                            row.append('<td>' + (pettycash.pcDV_number || '') + '</td>');
                            row.append('<td>' + (pettycash.pc_Date || '') + '</td>');
                            row.append('<td>' + (pettycash.pc_Name || '') + '</td>');
                            row.append('<td>' + (pettycash.pc_Amount || '') + '</td>');
                            row.append('<td>' + (pettycash.pc_ActualExpenses || '') + '</td>');
                            row.append('<td>' + (pettycash.pc_Returnables || '') + '</td>');
                            row.append('<td>' + (pettycash.Bank || '') + '</td>');
                            row.append('<td>' + (pettycash.pc_Description || '') + '</td>');
                            row.append('<td>' + (pettycash.pc_Project || '') + '</td>');
                            row.append('<td><button class="btn btn-warning btn-sm btnEditPettycashbank" data-pettycash-id="' + pettycash.Pettycash_id + '">Edit</button></td>');
                            tableBody.append(row);
                        });

                        var table = $('#liquidatedlist').DataTable();
                        table.clear().rows.add(tableBody.find('tr')).draw();

                        append_events();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: ", status, error);
                }
            });
        }

        function fetchPettyCashDataDone() {
            $.ajax({
                url: 'dbquery/viewpettycashdone.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    var tableBody = $('#donedata');
                    tableBody.empty();
                    
                    if (response.length === 0) {
                        tableBody.append('<tr><td colspan="12">No data available</td></tr>');
                    } else {
                        $.each(response, function(index, pettycash) {
                            var row = $('<tr>');
                            row.append('<td>' + (pettycash.pcDV_number || '') + '</td>');
                            row.append('<td>' + (pettycash.pc_Date || '') + '</td>');
                            row.append('<td>' + (pettycash.pc_Name || '') + '</td>');
                            row.append('<td>' + (pettycash.pc_Amount || '') + '</td>');
                            row.append('<td>' + (pettycash.pc_ActualExpenses || '') + '</td>');
                            row.append('<td>' + (pettycash.pc_Returnables || '') + '</td>');
                            row.append('<td>' + (pettycash.Bank || '') + '</td>');
                            row.append('<td>' + (pettycash.pc_Description || '') + '</td>');
                            row.append('<td>' + (pettycash.pc_Project || '') + '</td>');
                            row.append('<td>' + (pettycash.Admin_nickname || '') + '</td>');
                            row.append('<td>' + (pettycash.Remarks || '') + '</td>');
                            row.append('<td>' + (pettycash.PettyStatus || '') + '</td>');
                            tableBody.append(row);
                        });
        
                        var table = $('#donelist').DataTable();
                        table.clear().rows.add(tableBody.find('tr')).draw();

                        append_events();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: ", status, error);
                }
            });
        }
        

        function append_events() {

            $(".btnEditPettycash").click(function() {
                $('#exampleModal').modal('show');
                var editPettycash_id = $(this).data('pettycash-id');
            
                $.ajax({
                    url: 'dbquery/processpettycash.php',
                    type: 'POST',
                    data: {
                        editPettycash_id: editPettycash_id,
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 1) {
                            $('.visibleTextbox').hide();
                            $('.hiddenTextbox').show();
                            $('.doneremarks').hide();
                            $('.hiddenreturnables').hide();

                            $('#Pettycash_id').val(editPettycash_id);
                            $('#seriesNumber').val(response.data.pcDV_number);
                            $('#requestDate').val(response.data.pc_Date);
                            $('#requestName').val(response.data.pc_Name);
                            $('#requestAmount').val(response.data.pc_Amount);
                            $('#requestActexpenses').val(response.data.pc_ActualExpenses);
                            $('#pc_Returnables').val(response.data.pc_Returnables);
                            $('#pettycashbank').val(response.data.Bank_id);
                            $('#requestDesc').val(response.data.pc_Description);
                            $('#requestProj').val(response.data.pc_Project);
                            $('#pettycustodian').val(response.data.Admin_id);
                            $('#pettycashstatus').val(response.data.Status_id);
                            $('#pettyremarks').val(response.data.Remarks_id);
                            
                            $('#exampleModalLabel').text('Edit Petty Cash');
                            $('#btnSaveCash').text('Update');
                        } else {
                            alert('Failed to fetch data.');
                        }
                    },
                    error: function() {
                        alert('An error occurred while processing the request.');
                    }
                });
            });

            $(".btnEditPettycashbank").click(function() {
                $('#exampleModal').modal('show');
                var editPettycash_id = $(this).data('pettycash-id');
            
                $.ajax({
                    url: 'dbquery/processpettycash.php',
                    type: 'POST',
                    data: {
                        editPettycash_id: editPettycash_id,
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 1) {
                            $('.visibleTextbox').show();
                            $('.hiddenTextbox').show();
                            $('.doneremarks').show();
                            $('.hiddenreturnables').show();

                            $('#Pettycash_id').val(editPettycash_id);
                            $('#seriesNumber').val(response.data.pcDV_number);
                            $('#requestDate').val(response.data.pc_Date);
                            $('#requestName').val(response.data.pc_Name);
                            $('#requestAmount').val(response.data.pc_Amount);
                            $('#requestActexpenses').val(response.data.pc_ActualExpenses);
                            $('#pc_Returnables').val(response.data.pc_Returnables);
                            $('#pettycashbank').val(response.data.Bank_id);
                            $('#requestDesc').val(response.data.pc_Description);
                            $('#requestProj').val(response.data.pc_Project);
                            $('#pettycustodian').val(response.data.Admin_id);
                            $('#pettycashstatus').val(response.data.Status_id);
                            $('#pettyremarks').val(response.data.Remarks_id);

                            $('#exampleModalLabel').text('Edit Petty Cash');
                            $('#btnSaveCash').text('Update');   
                        } else {
                            alert('Failed to fetch data.');
                        }
                    },
                    error: function() {
                        alert('An error occurred while processing the request.');
                    }
                });
            });
        }
});
