#!/usr/bin/php -q
<?php
/*
 * @statistic client_cdr group by time date
 * @create by weifeng 2011-06-09
 */
ignore_user_abort();
set_time_limit(0);
//ini_set("display_errors","off");
define('RE_STATISTIC', false);				//要把数据重新统计吗
define('STATISTIC_TODAY', false);		//统计当日数据吗
define('STATISTIC_HOUR', false);			//当日数据按小时统计,这里默认STATISTIC_TODAY为true
require_once("config/database.php");
$time_start = microtime(true);	//调试

$class_dbconfig = new DATABASE_CONFIG();
$conn_config = $class_dbconfig->default;
$dbconn = pg_connect("host={$conn_config['host']} port={$conn_config['port']} dbname={$conn_config['database']} user={$conn_config['login']} password={$conn_config['password']}");

//---------all cdr tables
$cdr_tables = array();

$today_date = date("Ymd");
$today_cdr = '';
if (STATISTIC_TODAY == false)
{
	$today_cdr .= " and substring(table_name from 11 for 20)::integer < " . $today_date . "::integer";
}
if	(RE_STATISTIC == false)
{
	echo $sql = "SELECT table_name as name FROM INFORMATION_SCHEMA.tables WHERE table_name like 'client_cdr2%' and table_schema = 'public' and table_name not in (select 'client_'||substring(table_name from 11 for 20) from INFORMATION_SCHEMA.tables where table_name like 'statistic_cdr2%' and table_schema = 'public')" . $today_cdr;
}
else
{
	echo $sql = "SELECT table_name as name FROM INFORMATION_SCHEMA.tables WHERE table_name like 'client_cdr2%' and table_schema = 'public'";
}
echo "\r\n";
		
