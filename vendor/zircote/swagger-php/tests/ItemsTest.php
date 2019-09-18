<?php
include("include/connect.php");
include("include/patient-edit.php");
$User = $_SESSION['User'];
$sel = "SELECT * FROM admin WHERE user='$User' ";
$res = $conn->query($sel);
if ($res->num_rows == 1) {
  if (isset($_POST['nh'])) {
    $NHIS = $_POST['NHIS'];
    $sel2 = "SELECT * FROM patient WHERE NHIS='$NHIS' ";
    $res2 = $conn->query($sel2);
    if ($res2->num_rows < 1) {
      $err = "<dt style='color: red;'>No Record Found</dt>";
    }
    else{
      while ($row = $res2->fetch_array()) {
      $Name = $row[1];
      $HMO = $row[2];
      $HOSP = $row[3];
      $NHIS = $row[4];
      $Aut = $row[5];
      $Remarks = $row[6];
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
    <link rel="stylesheet" href