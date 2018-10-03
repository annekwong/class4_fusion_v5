<div id="title">
  <h1><?php echo __('account')?>&gt;&gt;
    <?php echo __('accountinfo')?>
  </h1>
</div>
<div id="container"><div style="font-size: 1.1em; line-height: 1.3;" class="msg">
<table class="form">
<tbody><tr>
    <td class="label label2"><?php echo __('accountname')?>:</td>
    <td class="value value2"><b><?php echo $info[0][0]['name']?></b></td>
</tr>
<tr>
    <td class="label label2"><?php echo __('phone_number')?>:</td>
    <td class="value value2"><?php echo $info[0][0]['phone_number']?></td>
</tr>
<tr>
    <td valign="top" class="label label2"><?php echo __('address')?>:</td>
    <td class="value value2"><?php echo $info[0][0]['address']?></td>
</tr>
<tr>
    <td class="label label2"><?php echo __('email')?>:</td>
    <td class="value value2"><?php echo $info[0][0]['email']?></td>
</tr>
<tr>
    <td class="label label2"><?php echo __('company_name')?>:</td>
    <td class="value value2"><?php echo $info[0][0]['company_name']?></td>
</tr>
<tr>
    <td class="label label2"><?php echo __('balance')?>:</td>
    <td class="value value2"><?php echo empty($info[0][0]['balance'])?"<span style='color:red'>0.000</span>":"<span style='color:green'>".$info[0][0]['balance']."</span>"?></td>
</tr>
<tr>
    <td class="label label2"><?php echo __('giftamount')?>:</td>
    <td class="value value2"><?php echo empty($info[0][0]['gift_amount'])?"<span style='color:red'>0.000</span>":"<span style='color:green'>".$info[0][0]['gift_amount']."</span>"?></td>
</tr>
<tr>
    <td class="label label2"><?php echo __('bonus')?>:</td>
    <td class="value value2"><?php echo empty($info[0][0]['points'])?"<span style='color:red'>0.000</span>":"<span style='color:green'>".$info[0][0]['points']."</span>"?></td>
</tr>
</tbody></table>
</div></div>

<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>