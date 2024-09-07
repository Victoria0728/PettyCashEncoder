<?php
include "config.php";

if (isset($_SESSION["Admin_name"])) {
    $userName = $_SESSION["Admin_name"];

    $conn = open_connection();
    $query = "SELECT tbladmins.Admin_name, tbldepartment.department FROM tbladmins
    LEFT JOIN tbldepartment ON tbladmins.Department_id = tbldepartment.department_id
    WHERE Admin_name = '$userName'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $userFullName = $row["Admin_name"];
        $userDepartment = $row["department"];
    } else {
        $userFullName = "Guest";
        $userDepartment = "Unknown Department";
    }
} else {
    $userFullName = "Guest";
    $userDepartment = "Unknown Department";
}
?>

<header>
    <label for="nav-toggle">
        <span class="bi bi-list"></span>
    </label>
    <h2 id="page-title">Dashboard</h2>

    <div class="user-wrapper">
        <span class="bi bi-person"></span>
        <div>
            <h5><?php echo $userFullName; ?></h5>
            <small><?php echo $userDepartment; ?></small>
        </div>
    </div>
</header>