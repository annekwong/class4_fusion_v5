<style type="text/css">
    .width125{
        width: 125px;
    }
</style>
<?php
$width_class = 'width125';
if(isset($has_trunk)){
    $width_class = '';
}
?>

<?php echo $form->create('CodeManagement', array('id' =>'CodeManagement','type' => 'post','url' => '/'.$this->params['url']['url'])); ?>
<table class="table tableTools table-bordered  table-white" >
    <tr>
        <colgroup>
            <col width="40%">
            <col width="60%">
        </colgroup>
    <tr>
        <td class="align_right padding-r10"><?php __('Product') ?></td>
        <td>
            <?php echo $form->input('product',array('type' =>'select','options'=>$product_list,'label' =>false,'div'=>false,
            )); ?>
        </td>
    </tr>
    <tr>
        <td class="align_right padding-r10"><?php __('Routing Plan') ?></td>
        <td>
            <?php echo $form->input('route_plan',array('type' => 'select','label' => false,'div' => false,'options'=>$route_plan)); ?>
        </td>
    </tr>
    <tr>
        <td class="align_right padding-r10"><?php __('Rate Table') ?></td>
        <td>
            <?php echo $form->input('rate_table',array('type' => 'select','label' => false,'div' => false,'options'=>$rate_table)); ?>
        </td>
    </tr>
    <?php if(!strcmp('no_route',$this->action)): ?>
        <tr>
            <td class="align_right padding-r10"><?php __('Condition') ?></td>
            <td>
                <?php __('Get all codes with'); ?><=
                <?php echo $form->input('condition',array('type' => 'select','label' => false,'div' => false,
                    'options'=>array(0,1,2,3,4,5),'class' => 'width80')); ?>
                <?php __('vendors'); ?>
            </td>
        </tr>
    <?php elseif(!strcmp('low_asr',$this->action)): ?>
        <tr>
            <td class="align_right padding-r10"><?php __('Condition') ?></td>
            <td>
                <?php __('Get all codes with'); ?><=
                %
                <?php __('ASR'); ?> <?php __('for last'); ?>
                <?php echo $form->input('condition',array('type' => 'select','label' => false,'div' => false,
                    'options'=>array(1=>1,2=>2,3=>3,4=>4,5=>5),'class' => 'width80')); ?>
            </td>
        </tr>
    <?php elseif(!strcmp('low_asr',$this->action)): ?>
    <?php else: ?>
    <?php endif; ?>
    <tr>
        <td colspan="2" class="center">
            <input type="submit" value="Search" class="btn btn-primary margin-bottom10">
        </td>
    </tr>
</table>
<?php echo $form->end(); ?>

<script type="text/javascript">
    $(function(){
        $.ajaxSettings.async = false;
        //product 过来
        $.getJSON('<?php echo $this->webroot ?>clients/ajax_procuct_list', function(data){product_arr = data;});
        $('#CodeManagementProduct').live('change',function(){
            var product_id = $(this).val();
            if(product_id != 0){
                $('#CodeManagementRoutePlan').val(product_arr[product_id]['route_strategy_id']).attr('disabled',true);
                $('#CodeManagementRateTable').val(product_arr[product_id]['rate_table_id']).attr('disabled',true);
            } else {
                $('#CodeManagementRoutePlan').attr('disabled',false);
                $('#CodeManagementRateTable').attr('disabled',false);
            }
        }).trigger('change');

        $("#CodeManagement").submit(function(){
            $('#CodeManagementRoutePlan').attr('disabled',false);
            $('#CodeManagementRateTable').attr('disabled',false);
        });
    })
</script>