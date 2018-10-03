<style>
    .footable-first-column{
        text-align: center !important;
    }
    #selectAll{
        position: relative !important;
        left: 4px !important;
    }
</style>
<script src="<?php echo $this->webroot ?>js/ajaxTable.js" type="text/javascript"></script>

<?php
$mydata = $p->getDataArray();
$loop = count($mydata);
?>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>alerts/rules"><?php __('Monitoring') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>alerts/rules"><?php echo __('Rule') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Rule') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <?php
    if ($_SESSION['role_menu']['Monitoring']['alerts:rules']['model_w'])
    {
        ?>
        <a class="btn btn-primary btn-icon glyphicons circle_plus" href="<?php echo $this->webroot ?>alerts/add_rules"><i></i> <?php __('Create New') ?></a>
        <a class="btn btn-primary btn-icon glyphicons remove" onclick="deleteAll('<?php echo $this->webroot ?>alerts/delete_rules_all');" href="###"><i></i> <?php __('Delete All') ?></a>
        <a class="btn btn-primary btn-icon glyphicons remove" onclick="deleteSelected('ruleId', '<?php echo $this->webroot ?>alerts/delete_rules_selected', 'rule');" href="###"><i></i> <?php __('Delete Seleted') ?></a>
    <?php } ?>
    <?php
    if (isset($edit_return))
    {
        ?>
        <a href="<?php echo $this->webroot; ?>alerts/rule" class="link_back btn btn-default btn-icon glyphicons left_arrow"><i></i> Back</a>
    <?php } ?>
