<div class="row-fluid">
    <form class="form-horizontal" id="send_email_id" method="post" action="<?php echo $this->webroot ?>pr/pr_invoices/resend/<?php echo $this->params['pass'][0]?>/<?php echo $this->params['pass'][1]; ?>">
        <div class="control-group">
          <label class="control-label" for="email"><?php __('Email')?></label>
          <div class="controls">
            <input type="text" id="email" name="email" class="validate[required]" data-prompt-position="bottomLeft" placeholder="Please input an Email Address" value="<?php echo $billing_email; ?>">
          </div>
        </div>
    </form>
</div>