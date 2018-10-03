<?php
$class = "num-dec-4";
echo $form->create('DidBillingPlan')?>
<table>
    <tr>
        <td></td>
        <td><?php echo $xform->input('name',array('maxlength'=>256, 'style'=> 'width: 125px;'))?></td>
        <td>
             <div>
                <select name="type_rate" id="type_rate" style="width: 98px;">
                    <option value="1" selected>Fixed Rate</option>
                    <option value="2">Variable Rate</option>
                </select>
            </div>
        </td>
        <td><?php echo $xform->input('monthly_charge',array('maxlength'=>256, 'style'=>'width:80px;', 'class' => $class))?></td>

<!--        <td>--><?php //echo $xform->input('channel_price',array('maxlength'=>256, 'style'=>'width:40px;', 'class' => $class))?><!--</td>-->
        <td>
        <?php echo $xform->input('min_price',array('maxlength'=>256, 'type'=>'text', 'style'=>'width:80px;', 'class' => $class))?>
        </td>
<!--        <td>--><?php //echo $xform->input('billed_channels',array('maxlength'=>256, 'style'=>'width:40px;', 'class' => $class))?><!--</td>-->
        <td><?php echo $xform->input('payphone_subcharge',array('maxlength'=>256, 'style'=>'width:80px;', 'class' => $class))?></td>
        <td>
                    <?php echo $xform->input('did_price',array('maxlength'=>256, 'style'=>'width:80px;', 'class' => $class))?>

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
    $(function () {
        $(".num-dec-4").keyup(function() {
            var $this = $(this);
            $this.val($this.val().replace (/(\.\d\d\d\d)\d+|([\d.]*)[^\d.]/, '$1$2'));
        });
        $("#type_rate").change(function () {
           if($(this).val() == 1) {
               $("#DidBillingPlanMinPrice").css("visibility", "visible").show();
           } else if($(this).val() == 2) {
               $("#DidBillingPlanMinPrice").css("visibility", "hidden").hide();
           }
        });
    });
</script>
