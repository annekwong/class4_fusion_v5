<link rel="stylesheet" href="<?php echo $this->webroot;?>js/jschosen/chosen.css">
<style>
    div.pagination {
        float: right;
        margin-top: 10px;
    }

    div.pagination:after {
        clear: both;
    }

    div.progress {
        display: none;
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        z-index: 99999;
        background: rgba(117, 117, 117, 0.5);
        height: 5px;
    }

    div.progress-bar {
        height: 5px;
        background: rgba(8, 165, 8, 1);
    }

    select option:disabled {
        color: #ccc;
        font-style: italic;
    }

    .btn-primary:active, .btn-primary.active:focus {
        background-color: #354900;
    }

    .btn-primary:active, .btn-primary.active:hover {
        background-color: #354900;
    }
</style>

<div class="progress">
    <div id="progress" class="progress-bar" role="progressbar" aria-valuenow="70"
         aria-valuemin="0" aria-valuemax="100" style="width:70%">
    </div>
</div>

<ul class="breadcrumb">
    <li><?php __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>cdrapi/code_based_report">
            <?php __('Statistics') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>cdrapi/code_based_report">
            <?php __('Code Based Report') ?></a></li>
</ul>
<div class="heading-buttons">
    <h4 class="heading">
        <?php __('Code Based Report'); ?>
    </h4>
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-icon glyphicons btn-inverse circle_arrow_left" onclick="history.go(-1);">
        <i></i>
        <?php __('Back') ?>
    </a>
