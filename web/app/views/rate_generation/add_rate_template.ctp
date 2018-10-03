<style>
    input{width: 220px;}
    select,textarea, input[type="text"]{margin-bottom: 0}
    .width25{width: 25px;}
    .width80{width: 80px;}
    .ms-container ul.ms-list{
        width: 280px;
    }
    .ms-container{
        background: transparent url('<?php echo $this->webroot; ?>common/theme/scripts/plugins/forms/multiselect/img/switch.png') no-repeat 290px 80px;
    }
</style>
<?php if ( !$appCommon->_get('is_ajax') ): ?>
<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><a href="<?php echo $this->webroot ?>rate_generation/rate_template">
        <?php __('Tool') ?></a></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li>
        <a href="<?php echo $this->webroot . $this->params['url']['url'] ?>">
        <?php
        if(isset($this->params['pass'][0]))
            __('Edit Rate Generation Template');
        else
            __('Create New Rate Generation Template');
        ?>
        </a>
    </li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php __('Rate Generation') ?></h4>

</div>
<div class="separator bottom"></div>
<div class="buttons pull-right newpadding">
    <a class="btn btn-icon btn-inverse glyphicons circle_arrow_left" href="<?php echo $this->webroot; ?>rate_generation/index"><i></i><?php __('Back') ?></a>
