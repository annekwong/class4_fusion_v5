<!--
<style type="text/css">
    #mainTable {
		width:1;
	}
</style>
<script src="<?php echo $this->webroot;?>js/sip_trace.js" type="text/javascript"></script> 
<?php
    //echo file_get_contents($drawfile);
?>
-->
<style type="text/css">
#shell_window { 
   overflow-y: auto;
   height:500px;
}
</style>

<div id="container">
<div id="shell_window">
<?php
    if(!empty($drawfile)) {
        echo str_replace("\n", "<br />", $drawfile);
    } else {
        echo _("<div id='content' style='height:100px;text-align:center'>No data!</div>");
    }
?>
</div>
</div>
         