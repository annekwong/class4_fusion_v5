<div class="row-fluid">
    <form class="form-horizontal" target="_blank" method="post" action="<?php echo $this->webroot ?>clients/download_balance">
        <div class="control-group">
          <label class="control-label"><?php __('Date')?></label>
          <div class="controls">
              <input type="text" autocomplete="off" value="<?php echo date('Y-m-d',time()) ?>" onclick="WdatePicker({dateFmt:'yyyy-MM-dd',lang:'en',maxDate:'<?php echo date('Y-m-d'); ?>'})" style="width:120px;" name="balance_date" class="input in-text in-input">
          </div>
        </div>
    </form>
</div>