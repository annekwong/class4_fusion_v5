<style type="text/css">
    tr {
        border-bottom: 1px dashed #ccc;
    }
    td {
        padding:5px;
    }
    .select_mul {width:350px;}
</style>

<div id="title">
    <h1><?php echo __('Management'); ?> &gt;&gt; <?php echo __('Wizard'); ?></h1>
</div>

<div id="container">
    
    <form>
        
        <table>
            
            <tr>
                <td>
                    <?php __('Carrier Name') ?>
                </td>
                <td>
                    <select name="client_type">
                        <option><?php __('New Carrier')?></option>
                        <option><?php __('Existing Carrier')?></option>
                    </select>
                </td>
                <td>
                    <input type="text" name="client_name" />
                </td>
            </tr>
            
            <tr>
                <td>
                    <?php __('Credit Limit') ?>
                </td>
                <td>
                    <input type="text" name="credit_limit" />
                </td>
            </tr>
            
            <tr>
                <td>
                    <?php __('Trunk Name') ?>
                </td>
                <td>
                    <select name="trunk_type">
                        <option><?php __('Ingress')?></option>
                        <option><?php __('Egress')?></option>
                    </select>
                </td>
                <td>
                    <input type="text" name="trunk_name" />
                </td>
            </tr>
            
            <tr>
                <td><?php __('CPS Limit') ?></td>
                <td>
                    <input type="text" name="cps_limit" />
                </td>
                <td><?php __('Call Limit') ?></td>
                <td>
                    <input type="text" name="call_limit" />
                </td>
            </tr>

            <tr>
                <td colspan="2"><?php __('IP List'); ?></td>
                <td colspan="2"><?php __('Codes'); ?></td>
            </tr>    
            
            <tr>
                <td colspan="2">
                    <select multiple="multiple" class="select_mul"></select>
                </td>
                <td colspan="2">
                    <select multiple="multiple" class="select_mul"></select>
                </td>
            </tr>
            
            <tr>
                <td><?php __('Host Routing'); ?></td>
                <td>
                    <select name="host_routing">
                        <option><?php __('Round Robin')?></option>
                        <option><?php __('Top Down')?></option>
                    </select>
                </td>
            </tr>
            
            <tr>
                <td><?php __('Rate Table'); ?></td>
                <td>
                    <input type="text" name="rate_table" />
                </td>
            </tr>

            <tr>
                <td><?php __('Routing'); ?></td>
                <td>
                    <select name="routing_type">
                        <option><?php __('Static Routing')?></option>
                        <option><?php __('Dynamic Routing')?></option>
                    </select>
                </td>
            </tr>
            
            <tr>
                <td><?php __('Egress Trunk List'); ?></td>
                <td>
                    <select multiple="multiple" class="select_mul"></select>
                </td>
            </tr>
            
            <tr style="text-align:center;">
                <td colspan="4">
                    <input type="button" value="Cancel">
                    <input type="button" value="Submit">
                    <input type="submit" value="Add Next">
                </td>
            </tr>
    
        </table>
    
    </form>
    
</div>