<style type="text/css">
#add_panel {display:none;}
</style>

<div id="title">
    <h1>
        <?php __('Routing')  ;  ?>
        &gt;&gt;
        <?php echo __('Trunk Priority')?>
    </h1>
</div>

<div id="container">
   <ul class="tabs">
    <li><a href="<?php echo $this->webroot; ?>dynamicroutes/qos/<?php echo $this->params['pass'][0];  ?>"><img width="16" height="16" src="<?php echo $this->webroot; ?>images/menuIcon.gif"> <?php __('List')?></a></li>
    <li class="active"><a href="<?php echo $this->webroot; ?>dynamicroutes/qos_import/<?php echo $this->params['pass'][0];  ?>"><img width="16" height="16" src="<?php echo $this->webroot; ?>images/import.png"> <?php __('Import')?></a></li>
    <li><a href="###"><img width="16" height="16" src="<?php echo $this->webroot; ?>images/export.png"> <?php __('export')?></a></li>
  </ul>
    <form method="post" enctype="multipart/form-data">
    <table class="cols" style="width:700px;margin:0px auto;">
        <tr>
            <td style="text-align:right;"><?php __('Upload File'); ?>:</td>
            <td style="text-align:left;"><input type="file" name="upfile" /></td>
        </tr>
        <tr>
            <td style="text-align:right;"><?php __('Method') ?>:</td>
            <td style="text-align:left;">
                <input type="radio" name="method" value="1" checked="checked" /><?php __('Ignore')?>
                <input type="radio" name="method" value="2" /><?php __('Delete')?>
            </td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="<?php __('Submit')?>" /></td>
        </tr>
    </table
    </form>
</div>
