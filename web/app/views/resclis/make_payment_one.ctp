<script type="text/javascript">
    			function checkForm(){

        			var amt = document.getElementById("amt").value;
        			if (!amt){
            			jGrowl_to_notyfy('<?php __('Transaction Amt is required!')?>',{theme:'jmsg-alert'});
            			return false;
            			} else {
                			if (!/^[0-9]+(\.[0-9]{1,3})?$/.test(amt)){
                				jGrowl_to_notyfy('<?php __('Transaction Amt,must contain numeric characters only!')?>',{theme:'jmsg-alert'});
                    			return false;
                    			}
                			}
        			}

    			//选择代理商或者客户或者卡  由子页面调用
				function choose(tr){
    			document.getElementById('res_name').value = tr.cells[1].innerHTML.trim();
    			document.body.removeChild(document.getElementById("infodivv"));
    			closeCover('cover_tmp');
    				}



				jQuery(document).ready(function(){
					jQuery('#amt').xkeyvalidate({type:'Num'}).attr('maxLength','16');
					
				});
    </script>

<div id="title">
  <h1><?php echo __('manage')?>&gt;&gt;
    
    Refill <font class="editname" title="Name" ><?php echo empty($name[0][0]['name'])||$name[0][0]['name']==''?'':"[".$name[0][0]['name']."]" ;?></font> </h1>
  <ul id="title-menu">
    <li> <a class="link_back" href="<?php echo $this->webroot?>clients/index"><img width="16" height="16" alt="<?php echo __('goback',true);?>" src="<?php echo $this->webroot?>images/icon_back_white.png">&nbsp;<?php echo __('goback',true);?></a> </li>
  </ul>
</div>
<div id="container">
  <div id="cover"></div>
  <div id="cover_tmp"></div>
  <form method="post" onsubmit = "return checkForm();">
    <input    name="type"   type="hidden"   value="<?php echo $type;?>"/>
    <input    name="type_id"   type="hidden"   value="<?php echo $type_id;?>"/>
  <table class="form" style="width:300px;">
<col style="width: 40%;"><col style="width: 60%;">
<tbody><tr>
    <td class="label"><?php echo __('charges')?></td>
    <td class="value" style="text-align:left;padding-left:10px;">
    <input class="input" name="amt" id="amt"/>
   	
    </td>
</tr>
<tr>
    <td class="label"><?php echo __('approved')?></td>
    <td class="value" style="text-align:left;padding-left:10px;">
    	<input type="checkbox" name="approved" id="approved"   value="true"	checked 		 onclick="$(this).attr('checked')==true?$(this).attr('value','true'):$(this).attr('value','false');"/>
    </td>
</tr>

</tbody></table>
<div class="form-buttons"><input type="submit" value="<?php __('submit')?>" class="input in-submit"></div>

</form>
</div>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png"> <img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png"> <img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>
