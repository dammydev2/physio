<?php
include("include/connect.php");
include("include/patient-edit.php");
$User = $_SESSION['User'];
$sel = "SELECT * FROM admin WHERE user='$User' ";
$res = $conn->query($sel);
if ($res->num_rows == 1) {
  if (isset($_POST['nh'])) {
      $_SESSION['prov'] = $_POST['prov'];
      ?>
      <meta http-equiv="refresh" content="0; URL=http:edit-prov.php">
      <?php
      
    }
  
  $active = "welcome"; //error_reporting(E_ALL);
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
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
  </head>
  <body>

    <!--Header-part-->
    <div id="header">
      <h1><a href="dashboard.html">Matrix Admin</a></h1>
    </div>
    <!--close-Header-part--> 

    <!--top-Header-menu-->
    <div id="user-nav" class="navbar navbar-inverse">
      <ul class="nav">
        <li  class="dropdown" id="profile-messages" ><a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i class="icon icon-user"></i>  <span class="text">Welcome User</span><b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="#"><i class="icon-user"></i> My Profile</a></li>
            <li class="divider"></li>
            <li><a href="#"><i class="icon-check"></i> My Tasks</a></li>
            <li class="divider"></li>
            <li><a href="include/logout.php"><i class="icon-key"></i> Log Out</a></li>
          </ul>
        </li>
        <li class="dropdown" id="menu-messages"><a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle"><i class="icon icon-envelope"></i> <span class="text">Messages</span> <span class="label label-important">5</span> <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a class="sAdd" title="" href="#"><i class="icon-plus"></i> new message</a></li>
            <li class="divider"></li>
            <li><a class="sInbox" title="" href="#"><i class="icon-envelope"></i> inbox</a></li>
            <li class="divider"></li>
            <li><a class="sOutbox" title="" href="#"><i class="icon-arrow-up"></i> outbox</a></li>
            <li clas