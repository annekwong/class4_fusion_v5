<style type="text/css">
.group-title.bottom {
-moz-border-radius:0 0 6px 6px;
border-top:1px solid #809DBA;
margin:15px auto 10px;
}
.list td.in-decimal {
text-align:center;
}

.value input, .value select, .value textarea, .value .in-text, .value .in-password, .value .in-textarea, .value .in-select {
-moz-box-sizing:border-box;
width:100px;;
}

.list {
font-size:1em;
margin:0 auto 20px;
width: 100%;
}

#container .form {
margin:0 auto;
width:750px;
}
</style>
<div id="title">
            <h1>

<?php echo __('Prefix Report',true);?>
                        </h1>
        


	    		
<?php echo  $this->element('qos/title_menu_ul');?>


    

        

    </div>

<div id="container">
<?php echo $this->element('stock/carrier_stock'); ?>
<?php echo  $this->element('qos/qos_tab2',array('active_tab'=>$this->params['pass'][0]))?>


<div style="width: 100%;; margin: 0px ">
  <fieldset>
    <legend  style='color:#7D858E;font-size:1.1em;font-weight:bold;'> <?php echo __('Hide Inactive Items',true);?> :
    <input  type="checkbox"   name="hidden_data"  <?php if(isset($_GET['where'])&&$_GET['where']=='active'){echo "checked='checked'";}?>
        onclick="($(this).attr('checked')==true)?(location=location.toString().split('?')+'?where=active'):(location=location.toString().split('?')+'?where=hidden')">
    </legend>
    <?php 
					if(empty($data)){
						?>
    <div class="msg"><?php echo __('no_data_found',true);?></div>
    <?php }else{?>

    <br />
    <table class="list nowrap with-fields">
      <thead>
      <tr>
        <td width="10%" rowspan="2" style="padding-bottom: 20px;" rel="0"><?php echo __('ip',true);?></td>
        <td class="cset-1" colspan="4">15 <?php echo __('minutes',true)?></td>
        <td colspan="4" class="cset-2">1 <?php echo __('hour',true)?></td>
        <td colspan="4" class="cset-3"><span id="ht-100002" class="helptip" rel="helptip">24 <?php echo __('hour',true)?>s</span><span id="ht-100002-tooltip" class="tooltip"><?php __('Average successful rate (percent of successful calls)')?></span></td>
      </tr>
      <tr>
        <td width="6%" class="cset-1" rel="2">&nbsp;<?php echo $appCommon->show_order('acd15m', __('avgduration', true)) ?>&nbsp;</td>
        <td width="6%" class="cset-1" rel="3">&nbsp;<?php echo $appCommon->show_order('asr15m', __('asr', true)) ?>&nbsp;</td>
        <td width="6%" class="cset-1" rel="2">&nbsp;<?php echo $appCommon->show_order('ca15m', __('calls', true)) ?>&nbsp;</td>
        <td width="6%" class="cset-1" rel="3">&nbsp;<?php echo $appCommon->show_order('pdd15m', __('calldelay', true)) ?>&nbsp;</td>
        <!-- 
             <td width="6%" class="cset-1" rel="3">&nbsp;profitability&nbsp;</td> 
               
            -->
      <td width="6%" class="cset-1" rel="2">&nbsp;<?php echo $appCommon->show_order('acd1h', __('avgduration', true)) ?>&nbsp;</td>
        <td width="6%" class="cset-1" rel="3">&nbsp;<?php echo $appCommon->show_order('asr1h', __('asr', true)) ?>&nbsp;</td>
        <td width="6%" class="cset-1" rel="2">&nbsp;<?php echo $appCommon->show_order('ca11h', __('calls', true)) ?>&nbsp;</td>
        <td width="6%" class="cset-1" rel="3">&nbsp;<?php echo $appCommon->show_order('pdd1h', __('calldelay', true)) ?>&nbsp;</td>
        <!--    
              <td width="6%" class="cset-1" rel="3">&nbsp;profitability&nbsp;</td> 
              
              
            -->
        <td width="6%" class="cset-1" rel="2">&nbsp;<?php echo $appCommon->show_order('acd24h', __('avgduration', true)) ?>&nbsp;</td>
        <td width="6%" class="cset-1" rel="3">&nbsp;<?php echo $appCommon->show_order('asr24h', __('asr', true)) ?>&nbsp;</td>
        <td width="6%" class="cset-1" rel="2">&nbsp;<?php echo $appCommon->show_order('ca124h', __('calls', true)) ?>&nbsp;</td>
        <td width="6%" class="cset-1" rel="3">&nbsp;<?php echo $appCommon->show_order('pdd24h', __('calldelay', true)) ?>&nbsp;</td>
        <!--
             <td width="6%" class="cset-1" rel="3">&nbsp;profitability&nbsp;</td> 
        --></tr>
    </thead>
      <tbody    id='tbodyOfShowTable'>
<?php
        foreach($data as $mydata) {
?>
        <tr>
          <td class="in-decimal"><strong   > <span id="ht-100019" class="helptip" rel="helptip"><a class=" monitor_product_style_19"
         href="###"  style='color:#4B9100'>
            <?php  echo $mydata['ip'];?>
            </a></span> </strong></td>
          <td class="in-decimal"><?php  echo  number_format($mydata['acd15m'],2)?></td>
          <td class="in-decimal"><?php  echo number_format($mydata['asr15m'],2)?></td>
          <td class="in-decimal"><?php echo number_format( $mydata['ca15m'],0)?></td>
          <td class="in-decimal"><?php   echo  number_format($mydata['pdd15m'],0)?></td>
          <td class="in-decimal"><?php  echo  number_format($mydata['acd1h'],2)?></td>
          <td class="in-decimal"><?php  echo number_format($mydata['asr1h'],2)?></td>
          <td class="in-decimal"><?php echo number_format( $mydata['ca1h'],0)?></td>
          <td class="in-decimal"><?php   echo  number_format($mydata['pdd1h'],0)?></td>
          <td class="in-decimal"><?php  echo  number_format($mydata['acd24h'],2)?></td>
          <td class="in-decimal"><?php  echo number_format($mydata['asr24h'],2)?></td>
          <td class="in-decimal"><?php echo number_format( $mydata['ca24h'],0)?></td>
          <td class="in-decimal"><?php   echo  number_format($mydata['pdd24h'],0)?></td>

        </tr>
 <?php
      }
?>
      </tbody>
     
    </table>
    </div>
    <?php }?>
  </fieldset>
</div>


    
    
     

</div>
<div>

</div>

    

	