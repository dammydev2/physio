<?php
include("include/connect.php");
include("include/Service-edit-inc.php");
$User = $_SESSION['User'];
$sel = "SELECT * FROM admin WHERE user='$User' ";
$res = $conn->query($sel);
if ($res->num_rows == 1) {
  if (isset($_POST['nh'])) {
    $Code = $_POST['Code'];
    $sel2 = "SELECT * FROM nhis_service WHERE NHIS_Code='$Code' ";
    $res2 = $conn->query($sel2);
    if ($res2->num_rows < 1) {
      $err = "<dt style='color: red;'>No Record Found</dt>";
    }
    else{
      while ($row = $res2->fetch_array()) {
      $Code2 = $row[1];
      $Description = $row[2];
      $Price = $row[3];
      }
    }
  }
  $active = "welcome";
  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <title>Matrix Admin</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/bootstrap-responsive.min.css" />
    <link rel="stylesheet" href="css/uniform.css" />
    <link rel="stylesheet" href="css/select2.css" />
    <link rel="styles