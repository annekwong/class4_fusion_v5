<link href="<?php echo $this->webroot?>images/favicon.ico" type="image/x-icon" rel="shortcut Icon">
<script type="text/javascript">
	function save_device(){
		var nm = document.getElementById("service_download_id").value;
		var pw = document.getElementById("name").value;
		var id = document.getElementById("url").value;
		jQuery.post("<?php echo $this->webroot?>testdevices/save_device",{n:nm,p:pw,r_id:id},function(d){
			jGrowl_to_notyfy(d,{theme:'jmsg-alert'});
		});
	}
</script>
<div id="title">
        <h1><?php __('jurisdiction')?></h1>
        <ul id="title-menu">
    		<li>
    			<a class="link_back" href="<?php echo $this->webroot?>systemlimits/jurisdiction_view">
    				<img width="16" height="16" src="<?php echo $this->webroot?>images/icon_back_white.png" alt="">
    				&nbsp;<?php __('back')?>   			</a>
    		</li>
  		</ul>
</div>
<div class="container">
<table class="form">
<tbody>

<tr>
    <td class="label label2"><?php __('name')?>:</td>
    <td class="value value2">
    
    <input style="float:left;width:300px;" type="hidden" id="id" 
    value="<?php if(isset($post[0][0]['id'])){
    echo $post[0][0]['id'];
    
    }?>" 
    name="id" class="input in-text">
    <input style="float:left;width:300px;" type="text" id="name" 
    value="<?php if(isset($post[0][0]['name'])){
    echo $post[0][0]['name'];
    
    }?>" 
    name="alias" class="input in-text"></td>
</tr>
<tr>
    <td class="label label2"><?php __('alias')?>:</td>
    <td class="value value2">
    		<input style="float:left;width:300px;" type="text" id="alias" 
    		
    		    value="<?php if(isset($post[0][0]['alias'])){
    echo $post[0][0]['alias'];
    
    }?>" 
    		name="alias" class="input in-text">
    </td>
</tr>



</tbody></table>

<div id="form_footer">
   <input type="button" value="<?php echo __('submit')?>" onclick="javascript:postLimit();return false;" class="input in-submit">
</div>
</div>

<script type="text/javascript">
	function postLimit(){
	
				var name = $('#name').val();
				var alias = $('#alias').val();
			
				var id = $('#id').val();


				var pattern = /^[1-9]{1}[0-9]*$/;

			//	if(!pattern.test(service_download_id)){
			//		showMessages("[{'field':'#service_download_id','code':'101','msg':'<?php echo __('calllimitinvalid',true)?>'}]");
					//alert('The Call Limit is invalid!');
			//		return false ;
	//			}




				
				$.post("<?php echo $this->webroot?>systemlimits/ajax_update_jurisdiction.json",
						    {name:name,
				       	alias:alias,
					       id:id
				        },
				   function(text){ 
            if(text=='3'){
               showMessages("[{'field':'#name','code':'101','msg':'<?php __('Namealreadyexists')?>'}]");
                        		}
            if(text=='4'){
              	showMessages("[{'field':'#alias','code':'101','msg':'<?php __('Namealreadyexists')?>'}]");
            					}
            if(text=='1'){
            				var myDate=new Date();
            				alert("Action Success");
            				//showMessages("[{'field':'','code':'201','msg':'Add Success'}]");
            				location="<?php echo $this->webroot?>systemlimits/jurisdiction_view/"+myDate;
                        		}
					        },
				 'json');
				}

	</script>
