
    <link type="text/css" rel="stylesheet" media="all" href="<?php echo $this->webroot?>css/base.css" />
    <link type="text/css" rel="stylesheet" media="all" href="<?php echo $this->webroot?>css/popup.css" />
    <link type="text/css" rel="stylesheet" media="all" href="<?php echo $this->webroot?>css/shared.css" />
    <link type="text/css" rel="stylesheet" media="all" href="<?php echo $this->webroot?>css/jquery.jgrowl.css" />
    <link type="text/css" rel="stylesheet" media="print" href="<?php echo $this->webroot?>css/print.css" />
        
    <script type="text/javascript" src="<?php echo $this->webroot?>js/jquery-1.4.1.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot?>js/jquery.tooltip.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot?>js/jquery.jgrowl.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot?>js/bb-functions.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot?>js/bb-interface.js"></script>
    <script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>
    
    <style>
<!--


#infodivif{
	
	width: 600px;
}
-->
</style>
    <script type="text/javascript">
    			//<![CDATA[
							function add_new (){
								var currency_id = document.getElementById("currency_id").value;
								var date = document.getElementById("date").value;
								var rate = document.getElementById("rate").value;
								var pattern = /^[+\-]?\d+(.\d+)?$/;
								if(!pattern.test(rate)){
									alert('rate must is digits');
			
									return false ;
								}
								jQuery.post("<?php echo $this->webroot?>currs/update_rate",{date:date,rate:rate,currency_id:currency_id},function(data){
									var tmp = data.split("|");
									var p = {theme:'jmsg-success',life:100,beforeClose:function(){parent.document.getElementById("infodivif").contentWindow.location.reload();}};
									if (tmp[1].trim() == 'false') p = {theme:'jmsg-alert'};
									jGrowl_to_notyfy(tmp[0],p);
								});
							}

							function del_update(id){
								if(confirm("Are you sure?")){
									jQuery.get("<?php echo $this->webroot?>currs/del_currency_updates?id="+id,function(data){
										var tmp = data.split("|");
										var p = {theme:'jmsg-success',life:100,beforeClose:function(){parent.document.getElementById("infodivif").contentWindow.location.reload();}};
										if (tmp[1].trim() == 'false') p = {theme:'jmsg-alert'};
										jGrowl_to_notyfy(tmp[0],p);
									});
								}
							}
					//]]>
    </script>
 <div id="title">
  <script>function closeWithoutLoad(){parent.closeCover('cover_tmp');parent.document.body.removeChild(parent.document.getElementById('infodivv'));}</script>
    <h2><a id="closeA" style="float:left;margin-left:20px;" href="javascript:void(0)" onclick="parent.window.location.reload();">
		    			<i class="icon-remove"></i>
		    		</a></h2><h1><?php echo __('currencyrate')?></h1>
</div>
<div class="container">

<input type="hidden" value="<?php echo $currecy_id?>" id="currency_id" name="currency_id"/>
<div class="panel">
<table class="form">
<tr>
    <td class="label4"><?php echo __('date')?>:</td>
    <td class="value4"><table class="in-date">
<tr>
    <td><input type="text" value="<?php echo $nowtime?>" style="width:200px;" id="date"  class="Wdate" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});" name="date" readonly /></td>
</tr>
</table>
    </td>
    <td class="label4"><?php echo __('Rates')?>:</td>
    <td class="value4"><input type="text" name="rate" value="0.000" class="in-decimal" style="width:80px" id="rate"/></td>
    <td class="buttons"><input type="button" onclick="add_new();" value="<?php echo __('update')?>" /></td>
</tr>
</table>
</div>

<table class="list">
<thead>
<tr>
    <td width="40%"><?php echo __('updatedate')?></td>
    <td width="50%"><?php echo __('updaterate')?></td>
   <td width="10%"><?php echo __('action')?></td>
</tr>
</thead>
<tbody class="rows"><?php 
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
		    <td><?php echo $mydata[$i][0]['modify_time']?></td>
		    <td style="font-weight: bold;"><?php $l =empty($mydata[$i][0]['last_rate'])?'<span style=\"color:green\">0.000</span>':$mydata[$i][0]['last_rate']<0?"<span style=\"color:red\">".$mydata[$i][0]['last_rate']."</span>":"<span style=\"color:green\">".$mydata[$i][0]['last_rate']."</span>"; echo $mydata[$i][0]['rate']."(".$l.")"?></td>
		    <td style="text-align:center;">
		    		<a style="text-align:center;" href="javascript:void(0)" onclick="del_update('<?php echo $mydata[$i][0]['currency_updates_id']?>')">
		    			<i class="icon-remove"></i>
		    		</a>
		    </td>
				</tr>
		<?php }?>		
</table>
<div id="tmppage">
<?php echo $this->element('page');?>
</div>
</div>