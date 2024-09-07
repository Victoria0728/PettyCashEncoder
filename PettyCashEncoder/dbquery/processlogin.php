<?php
include "../config.php";
$conn = open_connection();

if (isset($_POST['email']) && $_POST['password']) {

    $Admin_email = $_POST['email'];
    $Admin_password = $_POST['password'];

    $sql = "SELECT * FROM tbladmins WHERE Admin_email='" . $Admin_email . "' AND Admin_password='" . $Admin_password . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) == 1) {
        session_start();

        $_SESSION["is_login"] = 1;

        $_SESSION["Admin_id"] = $row["Admin_id"];
        $_SESSION["Admin_name"] = $row["Admin_name"];
        $_SESSION["Userlevel_id"] = $row["Userlevel_id"];
        echo "success";
    } else {
        echo "error";
    }
}
