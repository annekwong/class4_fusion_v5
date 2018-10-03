
    <script type="text/javascript">
    //<![CDATA[
    var currentTime = 1278411630;
    var L = {"loadingPanel":"Please Wait...","deleteConfirm":"Are you sure to delete this item?","hide-all":"hide all"};
    //]]>
    </script>
<?php //********************************菜单*********************************8?>
	<div id="title">
    <h1><?php echo __('Batchupdaterole')?></h1>
    <ul id="title-menu">
    		<li>
    			<a class="link_back" href="<?php echo $this->webroot?>roles/view">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png"/>
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
  		</ul>
	</div>



<?php //****************************************************页面主体?>


<div class="container">
	<?php echo $form->create ('Role', array ('action' => 'batchupdate'));?>

<table class="cols">
<col width="50%"/>
<col width="50%"/>
<tbody><tr>

<td class="last">
    <!-- COLUMN 2 -->
    <table class="form">
    <tbody>
    
    <tr>
        <td class="label label2"><?php echo __('usingrole')?>:</td>
        <td class="value value2">
       		<?php echo $form->input('old_role_id',
 		array('options'=>$role,'label'=>false ,'div'=>false,'type'=>'select','style'=>'height: 40px;','class'=>'input in-select'));?>
        </td>
    </tr>

    




    <tr>
        <td valign="top" class="label label2"><?php echo __('batchupdatenewrole')?>:</td>
        <td class="value value2">
        
         		<?php echo $form->input('new_role_id',
 		array('options'=>$role,'label'=>false ,'div'=>false,'type'=>'select','style'=>'height: 40px;','class'=>'input in-select'));?>

             </td>
    </tr>
    </tbody></table>
    <!-- / COLUMN 2 -->
</td>
</tr></tbody></table>

<div id="footer">
            <input type="submit" value="<?php echo __('submit')?>" />

  
    </div>
		<?php echo $form->end();?>

<script type="text/javascript">
//&lt;![CDATA

function checkAllowedIps()
{
    if ($('#allowed_ips').val() != '') {
        $('#allowed_ips_note').show();
    } else {
        $('#allowed_ips_note').hide();
    }
}
checkAllowedIps();

//]]&gt;
</script></div>