<script type="text/javascript">
  var currentTime = 1278411630;
  var L = {"loadingPanel":"Please Wait...","deleteConfirm":"Are you sure to delete this item?","hide-all":"hide all"};
</script>
<div id="title">
        <h1><?php __('addingress')?></h1>
    <ul id="title-menu">
    		<li>
    			<a  class="link_btn"href="#" onclick="history.go(-1)">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png"/>
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
  		</ul>
</div>
<div class="container">
	<ul class="tabs">
	   <li  class="active"><a><img width="16" height="16" src="<?php echo $this->webroot?>images/list.png"/>
	      <?php __('Routestrategy')?></a></li>
	</ul>
		<?php echo $form->create ('Gatewaygroup', array ('action' => 'add_resouce_ingress' ));?>
	<?php echo $form->input('ingress',array('label'=>false ,'value'=>'true','div'=>false,'type'=>'hidden'));?>
		<?php echo $form->input('egress',array('label'=>false ,'value'=>'false','div'=>false,'type'=>'hidden'));?>
		<input type="hidden" id="delete_rate_id" value="" name="delete_rate_id" class="input in-hidden">
<table class="cols"><col width="35%"/><col width="38%"/><col width="27%"/><tr>
<td><!-- COLUMN 1 -->
<?php //**********系统信息**************?>
<fieldset><legend> <?php __('Routestrategy')?></legend>
<table class="form">
<tr>
    <td><?php echo __('Ingress Name',true);?> :</td>
    <td>
   		<?php echo $form->input('alias',array('id'=>'alias','label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'16'));?>
    </td>
</tr>
<!--<tr>
    <td>Routestrategy :</td>
    <td>
   			<?php
   $t=array('1'=>'top-down','2'=>'round-robin');
   			echo $form->input('res_strategy',array('options'=>$t,'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
		
    </td>
</tr>
-->
<tr>
    <td><?php echo __('client')?>:</td>
    <td>
					<?php 
						if(isset($_GET['viewtype'])&& ($_GET['viewtype']=='client' || $_GET['viewtype']=='wizard') ){
							$sel=$_GET ['query'] ['id_clients'];
							echo $form->input('client_id',array('options'=>$c,'selected'=>$sel,'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));
						}else{
							echo $form->input('client_id',array('options'=>$c,'empty'=>'','label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));
						}
					?>
    </td>
</tr>
<tr>
    <td><?php echo __('Media Type',true);?>:</td>
    <td>
		<?php 
		$t=array('0'=>'Proxy Media + Transcoding','1'=>'Proxy Media ','2'=>'Bypass Media');
		echo $form->input('media_type',array('options'=>$t,'label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
    </td>
</tr>
<tr>
    <td><?php __('calllimit')?></td>
    <td>
   	<?php echo $form->input('capacity',array('id'=>'totalCall','label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'6'));?>
    </td>
</tr>

<tr>
    <td><?php __('cps')?></td>
    <td>
  		<?php echo $form->input('cps_limit',array('id'=>'totalCPS','label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'6'));?>
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
							'empty'=>'  ','label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
    </td>
</tr>
</table>
</fieldset>
<fieldset><legend><?php __('routeStrategy')?></legend>
<table class="form">


<tr>
    <td><?php __('routeStrategy')?>:</td>
    <td>
    
 		<?php echo $form->input('route_strategy_id',
 		array('options'=>$route_policy,'empty'=>'  ','label'=>false ,'div'=>false,'type'=>'select'));?>
    </td>

</tr>



</table>
</fieldset>
<!--<fieldset><legend><span id="ht-100012" class="helptip" rel="helptip">

主被叫号转换</span><span id="ht-100012-tooltip" class="tooltip">Send notification when current balance + credit limit is lower than specified threshold. Leave field empty to disable notification.</span></legend>
<table class="form">
<col style="width:37%;"/><col style="width:62%;"/>



<tr>
    <td>转换规则:</td>
    <td>
    
 		<?php echo $form->input('translation_id',
 		array('options'=>$r,'empty'=>'==select==','label'=>false ,'div'=>false,'type'=>'select'));?>
    </td>

</tr>


<tr>
    <td>时间�?</td>
    <td>
    
 		<?php echo $form->input('time_profile_id',
 		array('options'=>$timepro,'empty'=>'==select==','label'=>false ,'div'=>false,'type'=>'select'));?>
    </td>

</tr>
</table>
</fieldset>

--><!-- / COLUMN 1 --></td><td><!-- COLUMN 2 -->
<?php //***************************************费率设置************************************************************?>


<fieldset>
<table class="form">
<col style="width:37%;"/><col style="width:62%;"/>


<tr>
    <td colspan="2" class="value">
    		<div class="cb_select" style="height:90px;text-align: left">
            <div>
		            <?php echo $form->checkbox('active',array('checked'=>'checked'))?>
		            <label for="cp_modules-c_info"><?php __('active')?></label>
		            <?php echo $form->checkbox('t38',array('checked'=>'false'))?>
		            <label for="cp_modules-c_rates"><?php echo __('T38',true);?></label>
            </div>
            <div>
              <?php echo $form->checkbox('lnp',array('checked'=>'false'))?>
              <label for="cp_modules-c_invoices"><?php echo __('lrn',true);?></label>
              </div>
            <div>
              <?php echo $form->checkbox('lrn_block',array('checked'=>'false'))?>
              <label for="cp_modules-c_stats_summary"><?php echo __('Block LRN',true);?></label>
           </div>
           <div>
                                     <?php
	       						empty($post['Gatewaygroup']['dnis_only'])?$au='false':$au='checked';
	       						echo $form->checkbox('dnis_only',array('checked'=>$au))
	       				?>
          <label for="cp_modules-c_stats_summary"><?php echo __('DNIS Only',true);?></label>
         </div>
      </div>
    </td>
</tr>
</table>
</fieldset>
<fieldset><legend> <?php __('codec')?></legend>
<table class="form">
<tr>
    <td>   								
    		<?php echo $form->input('select1',array('id'=>'select1','options'=>$d,'multiple' => true,'style'=>'width: 200px; height: 150px;','label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
    </td>
		 <td>
			  <input  style="width: 48px; height: 25px; margin-left: 0px;"    onclick="DoAdd();"  type="button"  value="<?php __('add')?>"  />
		     <br/><br/>
		    <input  type="button"   style="width: 48px; height: 25px; margin-left: 0px;"  onclick="DoDel();"   value="<?php __('delete')?>"  />
		 </td>
    <td>
       <?php echo $form->input('select2',array('id'=>'select2','options'=>'','multiple' => true,'style'=>'width: 200px; height: 150px;','label'=>false, 'class' =>'select' ,'div'=>false,'type'=>'select'));?>
    </td>
    <td>
					  <input  style="width: 48px; height: 25px; margin-left: 0px;"    onclick="moveOption('select2','up');"  type="button"  value="<?php __('up')?>"  />
				     <br/> <br/>
				    <input  type="button"   style="width: 48px; height: 25px; margin-left: 0px;"  onclick="moveOption('select2','down');"   value="<?php __('Down')?>"  />
		 </td>
</tr>
</table>
</fieldset>
</td>
<td></td>
</tr></table>
<?php echo $this->element("gatewaygroups/host")?>

<?php #*************************************************** ?>


<?php echo $this->element('gatewaygroups/resource_prefix')?>


<?php if($$hel->_get('viewtype')=='wizard'){?>
<div id="form_footer">
    <input type="submit"    onclick="seleted_codes();jQuery('#GatewaygroupAddResouceIngressForm').attr('action','?nextType=egress&<?php echo $$hel->getParams('getUrl')?>')" value="<?php echo __('Next Egress')?>" style="width:80px" />
    <input type="submit"    onclick="seleted_codes();jQuery('#GatewaygroupAddResouceIngressForm').attr('action','?nextType=ingress&<?php echo $$hel->getParams('getUrl')?>')" value="<?php echo __('Next Ingress')?>" style="width:80px"/>
    <input type="button"  value="<?php echo __('End')?>" class="input in-submit" onclick="location='<?php echo $this->webroot?>clients/index?filter_id=<?php echo $$hel->_get('query.id_clients')?>'"/>
</div>
<?php }else{?>
<div id="footer">
  <input type="submit"    onclick="seleted_codes();"  value="<?php echo __('submit')?>" />
    <input type="reset"  value="<?php echo __('reset')?>" class="input in-submit"/>
    </div>
<?php }?>

		<?php echo $form->end();?>



</div>
<script type="text/javascript" src="<?php echo $this->webroot?>js/gateway.js"></script>

<script type="text/javascript">
	 jQuery(document).ready(
				function(){
					jQuery('#totalCall,#totalCPS').xkeyvalidate({type:'Num'});
					jQuery('#alias').xkeyvalidate({type:'strNum'});
				});
</script>
