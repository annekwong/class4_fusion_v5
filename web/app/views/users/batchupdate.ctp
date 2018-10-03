
    <script type="text/javascript">
    //<![CDATA[
    var currentTime = 1278411630; 
    var L = {"loadingPanel":"Please Wait...","deleteConfirm":"Are you sure to delete this item?","hide-all":"hide all"};
    //]]>
    </script>
<?php //********************************菜单*********************************8?>
	<div id="title">
    <h1><?php echo __('Batchupdateuser')?></h1>
    <ul id="title-menu">
    		<li>
    			<a class="link_back" href="<?php echo $this->webroot?>users/view">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png"/>
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
  		</ul>
	</div>



<?php //****************************************************页面主体?>


<div class="container">
	<?php echo $form->create ('User', array ('action' => 'batchupdate'));?>

<table class="cols">
<col width="50%"/>
<col width="50%"/>
<tbody><tr>

<td class="last">
    <!-- COLUMN 2 -->
    <table class="form">
    <tbody>
    
    <tr>
        <td class="label label2"><?php echo __('usingreseller')?>:</td>
        <td class="value value2">
       		<?php echo $form->input('old_reseller_id',
 		array('options'=>$reseller,'label'=>false ,'div'=>false,'type'=>'select','style'=>'height: 40px;','class'=>'input in-select'));?>
        </td>
    </tr>

    




    <tr>
        <td valign="top" class="label label2"><?php echo __('batchupdateuserreseller')?>:</td>
        <td class="value value2">
        
         		<?php echo $form->input('new_reseller_id',
 		array('options'=>$reseller,'label'=>false ,'div'=>false,'type'=>'select','style'=>'height: 40px;','class'=>'input in-select'));?>

             </td>
    </tr>
    </tbody></table>
    <!-- / COLUMN 2 -->
</td>
</tr></tbody></table>

<div id="form_footer">
            <input type="submit" value="<?php echo __('submit')?>" />

  
    </div>
		<?php echo $form->end();?>

</div>