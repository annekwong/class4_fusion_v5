<div id="title"> 
    <h1><?php __('Configuration') ?> &gt;&gt; <?php __('Advance System Setting'); ?></h1> 
</div> 

<div id="container"> 
    <?php echo $this->element("advance_setting/tab", array('active'=>'script'))?>
    <form method="post"> 
        <table class="list list-form"> 
            <thead> 
                <tr> 
                    <td colspan="3" class="last"><?php __('Database') ?></td> 
                </tr> 
            </thead> 
            <tbody> 
                <tr class="row-1"> 
                    <td class="label"><?php __('Database Host') ?>:</td> 
                    <td class="value"><input type="text" name="db_host" class="input in-text" value="<?php echo $data['db_host'] ?>"></td>
                    <td class="help last">*</td> 
                </tr> 
                <tr class="row-2"> 
                    <td class="label"><?php __('Database User') ?>:</td> 
                    <td class="value"><input type="text" name="db_username" class="input in-text" value="<?php echo $data['db_username'] ?>"></td> 
                    <td class="help last">*</td> 
                </tr> 
                <tr class="row-1"> 
                    <td class="label"><?php __('Database Name') ?>:</td> 
                    <td class="value"><input type="text" name="db_name" class="input in-text" value="<?php echo $data['db_name'] ?>"></td> 
                    <td class="help last">*</td> 
                </tr> 
                <tr class="row-2"> 
                    <td class="label"><?php __('Database Password') ?>:</td> 
                    <td class="value"><input type="text" name="db_password" class="input in-text" value="<?php echo $data['db_password'] ?>"></td>
                    <td class="help last">*</td> 
                </tr> 
                <tr class="row-1"> 
                    <td class="label"><?php __('Database Port') ?>:</td> 
                    <td class="value"><input type="text" name="db_port" class="input in-text" value="<?php echo $data['db_port'] ?>"></td> 
                    <td class="help last">*</td> 
                </tr> 
            </tbody>
        </table>
        
        <table class="list list-form"> 
            <thead> 
                <tr> 
                    <td colspan="3" class="last"><?php __('Switch') ?></td> 
                </tr> 
            </thead> 
            <tbody> 
                <tr class="row-1"> 
                    <td class="label"><?php __('Switch Host') ?>:</td> 
                    <td class="value"><input type="text" name="remote_ip" class="input in-text" value="<?php echo $data['remote_ip'] ?>"></td>
                    <td class="help last">*</td> 
                </tr> 
                <tr class="row-2"> 
                    <td class="label"><?php __('Switch Port') ?>:</td> 
                    <td class="value"><input type="text" name="remote_port" class="input in-text" value="<?php echo $data['remote_port'] ?>"></td> 
                    <td class="help last">*</td> 
                </tr> 
            </tbody>
        </table>
        <table class="list list-form"> 
            <thead> 
                <tr> 
                    <td colspan="3" class="last"><?php __('Web') ?></td> 
                </tr> 
            </thead> 
            <tbody> 
                <tr class="row-1"> 
                    <td class="label"><?php __('Web URL') ?>:</td> 
                    <td class="value"><input type="text" name="web_url" class="input in-text" value="<?php echo $data['web_url'] ?>"></td>
                    <td class="help last">*</td> 
                </tr>             
            </tbody>
        </table>
        <table class="list list-form"> 
            <thead> 
                <tr> 
                    <td colspan="3" class="last"><?php __('Log') ?></td> 
                </tr> 
            </thead> 
            <tbody> 
                <tr class="row-1"> 
                    <td class="label"><?php __('Log File Directory') ?>:</td> 
                    <td class="value"><input type="text" name="log_file_dir" class="input in-text" value="<?php echo $data['log_file_dir'] ?>"></td>
                    <td class="help last">*</td> 
                </tr> 
                <tr class="row-2"> 
                    <td class="label"><?php __('Log Level') ?>:</td> 
                    <td class="value">
                        <select name="log_level">
                            <option value="" <?php if ($data['log_level'] == '') echo 'selected="selected"'; ?>><?php __('NONE')?></option>
                            <option value="DEBUG" <?php if ($data['log_level'] == 'DEBUG') echo 'selected="selected"'; ?>><?php __('DEBUG')?></option>
                            <option value="INFO" <?php if ($data['log_level'] == 'INFO') echo 'selected="selected"'; ?>><?php __('INFO')?></option>
                            <option value="WARN" <?php if ($data['log_level'] == 'WARN') echo 'selected="selected"'; ?>><?php __('WARN')?></option>
                            <option value="CRITICAL" <?php if ($data['log_level'] == 'CRITICAL') echo 'selected="selected"'; ?>><?php __('CRITICAL')?></option>
                        </select>
                    </td> 
                    <td class="help last"><?php __('DEBUG') ?>,<?php __('INFO') ?>,<?php __('WARN') ?>,<?php __('CRITICAL') ?></td> 
                </tr>                 
            </tbody>
        </table>
        <table class="list list-form"> 
            <thead> 
                <tr> 
                    <td colspan="3" class="last"><?php __('Recover') ?></td> 
                </tr> 
            </thead> 
            <tbody> 
                <tr class="row-1"> 
                    <td class="label"><?php __('Recover Host') ?>:</td> 
                    <td class="value"><input type="text" name="recover_bill_ip" class="input in-text" value="<?php echo $data['recover_bill_ip'] ?>"></td>
                    <td class="help last">*</td> 
                </tr> 
                <tr class="row-2"> 
                    <td class="label"><?php __('Recover Port') ?>:</td> 
                    <td class="value"><input type="text" name="recover_bill_port" class="input in-text" value="<?php echo $data['recover_bill_port'] ?>"></td> 
                    <td class="help last">*</td> 
                </tr> 
                <tr class="row-1"> 
                    <td class="label"><?php __('Recover Local IP') ?>:</td> 
                    <td class="value"><input type="text" name="recover_local_ip" class="input in-text" value="<?php echo $data['recover_local_ip'] ?>"></td> 
                    <td class="help last">*</td> 
                </tr>                
                <tr class="row-2"> 
                    <td class="label"><?php __('Switch CDR Backup Path') ?>:</td> 
                    <td class="value"><input type="text" name="cdr_softswitch" class="input in-text" value="<?php echo $data['cdr_softswitch'] ?>"></td> 
                    <td class="help last">*</td> 
                </tr> 
            </tbody>
        </table>
        <div class="form-buttons"><input type="submit" value="<?php __('Submit')?>" class="input in-submit"></div> 
    </form>
</div>