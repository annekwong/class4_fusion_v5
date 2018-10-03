<style>
    #container table{border-top: 1px solid #ebebeb;}
    select,input[type="text"]{margin:5px 0;}
</style>
<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Tools') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Routing Analysis') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Routing Analysis') ?></h4>
    
</div>
<div class="separator bottom"></div>
<?php if (isset($p)): ?>
        <div class="buttons pull-right newpadding">
            <a class="btn btn-default btn-icon glyphicons circle_arrow_left btn-inverse" href="<?php echo $this->webroot; ?>routing_analysis">
                <i></i>
                <?php __('Back') ?>
            </a>
        </div>
        <?php
    endif;
    ?>
    <div class="clearfix"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-body">

            <div id="container">
                <?php
                if (isset($p)):
                    $mydata = $p->getDataArray();
                    $count = count($mydata);
                    if ($count == 0):
                        ?>
                <div class="msg center"><h3><?php echo __('no_data_found') ?></h3></div>
                    <?php else: ?>
                        <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                            <thead>
                                <tr>
                                    <th><?php __('Egress Carrier')?></th>
                                    <th><?php __('Egress Trunk')?></th>
                                    <th><?php __('Egress Code Name')?></th>
                                    <th><?php __('Egress Code')?></th>
                                    <th><?php __('Egress Rate')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($i = 0; $i < $count; $i++): ?>
                                    <tr>
                                        <td><?php echo $egress_infos[$mydata[$i][0]['rate_table_id']]['client_name'] ?></td>
                                        <td><?php echo $egress_infos[$mydata[$i][0]['rate_table_id']]['trunk_name'] ?></td>
                                        <td><?php echo $mydata[$i][0]['code_name']; ?></td>
                                        <td><?php echo $mydata[$i][0]['code']; ?></td>
                                        <td><?php echo number_format($mydata[$i][0]['rate'], 5); ?></td>
                                    </tr>
                                <?php endfor; ?>
                            </tbody>
                        </table>     
                        <div class="bottom row-fluid">
                            <div class="pagination pagination-large pagination-right margin-none">
                                <?php echo $this->element('page'); ?>
                            </div> 
                        </div>
                    <?php
                    endif;
                else:
                    ?>
                    <form method="get">
                        <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                            <col width="40%">
                            <col width="60%">
                            <tr>
                                <td class="right"><?php __('Ingress Carrier')?></td>
                                <td>
                                    <select id="carrier" name="carrier">
                                        <option></option>
                                        <?php foreach ($carriers as $carrier): ?>
                                            <option value="<?php echo $carrier[0]['client_id'] ?>"><?php echo $carrier[0]['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="right"><?php __('Ingress Trunk')?></td>
                                <td>
                                    <select id="ingress_trunk" name="ingress_trunk">
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="right"><?php __('Prefix')?></td>
                                <td>
                                    <select id="ingress_prefix" name="ingress_prefix">
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="right"><?php __('Code')?></td>
                                <td>
                                    <input type="text" name="code" class="width220">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="center">
                                    <input type="submit" name="submit" value="<?php __('Submit')?>" class="btn btn-primary" />
                                </td>
                            </tr>
                        </table>
                    </form>
                <?php
                endif;
                ?>
            </div>
        </div>
    </div>
</div>

            <script>
                $(function() {
                    var $carrier = $('#carrier');
                    var $ingress_trunk = $('#ingress_trunk');
                    var $ingress_prefix = $('#ingress_prefix');

                    $carrier.change(function() {
                        var $this = $(this);
                        var client_id = $this.val();
                        if (client_id != '')
                        {
                            $.ajax({
                                'url': '<?php echo $this->webroot ?>routing_analysis/get_ingress_trunks',
                                'type': 'POST',
                                'dataType': 'json',
                                'data': {'client_id': client_id},
                                'success': function(data) {
                                    $ingress_trunk.empty();
                                    $ingress_trunk.append('<option></option>')
                                    $.each(data, function(key, item) {
                                        $ingress_trunk.append('<option value="' + item[0]['resource_id'] + '">' + item[0]['alias'] + '</option>')
                                    });
                                }
                            });
                        }
                    }).trigger('change');

                    $ingress_trunk.change(function() {
                        var $this = $(this);
                        var ingress_id = $this.val();
                        if (ingress_id != '')
                        {
                            $.ajax({
                                'url': '<?php echo $this->webroot ?>routing_analysis/get_ingress_prefixes',
                                'type': 'POST',
                                'dataType': 'json',
                                'data': {'ingress_id': ingress_id},
                                'success': function(data) {
                                    $ingress_prefix.empty();
                                    $.each(data, function(key, item) {
                                        var item_name = item[0]['tech_prefix'];
                                        if (item_name == '')
                                        {
                                            item_name = 'NONE';
                                        }
                                        $ingress_prefix.append('<option value="' + item[0]['route_strategy_id'] + '">' + item_name + '</option>')
                                    });
                                }
                            });
                        }
                    }).trigger('change');

                });
            </script>