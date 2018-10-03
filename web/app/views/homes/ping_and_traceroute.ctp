<style type="text/css">
    #terminal {
        font: bold 12px/20px arial,sans-serif;
        padding:30px;
    }
</style>

<div id="title">
    <h1><?php echo __('Tools'); ?> &gt;&gt; <?php echo __('Ping And Traceroute'); ?></h1>
</div>

<div id="container">
    
    <div id="terminal">
        <?php
            if(isset($data))
                echo $data;
        ?>
    </div>
    
    <fieldset style=" clear:both;overflow:hidden;margin-top:10px;" class="query-box">
        <div class="search_title"><img src="/Class4/images/search_title_icon.png">
            <?php __('Ping And Traceroute')?>  
        </div>
        <form method="post" name="myform" onsubmit="loading();">
        <table class="form" style="width:100%">
            <tbody>
                <tr class="period-block">
                    <td class="label" style="width:50px;"><?php __('Type')?></td>
                    <td class="value" style="width:300px;">
                        <select name="type" style="width:200px;">
                            <option value="0" <?php echo isset($type)&& $type == 0 ? 'selected':''  ?>><?php __('Ping')?></option>
                            <option value="1" <?php echo isset($type)&& $type == 1 ? 'selected':''  ?>><?php __('Traceroute')?></option>
                        </select>
                    </td>
                    <td class="label" style="width:50px;"><?php __('IP Address')?></td>
                    <td class="value" style="width:300px;">
                        <input type="text" name="ip_address" style="width:200px;" value="<?php echo isset($ip_address) ? $ip_address : '' ?>" />
                    </td>
                    <td class="label" style="text-align:center;">
                        <input type="submit" value="<?php __('Submit')?>" />
                    </td>
                </tr>
            </tbody>
        </table>
        </form>
    </fieldset>
</div>