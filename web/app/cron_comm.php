#!/usr/bin/php -q
<?php
ignore_user_abort();
set_time_limit(0);
//ini_set("display_errors","off");
require_once("config/database.php");
error_reporting(E_ALL);

function change_cron($cron_arr = array(), $cron_chg_arr = array())
{
	if (empty($cron_arr))
	{
		$curr_user = system("whoami");
		$cron_file_tmp = "/tmp/cron_" . $curr_user;
		`crontab -l > {$cron_file_tmp}`;
		$cron_arr = file($cron_file_tmp);
	}
	if (!empty($cron_arr))
	{
		$alter_flag = true;
		foreach ($cron_arr as $k=>$v)
		{
			$cron_arr_tmp = array();
			if (!empty($cron_chg_arr['cmd_path']) && strpos($v, $cron_chg_arr['cmd_path']) !== false )
			{
				//echo $v, ' ', $cron_chg_arr['cmd_path']," strpos\r\n";
				$cron_arr_tmp[0] = $cron_chg_arr['cron_minute'];
				$cron_arr_tmp[1] = $cron_chg_arr['cron_hour'];
				$cron_arr_tmp[2] = $cron_chg_arr['cron_day'];
				$cron_arr_tmp[3] = $cron_chg_arr['cron_month'];
				$cron_arr_tmp[4] = $cron_chg_arr['cron_week'];
				$cron_arr_tmp[5] = $cron_chg_arr['cmd_path'];
				$cron_arr_tmp[6] = '>';
				$cron_arr_tmp[7] = empty($cron_chg_arr['log_path']) ? '/dev/null' : $cron_chg_arr['log_path'];
				$cron_arr_tmp[8] = "2>&1\n";
				$cron_arr[$k] = implode(" ", $cron_arr_tmp);
				$alter_flag = false;
				break(1);
			}			
		}	
		//echo $v, ' ', $cron_chg_arr['cmd_path'], " false strpos\r\n";
		if ($alter_flag)
		{
					$cron_arr_tmp[0] = $cron_chg_arr['cron_minute'];
					$cron_arr_tmp[1] = $cron_chg_arr['cron_hour'];
					$cron_arr_tmp[2] = $cron_chg_arr['cron_day'];
					$cron_arr_tmp[3] = $cron_chg_arr['cron_month'];
					$cron_arr_tmp[4] = $cron_chg_arr['cron_week'];
					$cron_arr_tmp[5] = $cron_chg_arr['cmd_path'];
					$cron_arr_tmp[6] = '>';
					$cron_arr_tmp[7] = empty($cron_chg_arr['log_path']) ? '/dev/null' : $cron_chg_arr['log_path'];
					$cron_arr_tmp[8] = "2>&1\n";
					$cron_arr[] = implode(" ", $cron_arr_tmp);
					$alter_flag = false;
		}			
	}
	else
	{
				$cron_arr_tmp[0] = $cron_chg_arr['cron_minute'];
				$cron_arr_tmp[1] = $cron_chg_arr['cron_hour'];
				$cron_arr_tmp[2] = $cron_chg_arr['cron_day'];
				$cron_arr_tmp[3] = $cron_chg_arr['cron_month'];
				$cron_arr_tmp[4] = $cron_chg_arr['cron_week'];
				$cron_arr_tmp[5] = $cron_chg_arr['cmd_path'];
				$cron_arr_tmp[6] = '>';
				$cron_arr_tmp[7] = empty($cron_chg_arr['log_path']) ? '/dev/null' : $cron_chg_arr['log_path'];
				$cron_arr_tmp[8] = "2>&1\n";
				$cron_arr[] = implode(" ", $cron_arr_tmp);			
		}
	return $cron_arr;
}

if (1)
{
	$class_dbconfig = new DATABASE_CONFIG();
	$conn_config = $class_dbconfig->default;
	$dbconn = pg_connect("host={$conn_config['host']} port={$conn_config['port']} dbname={$conn_config['database']} user={$conn_config['login']} password={$conn_config['password']}");
	
	
	$curr_user = system("whoami");
	$cron_file_tmp = "/tmp/cron_" . $curr_user;
	`crontab -l > {$cron_file_tmp}`;
	$cron_arr = file($cron_file_tmp);
	$sql = "select * from task_schedule where flag = true";
	$query = pg_query($dbconn, $sql);
	$amount = pg_num_rows($query);
	if ($amount > 0)
	{
		while ($row = pg_fetch_array($query))
		{
			$cron_arr = change_cron($cron_arr, $row);
		}
	}
	//var_dump($cron_arr);
}
if (!empty($cron_arr))
{
	$data = implode("", $cron_arr);//var_dump($data);
	file_put_contents($cron_file_tmp, $data);
//	$handle = fopen($cron_file_tmp, "w");
//	//$cron = (date("i")+1).",".(date("i")+2)." * * * * /opt/lampp/bin/php /opt/lampp/htdocs/test/excel.php > /dev/null 2>&1\n";
//	//$cron = (date("i")+1).",".(date("i")+2)." * * * * /opt/lampp/bin/php /opt/lampp/htdocs/test/excel.php";
//	fwrite($handle, $cron);
//	fclose($handle);
	$lastval = system("crontab -u {$curr_user} {$cron_file_tmp}", $intval);
	//var_dump($lastval, $intval);
}
?>