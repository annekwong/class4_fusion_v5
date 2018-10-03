<style type="text/css">
    #error_info {
        background:white;width:300px;height:200px;display:none;
        overflow:hide;word-wrap: break-word; padding:20px;
    }
    table.in-date tr td{border-top: 0;}
</style>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>logging"><?php __('Log') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?= $this->webroot?>logging"><?php echo __('Modification Log') ?></a></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Modification Log') ?></h4>
    <div class="buttons pull-right">

    </div>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>

<div class="innerLR">

    <div class="widget widget-heading-simple widget-body-white">
        <div class="widget-body">
            <div class="clearfix"></div>
            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                    <tr>
                        <th><?php echo $appCommon->show_order('Logging.time', __('Time', true)) ?></th>
                        <th><?php echo $appCommon->show_order('Logging.module', __('Module', true)) ?></th>
                        <th><?php echo $appCommon->show_order('Logging.name', __('Operator', true)) ?></th>
                        <th><?php __('Target') ?></th>
                        <th><?php __('Action') ?></th>
                        <?php if ($_SESSION['role_menu']['Log']['logging']['model_w']): ?>
                            <th><?php __('Rollback') ?></th>
                        <?php endif; ?>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($this->data as $item): ?>
                        <tr>
                            <td><?php echo $item['Logging']['time']; ?></td>
                            <td><?php echo $item['Logging']['module']; ?></td>
                            <td><?php echo $item['Logging']['name']; ?></td>
                            <td><?php echo $item['Logging']['detail']; ?></td>
                            <td><?php echo $actions[$item['Logging']['type']]; ?></td>
                            <?php if ($_SESSION['role_menu']['Log']['logging']['model_w']): ?>
                                <td>
                                    <?php
                                    if (!$item['Logging']['rollback_flg'] &&
                                            $item['Logging']['rollback'])
                                    {
                                        ?>
                                        <a href="<?php echo $this->webroot; ?>logging/rollback_data/<?php echo base64_encode($item['Logging']['id']); ?>">
                                            <i class="icon-reply"></i>
                                        </a>
                                        <?php
                                    }
                                    else if ($item['Logging']['rollback_flg'] == 1)
                                    {
                                        ?>
                                        <?php __('Complete'); ?>
                                        <?php
                                    }
                                    else if ($item['Logging']['rollback_flg'] == 2)
                                    {
                                        ?> 
                                        <?php __('Failed'); ?>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        --
                                    <?php } ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('xpage'); ?>
                </div> 
            </div>
            <div class="clearfix"></div>

            <fieldset style="clear:both;overflow:hidden;margin-top:10px;" class="query-box">
                <h4 class="heading glyphicons search"><i></i> <?php __('Search') ?></h4>
                <form method="get" class="form-inline" id="myform" name="myform">
                    <table  class="form table table-condensed">
                        <input type="hidden" value="FALSE" name="isDown" id="isDown" class="input in-hidden">
                        <tbody>
                            <tr class="period-block">

                                <td colspan="6" style="width:auto;" class="value value2"><table style="width: 100%;" class="in-date">
                                        <tbody>
                                            <tr>
                                                <td style="padding-left:0;padding-right: 0;"><?php __('Period') ?></td>
                                                <td><select id="query-smartPeriod" onchange="setPeriod(this.value)" name="smartPeriod" class="input in-select select input-small">
                                                        <option value="custom"><?php __('Custom') ?></option>
                                                        <option selected="selected" value="curDay"><?php __('Today') ?></option>
                                                        <option value="curWeek"><?php __('Current week') ?></option>
                                                        <option value="curMonth"><?php __('Current month') ?></option>
                                                    </select>
                                                </td>
                                                <td style="width:180px;"><input type="text"name="start_date" value="<?php echo $start_date ?>" onkeydown="setPeriod('custom')" readonly="readonly" onchange="setPeriod('custom')" class="in-text input in-input" id="query-start_date-wDt" style="width:70px;">
                                                    &nbsp;
                                                    <input type="text" class="input in-text in-input" name="start_time" value="<?php echo $start_time ?>" readonly="readonly" onkeydown="setPeriod('custom')" onchange="setPeriod('custom')" id="query-start_time-wDt" style="width:70px;"></td>
                                                <td style="padding-left:0;padding-right: 0;">&mdash;</td>
                                                <td style="width:180px;"><input type="text" name="stop_date" value="<?php echo $end_date ?>" onkeydown="setPeriod('custom')" readonly="readonly" onchange="setPeriod('custom')" class="in-text input in-input input-small" id="query-stop_date-wDt" style="width:70px;">
                                                    &nbsp;
                                                    <input type="text"  class="input in-text in-input input-small" name="stop_time" value="<?php echo $end_time ?>" onkeydown="setPeriod('custom')" readonly="readonly" onchange="setPeriod('custom')" id="query-stop_time-wDt" style="width:70px;"></td>
                                                <td><?php __('in') ?></td>
                                                <td><select  class="input in-select select input-small" name="gmt" id="query-tz">
                                                        <option value="-1200">GMT -12:00</option>
                                                        <option value="-1100">GMT -11:00</option>
                                                        <option value="-1000">GMT -10:00</option>
                                                        <option value="-0900">GMT -09:00</option>
                                                        <option value="-0800">GMT -08:00</option>
                                                        <option value="-0700">GMT -07:00</option>
                                                        <option value="-0600">GMT -06:00</option>
                                                        <option value="-0500">GMT -05:00</option>
                                                        <option value="-0400">GMT -04:00</option>
                                                        <option value="-0300">GMT -03:00</option>
                                                        <option value="-0200">GMT -02:00</option>
                                                        <option value="-0100">GMT -01:00</option>
                                                        <option value="+0000">GMT +00:00</option>
                                                        <option value="+0100">GMT +01:00</option>
                                                        <option value="+0200">GMT +02:00</option>
                                                        <option value="+0300">GMT +03:00</option>
                                                        <option value="+0330">GMT +03:30</option>
                                                        <option value="+0400">GMT +04:00</option>
                                                        <option value="+0500">GMT +05:00</option>
                                                        <option value="+0600">GMT +06:00</option>
                                                        <option value="+0700">GMT +07:00</option>
                                                        <option value="+0800">GMT +08:00</option>
                                                        <option value="+0900">GMT +09:00</option>
                                                        <option value="+1000">GMT +10:00</option>
                                                        <option value="+1100">GMT +11:00</option>
                                                        <option value="+1200">GMT +12:00</option>
                                                    </select></td>
                                                <td style="padding-right: 0;"><?php __('Operator') ?></td>
                                                <td>
