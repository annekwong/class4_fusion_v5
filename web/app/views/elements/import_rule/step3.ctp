<table class="form table table-condensed dynamicTable tableTools table-bordered ">
    <colgroup>
        <col width="40%">
        <col width="60%">
    </colgroup>
    <tr>
       <td class="align_right padding-r20"><?php echo __('Minimum Lead time for New Code', true); ?>  </td>
       <td> <?php echo $form->input('min_lead_time_new_code', array('label' => false, 'div' => false,
                'class' => 'width220 in-decimal validate[custom[integer]]' )) ?></td>
   </tr>
    <tr>
       <td class="align_right padding-r20"><?php echo __('Minimum Lead time for Increase Rate', true); ?>  </td>
       <td> <?php echo $form->input('min_lead_time_inc_rate', array('label' => false, 'div' => false,
               'class' => 'width220 in-decimal validate[custom[integer]]' )) ?></td>
   </tr>
    <tr>
       <td class="align_right padding-r20"><?php echo __('Minimum Lead time for Delete Code', true); ?>  </td>
       <td> <?php echo $form->input('min_lead_time_del_code', array('label' => false, 'div' => false,
              'class' => 'width220 in-decimal validate[custom[integer]]'  )) ?></td>
   </tr>

   <tr>
      <td class="align_right padding-r20"><?php echo __('Violation Action') ?>  </td>
      <td>
          <?php
          echo $form->input('violation_action', array('options' => $violation_action, 'label' => false, 'div' => false,
              'type' => 'select', 'class' => 'input in-text in-select no-select2'));

          ?>
      <?php echo $form->input('min_lead_time', array('label' => false, 'div' => false, 'style'=> 'width:20px;' )); ?>
      <span></span>
      </td>

  </tr>

   <tr>
      <td class="align_right padding-r20"><?php echo __('Special') ?>  </td>
      <td>
          <?php
          $st = array('false' => __('No', true), 'true' => __('Yes', true));
          echo $form->input('special', array('options' => $st, 'label' => false, 'div' => false,
              'type' => 'select', 'class' => 'input in-text in-select no-select2'))
          ?>
      </td>
  </tr>
  <tr>
       <td class="align_right padding-r20"><?php echo __('Special Case', true); ?>  </td>
       <td>
       <textarea style="width:100%;height:70px;" id="special_rule_case" name="special_rule_case">
       </textarea>
       </td>
   </tr>

</table>
<div class="center">
    <a step="#step2" href=""  data-toggle="tab" value="next"  id="previous3" class=" btn primary"><?php __('Previous')?></a>

    <a value="next" id="next3" href="" data-toggle="tab"  class="input in-submit btn btn-primary"><?php __('Next')?></a>
</div>
<script type="text/javascript">

    $(function() {

         $("#previous3").click(function() {
            $("#step2").attr('hit','1').click();
        });
        $("#step4_type").change(function() {
            var value = $(this).val();
            $(".step4_type").addClass('hidden');
            switch (value)
            {
                case '1':
                    $("#step4_type1").removeClass('hidden');
                    break;
                case '2':
                    $("#step4_type2").removeClass('hidden');
                    break;
                case '3':
                    $("#step4_type3").removeClass('hidden');
                    break;
            }
        });
    });
</script>