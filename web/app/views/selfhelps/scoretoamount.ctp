
		<div id="container">
			<?php if (!isset($strategys)) {?>
				<div class="msg"><?php echo __('nopointsstrategy')?></div>
			<?php } else {?>
				<form method="post" onsubmit="return confirm('转换将会扣除相应的积分,是否继续?');">
				<div class="msg"><?php echo __('currpoints')?><span style="color:green;"><?php echo $points?></span></div>
				<div class="cb_select input" style="margin-left:35%;width:100px;text-align:left;height: 150px;">
		    <?php
						$loop = count($strategys); 
						for ($i=0;$i<$loop;$i++) {
					?> 
		        <div><input checked class="input in-radio" name="point" value="<?php echo $strategys[$i][0]['sales_strategy_points_id']?>" type="radio"> <label for="voip_hosts-28"><span style="color:red;"><?php echo $strategys[$i][0]['bonus_credit']?></span>分换<span style="color:green;"><?php echo $strategys[$i][0]['gift_amount']?></span>元</label></div>
		     <?php 
						}
					?>
    		</div>
    		<div><input type="submit" style="margin-left:35%;margin-top:10px;" value="<?php echo __('starttransfer')?>"/></div>
    		</form>
			<?php }?>
		</div>