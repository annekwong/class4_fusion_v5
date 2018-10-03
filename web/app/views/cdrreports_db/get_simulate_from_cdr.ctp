<style type="text/css">
    .form_input {float:left;width:220px;}

    .container ul{
        padding-left:20px;
    }
    .container ul li {
        padding:3px;
    }
    select,input[type="text"]{margin: 5px 0;}
    .table-condensed{border-left: 1px solid #EBEBEB;border-bottom: 1px solid #EBEBEB;}
    .table-condensed td{border-right:1px solid #EBEBEB;}
</style>


<?php

function strip_invalid_xml_chars2($in)
{
    $out = "";
    $length = strlen($in);
    for ($i = 0; $i < $length; $i++)
    {
        $current = ord($in{$i});
        if (($current == 0x9) || ($current == 0xA) || ($current == 0xD) || (($current >= 0x20) && ($current <= 0xD7FF)) || (($current >= 0xE000) && ($current <= 0xFFFD)) || (($current >= 0x10000) && ($current <= 0x10FFFF)))
        {
            $out .= chr($current);
        }
        else
        {
            $out .= " ";
        }
    }
    return $out;
}

function recursion($element)
{
    if ($element->getName() != 'root')
    {
        echo "<li>";
        echo str_replace('-', ' ', $element->getName());
        if (trim($element) != '')
        {
            echo ' = ' . $element;
        }
    }
    if ($element->count())
    {
        foreach ($element->children() as $chldren)
        {
            echo "<ul>";
            recursion($chldren);
            echo "</ul>";
        }
    }
    echo "</li>";
}

if (isset($xdata))
{
    ?>
    <?php
    $xdata = strip_invalid_xml_chars2($xdata);
    $string = <<<XML
<?xml version='1.0'?> 
<root>
$xdata
</root>
XML;
    $xml = simplexml_load_string($string);
    if (Configure::read('debug'))
    {
        echo "<ul>";

        recursion($xml);

        echo "</ul>";
    }
    ?>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.container li > ul').hide();
            $('<img src="<?php echo $this->webroot . 'images/+.gif' ?>" />').prependTo('.container li:has(ul)').css('cursor', 'pointer').
                toggle(function() {
                    $(this).attr('src', '<?php echo $this->webroot . 'images/-.gif' ?>').siblings().show();
                }, function() {
                    $(this).attr('src', '<?php echo $this->webroot . 'images/+.gif' ?>').siblings().hide();
                });
        });
    </script>

    <table class="list  table table-striped table-bordered  table-white table-primary">
        <tbody>
        <tr>
            <td><?php __('Ingress Trunk') ?></td>
            <td><?php echo $xml->{'Origination-Trunk'}->{'Trunk-Name'}; ?></td>
            <td><?php __('Ingress Host') ?></td>
            <td><?php echo $ingress_host; ?></td>
            <td><?php __('Ingress ANI') ?></td>
            <td><?php echo $xml->{'Origination-SRC-ANI'}; ?></td>
            <td><?php __('Ingress DNIS') ?></td>
            <td><?php echo $xml->{'Origination-SRC-DNIS'}; ?></td>
        </tr>
        <tr>
            <td><?php __('Route Prefix') ?></td>
            <td>-</td>
            <td><?php __('Routing Plan') ?></td>
            <td><?php echo $xml->{'Origination-Trunk'}->{'Route-Strategy-Name'}; ?></td>
            <td><?php __('Static Route') ?></td>
            <td><?php echo $xml->{'Origination-Trunk'}->{'Static-Route-Name'}; ?></td>
            <td><?php __('Dynamic Route') ?></td>
            <td><?php echo $xml->{'Origination-Trunk'}->{'Dynamic-Route-Name'}; ?></td>
        </tr>

        <tr>
            <td><?php __('Ingress Rate') ?></td>
            <td><?php echo isset($xml->{'Origination-Trunk-Rate'}->{'Rate'}) ? $xml->{'Origination-Trunk-Rate'}->{'Rate'} : '' ?></td>
            <td><?php __('LRN Num') ?></td>
            <td><?php echo isset($xml->{'Origination-Respond-LRN-DNIS'}) ? $xml->{'Origination-Respond-LRN-DNIS'} : '' ?></td>
            <td><?php __('Jurisdiction') ?></td>
            <td><?php echo isset($xml->{'Termination-Route'}->{'Termination-Trunk'}->{'Trunk-Rate'}->{'Rate-Type'}) ? $xml->{'Termination-Route'}->{'Termination-Trunk'}->{'Trunk-Rate'}->{'Rate-Type'} : '' ?></td>
            <td><?php __('Release Cause') ?></td>
            <td><?php echo isset($xml->{'Global-Route-State'}->{'Origination-State'}) ? $xml->{'Global-Route-State'}->{'Origination-State'} : ''; ?></td>
        </tr>
        </tbody>
    </table>

    <table class="list table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
        <thead>
        <tr>
            <th><?php __('Egress Trunk') ?></th>
            <th><?php __('Egress Host') ?></th>
            <th><?php __('Term ANI') ?></th>
            <th><?php __('Term DNIS') ?></th>
            <th><?php __('Term Rate') ?></th>
            <th><?php __('Release Cause') ?></th>
            <th><?php __('Egress Start Time') ?></th>
            <th><?php __('PDD') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (isset($xml->{'Termination-Route'}->{'Termination-Trunk'}))
        {
            ?>
            <?php foreach ($xml->{'Termination-Route'}->{'Termination-Trunk'} as $item): ?>
            <tr>
                <td><?php echo $item->{'Trunk-Name'} ?></td>
                <td><?php echo $item->{'Termination-Host'}->{'Host-IP'} ?></td>
                <td><?php echo $item->{'Final-ANI'}->{'ANI'} ?></td>
                <td><?php echo $item->{'Final-DNIS'}->{'DNIS'} ?></td>
                <td><?php echo $item->{'Trunk-Rate'}->{'Rate'} ?></td>
                <td><?php __('normal') ?></td>
                <td><?php echo isset($start_time) ? $start_time : '';?></td>
                <td><?php echo isset($pdd) ? $pdd : ''; ?></td>
            </tr>
        <?php endforeach; ?>
        <?php } ?>

        <?php foreach ($xml->{'Global-Route-State'}->{'Termination-Trunk'} as $item): ?>
            <?php if (strnatcasecmp($item->{'Cause'}, 'normal') != 0): ?>
                <tr>
                    <td><?php
                        if (isset($item->{'Cause'}) && $item->{'Cause'} == 'normal')
                        {
                            echo isset($xml->{'Termination-Route'}->{'Termination-Trunk'}->{'Trunk-Name'}) ? $xml->{'Termination-Route'}->{'Termination-Trunk'}->{'Trunk-Name'} : '';
                        }
                        else
                        {
                            echo $item->{'Trunk-Name'};
                        }
                        ?>
                    </td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>
                        <?php
                        echo isset($item->{'Cause'}) ? $item->{'Cause'} : '';
                        ?>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        </tbody>
    </table>

    <br />
    <?php
}
?>
