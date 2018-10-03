<?php
ob_clean();

$days = array();
$startdate = strtotime($start);
$enddate = strtotime($end);
$day = round(($enddate - $startdate) / 3600 / 24);
$dt_begin = new DateTime($start);

for ($i = 0; $i < $day; $i++)
{
    if ($i > 0)
    {
        $dt_begin->modify('+1 days');
    }
    array_push($days, $dt_begin->format('Y-m-d'));
}

$layout = array();

foreach ($filed_arr as $value) {
    if (isset($replace_fields[$value])) {
        $layout[] = $replace_fields[$value];
    } else {
        $layout[] = $value;
    }
}

$layout[] = 'Not Zero Calls';
$layout[] = 'Total(Min)';
$layout[] = 'Total(Min)';
$layout[] = 'Count calls < 30s';
$layout[] = 'Percent calls < 30s';
$layout[] = 'Count calls < 6s';
$layout[] = 'Percent calls < 6s';

foreach ($days as $day) {
    $layout[] = "Billed Time (min) [{$day}]";
    $layout[] = "ASR (%) [{$day}]";
    $layout[] = "ACD (min) [{$day}]";
    $layout[] = "NPR Count [{$day}]";
    $layout[] = "NPR [{$day}]";
}

echo implode(',', $layout) . "\n";

$totalArray = array(
   'not_zero_calls' => 0,
   'total_calls' => 0,
   'total_time' => 0,
   'calls_30' => 0,
   'calls_6' => 0,
   'bill_time' => 0,
   'asr' => 0,
   'acd' => 0,
   'npr_count' => 0,
   'npr' => 0
);

foreach ($days as $day_item) {
    $totalArray[$day_item] = array(
       'bill_time' => 0,
       'asr' => 0,
       'acd' => 0,
       'npr_count' => 0,
       'total_calls' => 0
    );
}

$total_time_total = 0;
$calls_3_total = 0;
$time_3_total = 0;
$calls_6_total = 0;
$time_6_total = 0;
$years_total = array();
foreach ($days as $day)
{
    $years_total[$day] = 0;
}

foreach ($data as $key => $item) {
    $layout = array();
    $total_time_total += $item['total_time'];
    $calls_3_total += $item['calls_30'] + $item['calls_6'];
    $calls_6_total += $item['calls_6'];

    $totalArray['not_zero_calls'] += $item['not_zero_calls'];
    $totalArray['total_time'] += $item['total_time'];
    $totalArray['calls_30'] += $item['calls_30'];
    $totalArray['calls_6'] += $item['calls_6'];

    foreach ($filed_arr as $value) {
        if (isset($item[$value])) {
            $layout[] = $item[$value];
        } else {
            $layout[] = "";
        }
    }

    $layout[] = $item['not_zero_calls'];
    $layout[] = number_format($item['total_time'] / 60, 2, '.', '');
    $layout[] = $item['calls_30'];
    $layout[] = $item['not_zero_calls'] == 0 ? 0 : number_format(($item['calls_30']) / $item['not_zero_calls'] * 100, 2, '.', '');
    $layout[] = $item['calls_6'];
    $layout[] = $item['calls_6'] == 0 ? 0 : number_format($item['calls_6'] / $item['not_zero_calls'] * 100, 2, '.', '');
    $layout[] = $item['calls_6'];

    foreach ($days as $day_item) {
        if (array_key_exists($day_item, $item['years'])) {
            $totalArray[$day_item]['bill_time'] += $item['years'][$day_item]['bill_time'];
            $totalArray[$day_item]['asr'] += $item['years'][$day_item]['total_calls'] == 0 ? 0 : $item['years'][$day_item]['not_zero_calls'] / $item['years'][$day_item]['total_calls'] * 100;
            $totalArray[$day_item]['acd'] += $item['years'][$day_item]['not_zero_calls'] == 0 ? 0 : $item['years'][$day_item]['total_time'] / $item['years'][$day_item]['not_zero_calls'] / 60;
            $totalArray[$day_item]['npr_count'] += $item['years'][$day_item]['npr_count'];
            $totalArray[$day_item]['total_calls'] += $item['years'][$day_item]['total_calls'];

            $layout[] = number_format($item['years'][$day_item]['bill_time'] / 60, 2, '.', '');
            $layout[] = $item['years'][$day_item]['total_calls'] == 0 ? 0 : number_format($item['years'][$day_item]['not_zero_calls'] / $item['years'][$day_item]['total_calls'] * 100, 2, '.', '');
            $layout[] = $item['years'][$day_item]['not_zero_calls'] == 0 ? 0 : number_format($item['years'][$day_item]['total_time'] / $item['years'][$day_item]['not_zero_calls'] / 60, 5, '.', '');
            $layout[] = number_format($item['years'][$day_item]['npr_count']);
            $layout[] = number_format($item['years'][$day_item]['total_calls'] == 0 ? 0 : $item['years'][$day_item]['npr_count'] / $item['years'][$day_item]['total_calls'] * 100, 2) . '%';
        } else {
            $layout[] = '0';
            $layout[] = '0';
            $layout[] = '0';
            $layout[] = '0';
            $layout[] = '0';
        }
    }

    echo implode(',', $layout) . "\n";

}

$layout = array();
$layout[] = 'Total:';

for($i = 0; $i < count($filed_arr) - 1; $i ++ ){
    $layout[] = '';
}
$layout[] = $totalArray['not_zero_calls'];
$layout[] = number_format($totalArray['total_time'] / 60, 2, '.', '');
$layout[] = $totalArray['calls_30'];
$layout[] = $totalArray['not_zero_calls'] == 0 ? 0 : number_format(($totalArray['calls_30']) / $totalArray['not_zero_calls'] * 100, 2, '.', '');
$layout[] = $totalArray['calls_6'];
$layout[] = $totalArray['calls_6'] == 0 ? 0 : number_format($totalArray['calls_6'] / $totalArray['not_zero_calls'] * 100, 2, '.', '');

foreach ($days as $day_item){
    if (array_key_exists($day_item, $totalArray)) {
        $layout[] = number_format($totalArray[$day_item]['bill_time'] / 60, 2, '.', '');
        $layout[] = number_format($totalArray[$day_item]['asr'], 2, '.', '');
        $layout[] = number_format($totalArray[$day_item]['acd'], 5, '.' ,'');
        $layout[] = number_format($totalArray[$day_item]['npr_count']);
        $layout[] = number_format($totalArray[$day_item]['total_calls'] == 0 ? 0 : $totalArray[$day_item]['npr_count'] / $totalArray[$day_item]['total_calls'] * 100, 2) . '%';
    } else {
        $layout[] = '0';
        $layout[] = '0';
        $layout[] = '0';
    }
}

echo implode(',', $layout);
