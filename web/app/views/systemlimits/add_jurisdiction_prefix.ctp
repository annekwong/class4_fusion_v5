<div id="title">
     <h1><?php __('Chargingzoneprefixset')?></h1>
     <ul id="title-menu">
    		<li>
    			<a class="link_back" href="<?php echo $this->webroot?>systemlimits/jurisdiction_view_prefix">
    				<img width="16" height="16" src="<?php echo $this->webroot?>images/icon_back_white.png" alt="">
    				&nbsp;<?php __('back')?>		</a>
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
    <input style="float:left;width:300px;" type="text" id="alias" 
    value="<?php if(isset($post[0][0]['alias'])){
    echo $post[0][0]['alias'];
    
    }?>" 
    name="alias" class="input in-text"></td>
</tr>
<tr>
    <td class="label label2"><?php __('prefix')?>:</td>
    <td class="value value2">
    		<input style="float:left;width:300px;" type="text" id="prefix" 
    		
    		    value="<?php if(isset($post[0][0]['prefix'])){
    echo $post[0][0]['prefix'];
    
    }?>" 
    		name="prefix" class="input in-text">
    </td>
</tr>
</tbody>
</table>
<div id="form_footer">
   <input type="button" value="<?php echo __('submit')?>" onclick="javascript:postLimit();return false;" class="input in-submit">
</div>
</div>

<script type="text/javascript">
	function postLimit(){
				var prefix = $('#prefix').val();
				var alias = $('#alias').val();
				var id = $('#id').val();
				var pattern = /^[+\-]?\d+$/;
				if(!pattern.test(prefix)){
					showMessages("[{'field':'#prefix','code':'101','msg':'prfix must is digits'}]");
					return false ;
				}
				$.post("<?php echo $this->webroot?>systemlimits/ajax_update_jurisdiction_prefix.json",
						    {prefix:prefix,
				       	alias:alias,
					       id:id
				},
				function(text){ 
           if(text=='3'){
               showMessages("[{'field':'#prefix','code':'101','msg':'<?php __('Prefixalreadyexists')?>'}]");
           }if(text=='1'){
               showMessages("[{'code':'201','msg':'<?php __('add_suc')?>'}]");
               alert('Add  Success');
               location="<?php echo $this->webroot?>systemlimits/jurisdiction_view_prefix/"+myDate;
                        	}
				},
				'json');
				var myDate=new Date();

	
	}

	</script>
