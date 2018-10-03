
<div id="cover"></div>
 <div id="cover_bb"></div>
<div id="title">
            <h1>
      <?php echo __('paymentgateway');  ?>     
      
                        </h1>
    <ul id="title-menu">
    		<li>
    			<a class="link_back" href="<?php echo $this->webroot?>paymenttraces/view_trace">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
  		</ul>
    </div>

<div id="container">
<table class="list">
	<col width="25%">
	<col width="25%">
	<col width="25%">
	<col width="25%">
<thead>
<tr>
    <td ><?php echo __('id',true);?></td>
    <td ><?php echo __('tracename')?></td>
    <td ><?php echo __('status')?></td>
    <td  class="last"><?php echo __('action')?></td>
</tr>
 
</thead>
<tbody>




	<?php 
					$mydata =$ways;
					$loop = count($mydata); 
					for ($i=0;$i<$loop;$i++) {?>
<tr class="row-1">
    <td align="center"><?php echo $mydata[$i][0]['payment_trace_id']?></td>
    <td align="center"> <?php echo $mydata[$i][0]['trace_name']?> </td>
    <td align="center"> <?php echo $mydata[$i][0]['status']==true?__('active',true):__('disable',true)?> </td>
    <td class="last">
    			<?php
    					if ($mydata[$i][0]['status']==true) { 
    				?>
    							<a onclick="return confirm('<?php echo __('disableplat',true)?>?');" title="<?php echo __('disable')?>"   href="<?php echo $this->webroot?>paymenttraces/trace_active_or_not/<?php echo $mydata[$i][0]['payment_platform_new_id']?>/<?php echo $mydata[$i][0]['payment_trace_id']?>/false">
      						<img width="16" height="16" src="<?php echo $this->webroot?>images/flag-1.png">
      					</a>
    			<?php
    						}  else {
    				?>
    							<a onclick="return confirm('<?php echo __('activeplat',true)?>?');"   title="<?php echo __('active')?>"   href="<?php echo $this->webroot?>paymenttraces/trace_active_or_not/<?php echo $mydata[$i][0]['payment_platform_new_id']?>/<?php echo $mydata[$i][0]['payment_trace_id']?>/true">
      						<img width="16" height="16" src="<?php echo $this->webroot?>images/flag-0.png">
      					</a>
    			<?php 
    							}
    				?>
      
      </td>
    

  


                
  

</tr>


	<?php }?>

</tbody><tbody>
</tbody></table>



</div>
<div>
</div>

<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	
<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">
	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>