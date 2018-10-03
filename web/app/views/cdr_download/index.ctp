<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('CDR Download') ?></li>

</ul>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <form name="myform" method="post">
                <table class="form table  tableTools table-bordered  table-condensed">
                    <colgroup>
                        <col width="40%">
                        <col width="60%">
                    </colgroup>
                    <tr>
                        <td class="align_right"><?php __('Carrier Type'); ?> </td>
                        <td>
                            <select class="carrier_type" name="carrier_type" >
                                <option value="1" ><?php __('Ingress'); ?></option>
                                <option value="2" ><?php __('Egress'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr class="ingress_carrier">
                        <td class="align_right"><?php __('Ingress Carrier'); ?> </td>
                        <td>
                            <?php echo $form->input('ingress_carrier',array('type'=> 'select','options' => $ingress_carrier,'label'=>false,'div'=>false)); ?>
                        </td>
                    </tr>
                    <tr class="hide egress_carrier">
                        <td class="align_right"><?php __('Egress Carrier'); ?> </td>
                        <td>
                            <?php echo $form->input('egress_carrier',array('type'=> 'select','options' => $egress_carrier,'label'=>false,'div'=>false)); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="align_right"><?php __('Period'); ?></td>
                        <td>
                            <input type="text" class="wdate validate[required]" id="d4311" name="start" readonly="readonly"  onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:00:00',maxDate:'#F{$dp.$D(\'d4312\')||\'<?php echo date('Y-m-d 00:00:00',strtotime("-1 month")); ?>\'}'});" />
                            ~
                            <input type="text"  class="wdate validate[required]" id="d4312" name="end" readonly="readonly"  onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:59:59',minDate:'#F{$dp.$D(\'d4311\')}',maxDate:'<?php echo date('Y-m-d 23:59:59',strtotime("-1 month")); ?>'});"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="buttons-group center" colspan="2">
                            <input type="submit" value="<?php __('Download')  ?>" id="download" class="btn btn-primary" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<script  type="text/javascript">
    $(function(){
        $(".carrier_type").change(function(){
            var carrier_type = $(this).val();
            if (carrier_type == 1){
                $(".ingress_carrier").show();
                $(".egress_carrier").hide();
            }
            else{
                $(".ingress_carrier").hide();
                $(".egress_carrier").show();
            }
        });

    });
</script>