<?php $post_data['include'] = isset($post_data['include'])? $post_data['include'] : ""; 
    $post_data['exclude'] = isset($post_data['exclude'])? $post_data['exclude'] : "";
?>
<!--<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.0.8/semantic.css">-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.0.8/semantic.js"></script>-->
<table class="form table dynamicTable tableTools table-bordered  table-white">
    <tbody>
        <tr>
            <th width="30%" style="text-align: right;"><?php __('Monitoring Rule Name')?>: </th>
            <th width="30%">
                <input  type="text" id='rule_name' rule_id="<?php echo $post_data['id']; ?>" value="<?php echo $post_data['rule_name']; ?>" name="AlertRules[rule_name]" class="validate[required,custom[onlyLetterNumberLine]]" />
            </th>
            <th></th>
        </tr>
        <tr>
            <td width="30%" style="text-align: right;">
                <?php __('Monitoring On')?> :
            </td>
            <td width="30%">
                <select name="AlertRules[trunk_type]" id="trunk_type">
                    <option value="1"<?php
                    if ($post_data['trunk_type'] == 1)
                    {
                        ?> selected="selected" <?php } ?>><?php __('Ingress Trunks')?></option>
                    <option value="2"<?php
                    if ($post_data['trunk_type'] == 2)
                    {
                        ?> selected="selected" <?php } ?>><?php __('Egress Trunks')?></option>
                </select>
            </td>
            <td width="40%">
                <span id="ingress_trunk" class="trunk_type">
                    <select name="AlertRules[ingress_trunk][]" class="" multiple="multiple" style="width:250px;height:400px;" >
                        <?php
                        foreach ($ingress_trunk as $key => $item)
                        {
                            ?>
                            <option  class="option" value="<?php echo $key; ?>"<?php
                            if (in_array($key, $post_data['ingress_trunk']) || ($post_data['trunk_type'] == 1 && $post_data['all_trunk'] ))
                            {
                                ?> selected="selected" <?php } ?>><?php echo $item; ?></option>
                        <?php } ?>
                    </select>
                </span>
                <span class="trunk_type" id="egress_trunk" <?php if($post_data['trunk_type']  != 2){ ?>style="display: none;" <?php }?>>
                    <select name="AlertRules[egress_trunk][]" class="" multiple="multiple" style="width:250px;height:400px;">
                        <?php
                        foreach ($egress_trunk as $key => $item)
                        {
                            ?>
                            <option class="option" value="<?php echo $key; ?>"<?php
                            if (in_array($key, $post_data['egress_trunk']) || ($post_data['trunk_type'] == 2 && $post_data['all_trunk'] ))
                            {
                                ?> selected="selected" <?php } ?>><?php echo $item; ?></option>
                        <?php } ?>
                    </select>
                </span>
                <?php __('Select All')?> : <input type="checkbox" name="AlertRules[all_trunk]" <?php
                if ($post_data['all_trunk'])
                {
                    echo "checked='checked'";
                }
                ?> id="select_all" />
            </td>
        </tr>
        <tr>
            <td width="30%" style="text-align: right;">
                <?php __('Include')?> :
            </td>
            <td width="30%">
                <select name="AlertRules[include]"  id="include" >
                    <option value="0" <?php
                    if (!$post_data['include'])
                    {
                        echo "selected='selected'";
                    }
                    ?> ><?php __('All Code')?></option>
                    <option value="1" <?php
                    if ($post_data['include'] == 1)
                    {
                        echo "selected='selected'";
                    }
                    ?> ><?php __('Specific Codes')?></option>
                </select>
            </td>
            <td width="40%">
                <span <?php
                if ($post_data['include'] == 2 || !$post_data['include'])
                {
                    ?> class="hidden" <?php } ?> id="in_codes" >
                    &nbsp;&nbsp;
                    <input type="text" name="AlertRules[in_codes]" class="validate[required]" placeholder="Separated by ','" value="<?php echo $post_data['in_codes'] ?>"/>

                </span>
                <span <?php
                if ($post_data['include'] == 1 || !$post_data['include'])
                {
                    ?> class="hidden" <?php } ?> id="in_code_deck">
                    Code Deck:&nbsp;&nbsp;
                    <select name="AlertRules[in_code_deck]" class="validate[required]" id="in_code_deck_select">
                        <option value="" ></option>
                        <?php
                        foreach ($code_deck as $item)
                        {
                            ?>
                            <option value="<?php echo $item[0]['code_deck_id']; ?>"<?php
                            if ($post_data['in_code_deck'] == $item[0]['code_deck_id'])
                            {
                            ?>selected="selected"<?php } ?>><?php echo $item[0]['name']; ?></option>
                        <?php } ?>
                    </select>
                </span>
                <span <?php
                if ($post_data['include'] == 1 || !$post_data['include'])
                {
                    ?> class="hidden" <?php } ?> id="in_code_name">
                    <?php __('Code Name')?>:&nbsp;&nbsp;
                    <select name="AlertRules[in_code_name][]" class="validate[required]"  multiple="multiple" id="in_code_name_select">
                        <?php
                        foreach ($in_code_arr as $key => $item)
                        {
                            ?>
                            <option value="<?php echo $item[0]['name']; ?>"<?php
                            if (in_array($item[0]['name'], $post_data['in_code_name']))
                            {
                                ?>  selected="selected" <?php } ?>><?php echo $item[0]['name']; ?></option>
                        <?php } ?>
                    </select>
                </span>
            </td>
        </tr>


        <tr>
            <td width="30%" style="text-align: right;">
                Exclude :
            </td>
            <td width="30%">
                <select name="AlertRules[exclude]"  id="exclude" >
                    <option value="" ><?php __('Null')?></option>
                    <option value="1" <?php
                    if ($post_data['exclude'] == 1)
                    {
                        echo "selected='selected'";
                    }
                    ?> ><?php __('Specific Codes')?></option>
                    <!--                    <option value="2" --><?php
                    //                    if ($post_data['exclude'] == 2)
                    //                    {
                    //                        echo "selected='selected'";
                    //                    }
                    //                    ?><!-- >--><?php //__('Specific Code Names')?><!--</option>-->
                </select>
            </td>
            <td width="40%">
                <span <?php
                if ($post_data['exclude'] == 2 || !$post_data['exclude'])
                {
                    ?> class="hidden" <?php } ?> id="ex_codes" >
                    &nbsp;&nbsp;
                    <input type="text" name="AlertRules[ex_codes]" class="validate[required]" placeholder="Separated by ','" value="<?php echo $post_data['ex_codes'] ?>"/>

                </span>
                <span <?php
                if ($post_data['exclude'] == 1 || !$post_data['exclude'])
                {
                    ?> class="hidden" <?php } ?> id="ex_code_deck">
                    Code Deck:&nbsp;&nbsp;
                    <select name="AlertRules[ex_code_deck]" class="validate[required]" id="ex_code_deck_select">
                        <option value="" ></option>
                        <?php
                        foreach ($code_deck as $item)
                        {
                            ?>
                            <option value="<?php echo $item[0]['code_deck_id']; ?>"<?php
                            if ($post_data['ex_code_deck'] == $item[0]['code_deck_id'])
                            {
                            ?>selected="selected"<?php } ?>><?php echo $item[0]['name']; ?></option>
                        <?php } ?>
                    </select>
                </span>
                <span <?php
                if ($post_data['exclude'] == 1 || !$post_data['exclude'])
                {
                    ?> class="hidden" <?php } ?> style="display: block;" id="ex_code_name">
                    Code Name:&nbsp;&nbsp;
                    <select name="AlertRules[in_code_name][]" multiple="multiple" class="ui search fluid dropdown" id="ex_code_name_select">
                         <?php
                         foreach ($ex_code_arr as $key => $item)
                         {
                             ?>
                             <option value="<?php echo $item[0]['name']; ?>"<?php
                             if (in_array($item[0]['name'], $post_data['ex_code_name']))
                             {
                                 ?> selected="selected" <?php } ?>><?php echo $item[0]['name']; ?></option>
                         <?php } ?>
                    </select>
                </span>
            </td>
        </tr>

    </tbody>
