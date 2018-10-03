<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Exchange Manage') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Transaction Fee Item') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Transaction Fee Item') ?></h4>
    <div class="buttons pull-right">

        <?php echo $this->element("createnew",Array('url'=>'paymentterms/add_transaction_item/'.$id))?>
        

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">


<div class="widget-body">

<div id="container">
    
    <?php
        if(count($data) == 0){
    ?>
        <div class="msg"><?php echo __('no_data_found')?></div>
    <?php    
        }else{
    ?>
    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
        <thead>
            <tr>
                <th><?php __('Min Rate')?></th>
                <th><?php __('Max Rate')?></th>
                <th><?php __('Use Fee(%)')?></th>
                <th><?php __('Action')?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
                <td><?php echo $item[0]['min_rate']?></td>
                <td><?php echo $item[0]['max_rate']?></td>
                <td ><?php echo $item[0]['use_fee']?></td>
                <td>
                    <a title="Edit" href="<?php echo $this->webroot;?>paymentterms/edit_transaction_item/<?php echo $item[0]['transaction_item_id']; ?>">
                        <i class="icon-edit"></i>
                    </a>
                    <a title="Delete" href="javascript:void(0);" onclick="del(<?php echo $item[0]['transaction_item_id']?>)">
                        
                        <i class="icon-remove"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
        
    <?php }?>
<!--        <div class="separator bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php// echo $this->element('page'); ?>
                    </div> 
                </div>-->
</div>

<script>
    function del(id){
        if(confirm('Are you sure to delete?')){
            location = "<?php echo $this->webroot?>paymentterms/delete_transaction_item/"+id+"/"+<?php echo $id;?>;
        }
    }
</script>


