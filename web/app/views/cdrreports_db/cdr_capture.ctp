<style type="text/css">
    #mainTable {
		width:1;
	}
</style>
<script src="<?php echo $this->webroot;?>js/sip_trace.js" type="text/javascript"></script> 
<div id="title">
  <h1>
    <?php __('CDR Report')?>
    &gt;&gt;<?php echo __('View Sip Capture',true);?></h1>
</div>
<div id="container">
<?php
    if(!empty($drawfile)) {
        readfile($drawfile);
    } else {
        echo '<div class="msg">' . __('no_data_found',true) . '</div>';
    }
?>
</div>
         