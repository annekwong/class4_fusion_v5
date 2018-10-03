<?php $post_data['include'] = isset($post_data['include'])? $post_data['include'] : ""; 
    $post_data['exclude'] = isset($post_data['exclude'])? $post_data['exclude'] : "";
?>

<table class="form table dynamicTable tableTools table-bordered  table-white">
    <tbody>
        <tr>
            <td width="50%" style="text-align: right;"><?php __('Monitoring Rule Name')?>: </td>
            <td width="50%">
                <input  type="text" id='rule_name' rule_id="<?php echo $post_data['id']; ?>" value="<?php echo $post_data['rule_name']; ?>" name="AlertRules[rule_name]" class="validate[required,custom[onlyLetterNumberLine]]" />
            </td>
        </tr>
        <tr>
            <td width="50%" style="text-align: right;">
                <?php __('Ingress')?> : 
            </td>
            <td width="50%">
                <span id="ingress_trunk" class="trunk_type" >
                    <select name="AlertRules[ingress_id][]" class="validate[required]" multiple="multiple" style="width:250px;height:400px;" >
                        <?php
                        foreach ($ingress_trunk as $key => $item)
                        {
                            ?>
                            <option  class="option" value="<?php echo $key; ?>"<?php
                            if (in_array($key, $post_data['ingress_id']))
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


<!--<div class="center">-->
<!--    <a step="#step1" href=""  data-toggle="tab" value="next"  id="previous1" class=" btn primary disabled">--><?php //__('Previous')?><!--</a>-->
<!--    <a value="next" id="next1" data-toggle="tab" step="#step2" href=""  class="input in-submit btn btn-primary">--><?php //__('Next')?><!--</a>-->
<!--    <!--<input type="submit" value="Finish" id="finish" class="input in-submit btn btn-primary" style="display: none;"  />-->
<!--</div>-->

