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
                <td class="align_right"><?php __('Price/DID/Month')?>:</td>
                <td>
                    <input name="data[DidBillingPlan][did_price]"/>
                </td>
            </tr>
            <tr>
                <td class="align_right"><?php __('Price/Channel Limit')?>:</td>
                <td>
                    <input name="data[DidBillingPlan][channel_price]" />
                </td>
            </tr>
            <tr>
                <td class="align_right"><?php __('Price/Minute')?>:</td>
                <td>
                    <input name="data[DidBillingPlan][min_price]" />
                </td>
            </tr>
            <tr>
                <td class="align_right"><?php __('Price/Max Channel Usage')?>:</td>
                <td>
                    <input name="data[DidBillingPlan][billed_channels]" />
                </td>
            </tr>


        </table>
    </form>
</div>
<script type="text/javascript">

</script>