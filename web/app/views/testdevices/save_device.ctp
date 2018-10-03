<?php
//var_dump($result);
$xmlStr = "<Document>";
//$xmlStr .= "<Dnis>9911000</Dnis>
//<Result>Success</Result>
//<Release-Cause>Normal</Release-Cause>
//<Call-Time>2010-3-1 05:45:33</Call-Time>
//<Connect-Time>2010-3-1 05:45:34</Connect-Time>
//<Hangup-Time>2010-3-1 05:46:34</Hangup-Time>
//<PDD>200ms</PDD>";
$xmlStr .= html_entity_decode($result);
$xmlStr .= "</Document>";
//var_dump($xmlStr);
$xml = simplexml_load_string($xmlStr);
//var_dump($xml);
$out = "<table class=\"list list-form\">";
$out .= "</thead><tr>";
foreach($xml as $k=>$v)
{
    $out .= "<td>$k</td>";
 }
$out .= "</tr></thead>";
$out .= "<tbody><tr style=\"background-color: #EDF0F5;\">";
foreach($xml as $k=>$v)
{
    $out .= "<td>$v</td>";
 }
$out .= "</tr></tbody>";
$out .= "</table>";
echo $out;
//foreach($xml as $k=>$v)
//{
//	echo $k,':',$v,'<br />';
//	//var_dump($v);
//}
?>