</div>
<div class="clearfix"></div>
<div class="innerLR">
    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">
            <ul>
                <li class="active">
                    <a href="<?php echo $this->webroot; ?>cdrapi/summary_reports" class="glyphicons list">
                        <i></i>
                        <?php __('Code Based Report') ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->webroot; ?>cdrapi/export_log/3" class="glyphicons book_open">
                        <i></i>
                        <?php __('Export Log') ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="widget-body">
            <!--            Data is very big for showing in web-->
            <!--             --><?php //if (!count($data)): ?>
            <!--                <div class="msg center">-->
            <!--                    <br />-->
            <!--                    <h2>-->
            <!--                        --><?php //echo __('no_data_found', true); ?>
            <!--                    </h2>-->
            <!--                </div>-->
            <!--            --><?php //else: ?>
            <!--                <table class="list footable table table-striped tableTools table-bordered  table-white table-primary">-->
            <!--                    <thead>-->
            <!--                        <tr>-->
            <!--                            --><?php //foreach (array_keys($data['data'][0]) as $field) : ?>
            <!--                                <th>--><?php //echo $mapping[$field]; ?><!--</th>-->
            <!--                            --><?php //endforeach; ?>
            <!--                        </tr>-->
            <!--                    </thead>-->
            <!--                    <tbody >-->
            <!--                            --><?php //foreach ($data['data'] as $item) : ?>
            <!--                                <tr>-->
            <!--                                    --><?php //foreach ($item as $val) : ?>
            <!--                                        <td>--><?php //echo $val; ?><!--</td>-->
            <!--                                    --><?php //endforeach; ?>
            <!--                                </tr>-->
            <!--                            --><?php //endforeach; ?>
            <!--                    </tbody>-->
            <!--                </table>-->
            <!--            --><?php //endif; ?>

            <div class="clearfix"></div>

            <fieldset class="query-box" style=" clear:both;margin-top:10px;">
                <h4 style="display: inline-block;" class="heading glyphicons search"><i></i> <?php __('search') ?></h4>
                <div class="clearfix"></div>
                <?php
                $url = "/" . $this->params['url']['url'];

                echo $form->create('Cdr', array('type' => 'get', 'url' => $url, 'id' => 'report_form',
                    'onsubmit' => "if ($('#query-output').val() == 'web') loading();"));
                ?>

                <table class="form" style="width: 100%">
                    <tbody>
                    <?php
                    echo $this->element('report/form_period', array(
                        'group_time' => false,
                        'gettype' => '  <select id="query-output" name="query[output]" class="input in-select">
                                            <option value="csv" selected>CSV</option>
                                        </select>'
                    ));
                    ?>
                    </tbody>
                </table>
                <div id="advance_panel" class="widget widget-heading-simple widget-body-gray">
                    <table class="form" style="width:100%">
                        <tbody>
                        <tr>
                            <td valign="top" class="align_right padding-r10"><?php echo __('Ingress', true); ?></td>
                            <td valign="top" style="padding-bottom: 10px;"><?php
                                echo $form->input('ingress_alias', array('options' => $ingress, 'label' => false, 'class' => 'chosen-select', 'div' => false, 'multiple' => 'multiple', 'type' => 'select'));
                                ?><?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                            <td valign="top" class="in-out_bound">&nbsp;</td>
                            <td valign="top" class="align_right padding-r10"><?php echo __('Egress', true); ?></td>
                            <td valign="top" ><?php echo $form->input('egress_alias', array('options' => $egress, 'label' => false, 'class' => 'chosen-select', 'empty' => '', 'div' => false, 'multiple' => 'multiple', 'type' => 'select')); ?>
                                <?php echo $this->element('search_report/ss_clear_input_select'); ?></td>
                            <td valign="top" class="in-out_bound">&nbsp;</td>

                        </tr>
                        <tr>
                            <?php for ($i = 1; $i <= 2; $i++): ?>
                                <td  valign="top" class="align_right padding-r10"><?php __('Group By')?> #<?php echo $i; ?></td>
                                <td  valign="top" class="value">
                                    <select class="upper-first" name="group_select[]" style="width:220px;border-radius:0;">
                                        <option value=""></option>
                                        <option value="ingress_id">Ingress Trunk</option>
                                        <option value="egress_id">Egress Trunk</option>
                                    </select>
                                </td>
                                <td valign="top" class="in-out_bound">&nbsp;</td>
                            <?php endfor; ?>
                            <td  valign="top" rowspan="2" style="padding-left: 10px;width:25%;">
                                <div align="left"><?php echo __('Show Fields', true); ?>:</div>
                                <?php  echo $form->select('default_fields', $mapping, $fields, array('id' => 'query-fields', 'style' => 'width:100%; height: 230px;', 'name' => 'query[fields]', 'type' => 'select', 'multiple' => true), false);?>
                                <a class="btn btn-primary" style="width: 92%;" onclick="saveFields();">Save fields as default</a>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <!--p class="separator text-center"><i class="icon-table icon-3x"></i></p>
                    <table class="form" style="width:100%">
                        <tr>
                            <?php for ($i = 1; $i <= 2; $i++): ?>
                                <td class="align_right padding-r10"><?php __('Group By')?> #<?php echo $i; ?></td>
                                <td class="value">
                                    <select class="upper-first" name="group_select[]" style="width:160px;">
                                        <option value=""></option>
                                        <option value="ingress_id">Ingress Trunk</option>
                                        <option value="egress_id">Egress Trunk</option>
                                    </select>
                                </td>
                            <?php endfor; ?>
                        </tr>
                    </table-->
                </div>
            </fieldset>

        </div>
    </div>
</div>

<script src="<?php echo $this->webroot;?>js/jschosen/chosen.jquery.js" type="text/javascript"></script>

<script type="text/javascript">
    var config = {
        '.chosen-select'           : {},
        '.chosen-select-deselect'  : {allow_single_deselect:true},
        '.chosen-select-no-single' : {disable_search_threshold:10},
        '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
        '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

    function saveFields() {
        $.post( "<?php echo $this->webroot ?>cdrapi/save_fields", { "query-fields": $('#query-fields').val()})
            .done(function(data) {
                jGrowl_to_notyfy("The default field selection has been modified!", {theme: 'jmsg-success'});
            });
    }
</script>