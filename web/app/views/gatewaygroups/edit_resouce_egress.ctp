<script type="text/javascript">
  var currentTime = 1278411630;
  var L = {"loadingPanel":"Please Wait...","deleteConfirm":"Are you sure to delete this item?","hide-all":"hide all"};
</script>
<div id="title">
  <h1><?php echo "<font  class='editname' title='Name'>".$post['Gatewaygroup']['alias']."</font> " ;?><?php __('Edit  Egress')?></h1>
  <ul id="title-menu">
    <li> 
       <?php //echo $this->element("xback")?>
       <a  class="link_back"href="<?php echo $this->webroot?>gatewaygroups/view_egress">
	          <img width="16" height="16" alt="<?php echo __('goback',true);?>" src="<?php echo $this->webroot?>images/icon_back_white.png"/> <?php echo __('goback',true);?>
       </a>
    </li>
  	</ul>
 </div>
<div class="container">
<ul class="tabs">
   <li class="active" >
   		<a href="<?php echo $this->webroot?>gatewaygroups/edit_resouce_egress">
   				<img width="16" height="16" src="<?php echo $this->webroot?>images/list.png"/><?php __('Routestrategy')?>
   		</a>
   </li><!--
   <li>
   		<a href="<?php echo $this->webroot?>gatewaygroups/add_host?gress=egress&resource_id=<?php echo $post['Gatewaygroup']['resource_id']?>"> 
   			<img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif"/>Host
   		</a>  
   </li>
   --><li>
   		<a href="<?php echo $this->webroot?>gatewaygroups/add_direction?gress=egress">
   				<img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"/><?php __('Action')?>
   		</a>        
   </li>
   </ul>
				<?php echo $form->create ('Gatewaygroup', array ('action' => 'edit_resouce_egress' ));?>
				<?php echo $form->input('resource_id',array('id'=>'alias_','label'=>false ,'value'=>$post['Gatewaygroup']['resource_id'],'div'=>false,'type'=>'hidden','maxlength'=>'16'));?>
  			<?php echo $form->input('ingress',array('label'=>false ,'value'=>'false','div'=>false,'type'=>'hidden'));?>
				<?php echo $form->input('egress',array('label'=>false ,'value'=>'true','div'=>false,'type'=>'hidden'));?>
	 			<input type="hidden" value="<?php echo $post['Gatewaygroup']['resource_id'];?>" name="resource_id"/>
				<table class="cols" style="width:80%;"><col width="35%"/><col width="38%"/><col width="27%"/><tr>
					<td><!-- COLUMN 1 -->
<?php //**********系统信息**************?>
<fieldset><legend><?php __('Routestrategy')?></legend>
<table class="form" >
<tr>
    <td><?php __('Alias')?>:</td>
    <td>
   		<?php echo $form->input('alias',array('id'=>'alias','label'=>false ,'div'=>false,  'value'=>$post['Gatewaygroup']['alias'],'type'=>'text','maxlength'=>'16'));?>
    </td>
