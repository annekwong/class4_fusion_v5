<div id="title">
  <h1><?php __('Add')?>  
  <?php echo $_GET['gress']=="egress"? "Egress": "" ; echo $_GET['gress']=="ingress"?"Ingress":"" ;?> 
  <?php __('Host')?></h1>
  <ul id="title-menu">

<li>

 <a class="link_back" href="javascript:history.back();" ><img width="16" height="16" alt="<?php echo __('goback',true);?>" src="<?php echo $this->webroot?>images/icon_back_white.png">&nbsp;<?php echo __('goback',true);?></a>
    		</li>

  </ul>
</div>