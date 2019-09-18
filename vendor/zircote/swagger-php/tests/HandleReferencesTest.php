S Service Charges</a> <a href="#" class="current">Edit Service</a> </div>
        <h1>Edit Service Charge</h1>
      </div>
      <div class="container-fluid"><hr>
        <div class="row-fluid">
          <div class="span12">
            <div class="widget-box">
              <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
                <h5>Enter NHIS of service to edit</h5> <?php echo $err;?>
              </div>
              <div class="widget-content nopadding">
                <form class="form-horizontal" method="POST" action="#" name="basic_validate" id="basic_validate" novalidate="novalidate">
                  <div class="control-group">
                    <label class="control-label">NHIS Code</label>
                    <div class="controls">
                      <input type="text" name="Code" id="required" required="required">
                      <input type="submit" name="nh" value="Search Service" class="btn btn-success">
                    </div>
                  </div>
                </form>
              </div>
            </div>

            <div class="widget-box">
              <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
                <h5>Edit service details here</h5> 
              </div>
              <div class="widget-content nopadding">
                <form class="form-horizontal" method="POST" action="#" name="basic_validate" id="basic_validate" novalidate="novalidate">
                  <div class="control-group">
                    <label class="control-label">NHIS CODE</label>
                    <div class="controls">
                      <input 