
    <link href="<?php echo $this->webroot?>images/favicon.ico" type="image/x-icon" rel="shortcut Icon">
<div id="title">
        <h1><?php echo __('editcomplain')?></h1>
        <ul id="title-menu">
    		<li>
    			<a class="link_back" href="<?php echo $this->webroot?>complains/view">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png"/>
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
  		</ul>
</div>
<div class="container">


	<?php echo $form->create ('Complain', array ('action' => 'edit' ));?>
	  <?php echo $form->input('complain_id',array('label'=>false,'div'=>false,'type'=>'hidden','value'=>$post['Complain']['complain_id']))?>
 <input type="hidden" value="<?php echo $post['Complain']['complain_id']?>" name="complain_id"/>


<table class="form"  >
<tbody>

<tr>
    <td class="label label2"><?php echo __('titile')?></td>
    <td class="value value2">
           		<?php echo $form->input('title',  
 		array('label'=>false ,'div'=>false,'type'=>'text', 'style'=>'float: left; width: 390px;',
 		'value'=>$post['Complain']['title'],
 		'disabled'=>true,

 		'class'=>'input in-text'));?>
    </td>
</tr>




<tr>
    <td class="label label2"><?php echo __('content')?></td>
    <td class="value value2">
           		<?php echo $form->input('content', 
 		array('label'=>false ,'div'=>false,'type'=>'textarea', 
 'value'=>$post['Complain']['content'],
 		'style'=>'float: left; width: 494px; height: 94px;','class'=>'input in-text'));?>
    </td>
</tr>




</tbody></table>

<div id="form_footer">
   <input type="submit" value="<?php echo __('submit')?>"  class="input in-submit">
</div>
</div>
