ckbox" id="title-checkbox" name="title-checkbox" />
      </span>
      <h5>Add Details</h5>
      <div class="widget-content nopadding">
        <div class="control-group">
          <label class="control-label"></label>
          <!--div class="controls">
            <select class='drugs'>
              <option>SELECT DRUG</option>
            <?php
            $sql = "SELECT * FROM drug ORDER BY Name ASC";
            $result = $conn->query($sql);
            #$selectLoco = "<select class='drugs'>";
            while ($row = $result->fetch_array() ) {
              $selectLoco .= "<option data-price='$row[6]' data-id='$row[0]' data-dosage='$row[3]' data-num='". $id2."'> $row[2].$row[3] </option>";
            }
            $selectLoco .= "</select>";
            echo "$selectLoco";
            ?>
          </div-->
        </div>
      </div>
    </div>
    <hr>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
            <h5>Main Description</h5>
          </div>
          <div class="widget-content nopadding">
            <form method="post" action="include/query3.php">
              <table class="table table-bordered table-striped list">
                <thead>
                  <tr>
                    <th>Dtails</th>
                    <th>Amount</th>
                    <th>Days</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><input type="text" name="nm[]" value="INITIAL SPECIALIST CONSULTATION" readonly></td>
                    <td><input type="number" min="1" name="amount[]"></td>
                    <td><input type="number" min="1" value="1" name="day[]" style="display: none;"><input type="text" min="1" value="<?php echo $id2; ?>" name="num[]" style="display: none;"></td>
                  </tr>
                  <tr>
                    <td><input type="text" name="nm[]" value="NUMBER OF REVIEWS" readonly></td>
                    <td><input type="number" min="1" name="amount[]"></td>
                    <td><input type="number" min="1" name="day[]"></td>
                  </tr>
                  <tr>
                    <td><input type="text" name="nm[]" value="NURSING CARE DAYS" readonly></td>
                    <td><input type="number" min="1" name="amount[]"></td>
                    <td><input type="number" min="1" name="day[]"></td>
                  </tr>
                  <tr>
                    <td><input type="text" name="nm[]" value="OTHERS / SUBSEQUENT VISITS" readonly></td>
                    <td><input type="number" min="1" name="amount[]"></td>
                    <td><input type="number" min="1" name="day[]"></td>
                  </tr>
                </tbody>
              </table>
              <button type="submit">Submit</button>
            </form>
          </div>
        </div>
        <div class="widget-box">

          <div class="widget-content nopadding">

          </div>
          <div class="widget-box">
            <div class="widget-title"> <span 