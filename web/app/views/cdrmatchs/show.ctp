<div id="title">
    <h1>Tools&gt;&gt;<?php __('CDR Reconcilation'); ?></h1>
</div>
<div class="container">
    <?php
        if($flag) :
        $simplexml = simplexml_load_file($filename);
    ?>
    <form method="post" action="<?php echo $this->webroot; ?>cdrmatchs/export" target="_blank">
        <select name="type">
            <option selected="selected" value="xls">EXCEL</option>
            <option value="html">HTML</option>
            <option value="pdf">PDF</option>
        </select>
        <input type="hidden" name="filename" value="<?php echo $filename; ?>" />
        <input type="hidden" name="cmd" value="<?php echo $cmd; ?>" />
        <input type="submit" name="submit" value="Submit" />
    </form>
    <table class="list">
        <thead>
            <tr>
                <td rowspan="2" style="padding-bottom: 30px;"><?php echo __('code_name',true);?></td>
                <td colspan="4">System CDR</td>
                <td colspan="4"><?php if($_POST['type'] == 'client_cdr'): echo 'Client'; else : echo 'Vendor'; endif ?> CDR</td>
                <td colspan="4">Diff</td>
            </tr>
            <tr>
                <td>Call Count</td>
                <td>Min Count</td>
                <td>Bill Amount</td>
                <td>Avg Rate</td>
                <td>Call Count</td>
                <td>Min Count</td>
                <td>Bill Amount</td>
                <td>Avg Rate</td>
                <td>Call Count</td>
                <td>Min Count</td>
                <td>Bill Amount</td>
                <td>Avg Rate</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($simplexml->p as $p): ?>
            <tr>
                <?php foreach($p->attributes() as $val): ?>
                <td><?php echo $val ?></td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
        else:
            echo "Error!";
        endif;
    ?>
</div>