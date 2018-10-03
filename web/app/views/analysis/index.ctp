<style>
    .trunk_list{
    }
    .trunk_list li{
        float:left;padding:0px 2px;margin-top:2px;white-space:nowrap;width:300px;
    }
    select,input[type="text"]{margin:5px 0;}
    .query-box table {border-top: 1px solid #ebebeb;}
</style>

<ul class="breadcrumb">
    <li>You are here</li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Tools') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Rate Analysis') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading">Rate Analysis</h4>
    
</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
        <a class="btn btn-default btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>ratefinders">
            <i></i>
            Back
        </a>
    </div>
    <div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">
            <?php if (isset($p)): ?>
                <?php
                if (!empty($p)) :
                    $data = $p->getDataArray();
                    ?>
                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                        <thead>
                            <tr>
                            <tr>
                                <td><?php echo __('code', true); ?></td>
                                <td><?php echo __('min', true); ?></td>
                                <td><?php echo __('max', true); ?></td>
                                <td><?php echo __('avg', true); ?></td>
                                <?php for ($i = 0; $i < $maxfields; $i++): ?>
                                    <td>Trunk-<?php echo $i + 1; ?></td>
                                <?php endfor; ?>
                            </tr>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $item): ?>
                                <tr>
                                    <?php for ($i = 0; $i <= $maxfields + 3; $i++): ?>
                                        <?php if ($i > 3): ?>
                                            <td><a href="###" class="addtrunk"><?php echo isset($item[$i]) ? $item[$i] : '&nbsp'; ?></a></td>
                                        <?php else: ?>
                                            <td><?php echo isset($item[$i]) ? $item[$i] : '&nbsp'; ?></td>
                                        <?php endif ?>
                                    <?php endfor; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="bottom row-fluid">
                        <div class="pagination pagination-large pagination-right margin-none">
                            <?php echo $this->element('page'); ?>
                        </div> 
                    </div>
                <?php else: ?>
                    <div class="msg" style="width:550px;">No Egress Found For Specified Code and Rate can be found.</div>
                <?php endif; ?>
            <?php endif; ?>
            <form name="myform" id="myform" method="post">
                <fieldset class="query-box">
                    <h4 style="display: inline-block;" class="heading glyphicons search"><i></i> Search</h4>

                    <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                        
                        <tr>
                            <td class="align_right" rowspan="2" style="width:8%">
                                <?php echo __('Get Margin For', true); ?>
                            </td>
                            <td class="align_right" style="width:7%">
                                <?php echo __('Trunk', true); ?>
                            </td>
                            <td>
                                <select name="trunks" id="trunks">
                                    <option></option>
                                    <?php foreach ($trunks as $trunk): ?>
                                        <option value="<?php echo $trunk[0]['resource_id'] ?>"><?php echo $trunk[0]['alias'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="align_right" rowspan="2" style="width:7%">
                                <?php echo __('Report Type', true); ?>
                            </td>
                            <td rowspan="2">
                                <select name="report_type">
                                    <option value="0">Standard view</option>
                                    <option value="1">Rate Comparation</option>
                                </select>
                            </td>
                            <td class="align_right" rowspan="2" style="width:7%">
                                <?php echo __('Show Type', true); ?>
                            </td>
                            <td rowspan="2">
                                <select name="show_type">
                                    <option value="0">WEB</option>
                                    <option value="1">CSV</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="align_right" style="background:#fff;">
                             <?php echo __('Rate Table', true); ?></td>
                            <td style="background:#fff;">
                                <select name="ratetables" id="ratetables">
                                    <option></option>    
                                </select>
                            </td>
                        </tr>
                        
                        <tr>
                            <td></td>
                            <td colspan="6">
                                <label style="float:left;color:#FF6D06;font-weight:bold;"><input type="checkbox" id="select_all" style="cursor:pointer;" /><?php echo __('Select All', true); ?></label>
                                <label style="float:left;margin:0 10px;">|</label>
                                <label style="float:left"><a id="select_reverse" style="cursor:pointer;"><?php echo __('Unselected', true); ?></a></label>
                            </td>

                        </tr>
                        <tr>
                            <td class="align_right"><?php echo __('rate table', true); ?></td>
                            <td colspan="6" style="text-align:left !important;">
                                <div style="height:150px;overflow-y:scroll;margin-top:10px;">
                                    <?php foreach ($ratetables as $ratetable): ?>
                                        <label style="width:250px;float:left;"><input type="checkbox" name="ratetable[]" value="<?php echo $ratetable[0]['id'] ?>" /><?php echo $ratetable[0]['name'] ?></label>
                                        <?php endforeach; ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7"class="center"><input class="btn btn-primary" type="submit" value="<?php echo __('submit', true); ?>" /></td>
                        </tr>
                    </table>

                </fieldset>
            </form>
        </div>
        <script>
            $(function() {
                $('input[name=egress_trunk_type]').click(function() {
                    if ($(this).val() != '2') {
                        $('table.list tr:nth-child(4)').hide();
                    } else {
                        $('table.list tr:nth-child(4)').show();
                    }
                });

                $('#select_all').click(function() {
                    $("input[name='ratetable[]']").attr("checked", $(this).attr("checked"));
                });

                $('#select_reverse').click(function() {
                    $("input[name='ratetable[]']").each(function(idx, item) {
                        $(item).attr("checked", !$(item).attr("checked"));
                    });
                });

                $('#trunks').change(function() {
                    var resource_id = $(this).val();
                    $.ajax({
                        'url': '<?php $this->webroot; ?>analysis/ready_ratetables/' + resource_id,
                        'type': 'GET',
                        'dataType': 'json',
                        'success': function(data) {
                            var ratetables = $('#ratetables');
                            ratetables.empty();
                            ratetables.append('<option></option>');
                            $.each(data, function(idx, item) {
                                ratetables.append('<option value="' + item[0]['id'] + '">' + item[0]['name'] + '</option>');
                            });
                        }
                    });
                });
            });
        </script>