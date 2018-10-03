
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>did/orig_invoice/view">
    <?php if($_SESSION['login_type'] == 3):?>
        <?php __('Client Portal') ?>
    <?php else:?>
        <?php __('DID') ?></a></li>
    <?php endif;?>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>did/orig_invoice/view">
        <?php echo __('Origination Invoice', true); ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Origination Invoice', true); ?></h4>
</div>
<div class="clearfix"></div>
<div class="separator bottom"></div>
<div class="buttons pull-right" style="padding:0 15px 10px 0;">
     <?php
        if (isset($_SESSION['role_menu']['Finance']['pr_invoices']['model_w']) && $_SESSION['role_menu']['Finance']['pr_invoices']['model_w'])
        {
            ?>
            <?php
            if ($create_type == '1')
            {
                ?>
                <a class="link_btn btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot ?>did/orig_invoice/add/<?php echo $create_type; ?>"><i></i><?php __('Create New')?> </a>
                <?php
            }
        }
     ?>
</div>
<div class="clearfix"></div>
<div class="separator bottom"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <?php if ($_SESSION['login_type'] == '1'):?>
        <div class="widget-head">
            <ul>
                <li <?php
                if ($create_type == '0')
                {
                    echo "class='active'";
                }
                ?> ><a class="glyphicons no-js left_arrow" href="<?php echo $this->webroot ?>did/orig_invoice/view/0"><i></i><?php echo __('Auto Invoice') ?></a></li>
                <li <?php
                if ($create_type == '1')
                {
                    echo "class='active'";
                }
                ?> ><a class="glyphicons no-js right_arrow" href="<?php echo $this->webroot ?>did/orig_invoice/view/1"><i></i><?php echo __('Manual Invoice') ?></a></li>
            </ul>
        </div>
        <?php endif; ?>
        <div class="widget-body">
             <div class="filter-bar">
                  <form action="" method="get" id="search_panel"  >
                      <div>
                         <label><?php echo __('Carriers', true); ?></label>
                         <select name="query[client]" class="select2">
                             <option value=""><?php __('All')?></option>
                             <?php foreach ($clients as $client_id => $name): ?>
                                 <option <?php if (isset($_GET['query']['client']) && $_GET['query']['client'] == $client_id) echo 'selected="selected"'; ?> value="<?php echo $client_id ?>"><?php echo $name ?></option>
                             <?php endforeach; ?>
                         </select>
                     </div>
                     <div>
                         <label><?php echo __('Invoice Date', true); ?>:</label>
                         <input type="text" class="input in-text wdate" name="invoice_start" value="<?php echo date("Y-m-d 00:00:00"); ?>" id="start_date" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});" readonly="">
                         <input type="text" class="wdate input in-text" name="invoice_end"  value="<?php echo date("Y-m-d 23:59:59"); ?>" id="end_date" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});" readonly="">
                     </div>
                     <div>
                         <button  class="btn query_btn" id="search_button"><?php __('Query')?></button>
                     </div>
                  </form>
             </div>
             <div class="clearfix"></div>
             <table class="list footable table table-striped tableTools table-bordered  table-white table-primary table_page_num">
                 <thead>
                 <tr>
                      <th><?php __('Invoice Number') ?></th>
                      <th><?php __('Period') ?></th>
                      <th><?php __('Client Name') ?></th>
                      <th><?php __('Invoice Amount') ?></th>
                      <th class="last"><?php __('Action')?></th>
                 </tr>
                 </thead>
                 <tbody>

                 </tbody>
             </table>
        </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(){function(){


}};
</script >
