<div class="row-fluid">
    <form class="form-horizontal" method="post" action="<?php echo $this->webroot ?>clients/change_password/<?php echo $this->params['pass'][0]; ?>">
        <div class="control-group">
          <label class="control-label" for="password"><?php __('Password')?></label>
          <div class="controls">
              <input type="password" id="password" name="password" class="validate[required]" data-prompt-position="bottomLeft" placeholder="<?php __('Please input a new password of this client')?>">
          </div>
        </div>
    </form>
</div>