</div>
<div class="clearfix"></div>
<?php endif; ?>
<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <?php if ( $appCommon->_get('is_ajax') ): ?>
            <div class="separator"></div>
        <?php endif; ?>
        <div class="widget-body">
            <form method="post" id="myform">
                <table class="form table table-condensed tableTools table-bordered ">
                    <tbody>
                    <colgroup>
                        <col width="30%">
                        <col width="70%">
                    </colgroup>
                    <tr>
                        <td class="right"><?php __('Rate Template Name'); ?> </td>
                        <td>
                            <input type="text" name="name" id="template_name" <?php if (isset($data['RateGenerationTemplate']['name'])):
                            ?>value="<?php echo $data['RateGenerationTemplate']['name']; ?>"<?php endif; ?> class="validate[required,custom[onlyLetterNumberLineSpace]]" />
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php __('Include Blocked Route'); ?> </td>
                        <td>
                            <input type="checkbox" name="include_blocked_route" <?php if (isset($data['RateGenerationTemplate']['include_blocked_route']) && $data['RateGenerationTemplate']['include_blocked_route']): ?> checked="checked"<?php endif; ?> />
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php __('Include Local Rate'); ?> </td>
                        <td>
                            <input type="checkbox" name="include_local_rate" <?php if (isset($data['RateGenerationTemplate']['include_local_rate']) && $data['RateGenerationTemplate']['include_local_rate']): ?> checked="checked"<?php endif; ?> />
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php __('Rate Table') ?> </td>
                        <td>
                            <select name="rate_table_type" >
                                <?php foreach ($rate_table_type as $key => $rate_table_type_item): ?>
                                    <option value="<?php echo $key; ?>" <?php if (isset($data['RateGenerationTemplate']['rate_table_type']) && !strcmp($data['RateGenerationTemplate']['rate_table_type'], $key)): ?> selected="selected"<?php endif; ?>>
                                        <?php echo $rate_table_type_item; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php echo __('Calculate IJ Rate based on') ?> </td>
                        <td >
                            <?php echo $form->input('',array('name' =>'ij_rate_type','type'=> 'select','options'=> $ij_rate_type,'label'=>false,'div'=>false,'selected' => isset($data['RateGenerationTemplate']['ij_rate_type']) ? $data['RateGenerationTemplate']['ij_rate_type'] : '')); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php echo __('Default rate if no egress is available') ?> </td>
                        <td >
                            <input type="text" class="validate[required,custom[positiveNumber]]" name="default_rate" value="<?php echo isset($data['RateGenerationTemplate']['default_rate']) ? $data['RateGenerationTemplate']['default_rate'] : ''; ?>"/>
                            <?php __('USD'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php echo __('Decimal Places') ?> </td>
                        <td >
                            <?php echo $form->input('',array('name' =>'decimal_places','type'=> 'select','options'=> $decimal_places_arr,'label'=>false,'div'=>false,'selected' => isset($data['RateGenerationTemplate']['decimal_places']) ? $data['RateGenerationTemplate']['decimal_places'] : '')); ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="right"><?php __('Code Deck') ?> </td>
                        <td>
                            <select name="code_deck_id" >
                                <?php foreach ($code_deck as $code_deck_id => $code_deck_name): ?>
                                    <option value="<?php echo $code_deck_id; ?>" <?php if (isset($data['RateGenerationTemplate']['code_deck_id']) && !strcmp($data['RateGenerationTemplate']['code_deck_id'], $code_deck_id)): ?> selected="selected"<?php endif; ?>>
                                        <?php echo $code_deck_name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <?php
                    $egress_arr = array();
                    if(isset($data['RateGenerationTemplate']['egress_str'])){
                        $egress_arr = explode(";", $data['RateGenerationTemplate']['egress_str']);
                    }
                    ?>
                    <tr>
                        <td class="right"><?php __('Vendors') ?> </td>
                        <td>
                            <select multiple="multiple" id="egress_select" name="egress_id[]" class="width220 validate[required]" >
                                <?php foreach($egresses_info as $client_name=>$egress_info): ?>
                                    <optgroup label="<?php echo $client_name; ?>">
                                        <?php foreach($egress_info as $egress_id=>$egress_name): ?>
                                            <option value="<?php echo $egress_id ?>" <?php if (in_array($egress_id,$egress_arr)): ?> selected="selected"<?php endif; ?>>
                                                <?php echo $egress_name ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php echo __('Calculate rate based on LCR') ?> </td>
                        <td>
                            <?php echo $form->input('',array('name' =>'lcr_digit','type'=> 'select','options'=> $lcr_arr,'label'=>false,'div'=>false,'selected' => isset($data['RateGenerationTemplate']['lcr_digit']) ? $data['RateGenerationTemplate']['lcr_digit'] : '')); ?>
                            <!--                            <input type="text" class="validate[required,custom[onlyNumberSp],min[1],max[10]] width25" maxlength="2" name="cheap_digit" value="--><?php //echo isset($data['RateGenerationTemplate']['cheap_digit']) ? $data['RateGenerationTemplate']['cheap_digit'] : ''; ?><!--"/>-->
                        </td>
                    </tr>
                    <tr>
                        <td class="right"><?php echo __('Effective Date') ?> </td>
                        <td>
                            <?php echo $form->input('',array('name' =>'effective_days','type'=> 'select','options'=> $effective_arr,'label'=>false,'div'=>false,'selected' => isset($data['RateGenerationTemplate']['effective_days']) ? $data['RateGenerationTemplate']['effective_days'] : 0)); ?>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <!--margin start-->
                <div class="widget-body">
                    <a id="add_margin" class="btn btn-primary btn-icon glyphicons circle_plus" href="javascript:void(0)">
                        <i></i>
                        <?php __('ADD Margin'); ?>
                    </a>
                </div>
                <table class="footable table table-striped tableTools table-bordered  table-white table-primary default footable-loaded">
                    <thead>
                    <tr>
                        <th colspan="2"><?php __('Rate Range'); ?></th>
                        <th rowspan="2"><?php __('Define Margin By'); ?></th>
                        <th class="markup_head" rowspan="2"><?php __('Mark Up( % )'); ?></th>
                        <th rowspan="2"><?php __('Action'); ?></th>
                    </tr>
                    <tr>
                        <th><?php __('Min'); ?></th>
                        <th><?php __('Max'); ?></th>
                    </tr>
                    </thead>
                    <tbody id="margin_table">
                    <tr class="margin_table_tr">
                        <td><input type="text" name="margin[min_rate][]"  class="validate[required,custom[positiveNumber]] width80"  /></td>
                        <td><input type="text" name="margin[max_rate][]"  class="validate[required,custom[positiveNumber]] width80" /></td>
                        <td>
                            <select class="markup_type_select" name="margin[markup_type][]" >
                                <option value="1" ><?php __('Percentage'); ?></option>
                                <option value="2" ><?php __('Fix Value'); ?></option>
                            </select>
                        </td>
                        <td><input type="text" name="margin[markup_value][]" class="validate[required,custom[positiveNumber]] width80" /></td>
                        <td>
                            <a title="Remove" href="javascript:void(0)" onclick="$(this).closest('tr').remove();" style="margin-left: 20px;">
                                <i class="icon-remove"></i>
                            </a>
                        </td>
                    </tr>
                    <?php foreach ($margin_data as $margin_data_item): ?>
                        <tr class="margin_table_tr">
                            <td><input type="text" name="margin[min_rate][]" value="<?php echo $margin_data_item['RateGenerationTemplateMargin']['min_rate'] ?>"  class="validate[required,custom[positiveNumber]] width80"  /></td>
                            <td><input type="text" name="margin[max_rate][]" value="<?php echo $margin_data_item['RateGenerationTemplateMargin']['max_rate'] ?>"  class="validate[required,custom[positiveNumber]] width80" /></td>
                            <td>
                                <select name="margin[markup_type][]" >
                                    <option value="1" <?php if (!strcmp($margin_data_item['RateGenerationTemplateMargin']['markup_type'], "1")): ?> selected="selected"<?php endif; ?>>
                                        <?php __('Percentage'); ?>
                                    </option>
                                    <option value="2" <?php if (!strcmp($margin_data_item['RateGenerationTemplateMargin']['markup_type'], "2")): ?> selected="selected"<?php endif; ?>>
                                        <?php __('Fix Value'); ?>
                                    </option>
                                </select>
                            </td>
                            <td><input type="text" name="margin[markup_value][]" value="<?php echo $margin_data_item['RateGenerationTemplateMargin']['markup_value'] ?>" class="validate[required,custom[positiveNumber]] width80" /></td>
                            <td>
                                <a title="Remove" href="javascript:void(0)" onclick="$(this).closest('tr').remove();" style="margin-left: 20px;">
                                    <i class="icon-remove"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tbody>
                    <tr>
                        <td colspan="2"><?php __('Default'); ?></td>
                        <td>
                            <select name="margin_default_type" >
                                <option value="1" <?php if (isset($data['RateGenerationTemplate']['margin_default_type']) && !strcmp($data['RateGenerationTemplate']['margin_default_type'], 1)): ?> selected="selected"<?php endif; ?>>
                                    <?php __('Percentage'); ?>
                                </option>
                                <option value="2" <?php if (isset($data['RateGenerationTemplate']['margin_default_type']) && !strcmp($data['RateGenerationTemplate']['margin_default_type'], 2)): ?> selected="selected"<?php endif; ?>>
                                    <?php __('Fix Value'); ?>
                                </option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="margin_default_value" value="<?php echo isset($data['RateGenerationTemplate']['margin_default_value']) ? $data['RateGenerationTemplate']['margin_default_value'] : ''; ?>" class="validate[required,custom[positiveNumber]] width80"/>
                        </td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
                <!--margin end-->

                <!--Interval and Min Time start-->

                <div class="widget-body">
                    <a id="add_interval_mintime" class="btn btn-primary btn-icon glyphicons circle_plus" href="javascript:void(0)">
                        <i></i>
                        <?php __('ADD Interval and Min Time'); ?>
                    </a>
                </div>
                <table class="footable table table-striped tableTools table-bordered  table-white table-primary default footable-loaded">
                    <thead>
                    <tr>
                        <th><?php __('Code'); ?></th>
                        <th><?php __('Interval'); ?>(s)</th>
                        <th><?php __('Min Time'); ?>(s)</th>
                        <th><?php __('Action'); ?></th>
                    </tr>
                    </thead>
                    <tbody id="interval_mintime_table">
                    <tr class="interval_mintime_table_tr">
                        <td><input type="text" name="interval_mintime[code][]"  class="validate[required] width80 code_tr" /></td>
                        <td><input type="text" name="interval_mintime[rate_interval][]" class="validate[required,custom[onlyNumberSp]] width80"  /></td>
                        <td><input type="text" name="interval_mintime[min_time][]" class="validate[required,custom[onlyNumberSp]] width80"  /></td>
                        <td>
                            <a title="Remove" href="javascript:void(0)" onclick="$(this).closest('tr').remove();" style="margin-left: 20px;">
                                <i class="icon-remove"></i>
                            </a>
                        </td>
                    </tr>
                    <?php foreach ($detail_data as $detail_data_item): ?>
                        <tr class="interval_mintime_table_tr">
                            <td><input type="text" name="interval_mintime[code][]" value="<?php echo $detail_data_item['RateGenerationTemplateDetail']['code'] ?>"  class="validate[required] width80 code_tr" /></td>
                            <td><input type="text" name="interval_mintime[rate_interval][]" value="<?php echo $detail_data_item['RateGenerationTemplateDetail']['rate_interval'] ?>" class="validate[required,custom[onlyNumberSp]] width80"  /></td>
                            <td><input type="text" name="interval_mintime[min_time][]" value="<?php echo $detail_data_item['RateGenerationTemplateDetail']['min_time'] ?>" class="validate[required,custom[onlyNumberSp]] width80"  /></td>
                            <td>
                                <a title="Remove" href="javascript:void(0)" onclick="$(this).closest('tr').remove();" style="margin-left: 20px;">
                                    <i class="icon-remove"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tbody>
                    <tr>
                        <td>
                            <?php __('Default'); ?>
                        </td>
                        <td>
                            <input type="text" name="default_interval" value="<?php echo isset($data['RateGenerationTemplate']['default_interval']) ? $data['RateGenerationTemplate']['default_interval'] : ''; ?>" class="validate[required,custom[onlyNumberSp]] width80" />
                        </td>
                        <td>
                            <input type="text" name="default_min_time" value="<?php echo isset($data['RateGenerationTemplate']['default_min_time']) ? $data['RateGenerationTemplate']['default_min_time'] : ''; ?>" class="validate[required,custom[onlyNumberSp]] width80" />
                        </td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>

                <!--Interval and Min Time end-->
                <?php if ( !$appCommon->_get('is_ajax') ): ?>
                <table class="table" >
                    <tr>
                        <td class="buttons-group center">
                            <input type="button" id="myform_sub" value="<?php __('Submit') ?>" class="btn btn-primary"/>
                            <input type="reset"  value="<?php __('Revert') ?>" class="btn btn-default" />
                        </td>
                    </tr>
                </table>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>



<script type="text/javascript">

    $(function() {
        var id = "<?php echo isset($this->params['pass'][0]) ? $this->params['pass'][0] : ""; ?>";
        $("#egress_select").multiSelect({
            selectableOptgroup: true,
            buttonWidth: 400
        });

        $('select[name="rate_table_type"]').on('change', function(){
            $('select[name="ij_rate_type"]').closest('tr').hide();
            $('select[name="code_deck_id"]').closest('tr').hide();

            let rate_table_type = $(this).val();
            if(rate_table_type == 0 || rate_table_type == 2){
                $('select[name="code_deck_id"]').closest('tr').show();
            }
        }).trigger('change');

        $("#egress_select").on('change', function(){
            let lcr_count = $(this).val() ? $(this).val().length : 1;
            let sel = "<select>";
            for( let i = 1; i <= lcr_count; i ++ ){
                sel += "<option value=" + i + ">" + i + "</option>";
            }
            sel += "</select>";
            $('select[name="lcr_digit"]').html(sel);
        });

        if(!id){
            $("#egress_select").trigger('change');
        }

        var margin_table_tr = jQuery('.margin_table_tr').eq(0).remove();
        jQuery('a[id=add_margin]').click(function() {
            margin_table_tr.clone(true).prependTo('#margin_table');
        });

        var interval_mintime_table_tr = jQuery('.interval_mintime_table_tr').eq(0).remove();
        jQuery('a[id=add_interval_mintime]').click(function() {
            interval_mintime_table_tr.clone(true).prependTo('#interval_mintime_table');
        });

        $("#myform_sub").click(function() {
            var name = $("#template_name").val();
            var id = "<?php echo isset($this->params['pass'][0]) ? $this->params['pass'][0] : ""; ?>";
            if (!name)
            {
                jGrowl_to_notyfy('<?php __('Rate Template Name cannot be empty!'); ?>', {theme: 'jmsg-error'});
                return false;
            }
            $.ajax({
                'url': '<?php echo $this->webroot ?>rate_generation/judge_template_name/' + name + '/' + id,
                'type': 'POST',
                'dataType': 'json',
                'data': {'is_ajax': '1'},
                'success': function(data) {
                    if (data)
                        jGrowl_to_notyfy('<?php __('Template name is already in use!'); ?>', {theme: 'jmsg-error'});
                    else
                        $("#myform").submit();
                }
            });
        });

        $("#myform").submit(function() {
            var code_dulp = 0;
            $(".code_tr").each(function(i) {
                var $this_value = $(this).val();
                var flg = 0;
                $(".code_tr").each(function(j) {
                    if (i >= j)
                        return true;
                    if ($this_value == $(this).val())
                    {
                        flg = 1;
                        return false;
                    }
                });
                if (flg == 1)
                    code_dulp = 1;
                return false;
            });
            if (code_dulp)
            {
//                failed
                jGrowl_to_notyfy('<?php __('Exist Duplicate code'); ?>', {theme: 'jmsg-error'});
                return false;
            }

            var rate_range_dulp = 0;
            var rate_larger_flg = 0;
            $(".margin_table_tr").each(function(i) {
                var rate_min = $(this).children().eq(0).children().eq(0).val();
                var rate_max = $(this).children().eq(1).children().eq(0).val();
                if (parseInt(rate_min) > parseInt(rate_max))
                {
                    rate_larger_flg = 1;
                    return false;
                }
                var flg1 = 0;
                $(".margin_table_tr").each(function(j) {
                    if (i >= j)
                        return true;
                    var new_rate_min = $(this).children().eq(0).children().eq(0).val();
                    var new_rate_max = $(this).children().eq(1).children().eq(0).val();
                    if (judge_Interval_dulp(rate_min, rate_max, new_rate_min, new_rate_max))
                    {
                        flg1 = 1;
                        return false;
                    }
                });
                if (flg1 == 1)
                    rate_range_dulp = 1;
                return false;
            });
            if (!$('#egress_select').val())
            {
                jGrowl_to_notyfy('<?php __('Vendors cannot be empty!'); ?>', {theme: 'jmsg-error'});
                return false;
            }
            if (rate_larger_flg)
            {
                jGrowl_to_notyfy('<?php __('Rate min cannot be larger than rate max'); ?>', {theme: 'jmsg-error'});
                return false;
            }
            if (rate_range_dulp)
            {
//                failed
                jGrowl_to_notyfy('<?php __('Exist Duplicate rate range'); ?>', {theme: 'jmsg-error'});
                return false;
            }
            return true;
        });

    });

    function judge_Interval_dulp(rate_min, rate_max, new_rate_min, new_rate_max)
    {
        var begin = Math.max(rate_min, new_rate_min);
        var end = Math.min(rate_max, new_rate_max);
        var flg = Number(end) - Number(begin);
        if (flg >= 0)
        {
//            dulp
            return 1;
        }
        else
            return 0;
    }

    $('select[name="margin_default_type"]').on('change', function(){
       if($(this).val() == 1){
          $('.markup_head').text("Mark Up( % )");
       }else{
          $('.markup_head').text("Mark Up( USD )");
       }
    })

</script>




