<table class="form table table-condensed dynamicTable tableTools table-bordered ">
    <colgroup>
        <col width="40%">
        <col width="60%">
    </colgroup>
    <tr>
        <td class="align_right padding-r20">
            <?php __('Multiple Sheet'); ?>
        </td>
        <td>
            <?php
            $val = $this->data['multiple_sheet'] ? 1 : 0;
            echo $form->input('multiple_sheet', array('options' => $multiple_sheet, 'value' => $val, 'label' => false, 'div' => false,
                'type' => 'select', 'class' => 'input in-text in-select no-select2'))
            ?>
        </td>
    </tr>
    <tr>
        <td class="align_right padding-r20">
            <?php __('Tab Name'); ?>
        </td>
        <td>
            <?php
            echo $form->input('tab_index', array('options' => $tab_index, 'label' => false, 'div' => false,
                'type' => 'select', 'class' => 'input in-text in-select no-select2'))
            ?>
        </td>
    </tr>
    <tr>
        <td class="align_right padding-r20">
            <?php __('Filter By'); ?>
        </td>
        <td>
            <?php
            echo $form->input('filter_by', array('options' => $filter_by, 'label' => false, 'div' => false,
                'type' => 'select', 'class' => 'input in-text in-select no-select2'))
            ?>
        </td>
    </tr>
    <tr>
        <td class="align_right padding-r20">
            <?php __('Filter Column'); ?>
        </td>
        <td>
            <?php
            echo $form->input('filter_col', array('options' => $position, 'label' => false, 'div' => false,
                'type' => 'select', 'class' => 'input in-text in-select no-select2'))
            ?>
        </td>
    </tr>
    <tr>
        <td class="align_right padding-r20"><?php echo __('Filter Text') ?></td>
        <td>
            <?php
            echo $form->input('filter_val', array('label' => false, 'div' => false, 'class' => 'width220'))
            ?>
        </td>
    </tr>
    <tr>
        <td class="align_right padding-r20">
            <?php __('Code Deck'); ?>
        </td>
        <td>
            <?php
            echo $form->input('code_deck', array('options' => $code_decks, 'label' => false, 'div' => false,
                'type' => 'select', 'class' => 'input in-text in-select no-select2'))
            ?>
        </td>
    </tr>
    <tr>
        <td class="align_right padding-r20"><?php echo __('Code Prefix') ?></td>
        <td>
            <?php
            echo $form->input('add_code_front', array('label' => false, 'div' => false, 'class' => 'width220 validate[custom[onlyNumber]]'))
            ?>
        </td>
    </tr>
    <tr>
        <td class="align_right padding-r20"><?php echo __('Format') ?> </td>
        <td>
            <?php
            echo $form->input('file_type', array('options' => $file_format, 'label' => false, 'div' => false,
                'type' => 'select', 'class' => 'input in-text in-select no-select2'))
            ?>
        </td>
    </tr>
    <tr>
        <td class="align_right padding-r20"><?php echo __('Subject Keyword', true); ?>  </td>
        <td> <?php echo $form->input('subject_keyword', array('label' => false, 'div' => false,
                'class' => 'width220' )) ?></td>
    </tr>
    <tr>
        <td class="align_right padding-r20"><?php echo __('Read Effective Date from') ?> </td>
        <td>
            <?php
            echo $form->input('read_effective_rate_from_subject', array('options' => $effective_date_from, 'label' => false, 'div' => false,
                'type' => 'select', 'class' => 'input in-text in-select no-select2'))
            ?>
        </td>
    </tr>
    <tr style="display:none;">
        <td class="align_right padding-r20"><?php echo __('Keyword in Effective Date line', true); ?>  </td>
        <td> <?php echo $form->input('effect_rate_keyword', array('label' => false, 'div' => false,
                'class'=> 'width220' )) ?></td>
    </tr>

    <tr>
        <td class="align_right padding-r20"><?php echo __('Start From Row') ?> </td>
        <td>
            <?php
            echo $form->input('start_from_row', array('options' => $start_from, 'label' => false, 'div' => false,
                'type' => 'select', 'class' => 'input in-text in-select'))
            ?>
        </td>
    </tr>


    <tr>
        <td class="align_right padding-r20"><?php echo __('End To Row') ?></td>
        <td>
            <?php
            echo $form->input('end_to_row', array('label' => false, 'div' => false, 'class' => 'width220 validate[custom[onlyNumber]]'))
            ?>
        </td>
    </tr>

    <tr>
        <td class="align_right padding-r20"><?php echo __('Rate') ?> </td>
        <td>
            <?php
            echo $form->input('rate_col', array('options' => $position_opt, 'label' => false, 'div' => false,
                'type' => 'select', 'class' => 'input in-text in-select'))
            ?>
        </td>
    </tr>

    <tr>
        <td class="align_right padding-r20"><?php echo __('Code') ?> </td>
        <td>
            <?php
            echo $form->input('code_col', array('options' => $position_opt, 'label' => false, 'div' => false,
                'type' => 'select', 'class' => 'input in-text in-select'))
            ?>
        </td>
    </tr>

    <tr>
        <td class="align_right padding-r20"><?php echo __('Code Name') ?> </td>
        <td>
            <?php
            echo $form->input('code_name_col', array('options' => $position_opt, 'label' => false, 'div' => false,
                'type' => 'select', 'class' => 'input in-text in-select'))
            ?>
        </td>
    </tr>

    <tr>
        <td class="align_right padding-r20"><?php echo __('Country') ?> </td>
        <td>
            <?php
            echo $form->input('country_col', array('options' => $position_opt, 'label' => false, 'div' => false,
                'type' => 'select', 'class' => 'input in-text in-select'))
            ?>
        </td>
    </tr>

    <tr>
        <td class="align_right padding-r20"><?php echo __('Effective Date') ?> </td>
        <td>
            <?php
            echo $form->input('effective_date_col', array('options' => $position_opt, 'label' => false, 'div' => false,
                'type' => 'select', 'class' => 'input in-text in-select'))
            ?>
        </td>
    </tr>

    <tr>
        <td class="align_right padding-r20"><?php echo __('End Date') ?> </td>
        <td>
            <?php
            echo $form->input('end_date_col', array('options' => $position_opt, 'label' => false, 'div' => false,
                'type' => 'select', 'class' => 'input in-text in-select'))
            ?>
        </td>
    </tr>

    <tr>
        <td class="align_right padding-r20">
            <?php __('Separate Date Time'); ?>
        </td>
        <td>
            <?php
            $val = $this->data['separate_date_time'] ? 1 : 0;
            echo $form->input('separate_date_time', array('options' => $multiple_sheet, 'value' => $val, 'label' => false, 'div' => false,
                'type' => 'select', 'class' => 'input in-text in-select no-select2'))
            ?>
        </td>
    </tr>

    <tr>
        <td class="align_right padding-r20"><?php echo __('Timezone') ?> </td>
        <td>
            <?php
            echo $form->input('time_zone_col', array('options' => $position_opt, 'label' => false, 'div' => false,
                'type' => 'select', 'class' => 'input in-text in-select'))
            ?>
        </td>
    </tr>

    <tr>
        <td class="align_right padding-r20"><?php echo __('Inter Rate') ?> </td>
        <td>
            <?php
            echo $form->input('inter_rate_col', array('options' => $position_opt, 'label' => false, 'div' => false,
                'type' => 'select', 'class' => 'input in-text in-select'))
            ?>
        </td>
    </tr>

    <tr>
        <td class="align_right padding-r20"><?php echo __('Intra Rate') ?> </td>
        <td>
            <?php
            echo $form->input('intra_rate_col', array('options' => $position_opt, 'label' => false, 'div' => false,
                'type' => 'select', 'class' => 'input in-text in-select'))
            ?>
        </td>
    </tr>

    <tr>
        <td class="align_right padding-r20"><?php echo __('Local Rate') ?> </td>
        <td>
            <?php
            echo $form->input('local_rate_col', array('options' => $position_opt, 'label' => false, 'div' => false,
                'type' => 'select', 'class' => 'input in-text in-select'))
            ?>
        </td>
    </tr>

    <tr>
        <td class="align_right padding-r20"><?php echo __('Status') ?> </td>
        <td>
            <?php
            echo $form->input('rate_status_col', array('options' => $position_opt, 'label' => false, 'div' => false,
                'type' => 'select', 'class' => 'input in-text in-select'))
            ?>
        </td>
    </tr>

    <tr>
        <td class="align_right padding-r20"><?php echo __('Support Multiple Codes in one field') ?>  </td>
        <td>
            <?php
            $st = array('false' => __('No', true), 'true' => __('Yes', true));
            $value = $this->data['multiple_codes'] ? 'true' : 'false';

            echo $form->input('multiple_codes', array('options' => $st, 'value' => $value, 'label' => false, 'div' => false,
                'type' => 'select', 'class' => 'input in-text in-select no-select2'))
            ?>
            <?php echo $form->input('code_delimiter', array('label' => false, 'div' => false,
                'maxLength' => '5', 'style'=> 'width:20px;')) ?>
        </td>
    </tr>

    <tr>
        <td class="align_right padding-r20"><?php echo __('All info at single column') ?>  </td>
        <td>
            <?php
            $val = $this->data['all_info_at_single_column'] ? 1 : 0;
            echo $form->input('all_info_at_single_column', array('options' => $multiple_sheet, 'value' => $val, 'label' => false, 'div' => false,
                'type' => 'select', 'class' => 'input in-text in-select no-select2'))
            ?>
        </td>
    </tr>

    <tr>
        <td class="align_right padding-r20"><?php echo __('Effective date at single cell') ?>  </td>
        <td>
            <?php
            $val = $this->data['effective_date_at_single_cell'] ? 1 : 0;
            echo $form->input('effective_date_at_single_cell', array('options' => $multiple_sheet, 'value' => $val, 'label' => false, 'div' => false,
                'type' => 'select', 'class' => 'input in-text in-select no-select2'))
            ?>
            <?php echo $form->input('effective_date_cell', array('label' => false, 'div' => false, 'class' => 'width220 validate[custom[separatedNumbers]' , 'style'=>'margin-left:10px;' )) ?>
        </td>
    </tr>

    <tr>
        <td class="align_right padding-r20"><?php echo __('Support code in multiple columns') ?></td>
        <td>
            <?php
            echo $form->input('code_in_two_columns', array('label' => false, 'div' => false, 'class' => 'width220 validate[custom[separatedNumbers]]'))
            ?>
        </td>
    </tr>

    <tr>
        <td class="align_right padding-r20"><?php echo __(' Date Pattern') ?>  </td>
        <td>
            <select class="date_pattern">
                <option value=""></option>
                <?php foreach($date_pattern as $date_p): ?>
                    <option value="<?php echo $date_p;?>" <?php if(isset($this->data['date_pattern']) && $this->data['date_pattern'] == $date_p){echo 'selected';} ?>><?php echo $date_p;?></option>
                <?php endforeach;?>
                <?php
                echo $form->input('date_pattern', array('label' => false, 'div' => false, 'class' => 'width220' , 'style'=>'margin-left:10px;' ))
                ?>
                <span style="margin-bottom: 10px; display: inline-block;">Please use <b style="font-size: 16px; color: red;">;</b> to separate patterns</span>
        </td>
    </tr>
    <tr>
        <td class="align_right padding-r20"><?php echo __('Download link in email content') ?>  </td>
        <td>
            <div>
                <?php
                echo $form->checkbox('is_link', array('label' => false, 'div' => false, 'style'=>'margin: 0 0 7px 0;'));
                echo $form->input('link_text', array('label' => false, 'div' => false, 'class' => 'width220' , 'style'=>'margin-left:10px;' ))
                ?>
            </div>
        </td>
    </tr>
