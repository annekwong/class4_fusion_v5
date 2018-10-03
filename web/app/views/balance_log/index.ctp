<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>balance_log"><?php __('Log') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>balance_log"><?php __('Balance Log')?></a></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php __('Balance Log')?></h4>
   
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>



<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <div class="filter-bar">
            <form method="get">
                <!-- Filter -->
                <div>
                    <label><?php __('Client')?>:</label>
                    <select name="client_id">
                        <option></option>
                        <?php foreach ($clients as $key => $value): ?>
                        <option value="<?php echo $key ?>" <?php echo isset($_GET['client_id']) && $_GET['client_id'] == $key ? 'selected="selected"':''; ?>><?php echo $value ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- // Filter END -->
                <!-- Filter -->
                <div>
                    <label><?php __('Start Date')?>:</label>
                    <input type="text" name="start_date" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});" readonly="" value="<?php echo isset($_GET['start_date']) ? $_GET['start_date']:''; ?>" class="input in-text wdate " id="start_date">
                </div>

                <div>
                    <label><?php __('End Date')?>:</label>
                    <input type="text" name="end_date" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});" readonly="" value="<?php echo isset($_GET['end_date']) ? $_GET['end_date']:''; ?>" class="input in-text wdate " id="end_date">
                </div>
                <div>
                    <button class="btn query_btn" name="submit"><?php __('Query')?></button>
                </div>
                <!-- // Filter END -->

            </form>
        </div>

            <div class="clearfix"></div>
             <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                 <thead>
                     <tr>
                         <th><?php echo $appCommon->show_order('date', __('Date', true)); ?></th>
                         <th><?php echo $appCommon->show_order('name', __('Client',true)); ?></th>
                         <th><?php echo $appCommon->show_order('balance', __('Balance', true)); ?></th>
                         <th><?php __('Action')?></th>
                     </tr>
                 </thead>
                 <tbody>
                            <?php
                                        $count = count($this->data);
                                        for ($i = 0; $i < $count; $i++):
                            ?>
                            <tr>
                                <td><?php echo $this->data[$i][0]['date'] ?></td>
                                <td><?php echo $this->data[$i]['Client']['name'] ?></td>
                                <td><?php echo $this->data[$i][0]['balance'] ?></td>
                                <td>
                                    <?php if($_SESSION['role_menu']['Payment_Invoice']['reset_balance'] == 1): ?>
                                    <a  class="synchronize" control="<?php echo $this->data[$i][0]['id'] ?>" href="###" title="<?php __('Synchronize with Actual Balance')?>">
                                        <i class="icon-refresh"></i>
                                    </a>
                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endfor; ?>
                 </tbody>
             </table>
            <div class="row-fluid separator">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('xpage'); ?>
                </div> 
            </div>
            <div class="clearfix"></div>
        </div>
        
    </div>
    
</div>


<div id="dd"> </div> 
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot?>easyui/themes/icon.css">
<script type="text/javascript" src="<?php echo $this->webroot?>easyui/jquery.easyui.min.js"></script>

<script>
    var $dd = $('#dd');
    var $synchronize = $('.synchronize');
    
    
    $(function() {
        
        $synchronize.click(function() {
            var $this = $(this);
            var control = $this.attr('control');
            
            $dd.dialogui({  
                title: 'Synchronize',  
                width: 300,  
                height: 200,  
                closed: false,  
                cache: false,  
                resizable: true,
                href: '<?php echo $this->webroot?>balance_log/reset/' + control,  
                modal: true,                
                buttons:[{
                        text:'Save',
                        handler:function(){
                            $('#synchronize_form').submit();
                        }
                },{
                        text:'Close',
                        handler:function(){
                            $dd.dialogui('close');
                        }
                }]
            });

            $dd.dialogui('refresh', '<?php echo $this->webroot?>balance_log/reset/' + control);  
        });
        
    });
</script>
