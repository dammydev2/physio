ntrol-group">
                    <label class="control-label">Strength</label>
                    <div class="controls">
                      <input type="text" name="Strength" id="required" required="required">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label">Presentation</label>
                    <div class="controls">
                      <input type="text" name="Presentation" id="required" required="required">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label">Price</label>
                    <div class="controls">
                      <input type="text" name="Price" id="required" required="required" onkeypress="return isNumberKey(event)">
                    </div>
                  </div>
                  <div class="form-actions">
                    <input type="submit" name="submit" value="Add Drug" class="btn btn-success">
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
       