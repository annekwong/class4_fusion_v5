<div class="separator"></div>
<table class="table table-bordered">
    <tr>
        <td class="align_right"><?php echo __('Min ASR') ?>:</td>
        <td>
            <?php echo $form->input('min_asr', array('label' => false, 'div' => false, 'type' => 'text',
                'class' => 'input in-text validate[integer,max[100]] width80')); ?>
        </td>
        <td class="align_right"><?php echo __('Max ASR') ?>:</td>
        <td>
            <?php echo $form->input('max_asr', array('label' => false, 'div' => false, 'type' => 'text',
                'class' => 'input in-text validate[integer,max[100]] width80')); ?>
        </td>
    </tr>
    <tr>
        <td class="align_right"><?php echo __('Min ABR') ?>:</td>
        <td>

            <?php echo $form->input('min_abr', array('label' => false, 'div' => false, 'type' => 'text',
                'class' => 'input in-text validate[integer,max[100]] width80')); ?>
        </td>
        <td class="align_right width80"><?php echo __('Max ABR') ?>:</td>
        <td>
            <?php echo $form->input('max_abr', array('label' => false, 'div' => false, 'type' => 'text',
                'class' => 'input in-text validate[integer,max[100]] width80')); ?>
        </td>
    </tr>
    <tr>
        <td class="align_right"><?php echo __('Min ACD') ?>:</td>
        <td>
            <?php echo $form->input('min_acd', array('label' => false, 'div' => false, 'type' => 'text',
                'class' => 'input in-text validate[integer] width80')); ?>
        </td>
        <td class="align_right"><?php echo __('Max ACD') ?>:</td>
        <td>

            <?php echo $form->input('max_acd', array('label' => false, 'div' => false, 'type' => 'text',
                'class' => 'input in-text validate[integer] width80')); ?>
        </td>
    </tr>

    <tr>
        <td class="align_right"><?php echo __('Min PDD') ?>:</td>
        <td>
            <?php echo $form->input('min_pdd', array('label' => false, 'div' => false, 'type' => 'text',
                'class' => 'input in-text validate[integer] width80')); ?>
        </td>
        <td class="align_right"><?php echo __('Max PDD') ?>:</td>
        <td>

            <?php echo $form->input('min_pdd', array('label' => false, 'div' => false, 'type' => 'text',
                'class' => 'input in-text validate[integer] width80')); ?>
        </td>
    </tr>
    <tr>
        <td class="align_right"><?php echo __('Min ALOC') ?>:</td>
        <td>
            <?php echo $form->input('min_aloc', array('label' => false, 'div' => false, 'type' => 'text',
                'class' => 'input in-text validate[integer] width80')); ?>
        </td>
        <td class="align_right"><?php echo __('Max ALOC') ?>:</td>
        <td>

            <?php echo $form->input('max_aloc', array('label' => false, 'div' => false, 'type' => 'text',
                'class' => 'input in-text validate[integer] width80')); ?>
        </td>
    </tr>

    <tr>
        <td class="align_right"><?php echo __('Max Price') ?>:</td>
        <td>

            <?php echo $form->input('max_price', array('label' => false, 'div' => false, 'type' => 'text',
                'class' => 'input in-text validate[integer] width80')); ?>
        </td>
        <td></td>
        <td></td>
    </tr>
</table>

