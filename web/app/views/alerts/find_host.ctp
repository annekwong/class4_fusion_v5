<?php
//var_dump($host);
$host_arr = array();
if (!empty($host))
{
	foreach ($host as $k=>$v)
	{
		$host_arr[$v[0]['resource_ip_id']] = $v[0]['ip'] . ":" . $v[0]['port'];
	}
}
echo json_encode($host_arr);
?>