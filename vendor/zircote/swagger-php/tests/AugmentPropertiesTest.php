ef="welcome.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <!--a href="#">Patient</a--> <a href="#" class="current">Monthly Report</a> </div>
        <h1>Monthly Report</h1>
      </div>
      <div class="container-fluid"><hr>
        <div class="row-fluid">
          <div class="span12">
            <div class="widget-box">
              <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
                <h5>Select H M O  to edit monthly Report</h5> <?php echo $err;?>
              </div>
              <div class="widget-content nopadding">
                <form class="form-horizontal" method="POST" action="#" name="basic_validate" id="basic_validate" novalidate="novalidate">
                  <div class="control-group">
                    <label class="control-label">H M O</label>
                    <table class="table" border="1">
                      <tr>
                        <th>Month</th>
                        <th>Year</th>
                        <th>NHIS no</th>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Select to Edit</th>
                      </tr>
                    <?php 
                    $sel = "SELECT * FROM month WHERE provider='$prov' ORDER BY id DESC";
                    $res = $conn->query($sel);
                    while ($row = $res->fetch_array()) {
                      ?>
                      <tr>
                        <td><?php echo $row[1]; ?></td>
                        <td><?php echo $row[2]; ?></td>
                        <td><?php echo $row[7]; ?></td>
                        <td><?php echo $row[6]; ?></td>
                        <td><?php echo $row[3]; ?></td>
                        <td><input type="checkbox" name="id" value="<?php echo $row[0] ?>"><!--input type="submit" name="edit" value="<?php echo $row[0] ?>"--></td>
                      </tr>
                      <?php 
                    }//echo $id;
                     ?>
                     </table>
                      <input type="submit" name="nh" value="EDIT" class