</div>
<div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">

            <ul class="tabs">
                <li class="active">
                    <a class="glyphicons no-js paperclip" href="<?php echo $this->webroot; ?>alerts/rules">
                        <i></i><?php __('Rule') ?>
                    </a>
                </li>
                <!--<li>
                    <a class="glyphicons no-js tint" href="<?php /*echo $this->webroot; */?>alerts/block_log">
                        <i></i><?php /*__('Block') */?>
                    </a>
                </li>
                <li>
                    <a class="glyphicons no-js vector_path_all" href="<?php /*echo $this->webroot; */?>alerts/block_trouble_ticket">
                        <i></i><?php /*__('Trouble Tickets') */?>
                    </a>
                </li>-->
                <li>
                    <a class="glyphicons book_open" href="<?php echo $this->webroot; ?>alerts/alert_rules_log">
                        <i></i><?php __('Alert Rules Log') ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="widget-body">
            <div class="filter-bar">
                <div class="filter-bar">
                    <form method="get">
                        <div>
                            <label><?php __('Rule Name') ?></label>
                            <input type="text" name="search" id="search-_q" />
                        </div>
                        <div>
                            <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                        </div>
                    </form>
                </div>
            </div>

            <?php
            if (empty($mydata))
            {
                ?>

                <h2 class="msg center"><?php echo __('no_data_found', true); ?></h2>
                <?php
            }
            else
            {
                ?>
                <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">

                    <thead>
                    <tr>

                        <th><?php if ($_SESSION['login_type'] == '1'): ?>
                                <input id="selectAll" class="select" type="checkbox" onclick="checkAllOrNot(this, 'ruleId');" value=""/>
                            <?php endif; ?>
                        </th>
                        <th><?php __('Rule Name'); ?></th>
                        <th><?php __('Frequency'); ?></th>
                        <th><?php echo $appCommon->show_order('last_run_time', __('Last Run Time', true)) ?></th>
                        <th><?php echo $appCommon->show_order('next_run_time', __('Next Run Time', true)) ?></th>
                        <th><?php __('Update By'); ?>  </th>
                        <th><?php echo $appCommon->show_order('update_at', __('Update Time', true)) ?></th>
                        <?php if ($_SESSION['role_menu']['Monitoring']['alerts:rules']['model_w']): ?>
                            <th><?php echo __('action', true); ?></th>
                        <?php endif; ?>

                    </tr>

                    </thead>
                    <tbody  id="ruleId">

                    <?php foreach ($mydata as $data_item): ?>
                        <tr>
                            <td style="text-align:center">
                                <?php if ($_SESSION['login_type'] == '1'): ?><input class="select" type="checkbox" value="<?php echo $data_item[0]['id'] ?>"/>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php $this->webroot; ?>add_rules/<?php echo base64_encode($data_item[0]['id']); ?>"><?php echo $data_item[0]['rule_name']; ?>
                                </a>
                            </td>
                            <td><?php echo $data_item[0]['run_info']; ?> </td>
                            <td><?php echo $data_item[0]['last_run_time']; ?> </td>
                            <td><?php echo $data_item[0]['next_run_time']; ?> </td>
                            <td><?php echo $data_item[0]['update_by']; ?> </td>
                            <td><?php echo $data_item[0]['update_at']; ?> </td>
                            <?php if ($_SESSION['role_menu']['Monitoring']['alerts:rules']['model_w']): ?>
                                <td>
                                    <?php if ($data_item[0]['active']): ?>
                                        <a title="<?php __('Deactivate') ?>" onclick="return myconfirm('<?php __('Are you sure to deactivate it?'); ?>',this);"
                                           href="<?php echo $this->webroot; ?>alerts/disable_rule/<?php echo base64_encode($data_item[0]['id']) ?>" >
                                            <i class="icon-check"></i>
                                        </a>
                                    <?php else: ?>
                                        <a title="<?php __('Activate') ?>"  onclick="return myconfirm('<?php __('Are you sure to activate it?'); ?>',this);"
                                           href="<?php echo $this->webroot; ?>alerts/enable_rule/<?php echo base64_encode($data_item[0]['id']) ?>" >
                                            <i class="icon-unchecked"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a title="Edit" class="" href="<?php echo $this->webroot; ?>alerts/add_rules/<?php echo base64_encode($data_item[0]['id']); ?>">
                                        <i class="icon-edit"></i>
                                    </a>
                                    <a title="Delete" class="" onclick="return myconfirm('<?php __('sure to delete') ?>', this)" href="<?php echo $this->webroot; ?>alerts/delete_alert_rules/<?php echo base64_encode($data_item[0]['id']) ?>">
                                        <i class="icon-remove"></i>
                                    </a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>
                <div class="row-fluid separator">
                    <div class="pagination pagination-large pagination-right margin-none">
                        <?php echo $this->element('page'); ?>
                    </div>
                </div>

            <?php } ?>



            <div class="clearfix"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {

        $(".delete_rule").click(function() {
            var name = $(this).attr('hit');
            var obj = $("#delete_rule");
            obj.attr('href', $(this).attr('url'));
            myconfirm('Delete rule' + name + '?', obj);
        });

        $('.condition').hover(function(e) {
            $('.tooltips').remove();
            var xx = e.originalEvent.x || e.originalEvent.layerX || 0;
            var yy = e.originalEvent.y || e.originalEvent.layerY || 0;
            var condition_id = $.trim($(this).text());
            $.ajax({
                'url': '<?php echo $this->webroot; ?>alerts/get_condition/' + condition_id,
                'type': 'GET',
                'dataType': 'json',
                'success': function(data) {
                    var $ul = $('<ul />').css({
                        'position': 'absolute',
                        'left': xx,
                        'top': yy + 200,
                        'opacity': 1
                    });
                    $ul.addClass('tooltips');
                    $ul.append('<li>ACD:' + data[0][0]['acd'] + '</li>');
                    $ul.append('<li>ASR:' + data[0][0]['asr'] + '</li>');
                    $ul.append('<li>Margin:' + data[0][0]['margin'] + '</li>');
                    $('body').append($ul);

                }
            });
        }, function(e) {
            $('.tooltips').remove();
        });


        $('.action').hover(function(e) {
            $('.tooltips').remove();
            var xx = e.originalEvent.x || e.originalEvent.layerX || 0;
            var yy = e.originalEvent.y || e.originalEvent.layerY || 0;
            var condition_id = $.trim($(this).text());
            $.ajax({
                'url': '<?php echo $this->webroot; ?>alerts/get_action/' + condition_id,
                'type': 'GET',
                'dataType': 'json',
                'success': function(data) {
                    var $ul = $('<ul />').css({
                        'position': 'absolute',
                        'left': xx,
                        'top': yy + 200,
                        'opacity': 1
                    });
                    $ul.addClass('tooltips');
                    var arr = data[0][0]['content'].split(',');
                    for (var v in arr) {
                        $ul.append('<li>' + arr[v] + '</li>');
                    }
                    //$ul = "<div class='tooltips'>"+$ul+"</div>";
                    $('body').append($ul);

                }
            });
        }, function(e) {
            $('.tooltips').remove();
        });

        $('#selectAll').click(function () {
            let selected = $(this).prop('checked');
            $('input.select').prop('checked', selected);
        });

    });
</script>