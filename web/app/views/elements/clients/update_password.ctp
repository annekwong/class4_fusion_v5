
<!--
<style>
		body { font-size: 62.5%; }
		label, input { display:block; }
		input.text { margin-bottom:12px; width:95%; padding: .4em; }
		fieldset { padding:0; border:0; margin-top:5px; }
		h1 { font-size: 1.2em; margin: .6em 0; }
		div#users-contain { width: 350px; margin: 20px 0; }
		div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
		div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
		.ui-dialog .ui-state-error { padding: .3em; }
		.validateTips { border: 1px solid transparent; padding: 0.3em; }
</style>
-->
<script>


	$(function() {
		// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
		
		var 	password = $( "#password" ),
					pass_client_id=$("#pass_client_id"),
					allFields = $( [] ).add( password ).add(pass_client_id),
					tips = $( ".validateTips" );

		function updateTips( t ) {
			tips
				.text( t )
				.addClass( "ui-state-highlight" );
			setTimeout(function() {
				tips.removeClass( "ui-state-highlight", 1500 );
			}, 500 );
		}

		function checkLength( o, n, min, max ) {
			if ( o.val().length > max || o.val().length < min ) {
				o.addClass( "ui-state-error" );
				updateTips( "Length of " + n + " must be between " +
					min + " and " + max + "." );
				return false;
			} else {
				return true;
			}
		}

		function checkRegexp( o, regexp, n ) {
			if ( !( regexp.test( o.val() ) ) ) {
				o.addClass( "ui-state-error" );
				updateTips( n );
				return false;
			} else {
				return true;
			}
		}
		
		$( "#dialog-form" ).dialog({
			autoOpen: false,
			height: 140,
			width: 350,
			modal: true,
			close: function() {
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});


		$( "#create-cancel" )	.button().click(function() { $( "#dialog-form" ).dialog("close");});
		$( "#create-user" )
			.button()
			.click(function() {
				var bValid = true;
				allFields.removeClass( "ui-state-error" );
				//bValid = bValid && checkLength( password, "password", 2, 16 );
				//bValid = bValid && checkRegexp( password, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9" );
				if ( bValid ) {
					$.ajax({
						url:"<?php echo $this->webroot?>clients/ajax_update_password.json",
						data:{client_id:pass_client_id.val(),password:password.val()},
						type:'POST',
						async:false,
						success:function(text){
						if(text=='1'){

								showMessages("[{'field':'#ingrLimit','code':'201','msg':'Password  change success'}]");
							}
						},
						error:function(XmlHttpRequest){showMessages("[{'field':'#ingrLimit','code':'101','msg':'"+XmlHttpRequest.responseText+"'}]");}
				});
				}
				$( "#dialog-form" ).dialog("close");
			});


		$(document).find('*[rel=update_password]').live('click',function () {
			
			$( "#dialog-form" ).dialog("open");
			var hidden_client_id=$(this).closest('tr').find('input[name*=hidden_client_id]').val();
			if(hidden_client_id!=null&& hidden_client_id!=''){
				$('#pass_client_id').val(hidden_client_id);
				}
		   });

		
	});
	</script>


<div class="demo">

<div id="dialog-form" style="display: none;" title="">
	
	<form>
	<fieldset>
		<label for="password"  ><?php echo __('New Password',true);?></label><br/>
		<input type="hidden"  name="pass_client_id"  id="pass_client_id"  />
		<input type="password" name="password" id="password" value=""   class="text ui-widget-content ui-corner-all" />
	</fieldset>
	
	
		<fieldset  style="text-align: center;">
		<button id="create-user"><?php echo __('submit',true);?></button> <button id="create-cancel"><?php echo __('cancel',true);?></button>
	</fieldset>
	</form>
	<p class="validateTips"  ></p>
</div>

</div><!-- End demo -->


