<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Configuration') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Usage Report Delivery') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Usage Report Delivery') ?></h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot; ?>reports/add_report_delivery"><i></i> <?php __('Create New')?></a>
    </div>
    <div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            
            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                    <tr>
                        <th><?php __('Type')?></th>
                        <th><?php __('Frequency')?></th>
                        <th><?php __('Carrier')?></th>
                        <th><?php __('Ingress Trunk')?></th>
                        <th><?php __('Egress Trunk')?></th>
                        <th><?php __('Time Bucket')?></th>
                        <th><?php __('Code Bucket')?></th>
                        <th><?php __('Skip Empty')?></th>
                        <th><?php __('Email To')?></th>
                        <th><?php __('Action')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($list as $value){ ?>
                    <tr>
                        <td><?php echo $value['UsageReportDelivery']['type']; ?></td>
                        <td><?php echo $value['UsageReportDelivery']['frequency']; ?></td>
                        <td>
                            <?php if(strcmp('All', $value['UsageReportDelivery']['carrier'])){ ?>
                            <a href="###" class="carrier" id="Carrier" hit="<?php echo $value['UsageReportDelivery']['id']; ?>"><?php echo $value['UsageReportDelivery']['carrier']; ?></a></td>
                            <?php }else{ ?>
                <a href="<?php echo $this->webroot; ?>clients/index">All</a>
                            <?php } ?>
                        <td>
                            <?php if(strcmp('Null', $value['UsageReportDelivery']['ingress'])){ ?>
                            <a href="###" class="carrier" id="Ingress" hit="<?php echo $value['UsageReportDelivery']['id']; ?>"><?php echo $value['UsageReportDelivery']['ingress']; ?></a>
                    <?php }else{ echo $value['UsageReportDelivery']['ingress'];} ?>
                        </td>
                        <td>
                            <?php if(strcmp('Null', $value['UsageReportDelivery']['egress'])){ ?>
                            <a href="###" class="carrier" id="Egress" hit="<?php echo $value['UsageReportDelivery']['id']; ?>"><?php echo $value['UsageReportDelivery']['egress']; ?></a>
                     <?php }else{ echo $value['UsageReportDelivery']['egress'];} ?>
                        </td>
                        <td><?php echo $value['UsageReportDelivery']['time_bucket']; ?></td>
                        <td><?php echo $value['UsageReportDelivery']['code_bucket']; ?></td>
                        <td><input type="checkbox" disabled="disabled" <?php if($value['UsageReportDelivery']['skip_empty']){ ?>checked="checked"<?php } ?> /></td>
                        <td><?php echo $value['UsageReportDelivery']['email']; ?></td>
                        <td style="display: table-cell;" >
                            <?php if($value['UsageReportDelivery']['action']){ ?>
                            <a title="<?php __('Stop')?>" href="<?php echo $this->webroot ?>reports/action_report_delivery/<?php echo base64_encode($value['UsageReportDelivery']['id']); ?>/0"><i class="icon-stop"></i></a>
                            <?php }else{ ?>
                            <a title="<?php __('Start')?>" href="<?php echo $this->webroot ?>reports/action_report_delivery/<?php echo base64_encode($value['UsageReportDelivery']['id']); ?>/1"><i class="icon-play-circle"></i></a>
                            <?php } ?>
                            <a title="<?php __('View History')?>" href="<?php echo $this->webroot ?>reports/history_report_delivery/<?php echo base64_encode($value['UsageReportDelivery']['id']); ?>"><i class="icon-list-alt"></i></a>
                            <a title="<?php __('Edit')?>" href="<?php echo $this->webroot ?>reports/modify_report_delivery/<?php echo base64_encode($value['UsageReportDelivery']['id']); ?>"><i class="icon-edit"></i></a>
                            <a title="<?php __('Delete')?>" onClick="return myconfirm('Are you sure to delete the item? ',this);" href="<?php echo $this->webroot ?>reports/delete_report_delivery/<?php echo base64_encode($value['UsageReportDelivery']['id']); ?>"><i class="icon-remove"></i></a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>



        <div class="clearfix"></div>
    </div>
</div>


<script type="text/javascript" >
    
    var $carrier = $(".carrier");
$carrier.click(function() {
        var $this = $(this);
        if (!$('#dd').length) {
            $(document.body).append("<div id='dd'></div>");
        }
        var $dd = $('#dd');
        var $form = null;
        $dd.load('<?php echo $this->webroot; ?>reports/show_detail/' + $this.attr('id') +'/'+ $this.attr('hit'), 
            {}, 
            function(responseText, textStatus, XMLHttpRequest) {
                $dd.dialog({
                    'width': '450px',
                    'create' : function(event, ui) {
                        $form = $('form', $dd);
                        $form.validationEngine();
                    }
                });
            }
        );
        
    });


</script>

