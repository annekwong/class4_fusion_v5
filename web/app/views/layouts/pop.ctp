<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $html->charset ();?>
	<title><?php echo $title_for_layout;?></title>
	<link type="text/css" href="<?php echo $this->webroot?>css/base.css" rel="stylesheet" media="all" />
	<link type="text/css" href="<?php echo $this->webroot?>css/allPage.css" rel="stylesheet" media="all" />
	<link href="<?php echo $this->webroot?>css/shared.css" type="text/css" rel="stylesheet" media="all" />
	<link href="<?php echo $this->webroot?>css/form.css" type="text/css" rel="stylesheet" media="all" />
	<link href="<?php echo $this->webroot?>css/ipcentrex.css" type="text/css" rel="stylesheet" media="all" />
	<link href="<?php echo $this->webroot?>images/favicon.ico"	type="image/x-icon" rel="shortcut Icon">
	<link href="<?php echo $this->webroot?>css/main.css" media="all" rel="stylesheet" type="text/css">
	<link href="<?php echo $this->webroot?>css/list.css" media="all" rel="stylesheet" type="text/css">
	<link href="<?php echo $this->webroot?>css/popup.css" media="all"	rel="stylesheet" type="text/css">
	<link href="<?php echo $this->webroot?>css/jquery.jgrowl.css"	media="all" rel="stylesheet" type="text/css">
	<link href="<?php echo $this->webroot?>css/print.css" media="print" 	rel="stylesheet" type="text/css">
	<link href="<?php echo $this->webroot?>css/style.css" rel="stylesheet"	type="text/css">
	<link href="<?php echo $this->webroot?>css/jquery.css" rel="stylesheet"	type="text/css">
	<link href="<?php echo $this->webroot?>css/styles.css" rel="stylesheet"	type="text/css">
	<link href="<?php echo $this->webroot?>calendar/calendar.css"	type="text/css" rel="stylesheet" />
	<script src="<?php echo $this->webroot?>js/jquery-1.4.1.js"	type="text/javascript"></script>
	<script src="<?php echo $this->webroot?>js/app.js?>"	type="text/javascript"></script>
	<script src="<?php echo $this->webroot?>js/sst.js"	type="text/javascript"></script>
	<script src="<?php echo $this->webroot?>js/jquery.jgrowl.js"	type="text/javascript"></script>
	<script src="<?php echo $this->webroot?>js/jquery.tooltip.js"	type="text/javascript"></script>
	<script type="text/javascript"	src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>
	<script src="<?php echo $this->webroot?>calendar/calendar.js"	type="text/javascript"></script>
	<script src="<?php echo $this->webroot?>calendar/calendar-setup.js"	type="text/javascript"></script>
	<script src="<?php echo $this->webroot?>calendar/calendar-en.js"	type="text/javascript"></script>
	<script src="<?php echo $this->webroot?>js/util.js"	type="text/javascript"></script>
	<script type="text/javascript"	src="<?php echo $this->webroot?>js/swfobject.js"></script>
	<!--<link href="<?php echo $this->webroot?>css/jquery.windows-engine.css"	rel="stylesheet" type="text/css" />-->
	<!--<script src="<?php echo $this->webroot?>js/jquery.windows-engine.js"	type="text/javascript"></script>-->
	<script type="text/javascript">
		//<![CDATA[
		var currentTime = '<?php echo time();?>';
		var L = {"loadingPanel":"Please Wait...","deleteConfirm":"Are you sure to delete this item?","hide-all":"hide all"};
		//]]>
	</script>
	<?php if(isset($appCommon)):?>
		<?php echo $appCommon->auto_load_js()?>
	<?php endif;?>
	<?php
	echo $html->meta ( 'icon' );
	echo $scripts_for_layout;
	?>
</head>
<body>



<?php $session->flash ();?>
<?php echo $content_for_layout;?>

<script type="text/javascript">
	$(function(){
		$('#topmenu-menu > li').each(function(){
			if ($(this).children('ul').children('li').length == 0){
				$(this).css('display','none');
			}
		});
	});
</script>
<div id="footer"><span><strong><?php __('de novo lab@2010 All Rights Reserved')?>. </strong></span>
	<ul>
		<li><a href=""></a></li>
		<li><a href=""></a></li>
	</ul>
</div>

<script src="<?php echo $this->webroot?>js/newExcanvas.js"
		type="text/javascript"></script>
<script src="<?php echo $this->webroot?>js/bb-functions.js"
		type="text/javascript"></script>
<script src="<?php echo $this->webroot?>js/bb-interface.js"
		type="text/javascript"></script>
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



