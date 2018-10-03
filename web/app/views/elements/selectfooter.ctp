<div id="footer"><span><strong><?php __('DeNovoLab@2010-2011 All Rights Reserved')?>. </strong></span> </div>

<!-- All messages -->
<?php  
			if(empty($m)||$m==''){
		 			$m=$session->read('m');
		 			$session->del('m');
			}
					 
			if (!empty($m)) {
					 	
	?>
<script type="text/javascript">
				//<![CDATA[


					showMessages("<?php echo $m?>");
				//]]>
			</script>
<?php 
			}
	?>

<!-- 显示递归后的代理商 --> 
<script>

	<?php 


			//对时间控件的处理
			    $smartPeriod = 'custom';
					 $start_date = date("Y-m-d");
						$start_time = '00:00:00';
						$stop_date = date("Y-m-d");
						$stop_time ='23:59:59';
						
						if(!empty($is_query)){
								$smartPeriod = $_POST ['smartPeriod'];
						$smartPeriod = $_POST ['smartPeriod'];
						$start_date = $_POST ['start_date'];
						$start_time = $_POST ['start_time'];
						$stop_date = $_POST ['stop_date'];
						$stop_time = $_POST ['stop_time'];
							
						}

			?>

//给时间文本框帮定事件
			$('#query-smartPeriod').val('<?php echo  $smartPeriod?>');
			$('#query-start_date-wDt').val('<?php echo  $start_date?>');
			$('#query-start_time-wDt').val('<?php echo $start_time?>');
			$('#query-stop_date-wDt').val('<?php echo $stop_date?>');
			$('#query-stop_time-wDt').val('<?php echo $stop_time?>');

			
			$("#query-start_date-wDt").click(function(){
				var max_date = $('#query-start_date-wDt').val();
				WdatePicker({dateFmt:'yyyy-MM-dd',maxDate:max_date});});
			$("#query-start_time-wDt").click(function(){WdatePicker({dateFmt:'HH:mm:ss'});});
			$("#query-stop_date-wDt").click(function(){
				var min_date = $("#query-start_date-wDt").val();
				WdatePicker({dateFmt:'yyyy-MM-dd',minDate:min_date});});
			$("#query-stop_time-wDt").click(function(){WdatePicker({dateFmt:'HH:mm:ss'});});

			startWatchStopTime();
			function show_recu_reseller(info){
				var s = document.getElementsByName(info[0]);
				var msg = "["+info[1].substring(0,info[1].lastIndexOf(","))+"]";
				var res = eval("("+msg+")");
				var o = document.createElement("option");
				for (var j =0;j<s.length;j++){
					for (var i = 0;i<res.length;i++) {
						var op = o.cloneNode(true);
						var space = "";
						space += "&nbsp;&nbsp;";
						op.innerHTML = space + res[i].name;
						op.value = res[i].id;
						if (info[2]){
							if (info[2].trim()==res[i].id){
									op.selected = true;
							}
						}
						s[j].appendChild(op);
					}
				}
			}
	</script>
<?php  
			if(empty($r_reseller)||$r_reseller==''){
		 			$r_reseller=$session->read('r_reseller');
		 			$session->del('r_reseller');
			}
					 
			if (!empty($r_reseller)) {
	?>
<script type="text/javascript">
				//<![CDATA[
			var info = "<?php echo $r_reseller?>".split("|");
			show_recu_reseller(info);
				//]]>
			</script>
<?php
	}
	?>
<script type="text/javascript">

if(document.getElementById("toppage")!=undefined){
	document.getElementById("toppage").appendChild(document.getElementById("tmppage").cloneNode(true));
	var size = document.getElementsByName("size");
	for (var i = 0;i<size.length;i++)
		size[i].value = "<?php echo $p->getPageSize();?>";
}

<?php if(!empty($search)){?>
$('#advsearch').show();
 $('#title-search-adv').addClass('opened');

<?php }?>

</script>
<div class="  viewport-bottom"
	style="display: none; top: 409px; left: 915px; right: auto;"
	id="tooltip">
  <h3 style="display: none;"></h3>
  <div class="body">
    <dl id="pi-11510-tooltip" class=" ">
      <dt><?php __('Not Active')?>:</dt>
      <dd><?php __('DID-Spain-Madrid')?></dd>
      <dd><?php __('Testprodukt')?></dd>
    </dl>
  </div>
  <div style="display: none;" class="url"></div>
</div>
</body></html>