<?php
include "include/login-inc.php";
$month = date("m");
$year = date("Y");
#echo "<dt style='color: #fff;'>$year</dt>";
if ($year=='2020' && $month > 6 ) {
    echo "<dt style='color: yellow; text-align:center;'>Error 708: Database error. Program may be down anytime soon</dt>";
}
elseif ($year=='2020' && $month > 10) {
    header("location: upgrade.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>NHIS Admin</title><meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/bootstrap-responsive.min.css" />
    <link rel="stylesheet" href="css/matrix-login.css" />
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

</head>
<body>
    <div id="loginbox">    
    <div class="col-sm-12 text-center">
        <img src="img/logos.jpg" class="img img-circle" width="100"> 
    </div>       
        <form id="loginform" class="form-vertical" method="POST">
         <div class="control-group normal_text"> <h3><img src="img/logo.png" alt="Logo" /></h3></div>
         <div class="control-group">
            <div class="controls">
                <div class="main_input_box">
                    <span class="add-on bg_lg"><i class="icon-user"> </i></span><input required="required" type="text" name="User" placeholder="Username" />
                </div>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <div class="main_input_box">
                    <span class="add-on bg_ly"><i class="icon-lock"></i></span><input required="required" name="Pass" type="password" placeholder="Password" />
                </div>
            </div>
        </div>
        <div c