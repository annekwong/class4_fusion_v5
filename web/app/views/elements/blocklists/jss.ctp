<script type="text/javascript">
	blocklist={
		trAddCallback:function(options){
			jQuery('input[type=text],input[type=password]').addClass('input in-input in-text');
			jQuery('input[type=button],input[type=submit]').addClass('input in-submit');
			jQuery('select').addClass('select in-select');
			jQuery('textarea').addClass('textarea in-textarea');
			jQuery('#'+options.log+' #ResourceBlockEgressClientId').change(
				function(){
					var id=jQuery(this).val();
					if(id=='')
					{
						jQuery('#'+options.log+' #ResourceBlockEngressResId').html('');
						return;
					}
					jQuery.get("<?php echo $this->webroot?>trunks/ajax_options",{type:'egress','filter_id':id},function(data){
						jQuery('#'+options.log+' #ResourceBlockEngressResId').html('');
						var arr=eval(data);
						//jQuery('<option/>').html('select').val('').appendTo('#'+options.log+' #ResourceBlockEngressResId');
						if(arr.length == 0){
							jQuery('<option/>').html('').val('').appendTo('#'+options.log+' #ResourceBlockEngressResId');
						}else{
							if (global_option.id == '') {
								jQuery('<option/>').html('All Trunk').val('all').appendTo('#' + options.log + ' #ResourceBlockEngressResId');
							}
						}
						for(i in arr){
							jQuery('<option/>').val(arr[i].resource_id).html(arr[i].alias).appendTo('#'+options.log+' #ResourceBlockEngressResId');
						}
						if (jQuery('#'+options.log+' #ResourceBlockEngressResId').attr('v') != ''){
							jQuery('#'+options.log+' #ResourceBlockEngressResId').val(jQuery('#'+options.log+' #ResourceBlockEngressResId').attr('v')).attr('v','');
						}
					});
				}
			).change();
			jQuery('#'+options.log+' #ResourceBlockIngressClientId').change(
				function(){
					var id=jQuery(this).val();
					if(id=='')
					{
						jQuery('#'+options.log+' #ResourceBlockIngressResId').html('');
						return;
					}
					jQuery.get("<?php echo $this->webroot?>trunks/ajax_options",{'filter_id':id, 'trunk_type2':0, 'show_type' : 1},function(data){
						jQuery('#'+options.log+' #ResourceBlockIngressResId').html('');
						var arr=eval(data);

						if(arr.length == 0){
							jQuery('<option/>').html('').val('').appendTo('#'+options.log+' #ResourceBlockIngressResId');
						}else{
							if (global_option.id == '') {
								jQuery('<option/>').html('All Trunk').val('all').appendTo('#' + options.log + ' #ResourceBlockIngressResId');
							}
						}

						//jQuery('<option/>').html('select').val('').appendTo('#'+options.log+' #ResourceBlockIngressResId');
						console.log(data);
                        for(i in arr){
							jQuery('<option/>').val(arr[i].resource_id).html(arr[i].alias).appendTo('#'+options.log+' #ResourceBlockIngressResId');
						}
						if (jQuery('#'+options.log+' #ResourceBlockIngressResId').attr('v')!= ''){
							jQuery('#'+options.log+' #ResourceBlockIngressResId').val(jQuery('#'+options.log+' #ResourceBlockIngressResId').attr('v')).attr('v','');
						}
					});
				}
			).change();
			jQuery('#ResourceBlockDigit').xkeyvalidate({type:'Num'});
			if(jQuery('table.list').css('display')=="none"){
				jQuery('table.list').show();
			}
		},
		trAddOnsubmit:function(options){
            if(!options.id){
                options.id = global_option.id;
            }
			var re=true;
			var Digit=jQuery('#'+options.log).find('#ResourceBlockDigit').val();
			var Ani=jQuery('#'+options.log).find('#ResourceBlockAniPrefix').val();
			var Egress=jQuery('#'+options.log).find('#ResourceBlockEgressClientId').val();
			var Ingress=jQuery('#'+options.log).find('#ResourceBlockIngressClientId').val();
			var EgressTrunk=jQuery('#'+options.log).find('#ResourceBlockEngressResId').val();
			var IngressTrunk=jQuery('#'+options.log).find('#ResourceBlockIngressResId').val();
//			if(!Digit && !Ani && !EgressTrunk && !IngressTrunk)
//			{
//				jQuery.jGrowlError("<?php //__("block control notice"); ?>//");
//				return false;
//			}
			var data=jQuery.ajaxData('<?php echo $this->webroot?>blocklists/ajaxValidateRepeat?id='+options.id+'&digit='+Digit+'&ani='+Ani+'&egress_trunk='+EgressTrunk+'&ingress_trunk='+IngressTrunk);

			if(!data.indexOf('false')){
				jQuery.jGrowlError(Digit+' is already in use! ');
				jQuery('#'+options.log).find('#ResourceBlockDigit').addClass('invalid');
				jQuery('#'+options.log).find('#ResourceBlockEgressClientId').addClass('invalid');
				jQuery('#'+options.log).find('#ResourceBlockIngressClientId').addClass('invalid');
				re=false;
			}
			return re;
		}
	}
</script>