</tr>
<tr>
    <td><?php __('HostStrategy')?></td>
    <td>
   		<?php
   			$t=array('1'=>'top-down','2'=>'round-robin');
   			echo $form->input('res_strategy',array('options'=>$t,
   			'selected'=>$post['Gatewaygroup']['res_strategy'],
   			'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));
   				?>
    </td>
</tr>
<tr>
    <td><?php __('Carrier')?>:</td>
    <td>
				<?php echo $form->input('client_id',array('options'=>$c,'empty'=>'','label'=>false,'selected'=>$post['Gatewaygroup']['client_id'],'class' =>'select' ,'div'=>false,'type'=>'select'));?>
    </td>
</tr>
<tr>
    <td><?php echo __('Media Type',true);?>:</td>
    <td>
				<?php 
					$t=array('0'=>'Proxy Media + Transcoding','1'=>'Proxy Media ','2'=>'Bypass Media');
					echo $form->input('media_type',array('options'=>$t,'label'=>false, 'class' =>'select' ,'selected'=>$post['Gatewaygroup']['media_type'],'div'=>false,'type'=>'select'));
				?>
    </td>
</tr>
<tr>
    <td><?php __('calllimit')?></td>
    <td>
   		<?php echo $form->input('capacity',array('id'=>'totalCall','label'=>false ,'div'=>false,'value'=>$post['Gatewaygroup']['capacity'],'type'=>'text','maxlength'=>'6'));?>
    </td>
</tr>
<tr>
    <td><?php __('cps')?></td>
    <td>
  		<?php echo $form->input('cps_limit',array('id'=>'totalCPS','label'=>false ,'div'=>false,'value'=>$post['Gatewaygroup']['cps_limit'],'type'=>'text','maxlength'=>'6'));?>
    </td>
</tr>
<tr>
    <td><?php __('proto')?></td>
    <td>
  				<?php echo $form->input('proto',array('label'=>false ,'value'=>$post['Gatewaygroup']['proto'],'div'=>false,'type'=>'select','options'=>Array(Resource::RESOURCE_PROTO_ALL=>'All',Resource::RESOURCE_PROTO_SIP=>'SIP',Resource::RESOURCE_PROTO_PROTO=>'Proto')));?>
    </td>
</tr>
</table>
</fieldset>
<fieldset><legend><?php __('rateTable')?></legend>
<table class="form">
<tr>
    <td><?php __('rateTable')?>:</td>
    <td>
				<?php echo $form->input('rate_table_id',array('options'=>$rate,
								'empty'=>'  ','label'=>false, 'class' =>'select' ,'selected'=>$post['Gatewaygroup']['rate_table_id'],'div'=>false,'type'=>'select'));?>
    </td>
</tr>
</table>
</fieldset><?php //***************************************费率设置************************************************************?>
<fieldset>
<table class="form">
<col style="width:37%;"/><col style="width:62%;"/>
<tr>
    <td colspan="2" class="value">
    <div class="cb_select" style="height:30px; line-height:30px;text-align: left"><div>
    <?php 
    			empty($post['Gatewaygroup']['active'])?$au='false':$au='checked';
					echo $form->checkbox('active',array('checked'=>$au))
			?>
    <label for="cp_modules-c_info"><?php __('active')?></label>
    <?php
					empty($post['Gatewaygroup']['t38'])?$au='false':$au='checked';
        echo $form->checkbox('t38',array('checked'=>$au,'style'=>'margin-left: 40px;'))
        	?>
     <label for="cp_modules-c_rates"><?php echo __('T38',true);?> <?php echo __('Fax',true);?></label></div><div>
	    <?php
					empty($post['Gatewaygroup']['lnp'])?$au='false':$au='checked';
	      echo $form->checkbox('lnp',array('checked'=>$au))
	        	?>
	    <label for="cp_modules-c_invoices"><?php echo __('lrn',true);?></label></div><div>
      <?php
					empty($post['Gatewaygroup']['lrn_block'])?$au='false':$au='checked';
	      echo $form->checkbox('lrn_block',array('checked'=>$au))
	          	?>
       <label for="cp_modules-c_stats_summary"><?php echo __('Block LRN',true);?></label>
		       </div>
       </div>
    </td>
</tr>
</table>
</fieldset>
<!-- / COLUMN 1 --></td><td><!-- COLUMN 2 -->

<fieldset><legend> <?php __('codec')?></legend>
<table class="form">
<tr>
    <td> 
					<?php echo $form->input('select1',array('id'=>'select1','options'=>$nousecodes,'multiple' => true,
				  'style'=>'width: 200px; height: 150px;',
					'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
			</td>
			<td>
					  <input  style="width: 48px; height: 25px; margin-left: 0px;"    onclick="DoAdd();"  type="button"  value="<?php __('add')?>"class="input in-submit"  />
				    <br/><br/>
				    <input  type="button"   style="width: 48px; height: 25px; margin-left: 0px;"  onclick="DoDel();"   value="<?php __('delete')?>" class="input in-submit" />
			</td>
    <td>
       <?php echo $form->input('select2',array('id'=>'select2','options'=>$usecodes,'multiple' => true,
				  'style'=>'width: 200px; height: 150px;',
					'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
    </td>
     <td>
					  <input class="input in-submit"  style="width: 48px; height: 25px; margin-left: 0px;"    onclick="moveOption('select2','up');"  type="button"  value="<?php __('up')?>"  />
				    <br/><br/>
				    <input  type="button" class="input in-submit"  style="width: 48px; height: 25px; margin-left: 0px;"  onclick="moveOption('select2','down');"   value="<?php __('Down')?>"  />
			 </td>
</tr>
</table>
</fieldset>
</td><td>
<?php //************************client panel**********************************?>



<?php //************************balancenotice**********************************?>
</td></tr></table>
<div>
<?php echo $this->element("gatewaygroups/host")?>
</div>
<div id="form_footer">
    <input type="submit"class="input in-submit"   onclick="seleted_codes();"    value="<?php echo __('submit')?>" />
    <!--  <input type="button" value="<?php echo __('apply')?>" />-->
    </div>
		<?php echo $form->end();?>
</div>
<script type="text/javascript" src="<?php echo $this->webroot?>js/gateway.js"></script>

<script type="text/javascript">
	 jQuery(document).ready(
				function(){
					jQuery('#alias').xkeyvalidate({type:'strNum'});
					jQuery('#totalCall,#totalCPS').xkeyvalidate({type:'Num'});
				}
		);
</script>
<script type="text/javascript">
<!--
  jQuery(document).ready(function(){
	   jQuery('#GatewaygroupLrnBlock').change(function(){
  	    if(jQuery('#GatewaygroupLrnBlock').attr('checked')==true){
           jQuery('#GatewaygroupLnp').attr('checked',true);
           jQuery('#GatewaygroupLnp').attr('disabled','disabled');   
    		  }else{
    			  jQuery('#GatewaygroupLnp').removeAttr('disabled');
        		     }
		   })
	  });

//-->
</script>

