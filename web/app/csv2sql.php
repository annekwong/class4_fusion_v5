#!/usr/bin/php -q
<?php
error_reporting(E_ALL ^ E_NOTICE);
ignore_user_abort();
set_time_limit(0);
//ini_set("display_errors","off");
require_once("config/database.php");

//两个ip判断是否同一ip或ip段
function check_ip($ip1, $ip2)
{
	if ($ip1 == $ip2)
	{
		return true;
	}
	else 
	{
		$ip1_split = explode("/", $ip1);
		$ip2_split = explode("/", $ip2);
		$ip1_tmp = array();
		$ip2_tmp = array();
		if (count($ip1_split)>1)
		{
			$ip1_mask[0] = pow(2, ($ip1_split[1]>=8?8:$ip1_split[1]))-1;
			$ip1_mask[1] = pow(2, ($ip1_split[1]-8>=8?8:$ip1_split[1]-8))-1;
			$ip1_mask[2] = pow(2, ($ip1_split[1]-16>=8?8:$ip1_split[1]-16))-1;
			$ip1_mask[3] = pow(2, ($ip1_split[1]-24>=8?8:$ip1_split[1]-24))-1;
			$ip1_tmp = explode(".", $ip1_split[0]);
		}
		else
		{
			$ip1_tmp = explode(".", $ip1);
		}
		if (count($ip2_split)>1)
		{
			$ip2_mask[0] = pow(2, ($ip2_split[1]>=8?8:$ip2_split[1]))-1;
			$ip2_mask[1] = pow(2, ($ip2_split[1]-8>=8?8:$ip2_split[1]-8))-1;
			$ip2_mask[2] = pow(2, ($ip2_split[1]-16>=8?8:$ip2_split[1]-16))-1;
			$ip2_mask[3] = pow(2, ($ip2_split[1]-24>=8?8:$ip2_split[1]-24))-1;
			$ip2_tmp = explode(".", $ip2_split[0]);
		}
		else
		{
			$ip2_tmp = explode(".", $ip2);
		}
		if (!empty($ip1_mask))
		{
			$ip1_area[0] = $ip1_mask[0]&$ip1_tmp[0];
			$ip1_area[1] = $ip1_mask[1]&$ip1_tmp[1];
			$ip1_area[2] = $ip1_mask[2]&$ip1_tmp[2];
			$ip1_area[3] = $ip1_mask[3]&$ip1_tmp[3];
			if (empty($ip2_mask))
			{
				$ip2_area[0] = $ip1_mask[0]&$ip2_tmp[0];
				$ip2_area[1] = $ip1_mask[1]&$ip2_tmp[1];
				$ip2_area[2] = $ip1_mask[2]&$ip2_tmp[2];
				$ip2_area[3] = $ip1_mask[3]&$ip2_tmp[3];
			}
			else
			{
				$ip2_area[0] = $ip2_mask[0]&$ip2_tmp[0];
				$ip2_area[1] = $ip2_mask[1]&$ip2_tmp[1];
				$ip2_area[2] = $ip2_mask[2]&$ip2_tmp[2];
				$ip2_area[3] = $ip2_mask[3]&$ip2_tmp[3];	
			}
		}
		else
		{
			if (!empty($ip2_mask))
			{
				$ip2_area[0] = $ip2_mask[0]&$ip2_tmp[0];
				$ip2_area[1] = $ip2_mask[1]&$ip2_tmp[1];
				$ip2_area[2] = $ip2_mask[2]&$ip2_tmp[2];
				$ip2_area[3] = $ip2_mask[3]&$ip2_tmp[3];
				$ip1_area[0] = $ip2_mask[0]&$ip1_tmp[0];
				$ip1_area[1] = $ip2_mask[1]&$ip1_tmp[1];
				$ip1_area[2] = $ip2_mask[2]&$ip1_tmp[2];
				$ip1_area[3] = $ip2_mask[3]&$ip1_tmp[3];
			}
			else
			{
				return false;
			}
		} 
		$ip1_area_addr = implode(".", $ip1_area);
		$ip2_area_addr = implode(".", $ip2_area);
		if ($ip1_area_addr == $ip2_area_addr)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
//处理上传文件的类
class UploadFileAction
{
	public $dbConn;	

	/** 
	* @Purpose: 
	* 错误报告
	* @Method Name: Log() 
	* @Parameter: $logFile 日志文件, $error_str 错误报告信息
	* @Return: bool true写入正常，false未写入
	*/ 
	public function Log($logFile, $error_str='')
	{
		if (!is_file($logFile))
		{
			mkdir($logFile);			
		}
		if (is_writable($logFile))
		{
		   if (!$handle = fopen($logFile, 'a')) 
		   {
		         return false;
		   }
		   if (fwrite($handle, $somecontent) === FALSE)
		   {
		        return false;
		   }
		   fclose($handle);
		   return true;
		}
		else
		{
		    return false;
		}
	}

	/** 
	* @Purpose: 
	* 导入文件数据写入数据表
	* @Method Name: InsertMessageInfo() 
	* @Parameter: $dbConn数据库连接,$uploadFileInfoArr=array() 错误报告信息数组
	* @Return: bool true写入正常，false未写入
	*/ 
	public function InsertMessageInfo($dTable, $uploadTable, $uploadFileInfoArr=array(), $foreignId = 0, $uploadInfo = array())
	{
		$return = false;
		
		//$dTable = preg_replace("/\d*/", "", $dTable);
		if ($this->dbConn && $dTable!='')
		{
			$sql = '';
			//$dTtable_parse = preg_replace("/(tmp_code|tmp_jurisdiction|tmp_rate|tmp_location_code|tmp_conf_member|tmp_cpart|tmp_product_items|tmp_translation_item|tmp_resource_block|tmp_route)(.*)$/i","\$1",$dTable);
			$tpb = array('T'=>0, 'P'=>1, 'B'=>2);
			$sh  = array('S'=>1, 'H'=>2, 'A'=>0);
			$icr = array('I'=>0, 'C'=>1, 'R'=>2);
			$tr	 = array('P'=>0, 'T'=>1, 'R'=>2);
			$ei	 = array('I'=>1, 'E'=>2);
			switch (intval($uploadTable))	//$dTable_parse)
			{
				case 1:
					$sql = "insert into {$dTable} (alias,active,t38,cps_limit,capacity,lnp,lrn_block,carrier_name,media_type,proto,dnis_only,ring_timeout,ignore_ring,ignore_early_media,codec) values ('".addslashes($uploadFileInfoArr['trunk_alias'])."', ".(''==$uploadFileInfoArr['active']?'default':"'{$uploadFileInfoArr['active']}'").", ".(''==$uploadFileInfoArr['t.38']?'default':"'{$uploadFileInfoArr['t.38']}'").", '".$uploadFileInfoArr['cps_limit']."', '".$uploadFileInfoArr['capacity']."', ".(''==$uploadFileInfoArr['lrn']?'default':"'{$uploadFileInfoArr['lrn']}'").", ".(''==$uploadFileInfoArr['block_lrn']?'default':"'{$uploadFileInfoArr['block_lrn']}'").", '".$uploadFileInfoArr['carrier_name']."', ".(''==$uploadFileInfoArr['media_type']?'default':"'{$tpb[$uploadFileInfoArr['media_type']]}'").", ".(''==$uploadFileInfoArr['proto']?'default':"'{$sh[$uploadFileInfoArr['proto']]}'").", ".((!isset($uploadFileInfoArr['dnis_only'])||''==$uploadFileInfoArr['dnis_only'])?'default':"'{$uploadFileInfoArr['dnis_only']}'").", '".(isset($uploadFileInfoArr['ring_timeout'])?$uploadFileInfoArr['ring_timeout']:'')."', ".((!isset($uploadFileInfoArr['ignore_ring'])||''==$uploadFileInfoArr['ignore_ring'])?'default':"'{$uploadFileInfoArr['ignore_ring']}'").", ".((!isset($uploadFileInfoArr['ignore_early_media'])||''==$uploadFileInfoArr['ignore_early_media'])?'default':"'{$uploadFileInfoArr['ignore_early_media']}'").", '".$uploadFileInfoArr['codec']."')";
					break;
				case 2:
					$sql = "insert into {$dTable} (alias,active,t38,cps_limit,capacity,lnp,lrn_block,carrier_name,media_type,proto,dnis_only,ring_timeout,ignore_ring,ignore_early_media,codec,res_strategy) values ('".addslashes($uploadFileInfoArr['trunk_alias'])."', ".(''==$uploadFileInfoArr['active']?'default':"'{$uploadFileInfoArr['active']}'").", ".(''==$uploadFileInfoArr['t.38']?'default':"'{$uploadFileInfoArr['t.38']}'").", '".$uploadFileInfoArr['cps_limit']."', '".$uploadFileInfoArr['capacity']."', ".(''==$uploadFileInfoArr['lrn']?'default':"'{$uploadFileInfoArr['lrn']}'").", ".(''==$uploadFileInfoArr['block_lrn']?'default':"'{$uploadFileInfoArr['block_lrn']}'").", '".$uploadFileInfoArr['carrier_name']."', ".(''==$uploadFileInfoArr['media_type']?'default':"'{$tpb[$uploadFileInfoArr['media_type']]}'").", ".(''==$uploadFileInfoArr['proto']?'default':"'{$sh[$uploadFileInfoArr['proto']]}'").", ".((!isset($uploadFileInfoArr['dnis_only'])||''==$uploadFileInfoArr['dnis_only'])?'default':"'{$uploadFileInfoArr['dnis_only']}'").", '".(isset($uploadFileInfoArr['ring_timeout'])?$uploadFileInfoArr['ring_timeout']:'')."', ".((!isset($uploadFileInfoArr['ignore_ring'])||''==$uploadFileInfoArr['ignore_ring'])?'default':"'{$uploadFileInfoArr['ignore_ring']}'").", ".((!isset($uploadFileInfoArr['ignore_early_media'])||''==$uploadFileInfoArr['ignore_early_media'])?'default':"'{$uploadFileInfoArr['ignore_early_media']}'").", '".$uploadFileInfoArr['codec']."', ".(''==$uploadFileInfoArr['host_strategy']?'default':"'{$tr[$uploadFileInfoArr['host_strategy']]}'").")";
					break;
				case 3:
					$sql = "insert into {$dTable} (resource_name, ip, port) values ('".addslashes($uploadFileInfoArr['trunk_alias'])."', '".addslashes($uploadFileInfoArr['ip'])."', '".intval($uploadFileInfoArr['port'])."')";
					break;
				case 4:
					$sql = "insert into {$dTable} (resource_name,direction,action,digits,dnis,time_profile_name,type,number_length,number_type) values ('".addslashes($uploadFileInfoArr['trunk_alias'])."', '".$ei[$uploadFileInfoArr['direction']]."', '".intval($uploadFileInfoArr['action'])."', '".addslashes($uploadFileInfoArr['digits'])."', '".addslashes($uploadFileInfoArr['dnis'])."', '".addslashes($uploadFileInfoArr['time_profile'])."', '".intval($uploadFileInfoArr['type'])."', '".intval($uploadFileInfoArr['number_length'])."', ".(''==$uploadFileInfoArr['ignore_early_media']?'default':"'{$uploadFileInfoArr['number_type']}'").")";
					break;
				case 5:
					$sql = "insert into {$dTable} (resource_name,translation_name,time_profile_name) values ('".addslashes($uploadFileInfoArr['resource_alias'])."', '".addslashes($uploadFileInfoArr['translation_name'])."', '".addslashes($uploadFileInfoArr['time_profile'])."')";
					break;
				case 6:
					$sql = "insert into {$dTable} (ani,dnis,action_ani,action_dnis,ani_method,dnis_method) values ('".addslashes($uploadFileInfoArr['ani'])."', '".addslashes($uploadFileInfoArr['dnis'])."', '".addslashes($uploadFileInfoArr['translated_ani'])."', '".addslashes($uploadFileInfoArr['translated_dnis'])."', '".$icr[$uploadFileInfoArr['ani_action']]."', '".$icr[$uploadFileInfoArr['dnis_action']]."')";
					break;	
				case 7:
					$sql = "insert into {$dTable} (ingress_name,engress_name,digit,time_profile_name) values ('".addslashes($uploadFileInfoArr['ingress_trunk'])."', '".addslashes($uploadFileInfoArr['engress_trunk'])."', '".addslashes($uploadFileInfoArr['prefix'])."', '".addslashes($uploadFileInfoArr['time_profile'])."')";
					break;	
				case 8:
					$sql = "insert into {$dTable} (prefix,jurisdiction_name,state) values ('".addslashes($uploadFileInfoArr['prefix'])."', '".addslashes($uploadFileInfoArr['jurisdiction_name'])."', '".addslashes($uploadFileInfoArr['state'])."')";
					break;
				case 9:
					$sql = "insert into {$dTable} (code,country,name) values ('".addslashes($uploadFileInfoArr['prefix'])."', '".addslashes($uploadFileInfoArr['country'])."', '".addslashes($uploadFileInfoArr['code_name'])."')";
					break;			
				case 10:				
					if (!empty($uploadInfo['duplicate_type']) && 'delete' == $uploadInfo['duplicate_type'])
					{
						$delete_sql_condition = '';
						if (empty($uploadFileInfoArr['profile']))
						{
							$delete_sql_condition = " and time_profile_id is null";
						}
						else
						{
							$delete_sql_condition = " and time_profile_id = (select time_profile_id from time_profile where name = '".$uploadFileInfoArr['profile']."' limit 1)";
						}
						$delete_sql = "delete from rate where code = ".(empty($uploadFileInfoArr['code'])?"''::prefix_range":("'".$uploadFileInfoArr['code']."'"))." and effective_date = ".(''==$uploadFileInfoArr['effective_date']?'default':"'{$uploadFileInfoArr['effective_date']}'")." {$delete_sql_condition} and zone = '".(empty($uploadFileInfoArr['ratezonetime']) ? 0 : $uploadFileInfoArr['ratezonetime'])."' and rate_table_id = " . intval($foreignId);
						$delete_re = pg_query($this->dbConn, $delete_sql);
						if ($delete_re === false)
						{
							echo "Delete SQL ERROR: ".$delete_sql.".\r\n";				
						}
						else
						{
							echo "Delete SQL : ".$delete_sql.".\r\n";
						}
					}
					$sql = "insert into rate (rate_table_id, code, rate, setup_fee, effective_date, end_date, min_time, grace_time, interval, time_profile_id, seconds, code_name, intra_rate, inter_rate, local_rate, country, zone) values (" . intval($foreignId) . ", ".(empty($uploadFileInfoArr['code'])?"''::prefix_range":("'".$uploadFileInfoArr['code']."'")).", '".$uploadFileInfoArr['rate']."', ".(''==$uploadFileInfoArr['setup_fee']?'default':"'{$uploadFileInfoArr['setup_fee']}'").", ".(''==$uploadFileInfoArr['effective_date']?'default':"'{$uploadFileInfoArr['effective_date']}'").", ". (empty($uploadFileInfoArr['end_date']) ? 'null' : ("'".$uploadFileInfoArr['end_date']."'")) . ", ".(''==$uploadFileInfoArr['min_time']?'default':"'{$uploadFileInfoArr['min_time']}'").", ".(''==$uploadFileInfoArr['grace_time']?'default':"'{$uploadFileInfoArr['grace_time']}'").", ".(''==$uploadFileInfoArr['interval']?'default':"'{$uploadFileInfoArr['interval']}'").", (select time_profile_id from time_profile where name = '".$uploadFileInfoArr['profile']."' limit 1), ".(''==$uploadFileInfoArr['seconds']?'default':"'{$uploadFileInfoArr['seconds']}'").", '".addslashes($uploadFileInfoArr['code_name'])."', ".(''==$uploadFileInfoArr['intrastate_rate']?'default':"'{$uploadFileInfoArr['intrastate_rate']}'").", ".(''==$uploadFileInfoArr['inter_rate']?'default':"'{$uploadFileInfoArr['inter_rate']}'").", ".(''==$uploadFileInfoArr['local_rate']?'default':"'{$uploadFileInfoArr['local_rate']}'").", '".$uploadFileInfoArr['country']."', '".(empty($uploadFileInfoArr['ratezonetime']) ? 0 : $uploadFileInfoArr['ratezonetime'])."')";
					//$sql = "insert into {$dTable} (code, rate, setup_fee, effective_date, end_date, min_time, grace_time, interval, time_profile_name, seconds, code_name, intra_rate, inter_rate, local_rate, country, zone) values ('".$uploadFileInfoArr['code']."', '".$uploadFileInfoArr['rate']."', ".(''==$uploadFileInfoArr['setup_fee']?'default':"'{$uploadFileInfoArr['setup_fee']}'").", ".(''==$uploadFileInfoArr['effective_date']?'default':"'{$uploadFileInfoArr['effective_date']}'").", '".$uploadFileInfoArr['end_date']."', ".(''==$uploadFileInfoArr['min_time']?'default':"'{$uploadFileInfoArr['min_time']}'").", ".(''==$uploadFileInfoArr['grace_time']?'default':"'{$uploadFileInfoArr['grace_time']}'").", ".(''==$uploadFileInfoArr['interval']?'default':"'{$uploadFileInfoArr['interval']}'").", '".$uploadFileInfoArr['profile']."', ".(''==$uploadFileInfoArr['seconds']?'default':"'{$uploadFileInfoArr['seconds']}'").", '".addslashes($uploadFileInfoArr['code_name'])."', ".(''==$uploadFileInfoArr['intrastate_rate']?'default':"'{$uploadFileInfoArr['intrastate_rate']}'").", ".(''==$uploadFileInfoArr['interstate_rate']?'default':"'{$uploadFileInfoArr['interstate_rate']}'").", ".(''==$uploadFileInfoArr['local_rate']?'default':"'{$uploadFileInfoArr['local_rate']}'").", '".$uploadFileInfoArr['country']."', '".$uploadFileInfoArr['timezone']."')";
					break;
				case 11:
//					$sql = "insert into {$dTable} (digits, strategy, resource_alias_1, percentage_1, resource_alias_2, percentage_2, resource_alias_3, percentage_3, resource_alias_4, percentage_4, resource_alias_5, percentage_5, resource_alias_6, percentage_6, resource_alias_7, percentage_7, resource_alias_8, percentage_8, time_profile_name) values ('".$uploadFileInfoArr['digits']."', '".$tr[$uploadFileInfoArr['strategy']]."', '".$uploadFileInfoArr['resource_alias_1']."', '".$uploadFileInfoArr['percentage_1']."', '".$uploadFileInfoArr['resource_alias_2']."', '".$uploadFileInfoArr['percentage_2']."', '".$uploadFileInfoArr['resource_alias_3']."', '".$uploadFileInfoArr['percentage_3']."', '".$uploadFileInfoArr['resource_alias_4']."', '".$uploadFileInfoArr['percentage_4']."', '".$uploadFileInfoArr['resource_alias_5']."', '".$uploadFileInfoArr['percentage_5']."', '".$uploadFileInfoArr['resource_alias_6']."', '".$uploadFileInfoArr['percentage_6']."', '".$uploadFileInfoArr['resource_alias_7']."', '".$uploadFileInfoArr['percentage_7']."', '".$uploadFileInfoArr['resource_alias_8']."', '".$uploadFileInfoArr['percentage_8']."', '".$uploadFileInfoArr['profile']."')";
						
					$sql = "insert into {$dTable} (digits, strategy, resource_alias, percentage, time_profile_name) values ('".$uploadFileInfoArr['digits']."', '".$tr[$uploadFileInfoArr['strategy']]."', '".$uploadFileInfoArr['trunk']."', '".$uploadFileInfoArr['percentage']."', '".$uploadFileInfoArr['profile']."')";
					break;
				case 12:
					$sql = "insert into {$dTable} (digits, route_type, dynamic_route_name,static_route_name) values ('".addslashes($uploadFileInfoArr['digits'])."', '".addslashes($uploadFileInfoArr['route_type'])."', '".addslashes($uploadFileInfoArr['dynamic_routing_name'])."', '".addslashes($uploadFileInfoArr['static_route_name'])."')";
					break;
				default:
					$sql = '';
			}
			if ($sql != '')
			{
				
				
				//对code 的特殊处理
				if (intval($uploadTable) == 9)
				{
					$prefix_arr = explode(",", $uploadFileInfoArr['prefix']);
					foreach ($prefix_arr as $k=>$v)
					{
						$range = explode("-", $v);
						if (isset($range[1]) && intval($range[1])>=intval($range[0]) )
						{
							for ($i=intval($range[0]); $i<=intval($range[1]); $i++)
							{
								$sql = "insert into {$dTable} (code,country,name) values ('".$i."', '".addslashes($uploadFileInfoArr['country'])."', '".addslashes($uploadFileInfoArr['code_name'])."')";
								$return = pg_query($this->dbConn, $sql);
							}
						}
						else 
						{
							$sql = "insert into {$dTable} (code,country,name) values ('".$range[0]."', '".addslashes($uploadFileInfoArr['country'])."', '".addslashes($uploadFileInfoArr['code_name'])."')";
							$return = pg_query($this->dbConn, $sql);
						}
					}
				}
				elseif (intval($uploadTable) == 11)
				{
						$trunk_alias_arr = explode(";", $uploadFileInfoArr['trunk']);
						$percentage_arr = explode(";", $uploadFileInfoArr['percentage']);
						if (!empty($trunk_alias_arr))
						{
							foreach ($trunk_alias_arr as $k=>$v)
							{
								$sql = "insert into {$dTable} (digits, strategy, resource_alias, percentage, time_profile_name) values ('".$uploadFileInfoArr['digits']."', '".$tr[$uploadFileInfoArr['strategy']]."', '".$v."', '".(empty($percentage_arr[$k])?'':$percentage_arr[$k])."', '".$uploadFileInfoArr['profile']."')";
								$return = pg_query($this->dbConn, $sql);
							}
						}
				}
				else 
				{
					$return = pg_query($this->dbConn, $sql);
				}
				if ($return === false)
				{
					echo "SQL ERROR: ".$sql.".\r\n";				
				}
				else
				{
					echo "SQL : ".$sql.".\r\n";
				}
			}
			
		}
		return $return;
	}

	/** 
	* @Purpose: 
	* 写入一行格式错误的csv行（异常处理）
	* @Method Name: csvErrorInfo() 
	* @Parameter: $errorFile 错误记录csv文件,$errorInfoLine=array() 格式错误报告信息数组
	* @Return: bool true写入正常，false未写入
	*/ 
	public function csvErrorInfo($errorFile, $errorInfoLine=array(), $handle = null)
	{
		$return = false;
		
		if ($handle)
		{
			$return = fputcsv($handle,$errorInfoLine); 
			//fclose($fp);
			return $return===false ? false : $handle;
		}
		else 
		{
			$dir = dirname($errorFile);
			if (!is_dir($dir))
			{
				mkdir($dir);		
			}
			$fp = fopen($errorFile, 'a');   
			$return = fputcsv($fp,$errorInfoLine); 
			//fclose($fp);
			return $return===false ? false : $fp;
		}
	}

	/** 
	* @Purpose: 
	* 检查唯一性
	* @Method Name: checkUnique() 
	* @Parameter: $tmpTable 临时表,$colName数据库字段, $str 待检查内容
	* @Return: int 0 唯一 >0已经有数据了
	*/ 
	public function checkUnique($tmpTable, $colName, $str)
	{
		$return = 0;
		
		if (!empty($colName) && !empty($tmpTable) && $str!='')
		$sql = "select count(*) from {$tmpTable} where {$colName} = '" . addslashes($str) . "'";
		$result = pg_query($this->dbConn, $sql);
		if ($row = pg_fetch_array($result))
		{
			$return = $row[0];
		}
		return $return;
	}

	/** 
	* @Purpose: 
	* 检查上传文件的格式
	* @Method Name: checkUploadData() 
	* @Parameter: $data 一行csv记录, $tmpTable临时表名, $uploadTable临时表检查格式的代码
	* @Return: array(bool true写入正常false未写入, array()返回错误报告要写进csv的数组)
	*/ 
	public function checkUploadData($data, $tmpTable, $uploadTable)
	{
		$return = array(0=>true,1=>$data);
		$size = count($data);
		$return[1][$size] = '';
		switch ($uploadTable)
		{
			case 1:			//-ingress
		//				if (!preg_match("/^[\.\/\_0-9a-z\- ]{1,40}$/i", $data['name']))		//name
//				{
//					$return[0] = false;
//					$return[1][$size] .= "name can not be NULL,length < 40!"; 
//				}	
				if (!preg_match("/^[TF]?$/", $data['active']))		//active
				{
					$return[0] = false;
					$return[1][$size] .= "<active> can be either T or F!"; 
				}	
				if (!preg_match("/^[TF]?$/", $data['t.38']))		//t38
				{
					$return[0] = false;
					$return[1][$size] .= "<t.38> can be either T or F!"; 
				}	
				if (!preg_match("/^\d{0,3}$/", $data['cps_limit']))		//cps_limit
				{
					$return[0] = false;
					$return[1][$size] .= "<cps_limit> can only be NULL or number in 0-999!"; 
				}
				if (!preg_match("/^\d{0,4}$/", $data['capacity']))		//capacity
				{
					$return[0] = false;
					$return[1][$size] .= "<capacity> can only be NULL or number in 0-9999!"; 
				}
				if (!preg_match("/^[TF]?$/", $data['lrn']))		//lnp
				{
					$return[0] = false;
					$return[1][$size] .= "<lrn> can be either T or F!"; 
				}
				if (!preg_match("/^[TF]?$/", $data['block_lrn']))		//lrn_block
				{
					$return[0] = false;
					$return[1][$size] .= "<block_lrn> can be either T or F!"; 
				}
				if (!preg_match("/^[\.\/\_0-9a-z\- ]+$/i", $data['carrier_name']))// == '')			//carrier_name
				{
					$return[0] = false;
					$return[1][$size] .= "<carrier_name> must contain alphanumeric characters only.";
				}	
//				if ($data['rate_table_name'] == '')			//rate_table_name
//				{
//					$return[0] = false;
//					$return[1][$size] .= "rate_table_name can not be NULL!";
//				}
//				if ($data['route_plan_name'] == '')			//route_plan_name
//				{
//					$return[0] = false;
//					$return[1][$size] .= "route_plan_name can not be NULL!";
//				}
				if (!preg_match("/^[TPB]?$/", $data['media_type']))		//media_type
				{
					$return[0] = false;
					$return[1][$size] .= "<media_type> can be T or P or B!"; 
				}
				if (!preg_match("/^[\.\/\_0-9a-z\- ]+$/i", $data['trunk_alias']))	//Trunk Alias
				{
					$return[0] = false;
					$return[1][$size] .= "<Trunk Alias> must contain alphanumeric characters only."; 
				}
				if (!preg_match("/^[SHA]?$/", $data['proto']))		//proto
				{
					$return[0] = false;
					$return[1][$size] .= "<proto> can only be S or H or A!"; 
				}	
//				if (!preg_match("/^[TF]$/", $data['dnis_only']))		//dnis_only
//				{
//					$return[0] = false;
//					$return[1][$size] .= "dnis_only can only be T or F!"; 
//				}
//				if (!preg_match("/^\d{0,3}$/", $data['ring_timeout']))		//ring_timeout
//				{
//					$return[0] = false;
//					$return[1][$size] .= "ring_timeout can only be NULL or number in 0-999!"; 
//				}
//				if (!preg_match("/^[TF]$/", $data['ignore_ring']))		//ignore_ring
//				{
//					$return[0] = false;
//					$return[1][$size] .= "ignore_ring can only be T or F!"; 
//				}
//				if (!preg_match("/^[TF]$/", $data['ignore_early_media']))		//ignore_early_media
//				{
//					$return[0] = false;
//					$return[1][$size] .= "ignore_early_media can only be T or F!"; 
//				}
//				if (!preg_match("/^[\.\/\_0-9a-z\- ]+$/i", $data['codec']))		//codec
//				{
//					$return[0] = false;
//					$return[1][$size] .= "codec can not be NULL."; 
//				}
//				else 
//				{
//					if ($this->checkUnique($tmpTable,'codec', $data['codec']))
//					{
//						$return[0] = false;
//						$return[1][$size] .= "codec must be unique."; 
//					}
//				}
				break;
			case 2:			//-egress
//				if (!preg_match("/^[\.\/\_0-9a-z\- ]{1,40}$/i", $data['name']))		//name
//				{
//					$return[0] = false;
//					$return[1][$size] .= "name can not be NULL,length < 40!"; 
//				}	
				if (!preg_match("/^[TF]?$/", $data['active']))		//active
				{
					$return[0] = false;
					$return[1][$size] .= "<active> can be either T or F!"; 
				}	
				if (!preg_match("/^[TF]?$/", $data['t.38']))		//t38
				{
					$return[0] = false;
					$return[1][$size] .= "<t.38> can be either T or F!"; 
				}	
				if (!preg_match("/^[TRP]?$/", $data['host_strategy']))		//res_strategy
				{
					$return[0] = false;
					$return[1][$size] .= "<host_strategy> can be T or R or P!"; 
				}
				if (!preg_match("/^\d{0,3}$/", $data['cps_limit']))		//cps_limit
				{
					$return[0] = false;
					$return[1][$size] .= "<cps_limit> can only be NULL or number in 0-999!"; 
				}
				if (!preg_match("/^\d{0,4}$/", $data['capacity']))		//capacity
				{
					$return[0] = false;
					$return[1][$size] .= "<cps_limit> can only be NULL or number in 0-9999!"; 
				}
				if (!preg_match("/^[TF]?$/", $data['lrn']))		//lnp
				{
					$return[0] = false;
					$return[1][$size] .= "<lrn> can be either T or F!"; 
				}
				if (!preg_match("/^[TF]?$/", $data['block_lrn']))		//lrn_block
				{
					$return[0] = false;
					$return[1][$size] .= "<block_lrn> can be either T or F!"; 
				}
				if (!preg_match("/^[\.\/\_0-9a-z\- ]+$/i", $data['carrier_name']))			//carrier_name
				{
					$return[0] = false;
					$return[1][$size] .= "<carrier_name> must contain alphanumeric characters only.";
				}	
//				if ($data['rate_table_name'] == '')			//rate_table_name
//				{
//					$return[0] = false;
//					$return[1][$size] .= "rate_table_name can not be NULL!";
//				}
//				if ($data['route_plan_name'] == '')			//route_plan_name
//				{
//					$return[0] = false;
//					$return[1][$size] .= "route_plan_name can not be NULL!";
//				}
				if (!preg_match("/^[TPB]?$/", $data['media_type']))		//media_type
				{
					$return[0] = false;
					$return[1][$size] .= "<media_type> can be T or P or B!"; 
				}
				if (!preg_match("/^[\.\/\_0-9a-z\- ]+$/i", $data['trunk_alias']))		//Trunk Alias
				{
					$return[0] = false;
					$return[1][$size] .= "<Trunk Alias> must contain alphanumeric characters only."; 
				}
				if (!preg_match("/^[SHA]?$/", $data['proto']))		//proto
				{
					$return[0] = false; 
					$return[1][$size] .= "<Proto> can only be S or H or A!"; 
				}	
//				if (!preg_match("/^[TF]$/", $data['dnis_only']))		//dnis_only
//				{
//					$return[0] = false;
//					$return[1][$size] .= "dnis_only can only be T or F!"; 
//				}
//				if (!preg_match("/^\d{0,3}$/", $data['ring_timeout']))		//ring_timeout
//				{
//					$return[0] = false;
//					$return[1][$size] .= "ring_timeout can only be NULL or number in 0-999!"; 
//				}
//				if (!preg_match("/^[TF]$/", $data['ignore_ring']))		//ignore_ring
//				{
//					$return[0] = false;
//					$return[1][$size] .= "ignore_ring can only be T or F!"; 
//				}
//				if (!preg_match("/^[TF]$/", $data['ignore_early_media']))		//ignore_early_media
//				{
//					$return[0] = false;
//					$return[1][$size] .= "ignore_early_media can only be T or F!"; 
//				}
//				if (!preg_match("/^[\.\/\_0-9a-z\- ]+$/i", $data['codec']))		//codec
//				{
//					$return[0] = false;
//					$return[1][$size] .= "codec can not be NULL."; 
//				}
//				else 
//				{
//					if ($this->checkUnique($tmpTable,'codec', $data['codec']))
//					{
//						$return[0] = false;
//						$return[1][$size] .= "codec must be unique."; 
//					}
//				}
				break;
			case 3:			//-host
				if (!preg_match("/^(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.)(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.){2}([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))|(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\/([1-9]|([1]\d)|2[0-9]|3[0-2]))$/", $data['ip']))		//ip
				{
					$return[0] = false;
					$return[1][$size] .= "<ip> can be aaa.bbb.ccc.ddd or aaa.bbb.ccc.ddd/xx."; 
				}
				if (!preg_match("/^\d{1,5}$/", $data['port']) || intval($data['port'])>65535 || intval($data['port'])<1)		//port
				{
					$return[0] = false;
					$return[1][$size] .= "<port> can only in 1-65536."; 
				}
				if (!preg_match("/^[\.\/\_0-9a-z\- ]+$/i", $data['trunk_alias']))		//Trunk Alias
				{
					$return[0] = false;
					$return[1][$size] .= "<Trunk Alias> must contain alphanumeric characters only."; 
				}
				
				break;
			case 4:			//-resource action
				if (!preg_match("/^[\.\/\_0-9a-z\- ]+$/i", $data['trunk_alias']))		//Trunk Alias
				{
					$return[0] = false;
					$return[1][$size] .= "<Trunk Alias> must contain alphanumeric characters only."; 
				}
//				if ('' == $data['resource_name'])							//resource_name
//				{
//					$return[0] = false;
//					$return[1][$size] .= "<resource_name> can not be NULL."; 
//				}
				if ('I' != $data['direction'] && 'E' != $data['direction'])							//direction
				{
					$return[0] = false;
					$return[1][$size] .= "<direction> can be either I or E."; 
				}
				if (!preg_match("/^\d$/", $data['action']))		//action
				{
					$return[0] = false;
					$return[1][$size] .= "<action> can only be small integer."; 
				}
				if (!preg_match("/^\d*$/", $data['digits']))		//digits
				{
					$return[0] = false;
					$return[1][$size] .= "<digits> can only be integer."; 
				}
				if (!preg_match("/^[0-9]*$/", $data['dnis']))		//dnis prefix_range
				{
					$return[0] = false;
					$return[1][$size] .= "<dnis> can be NULL or integer."; 
				}
//				if ('' == $data['time_profile'])							//time_profile
//				{
//					$return[0] = false;
//					$return[1][$size] .= "<Time Profile> can not be NULL."; 
//				}
				if (!preg_match("/^\d+$/", $data['type']))		//type
				{
					$return[0] = false;
					$return[1][$size] .= "<type> can only be integer."; 
				}
				if (!preg_match("/^[0123]?$/", $data['number_type']))		//number_type
				{
					$return[0] = false;
					$return[1][$size] .= "<number_type> can only be integer 0-3 or null."; 
				}
				if (intval($data['number_type']) > 0)
				{
						if (!preg_match("/^\d{1,2}$/", $data['number_length']))		//number_length
						{
							$return[0] = false;
							$return[1][$size] .= "<number_length> can be integer 0-99 when Number Type > 0."; 
						}
				}
				break;
			case 5:			//-resource digit mapping
				if (!preg_match("/^[\.\/\_0-9a-z\- ]+$/i", $data['resource_alias']))		//Resource Alias
				{
					$return[0] = false;
					$return[1][$size] .= "<Resource Alias> must contain alphanumeric characters only."; 
				}
				if (!preg_match("/^[\.\/\_0-9a-z\- ]+$/i", $data['translation_name']))		//Translation Name
				{
					$return[0] = false;
					$return[1][$size] .= "<Translation Name> must contain alphanumeric characters only."; 
				}

				break;
			case 6:			//-digit translation
				if (!preg_match("/^\d+$/", $data['translated_ani']))							//Translated ANI
				{
					$return[0] = false;
					$return[1][$size] .= "<Translated ANI> must be integer."; 
				}
				if (!preg_match("/^\d+$/", $data['translated_dnis'])	)						//Translated DNIS
				{
					$return[0] = false;
					$return[1][$size] .= "<Translated DNIS> must be integer."; 
				}
				if (!preg_match("/^[ICR]$/", $data['ani_action']))	//('ignore' != $data['ani_method'] && 'compare' != $data['ani_method'] && 'replace' != $data['ani_method'])							//ANI Action
				{
					$return[0] = false;
					$return[1][$size] .= "<ANI Action> can be I or C or R."; 
				}
				if (!preg_match("/^[ICR]$/", $data['dnis_action']))	//('ignore' != $data['dnis_method'] && 'compare' != $data['dnis_method'] && 'replace' != $data['dnis_method'])							//DNIS Action
				{
					$return[0] = false;
					$return[1][$size] .= "<DNIS Action> can be I or C or R."; 
				}

				break;
			case 7:			//-resource block
				if (!preg_match("/^\d+$/", $data['prefix']))
				{
					$return[0] = false;
					$return[1][$size] .= "<Prefix> can be only integer."; 					
				}
				if (!preg_match("/^[\.\/\_0-9a-z\- ]+$/i", $data['engress_trunk']))		//Egress Trunk
				{
					$return[0] = false;
					$return[1][$size] .= "<Engress Trunk> must contain alphanumeric characters only."; 
				}
				if (!preg_match("/^[\.\/\_0-9a-z\- ]+$/i", $data['ingress_trunk']))		//Ingress Trunk
				{
					$return[0] = false;
					$return[1][$size] .= "<Ingress Trunk> must contain alphanumeric characters only."; 
				}
				break;
			case 8:			//-jurisdiction
//				if (!preg_match('/^[\.\/\_0-9a-z\- ]{1,16}$/i', $data['alias']))							//alias
//				{
//					$return[0] = false;
//					$return[1][$size] .= "<alias> must contain alphanumeric characters only."; 
//				}
				if (!preg_match("/^\d+$/", $data['prefix'])	)						//prefix
				{
					$return[0] = false;
					$return[1][$size] .= "<prefix> must be integer."; 
				}
				if ('' == $data['jurisdiction_name'])							//jurisdiction_name
				{
					$return[0] = false;
					$return[1][$size] .= "<jurisdiction_name> can not be NULL."; 
				}
				if ('' == $data['state'])							//state
				{
					$return[0] = false;
					$return[1][$size] .= "<State> can not be NULL."; 
				}

				break;
			case 9:			//-code deck
				if (!preg_match("/^([0-9]+)$|^([0-9]+[0-9\-,]*)$/", $data['prefix']))							//code
				{
					$return[0] = false;
					$return[1][$size] .= "<prefix> can be integer or range."; 
				}
				else
				{
					$range = explode("-", $data['prefix']);
					if (isset($range[1]) && intval($range[1])<intval($range[0]) )
					{
						$return[0] = false;
						$return[1][$size] .= "<prefix> is not a correct range.";
					}
				}
//				if (!preg_match("/^[\.\/\_0-9a-zA-Z\- ]+$/",$data['code_name']))
//				{
//					$return[0] = false;
//					$return[1][$size] .= "<code_name> must contain alphanumeric characters only.";
//				}
//				if (!preg_match("/^[\.\/\_0-9a-zA-Z\- ]+$/",$data['country']))
//				{
//					$return[0] = false;
//					$return[1][$size] .= "<country> must contain alphanumeric characters only.";
//				}

				break;
			case 10:		//-rate
				/*if ('' == $data['rate_table_name'])							//rate_table_name
				{
					$return[0] = false;
					$return[1][$size] .= "rate_table_name can not be NULL."; 
				}*/
				/*if (!preg_match("/^[\.\/\_0-9a-zA-Z\- ]*$/",$data['country']))
				{
					$return[0] = false;
					$return[1][$size] .= "<country> must contain alphanumeric characters only.";
				}
				if (!preg_match("/^\d+$/", $data['code']))							//code
				{
					$return[0] = false;
					$return[1][$size] .= "<code> must be integer."; 
				}*/
				if ($data['effective_date'] == '')
				{
					$return[0] = false;
					$return[1][$size] .= "<effective_date> can not be null.";
				}
                                /*
				if (!preg_match("/^\d+(\.\d*)*$/", $data['rate']))		//rate numeric(30,10)
				{
					$return[0] = false;
					$return[1][$size] .= "<rate> can only numeric."; 
				}
                                 * 
                                 */
				if (!preg_match("/^\d*(\.\d*)*$/", $data['setup_fee']))		//setup_fee numeric(30,10)
				{
					$return[0] = false;
					$return[1][$size] .= "<setup_fee> can only numeric."; 
				}
				if (!preg_match("/^[0-9-+: ]*$/", $data['effective_date']))		//effective_date
				{
					$return[0] = false;
					$return[1][$size] .= "<effective_date> can only be time zone."; 
				}
				if (!preg_match("/^[0-9-+: ]*$/", $data['end_date']) && $data['end_date']!='')		//end_date
				{
					$return[0] = false;
					$return[1][$size] .= "<end_date> can be NULL or time zone."; 
				}
				if (!preg_match("/^\d*$/", $data['min_time']))		//min_time
				{
					$return[0] = false;
					$return[1][$size] .= "<min_time> can only integer."; 
				}
				if (!preg_match("/^\d*$/", $data['grace_time']))		//grace_time
				{
					$return[0] = false;
					$return[1][$size] .= "<grace_time> can only integer."; 
				}
				if (!preg_match("/^\d*$/", $data['interval']))		//interval
				{
					$return[0] = false;
					$return[1][$size] .= "<interval> can only integer."; 
				}
				if (!preg_match("/^\d*$/", $data['seconds']))		//seconds
				{
					$return[0] = false;
					$return[1][$size] .= "<seconds> can only integer."; 
				}
                                /*
				if (!preg_match("/^\d*(\.\d*)*$/", $data['intrastate_rate']))		//intra_rate numeric(30,10)
				{
					$return[0] = false;
					$return[1][$size] .= "<intrastate_rate> can only numeric."; 
				}
                                 * 
                                 */
				/*if (!preg_match("/^\d*(\.\d*)*$/", $data['profile']))		//profile integer
				{
					$return[0] = false;
					$return[1][$size] .= "Profile can only numeric."; 
				}*/
                                /*
				if (!preg_match("/^\d*(\.\d*)*$/", $data['interstate_rate']))		//inter_rate numeric(30,10)
				{
					$return[0] = false;
					$return[1][$size] .= "<interstate_rate> can only numeric."; 
				}
				if (!preg_match("/^\d*(\.\d*)*$/", $data['local_rate']))		//local_rate numeric(30,10)
				{
					$return[0] = false;
					$return[1][$size] .= "<local_rate> can only numeric."; 
				}
                                */
				break;
			case 11:		//-static route
				if ('' == $data['digits'])							//digits
				{
					$return[0] = false;
					$return[1][$size] .= "<digits> can not be NULL."; 
				}
				if (!preg_match("/^[TRP]$/", $data['strategy']))		//strategy
				{
					$return[0] = false;
					$return[1][$size] .= "<strategy> can be T or R or P."; 
				}
				if (!preg_match("/^[\.\/\_0-9a-zA-Z\- ;]*$/", $data['trunk']))		//trunk
				{
					$return[0] = false;
					$return[1][$size] .= "<trunk> must contain alphanumeric characters only."; 
				}
				if (!preg_match("/^[\.\/\_0-9a-z\- ;]*$/", $data['percentage']))		//percentage
				{
					$return[0] = false;
					$return[1][$size] .= "<percentage> must contain alphanumeric characters only."; 
				}
				
				if (!preg_match("/^[\.\/\_0-9a-z\- ]+$/i", $data['profile']))		//profile
				{
					$return[0] = false;
					$return[1][$size] .= "<profile>  must contain alphanumeric characters only."; 
				}
//				if ('P' == $data['strategy'])
//				{
//					$percent = floatval($data['percentage_1'])+ floatval($data['percentage_2']) + floatval($data['percentage_3']) + floatval($data['percentage_4']) + floatval($data['percentage_5']) + floatval($data['percentage_6']) + floatval($data['percentage_7']) + floatval($data['percentage_8']);
//					if ($percent > 100)
//					{
//						$return[0] = false;
//						$return[1][$size] .= "add up all <percentage>  must less than 100."; 
//					}
//				}

				break;
			case 12:
				if (!preg_match("/^[1-3]$/", $data['route_type']))
				{
					$return[0] = false;
					$return[1][$size] .= "<Route Type> can only be integer 1-3."; 
				}
				break;
			default:
				$return[0] = true;
				$return[1] = $data;
		}
		return $return;
	}

	public function columnize($lowerCaseAndUnderscoredWord)
	{
		return str_replace(" ", "_", strtolower(trim($lowerCaseAndUnderscoredWord)));
	}
	
	
	
	
	
/**
 * @Puerpose
 * 
 * 循环处理文件中的数据
 * 
 * 
 * 
 */	
public   function  while_hander_file_data($handle,$row_in,$row_start,$UploadFileInfo,$row_error,$error_file,$error_flag,$error_csv_flag,$row,$time_start){
				while ($data = fgetcsv($handle, 2000, ","))
			{//echo $data[0],"\r\n";
				$data_arr = array();
				if ($row_in == 1)
				{
					$col_name_arr = $data;	
				}
				else 
				{
					foreach($data as $k=>$v)
					{
						$data_arr[$this->columnize($col_name_arr[$k])] = trim($data[$k]);
					}
				}//var_dump($data_arr);echo "\r\n";
				$row_in++;
				$rid = 0;
				//检查上传文件的格式
				if ($row_in > $row_start)
				{
					$ret = $this->checkUploadData($data_arr, $UploadFileInfo['upload_table'], $UploadFileInfo['upload_type']);
				}
				else
				{
					$ret[0] = true;
				}
				if ($ret[0]==true)
				{
					$uploadFileInfoArr_tmp = $data_arr;
					if ($row_in > $row_start)
					{
						
						//将文件数据插入临时表
						$rid = $this->InsertMessageInfo($UploadFileInfo['upload_table'], $UploadFileInfo['upload_type'], $uploadFileInfoArr_tmp, $UploadFileInfo['foreign_id'], $UploadFileInfo);//入库
					}
				}
				else		//错误行数写进错误记录csv中
				{
					$row_error++;
					if ($error_csv_flag == 0)
					{
						if ($this->csvErrorInfo($error_file, $col_name_arr))
						{
							$error_csv_flag = 1;
						}				
					}
					$this->csvErrorInfo($error_file, $ret[1]);
					if ($error_flag == 0)
					{
						$error_flag = pg_query($this->dbConn, "update import_export_logs set error_file_path = '".$error_file."' where id = " . $UploadFileInfo['id']);
					}					
				}
				
				//csv数据插入数据库记录报告
				if ($rid)
				{
					$row++;
				}
				elseif ($row_in > $row_start)
				{
					//$massage .= "第".($row_in-$row_start+1)."行录入失败，原因:未能插入数据库，检查是否格式有误。";
					$massage .= "line".($row_in-$row_start+1)."insert error";
				}
				else
				{
					//void
				}
				//不要这么着急，休息一下,给数据库一个缓冲时间
				if ($row_in%1000==0)
				{
					//$row = 0;
					pg_query($this->dbConn, "update import_export_logs set php_process_number = {$row_in} where id = " . $UploadFileInfo['id']);
					echo "update import_export_logs set php_process_number = {$row_in} where id = " . $UploadFileInfo['id'],"\r\n";
					$time_end = microtime(true);
					echo "\r\n No. {$row_in}, time used(micro second):",$time_end-$time_start, "\r\n";
				}
				/*if ($row_in%20000==0)
				{
					sleep(1);
				}*/
			}
			
		return compact('handle','row_in','row_start','UploadFileInfo','row_error','error_file','error_flag','error_csv_flag','row','time_start');
	
}


/** 
	* @Purpose: 
	* 从上传文件中获取信息
	* @Method Name: GetInfoFromFile() 
	* @Parameter:  $UploadFileInfo 上传文件的数据库信息    import_export_logs表的一条记录
	* @Return: int 8 8全部数据处理完成 10文件信息错误
	*/ 
	public function GetInfoFromFile($UploadFileInfo = array())
	{
		$return = 0;
		if (empty($UploadFileInfo))
		{
			$return = 10;
			echo "upload File not exist.";
		}	
		//是否有表头
		$row_start = 2;
		
		$massage = '';
		
		$error_file = preg_replace("/([0-9A-Za-z\-\/]*)(\/[^\/\.]+\.csv)/", "\\1/error\\2", $UploadFileInfo['file_path']);
		//if (!class_exists("Spreadsheet_Excel_Reader"))处理microsoft office excel文件时的控件
		//{
		//	require $_SERVER['DOCUMENT_ROOT'] . "./Excel/reader.php";
		//}		
		$importfile = $filename = $UploadFileInfo['file_path'];

		if (preg_match("/xls$/", $filename))
		{
			//暂不处理此类型文件	
		}
		elseif (preg_match("/csv$/", $filename))
		{
			$time_start = microtime(true);	//调试		
			//setlocale(LC_ALL, 'zh_CN.gb2312');//中文内容处理
			$error_flag = 0;	//写入import_export_logs标记
			$error_csv_flag = 0;	//新建csv表头标记
			$row = 1;	//记录入库成功
			$row_in = 1;
			$row_error = 0;
			$handle = fopen($importfile, "r");
			$col_name_arr = array();

			//while hander file   per row data
			extract($this->while_hander_file_data($handle,$row_in,$row_start,$UploadFileInfo,$row_error,$error_file,$error_flag,$error_csv_flag,$row,$time_start));
			
			
//			while ($data = fgetcsv($handle, 2000, ","))
//			{//echo $data[0],"\r\n";
//				$data_arr = array();
//				if ($row_in == 1)
//				{
//					$col_name_arr = $data;	
//				}
//				else 
//				{
//					foreach($data as $k=>$v)
//					{
//						$data_arr[$this->columnize($col_name_arr[$k])] = $data[$k];
//					}
//				}//var_dump($data_arr);echo "\r\n";
//				$row_in++;
//				$rid = 0;
//				//检查上传文件的格式
//				if ($row_in > $row_start)
//				{
//					$ret = $this->checkUploadData($data_arr, $UploadFileInfo['upload_table'], $UploadFileInfo['upload_type']);
//				}
//				else
//				{
//					$ret[0] = true;
//				}
//				if ($ret[0]==true)
//				{
//					$uploadFileInfoArr_tmp = $data_arr;
//					if ($row_in > $row_start)
//					{
//						$rid = $this->InsertMessageInfo($UploadFileInfo['upload_table'], $UploadFileInfo['upload_type'], $uploadFileInfoArr_tmp);//入库
//					}
//				}
//				else		//错误行数写进错误记录csv中
//				{
//					$row_error++;
//					if ($error_csv_flag == 0)
//					{
//						if ($this->csvErrorInfo($error_file, $col_name_arr))
//						{
//							$error_csv_flag = 1;
//						}				
//					}
//					$this->csvErrorInfo($error_file, $ret[1]);
//					if ($error_flag == 0)
//					{
//						$error_flag = pg_query($this->dbConn, "update import_export_logs set error_file_path = '".$error_file."' where id = " . $UploadFileInfo['id']);
//					}					
//				}
//				
//				//csv数据插入数据库记录报告
//				if ($rid > 0)
//				{
//					$row++;
//				}
//				elseif ($row_in > $row_start)
//				{
//					//$massage .= "第".($row_in-$row_start+1)."行录入失败，原因:未能插入数据库，检查是否格式有误。";
//					$massage .= "line".($row_in-$row_start+1)."insert error";
//				}
//				else
//				{
//					//void
//				}
//				//不要这么着急，休息一下,给数据库一个缓冲时间
//				if ($row_in%1000==0)
//				{
//					//$row = 0;
//					pg_query($this->dbConn, "update import_export_logs set php_process_number = {$row_in} where id = " . $UploadFileInfo['id']);
//					echo "update import_export_logs set php_process_number = {$row_in} where id = " . $UploadFileInfo['id'],"\r\n";
//					$time_end = microtime(true);
//					echo "\r\n No. {$row_in}, time used(micro second):",$time_end-$time_start, "\r\n";
//				}
//				if ($row_in%20000==0)
//				{
//					sleep(1);
//				}
//			}
//			
			
			fclose($handle);
			if ($UploadFileInfo['upload_type'] == 10)
			{
				$upd = pg_query($this->dbConn, "update import_export_logs set php_process_number = " . ($row-1) . ", error_row = ".$row_error .", duplicate_numbers = ".($row_in-1-$row-$row_error)." where id = " . $UploadFileInfo['id']);
			}
			else {
				$upd = pg_query($this->dbConn, "update import_export_logs set php_process_number = " . ($row-1) . ", error_row = ".$row_error ." where id = " . $UploadFileInfo['id']);
			}
			$return = 8;//"共".($row_in-2)."条记录，成功导入了".($row-1)."条记录.";
			if ($UploadFileInfo['upload_type'] == 10)
			{
				$return = 6;
			}
			if ($massage!='')
			{
				$return .= $massage;
			}			
		}
		else
		{
			//VOID
		}
		return $return;
	}
	
//copy table from file header csv
	public function CopyFromFile($UploadFileInfo = array())
	{
		$return = 0;
		if (empty($UploadFileInfo))
		{
			$return = 10;
			echo "upload File not exist.";
		}	
		//是否有表头
		$row_start = 2;
		
		$massage = '';
		
		$error_file = preg_replace("/([0-9A-Za-z\-\/]*)(\/[^\/\.]+\.csv)/", "\\1/error\\2", $UploadFileInfo['file_path']);
		//if (!class_exists("Spreadsheet_Excel_Reader"))处理microsoft office excel文件时的控件
		//{
		//	require $_SERVER['DOCUMENT_ROOT'] . "./Excel/reader.php";
		//}		
		$importfile = $filename = $UploadFileInfo['file_path'];

		if (preg_match("/xls$/", $filename))
		{
			//暂不处理此类型文件	
		}
		elseif (preg_match("/csv$/", $filename))
		{
			$time_start = microtime(true);	//调试		
			
			echo $sql = "copy {$UploadFileInfo['upload_table']} from {$filename} header csv";
			echo "\r\n";
			$result = @pg_query($this->dbConn, "copy {$UploadFileInfo['upload_table']} from '{$filename}' header csv");
			$db_error = pg_result_error($result);
			var_dump($db_error);
			
			$time_end = microtime(true);
				echo "\r\n time used(micro second):",$time_end-$time_start, "\r\n";

			if ($massage!='')
			{
				$return .= $massage;
			}			
		}
		else
		{
			//VOID
		}
		return $return;
	}
	
	//处理rate上传
	public function rateUploadAct($UploadFileInfo = array())
	{
		$return = 0;
		
		if (empty($UploadFileInfo))
		{
			$return = 10;
			echo "upload File not exist.";
		}	
		$checked_file = dirname($_SERVER['SCRIPT_FILENAME']) . "/upload_act/" . basename($UploadFileInfo['file_path'], '.csv') . '_checked.csv';
		$redup_file = dirname($_SERVER['SCRIPT_FILENAME']) . "/upload_act/" . basename($UploadFileInfo['file_path'], '.csv') . '_redup.csv';
		$sql_file = dirname($_SERVER['SCRIPT_FILENAME']) . "/upload_act/" . basename($UploadFileInfo['file_path'], '.csv') . '_sql.csv';
	//是否有表头
		$row_start = 2;
		
		$massage = '';
		
		$error_file = preg_replace("/([0-9A-Za-z\-\/]*)(\/[^\/\.]+\.csv)/", "\\1/error\\2", $UploadFileInfo['file_path']);
		//if (!class_exists("Spreadsheet_Excel_Reader"))处理microsoft office excel文件时的控件
		//{
		//	require $_SERVER['DOCUMENT_ROOT'] . "./Excel/reader.php";
		//}		
		if (!is_dir("/tmp/exports"))
		{
			mkdir("/tmp/exports",0777);
		}
		if (!is_dir(dirname($error_file)))
		{
			mkdir(dirname($error_file));
		}
		echo "\r\n Import File:";
		echo $importfile = $filename = $UploadFileInfo['file_path'];
		
		$handle_checked = null;
		//$db_rate_arr = array();
		//$db_rate_query = pg_query($this->dbConn, "select rate_id,code,effective_date,end_date from rate where rate_table_id = {$UploadFileInfo['foreign_id']}");
		//$handle_sql = $this->csvErrorInfo($sql_file, array('rate_id', 'code', 'effective_date', 'end_date'));
		
		/*while ($row_rate = pg_fetch_array($db_rate_query, 0, PGSQL_ASSOC))
		{
			//$db_rate_arr[$row_rate['code']] = $row_rate;
			$handle_sql = $this->csvErrorInfo($sql_file, $row_rate, $handle_sql);
		}
		exit;*/
		if (preg_match("/xls$/", $filename))
		{
			//暂不处理此类型文件	
		}
		elseif (preg_match("/csv$/", $filename))
		{
			$time_start = microtime(true);	//调试		
			$error_flag = 0;	//写入import_export_logs标记			
			$error_csv_flag = 0;	//新建csv表头标记
			$true_csv_flag = 0;	//新建csv表头标记
			
			$row = 1;	//记录入库成功
			$row_in = 1;
			$row_error = 0;
			$row_true = 0;
			$handle = fopen($importfile, "r");
			$col_name_arr = array();

			//while hander file   per row data
			while ($data = fgetcsv($handle, 2000, ","))
			{//echo $data[0],"\r\n";
				$data_arr = array();
				if ($row_in == 1)
				{
					$col_name_arr = $data;	
				}
				else 
				{
					foreach($data as $k=>$v)
					{
						$data_arr[$this->columnize($col_name_arr[$k])] = trim($data[$k]);
					}
				}//var_dump($data_arr);echo "\r\n";
				$row_in++;
				$rid = 0;
				//检查上传文件的格式
				if ($row_in > $row_start)
				{
					$ret = $this->checkUploadData($data_arr, $UploadFileInfo['upload_table'], $UploadFileInfo['upload_type']);
				}
				else
				{
					$ret[0] = true;
				}
				
				if ($ret[0]==true)
				{
					$uploadFileInfoArr_tmp = $data_arr;
					$row_true++;
					if ($true_csv_flag == 0)
					{
						$col_name_arr1 = array();
						foreach ($col_name_arr as $k=>$v)
						{
							$col_name_arr1[$k] = str_replace(' ', '_', strtolower(trim($v)));
						}
						if ($handle_checked = $this->csvErrorInfo($checked_file, $col_name_arr1, $handle_checked) )// && $this->csvErrorInfo($redup_file, $col_name_arr1))
						{
							$true_csv_flag = 1;
						}				
					}
					if (!empty($ret[1]))
					{
						$handle_checked = $this->csvErrorInfo($checked_file, $ret[1], $handle_checked);
						/*$flag_redup = 0;
						if ($db_rate_arr[$ret[1]['code']] == $ret[1]['effective_date'])
						{
								$flag_redup = 1;						
						}
						if ($flag_redup)
						{
								$this->csvErrorInfo($redup_file, $ret[1]);
						}
						else 
						{
								$this->csvErrorInfo($checked_file, $ret[1]);
						}*/
					}
							
				}
				else		//错误行数写进错误记录csv中
				{
					$row_error++;
					if ($error_csv_flag == 0)
					{
						if ($this->csvErrorInfo($error_file, $col_name_arr))
						{
							$error_csv_flag = 1;
						}				
					}
					$this->csvErrorInfo($error_file, $ret[1]);
					if ($error_flag == 0)
					{
						$error_flag = pg_query($this->dbConn, "update import_export_logs set error_file_path = '".$error_file."' where id = " . $UploadFileInfo['id']);
					}					
				}	
			}
			fclose($handle);
			fclose($handle_checked);
		}
		
		$time_end = microtime(true);
		echo "\r\n No. {$row_in}, currect number:{$row_true}, error number: {$row_error}. time used(micro second):",$time_end-$time_start, "\r\n";
		
		$sb_act_num_sql =  pg_query($this->dbConn, "select count(*) as c from import_export_logs where status = 7"); 
		if (0)//($sb_act_num = pg_fetch_array($sb_act_num_sql))
		{
			if ($sb_act_num['c'] == 0)
			{
				echo $a_z_sql = "copy (select case country when '' then null else country end	as \"Country\", name as \"Code Name\",  Code as \"Prefix\" from code where code_deck_id = (select code_deck_id from code_deck where name = 'A-Z' order by code_deck_id limit 1) order by country) to '/tmp/exports/A-Z.csv' delimiter as  ',' header csv";
				echo "\r\n";
				pg_query($this->dbConn, $a_z_sql);
				copy("/tmp/exports/A-Z.csv", dirname($_SERVER['SCRIPT_FILENAME']) . "/upload_act/A-Z.csv");
			}
		}			
		
		pg_query($this->dbConn, "update import_export_logs set status = 7 where id = " . $UploadFileInfo['id']);
		
		$rate_table_sql = pg_query($this->dbConn, "select code_deck_id from rate_table where rate_table_id = {$UploadFileInfo['foreign_id']}");
		$rate_table_result = pg_fetch_array($rate_table_sql);
		$code_deck_id = empty($rate_table_result['code_deck_id']) ? 0 : $rate_table_result['code_deck_id'];
		
			
		if (file_exists($checked_file))
		{
			pg_query($this->dbConn, "copy (select rate_id,code,effective_date,end_date from rate where rate_table_id = {$UploadFileInfo['foreign_id']}) to '/tmp/exports/".basename($sql_file,'.csv')."' delimiter as ',' header csv");
			copy("/tmp/exports/".basename($sql_file,'.csv'), $sql_file);
			echo "check_rate command:";
			$checked = basename($checked_file, ".csv");
                        $custom_end_date = $UploadFileInfo['custom_end_date'] == '' ? '' : " -T {$UploadFileInfo['custom_end_date']} ";
			if (0)//($UploadFileInfo['duplicate_type'] == 'delete')
			{
				echo $exec = dirname($_SERVER['SCRIPT_FILENAME']) . "/upload_act/import_rate -n {$checked} -o " . basename($sql_file, '.csv') . " -u " . basename($UploadFileInfo['file_path'], '.csv') . " -t " . $UploadFileInfo['foreign_id'] . " -c {$code_deck_id} -d delete  -p " . dirname($_SERVER['SCRIPT_FILENAME']) . "/upload_act";
			}
			else 
			{
				echo $exec = dirname($_SERVER['SCRIPT_FILENAME']) . "/upload_act/import_rate -n {$checked}" . " -U " .  $UploadFileInfo['auto_enddate']  . " -o " . basename($sql_file, '.csv') . " -u " . basename($UploadFileInfo['file_path'], '.csv') . " -t " . $UploadFileInfo['foreign_id'] . " -c {$code_deck_id} -d {$UploadFileInfo['duplicate_type']} {$custom_end_date} -p " . dirname($_SERVER['SCRIPT_FILENAME']) . "/upload_act";
			}
			echo "\r\n";
			echo exec($exec . " > /tmp/csv2sql.rate_table.log");
			echo "\r\n";
		//处理_new.csv copy
			/*copy(dirname($_SERVER['SCRIPT_FILENAME']) . "/upload_act/" . basename($UploadFileInfo['file_path']), "/tmp/exports/" . basename($UploadFileInfo['file_path'], ".csv") . "_new.csv");
			pg_query($this->dbConn, "copy rate from '/tmp/exports/" . basename($UploadFileInfo['file_path'], ".csv") . "_new.csv' header csv");
		//处理_end.csv update
		
		//处理_redup.csv 重复记录文件 ignore不处理， 覆盖则delete后insert
			if ($UploadFileInfo['duplicate_type'] == 'delete')
			{
				
			}
			else
			{
				//ignore
			}*/
			$logfile = dirname($error_file)  . "/" . basename($UploadFileInfo['file_path'], '.csv') . ".log";
			$log_file_checked = dirname($_SERVER['SCRIPT_FILENAME']) . "/upload_act/" . basename($UploadFileInfo['file_path'], '.csv') . ".log";
			$sql_log_db = "";
			if (file_exists($log_file_checked) && filesize($log_file_checked) > 24)
			{
				copy($log_file_checked, $logfile);
				if (file_exists($logfile))
				{
					$sql_log_db = ", db_error_file_path = '" . $logfile . "'";
				}
			}
			echo "upload success info:";
			//", duplicate_numbers = ".($row_in-1-$row-$row_error)
			echo $upd_sql_succ = "update import_export_logs set finished_time = now(), php_process_number = " . ($row-1) . ", error_row = ".$row_error.$sql_log_db." where id = " . $UploadFileInfo['id'];
			echo "\r\n";
			$upd = pg_query($this->dbConn, $upd_sql_succ);
			$return = 6;
			if ($massage!='')
			{
				$return .= $massage;
			}			
			
		}
		return $return;
	}
	
}

//$time_start = microtime();	//调试

$class_dbconfig = new DATABASE_CONFIG();
$conn_config = $class_dbconfig->default;
$dbconn = pg_connect("host={$conn_config['host']} port={$conn_config['port']} dbname={$conn_config['database']} user={$conn_config['login']} password={$conn_config['password']}");
//查找错误报告文件上传表0上传成功未处理
$sql = "select * from import_export_logs where status=0";
$file_result = pg_query($dbconn, $sql);
$amount = pg_num_rows($file_result);
if ($amount > 0)
{
	$ufa_class = new UploadFileAction();
	$ufa_class->dbConn = $dbconn;
	while ($row = pg_fetch_array($file_result)) 
	{
		//处理文件
		if (is_file($row['file_path']) && !empty($row['upload_table']))
		{
			$table_check = true;//pg_query($dbconn, "select count(*) from {$row['upload_table']}");
			if (false=== $table_check)
			{
				echo "table {$row['upload_table']} not creat.\r\n";
			}
			else 
			{
				$re = 0;			
				$upd_q = pg_query($dbconn, "update import_export_logs set status=9 where status=0 and id=".intval($row['id'])); 
				if (pg_affected_rows($upd_q)>0)
				{
					if (10 == $row['upload_type'])									//处理rate上传
					{
						$re = $ufa_class->rateUploadAct($row);
					}
					else 
					{
						$re = $ufa_class->GetInfoFromFile($row);
					}
					//$re = $ufa_class->CopyFromFile($row);
				}
				if (intval($re)>0)
				{
					$upd_q = pg_query($dbconn, "update import_export_logs set status=".intval($re)." where id=".intval($row['id']));
					echo "{$row['file_path']} parse success!\r\n";
				}
			}
		}
		else
		{
			echo "file {$row['file_path']} no exist or tmp table {$row['upload_table']} not creat!\r\n";	//log
		}	
	}
} 
//$time_end = microtime();
//echo "\r\n time used(micro second):",$time_end-$time_start;
?>
