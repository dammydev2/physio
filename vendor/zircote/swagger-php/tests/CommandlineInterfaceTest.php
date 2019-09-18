<span class="icon"> <i class="icon-info-sign"></i> </span>
                <h5>Select H M O  to edit monthly Report</h5> <?php echo $err;?>
              </div>
              <div class="widget-content nopadding">
                <form class="form-horizontal" method="POST" action="#" name="basic_validate" id="basic_validate" novalidate="novalidate">
                  <div class="control-group">
                    <label class="control-label">H M O</label>
                    <?php
                      echo "<select name='prov'>";
                      $sel = "SELECT DISTINCT provider FROM month";
                      $res = $conn->query($sel);
                      while ($row = $res->fetch_array()) {
                        echo "<option value='".$row['provider']."'>".$row['provider']."</option>";
                      }
                      echo "</select>";
                      ?>
                      <input type="submit" name="nh" value="Continue" class="btn btn-success">
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
    <!--div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
            <h5>Numeric validation</h5>
          </div>
          <div class="widget-content nopadding">
            <form class="form-horizontal" method="post" action="#" name="number_validate" id="number_validate" novalidate="novalidate">
              <div class="control-group">
                <label class="control-label">Minimal Salary</label>
                <div class="controls">