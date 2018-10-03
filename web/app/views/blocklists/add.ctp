<script type="text/javascript">
    //<![CDATA[
    var currentTime = 1278411630;
    var L = {"loadingPanel":"Please Wait...","deleteConfirm":"Are you sure to delete this item?","hide-all":"hide all"};
    //]]>
</script>
	<div id="title">
    <h1><?php echo __('addbolcklist')?>&gt;&gt;</h1>
    <ul id="title-menu">
    		<li>
    			<a  class="link_back"href="<?php echo $this->webroot?>blocklists/view">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png"/>
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
  		</ul>
	</div>
<div class="container">    <script type="text/javascript">winResize(940, 690);</script>


	<?php echo $form->create ('Blocklist', array ('action' => 'index' ));?>

<table class="cols"><col width="35%"/><col width="38%"/><col width="27%"/><tr>
<td><!-- COLUMN 1 -->

<fieldset><legend><?php echo __('egress')?></legend>
<table class="form">

<tr>
    <td><?php echo __('Egress Carriers',true);?>:</td>
    <td>
    
<?php echo $form->input('egress_client_id',
 		array('options'=>$client,'label'=>false ,'empty'=>'','onchange'=>'ajax_egress("'.$this->webroot.'",this.value)',
'div'=>false,'type'=>'select'));?>
    
    </td>

</tr>


<tr>
    <td><?php echo __('egress')?>:</td>
    <td>
   		<?php echo $form->input('engress_res_id',
 		array('options'=>$egress,'empty'=>'All','label'=>false ,
 	
 		'div'=>false,'type'=>'select'));?>
    </td>
</tr>


<tr>
    <td><?php echo __('prefix')?>:</td>
    <td>
   		<?php echo $form->input('digit',
 		array('label'=>false ,'div'=>false,'type'=>'text'));?>
    </td>
</tr>

</table>

</fieldset>



<!-- / COLUMN 1 --></td><td><!-- COLUMN 2 -->
<?php //***************************************对接设置************************************************************?>
<fieldset><legend> <?php echo __('ingress')?></legend>
<table class="form">
<tr>
    <td><?php echo __('Ingress Carriers',true);?>:</td>
    <td>
    
					    		<?php 

					    		echo $form->input('ingress_client_id',
 		array('options'=>$client,'label'=>false ,'empty'=>'','onchange'=>'ajax_ingress("'.$this->webroot.'",this.value)',
 		'div'=>false,'type'=>'select'));?>
    
    </td>

</tr>
<tr>
    <td><?php echo __('gatewayid')?>:</td>
    <td>
    
	    		<?php echo $form->input('ingress_res_id',
 		array('options'=>$ingress,'empty'=>'All','label'=>false ,
 		
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
    </td>

</tr>
</table>
</fieldset>




<!-- / COLUMN 3 -->
</td></tr></table>

<div id="form_footer">
            <input type="submit" value="<?php echo __('submit',true);?>"class="input in-submit" />

    <input type="reset" onClick="winClose();" value="<?php echo __('reset',true);?>" class="input in-submit" />
   
    </div>
		<?php echo $form->end();?>



</div>
<script type="text/javascript" 	src="<?php echo $this->webroot?>js/res.js"></script>
<script type="text/javascript">
jQuery(document).ready(
	function(){
         	jQuery('#BlocklistDigit').xkeyvalidate({type:'Ip'});	
	}
);

</script>

