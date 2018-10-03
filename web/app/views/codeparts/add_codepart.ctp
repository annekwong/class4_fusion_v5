
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />

    
    <script type="text/javascript">
    //<![CDATA[
    var currentTime = 1278411630;
    var L = {"loadingPanel":"Please Wait...","deleteConfirm":"Are you sure to delete this item?","hide-all":"hide all"};
    //]]>
    </script>

<div id="title">
        <h1>新增号段</h1>
                <ul id="title-menu">
    		<li>
    			<a class="link_back"href="<?php echo $this->webroot?>clients/view">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png"/>
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
  		</ul>
</div>
<div class="container">


		<?php echo $form->create ('Codepart', array ('action' => 'add_codepart' ));?>

<table class="cols"><col width="35%"/><col width="38%"/><col width="27%"/><tr>
<td><!-- COLUMN 1 -->



<?php //**********系统信息**************?>
<fieldset><legend>号段设置</legend>
<table class="form">
<tr>
    <td>开始号�?</td>
    <td>
    <?php echo $form->input('start_code',array('label'=>false,'div'=>false,'type'=>'text'))?>
    </td>
</tr>

<tr>
    <td>结束号段:</td>
    <td>
    <?php echo $form->input('end_code',array('label'=>false,'div'=>false,'type'=>'text'))?>
    </td>
</tr>











</table>

</fieldset>



<!-- / COLUMN 1 --></td><td><!-- COLUMN 2 -->
<?php //***************************************费率设置************************************************************?>
<fieldset><legend> <?php echo __('BillingSettings')?></legend>
<table class="form">
<tr>
    <td>每分钟费�?</td>
    <td>
    <?php echo $form->input('rate',array('label'=>false,'div'=>false,'type'=>'text'))?>
    </td>
</tr>

<tr>
    <td>通话费用:</td>
    <td>
    <?php echo $form->input('setup_fee',array('label'=>false,'div'=>false,'type'=>'text'))?>
    </td>
</tr>
<tr>
    <td>首次时长:</td>
    <td>
 <?php echo $form->input('min_time',array('label'=>false,'div'=>false,'type'=>'text'))?>
    
    </td>

</tr>

<tr>
    <td>免费时长:</td>
    <td>
 <?php echo $form->input('grace_time',array('label'=>false,'div'=>false,'type'=>'text'))?>
    </td>

</tr>

<tr>
    <td>计费周期:</td>
    <td>
 <?php echo $form->input('interval',array('label'=>false,'div'=>false,'type'=>'text'))?>
    </td>

</tr>


<tr>
    <td>1分钟多少�?</td>
    <td>
 <?php echo $form->input('seconds',array('label'=>false,'div'=>false,'type'=>'text'))?>
    </td>

</tr>

<tr>
    <td>月费:</td>
    <td>
 <?php echo $form->input('month_fee',array('label'=>false,'div'=>false,'type'=>'text'))?>
    </td>

</tr>

<tr>
    <td>启动费用:</td>
    <td>
 <?php echo $form->input('active_fee',array('label'=>false,'div'=>false,'type'=>'text'))?>
    </td>

</tr>

</table>
</fieldset>








<!-- / COLUMN 2 --></td><td><!-- COLUMN 3 -->




<?php //************************client panel**********************************?>




<!-- / COLUMN 3 -->
</td></tr></table>

<div id="form_footer">
            <input type="submit" value="<?php echo __('submit')?>" />

    <input type="button"  value="<?php echo __('cancel')?>" />
    <!--  <input type="button" value="<?php echo __('apply')?>" />-->
    </div>
		<?php echo $form->end();?>



</div>