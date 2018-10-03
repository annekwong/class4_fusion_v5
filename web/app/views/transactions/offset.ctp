<?php echo $this->element('magic_css_three');?>
<div id="title">
  <h1>
    <?php  __('Finance')?>
    &gt;&gt;
    <?php __('Offset')?>
  </h1>
  <ul id="title-menu">
    <?php  if ($_SESSION['role_menu']['Finance']['resclis']['model_w']) {?>
    <li> <a class="link_btn" id="add" href="<?php echo $this->webroot; ?>transactions/create_offset" > <img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/add.png">&nbsp;
      <?php __('createnew')?>
      </a> </li>
    <?php }?>
  </ul>
  <?php  $action=isset($_SESSION['sst_statis_smslog'])?$_SESSION['sst_statis_smslog']:'';
    $w=isset($action['writable'])?$action['writable']:'';?>
</div>

<div id="container">
 <ul class="tabs">
        <li>
            <a href="<?php echo $this->webroot; ?>transactions/payment/incoming">
                <img width="16" height="16" src="<?php echo $this->webroot; ?>images/list.png"><?php echo __('Incoming',true);?>				
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot; ?>transactions/payment/outgoing">
                 <img width="16" height="16" src="<?php echo $this->webroot; ?>images/menuIcon.gif"><?php echo __('Outgoing',true);?>			
            </a>  
        </li>
        <li class="active">
            <a href="<?php echo $this->webroot; ?>transactions/offset">
                 <img width="16" height="16" src="<?php echo $this->webroot; ?>images/offset_diff.png"><?php echo __('Offset',true);?>			
            </a>  
        </li>
 </ul>
    <?php $offsets = $p->getDataArray(); ?>
    <div id="toppage"></div>
    <table class="list">
        <thead>
            <tr>
                <td><?php echo __('Entered Date',true);?></td>
                <td><?php echo __('Carrier',true);?></td>
                <td><?php echo __('Amount',true);?></td>
                <td><?php echo __('Create By',true);?></td>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($offsets as $offset): ?>
            <tr>
                <td><?php echo $offset[0]['payment_time']; ?></td>
                <td><?php echo $offset[0]['client']; ?></td>
                <td><?php echo $offset[0]['amount']; ?></td>
                <td><?php echo $offset[0]['update_by']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="tmppage"> <?php echo $this->element('page');?> </div>
    <br />
    <fieldset style=" clear:both;overflow:hidden;margin-top:10px;" class="query-box">
        <div class="search_title">
          <img src="<?php echo $this->webroot; ?>images/search_title_icon.png">
          <?php echo __('Search',true);?>  
        </div>
        <div style="margin:0px auto; text-align:center;">
        <form name="myform" method="get">
            <?php echo __('carrier',true);?>:
            <select name="client_id">
                <option value=""><?php __('All')?></option>
                <?php foreach($clients as $client): ?>
                <option value="<?php echo $client[0]['client_id'] ?>"><?php echo $client[0]['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="<?php echo __('submit',true);?>" />
        </form>
        </div>
   </fieldset>
</div>