<script src="<?php echo $this->webroot ?>js/ajaxTable.js" type="text/javascript"></script>

<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>did/clients/index"><?php __('Origination') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>did/clients/index"><?php echo __('Clients') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Clients') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot ?>did/clients/add"><i></i>
        <?php __('Create New') ?>
    </a>
</div>
<div class="buttons pull-right" style="margin:0 10px 0 0">
    <a rel="popup" id="delete_selected" class="link_btn btn btn-primary btn-icon glyphicons remove" href="javascript:void(0)">
        <i></i><?php __('Delete Selected') ?>
    </a>
</div>
<div class="clearfix"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <div class="filter-bar">
                <form method="get">
                    <!-- Filter -->

                    <!-- Filter -->
                    <div>
                        <label><?php __('Name')?>:</label>
                        <input type="text" name="search" id="search-_q" />
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                    </div>
                    <!-- // Filter END -->
                </form>
            </div>
            <div class="clearfix"></div>
            <div id="container">
                <?php
                if (empty($this->data)):
                    ?>
                <div class="msg center"><br /><h2><?php echo __('no_data_found', true); ?></h2></div>
                <?php else: ?>

                    <table class="footable table table-striped tableTools table-bordered  table-white table-primary table_page_num" id="key_list" >
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th><?php __('View IP') ?></th>
                                <th><?php __('Name') ?></th>
                                <th><?php __('Balance') ?></th>
                                <th><?php __('Allowed credit') ?></th>
                                <!--th><?php __(' Price Per Actual Channel') ?></th-->
                                <th><?php __(' Price Per Max Channel') ?></th>
                                <!--th><?php __('Billing Rule') ?></th-->
                                <th><?php __('Update At') ?></th>
                                <th><?php __('Update By') ?></th>
                                <th><?php __('Action') ?></th>
                            </tr>
                        </thead>


                        <?php
                        $count = count($this->data);
                        for ($i = 0; $i < $count; $i++):
                            ?>
                            <tbody id="resInfo<?php echo $i ?>">
                                <tr class="row-<?php echo $i % 2 + 1; ?>">
                                    <td>
                                        <input type="checkbox" class="multi_select" value="<?php echo $this->data[$i]['Resource']['resource_id']; ?>">
                                    </td>
                                    <td>
                                        <img id="image<?php echo $i; ?>"  onclick="pull('<?php echo $this->webroot ?>', this,<?php echo $i; ?>)"  class="jsp_resourceNew_style_1"  src="<?php echo $this->webroot ?>images/+.gif" title="<?php __('View All') ?>"/>
                                    </td>
                                    <td>
                                        <a href="<?php echo $this->webroot ?>did/clients/edit/<?php echo base64_encode($this->data[$i]['Client']['client_id']) ?>" title="<?php __('Edit') ?>">
                                            <?php echo $this->data[$i]['Client']['name'] ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php echo $this->data[$i]['Balance']['balance'] < 0 ? '(' . str_replace('-', '', number_format($this->data[$i]['Balance']['balance'], 3)) . ')' : number_format($this->data[$i]['Balance']['balance'], 3); ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (isset($this->data[$i]['Client']['mode']) && $this->data[$i]['Client']['mode'] == 1) {
                                            echo "--";
                                        } elseif (isset($this->data[$i]['Client']['unlimited_credit']) && $this->data[$i]['Client']['unlimited_credit']) {
                                            __('Unlimited');
                                        } else {
                                            echo number_format(abs($this->data[$i]['Client']['allowed_credit']), 5);
                                        }
                                        ?>
                                    </td>
                                    <!--td><?php echo isset($this->data[$i]['Resource'][0]['price_per_actual_channel']) ? number_format($this->data[$i]['Resource'][0]['price_per_actual_channel'], 3) : "0.000"; ?></td-->
                                    <td><?php echo isset($this->data[$i]['Resource'][0]['price_per_max_channel']) ? number_format($this->data[$i]['Resource'][0]['price_per_max_channel'], 3) : "0.000"; ?></td>
                                    <!--td><?php echo isset($routing_rules[$this->data[$i]['Resource']['billing_rule']]) ? $routing_rules[$this->data[$i]['Resource']['billing_rule']] : ""; ?></td-->
                                    <td><?php echo $this->data[$i]['Client']['update_at'] ?></td>
                                    <td><?php echo $this->data[$i]['Client']['update_by'] ?></td>
                                    <td>
                                        <a href="<?php echo $this->webroot ?>did/clients/edit/<?php echo base64_encode($this->data[$i]['Client']['client_id']) ?>" title="<?php __('Edit') ?>"> 
                                            <i class="icon-edit"></i> 
                                        </a>

                                        <a title="<?php __('delete'); ?>" onclick="return myconfirm('<?php __('sure to delete'); ?>', this);" href="<?php echo $this->webroot ?>did/clients/delete/<?php echo base64_encode($this->data[$i]['Client']['client_id']) ?>">
                                            <i class="icon-remove"></i>
                                        </a>
                                        <?php
                                        if ($this->data[$i]['Client']['status'] == 1)
                                        {
                                            ?>
                                            <a  title=" <?php __('Click to inactive'); ?>" onclick="return myconfirm('Are you sure to inactive the selected <?php echo $this->data[$i]['Client']['name'] ?>?', this)"   href="<?php echo $this->webroot ?>did/clients/disable/<?php echo base64_encode($this->data[$i]['Client']['client_id']) ?>" > <i class="icon-check"></i> </a>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <a title=" <?php __('Click to active'); ?>" onclick="return myconfirm('Are you sure to active the selected <?php echo $this->data[$i]['Client']['name'] ?>?', this)" href="<?php echo $this->webroot ?>did/clients/enable/<?php echo base64_encode($this->data[$i]['Client']['client_id']) ?>"  > <i class="icon-unchecked"></i> </a>
                                        <?php } ?>

                                        <a href="<?php echo $this->webroot ?>did/repository?client_id=<?php echo $this->data[$i]['Client']['client_id'] ?>" title="<?php __('View DID') ?>">
                                            <i class="icon-list-alt"></i> 
                                        </a>
                                    </td>
                                </tr>
                                <tr style="height:auto">
                                    <td colspan="10">
                                        <div id="ipInfo<?php echo $i ?>" class=" jsp_resourceNew_style_2" style="padding:5px;display: none;"> 
                                            <table>
                                                <tr>
                                                    <td><?php __('IP') ?></td>
                                                </tr>
                                                <?php foreach ($this->data[$i]['ResourceIps'] as $ip): ?>
                                                    <tr>
                                                        <td><?php echo $ip; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        <?php endfor; ?>

                    </table>
                    <div class="separator row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('xpage'); ?>
                        </div> 
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        var $selectAll = $('#selectAll');
        var $multi_select = $('.multi_select');
        var $delete_selected = $('#delete_selected');

        $selectAll.change(function() {
            $multi_select.attr('checked', $(this).is(':checked'));
        });

        $delete_selected.click(function() {
            var selected = new Array();
            $multi_select.each(function() {
                var $this = $(this);
                if ($this.is(':checked')) {
                    selected.push($this.val());
                }
            });
            if (!selected.length) {
                jGrowl_to_notyfy("You did not select any item!", {theme: 'jmsg-error'});
            }
            else
            {
                bootbox.confirm("Are you sure to delete selected clients?", function (result) {
                    if (result == true) {
                        $.ajax({
                            'url': '<?php echo $this->webroot; ?>did/clients/deleteSelected',
                            'type': 'POST',
                            'dataType': 'json',
                            'data': {
                                'selected[]': selected
                            },
                            'success': function (data) {
                                if (data.status == 1) {
                                    jGrowl_to_notyfy("The numbers you selected is deleted successfully!", {theme: 'jmsg-success'});
                                    $multi_select.each(function () {
                                        var $this = $(this);
                                        if ($this.is(':checked')) {
                                            $this.closest('tr').remove();
                                        }
                                    });
                                } else {
                                    jGrowl_to_notyfy("The numbers you selected is deleted failed!", {theme: 'jmsg-error'});
                                }
//                                window.setTimeout("window.location.reload();", 3000);
                            }
                        });
                    }
                });
            }
        });
    });
</script>
