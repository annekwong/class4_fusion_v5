
<div id="chart" style="background-image: none;">
	<div id="flashcontent"><strong><?php __('You need to upgrade your Flash Player')?></strong></div>	
</div>
<?php 
	$params = $this->params['url'];
	
	unset($params['url']);
	unset($params['ext']);
	unset($params['time']);
	unset($params['col']);
	$ap=array();
	foreach($params as $k=>$v){
		$ap[] = $k.'='.urlencode($v);
	}

	$params_str = join('&',$ap);
	//	pr($params_str);
?>
<script type="text/javascript">
	var CHART = new SWFObject("<? echo WEBROOT?>amline.swf", "amline", "935", "250", "8", "#FFFFFF");
	CHART.addParam("wmode", "transparent");
	CHART.addVariable("path", "<? echo WEBROOT?>");
	CHART.addVariable("settings_file", encodeURIComponent("<? echo WEBROOT?><?php echo $this->params['controller']?>/<?php echo $this->params['action']?>_settings.xml?<?php echo $params_str?>"));
	CHART.write('flashcontent');
	
	CHART.app_setting = {};	
	CHART.app_setting.time = '<?php echo array_keys_value($this->params,'url.time','today')?>';
	CHART.app_setting.col = '<?php echo array_keys_value($this->params,'url.col','call_duration')?>';
	
	(function($){
		var initAmchartRefreshTime = function(_actived,periods){
			var actived = _actived;	
			$(periods).each(function(){				
				$('#range-nav #' + this).bind('click',(function(period){
					//alert(period);
					return function(){
						if(actived != period){							
							if(window.CHART){
								//重新获取xml
								$('#range-nav #' + period).addClass('active');
								$('#range-nav #' + actived).removeClass('active');
								window.CHART.addVariable("settings_file",encodeURIComponent("
								<? echo WEBROOT?><?php echo $this->params['controller']?>/<?php echo $this->params['action']?>_settings.xml?<?php echo $params_str?>&time="+period+'&col='+window.CHART.app_setting.col));
								window.CHART.write('flashcontent');
							}
							actived = period;
							window.CHART.app_setting.time = period;
						}
					}
				})(this));
			});
			$('#range-nav #' +actived).trigger('click');
		}

		var initAmchartRefreshCol = function(_actived,cols){
			var actived = _actived;
			$('#metrics div[rel=' + _actived + ']').addClass('selected-base selected');
			$(cols).each(function(){				
				$('#metrics div[rel=' + this + ']').bind('click',(function(col){
					return function(){
						if(actived != col){							
							if(window.CHART){
								$('#metrics div[rel=' + col + ']').addClass('selected-base selected');
								$('#metrics div[rel=' + actived + ']').removeClass('selected-base selected');
								window.CHART.addVariable("settings_file",encodeURIComponent("<? echo WEBROOT?><?php echo $this->params['controller']?>/<?php echo $this->params['action']?>_settings.xml?<?php echo $params_str?>&col="+col+'&time='+window.CHART.app_setting.time));
								window.CHART.write('flashcontent');
							}
							actived = col;
							window.CHART.app_setting.col = col;
						}
					}
				})(this));
			});
			
				
		}

		
		$(document).ready(function(){
			initAmchartRefreshTime(CHART.app_setting.time,['today','week','month','3month','6month','1year','2year']);
			initAmchartRefreshCol(CHART.app_setting.col,['call_duration','asr','acd','call_attempt','success_call','failed_call','avg_buy_price','avg_sell_price','total_buy_volume','total_sell_volume'])
			$('#metrics #' + CHART.app_setting.col).addClass('selected-base selected');	
			$('#metrics .metric').bind('mouseover',function(){
				$(this).addClass("hovered");
			});
			$('#metrics .metric').bind('mouseout',function(){
				$(this).removeClass("hovered");
			});
				
		});
	})(jQuery);	
</script>