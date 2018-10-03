<?php
$class = "num-dec-4";
echo $form->create('DidBillingPlan')?>
<table>
    <tr>
        <td></td>
        <td><?php echo $xform->input('name',array('maxlength'=>256, 'style'=> 'width: 90%;'))?></td>
        <td>
            <?php if (isset($this->data['DidBillingPlan']['rate_type'])): ?>
                <input type="hidden" name="type_rate" value="<?php echo $this->data['DidBillingPlan']['rate_type']; ?>">
                <?php echo $this->data['DidBillingPlan']['rate_type_text'] ?>
            <?php else: ?>
                 <div>
                    <select name="type_rate" id="type_rate" style="width: 90%;">
                        <option value="1" selected>Fixed Rate</option>
                        <option value="2">Variable Rate</option>
                        <option value="3">US LRN Variable Rate</option>
                    </select>
                </div>
            <?php endif ?>
        </td>
        <td><?php echo $xform->input('monthly_charge',array('maxlength'=>256, 'style'=>'width: 90%;', 'class' => $class))?></td>
        <td>
            <div>
                <select name="price_type" id="price_type" style="width: 90%;">
                    <?php foreach ($payTypes as $key => $payType): ?>
                        <option value="<?php echo $key ?>" <?php if ($key == $this->data['DidBillingPlan']['price_type']) echo 'selected' ?>><?php echo $payType ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </td>

<!--        <td>--><?php //echo $xform->input('channel_price',array('maxlength'=>256, 'style'=>'width:40px;', 'class' => $class))?><!--</td>-->
        <td>
        <?php echo $xform->input('min_price',array('maxlength'=>256, 'type'=>'text', 'style'=>'width: 90%;', 'class' => $class))?>
        </td>
<!--        <td>--><?php //echo $xform->input('billed_channels',array('maxlength'=>256, 'style'=>'width:40px;', 'class' => $class))?><!--</td>-->
        <td><?php echo $xform->input('payphone_subcharge',array('maxlength'=>256, 'style'=>'width: 90%;', 'class' => $class))?></td>
        <td><?php echo $xform->input('fee_per_port',array('maxlength'=>256, 'style'=>'width: 90%;', 'class' => $class))?></td>
        <td>
            <div>
                <select name="pay_type" id="pay_type" style="width: 90%;">
                    <?php foreach ($payTypes as $key => $payType): ?>
                        <option value="<?php echo $key ?>" <?php if ($key == $this->data['DidBillingPlan']['pay_type']) echo 'selected' ?>><?php echo $payType ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </td>
        <td>
                    <?php echo $xform->input('did_price',array('maxlength'=>256, 'style'=>'width: 90%;', 'class' => $class))?>

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
