<script type="text/javascript">
    function judge_billing_rule(obj)
    {
        var $obj = $(obj);
        $.ajax({
            'url': '<?php echo $this->webroot ?>did/billing_rule/ajax_judge_billing_rule',
            'type': 'POST',
            'success': function(data) {
                if (data == 0)
                {
                    jGrowl_to_notyfy('<?php __("You need to add the billing rule"); ?>', {theme: 'jmsg-error'});
                }
                else
                {
                    window.location.href = $obj.attr('href');
                }
            }
        });
        return false;

    }
</script>