
    <script type="text/javascript">
    			function checkForm(){

        			var amt = document.getElementById("amt").value;
        			var trantype = document.getElementById("trantype").value;
        			if (!trantype){
            			jGrowl_to_notyfy('请选择交易类型',{theme:'jmsg-alert'});
            			return false;
            			}
        			
        			if (!amt){
            			jGrowl_to_notyfy('请输入充值金额',{theme:'jmsg-alert'});
            			return false;
            			} else {
                			if (!/^[0-9]+(\.[0-9]{1,3})?$/.test(amt)){
                				jGrowl_to_notyfy('充值金额格式不正确',{theme:'jmsg-alert'});
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
    </script>

<div id="title">
  <h1>
    添加交易记录
  </h1>
</div>
<div id="container">
<div id="cover"></div>
<div id="cover_tmp"></div>
<form method="post" onsubmit = "return checkForm();">

	<table class="form">
	
	
	<tr>
					<td class="label">帐号卡</td>
					<td class="value">
					        <input type="text" id="query-id_cards_name" onclick="showCards()" style="width: 150px;" readonly="1" value="" name="query[id_cards_name]" class="input in-text">        
        
        <img width="9" height="9" onclick="showCards()" class="img-button" src="<?php echo $this->webroot?>images/search-small.png">
        <img width="9" height="9" onclick="ss_clear('card', _ss_ids_card)" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png">
        <input class="input in-hidden" name="query[id_cards]" value="" id="query-id_cards" type="hidden">
					
					</td>
			</tr>
			
			
			<tr>
					<td class="label">交易类型</td>
					<td class="value"><?php
	$trantype=array('1'=>'增值业务','2'=>'语音套餐租金',  '3'=>'自定义' );
						echo $form->input('trantype',
 		array('options'=>$trantype,'empty'=>'请选择交易类型','label'=>false ,'div'=>false,'type'=>'select','style'=>'width:180px;'));?></td>
			</tr>
<tr>
					<td class="label">交易金额</td>
					<td class="value"><input style="width:120;text-align:right;height:20px" name="amt" id="amt"/></td>
			</tr><!--
			
			<tr>
					<td class="label"><?php echo __('approved')?></td>
					<td class="value">
						<input type="checkbox" checked onclick="if ($('#approved').val()==='false'){$('#approved').val('true')}"/>
						<input type="hidden" name="approved" id="approved" value="false"/>
					</td>
			</tr>
			
			--><tr>
					<td class="label">&nbsp;</td>
					<td class="value"><input type="submit" style="width:60px;"  value="<?php echo __('submit')?>"/></td>
			</tr>
	</table>
	</form>
</div>
<script type="text/javascript">

//设置每个字段所对应的隐藏域


var _ss_ids_card = {'id_cards': 'query-id_cards', 'id_cards_name': 'query-id_cards_name', 	'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};


//<![CDATA[

  function showCards ()
  {
      ss_ids_custom['card'] = _ss_ids_card;
     // val = $('#query-client_type').val();//客户类型
      //tz = $('#query-tz').val();

      winOpen('<?php echo $this->webroot?>clients/ss_card?type=2&types=8', 500, 530);

  }




function repaintOutput() {
    if ($('#query-output').val() == 'web') {
        $('#output-sub').show();
    } else {
        $('#output-sub').hide();
    }
}
repaintOutput();
//]]>
</script>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>