$result = pg_query($dbconn, $sql);
$amount = pg_num_rows($result);
if ($amount > 0)
{
	if (STATISTIC_TODAY || STATISTIC_HOUR)
	{
		while ($row = pg_fetch_array($result)) 
		{
		if ($row['name'] != 'client_cdr' . $today_date)
			{
				$cdr_tables[] = $row['name'];
			}
			
		}
	}
	else
	{
		while ($row = pg_fetch_array($result)) 
		{
			$cdr_tables[] = $row['name'];
		}
	}
	//var_dump($cdr_tables);exit;
	foreach ($cdr_tables as $k=>$v)
	{
		$date = str_replace('client_cdr', '', $v);
		$date_time = date("Y-m-d 00:00:00+00", strtotime($date));
		$date_time_end = date("Y-m-d 23:59:59+00", strtotime($date));
		if (RE_STATISTIC)
		{
			pg_query($dbconn, "drop table statistic_cdr$date");
		}
		$check_table_sql = "SELECT table_name as name FROM INFORMATION_SCHEMA.tables WHERE table_name = 'statistic_cdr{$date}' and table_schema = 'public'";
		$table_amount = pg_num_rows(pg_query($dbconn, $check_table_sql));		
		if ($table_amount == 0)					//建子表
		{
			echo "\n\n";
			echo $create_table_sql = <<<EOD
CREATE TABLE statistic_cdr$date
(
CONSTRAINT timecheck CHECK ("time" >= '$date_time'::timestamp with time zone AND "time" <= '$date_time_end'::timestamp with time zone)
)
INHERITS (statistic_cdr)
WITH (OIDS=FALSE);
CREATE INDEX idx_statistic_cdr_time_$date ON statistic_cdr$date USING btree (time);
CREATE INDEX idx_statistic_cdr_orig_country_$date ON statistic_cdr$date USING btree (orig_country);
CREATE INDEX idx_statistic_cdr_orig_code_name_$date ON statistic_cdr$date USING btree (orig_code_name);
CREATE INDEX idx_statistic_cdr_orig_code_$date ON statistic_cdr$date USING btree (orig_code);
CREATE INDEX idx_statistic_cdr_ingress_client_id_$date ON statistic_cdr$date USING btree (ingress_client_id);
CREATE INDEX idx_statistic_cdr_ingress_alias_$date ON statistic_cdr$date USING btree (trunk_id_origination);
CREATE INDEX idx_statistic_cdr_ingress_id_$date ON statistic_cdr$date USING btree (ingress_id);
CREATE INDEX idx_statistic_cdr_term_country_$date ON statistic_cdr$date USING btree (term_country);
CREATE INDEX idx_statistic_cdr_term_code_name_$date ON statistic_cdr$date USING btree (term_code_name);
CREATE INDEX idx_statistic_cdr_term_code_$date ON statistic_cdr$date USING btree (term_code);
CREATE INDEX idx_statistic_cdr_egress_client_id_$date ON statistic_cdr$date USING btree (egress_client_id);
CREATE INDEX idx_statistic_cdr_egress_alias_$date ON statistic_cdr$date USING btree (trunk_id_termination);
CREATE INDEX idx_statistic_cdr_egress_id_$date ON statistic_cdr$date USING btree (egress_id);
CREATE INDEX idx_statistic_cdr_ingress_host_$date ON statistic_cdr$date USING btree (origination_source_host_name);
CREATE INDEX idx_statistic_cdr_egress_host_$date ON statistic_cdr$date USING btree (termination_destination_host_name);
CREATE INDEX idx_statistic_cdr_ingress_client_rate_table_id_$date ON statistic_cdr$date USING btree (ingress_client_rate_table_id);
CREATE INDEX idx_statistic_cdr_egress_rate_table_id_$date ON statistic_cdr$date USING btree (egress_rate_table_id);
CREATE INDEX idx_statistic_cdr_switch_ip_$date ON statistic_cdr$date USING btree (termination_source_host_name);
EOD;
			pg_query($dbconn, $create_table_sql);
		}
		echo "\n\n";
		if (STATISTIC_HOUR && $v == 'client_cdr' . $today_date)
		{
			$hour_start = date("Y-m-d H:00:00 +00", strtotime("-1 hours"));
			$hour_end = date("Y-m-d H:59:59 +00", strtotime("-1 hours"));
			echo $statistic_sql = "insert into statistic_cdr{$date} (
 orig_country, orig_code_name, orig_code, ingress_client_id, trunk_id_origination, ingress_id, term_country, term_code_name, term_code, egress_client_id, trunk_id_termination, egress_id, time, termination_source_host_name, origination_source_host_name, termination_destination_host_name, ca, ok_calls, egress_ok_calls, call_duration, ingress_cost, egress_cost, orig_bill_minute, term_bill_minute, ingress_ca, egress_ca, not_zero_calls, busy_calls, egress_no_channel_calls, no_channel_calls, lrn_calls, ingress_client_rate_table_id, egress_rate_table_id, pdd, lnp_dipping_cost
)

select orig_country, orig_code_name, orig_code, ingress_client_id, trunk_id_origination as ingress_alias, ingress_id, term_country, 
term_code_name, term_code, egress_client_id, trunk_id_termination as egress_alias, egress_id, to_char(time, 'YYYY-MM-DD HH24:00:00 +00')::timestamp with time zone as statistic_time, termination_source_host_name as switch_ip, origination_source_host_name as ingress_host, 
termination_destination_host_name as egress_host, sum(coalesce(is_final_call, '1')::integer) as ca, 
sum( case when trunk_id_termination = '' then null else coalesce(is_final_call, '1')::integer end ) as ok_calls, 
sum( case trunk_id_termination when '' then null else 1 end ) as egress_ok_calls, 
(sum ( case call_duration when '' then 0 else call_duration::numeric end))/60 ::numeric as call_duration, 
sum(case ingress_client_cost when 'nan' then null else ingress_client_cost::numeric end)::numeric as ingress_cost, 
sum(case egress_cost when 'nan' then null else egress_cost::numeric end)::numeric as egress_cost, 
sum(ingress_bill_minutes::numeric) as orig_bill_minute, 
sum(egress_bill_minutes::numeric) as term_bill_minute, 
sum(coalesce(is_final_call, '1')::integer) as ingress_ca, 
count(case egress_id when '' then null else egress_id end) as egress_ca, 
count(NULLIF(call_duration , '0')) as not_zero_calls, 
count(case when split_part(release_cause_from_protocol_stack,':',1)='486' then release_cause_from_protocol_stack else null end) as busy_calls, 
count(case when split_part(release_cause_from_protocol_stack,':',1)='503' then release_cause_from_protocol_stack else null end) as egress_no_channel_calls, 
count(case when release_cause::integer >=6 and release_cause::integer <=9 then release_cause else null end) as no_channel_calls,
sum(case when coalesce(is_final_call, '1')='1' and lrn_number_vendor::integer in (2,3) then 1 else 0 end) as lrn_calls,
ingress_client_rate_table_id, 
egress_rate_table_id, sum( case when call_duration = '0' then 0 else pdd::integer end) as pdd,
sum(lnp_dipping_cost::numeric)                        
from {$v} where time between {$hour_start} and {$hour_end} group by orig_country, orig_code_name, orig_code, ingress_client_id, ingress_alias, ingress_id, term_country, term_code_name, term_code, egress_client_id, egress_alias, egress_id, statistic_time, switch_ip, ingress_host, egress_host, ingress_client_rate_table_id, egress_rate_table_id";
		}
		else
		{
			echo $statistic_sql = "insert into statistic_cdr{$date} (
 orig_country, orig_code_name, orig_code, ingress_client_id, trunk_id_origination, ingress_id, term_country, term_code_name, term_code, egress_client_id, trunk_id_termination, egress_id, time, termination_source_host_name, origination_source_host_name, termination_destination_host_name, ca, ok_calls, egress_ok_calls, call_duration, ingress_cost, egress_cost, orig_bill_minute, term_bill_minute, ingress_ca, egress_ca, not_zero_calls, busy_calls, egress_no_channel_calls, no_channel_calls, lrn_calls, ingress_client_rate_table_id, egress_rate_table_id, pdd
)

select orig_country, orig_code_name, orig_code, ingress_client_id, trunk_id_origination as ingress_alias, ingress_id, term_country, 
term_code_name, term_code, egress_client_id, trunk_id_termination as egress_alias, egress_id, to_char(time, 'YYYY-MM-DD HH24:00:00 +00')::timestamp with time zone as statistic_time, termination_source_host_name as switch_ip, origination_source_host_name as ingress_host, 
termination_destination_host_name as egress_host, sum(coalesce(is_final_call, '1')::integer) as ca, 
sum( case when trunk_id_termination = '' then null else coalesce(is_final_call, '1')::integer end ) as ok_calls, 
sum( case trunk_id_termination when '' then null else 1 end ) as egress_ok_calls, 
(sum ( case call_duration when '' then 0 else call_duration::numeric end))/60 ::numeric as call_duration, 
sum(case ingress_client_cost when 'nan' then null else ingress_client_cost::numeric end)::numeric as ingress_cost, 
sum(case egress_cost when 'nan' then null else egress_cost::numeric end)::numeric as egress_cost, 
sum(ingress_bill_minutes::numeric) as orig_bill_minute, 
sum(egress_bill_minutes::numeric) as term_bill_minute, 
sum(coalesce(is_final_call, '1')::integer) as ingress_ca, 
count(case egress_id when '' then null else egress_id end) as egress_ca, 
count(NULLIF(call_duration , '0')) as not_zero_calls, 
count(case when split_part(release_cause_from_protocol_stack,':',1)='486' then release_cause_from_protocol_stack else null end) as busy_calls, 
count(case when split_part(release_cause_from_protocol_stack,':',1)='503' then release_cause_from_protocol_stack else null end) as egress_no_channel_calls, 
count(case when release_cause::integer >=6 and release_cause::integer <=9 then release_cause else null end) as no_channel_calls,
sum(case when coalesce(is_final_call, '1')='1' and lrn_number_vendor::integer in (2,3) then 1 else 0 end) as lrn_calls, 
ingress_client_rate_table_id, 
egress_rate_table_id, sum( case when call_duration = '0' then 0 else pdd::integer end) as pdd
from {$v} group by orig_country, orig_code_name, orig_code, ingress_client_id, ingress_alias, ingress_id, term_country, term_code_name, term_code, egress_client_id, egress_alias, egress_id, statistic_time, switch_ip, ingress_host, egress_host, ingress_client_rate_table_id, egress_rate_table_id";
		}
		pg_query($dbconn, $statistic_sql);
		$time_end = microtime(true);
		echo "\n Table. {$v}, time used(micro second):",$time_end-$time_start, "\r\n";
	}

}
$time_end = microtime(true);
echo "\n All complete: time used(micro second):",$time_end-$time_start, "\r\n";

pg_close($dbconn);
?>