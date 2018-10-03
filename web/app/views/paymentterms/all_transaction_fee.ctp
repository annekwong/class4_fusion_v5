<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Exchange Manage') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('All Transaction Fee') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php ('All Transaction Fee')?></h4>
    <div class="buttons pull-right">

        <?php echo $this->element("createnew",Array('url'=>'paymentterms/add_transaction'))?>
        

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        
<div class="filter-bar">

                <form method="get">
                    <!-- Filter -->
                    <div>
                        <label><?php __('Search')?>:</label>
                        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch') ?>" value="<?php if (!empty($search)) echo $search; ?>" name="search">
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    
                    <!-- // Filter END -->

                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->

                    
                </form>
            </div>
            </div>


<div class="widget-body">


<div id="container">
     <?php
        $data = $p->getDataArray();
    ?>
    <div class="separator bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div> 
                </div>
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
<!--                <th>id</th>-->
                <th><?php __('Name')?></th>
                <th ><?php __('Is Default')?></th>
                <th><?php __('Action')?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $item): ?>
            <tr>
<!--                <td><?php echo $item[0]['id']?></td>-->
                <td><?php echo $item[0]['name']?></td>
                <td ><?php
                        if($item[0]['is_default']){
                            ?>
                    <a><i title="<?php __('default transaction fee')?>"  class="icon-check"></i></a>
                    <?php
                        }else{
                    ?>
                    <a><i title="not default transaction fee"  class="icon-unchecked"></i></a>
                    <?php
                        }
                    ?>
                </td>
                <td>
                    <a href="<?php echo $this->webroot;?>paymentterms/view_finance_fee_item/<?php echo $item[0]['id']; ?>">
                        <?php __('Finance Fee')?>
                    </a>
                    |
                    <a href="<?php echo $this->webroot;?>paymentterms/view_transaction_fee_item/<?php echo $item[0]['id']; ?>">
                        <?php __('Transaction Fee')?>
                    </a>
                    
                    <a title="<?php __('Edit')?>" href="<?php echo $this->webroot;?>paymentterms/edit_transaction/<?php echo $item[0]['id']; ?>">
                        <i class="icon-edit"></i>
                    </a>
                    <a title="<?php __('Delete')?>" href="javascript:void(0);" onclick="del(<?php echo $item[0]['id']?>)">
                        <i class="icon-remove"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="separator bottom row-fluid">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div> 
                </div>
    <?php }?>
</div>

<script>
    function del(id){
        if(confirm('Are you sure to delete?')){
            location = "<?php echo $this->webroot?>paymentterms/delete_transaction/"+id;
        }
    }
    
</script>


