<div id="title">
 	<h1><?php echo __('Soft Swap',true);?>&gt;&gt;<?php echo __('List',true);?></h1>
	<ul id="title-search">
		 <li>
	    	<form onsubmit="loading();" method="get">
	    		<input type="text" name="search" value="" title="Search" class="in-search default-value input in-text defaultText" id="search-_q">
	    	</form>
    </li>
    <li style="display: list-item;" id="title-search-adv" onclick="advSearchToggle();" title="advanced search »"></li>
  </ul>
 	 <ul id="title-menu">
    <li>
    		<a class="link_btn" href="add">
       <img width="16" height="16" src="<?php echo $this->webroot?>images/add.png" alt=""><?php echo __('Add Soft swap',true);?>
      </a>
    </li>           
  </ul>
</div>