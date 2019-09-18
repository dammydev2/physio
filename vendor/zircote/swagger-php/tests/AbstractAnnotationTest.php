ss="visible-phone"><i class="icon icon-list"></i>Forms</a>
      <?php include ("include/sidebar.php");?>
    </div>
    <div id="content">
      <div id="content-header">
        <div id="breadcrumb"> <a href="welcome.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#">NHIS Drugs</a> <a href="#" class="current">Edit Drugs</a> </div>
        <h1>Edit NHIS Drugs Details</h1>
      </div>
      <div class="container-fluid"><hr>
        <div class="row-fluid">
          <div class="span12">
            <div class="widget-box">
              <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
                <h5>Select Drug category and enter drug number to Edit</h5> <?php echo $err;?>
              </div>
              <div class="widget-content nopadding">
                <form class="form-horizontal" method="POST" action="#" name="basic_validate" id="basic_validate" novalidate="novalidate">
                  <div class="control-group">
                    <label class="control-label">Drug NO</label>
                    <div class="controls">
                      <input type="text" name="No" id="required" required="required" onkeypress="return isNumberKey(event)">
                      <input type="submit" name="nh" value="Search Patient" class="btn btn-success">
                    </div>
                  </div>
                </form>
              </div>
            </div>

            <div class="widget-box">
              <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
                <h5>Edit patient details here</h5> 
              </div>
              <div class="widget-content nopadding">
                <form class="form-horizontal" method="POST" action="#" name="basic_validate" id="basic_validate" novalidate="novalidate">
                  <div class="control-group">
                    <label class="control-label">Drug Category</label>
                    <div class="controls">
                      <input type="text" name="Cat" readonly="readonly" value="<?php echo $Cat2;?>" id="required" required="required">
                      <input type="text" name="No2" readonly="readonly" value="<?php echo $No;?>" id="required" required="required" style="display: none;">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label">Drug Name</label>
                    <div class="controls">
                      <input type="text" name="Name" id="required" value="<?php echo $Name;?>" required="required">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label">Dosage Form</label>
                    <div class="controls">
                      <input type="text" name="Dosage" id="HMO" value="<?php echo $Dosage;?>" required="required">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label">Strength</label>
                    <div class="controls">
                      <input type="text" name="Strength" id="required" value="<?php echo $Strength;?>" required="required">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label">Presentation</label>
                    <div class="controls">
                      <input type="text" name="Presentation" id="required" value="<?php echo $Presentation;?>" required="required">
                    </div>
                  </div>
        