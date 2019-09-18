er-fluid">
      <div class="widget-title"> <span class="icon">
        <input type="checkbox" id="title-checkbox" name="title-checkbox" />
      </span>
      <h5>Select Service(s)</h5>
      <div class="widget-content nopadding">
        <div class="control-group">
          <label class="control-label"></label>
          <div class="controls">
            <select class='drugs'>
              <option>SELECT SERVICE(S)</option>
            <?php
            $sql = "SELECT * FROM nhis_service ORDER BY Description ASC";
            $result = $conn->query($sql);
            #$selectLoco = "<select class='drugs'>";
            while ($row = $result->fetch_array() ) {
              $selectLoco .= "<option data-price='$row[3]' data-id='$row[0]' data-num='". $id2."'> $row[2] </option>";
            }
            $selectLoco .= "</select>";
            echo "$sele