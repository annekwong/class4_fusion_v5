


<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('LCR Report') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('LCR Report')?></h4>
    <div class="buttons pull-right">
        <?php if ($_SESSION['role_menu']['Statistics']['lcrreports']['model_w'])
        { ?>
            <a id="add" class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot ?>lcrreports/add"><i></i> <?php __('Create New')?></a>

<?php } ?>
    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">

            <div id="container">
                <?php
                if (empty($this->data)):
                    ?>
                    <div class="msg"><?php echo __('no_data_found', true); ?></div>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" style="display:none;">
                        <thead>
                            <tr>
                                <th><?php __('Time')?></th>
                                <th><?php __('Type')?></th>
                                <th><?php __('Rate Table')?></th>
                                <th><?php __('Status')?></th>
                                <th><?php __('Action')?></th>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
<?php else: ?>
                    <div class="separator bottom row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
    <?php echo $this->element('xpage'); ?>
                        </div> 
                    </div>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                        <thead>
                            <tr>
                                <th><?php __('Time')?></th>
                                <th><?php __('Type')?></th>
                                <th><?php __('Rate Table')?></th>
                                <th><?php __('Status')?></th>
                                <th><?php __('Action')?></th>
                            </tr>
                        </thead>

                        <tbody>
    <?php foreach ($this->data as $item): ?>
                                <tr>
                                    <td><?php echo $item['LcrRecord']['time'] ?></td>
                                    <td><?php echo $item['LcrRecord']['type'] ?></td>
                                    <td>
                                        <?php
                                        $rate_table_names = array();

                                        $rate_tables = explode(',', $item['LcrRecord']['rate_tables']);

                                        foreach ($rate_tables as $rate_table):

                                            array_push($rate_table_names, $common->get_rate_table($rate_table));

                                        endforeach;

                                        $rate_table_str = implode(',', $rate_table_names);
                                        ?>
                                        <a href="###" class="showdetail" detail="<?php echo $rate_table_str ?>">
        <?php echo substr($rate_table_str, 0, 20); ?>
                                        </a>
                                    </td>
                                    <td><?php echo $item['LcrRecord']['status'] == 0 ? 'Progress' : 'Done' ?></td>
                                    <td>
        <?php if ($item['LcrRecord']['status'] == 1): ?>
                                            <a href="<?php echo $this->webroot ?>lcrreports/get_file/<?php echo $item['LcrRecord']['id'] ?>" class="download" title="<?php __('Download')?>">
                                                <i class="icon-download-alt"></i>
                                            </a>
                                        <?php endif; ?>
        <?php if ($_SESSION['role_menu']['Statistics']['lcrreports']['model_w']): ?>
                                            <a href="<?php echo $this->webroot ?>lcrreports/delete/<?php echo $item['LcrRecord']['id'] ?>" class="delete" title="<?php __('Delete')?>">
                                                <i class="icon-remove"></i>
                                            </a>
        <?php endif; ?>
                                    </td>
                                </tr>
    <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="separator bottom row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
    <?php echo $this->element('xpage'); ?>
                        </div> 
                    </div>
<?php endif; ?>
            </div>

            <div id="pop-div" class="pop-div" style="text-align: center;width: 400px;  margin-top: 0px;display:none;word-wrap: break-word; ">
                
            </div>

            <script>
                $(function() {
                    $('.showdetail').click(function() {
                        $('#pop-div').show();
                        var obj = $(this).attr('detail');
                        var newobj = obj.replace(",","<br />");
                        $('#pop-div').html(newobj);
                        $("#pop-div").dialog({
                            'title': 'Rate Table Detail',
                            'width': '400px',
                            
                            'buttons': [{text: "Cancel", "class": "btn btn-inverse", click: function() {
                                        $(this).dialog("close");
                                    }}]

                        });
                    });

                    
                });
            </script>