<script>

	<?php 


			//给查询条件对应的表单赋�?
			    $smartPeriod = 'custom';
					 $start_date = date("Y-m-d");
						$start_time = '00:00:00';
						$stop_date = date("Y-m-d");
						$stop_time ='23:59:59';
							$tz ='+0000';
						
					   $client_type='';
						 $client_id ='';
						 
						  $client_id_term ='';
						  $client_name_term='';
						 $reseller_id='';
						 $reseller_name='';
						  $card_id ='';
						  $card_number='';
						 $client_name='';
						 $code='' ;
						 $code_name='';
						 
						  $code_term='' ;
						 $code_name_term='';
						 
						  $rate_id='';
						  $rate_name='';
						  $output= '' ;
						  $serie_id='';
						  $serie_name='';
						   $batch_id='';
						  $batch_name='';
						  
						  //group by 条件
						   $group_by0='';
						  $group_by1='';
						  $group_by2='';
						  $group_by3='';
						  $group_by4='';
						  $group_by5='';
						  
						  
						  
						if(isset($_POST['searchkey'])){
							
							
								$smartPeriod = $_POST ['smartPeriod'];
						$smartPeriod = $_POST ['smartPeriod'];
						$start_date = $_POST ['start_date'];
						$start_time = $_POST ['start_time'];
						$stop_date = $_POST ['stop_date'];
						$stop_time = $_POST ['stop_time'];
												if(isset($_POST['query']['tz'])){
							 $tz= $_POST ['query']['tz'] ;
						}
						
							if(isset($_POST['query']['id_series'])){
							 $serie_id= $_POST ['query']['id_series'] ;
						}
						
																		if(isset($_POST['query']['id_series_name'])){
							 $serie_name= $_POST ['query']['id_series_name'] ;
						}
								if(isset($_POST['query']['id_batchs_name'])){
							 $batch_name= $_POST ['query']['id_batchs_name'] ;
						}
						
						
						
												if(isset($_POST['query']['id_batchs'])){
							 $batch_id= $_POST ['query']['id_batchs'] ;
						}
						

						
						
						if(isset($_POST['query']['client_type'])){
							 $client_type=$_POST['query']['client_type'];
						}
							if(isset($_POST['query']['id_clients'])){
							 $client_id =$_POST['query']['id_clients'];
						}
						
									if(isset($_POST['query']['id_clients_name'])){
							$client_name=	 $_POST['query']['id_clients_name'] ;
						}
						
						

	
													if(isset($_POST['query']['id_clients_term'])){
							 $client_id_term =$_POST['query']['id_clients_term'];
						}
						
									if(isset($_POST['query']['id_clients_name_term'])){
							$client_name_term=	 $_POST['query']['id_clients_name_term'] ;
						}
						
						
						
						
													if(isset($_POST['query']['code'])){
									$code= $_POST ['query']['code'] ;
						}
						
							if(isset($_POST['query']['code_name'])){
									$code_name= $_POST ['query']['code_name'] ;
						}
			
						
						
																			if(isset($_POST['query']['code_term'])){
									$code_term= $_POST ['query']['code_term'] ;
						}
						
							if(isset($_POST['query']['code_name_term'])){
									$code_name_term= $_POST ['query']['code_name_term'] ;
						}
			
						
									if(isset($_POST['query']['output'])){
								 $output= $_POST ['query']['output'] ;
						}
					
											if(isset($_POST['query']['id_rates'])){
							 $rate_id= $_POST ['query']['id_rates'] ;
						}
						
												if(isset($_POST['query']['rate_name'])){
							 $rate_name= $_POST ['query']['rate_name'] ;
						}
						
						 								if(isset($_POST['query']['id_cards'])){
							 $card_id= $_POST ['query']['id_cards'] ;
						}
						
												if(isset($_POST['query']['id_cards_name'])){
							 $card_number= $_POST ['query']['id_cards_name'] ;
						}
						
														if(isset($_POST['group_by'][0])){
							 $group_by0=$_POST ['group_by'][0];
						}
																if(isset($_POST['group_by'][1])){
							 $group_by1=$_POST ['group_by'][1];
						}
																if(isset($_POST['group_by'][2])){
							 $group_by2=$_POST ['group_by'][2];
						}
																if(isset($_POST['group_by'][0])){
							 $group_by0=$_POST ['group_by'][0];
						}
							if(isset($_POST['group_by'][3])){
							 $group_by3=$_POST ['group_by'][3];
						}
								if(isset($_POST['group_by'][4])){
							 $group_by4=$_POST ['group_by'][4];
						}
								if(isset($_POST['group_by'][5])){
							 $group_by5=$_POST ['group_by'][5];
						}
							
						}

						$search='';
					if(isset($_POST['search'])){
									$search= $_POST ['search'] ;
						}
			?>

	//给时间文本框帮定事件
	$('#search-_q').val('<?php echo  $search?>');
	//$('#query-smartPeriod').val('<?php echo  $smartPeriod?>');
	$('#query-tz').val('<?php echo  $tz?>');
	$('#query-start_date-wDt').val('<?php echo  $start_date?>');
	$('#query-start_time-wDt').val('<?php echo $start_time?>');
	$('#query-stop_date-wDt').val('<?php echo $stop_date?>');
	$('#query-stop_time-wDt').val('<?php echo $stop_time?>');

	$('#query-client_type').val('<?php echo $client_type?>');
	$('#query-id_clients').val('<?php echo $client_id?>');
	$('#query-id_clients_name').val('<?php echo $client_name?>');

	$('#query-id_clients_term').val('<?php echo $client_id_term?>');
	$('#query-id_clients_name_term').val('<?php echo $client_name_term?>');

	$('#query-id_resellers').val('<?php echo $reseller_id?>');
	$('#query-id_resellers_name').val('<?php echo $reseller_name?>');
	$('#query-code_name').val('<?php echo $code_name?>');
	$('#query-code').val('<?php echo $code?>');



	$('#query-code_name_term').val('<?php echo $code_name_term?>');
	$('#query-code_term').val('<?php echo $code_term?>');
	$('#query-output').val('<?php echo $output?>');

	$('#query-id_rates_name').val('<?php echo $rate_name?>');
	$('#query-id_rates').val('<?php echo $rate_id?>');

	$('#query-id_cards_name').val('<?php echo $card_number?>');
	$('#query-id_cards').val('<?php echo $card_id?>');


	$('#query-id_series').val('<?php echo $serie_id?>');
	$('#query-id_series_name').val('<?php echo $serie_name?>');
	$('#query-id_batchs').val('<?php echo $batch_id?>');
	$('#query-id_batchs_name').val('<?php echo $batch_name?>');

	$('#CdrGroupBy1').val('<?php echo $group_by0?>');
	$('#CdrGroupBy2').val('<?php echo $group_by1?>');
	$('#CdrGroupBy3').val('<?php echo $group_by2?>');
	$('#CdrGroupBy4').val('<?php echo $group_by3?>');
	$('#CdrGroupBy5').val('<?php echo $group_by4?>');
	$('#CdrGroupBy6').val('<?php echo $group_by5?>');

	$("#query-start_date-wDt").click(function(){
		var max_date = $('#query-start_date-wDt').val();
		WdatePicker({dateFmt:'yyyy-MM-dd',maxDate:max_date});});
	$("#query-start_time-wDt").click(function(){WdatePicker({dateFmt:'HH:mm:ss'});});
	$("#query-stop_date-wDt").click(function(){
		var min_date = $("#query-start_date-wDt").val();
		WdatePicker({dateFmt:'yyyy-MM-dd',minDate:min_date});});
	$("#query-stop_time-wDt").click(function(){WdatePicker({dateFmt:'HH:mm:ss'});});

	startWatchStopTime();
	//			function show_recu_reseller(info){
	//				var s = document.getElementsByName(info[0]);
	//				var msg = "["+info[1].substring(0,info[1].lastIndexOf(","))+"]";
	//				var res = eval("("+msg+")");
	//				var o = document.createElement("option");
	//				for (var j =0;j<s.length;j++){
	//					for (var i = 0;i<res.length;i++) {
	//						var op = o.cloneNode(true);
	//						var space = "";
	//						space += "&nbsp;&nbsp;";
	//						op.innerHTML = space + res[i].name;
	//						op.value = res[i].id;
	//						if (info[2]){
	//							if (info[2].trim()==res[i].id){
	//									op.selected = true;
	//							}
	//						}
	//						s[j].appendChild(op);
	//					}
	//				}
	//			}
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
		//			var info = "<?php echo $r_reseller?>".split("|");
		//			show_recu_reseller(info);
		//]]>
	</script>
<?php
}
?>

<script type="text/javascript">

	<?php if(!empty($p)):?>
	if(document.getElementById("toppage")!=undefined){
		document.getElementById("toppage").appendChild(document.getElementById("tmppage").cloneNode(true));
		var size = document.getElementsByName("size");
		for (var i = 0;i<size.length;i++)
			size[i].value = "<?php echo $p->getPageSize();?>";
	}
	<?php endif;?>
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

<!--	<?php echo $cakeDebug;?>-->
</body>
</html>
