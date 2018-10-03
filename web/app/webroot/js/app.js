var App = (function($){	
		$.fn.extend({
			checkAll : function(_checkboxes){
				this.each(function(){
					var that = $(this);
					var checkboxes = '';
					if(typeof(_checkboxes)=='function'){
						checkboxes = _checkboxes(that);
					}else{
						checkboxes = _checkboxes;
					}					
					$(checkboxes).bind('click',function(){
						if(this.checked == true){
							$(that).attr('checked',true);
						}
					});
					
					that.bind('click',function(){			
						if(this.checked == true){
							$(checkboxes).attr('checked',true);
						}else{
							$(checkboxes).attr('checked',false);
						}
					});
				});
				
				return this;
			},
			selectAll : function(checkboxes){
				var that = this;
				$(checkboxes).live('click',function(){
					if(this.checked == false){
						$(that).attr('checked',false);
					}
				});
				this.live('click',function(){
					if(this.checked == true){
						$(checkboxes).attr('checked',true);
					}else{
						$(checkboxes).attr('checked',false);
					}
				});
				return this;
			}
		});
	return {
		WEBROOT : "/Class4",
		Common : {
			updateDivByAjax : function(url, updateDIV,data,_options) {
				var options = {};
				options = $.extend(options, _options || {});
				if(options['callback']){
					if(!eval(options['callback'])){
						return false;
					}
				}
				if(options['confirm']){
					if(!confirm(options['confirm'])){
						return false;
					}
				}
				if(options['getIndexCallback']){
					data['index'] = eval(options['getIndexCallback']);
				}
				$.get(url, data, function(html){
					if(html == 'do_delete'){
						$(updateDIV).remove();
					}else{
						$(updateDIV).html(html);
						if(options['success']){
							options['success']();
						}
					}
				});
				return false;
			},
            isBlank : function(value){
                return $.trim(value) == '';
            },
			hasIndexCheckboxChecked : function(name){
				if($("input[type=checkbox][name="+name+"]:checked").length <= 0){					
					return false;
				}
				return true;
			},
			getIndexCheckboxValues : function(name){
				var a = new Array();
				var d = $("input[type=checkbox][name="+name+"]:checked").map(function(){return this.value;});
				return a.join.call(d);
			},
			
			initSimpleTabDiv : function(_activedDiv,divs){
				var activedDiv = _activedDiv;
				$(divs[activedDiv]).show();
				for(var key in divs){
					$(key).bind('click',(function(k){
						return function(){
							if(activedDiv != k){
								$(divs[activedDiv]).hide();
								$(divs[k]).show();
								activedDiv = k;
							}
						}
					})(key));
				}
			},
			autoIncrement : function(_start,_step){
				var start = 1;
				var step = 1;
				var s=1;
				if(_start){ start = parseInt(_start);}
				if(_step) { step = parseInt(_step);}
				return function(){
					s = start;
					start = start + step;
					return s;
				}
			}
		},
		Order : {
			OrderBrowsers : {
				onBuyLoad : function(){
					$("#filterAcd").bind('change',function(){
						if(this.value === 'custom'){
							$('#filter_acd_div').attr('style',"display: inline-block;width:70px;");
						}else{
							$('#filter_acd_div').attr('style',"display: none;width:70px;");
						}
					});
					$("#filterAsr").bind('change',function(){
						if(this.value === 'custom'){
							$('#filter_asr_div').attr('style',"display: inline-block;width:70px;");
						}else{
							$('#filter_asr_div').attr('style',"display: none;width:70px;");
						}
					});
				}				
			},
			OrderResponses : {				
				
			},
			OrderPlaces : {
				onOrderSubmit : function(){
				var flag=true;
				var rate_obj=$('#OrderForm').find('input[name*=rate]');
				if(rate_obj.val()==''){
					show_filter_mess(rate_obj,5,'Please fill rate field correctly (only  digits allowed).');
					flag=false;
					
				}
					if($("#regions input[type=checkbox]:checked").length <= 0){
						show_filter_mess($("#Country"),5,'Please select country field .');
						flag=false;
					}
					
					
					
					
					return flag;
				},
				onBuyLoad : function(){
					$("input[name='data[BuyOrder][is_private]']").bind('click',function(){
						if(this.checked){
							if(this.value == 1){
								$("input[name='data[BuyOrder][is_commit]']").attr('disabled',false);
								$("input[name='data[BuyOrder][commit_minutes]']").attr('disabled',false);
								$("input[name='data[BuyOrder][expire_time]']").attr('disabled',false);
								$('#is_commit_tr').show();
								$('#commit_minutes_tr').show();
								$('#expire_time_tr').show();
							}else{
								$("input[name='data[BuyOrder][is_commit]']").attr('disabled',true);
								$("input[name='data[BuyOrder][commit_minutes]']").attr('disabled',true);
								$("input[name='data[BuyOrder][expire_time]']").attr('disabled',true);
								$('#is_commit_tr').hide();
								$('#commit_minutes_tr').hide();
								$('#expire_time_tr').hide();
							}
					}});
					$("input[name='data[BuyOrder][is_commit]']").bind('click',function(){
						if(this.checked){
							if(this.value == 1){
								$("input[name='data[BuyOrder][commit_minutes]']").attr('disabled',false);
								$("input[name='data[BuyOrder][expire_time]']").attr('disabled',false);
								$('#commit_minutes_tr').show();
								$('#expire_time_tr').show();
							}else{
								$("input[name='data[BuyOrder][commit_minutes]']").attr('disabled',true);
								$("input[name='data[BuyOrder][expire_time]']").attr('disabled',true);
								$('#commit_minutes_tr').hide();
								$('#expire_time_tr').hide();
							}
						}
					});
				},
				onSellLoad : function(){
					$("input[name='data[SellOrder][is_private]']").bind('click',function(){
						if(this.checked){
							if(this.value == 1){
								$("input[name='data[SellOrder][is_commit]']").attr('disabled',false);
								$("input[name='data[SellOrder][commit_minutes]']").attr('disabled',false);
								$("input[name='data[SellOrder][expire_time]']").attr('disabled',false);
								$('#is_commit_tr').show();
								$('#commit_minutes_tr').show();
								$('#expire_time_tr').show();
							}else{
								$("input[name='data[SellOrder][is_commit]']").attr('disabled',true);
								$("input[name='data[SellOrder][commit_minutes]']").attr('disabled',true);
								$("input[name='data[SellOrder][expire_time]']").attr('disabled',true);
								$('#is_commit_tr').hide();
								$('#commit_minutes_tr').hide();
								$('#expire_time_tr').hide();
							}
					}});
					$("input[name='data[SellOrder][is_commit]']").bind('click',function(){
						if(this.checked){
							if(this.value == 1){
								$("input[name='data[SellOrder][commit_minutes]']").attr('disabled',false);
								$("input[name='data[SellOrder][expire_time]']").attr('disabled',false);
								$('#commit_minutes_tr').show();
								$('#expire_time_tr').show();
							}else{
								$("input[name='data[SellOrder][commit_minutes]']").attr('disabled',true);
								$("input[name='data[SellOrder][expire_time]']").attr('disabled',true);
								$('#commit_minutes_tr').hide();
								$('#expire_time_tr').hide();
							}
						}
					});
				}
			}
		}
	};	
})(jQuery);
