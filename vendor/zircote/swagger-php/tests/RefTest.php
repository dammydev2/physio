<?php
include("include/connect.php");
include("include/patient-inc.php");
$User = $_SESSION['User'];
$id2 = $_SESSION['$id2'];
$sel = $sel = "SELECT * FROM table_1 WHERE id2='$id2' ";
$res = $conn->query($sel);
while ($row = $res->fetch_array()) {
  $Name = $row['Name'];
}
$sel = $sel = "SELECT * FROM admin WHERE user='$User' ";
$res = $conn->query($sel);
if ($res->num_rows == 1) {
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
    <link rel="stylesheet" href="css/matrix-style.css" />
    <link rel="stylesheet" href="css/matrix-media.css" />
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link