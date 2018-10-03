

<div id="title">
  <h1><?php __('System')?>&gt;&gt;<?php __('jurisdiction')?></h1>
		<ul id="title-search">
			<li>
				<form method="get" action="">
                                    <input type="text" name="search" onclick="this.value=''" value="Search" title="<?php __('Search')?>" class="in-search default-value input in-text defaultText" id="search-_q">
				</form>
			</li>
			<li style="display: list-item;" id="title-search-adv" onclick="advSearchToggle();" title="advanced search »" class=""></li>
		</ul>
		<ul id="title-menu">
		<li>
			
    			<a class="link_back" href="<?php  echo $this->webroot?>jurisdictioncountrys/view/">
    				<img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback',true);?>    			</a>
    		</li>
			<li>
			<?php echo $this->element("createnew",Array('url'=>'javascript:addItem()','jsAdd'=>true))?>
			</li>
		</ul>
</div>