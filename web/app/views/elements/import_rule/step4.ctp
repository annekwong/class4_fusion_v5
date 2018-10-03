<table class="form table table-condensed dynamicTable tableTools table-bordered ">
    <colgroup>
        <col width="40%">
        <col width="60%">
    </colgroup>
   <tr>
       <td class="align_right padding-r20"><?php echo __('Country Code') ?> </td>
       <td>
           <?php
           $active = isset($this->data['active']) ? $this->data['active'] : false;
           echo $form->input('active', array('type' => 'hidden',  'value' => $active, 'label' => false, 'div' => false))
           ?>
           <?php
           $country_code_val = isset($this->data['country_code']) ? explode(',', $this->data['country_code']) : [];
           echo $form->input('country_code', array('options' => $country_code,  'value' => $country_code_val, 'multiple' => 'multiple', 'label' => false, 'div' => false,
               'type' => 'select', 'class' => 'input in-text in-select'))
           ?>
       </td>
   </tr>
   <tr>
       <td class="align_right padding-r20"><?php echo __('Min Time', true); ?> </td>
       <td> <?php
       $min_time = isset($this->data['min_time']) ? $this->data['min_time'] : 1;
       echo $form->input('min_time', array('label' => false, 'div' => false, 'value' => $min_time,
               'class' => 'width220 in-decimal validate[custom[integer]]')) ?></td>
   </tr>
   <tr>
       <td class="align_right padding-r20"><?php echo __('Interval', true); ?> </td>
       <td> <?php
       $interval = isset($this->data['interval']) ? $this->data['interval'] : 1;
       echo $form->input('interval', array('label' => false, 'div' => false, 'value' => $interval,
               'class' => 'width220 in-decimal validate[custom[integer]]')) ?></td>
   </tr>
</table>

<div class="center">
    <a step="#step3" href data-toggle="tab" value="next"  id="previous4" class=" btn primary"><?php __('Previous')?></a>
    <input type="submit" value="Finish" id="finish" class="input in-submit btn btn-primary" />
</div>


<script type="text/javascript">
    $(function() {
        $('#country_code').multiSelect();

        $("#next3").click(function(){

            $("#step4").click();
        });
        $("#step4").click(function(){
            if ($("#rule_form").validationEngine('validate'))
            {

                return true;
            }
            return false;
        });
        $("#previous4").click(function() {
            $("#step3").click();
        });
        $("#finish").click(function() {

            if ($("#rule_form").validationEngine('validate'))
            {
                $("#step_").val(4);
                let is_active =  $("#rule_form").find('#active').val();
                if(!is_active){
                    if (confirm("Do you want to activate the rule now ?")) {
                        $("#rule_form").find('#active').val('true');
                    }else{
                        $("#rule_form").find('#active').val('false');
                    }
                }

                return true;
            }

            return false;
        });
        var value_init = $("#step3_type").val();
        if (value_init == 1)
        {
            $("#step3_type2").addClass('hidden');
        }
        else
        {
            $("#step3_type1").addClass('hidden');
        }
    });
</script>
