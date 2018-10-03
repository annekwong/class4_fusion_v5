<ul id="title-menu">
	
	
	
	
		<li>
			<form  method="get"   onsubmit=" loading();">
			
			<font class="fwhite"><?php echo __('Period',true);?>:</font>
			<select   id="period_select"  name="period_select" onchange=" $('#title-menu').find('form').submit();"

			>
				<option value="15min">15 <?php __('Min')?></option>
				<option value="1h">1 <?php __('Hour')?></option>
				<option    value="24h">24 <?php __('Hour')?></option>
				</select>
			
				<input type="text" id="search-_q" class="in-search default-value input in-text defaultText"
			
				value="<?php if (!empty($_GET['search'])) echo $_GET['search'];?>" name="search">
			</form>
		</li>
                <li><font class="fwhite"><?php echo __('Refresh Every',true);?>:</font>
                        <select onchange="change_time(this);">
                        <option value="180">3 <?php __('minutes')?></option>
                        <option value="300">5 <?php __('minutes')?></option>
                        <option value="800">15 <?php __('minutes')?></option>
                        </select>
                </li>
	</ul>