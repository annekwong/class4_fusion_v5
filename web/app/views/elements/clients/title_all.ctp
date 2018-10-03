<div id="title">
 <h1><?php  __('Finance')?>&gt;&gt;<?php echo __('client')?></h1>
	<ul id="title-search">
    <li>
	    	<form  method="get"   onsubmit=" loading();">
	    		<input type="text" id="search-_q" class="in-search default-value input in-text defaultText"
	    		 title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
	    	</form>
    </li>
    <li title="advanced search »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
	</ul>
	<ul id="title-menu">
    <li>
    		<a  href="<?php echo $this->webroot?>clients/add">
       <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createclient')?>
      </a>
    </li>
    <li>
		    <a class="link_back" href="<?php echo $this->webroot?>resellers/reseller_list">
			    <img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
			    &nbsp;<?php echo __('goback')?>
		    </a>
    </li>
    <li>
		    <a class="link_back" href="<?php echo $this->webroot?>clients/view">
			    <img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
			    &nbsp;<?php echo __('gobackall')?>
		    </a>
    </li>
    <li>
		    <a class="link_back" href="#" onclick="history.go(-1)">
			    <img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
			    &nbsp;<?php echo __('back')?>
		    </a>
    </li>
    <li>
       <a class="list-export" href="<?php echo $this->webroot?>clients/download">
       <!--
       		<img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/export.png">--><?php echo __('Download');?>
       </a>
    </li>
	</ul>
</div>