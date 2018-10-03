
        <tr>
            <td  style="text-align: right;"><?php __('Cause Code Criteria')?>: </td>
            <td >
                <select name="AlertRules[cause_code_criteria]" id="acd" class="step2_select">
                    <option value="1"<?php
                    if ($post_data['cause_code_criteria'] == 1)
                    {
                        ?> selected="selected" <?php } ?>>404
                    </option>
                    
                    <option value="2"<?php
                    if ($post_data['cause_code_criteria'] == 2)
                    {
                        ?> selected="selected" <?php } ?>>480
                    </option>
                    
                    <option value="3"<?php
                    if ($post_data['cause_code_criteria'] == 3)
                    {
                        ?> selected="selected" <?php } ?>>503
                    </option>
                    
                    <option value="4"<?php
                    if ($post_data['cause_code_criteria'] == 4)
                    {
                        ?> selected="selected" <?php } ?>>400
                    </option>

                </select>
            </td>
        </tr>
        
        

        <tr>
            <td  style="text-align: right;"><?php __('Threshold')?>: </td>
            <td >
                > <input type="text" name="AlertRules[threshold]" value="<?php echo $post_data['threshold'] ?>" class="validate[required,custom[number]]" style="width: 196px;" />
            </td>
        </tr>


<!--<div class="center">-->
<!--    <a step="#step1" href=""  data-toggle="tab" value="next"  id="previous2" class=" btn primary">--><?php //__('Previous')?><!--</a>-->
<!--    <a value="next" id="next2" data-toggle="tab" href=""  class="input in-submit btn btn-primary">--><?php //__('Next')?><!--</a>-->
<!--    <!--<input type="submit" value="Finish" id="finish" class="input in-submit btn btn-primary" style="display: none;"  />-->
<!--</div>-->


