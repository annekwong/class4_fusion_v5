
		<div id="title">
	  	<h1><?php echo __('systemc')?>&gt;&gt;<?php echo __('setmsgtmp')?></h1>
		</div>
		
		<div id="container">
		 	<form method="post" style="margin-left:30%;">
		 		<fieldset><legend><?php echo __('setmsgtmp')?></legend>
					<table >
						<tr>
							<td><?php echo __('balancetmp')?></td>
							<td style="float:left;">
								<textarea style="width:250px;height:50px;" name="balance"><?php echo $tmp_bal[0][0]['tmp_content']?></textarea>
								<span style="color:red">*</span>
								<div style="color:green">注意:(用{balance}代替需要系统填入的值,比如:(您的余额是{balance}))</div>
							</td>
						</tr>
						<tr><td>&nbsp;</td></tr>
						<tr>
							<td><?php echo __('findpwdtmp')?></td>
							<td style="float:left;">
								<textarea style="width:250px;height:50px;" name="pass"><?php echo $tmp_findp[0][0]['tmp_content']?></textarea>
								<span style="color:red">*</span>
								<div style="color:green">注意:(用{findpwd}代替需要系统填入的值,比如:(您的密码是{findpwd}))</div>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<input style="margin-left:-25%;" type="submit" value="<?php echo __('submit')?>"/>
							</td>
						</tr>
					</table>
		 		</fieldset>
		 	</form>
		</div>
		
		<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>
