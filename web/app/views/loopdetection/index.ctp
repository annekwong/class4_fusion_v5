

<ul class="breadcrumb">
    <li><?php __('You are here')?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Statistics') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Loop Detection') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Loop Detection')?></h4>

    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">



        <div class="filter-bar">
            <form method="get" name="myform">
                <div>
                    <label><?php __('Detect For'); ?>:</label>
                    <select name="duration">
                        <option value="5" <?php if ($duration == 5) echo 'selected'; ?>>5 <?php __('min')?></option>
                        <option value="10" <?php if ($duration == 10) echo 'selected'; ?>>10 <?php __('min')?></option>
                        <option value="15" <?php if ($duration == 15) echo 'selected'; ?>>15 <?php __('min')?></option>
                        <option value="30" <?php if ($duration == 30) echo 'selected'; ?>>30 <?php __('min')?></option>
                    </select>
                </div>

                <div>
                    <label><?php __('Threshold'); ?>:</label>
                    <input type="text" name="threshold" value="<?php echo $threshold; ?>" />
                </div>
                <div>
                    <button name="submit" class="btn query_btn"><?php __('Query')?></button>
                </div>
            </form>
        </div>
        <div class="widget-body">

            <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
                <thead>
                    <tr>
                        <th><?php __('Ingress Trunk'); ?></th>
                        <th><?php __('Egress Trunk'); ?></th>
                        <th><?php __('Orig ANI'); ?></th>
                        <th><?php __('Orig DNIS'); ?></th>
                        <th><?php __('Counts'); ?></th>
                        <th><?php __('Action'); ?></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($data as $item): ?>
                        <tr>
                            <td><?php echo $item[0]['ingress_trunk']; ?></td>
                            <td><?php echo $item[0]['egress_trunk']; ?></td>
                            <td class="ani"><?php echo $item[0]['origination_source_number']; ?></td>
                            <td class="dnis"><?php echo $item[0]['origination_destination_number']; ?></td>
                            <td><?php echo $item[0]['count']; ?></td>
                            <td>
                                <a href="###" class="blockbtn" ingress_id="<?php echo $item[0]['ingress_id']; ?>" egress_id="<?php echo $item[0]['egress_id']; ?>">
                                    <input type="button" value="Block" />
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $('a.blockbtn').click(function() {
            var $this = $(this);
            var ingress_id = $this.attr('ingress_id');
            var egress_id = $this.attr('egress_id');
            var ani = $this.parent().prev().prev().prev().text();
            var dnis = $this.parent().prev().prev().text();
            $.ajax({
                'url': '<?php echo $this->webroot; ?>loopdetection/put_block_list',
                'type': 'POST',
                'dataType': 'text',
                'data': {'ingress_id': ingress_id, 'egress_id': egress_id, 'ani': ani, 'dnis': dnis},
                'success': function(data) {
                    jGrowl_to_notyfy('Succeeded', {theme: 'jmsg-success'});
                    window.setTimeout("window.location.reload();", 3000);
                }
            });
        });
    });
</script>