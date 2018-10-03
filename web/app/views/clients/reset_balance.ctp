<div class="row-fluid">
    <form class="form-horizontal" method="post" action="<?php echo $this->webroot ?>clients/get_mutual_ingress_egress_detail/<?php echo base64_encode($this->params['pass'][0]); ?>">
        <div class="control-group">
          <label class="control-label" for="balance"><?php __('Balance')?></label>
          <div class="controls">
              <input type="text" id="balance" name="balance" class="validate[required,custom[number]]" data-prompt-position="bottomLeft" placeholder="<?php __('It will reset the mutual balance')?>">
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="begin_time"><?php __('Begin Time')?></label>
          <div class="controls">
            <input type="text" class="validate[required] Wdate" onclick="WdatePicker({dateFmt:'yyyy-MM-dd 00:00:00',lang:'en'})" data-prompt-position="bottomLeft" name="begin_time" id="begin_time">
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="description"><?php __('Description')?></label>
          <div class="controls">
            <textarea name="description"></textarea>
          </div>
        </div>
    </form>
</div>