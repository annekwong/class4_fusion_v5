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
        <h1>游戏和铃声下载设置</h1>
        <ul id="title-menu">
    		<li>
    			<a class="link_back" href="<?php echo $this->webroot?>systemlimits/service_view">
    				<img width="16" height="16" src="<?php echo $this->webroot?>images/icon_back_white.png" alt="">
    				&nbsp;<?php echo __('goback',true);?>   			</a>
    		</li>
  		</ul>
</div>
<div class="container">
<table class="form">
<tbody>

<tr>
    <td class="label label2"><?php echo __('id',true);?>:</td>
    <td class="value value2"><input style="float:left;width:300px;" type="text" id="service_download_id" 
    value="<?php if(isset($post[0][0]['service_download_id'])){
    echo $post[0][0]['service_download_id'];
    
    }?>" 
    name="service_download_id" class="input in-text"></td>
</tr>
<tr>
    <td class="label label2">名称:</td>
    <td class="value value2">
    		<input style="float:left;width:300px;" type="text" id="name" 
    		
    		    value="<?php if(isset($post[0][0]['name'])){
    echo $post[0][0]['name'];
    
    }?>" 
    		name="name" class="input in-text">
    </td>
</tr>

<tr>
    <td class="label label2">下载地址:</td>
    <td class="value value2">
    		<input style="float:left;width:300px;" type="text" id="url" 
    		
    		    value="<?php if(isset($post[0][0]['url'])){
    echo $post[0][0]['url'];
    
    }?>" 
    		name="url" class="input in-text">
    </td>
</tr>

</tbody></table>

<div id="form_footer">
   <input type="button" value="<?php echo __('submit')?>" onclick="javascript:postLimit();return false;" class="input in-submit">
</div>
</div>

<script type="text/javascript">
	function postLimit(){
		

			
				var service_download_id = $('#service_download_id').val();
				var name = $('#name').val();
				var url = $('#url').val();


				var pattern = /^[1-9]{1}[0-9]*$/;

				if(!pattern.test(service_download_id)){
					showMessages("[{'field':'#service_download_id','code':'101','msg':'<?php echo __('calllimitinvalid',true)?>'}]");
					//alert('The Call Limit is invalid!');
					return false ;
				}




				
				$.post("<?php echo $this->webroot?>systemlimits/ajax_update_service.json",
						    {service_download_id:service_download_id,
					       name:name,
					       url:url
				        },
				        function(text){ 	showMessages("[{'code':'201','msg':'添加成功'}]");},
				        'json');
			
		location="<?php echo $this->webroot?>systemlimits/service_view/";
	
	}

	</script>
