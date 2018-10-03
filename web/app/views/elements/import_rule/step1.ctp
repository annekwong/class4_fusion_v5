<table class="form table table-condensed dynamicTable tableTools table-bordered ">
    <colgroup>
        <col width="40%">
        <col width="60%">
    </colgroup>
    <tr>
        <td class="align_right padding-r20"><?php echo __('Rule Name') ?>* </td>
        <td>
            <?php
            $rate_id = isset($this->data['id']) ? $this->data['id'] : '';
            echo $form->input('rule_name', array('label' => false, 'div' => false, 'type' => 'text',
                 'rule_id' => $rate_id, 'maxLength' => '100', 'class' => 'width220 validate[required,custom[onlyLetterNumberLineSpace],funcCall[notEqualAdmin]]')) ?>
        </td>
    </tr>
    <tr>
        <td class="align_right padding-r20"><?php echo __('Egress Trunk') ?>*  </td>
        <td>
            <?php
            echo $form->input('resource_id', array('options' => $egress_trunks, 'label' => false, 'div' => false,
                'type' => 'select', 'class' => 'input in-text in-select validate[required]'))
            ?>
        </td>
    </tr>
     <tr>
        <td class="align_right padding-r20"><?php echo __('From Email', true); ?>* </td>
        <td> <?php echo $form->input('from_email', array('label' => false, 'div' => false,
              'class' => 'width220 validate[required,custom[email]]')) ?></td>
    </tr>
    <tr>
        <td class="align_right padding-r20"><?php echo __('Mail Server IP', true); ?>* </td>
        <td> <?php echo $form->input('mail_server_ip', array('label' => false, 'div' => false,
                'class' => 'width220 validate[required,custom[ipv4]]')) ?></td>
    </tr>
</table>


<div class="center">
    <a value="next" id="next1" data-toggle="tab" step="#step2" href=""  class="input in-submit btn btn-primary"><?php __('Next')?></a>
</div>

<script type="text/javascript">
    $(function() {
          $("#next1").click(function() {
              $("#step2").click();
          });

         $("#step2").click(function() {
            let rule_name = $("#rule_name").val();
            let rule_id = $("#rule_name").attr('rule_id');
            let check_flg = '';
            if (!$("#rule_form").validationEngine('validate'))
            {
               return false;
            }
            $.ajax({
                type: "POST",
                async: false,
                url: "<?php echo $this->webroot; ?>import_rule/ajax_check_rule_name",
                data: {'rule_name' : rule_name ,'rule_id':rule_id},
                dataType: 'json',
                success: function(result) {
                    if (result.status == 0)
                    {
                        jGrowl_to_notyfy(result.msg, {theme: 'jmsg-error'});
                        check_flg = 1;
                    }
                }
            });
            if(check_flg)
            {
                return false;
            }
            return true;
        });
    });
</script>