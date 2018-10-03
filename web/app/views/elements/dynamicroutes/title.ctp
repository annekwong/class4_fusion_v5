<div id="title">
  <h1><?php __('Routing')?>&gt;&gt;<?php echo __('DynamicRouting')?></h1>
  	<?php $w = $session->read('writable');?>
		<ul id="title-search">
				<li>
				<?php //****************************模糊搜索**************************?>
					<form  action="<?php echo $this->webroot?>dynamicroutes/view"  method="get">
						<input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>" 
						value="<?php if(isset($_POST['search'])){echo $_POST['search'];}else{ echo '';}?>"  onclick="this.value=''" name="search">
					</form>
				</li>
				<li title="<?php echo __('advancedsearch')?> »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
    </ul>
    <ul id="title-menu">
     <?php if (isset($edit_return)) {?>
     <li>
    			<a class="link_back" href="<?php echo $this->webroot?>dynamicroutes/index">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">&nbsp;<?php echo __('gobackall')?>
    			</a>
    </li>
     <?php }?>
     <li>
    	<?php echo $this->element("createnew",Array('url'=>'dynamicroutes/add'))?>
     </li>
	</ul>
</div>