</table>

<div class="center">
    <a step="#step1" href=""  data-toggle="tab" value="next"  id="previous2" class=" btn primary"><?php __('Previous')?></a>
    <a value="next" id="next2" data-toggle="tab" href=""  class="input in-submit btn btn-primary"><?php __('Next')?></a>
</div>

<script type="text/javascript">
    $(function() {
        $('#multiple_sheet').on('change', function(){
            if ($(this).val() == false) {
                $('#tab_index').closest('tr').show();
            }else{
                $('#tab_index').closest('tr').hide();
            }
        }).change();

        $('#effective_date_at_single_cell').on('change', function(){
            if ($(this).val() == 1) {
                $('#effective_date_cell').show();
            }else{
                $('#effective_date_cell').hide();
            }
        }).change();

        $('#filter_by').on('change', function(){
            if ($(this).val() == 2) {
                $('#filter_col').closest('tr').show();
            }else{
                $('#filter_col').closest('tr').hide();
            }
        }).change();

        $("#next2").click(function() {
            var filterType = $('#file_type').val();
            var multipleCodes = $('#multiple_codes').val();
            var codeDelimiter = $('#code_delimiter').val();

            if (multipleCodes == 'true' && filterType == 2 && codeDelimiter == ',') {
                showMessages_new('[{code: 101, msg: \'Code delimiter should be different from file delimiter\'}]');
            } else {
                $("#step3").click();
            }
        });
        $("#previous2").click(function() {
            $("#step1").click();
        });
        $("#step3").click(function() {
            var flg = $("#step_").val();
            if(flg > 3)
            {
                $("#step_").val(3);
                return true;
            }
            if ($("#rule_form").validationEngine('validate'))
            {
                $("#step_").val(4);
                return true;
            }
            return false;
        });
    });
</script>