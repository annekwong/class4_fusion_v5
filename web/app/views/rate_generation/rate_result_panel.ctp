<tr>
    <td>
        <?php echo $xform->input('generation_rate_id',array('type' => 'hidden')); ?>
    </td>
    <td>
        <?php echo $xform->input('code',array('type' => 'text','class' =>'width120 validate[required,custom[code]]')); ?>
    </td>
    <td>
        <?php echo $xform->input('code_name',array('type' => 'text','class' =>'width120')); ?>
    </td>
    <td>
        <?php echo $xform->input('country',array('type' => 'text','class' =>'width80')); ?>
    </td>
    <td>
        <?php echo $xform->input('rate',array('type' => 'text','class' =>'width80 validate[required,custom[number]]','maxlength' => 9)); ?>
    </td>
    <?php if($rate_table_type == '1'): ?>
        <td>
            <?php echo $xform->input('intra_rate',array('type' => 'text','class' =>'validate[custom[number]]','maxlength' => 9)); ?>
        </td>
        <td>
            <?php echo $xform->input('inter_rate',array('type' => 'text','class' =>'validate[custom[number]]','maxlength' => 9)); ?>
        </td>
        <td>
            <?php echo $xform->input('local_rate',array('type' => 'text','class' =>'validate[custom[number]]','maxlength' => 9)); ?>
        </td>
    <?php endif; ?>
    <!--td>
        <?php echo $xform->input('setup_fee',array('type' => 'text','class' =>'validate[custom[number]]','maxlength' => 9)); ?>
    </td-->
    <td>
        <?php echo $xform->input('min_time',array('type' => 'text','class' =>'width40 validate[custom[integer]]')); ?>
    </td>
    <td>
        <?php echo $xform->input('interval',array('type' => 'text','class' =>'width40 validate[custom[integer]]')); ?>
    </td>
    <!--td>
        <?php echo $xform->input('grace_time',array('type' => 'text','class' =>'width40 validate[custom[integer]]')); ?>
    </td>
    <td>
        <?php echo $xform->input('seconds',array('type' => 'text','class' =>'width40 validate[custom[integer]]')); ?>
    </td>
    <td>
        <?php echo $form->input('time_profile_id',array('type' => 'select','options' => $time_profile,'class' => 'width120',
            'label' => false,'div' => false)); ?>
    </td-->
    <td class="last">
        <a id="save" href="javascript:void(0)" title="<?php __('save'); ?>">
            <i class="icon-save"></i>
        </a>
        <a id="delete" title="<?php __('cancel'); ?>">
            <i class="icon-remove"></i>
        </a>
    </td>
</tr>