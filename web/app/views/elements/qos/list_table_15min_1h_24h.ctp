<div style="width: 100%;; margin: 0px ">
    <fieldset>
    
    <legend  style='color:#7D858E;font-size:1.1em;font-weight:bold;'> 
   Hide Inactive Items : 
     <input  type="checkbox"   name="hidden_data"  <?php if(isset($_GET['where'])&&$_GET['where']=='active'){echo "checked='checked'";}?>
        onclick="($(this).attr('checked')==true)?(location=location.toString().split('?')[0]+'?where=active'):(location=location.toString().split('?')[0]+'?where=hidden')">  
    </legend>
    
    
    
        	<?php 
					$mydata =$p->getDataArray();
					if(empty($mydata)){
						?>
						<div class="msg"><?php  echo __('no_data_found') ?></div>
						<?php }else{?>
 	<div id="toppage"></div>
   <table class="list nowrap with-fields">
    <thead>
                <tr>
           	<td   width="10%" rowspan="2" rel="0"  style="vertical-align: bottom;"><?php __('ProductName')?> </td>
            <td class="cset-1" colspan="4">15 <?php __('Minutes')?></td>
            <td colspan="4" class="cset-2">1 <?php __('Hour')?></td>
            <td colspan="4" class="cset-3">
            24 <?php __('Hour')?>
            </td>
        </tr>
        <tr>
            <td width="6%" class="cset-1" rel="2">&nbsp; <?php echo $appCommon->show_order('acd_15min',__('acd',true))?>&nbsp;</td>
            <td width="6%" class="cset-1" rel="3">&nbsp;<?php echo $appCommon->show_order('asr_15min',__('asr',true))?>&nbsp;</td>   
              <td width="6%" class="cset-1" rel="2">&nbsp;<?php echo $appCommon->show_order('ca_15min',__('ca',true))?>&nbsp;</td>
            <td width="6%" class="cset-1" rel="3">&nbsp;<?php echo $appCommon->show_order('pdd_15min',__('pdd',true))?>&nbsp;</td> 
               
            <td width="6%" class="cset-1" rel="2">&nbsp;<?php echo $appCommon->show_order('acd_1h',__('acd',true))?>&nbsp;</td>
            <td width="6%" class="cset-1" rel="3">&nbsp;<?php echo $appCommon->show_order('asr_1h',__('asr',true))?>&nbsp;</td>   
              <td width="6%" class="cset-1" rel="2">&nbsp;<?php echo $appCommon->show_order('ca_1h',__('ca',true))?>&nbsp;</td>
            <td width="6%" class="cset-1" rel="3">&nbsp;<?php echo $appCommon->show_order('pdd_1h',__('pdd',true))?>&nbsp;</td>    
            
            <td width="6%" class="cset-3" rel="10">&nbsp;<?php echo $appCommon->show_order('acd_24h',__('acd',true))?></td>
           
               <td width="6%" class="cset-3 last" rel="10" >&nbsp;<?php echo $appCommon->show_order('asr_24h',__('asr',true))?></td>
            <td width="6%" class="cset-3" rel="10" >&nbsp;<?php echo $appCommon->show_order('ca_24h',__('ca',true))?>&nbsp;</td>
           
               <td width="6%" class="cset-3 last" rel="10" >&nbsp;<?php echo $appCommon->show_order('pdd_24h',__('pdd',true))?>&nbsp;</td>
           
        </tr>
    </thead>
    
    	<?php 
					$mydata =$p->getDataArray();
					$loop = count($mydata); 
					for ($i=0;$i<$loop;$i++) {
						
	
					if($i%2==0){
						?>
                        <tbody    id='tbodyOfShowTable'>

                <tr>
 <td class="in-decimal"><strong   >
        <span id="ht-100019" class="helptip" rel="helptip">
        
        <a class=" monitor_product_style_19" href="<?php echo $this->webroot?>monitorsreports/prefix/<?php echo  $mydata[$i][0]['product_id']?>"  style='color:#4B9100'>
  <?php  echo $mydata[$i][0]['name'];?>	</a></span>
            <span id="ht-100019-tooltip" class="tooltip">点击查看这个路由的prefix</span>
            
 
				</strong></td>
 
   <td class="in-decimal"><?php  echo  $mydata[$i][0]['acd_15min']?></td>
   <td class="in-decimal"><?php  echo $mydata[$i][0]['asr_15min']?></td>  
   <td class="in-decimal"><?php echo $mydata[$i][0]['ca_15min']?></td>
  <td class="in-decimal"><?php   echo  $mydata[$i][0]['pdd_15min']?></td>
  
        <td class="in-decimal"><?php echo $mydata[$i][0]['acd_1h']?></td>
        <td class="in-decimal"><?php echo $mydata[$i][0]['asr_1h']?></td>
        <td class="in-decimal"><?php echo $mydata[$i][0]['ca_1h']?></td>
        <td  class="in-decimal"><?php echo $mydata[$i][0]['pdd_1h']?></td>
        <td class="in-decimal"><?php echo $mydata[$i][0]['acd_24h']?></td>
        <td  class="in-decimal"><?php echo $mydata[$i][0]['asr_24h']?></td>
         <td  class="in-decimal"><?php echo $mydata[$i][0]['ca_24h']?></td>
        <td  class="in-decimal last"><?php echo $mydata[$i][0]['pdd_24h']?></td>
    </tr>
        
        
            </tbody>
            <?php }else{?>
                <tbody >

                <tr class="row-2">
						<td class="in-decimal"><strong   >
						<a class=" monitor_product_style_19" href="<?php echo $this->webroot?>monitorsreports/prefix/<?php echo  $mydata[$i][0]['product_id']?>"  style='color:#912C00'>
							   							 <?php  echo $mydata[$i][0]['name'];?>				   						</a>
           </strong></td>
   <td class="in-decimal"><?php echo $mydata[$i][0]['acd_15min']?></td>
   <td class="in-decimal"><?php echo $mydata[$i][0]['asr_15min']?></td>  
   <td class="in-decimal"><?php echo $mydata[$i][0]['ca_15min']?></td>
  <td class="in-decimal"><?php echo $mydata[$i][0]['pdd_15min']?></td>
        <td class="in-decimal"><?php echo $mydata[$i][0]['acd_1h']?></td>
        <td class="in-decimal"><?php echo $mydata[$i][0]['asr_1h']?></td>
        <td class="in-decimal"><?php echo $mydata[$i][0]['ca_1h']?></td>
        <td  class="in-decimal"><?php echo $mydata[$i][0]['pdd_1h']?></td>
        
        <td class="in-decimal"><?php echo $mydata[$i][0]['acd_24h']?></td>
        <td  class="in-decimal"><?php echo $mydata[$i][0]['asr_24h']?></td>
         <td  class="in-decimal"><?php echo $mydata[$i][0]['ca_24h']?></td>
        <td  class="in-decimal last"><?php echo $mydata[$i][0]['pdd_24h']?></td>
    </tr>
        
        
            </tbody>
            <?php }}?>
 </table>
 <div id="tmppage">
<?php echo $this->element('page');?>

<?php }?>
</div>
    </fieldset>
    
    
    </div>