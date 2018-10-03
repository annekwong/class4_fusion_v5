<?php
$class = "num-dec-4";
echo $form->create('Gatewaygroup')?>
    <table>
        <tr>
<!--            <td>--><?php //echo $xform->input('alias',array('maxlength'=>256, 'style'=> 'width: 125px;'))?><!--</td>-->
            <td><?php echo $this->data["Gatewaygroup"]["alias"]; ?></td>
            <td>
                <?php if (!empty($this->data)): ?>
                    <?php foreach ($this->data["Gatewaygroup"]["ip"] as $key => $item): ?>
                        <div>
                            <div style="display: inline-block;">
                                <input type="text" name="data[ips][]" value="<?php echo $item['ResourceIp']['ip'] ?>" class="validate[custom[ipv4]]">
                            </div>
                            <div style="display: inline-block;">
                                <input type="text" name="data[ports][]" value="<?php echo $item['ResourceIp']['port'] ?>" class="validate[custom[integer]]">
                            </div>

                            <?php if ($key == 0): ?>
                                <a href="javascript:void(0)" id="add_ip">
                                    <i class="icon-plus"></i>
                                </a>
                            <?php else: ?>
                                <a href='javascript:void(0)' onclick='deleteIp(this)'>
                                    <i class='icon-remove'></i>
                                </a>
                            <?php endif; ?>
                            <div class="clear"></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div>
                        <div style="display: inline-block;">
                            <input type="text" name="data[ips][]" class="validate[custom[ipv4]]">
                        </div>
                        <a href="javascript:void(0)" id="add_ip">
                            <i class="icon-plus"></i>
                        </a>
                        <div class="clear"></div>
                    </div>
                <?php endif; ?>
            </td>
<!--            <td>--><?php //echo $xform->input('price_per_max_channel',array('maxlength'=>256, 'style'=> 'width: 125px;'))?><!--</td>-->
<!--            <td>--><?php //echo $xform->input('price_per_actual_channel',array('maxlength'=>256, 'style'=> 'width: 125px;'))?><!--</td>-->
            <td></td>
            <td></td>
<!--            <td>--><?php //echo $xform->input('status',array('maxlength'=>256, 'style'=> 'width: 125px;', 'type' => 'select', 'options' => array('1' => 'Untested', '2' => 'Tested', '3' => 'Failed')))?><!--</td>-->
            <td>
                <?php
                $statuses = array('1' => 'Untested', '2' => 'Tested', '3' => 'Failed');
                echo $statuses[$this->data["Gatewaygroup"]["status"]];
                ?>
            </td>
            <td align="center" style="text-align:center" class="last">
                <a id="save" href="###" title="Edit">
                    <i class="icon-save"></i>
                </a>
                <a id="delete" title="Exit">
                    <i class="icon-remove"></i>
                </a>
            </td>
        </tr>
    </table>
<?php echo $form->end()?>

<script>
    function deleteIp(el) {
        $(el).parent().remove();
    }

    $("#add_ip").click(function() {
        var $this = $(this);
        var clone = $(this).closest('div').clone();
        $(clone).find('input').val('').end().find('#add_ip').remove().end().find('.clear').remove().end().append("<a href='javascript:void(0)' onclick='deleteIp(this)'><i class='icon-remove'></i></a>");
        $this.parent().after(clone);
    });
</script>
