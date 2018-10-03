<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="title">
            <h1>
      <?php echo __('onlineusermanager')?>     
      <!--  <a title="add to smartbar" href="<?php echo $this->webroot?>clients/view">
      <img width="10" height="10" alt="+" src="<?php echo $this->webroot?>images/qb-plus.png"></a>-->
                        </h1>
        

    
<ul id="title-search">
        <li>
        <?php //****************************模糊搜索**************************?>
        <form  action="<?php echo $this->webroot?>users/view"  method="post">
        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>..." 
        value="<?php if(isset($searchkey)){echo $searchkey;}else{ echo __('pleaseinputkey');}?>"  onclick="this.value=''" name="searchkey">
        </form>
        </li>
        
        <li title="<?php echo __('advancedsearch')?> »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
    </ul><!--
    
        <ul id="title-menu">
      <li><a   title="<?php echo __('Batchupdateusertitle')?>"   href="<?php echo $this->webroot?>users/batchupdate"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/list.png"> 
      <?php echo __('Batchupdateuser')?></a></li>
     
       <li><a  href="<?php echo $this->webroot?>users/add">
       <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createnew')?></a></li>
       <li><a href="<?php echo $this->webroot?>clients/view"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/list.png"> DL History</a></li>
       
        </ul>
        

    --></div>

<div id="container">

	<?php //*********************  条件********************************?>
<fieldset class="title-block" id="advsearch">
	<?php echo $form->create ('User', array ('action' => 'view' ,'onsubmit'=>""));?>
<table>
<tbody>

<tr>
    <td><label><?php echo __('username')?>:</label>
        
	<?php echo $form->input('name',array('label'=>false ,'div'=>false,'type'=>'text','class'=>'input in-text'));?>
		
    

    </td>
    <td><label><?php echo __('Reseller')?>:</label>
    
 		<?php echo $form->input('reseller_id',
 		array('options'=>$reseller,'empty'=>__('pleaseselectreseller',true),'label'=>false ,'div'=>false,'type'=>'select'));?>
    </td>
    <td><label><?php echo __('Role')?>:</label>
         		<?php echo $form->input('role_id',
 		array('options'=>$role,'empty'=>__('pleaseselrole',true),'label'=>false ,'div'=>false,'type'=>'select','class'=>'input in-select'));?>

    </td>
    <td><label><?php echo __('activestatus')?>:</label>
    
         		<?php
  $tmp=array('true'=>__('active',true),'false'=>__('noactive',true));
         		echo $form->input('active',
 		array('options'=>$tmp,'empty'=>__('pleaseactive',true),'label'=>false ,'div'=>false,'type'=>'select','class'=>'input in-select'));?>
    
    </td>
<td class="buttons"><input type="submit" class="input in-submit" value="Search"></td>
</tr>
</tbody></table>
<?php echo $form->end();?></fieldset>

	<?php //*********************查询条件********************************?>

		<div id="toppage"></div>
<table class="list">
<col width="6%">
<col width="18%">
<col width="12%">
<col width="8%">
<col width="8%">

<thead>
<tr>

 <td >
    &nbsp;<?php echo __('username')?></td>
     <td ><?php echo __('login_time')?></td>
    <td >&nbsp;<?php echo __('parentReseller')?>&nbsp;</td>
    <td >&nbsp;<?php echo __('usertype')?>&nbsp;</td>
  <td >&nbsp;代理商&nbsp;/&nbsp;客户&nbsp;/&nbsp;帐号卡&nbsp;</td>
    
  
</tr>
 
</thead>
<tbody>




	<?php 
					$mydata =$p->getDataArray();
					$loop = count($mydata); 
					for ($i=0;$i<$loop;$i++) {?>
<tr class="row-1">


    <td align="center">	<?php echo $mydata[$i][0]['user_name']?></td>
					
					    <td align="center">	<?php echo $mydata[$i][0]['login_time']?></td>


    <td align="center"><?php echo $mydata[$i][0]['reseller_name']?></td>
    <td><?php  
    if( $mydata[$i][0]['user_type']==1){echo __('systemadmin');  }
					 if( $mydata[$i][0]['user_type']==2){echo __('routseller');  }
					 if( $mydata[$i][0]['user_type']==3){echo __('client');  }
					 if( $mydata[$i][0]['user_type']==4){echo __('logincard');  }
					 if( $mydata[$i][0]['user_type']==5){echo __('userlevel2');  }
					 if( $mydata[$i][0]['user_type']==6){echo __('userlevel3');  }
    ?></td>


<td class="last"><?php echo $mydata[$i][0]['user_name']?> </td>
  


                
  

</tr>


	<?php }?>

</tbody><tbody>
</tbody></table>

<div id="tmppage">
<?php echo $this->element('page');?>


</div>

</div>
<div>

</div>

    