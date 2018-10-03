<div id="title">
<h1>
   <?php  __('Finance')?>
    &gt;&gt;
    <?php __('Detail Statements')?>
  </h1>
	<ul id="title-search">
         
    <li>
	    	<form id="search_frm" method="get">
                        <input type="hidden" id="is_download" name="is_download" value="0" />
	    		<input type="text" name="search" value="Search" title="Search" class="in-search default-value input in-text defaultText in-input" id="search-_q">
				<input type="submit" name="submit" id="sub-btn" value="" class="search_submit"/>
	    	</form>
    </li>
    <li id="title-search-adv" onclick="advSearchToggle();" title="advanced search »" style="display: list-item; "></li>
	</ul>
    <ul id="title-menu">
           <li>
                <a href="###" id="export_csv" class="link_btn">
                    <img height="16" width="16" alt="Export" src="<?php echo $this->webroot; ?>images/export.png"><?php __('Export')?>				
                </a>
            </li>
    </ul>
</div>
