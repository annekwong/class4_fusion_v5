<script type="text/javascript">
    //<![CDATA[
    var currentTime = 1278411630;
    var L = {"loadingPanel":"Please Wait...","deleteConfirm":"Are you sure to delete this item?","hide-all":"hide all"};
    //]]>
    </script>
	<div id="title">
    <h1><?php echo __('editbolcklist')?></h1>
    <ul id="title-menu">
    		<li>
    			<a class="link_back" href="<?php echo $this->webroot?>blocklists/index">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png"/>
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
  		</ul>
	</div>
<div class="container">    <script type="text/javascript">winResize(940, 690);</script>
	<?php echo $form->create ('Blocklist', array ('action' => 'edit' ));?>
	<?php echo $form->input('res_block_id',array('label'=>false,'div'=>false,'type'=>'hidden','value'=>$post['Blocklist']['res_block_id']))?>
 <input type="hidden" value="<?php echo $post['Blocklist']['res_block_id']?>" name="client_id"/>
<input type="hidden" value="<?php echo $post['Blocklist']['res_block_id']?>" name="res_block_id"/>
<table class="cols"><col width="35%"/><col width="38%"/><col width="27%"/><tr>
<td><!-- COLUMN 1 -->

<fieldset><legend><?php echo __('egress')?></legend>
<table class="form">


<tr>
    <td><?php echo __('Egress Carriers',true);?>:</td>
    <td>
    
					    		<?php 
					    				echo $form->input('egress_client_id',
									 		array('options'=>$client,'label'=>false ,'empty'=>'','onchange'=>'ajax_egress("'.$this->webroot.'",this.value)',
									 		'div'=>false,'type'=>'select'));
									?>
    </td>
</tr>
<tr>
    <td><?php echo __('egress')?>:</td>
    <td>
   		<?php echo $form->input('engress_res_id',  
 		array(  'selected'=>array_keys_value($post,'Blocklist.engress_res_id'),  'options'=>$egress,'empty'=>__('pleaseselectengress',true),'label'=>false ,
 	
 		'div'=>false,'type'=>'select'));?>
    </td>
</tr>


<tr>
    <td><?php echo __('prefix')?>:</td>
    <td>
   		<?php echo $form->input('digit',
 		array('label'=>false ,'div'=>false,'type'=>'text','value'=>$post['Blocklist']['digit'],));?>
    </td>
</tr>

</table>

</fieldset>



<!-- / COLUMN 1 --></td><td><!-- COLUMN 2 -->
<?php //***************************************对接网关设置************************************************************?>
<fieldset><legend> <?php echo __('ingress')?></legend>
<table class="form">
<tr>
    <td><?php echo __('Ingress Carriers',true);?>: 	:</td>
    <td>
    
					    		<?php 
					    		echo $form->input('ingress_client_id',
 		array('options'=>$client,'label'=>false ,
 		'selected'=>$post['Blocklist']['ingress_client_id'],
 		'empty'=>__('All',true),'onchange'=>'ajax_ingress("'.$this->webroot.'",this.value)',
 		
 		'div'=>false,'type'=>'select'));?>
    
    </td>

</tr>
<tr>
    <td><?php __('Ingress Trunk')?>:</td>
    <td>
    
	    		<?php echo $form->input('ingress_res_id',
 		array('options'=>$ingress,'empty'=>__('All',true),'label'=>false ,'selected'=>$post['Blocklist']['ingress_res_id'],
 		
 		'div'=>false,'type'=>'select'));?>

    </td>

</tr>


</table>
</fieldset>








<!-- / COLUMN 2 --></td><td><!-- COLUMN 3 -->

<fieldset><legend> <?php echo __('timeprofile')?></legend>
<table class="form">
<tr>
    <td><?php echo __('timeprofile')?>:</td>
    <td>
    			<select id="BlocklistTimeProfileId" name="data[Blocklist][time_profile_id]">
    					<option value=""><?php echo __('select')?></option>
    					<?php
    						$loop = count($timeprofiles);
    						for ($i = 0;$i<$loop;$i++) { 
    					?>
    							<option value="<?php echo $timeprofiles[$i][0]['time_profile_id']?>"><?php echo $timeprofiles[$i][0]['name']?></option>		
    				<?php
    							} 
    					?>
    			</select>
    			
    			<script>document.getElementById('BlocklistTimeProfileId').value="<?php echo $post['Blocklist']['time_profile_id']?>";</script>
    </td>

</tr>
</table>
</fieldset>





<!-- / COLUMN 3 -->
</td></tr></table>

<div id="form_footer">
            <input type="submit" value="<?php echo __('submit',true);?>" class="input in-submit" />

    <input type="reset" onClick="winClose();" value="<?php echo __('reset',true);?>" class= "input in-submit" />
   
    </div>
		<?php echo $form->end();?>



<script type="text/javascript">
//<![CDATA[
function fullDelete() {
    if (!confirm('Are you sure to delete this client? It will be impossible to restore data.')) {
        return false;
    }
    if (!confirm('ARE YOU SURE?')) {
        return false;
    }
    winOpen('/admin/clients/fullDelete?id=');
    winClose();
}

/*
 * Hide allowed credit in post-paid mode
 */
function checkMode()
{
	var mode = $('select[name=radius_strict_auth]').val();
	if (mode == '1') {
		$('#cell-credit input').attr('readonly', '').removeClass('disabled');
	} else {
		$('#cell-credit input').attr('readonly', 'readonly').addClass('disabled');
	}
}
$(function () {
	checkMode();
	$('select[name=radius_strict_auth]').bind('change', checkMode);
});

//]]>
</script></div>
<script type="text/javascript" 	src="<?php echo $this->webroot?>js/res.js"></script>
<script type="text/javascript">
       jQuery(document).ready(
		function(){
			jQuery('#BlocklistDigit').xkeyvalidate({type:'Ip'});	
		}
);           

</script>
