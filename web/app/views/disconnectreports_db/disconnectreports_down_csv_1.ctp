<?php

$head = __('Carrier', true) . ",";
$content = "";
$foot = "";
foreach ($release_cause_show as $release_cause_show_item):
    $head .= $release_cause_show_item . "%,";
endforeach;
$head .= __('Others', true) . "%\n";
foreach ($client_org['result'] as $carrier_name => $client_org_item):
    $client_org_item['all_count'] = (int) $client_org_item['all_count'];
    $content .= $carrier_name . ",";
    foreach ($release_cause_show as $release_cause_show_item):

        $data_count = "0.000";
        if (isset($client_org_item[$release_cause_show_item]) && $client_org_item['all_count'])
        {
            $client_org_item[$release_cause_show_item] = (int) $client_org_item[$release_cause_show_item];
            $data_count = round($client_org_item[$release_cause_show_item] / $client_org_item['all_count'] * 100, 3);
        }

        $content .= $data_count . ",";
    endforeach;
    $content .= isset($client_org_item['Others']) && $client_org_item['all_count'] ? round($client_org_item['Others'] / $client_org_item['all_count'] * 100, 3) : "0.000";
    $content .="\n";

endforeach;

$foot .=__('Total', true) . ",";
foreach ($release_cause_show as $release_cause_show_item):

    $data_count = "0.000";
    if (isset($client_org['total'][$release_cause_show_item]) && $client_org['total']['all_count'])
    {
        $client_org['total'][$release_cause_show_item] = (int) $client_org['total'][$release_cause_show_item];
        $data_count = round($client_org['total'][$release_cause_show_item] / $client_org['total']['all_count'] * 100, 3);
    }

    $foot .= $data_count . ",";
endforeach;
$foot .= isset($client_org['total']['Others']) && $client_org['total']['all_count'] ? round($client_org['total']['Others'] / $client_org['total']['all_count'] * 100, 3) : "0.000";
$foot .= "\n";

echo $head . $content . $foot;

