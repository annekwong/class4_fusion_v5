<?php echo $form->create('Clientrates') ?>

<table>
    <tr>
        <td>
            <input type="input" value="<?php echo $code_name; ?>"  class="validate[required]" id="code_name" name="code_name" />
            <input type="hidden" value="<?php echo $code_name; ?>" name="old_code_name" />
        </td>
        <td>
            <input type="input" value="<?php echo $rate; ?>" class="validate[required,custom[number]]" id="rate" name="rate" />
            <input type="hidden" value="<?php echo $rate; ?>" name="old_rate" />
        </td>
        <td></td>
        <td>
            <input type="text" name="effective_date" class="validate[required]" value="<?php echo $effective_date; ?>" style="width:100px;"   class="input in-text wdate" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss'});"  />
            <input type="hidden" value="<?php echo $effective_date; ?>" name="old_effective_date" />
            <input type="hidden" value="<?php echo $effective_date_timezone; ?>" name="old_effective_date_timezone" />
            <select name="effective_date_timezone" style="width:100px;">
                <option value="-12" <?php
                if (!strcmp('-12', $effective_date_timezone))
                {
                    ?>selected="selected" <?php } ?>>GMT -12:00</option>
                <option value="-11" <?php
                if (!strcmp('-11', $effective_date_timezone))
                {
                    ?>selected="selected" <?php } ?>>GMT -11:00</option>
                <option value="-10" <?php
                if (!strcmp('-10', $effective_date_timezone))
                {
                    ?>selected="selected" <?php } ?>>GMT -10:00</option>
                <option value="-09" <?php
                if (!strcmp('-09', $effective_date_timezone))
                {
                    ?>selected="selected" <?php } ?>>GMT -09:00</option>
                <option value="-08" <?php
                if (!strcmp('-08', $effective_date_timezone))
                {
                    ?>selected="selected" <?php } ?>>GMT -08:00</option>
                <option value="-07" <?php
                if (!strcmp('-07', $effective_date_timezone))
                {
                    ?>selected="selected" <?php } ?>>GMT -07:00</option>
                <option value="-06" <?php
                if (!strcmp('-06', $effective_date_timezone))
                {
                    ?>selected="selected" <?php } ?>>GMT -06:00</option>
                <option value="-05" <?php
                if (!strcmp('-05', $effective_date_timezone))
                {
                    ?>selected="selected" <?php } ?>>GMT -05:00</option>
                <option value="-04" <?php
                if (!strcmp('-04', $effective_date_timezone))
                {
                    ?>selected="selected" <?php } ?>>GMT -04:00</option>
                <option value="-03" <?php
                if (!strcmp('-03', $effective_date_timezone))
                {
                    ?>selected="selected" <?php } ?>>GMT -03:00</option>
                <option value="-02" <?php
                if (!strcmp('-02', $effective_date_timezone))
                {
                    ?>selected="selected" <?php } ?>>GMT -02:00</option>
                <option value="-01" <?php
                if (!strcmp('-01', $effective_date_timezone))
                {
                    ?>selected="selected" <?php } ?>>GMT -01:00</option>
                <option value="+00" <?php
                if (!strcmp('00', $effective_date_timezone))
                {
                    ?>selected="selected" <?php } ?>>GMT +00:00</option>
                <option value="+01" <?php
                if (!strcmp('01', $effective_date_timezone))
                {
                    ?>selected="selected" <?php } ?>>GMT +01:00</option>
                <option value="+02" <?php
                if (!strcmp('02', $effective_date_timezone))
                {
                    ?>selected="selected" <?php } ?>>GMT +02:00</option>
                <option value="+03" <?php
                if (!strcmp('03', $effective_date_timezone))
                {
                    ?>selected="selected" <?php } ?>>GMT +03:00</option>
                <option value="+04" <?php
                if (!strcmp('04', $effective_date_timezone))
                {
                    ?>selected="selected" <?php } ?>>GMT +04:00</option>
                <option value="+05" <?php
                if (!strcmp('05', $effective_date_timezone))
                {
                    ?>selected="selected" <?php } ?>>GMT +05:00</option>
                <option value="+06" <?php
                if (!strcmp('06', $effective_date_timezone))
                {
                    ?>selected="selected" <?php } ?>>GMT +06:00</option>
                <option value="+07" <?php
                if (!strcmp('07', $effective_date_timezone))
                {
                    ?>selected="selected" <?php } ?>>GMT +07:00</option>
                <option value="+08" <?php
                if (!strcmp('08', $effective_date_timezone))
                {
                    ?>selected="selected" <?php } ?>>GMT +08:00</option>
                <option value="+09" <?php
                if (!strcmp('09', $effective_date_timezone))
                {
                    ?>selected="selected" <?php } ?>>GMT +09:00</option>
                <option value="+10" <?php
                if (!strcmp('10', $effective_date_timezone))
                {
                    ?>selected="selected" <?php } ?>>GMT +10:00</option>
                <option value="+11" <?php
                if (!strcmp('11', $effective_date_timezone))
                {
                    ?>selected="selected" <?php } ?>>GMT +11:00</option>
                <option value="+12" <?php
                if (!strcmp('12', $effective_date_timezone))
                {
                    ?>selected="selected" <?php } ?>>GMT +12:00</option>
                <option value=""></option>
            </select>
        </td>

        <td>
            <a title="Save" href="#%20" id="save" >
                <i class="icon-save"></i>
            </a>
            <a title="Exit" href="#%20" style="margin-left: 20px;" id="delete" >
                <i class="icon-remove"></i>
            </a>
        </td>
    </tr>
</table>
<?php echo $form->end() ?>
