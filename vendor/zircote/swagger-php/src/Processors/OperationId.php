<?php
include("include/connect.php");
include("include/Drug-edit-inc.php");
$User = $_SESSION['User'];
$sel = "SELECT * FROM admin WHERE user='$User' ";
$res = $conn->query($sel);
if ($res->num_rows == 1) {
  if (isset($_POST['nh'])) {
    $No = $_POST['No'];
    $sel2 = "SELECT * FROM drug WHERE  id='$No' ";
    $res2 = $conn->query($sel2);
    if ($res2->num_rows < 1) {
      $err = "<dt style='color: red;'>No Record Found</dt>";
    }
    else{
      while ($row = $res2->fetch_array()) {
        $Cat2 = $row[1];
        $Name = $row[2];
        $Dosage = $row[3];
        $Strength = $row[4];
        $Presentation = $row[5];
        $Price = $row[6];
      }
    }
  }
  $active = "welcome";
  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <title>Edit Drugs NHIS</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/bootstrap-responsive.min.css" />
    <link rel="stylesheet" href="css/uniform.css" />
    <link rel="stylesheet" href="css/sele