<!--                                                    <input type="text" name="operator" value="<?php echo $common->set_get_value('operator') ?>" class="input-small" />-->
                                                    <?php
                                                    echo $form->input('rerate_rate_table', array('options' => $all_operator, 'name' => 'operator', 'empty' => '', 'label' => false, 'div' => false, 'class' => 'input-medium', 'style' => 'width:100px;', 'type' => 'select', 'selected' => isset($_GET['operator']) ? $_GET['operator'] : ''));
                                                    ?>
                                                </td>
                                                <td><?php __('Target') ?></td>
                                                <td>
                                                    <input type="text" name="target" value="<?php echo $common->set_get_value('target') ?>" class="input-small" />
                                                </td>
                                                <td><?php __('Action') ?></td>
                                                <td>
                                                    <select name="action" class="input-small">
                                                        <option value="all"  <?php echo $common->set_get_select('action', 'all', TRUE); ?>><?php __('All') ?></option>
                                                        <option value="0" <?php echo $common->set_get_select('action', '0'); ?>><?php __('Creation') ?></option>
                                                        <option value="1" <?php echo $common->set_get_select('action', '1'); ?>><?php __('Deletion') ?></option>
                                                        <option value="2" <?php echo $common->set_get_select('action', '2'); ?>><?php __('Modification') ?></option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="8" class="buttons-group center"><input type="submit" value="<?php __('Submit') ?>" class="input in-submit btn btn-primary"></td>
                            </tr>
                        </tfoot>
                    </table>
                </form>
            </fieldset>

        </div>
    </div>
</div>

<script>
    $(function() {

        $('#query-tz option[value="<?php echo $tz ?>"]').attr('selected', true);

<?php
if ($log_id)
{
    ?>
            if (!$('#dd').length) {
                $(document.body).append("<div id='dd'></div>");
            }
            var $dd = $('#dd');
            var $form = null;


            $dd.load("<?php echo $this->webroot; ?>logging/show_notes/<?php echo $log_id . "/" . $path; ?>",
                            {},
                            function(responseText, textStatus, XMLHttpRequest) {
                                $dd.dialog({
                                    'width': '30%',
                                    'create': function(event, ui) {
                                        $form = $('form', $dd);
                                        $form.validationEngine();
                                    }
                                });
                            }
                    );
<?php } ?>


            });
</script>


