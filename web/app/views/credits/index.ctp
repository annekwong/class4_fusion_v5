<div id="title">
  <h1>
   <?php  __('Finance')?>
    &gt;&gt;
    <?php __('Credit Application')?>
  </h1>
  <ul id="title-search">
    <li>
      <?php //********************模糊搜索**************************?>
      <form   id="like_form"  action=""  method="get">
        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
      </form>
    </li>
    <li title="<?php echo __('advancedsearch')?> »" onClick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
  </ul>
  <ul id="title-menu">
    <?php if (isset($extraSearch)) {?>
    <li> <a class="link_back" href="<?php echo $extraSearch?>" onClick="history.go(-1)"> <img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png"> &nbsp;<?php echo __('goback')?> </a> </li>
    <?php }?>
  </ul>
  <?php  
	$action=isset($_SESSION['sst_statis_smslog'])?$_SESSION['sst_statis_smslog']:'';
 $w=isset($action['writable'])?$action['writable']:'';
 ?>
</div>
<div id="container">
  
 <!-- <div id="toppage"></div>-->
 <?php 		//$mydata =$p->getDataArray();
			//$loop = count($mydata); 
			//if(empty($mydata)){
			?>
<div class="msg"><?php echo __('no_data_found',true);?></div>
<?php //}else{

?>
  <table class="list">
      <thead>
        <tr>
          <td><?php echo __('Legal Name',true);?></td>
          <td><?php echo __('Registration number',true);?></td>
          <td><?php echo __('Established',true);?></td>
          <td><?php echo __('country',true);?></td>
          <td><?php echo __('gross_annual_revenue',true);?></td>
          <td><?php echo __('Principals',true);?></td>
          <td><?php echo __('Address',true);?></td>
          <td><?php echo __('Phone',true);?></td>
          <td><?php echo __('Email',true);?></td>
          
          <td><?php echo __('Company URL',true);?></td>
          <td><?php echo __('Annual Sales Volumes',true);?></td>
          <td><?php echo __('D&B',true);?> </td>
          <td><?php __('action')?></td>
        </tr>
      </thead>
      <tbody>
      <?php 
      //for ($i=0;$i<$loop;$i++){
      			?>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
         <td></td>
         <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td><a href="<?php echo $this->webroot?>finances/edit_credit/<?php //echo $mydata[$i][0]['id']; ?>"; 
        		title="<?php echo __('edit')?>"> <i class="icon-edit"></i></a></td>
        </tr>
        <?php //}?>
      </tbody>
    </table>
  <div id="tmppage"> 
  <?php //echo $this->element('page');?>
  </div>
  <?php //} ?>
</div>