<ul class="inline" id="stats-extra"
	style="font-weight: bolder; font-size: 1.1em; color: #6694E3; visibility: visible; height: 18px;">
	<li id="stats-period" style="position: relative; visibility: visible;">
	<span rel="helptip" class="helptip" id="ht-100012"><?php __('Report Period')?></span>
	<span><?php  echo $start;?></span> &mdash; <span><?php echo $end?></span>
	<span style="width: 20px;">&nbsp;&nbsp;&nbsp;&nbsp;</span></li>
	<li class="pull-right" id="stats-time"><?php __('Query Time')?>: <?php echo $quey_time?> <?php __('ms')?></li>
	<input type="hidden" name="currentdate" id="currentdate" value="<?php  echo $start;?>" />
</ul>
<div class="clearfix"></div>
<br>