</table>
<div class="center">
    <!-- <a step="#step1" href=""  data-toggle="tab" value="next"  id="previous1" class=" btn primary disabled"><?php // __('Previous')?></a> -->
    <a value="next" id="next1" data-toggle="tab" step="#step2" href=""  class="input in-submit btn btn-primary"><?php __('Next')?></a>
    <!--<input type="submit" value="Finish" id="finish" class="input in-submit btn btn-primary" style="display: none;"  />-->
</div>

<script type="text/javascript">
//    $("#ex_code_name_select").dropdown({
//        allowLabels:true
//    });

    var selectedValues = "<?php echo implode(',', $post_data['ex_code_name']); ?>";

//    $('#ex_code_name_select').dropdown({'set selected': selectedValues});

    $(function() {




//        


        $("#next1").click(function() {
            $("#step2").click();
        });

        $("#step2").click(function() {
            var flg = $("#step_").val();

            if (flg >= 2)
            {
                $("#step_").val(2);
                return true;
            }
            var rule_name = $("#rule_name").val();
            var rule_id = $("#rule_name").attr('rule_id');
            var check_flg = '';
            $.ajax({
                type: "POST",
                async: false,
                url: "<?php echo $this->webroot; ?>alerts/ajax_check_rule_name",
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
            if ($("#rule_form").validationEngine('validate'))
            {
                $("#step3").css('cursor', 'pointer').unbind('click', die);
                $("#step_").val(3);
                return true;
            }
            return false;


        });


        var trunk_type_first = '<?php echo $post_data['trunk_type']; ?>';
        switch (trunk_type_first)
        {
            case '1':
                $("#ingress_trunk").show();
                $("#egress_trunk").hide();
                break;
            case '2':
                $("#ingress_trunk").hide();
                $("#egress_trunk").show();
                break;
        }

        $("#include").change(function() {
            if ($(this).val() == 1)
            {
                $("#in_code_name").addClass('hidden');
                $("#in_codes").removeClass('hidden');
                $("#in_code_deck").addClass('hidden');
            }
            else if ($(this).val() == 0)
            {
                $("#in_code_name").addClass('hidden');
                $("#in_codes").addClass('hidden');
                $("#in_code_deck").addClass('hidden');
            }
            else
            {
                $("#in_code_name").removeClass('hidden');
                $("#in_codes").addClass('hidden');
                $("#in_code_deck").removeClass('hidden');
            }
        });

        $("#exclude").change(function() {
            if ($(this).val() == 1)
            {
                $("#ex_code_name").addClass('hidden');
                $("#ex_codes").removeClass('hidden');
                $("#ex_code_deck").addClass('hidden');
            }
            else if ($(this).val() == 2)
            {
                $("#ex_code_name").removeClass('hidden');
                $("#ex_codes").addClass('hidden');
                $("#ex_code_deck").removeClass('hidden');
            }
            else
            {
                $("#ex_code_name").addClass('hidden');
                $("#ex_codes").addClass('hidden');
                $("#ex_code_deck").addClass('hidden');
            }

        });

        $("#trunk_type").change(function() {
            var trunk_type = $(this).val();
            switch (trunk_type)
            {
                case '1':
                    $('#ingress_trunk').show();
                    $("#egress_trunk").hide();
                    break;
                case '2':
                    $('#ingress_trunk').hide();
                    $("#egress_trunk").show();
                    break;
            }
        });

        $("#in_code_deck_select").change(function() {
            $("#in_code_name_select").html('');
            var code_deck_id = $(this).val();
            $.ajax({
                'url': '<?php echo $this->webroot ?>alerts/ajax_get_code_name',
                'type': 'POST',
                'dataType': 'json',
                'data': {'code_deck_id': code_deck_id},
                'success': function(data) {
                    if (!data)
                    {
                        jGrowl_to_notyfy('The code deck has not code!', {theme: 'jmsg-error'});
                    } else
                    {
                        $("#in_code_name").removeClass('hidden');
                        $.each(data, function(index, item) {
                            $("#in_code_name_select").append("<option value='" + item + "'>" + item + "</option>");
                        });
                    }

                }
            });

        });

        $("#ex_code_deck_select").change(function() {
            $("#ex_code_name_select").html('');
            var code_deck_id = $(this).val();
            $.ajax({
                'url': '<?php echo $this->webroot ?>alerts/ajax_get_code_name',
                'type': 'POST',
                'dataType': 'json',
                'data': {'code_deck_id': code_deck_id},
                'success': function(data) {
                    if (!data)
                    {
                        jGrowl_to_notyfy('The code deck has not code!', {theme: 'jmsg-error'});
                    } else
                    {
                        $("#ex_code_name").removeClass('hidden');
                        $.each(data, function(index, item) {
                            $("#ex_code_name_select").append("<option value='" + item + "'>" + item + "</option>");
                        });
                    }

                }
            });

        });

        if($("#select_all").is(':checked')){
             $('.trunk_type select').hide();
        }

        $("#select_all").on('click',function() {
        var checked = $(this).attr("checked");
        if (checked)
        {
            $('.trunk_type select').hide();
        }
        else
        {
            $(".option").removeAttr("selected");
            $('.trunk_type select').show();
        }
    });

    });

</script>