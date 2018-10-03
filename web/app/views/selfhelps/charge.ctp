
    <link href="/static/favicon.ico" type="image/x-icon" rel="shortcut Icon">
<div id="container">
<script type="text/javascript">
	function checked(obj){
		document.getElementById('charge_card').style.display='none';
		document.getElementById('charge_post').style.display='none';
		document.getElementById('charge_plat').style.display='none';
		var lis = document.getElementById("items").getElementsByTagName("li");
		for (var i = 0;i<lis.length;i++) {
			lis[i].className = "";
		}
		obj.parentNode.className = 'active';
		document.getElementById(obj.name).style.display='';
	}

	function checkForm(){
		var card_num = document.getElementById('card_num').value;
		var card_pass = document.getElementById('card_pass').value;

		if (!card_num){
			jGrowl_to_notyfy('请输入充值卡号',{theme:'jmsg-alert'});
			return false;
		}

		if (!card_pass){
			jGrowl_to_notyfy('请输入充值卡密码',{theme:'jmsg-alert'});
			return false;
		}
	}
</script>
	<ul class="tabs" id="items" style="background:white;">
	    <li class="active" value="charge_card"><a name="charge_card" onclick="checked(this);" href="javascript:void(0)" ><img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif">系统充值卡充值</a></li>   
	    <li value="charge_post"><a onclick="checked(this);" name="charge_post"  href="javascript:void(0)" ><img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon_005.gif">充值卡支付</a></li>
	    <li value="charge_plat"><a onclick="checked(this);" name="charge_plat"  href="javascript:void(0)" ><img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon_005.gif">网银支付</a></li>    
	</ul>
	<div id="charge_card">
		<form method="post" onsubmit="return checkForm();">
			<input type="hidden" value="1" name="charge_type"/>
			<div class="msg" style="height:auto;margin-left:35%;width:300px;text-align:left;">
		   <div><label for="voip_hosts-28">冲值卡卡号:<input id="card_num" name="card_num"/></label></div>
		   <div><label for="voip_hosts-28">冲值卡密码:<input id="card_pass" name="card_pass"/></label></div>
		   <div style="text-align:center"><input type="submit" value="<?php echo __('submit')?>"/></div>
		 </div>
		</form>
	</div>
	
	<?php if (isset($noplatform)) {?>
		<div class="msg">对不起,系统当前暂停充值功能</div>
	<?php } else {?>
	<div id="charge_post" style="display:none">
	<div>
	</div>
			<form method="post" action="<?php echo $this->webroot?>selfhelps/send">
			<table class="form">
			<tr>
<td class="label label2"><input type="radio" checked name="cardtype" value="SZX" /></td>
<td class="value value2"><img height="35" width="120" src="<?php echo $this->webroot?>images/szx.jpg"/></td>
</tr>
<tr>
<td class="label label2"><input type="radio"  name="cardtype" value="DX" /></td>
<td class="value value2" ><img width="120" height="30" src="<?php echo $this->webroot?>images/dx.jpg"/></td>
</tr>
<tr>
	<td class="label label2"><input type="radio"  name="cardtype" value="LT" /></td>
<td class="value value2"><img width="120" height="30"  src="<?php echo $this->webroot?>images/lt.gif"/></td>
</tr>
<tr>
	<td class="label label2"><input type="radio"  name="cardtype" value="QB" /></td>
<td class="value value2"><img width="120" height="30"  src="<?php echo $this->webroot?>images/qb.gif"/></td>
</tr>
				<tr>
					<td class="label label2">充值金额</td>
					<td class="value value2"><input name="amt"/></td>
				</tr>
				<tr>
				<td class="label label2">充值卡号</td>
				<td class="value value2"><input name="cardno"/></td></tr>
				<tr>
				<td class="label label2">充值卡密</td>
				<td class="value value2"><input name="cardpass"/></td></tr>
				<tr><td class="label label2">&nbsp;</td>
				<td class="value value2"><input type="submit" value="<?php echo __('submit')?>"/></td>
				</tr>
			</table>
			</form>
		</div>
	
	<div id="charge_plat"  style="display:none">
		<form method="post" action="<?php echo $this->webroot?>selfhelps/send">
		<input type="hidden" value="BANK" name="cardtype"/>
			<div><label for="voip_hosts-28"></label></div>
			<table class="form">
				<tr>
					<td colspan="4" style="text-align:center">充值金额:<input class="input in-text" name="amt" id="amt"/><input type="submit" value="<?php echo __('submit')?>"/></td>
				</tr>
				<tr>
					<td class="label label2"></td>
					<td class="value value2"><img src="<?php echo $this->webroot?>images/bankabc.gif"/></td>
					<td class="label label2"></td>
					<td class="value value2"><img src="<?php echo $this->webroot?>images/bankbc.gif"/></td>
				</tr>
				<tr>
					<td class="label label2"></td>
					<td class="value value2"><img src="<?php echo $this->webroot?>images/bankbcc.gif"/></td>
					<td class="label label2"></td>
					<td class="value value2"><img src="<?php echo $this->webroot?>images/bankbj.gif"/></td>
				</tr>
				<tr>
					<td class="label label2"></td>
					<td class="value value2"><img src="<?php echo $this->webroot?>images/bankccb.gif"/></td>
					<td class="label label2"></td>
					<td class="value value2"><img src="<?php echo $this->webroot?>images/bankcib.gif"/></td>
				</tr>
				<tr>
					<td class="label label2"></td>
					<td class="value value2"><img src="<?php echo $this->webroot?>images/bankcitic.gif"/></td>
					<td class="label label2"></td>
					<td class="value value2"><img src="<?php echo $this->webroot?>images/bankcmb.gif"/></td>
				</tr>
				<tr>
					<td class="label label2"></td>
					<td class="value value2"><img src="<?php echo $this->webroot?>images/bankcmbc.gif"/></td>
					<td class="label label2"></td>
					<td class="value value2"><img src="<?php echo $this->webroot?>images/bankgdb.gif"/></td>
				</tr>
				<tr>
					<td class="label label2"></td>
					<td class="value value2"><img src="<?php echo $this->webroot?>images/bankgznxs.gif"/></td>
					<td class="label label2"></td>
					<td class="value value2"><img src="<?php echo $this->webroot?>images/bankgzs.gif"/></td>
				</tr>
				<tr>
					<td class="label label2"></td>
					<td class="value value2"><img src="<?php echo $this->webroot?>images/bankicbc.gif"/></td>
					<td class="label label2"></td>
					<td class="value value2"><img src="<?php echo $this->webroot?>images/bankpost.gif"/></td>
				</tr>
				<tr>
					<td class="label label2"></td>
					<td class="value value2"><img src="<?php echo $this->webroot?>images/banksdb.gif"/></td>
					<td class="label label2"></td>
					<td class="value value2"><img src="<?php echo $this->webroot?>images/bankshpd.gif"/></td>
				</tr>
			</table>
		</form>
	</div><?php }?>
	</div>