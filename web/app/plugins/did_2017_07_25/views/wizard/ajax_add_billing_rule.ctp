<div>
    <form action="<?php echo $this->webroot; ?>did/billing_rule/plan_edit_panel" method="post" id="form1">
        <table class="table dynamicTable tableTools table-bordered  table-white">
            <col width="40%">
            <col width="60%">
            <tr>
                <td class="align_right"><?php __('Name')?>:</td>
                <td>
                    <input name="data[DidBillingPlan][name]"  class="validate[required]" />
                </td>
            </tr>
            <tr>
                <td class="align_right"><?php __('Type Rate')?>:</td>
                <td>
                   <select name="data[DidBillingPlan][type_rate]" id="type_rate" style="width: 158px; border-radius: 0;">
                        <option value="1" selected>Fixed Rate</option>
                        <option value="2">Variable Rate</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="align_right"><?php __('Price/DID/Month')?>:</td>
                <td>
                    <input name="data[DidBillingPlan][monthly_charge]" class="num-dec-4"/>
                </td>
            </tr>
            <tr class="min-price-row">
                <td class="align_right"><?php __('Price/Minute')?>:</td>
                <td>
                    <input name="data[DidBillingPlan][min_price]"  class="num-dec-4"/>
                </td>
            </tr>
            <tr>
                <td class="align_right"><?php __('Payphone Subcharge')?>:</td>
                <td>
                    <input name="data[DidBillingPlan][payphone_subcharge]"  class="num-dec-4"/>
                </td>
            </tr>
            <tr>
                <td class="align_right"><?php __('Setup Fee')?>:</td>
                <td>
                    <input name="data[DidBillingPlan][did_price]"  class="num-dec-4"/>
                </td>
            </tr>
        </table>
    </form>
</div>
<script type="text/javascript">
    $(function () {
        $(".num-dec-4").keyup(function() {
            var $this = $(this);
            $this.val($this.val().replace (/(\.\d\d\d\d)\d+|([\d.]*)[^\d.]/, '$1$2'));
        });
        $("#type_rate").change(function () {
           if($(this).val() == 1) {
               $(".min-price-row").css("visibility", "visible").show();
           } else if($(this).val() == 2) {
               $(".min-price-row").css("visibility", "hidden").hide();
           }
        });
    });
</script>