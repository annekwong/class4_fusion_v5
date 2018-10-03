<script type="text/javascript">
	var orig_res = eval(<?php echo $orig_res?>);
	var orig_cli = eval(<?php echo $orig_cli?>);
	var term_res = eval(<?php echo $term_res?>);
	var term_cli = eval(<?php echo $term_cli?>);
	var calls_res = eval(<?php echo $calls_res?>);
	var calls_cli = eval(<?php echo $calls_cli?>);

	var orig_bill_time = Number(orig_res.billed_time) + Number(orig_cli.billed_time);
	var orig_cost = Number(orig_res.cost)+Number(orig_cli.cost);
	var orig_rate = Number(orig_res.avg)+Number(orig_cli.avg);

	var term_bill_time = Number(term_res.billed_time) + Number(term_cli.billed_time);
	var term_cost = Number(term_res.cost)+Number(term_cli.cost);
	var term_rate = Number(term_res.avg)+Number(term_cli.avg);

	var total_ca = isNaN(Number(calls_res.total)+Number(calls_cli.total))?0:Number(calls_res.total)+Number(calls_cli.total);
	var success = Number(calls_res.success)+Number(calls_cli.success);
	var failed = Number(calls_res.failed)+Number(calls_cli.failed);
	var busy = Number(calls_res.busy)+Number(calls_cli.busy);
	var nochannel = Number(calls_res.nochannel)+Number(calls_cli.nochannel);
	var nonzero = Number(calls_res.nonzero)+Number(calls_cli.nonzero);
	var acd = total_ca > 0?(Number(calls_res.durations)+Number(calls_cli.durations))/total_ca/60:0;
	var tr = document.createElement("tr");
	var td = document.createElement("td");
	td.className = 'in-decimal';
	td.innerHTML = orig_bill_time;


	var td1 = td.cloneNode(true);
	td1.className = 'in-decimal pos';
	td1.innerHTML = orig_cost;

	var td2 = td.cloneNode(true);
	td2.className = 'in-decimal right zero';
	td2.innerHTML = orig_rate;

	var td3 = td.cloneNode(true);
	td3.className = 'in-decimal';
	td3.innerHTML = term_bill_time;

	var td4 = td.cloneNode(true);
	td4.className = 'in-decimal pos';
	td4.innerHTML = term_cost;

	var td5 = td.cloneNode(true);
	td5.className = 'in-decimal right zero';
	td5.innerHTML = term_rate;

	var td6 = td.cloneNode(true);
	td6.className = 'in-decimal pos';
	td6.innerHTML = (orig_bill_time+term_bill_time)/60;

	var td6_b = td.cloneNode(true);
	td6_b.className = 'in-decimal pos';
	td6_b.innerHTML = total_ca>0?success/total_ca:0;
	
	
	var td7_b = td.cloneNode(true);
	td7_b.innerHTML = "";

	var td7 = td.cloneNode(true);
	td7.className = 'in-decimal right zero';
	td7.innerHTML = acd;


	var td8_b = td.cloneNode(true);
	td8_b.innerHTML = "";

	
	var td8 = td.cloneNode(true);
	td8.className = 'in-decimal pos';
	td8.innerHTML = total_ca;

	var td9 = td.cloneNode(true);
	td9.className = 'in-decimal right zero';
	td9.innerHTML = success;

	var td10 = td.cloneNode(true);
	td10.className = 'in-decimal pos';
	td10.innerHTML = failed;

	var td11 = td.cloneNode(true);
	td11.className = 'in-decimal right zero';
	td11.innerHTML = busy;

	var td12 = td.cloneNode(true);
	td12.className = 'in-decimal pos';
	td12.innerHTML = nochannel;

	var td13 = td.cloneNode(true);
	td13.className = 'in-decimal pos';
	td13.innerHTML = nonzero;
	tr.appendChild(td);
	tr.appendChild(td1);
	tr.appendChild(td2);
	tr.appendChild(td3);
	tr.appendChild(td4);
	tr.appendChild(td5);
	tr.appendChild(td6);
	tr.appendChild(td6_b);
	tr.appendChild(td7_b);
	tr.appendChild(td7);
	tr.appendChild(td8_b);
	tr.appendChild(td8);
	tr.appendChild(td9);
	tr.appendChild(td10);
	tr.appendChild(td11);
	tr.appendChild(td12);
	tr.appendChild(td13);


	//选择代理商或者客户或者卡  由子页面调用
	function choose(tr){
	document.getElementById('query-id_clients_name').value = tr.cells[1].innerHTML.trim();
	document.body.removeChild(document.getElementById("infodivv"));
	closeCover('cover_tmp');
		}

	
</script>
<div id="title">
  <h1><?php echo __('origtermreport')?> </h1>
</div>
<div id="container">
  <div id="cover"></div>
  <div id="cover_tmp"></div>
  <?php //****************************************表格报表?>
  <table class="list nowrap with-fields">
    <thead>
      <tr>
        <td class="cset-1" colspan="3"><?php echo __('origination')?></td>
        <td class="cset-2" colspan="3"><?php echo __('termination')?></td>
                <td rel="8" rowspan="2"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"><br><?php echo __('time')?>, <?php echo __('minutes')?></td>
        <td class="cset-4" colspan="2"><span rel="helptip" class="helptip" id="ht-100002"><?php echo __('asr')?></span><span class="tooltip" id="ht-100002-tooltip"><?php __('Average successful rate (percent of successful calls)')?></span></td>
        <td class="cset-5" colspan="2"><span rel="helptip" class="helptip" id="ht-100003"><?php echo __('acd')?></span><span class="tooltip" id="ht-100003-tooltip"><?php __('Average call duration')?></span>, <?php __('min')?></td>
        <td class="cset-6 last" colspan="5"><span><?php echo __('calls')?></span> <span rel="helptip" class="helptip" id="ht-100004">*</span><span class="tooltip" id="ht-100004-tooltip"><?php __('These values (except for not zero calls) are approximate and may slightly differ from the values in the summary report. For exact values refer to summary report')?>.</span></td>
      </tr>
      <tr>
        <td class="cset-1" rel="0"><a href="?orderby=orig_billed_time&amp;query%5Bprocess%5D=1&amp;query%5Border_by%5D=&amp;query%5Borig_id_clients%5D=&amp;query%5Borig_account%5D=&amp;query%5Bterm_id_clients%5D=&amp;query%5Bterm_account%5D=&amp;query%5BsmartPeriod%5D=custom&amp;query%5Bstart_date%5D=2000-08-11&amp;query%5Bstart_time%5D=00%3A00%3A00&amp;query%5Bstop_date%5D=2010-08-11&amp;query%5Bstop_time%5D=23%3A59%3A59&amp;query%5Btz%5D=%2B0300&amp;query%5Bgroup_by_date%5D=&amp;query%5Borig_id_clients_name%5D=&amp;query%5Borig_code_name%5D=&amp;query%5Borig_code%5D=&amp;query%5Borig_id_companies%5D=&amp;query%5Borig_id_groups%5D=&amp;query%5Bterm_id_clients_name%5D=&amp;query%5Bterm_code_name%5D=&amp;query%5Bterm_code%5D=&amp;query%5Bterm_id_companies%5D=&amp;query%5Bterm_id_groups%5D=&amp;query%5Boutput%5D=web&amp;query%5Bgroup_by%5D%5B0%5D=&amp;query%5Bgroup_by%5D%5B1%5D=&amp;query%5Bgroup_by%5D%5B2%5D=&amp;query%5Bgroup_by%5D%5B3%5D=&amp;query%5Bgroup_by%5D%5B4%5D=&amp;query%5Bgroup_by%5D%5B5%5D=&amp;query%5Bid_code_decks%5D="><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('billedtime')?>&nbsp;<a href="?orderby=orig_billed_time_desc&amp;query%5Bprocess%5D=1&amp;query%5Border_by%5D=&amp;query%5Borig_id_clients%5D=&amp;query%5Borig_account%5D=&amp;query%5Bterm_id_clients%5D=&amp;query%5Bterm_account%5D=&amp;query%5BsmartPeriod%5D=custom&amp;query%5Bstart_date%5D=2000-08-11&amp;query%5Bstart_time%5D=00%3A00%3A00&amp;query%5Bstop_date%5D=2010-08-11&amp;query%5Bstop_time%5D=23%3A59%3A59&amp;query%5Btz%5D=%2B0300&amp;query%5Bgroup_by_date%5D=&amp;query%5Borig_id_clients_name%5D=&amp;query%5Borig_code_name%5D=&amp;query%5Borig_code%5D=&amp;query%5Borig_id_companies%5D=&amp;query%5Borig_id_groups%5D=&amp;query%5Bterm_id_clients_name%5D=&amp;query%5Bterm_code_name%5D=&amp;query%5Bterm_code%5D=&amp;query%5Bterm_id_companies%5D=&amp;query%5Bterm_id_groups%5D=&amp;query%5Boutput%5D=web&amp;query%5Bgroup_by%5D%5B0%5D=&amp;query%5Bgroup_by%5D%5B1%5D=&amp;query%5Bgroup_by%5D%5B2%5D=&amp;query%5Bgroup_by%5D%5B3%5D=&amp;query%5Bgroup_by%5D%5B4%5D=&amp;query%5Bgroup_by%5D%5B5%5D=&amp;query%5Bid_code_decks%5D="><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
        <td class="cset-1" rel="1"><a href="?orderby=orig_cost&amp;query%5Bprocess%5D=1&amp;query%5Border_by%5D=&amp;query%5Borig_id_clients%5D=&amp;query%5Borig_account%5D=&amp;query%5Bterm_id_clients%5D=&amp;query%5Bterm_account%5D=&amp;query%5BsmartPeriod%5D=custom&amp;query%5Bstart_date%5D=2000-08-11&amp;query%5Bstart_time%5D=00%3A00%3A00&amp;query%5Bstop_date%5D=2010-08-11&amp;query%5Bstop_time%5D=23%3A59%3A59&amp;query%5Btz%5D=%2B0300&amp;query%5Bgroup_by_date%5D=&amp;query%5Borig_id_clients_name%5D=&amp;query%5Borig_code_name%5D=&amp;query%5Borig_code%5D=&amp;query%5Borig_id_companies%5D=&amp;query%5Borig_id_groups%5D=&amp;query%5Bterm_id_clients_name%5D=&amp;query%5Bterm_code_name%5D=&amp;query%5Bterm_code%5D=&amp;query%5Bterm_id_companies%5D=&amp;query%5Bterm_id_groups%5D=&amp;query%5Boutput%5D=web&amp;query%5Bgroup_by%5D%5B0%5D=&amp;query%5Bgroup_by%5D%5B1%5D=&amp;query%5Bgroup_by%5D%5B2%5D=&amp;query%5Bgroup_by%5D%5B3%5D=&amp;query%5Bgroup_by%5D%5B4%5D=&amp;query%5Bgroup_by%5D%5B5%5D=&amp;query%5Bid_code_decks%5D="><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('cost')?>&nbsp;<a href="?orderby=orig_cost_desc&amp;query%5Bprocess%5D=1&amp;query%5Border_by%5D=&amp;query%5Borig_id_clients%5D=&amp;query%5Borig_account%5D=&amp;query%5Bterm_id_clients%5D=&amp;query%5Bterm_account%5D=&amp;query%5BsmartPeriod%5D=custom&amp;query%5Bstart_date%5D=2000-08-11&amp;query%5Bstart_time%5D=00%3A00%3A00&amp;query%5Bstop_date%5D=2010-08-11&amp;query%5Bstop_time%5D=23%3A59%3A59&amp;query%5Btz%5D=%2B0300&amp;query%5Bgroup_by_date%5D=&amp;query%5Borig_id_clients_name%5D=&amp;query%5Borig_code_name%5D=&amp;query%5Borig_code%5D=&amp;query%5Borig_id_companies%5D=&amp;query%5Borig_id_groups%5D=&amp;query%5Bterm_id_clients_name%5D=&amp;query%5Bterm_code_name%5D=&amp;query%5Bterm_code%5D=&amp;query%5Bterm_id_companies%5D=&amp;query%5Bterm_id_groups%5D=&amp;query%5Boutput%5D=web&amp;query%5Bgroup_by%5D%5B0%5D=&amp;query%5Bgroup_by%5D%5B1%5D=&amp;query%5Bgroup_by%5D%5B2%5D=&amp;query%5Bgroup_by%5D%5B3%5D=&amp;query%5Bgroup_by%5D%5B4%5D=&amp;query%5Bgroup_by%5D%5B5%5D=&amp;query%5Bid_code_decks%5D="><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
        <td class="cset-1" rel="2"><span rel="helptip" class="helptip" id="ht-100005"><?php echo __('avgrate')?></span><span class="tooltip" id="ht-100005-tooltip">Average Rate per minute, calculated as total cost / total time</span></td>                <td class="cset-2" rel="3"><a href="?orderby=term_billed_time&amp;query%5Bprocess%5D=1&amp;query%5Border_by%5D=&amp;query%5Borig_id_clients%5D=&amp;query%5Borig_account%5D=&amp;query%5Bterm_id_clients%5D=&amp;query%5Bterm_account%5D=&amp;query%5BsmartPeriod%5D=custom&amp;query%5Bstart_date%5D=2000-08-11&amp;query%5Bstart_time%5D=00%3A00%3A00&amp;query%5Bstop_date%5D=2010-08-11&amp;query%5Bstop_time%5D=23%3A59%3A59&amp;query%5Btz%5D=%2B0300&amp;query%5Bgroup_by_date%5D=&amp;query%5Borig_id_clients_name%5D=&amp;query%5Borig_code_name%5D=&amp;query%5Borig_code%5D=&amp;query%5Borig_id_companies%5D=&amp;query%5Borig_id_groups%5D=&amp;query%5Bterm_id_clients_name%5D=&amp;query%5Bterm_code_name%5D=&amp;query%5Bterm_code%5D=&amp;query%5Bterm_id_companies%5D=&amp;query%5Bterm_id_groups%5D=&amp;query%5Boutput%5D=web&amp;query%5Bgroup_by%5D%5B0%5D=&amp;query%5Bgroup_by%5D%5B1%5D=&amp;query%5Bgroup_by%5D%5B2%5D=&amp;query%5Bgroup_by%5D%5B3%5D=&amp;query%5Bgroup_by%5D%5B4%5D=&amp;query%5Bgroup_by%5D%5B5%5D=&amp;query%5Bid_code_decks%5D="><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;Billed Time&nbsp;<a href="?orderby=term_billed_time_desc&amp;query%5Bprocess%5D=1&amp;query%5Border_by%5D=&amp;query%5Borig_id_clients%5D=&amp;query%5Borig_account%5D=&amp;query%5Bterm_id_clients%5D=&amp;query%5Bterm_account%5D=&amp;query%5BsmartPeriod%5D=custom&amp;query%5Bstart_date%5D=2000-08-11&amp;query%5Bstart_time%5D=00%3A00%3A00&amp;query%5Bstop_date%5D=2010-08-11&amp;query%5Bstop_time%5D=23%3A59%3A59&amp;query%5Btz%5D=%2B0300&amp;query%5Bgroup_by_date%5D=&amp;query%5Borig_id_clients_name%5D=&amp;query%5Borig_code_name%5D=&amp;query%5Borig_code%5D=&amp;query%5Borig_id_companies%5D=&amp;query%5Borig_id_groups%5D=&amp;query%5Bterm_id_clients_name%5D=&amp;query%5Bterm_code_name%5D=&amp;query%5Bterm_code%5D=&amp;query%5Bterm_id_companies%5D=&amp;query%5Bterm_id_groups%5D=&amp;query%5Boutput%5D=web&amp;query%5Bgroup_by%5D%5B0%5D=&amp;query%5Bgroup_by%5D%5B1%5D=&amp;query%5Bgroup_by%5D%5B2%5D=&amp;query%5Bgroup_by%5D%5B3%5D=&amp;query%5Bgroup_by%5D%5B4%5D=&amp;query%5Bgroup_by%5D%5B5%5D=&amp;query%5Bid_code_decks%5D="><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
        <td class="cset-2" rel="4"><a href="?orderby=term_cost&amp;query%5Bprocess%5D=1&amp;query%5Border_by%5D=&amp;query%5Borig_id_clients%5D=&amp;query%5Borig_account%5D=&amp;query%5Bterm_id_clients%5D=&amp;query%5Bterm_account%5D=&amp;query%5BsmartPeriod%5D=custom&amp;query%5Bstart_date%5D=2000-08-11&amp;query%5Bstart_time%5D=00%3A00%3A00&amp;query%5Bstop_date%5D=2010-08-11&amp;query%5Bstop_time%5D=23%3A59%3A59&amp;query%5Btz%5D=%2B0300&amp;query%5Bgroup_by_date%5D=&amp;query%5Borig_id_clients_name%5D=&amp;query%5Borig_code_name%5D=&amp;query%5Borig_code%5D=&amp;query%5Borig_id_companies%5D=&amp;query%5Borig_id_groups%5D=&amp;query%5Bterm_id_clients_name%5D=&amp;query%5Bterm_code_name%5D=&amp;query%5Bterm_code%5D=&amp;query%5Bterm_id_companies%5D=&amp;query%5Bterm_id_groups%5D=&amp;query%5Boutput%5D=web&amp;query%5Bgroup_by%5D%5B0%5D=&amp;query%5Bgroup_by%5D%5B1%5D=&amp;query%5Bgroup_by%5D%5B2%5D=&amp;query%5Bgroup_by%5D%5B3%5D=&amp;query%5Bgroup_by%5D%5B4%5D=&amp;query%5Bgroup_by%5D%5B5%5D=&amp;query%5Bid_code_decks%5D="><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('cost')?>&nbsp;<a href="?orderby=term_cost_desc&amp;query%5Bprocess%5D=1&amp;query%5Border_by%5D=&amp;query%5Borig_id_clients%5D=&amp;query%5Borig_account%5D=&amp;query%5Bterm_id_clients%5D=&amp;query%5Bterm_account%5D=&amp;query%5BsmartPeriod%5D=custom&amp;query%5Bstart_date%5D=2000-08-11&amp;query%5Bstart_time%5D=00%3A00%3A00&amp;query%5Bstop_date%5D=2010-08-11&amp;query%5Bstop_time%5D=23%3A59%3A59&amp;query%5Btz%5D=%2B0300&amp;query%5Bgroup_by_date%5D=&amp;query%5Borig_id_clients_name%5D=&amp;query%5Borig_code_name%5D=&amp;query%5Borig_code%5D=&amp;query%5Borig_id_companies%5D=&amp;query%5Borig_id_groups%5D=&amp;query%5Bterm_id_clients_name%5D=&amp;query%5Bterm_code_name%5D=&amp;query%5Bterm_code%5D=&amp;query%5Bterm_id_companies%5D=&amp;query%5Bterm_id_groups%5D=&amp;query%5Boutput%5D=web&amp;query%5Bgroup_by%5D%5B0%5D=&amp;query%5Bgroup_by%5D%5B1%5D=&amp;query%5Bgroup_by%5D%5B2%5D=&amp;query%5Bgroup_by%5D%5B3%5D=&amp;query%5Bgroup_by%5D%5B4%5D=&amp;query%5Bgroup_by%5D%5B5%5D=&amp;query%5Bid_code_decks%5D="><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
        <td class="cset-2" rel="5"><span rel="helptip" class="helptip" id="ht-100006"><?php echo __('avgrate')?></span><span class="tooltip" id="ht-100006-tooltip">Average Rate per minute, calculated as total cost / total time</span></td>                <td class="cset-3" rel="6">RMB</td>
        <td class="cset-3" rel="7">%</td>
        <td width="5%" class="cset-5" rel="11"><a href="?orderby=acd_std&amp;query%5Bprocess%5D=1&amp;query%5Border_by%5D=&amp;query%5Borig_id_clients%5D=&amp;query%5Borig_account%5D=&amp;query%5Bterm_id_clients%5D=&amp;query%5Bterm_account%5D=&amp;query%5BsmartPeriod%5D=custom&amp;query%5Bstart_date%5D=2000-08-11&amp;query%5Bstart_time%5D=00%3A00%3A00&amp;query%5Bstop_date%5D=2010-08-11&amp;query%5Bstop_time%5D=23%3A59%3A59&amp;query%5Btz%5D=%2B0300&amp;query%5Bgroup_by_date%5D=&amp;query%5Borig_id_clients_name%5D=&amp;query%5Borig_code_name%5D=&amp;query%5Borig_code%5D=&amp;query%5Borig_id_companies%5D=&amp;query%5Borig_id_groups%5D=&amp;query%5Bterm_id_clients_name%5D=&amp;query%5Bterm_code_name%5D=&amp;query%5Bterm_code%5D=&amp;query%5Bterm_id_companies%5D=&amp;query%5Bterm_id_groups%5D=&amp;query%5Boutput%5D=web&amp;query%5Bgroup_by%5D%5B0%5D=&amp;query%5Bgroup_by%5D%5B1%5D=&amp;query%5Bgroup_by%5D%5B2%5D=&amp;query%5Bgroup_by%5D%5B3%5D=&amp;query%5Bgroup_by%5D%5B4%5D=&amp;query%5Bgroup_by%5D%5B5%5D=&amp;query%5Bid_code_decks%5D="><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<span rel="helptip" class="helptip" id="ht-100009">Std</span><span class="tooltip" id="ht-100009-tooltip">Value of respective parameter calculated on base of disconnect cause (16 and 31 are success)</span>&nbsp;<a href="?orderby=acd_std_desc&amp;query%5Bprocess%5D=1&amp;query%5Border_by%5D=&amp;query%5Borig_id_clients%5D=&amp;query%5Borig_account%5D=&amp;query%5Bterm_id_clients%5D=&amp;query%5Bterm_account%5D=&amp;query%5BsmartPeriod%5D=custom&amp;query%5Bstart_date%5D=2000-08-11&amp;query%5Bstart_time%5D=00%3A00%3A00&amp;query%5Bstop_date%5D=2010-08-11&amp;query%5Bstop_time%5D=23%3A59%3A59&amp;query%5Btz%5D=%2B0300&amp;query%5Bgroup_by_date%5D=&amp;query%5Borig_id_clients_name%5D=&amp;query%5Borig_code_name%5D=&amp;query%5Borig_code%5D=&amp;query%5Borig_id_companies%5D=&amp;query%5Borig_id_groups%5D=&amp;query%5Bterm_id_clients_name%5D=&amp;query%5Bterm_code_name%5D=&amp;query%5Bterm_code%5D=&amp;query%5Bterm_id_companies%5D=&amp;query%5Bterm_id_groups%5D=&amp;query%5Boutput%5D=web&amp;query%5Bgroup_by%5D%5B0%5D=&amp;query%5Bgroup_by%5D%5B1%5D=&amp;query%5Bgroup_by%5D%5B2%5D=&amp;query%5Bgroup_by%5D%5B3%5D=&amp;query%5Bgroup_by%5D%5B4%5D=&amp;query%5Bgroup_by%5D%5B5%5D=&amp;query%5Bid_code_decks%5D="><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
        <td class="cset-3" rel="7">%</td>
        <td width="6%" class="cset-6" rel="13"><a href="?orderby=calls_total&amp;query%5Bprocess%5D=1&amp;query%5Border_by%5D=&amp;query%5Borig_id_clients%5D=&amp;query%5Borig_account%5D=&amp;query%5Bterm_id_clients%5D=&amp;query%5Bterm_account%5D=&amp;query%5BsmartPeriod%5D=custom&amp;query%5Bstart_date%5D=2000-08-11&amp;query%5Bstart_time%5D=00%3A00%3A00&amp;query%5Bstop_date%5D=2010-08-11&amp;query%5Bstop_time%5D=23%3A59%3A59&amp;query%5Btz%5D=%2B0300&amp;query%5Bgroup_by_date%5D=&amp;query%5Borig_id_clients_name%5D=&amp;query%5Borig_code_name%5D=&amp;query%5Borig_code%5D=&amp;query%5Borig_id_companies%5D=&amp;query%5Borig_id_groups%5D=&amp;query%5Bterm_id_clients_name%5D=&amp;query%5Bterm_code_name%5D=&amp;query%5Bterm_code%5D=&amp;query%5Bterm_id_companies%5D=&amp;query%5Bterm_id_groups%5D=&amp;query%5Boutput%5D=web&amp;query%5Bgroup_by%5D%5B0%5D=&amp;query%5Bgroup_by%5D%5B1%5D=&amp;query%5Bgroup_by%5D%5B2%5D=&amp;query%5Bgroup_by%5D%5B3%5D=&amp;query%5Bgroup_by%5D%5B4%5D=&amp;query%5Bgroup_by%5D%5B5%5D=&amp;query%5Bid_code_decks%5D="><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('total')?>&nbsp;<a href="?orderby=calls_total_desc&amp;query%5Bprocess%5D=1&amp;query%5Border_by%5D=&amp;query%5Borig_id_clients%5D=&amp;query%5Borig_account%5D=&amp;query%5Bterm_id_clients%5D=&amp;query%5Bterm_account%5D=&amp;query%5BsmartPeriod%5D=custom&amp;query%5Bstart_date%5D=2000-08-11&amp;query%5Bstart_time%5D=00%3A00%3A00&amp;query%5Bstop_date%5D=2010-08-11&amp;query%5Bstop_time%5D=23%3A59%3A59&amp;query%5Btz%5D=%2B0300&amp;query%5Bgroup_by_date%5D=&amp;query%5Borig_id_clients_name%5D=&amp;query%5Borig_code_name%5D=&amp;query%5Borig_code%5D=&amp;query%5Borig_id_companies%5D=&amp;query%5Borig_id_groups%5D=&amp;query%5Bterm_id_clients_name%5D=&amp;query%5Bterm_code_name%5D=&amp;query%5Bterm_code%5D=&amp;query%5Bterm_id_companies%5D=&amp;query%5Bterm_id_groups%5D=&amp;query%5Boutput%5D=web&amp;query%5Bgroup_by%5D%5B0%5D=&amp;query%5Bgroup_by%5D%5B1%5D=&amp;query%5Bgroup_by%5D%5B2%5D=&amp;query%5Bgroup_by%5D%5B3%5D=&amp;query%5Bgroup_by%5D%5B4%5D=&amp;query%5Bgroup_by%5D%5B5%5D=&amp;query%5Bid_code_decks%5D="><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
        <td width="6%" class="cset-6" rel="14"><a href="?orderby=calls_notzero&amp;query%5Bprocess%5D=1&amp;query%5Border_by%5D=&amp;query%5Borig_id_clients%5D=&amp;query%5Borig_account%5D=&amp;query%5Bterm_id_clients%5D=&amp;query%5Bterm_account%5D=&amp;query%5BsmartPeriod%5D=custom&amp;query%5Bstart_date%5D=2000-08-11&amp;query%5Bstart_time%5D=00%3A00%3A00&amp;query%5Bstop_date%5D=2010-08-11&amp;query%5Bstop_time%5D=23%3A59%3A59&amp;query%5Btz%5D=%2B0300&amp;query%5Bgroup_by_date%5D=&amp;query%5Borig_id_clients_name%5D=&amp;query%5Borig_code_name%5D=&amp;query%5Borig_code%5D=&amp;query%5Borig_id_companies%5D=&amp;query%5Borig_id_groups%5D=&amp;query%5Bterm_id_clients_name%5D=&amp;query%5Bterm_code_name%5D=&amp;query%5Bterm_code%5D=&amp;query%5Bterm_id_companies%5D=&amp;query%5Bterm_id_groups%5D=&amp;query%5Boutput%5D=web&amp;query%5Bgroup_by%5D%5B0%5D=&amp;query%5Bgroup_by%5D%5B1%5D=&amp;query%5Bgroup_by%5D%5B2%5D=&amp;query%5Bgroup_by%5D%5B3%5D=&amp;query%5Bgroup_by%5D%5B4%5D=&amp;query%5Bgroup_by%5D%5B5%5D=&amp;query%5Bid_code_decks%5D="><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<span rel="helptip" class="helptip" id="ht-100011"><?php echo __('nonzero')?></span><span class="tooltip" id="ht-100011-tooltip">Calls with duration greater than 0</span>&nbsp;<a href="?orderby=calls_notzero_desc&amp;query%5Bprocess%5D=1&amp;query%5Border_by%5D=&amp;query%5Borig_id_clients%5D=&amp;query%5Borig_account%5D=&amp;query%5Bterm_id_clients%5D=&amp;query%5Bterm_account%5D=&amp;query%5BsmartPeriod%5D=custom&amp;query%5Bstart_date%5D=2000-08-11&amp;query%5Bstart_time%5D=00%3A00%3A00&amp;query%5Bstop_date%5D=2010-08-11&amp;query%5Bstop_time%5D=23%3A59%3A59&amp;query%5Btz%5D=%2B0300&amp;query%5Bgroup_by_date%5D=&amp;query%5Borig_id_clients_name%5D=&amp;query%5Borig_code_name%5D=&amp;query%5Borig_code%5D=&amp;query%5Borig_id_companies%5D=&amp;query%5Borig_id_groups%5D=&amp;query%5Bterm_id_clients_name%5D=&amp;query%5Bterm_code_name%5D=&amp;query%5Bterm_code%5D=&amp;query%5Bterm_id_companies%5D=&amp;query%5Bterm_id_groups%5D=&amp;query%5Boutput%5D=web&amp;query%5Bgroup_by%5D%5B0%5D=&amp;query%5Bgroup_by%5D%5B1%5D=&amp;query%5Bgroup_by%5D%5B2%5D=&amp;query%5Bgroup_by%5D%5B3%5D=&amp;query%5Bgroup_by%5D%5B4%5D=&amp;query%5Bgroup_by%5D%5B5%5D=&amp;query%5Bid_code_decks%5D="><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
        <td width="6%" class="cset-6" rel="15"><a href="?orderby=calls_success&amp;query%5Bprocess%5D=1&amp;query%5Border_by%5D=&amp;query%5Borig_id_clients%5D=&amp;query%5Borig_account%5D=&amp;query%5Bterm_id_clients%5D=&amp;query%5Bterm_account%5D=&amp;query%5BsmartPeriod%5D=custom&amp;query%5Bstart_date%5D=2000-08-11&amp;query%5Bstart_time%5D=00%3A00%3A00&amp;query%5Bstop_date%5D=2010-08-11&amp;query%5Bstop_time%5D=23%3A59%3A59&amp;query%5Btz%5D=%2B0300&amp;query%5Bgroup_by_date%5D=&amp;query%5Borig_id_clients_name%5D=&amp;query%5Borig_code_name%5D=&amp;query%5Borig_code%5D=&amp;query%5Borig_id_companies%5D=&amp;query%5Borig_id_groups%5D=&amp;query%5Bterm_id_clients_name%5D=&amp;query%5Bterm_code_name%5D=&amp;query%5Bterm_code%5D=&amp;query%5Bterm_id_companies%5D=&amp;query%5Bterm_id_groups%5D=&amp;query%5Boutput%5D=web&amp;query%5Bgroup_by%5D%5B0%5D=&amp;query%5Bgroup_by%5D%5B1%5D=&amp;query%5Bgroup_by%5D%5B2%5D=&amp;query%5Bgroup_by%5D%5B3%5D=&amp;query%5Bgroup_by%5D%5B4%5D=&amp;query%5Bgroup_by%5D%5B5%5D=&amp;query%5Bid_code_decks%5D="><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<span rel="helptip" class="helptip" id="ht-100012"><?php echo __('success')?></span><span class="tooltip" id="ht-100012-tooltip">Calls with Q.931 disconnect cause = 16 or 31</span>&nbsp;<a href="?orderby=calls_success_desc&amp;query%5Bprocess%5D=1&amp;query%5Border_by%5D=&amp;query%5Borig_id_clients%5D=&amp;query%5Borig_account%5D=&amp;query%5Bterm_id_clients%5D=&amp;query%5Bterm_account%5D=&amp;query%5BsmartPeriod%5D=custom&amp;query%5Bstart_date%5D=2000-08-11&amp;query%5Bstart_time%5D=00%3A00%3A00&amp;query%5Bstop_date%5D=2010-08-11&amp;query%5Bstop_time%5D=23%3A59%3A59&amp;query%5Btz%5D=%2B0300&amp;query%5Bgroup_by_date%5D=&amp;query%5Borig_id_clients_name%5D=&amp;query%5Borig_code_name%5D=&amp;query%5Borig_code%5D=&amp;query%5Borig_id_companies%5D=&amp;query%5Borig_id_groups%5D=&amp;query%5Bterm_id_clients_name%5D=&amp;query%5Bterm_code_name%5D=&amp;query%5Bterm_code%5D=&amp;query%5Bterm_id_companies%5D=&amp;query%5Bterm_id_groups%5D=&amp;query%5Boutput%5D=web&amp;query%5Bgroup_by%5D%5B0%5D=&amp;query%5Bgroup_by%5D%5B1%5D=&amp;query%5Bgroup_by%5D%5B2%5D=&amp;query%5Bgroup_by%5D%5B3%5D=&amp;query%5Bgroup_by%5D%5B4%5D=&amp;query%5Bgroup_by%5D%5B5%5D=&amp;query%5Bid_code_decks%5D="><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
        <td width="6%" class="cset-6" rel="15"><a href="?orderby=calls_success&amp;query%5Bprocess%5D=1&amp;query%5Border_by%5D=&amp;query%5Borig_id_clients%5D=&amp;query%5Borig_account%5D=&amp;query%5Bterm_id_clients%5D=&amp;query%5Bterm_account%5D=&amp;query%5BsmartPeriod%5D=custom&amp;query%5Bstart_date%5D=2000-08-11&amp;query%5Bstart_time%5D=00%3A00%3A00&amp;query%5Bstop_date%5D=2010-08-11&amp;query%5Bstop_time%5D=23%3A59%3A59&amp;query%5Btz%5D=%2B0300&amp;query%5Bgroup_by_date%5D=&amp;query%5Borig_id_clients_name%5D=&amp;query%5Borig_code_name%5D=&amp;query%5Borig_code%5D=&amp;query%5Borig_id_companies%5D=&amp;query%5Borig_id_groups%5D=&amp;query%5Bterm_id_clients_name%5D=&amp;query%5Bterm_code_name%5D=&amp;query%5Bterm_code%5D=&amp;query%5Bterm_id_companies%5D=&amp;query%5Bterm_id_groups%5D=&amp;query%5Boutput%5D=web&amp;query%5Bgroup_by%5D%5B0%5D=&amp;query%5Bgroup_by%5D%5B1%5D=&amp;query%5Bgroup_by%5D%5B2%5D=&amp;query%5Bgroup_by%5D%5B3%5D=&amp;query%5Bgroup_by%5D%5B4%5D=&amp;query%5Bgroup_by%5D%5B5%5D=&amp;query%5Bid_code_decks%5D="><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<span rel="helptip" class="helptip" id="ht-100012"><?php echo __('failed')?></span><span class="tooltip" id="ht-100012-tooltip">Calls with Q.931 disconnect cause = 16 or 31</span>&nbsp;<a href="?orderby=calls_success_desc&amp;query%5Bprocess%5D=1&amp;query%5Border_by%5D=&amp;query%5Borig_id_clients%5D=&amp;query%5Borig_account%5D=&amp;query%5Bterm_id_clients%5D=&amp;query%5Bterm_account%5D=&amp;query%5BsmartPeriod%5D=custom&amp;query%5Bstart_date%5D=2000-08-11&amp;query%5Bstart_time%5D=00%3A00%3A00&amp;query%5Bstop_date%5D=2010-08-11&amp;query%5Bstop_time%5D=23%3A59%3A59&amp;query%5Btz%5D=%2B0300&amp;query%5Bgroup_by_date%5D=&amp;query%5Borig_id_clients_name%5D=&amp;query%5Borig_code_name%5D=&amp;query%5Borig_code%5D=&amp;query%5Borig_id_companies%5D=&amp;query%5Borig_id_groups%5D=&amp;query%5Bterm_id_clients_name%5D=&amp;query%5Bterm_code_name%5D=&amp;query%5Bterm_code%5D=&amp;query%5Bterm_id_companies%5D=&amp;query%5Bterm_id_groups%5D=&amp;query%5Boutput%5D=web&amp;query%5Bgroup_by%5D%5B0%5D=&amp;query%5Bgroup_by%5D%5B1%5D=&amp;query%5Bgroup_by%5D%5B2%5D=&amp;query%5Bgroup_by%5D%5B3%5D=&amp;query%5Bgroup_by%5D%5B4%5D=&amp;query%5Bgroup_by%5D%5B5%5D=&amp;query%5Bid_code_decks%5D="><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
        <td width="6%" class="cset-6" rel="16"><a href="?orderby=calls_busy&amp;query%5Bprocess%5D=1&amp;query%5Border_by%5D=&amp;query%5Borig_id_clients%5D=&amp;query%5Borig_account%5D=&amp;query%5Bterm_id_clients%5D=&amp;query%5Bterm_account%5D=&amp;query%5BsmartPeriod%5D=custom&amp;query%5Bstart_date%5D=2000-08-11&amp;query%5Bstart_time%5D=00%3A00%3A00&amp;query%5Bstop_date%5D=2010-08-11&amp;query%5Bstop_time%5D=23%3A59%3A59&amp;query%5Btz%5D=%2B0300&amp;query%5Bgroup_by_date%5D=&amp;query%5Borig_id_clients_name%5D=&amp;query%5Borig_code_name%5D=&amp;query%5Borig_code%5D=&amp;query%5Borig_id_companies%5D=&amp;query%5Borig_id_groups%5D=&amp;query%5Bterm_id_clients_name%5D=&amp;query%5Bterm_code_name%5D=&amp;query%5Bterm_code%5D=&amp;query%5Bterm_id_companies%5D=&amp;query%5Bterm_id_groups%5D=&amp;query%5Boutput%5D=web&amp;query%5Bgroup_by%5D%5B0%5D=&amp;query%5Bgroup_by%5D%5B1%5D=&amp;query%5Bgroup_by%5D%5B2%5D=&amp;query%5Bgroup_by%5D%5B3%5D=&amp;query%5Bgroup_by%5D%5B4%5D=&amp;query%5Bgroup_by%5D%5B5%5D=&amp;query%5Bid_code_decks%5D="><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<span rel="helptip" class="helptip" id="ht-100013"><?php echo __('busy')?></span><span class="tooltip" id="ht-100013-tooltip">Calls with Q.931 disconnect cause = 17</span>&nbsp;<a href="?orderby=calls_busy_desc&amp;query%5Bprocess%5D=1&amp;query%5Border_by%5D=&amp;query%5Borig_id_clients%5D=&amp;query%5Borig_account%5D=&amp;query%5Bterm_id_clients%5D=&amp;query%5Bterm_account%5D=&amp;query%5BsmartPeriod%5D=custom&amp;query%5Bstart_date%5D=2000-08-11&amp;query%5Bstart_time%5D=00%3A00%3A00&amp;query%5Bstop_date%5D=2010-08-11&amp;query%5Bstop_time%5D=23%3A59%3A59&amp;query%5Btz%5D=%2B0300&amp;query%5Bgroup_by_date%5D=&amp;query%5Borig_id_clients_name%5D=&amp;query%5Borig_code_name%5D=&amp;query%5Borig_code%5D=&amp;query%5Borig_id_companies%5D=&amp;query%5Borig_id_groups%5D=&amp;query%5Bterm_id_clients_name%5D=&amp;query%5Bterm_code_name%5D=&amp;query%5Bterm_code%5D=&amp;query%5Bterm_id_companies%5D=&amp;query%5Bterm_id_groups%5D=&amp;query%5Boutput%5D=web&amp;query%5Bgroup_by%5D%5B0%5D=&amp;query%5Bgroup_by%5D%5B1%5D=&amp;query%5Bgroup_by%5D%5B2%5D=&amp;query%5Bgroup_by%5D%5B3%5D=&amp;query%5Bgroup_by%5D%5B4%5D=&amp;query%5Bgroup_by%5D%5B5%5D=&amp;query%5Bid_code_decks%5D="><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
        <td width="6%" class="cset-6 last" rel="17"><a href="?orderby=calls_nochannel&amp;query%5Bprocess%5D=1&amp;query%5Border_by%5D=&amp;query%5Borig_id_clients%5D=&amp;query%5Borig_account%5D=&amp;query%5Bterm_id_clients%5D=&amp;query%5Bterm_account%5D=&amp;query%5BsmartPeriod%5D=custom&amp;query%5Bstart_date%5D=2000-08-11&amp;query%5Bstart_time%5D=00%3A00%3A00&amp;query%5Bstop_date%5D=2010-08-11&amp;query%5Bstop_time%5D=23%3A59%3A59&amp;query%5Btz%5D=%2B0300&amp;query%5Bgroup_by_date%5D=&amp;query%5Borig_id_clients_name%5D=&amp;query%5Borig_code_name%5D=&amp;query%5Borig_code%5D=&amp;query%5Borig_id_companies%5D=&amp;query%5Borig_id_groups%5D=&amp;query%5Bterm_id_clients_name%5D=&amp;query%5Bterm_code_name%5D=&amp;query%5Bterm_code%5D=&amp;query%5Bterm_id_companies%5D=&amp;query%5Bterm_id_groups%5D=&amp;query%5Boutput%5D=web&amp;query%5Bgroup_by%5D%5B0%5D=&amp;query%5Bgroup_by%5D%5B1%5D=&amp;query%5Bgroup_by%5D%5B2%5D=&amp;query%5Bgroup_by%5D%5B3%5D=&amp;query%5Bgroup_by%5D%5B4%5D=&amp;query%5Bgroup_by%5D%5B5%5D=&amp;query%5Bid_code_decks%5D="><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<span rel="helptip" class="helptip" id="ht-100014"><?php echo __('nochannel')?></span><span class="tooltip" id="ht-100014-tooltip">Calls with Q.931 disconnect cause = 34</span>&nbsp;<a href="?orderby=calls_nochannel_desc&amp;query%5Bprocess%5D=1&amp;query%5Border_by%5D=&amp;query%5Borig_id_clients%5D=&amp;query%5Borig_account%5D=&amp;query%5Bterm_id_clients%5D=&amp;query%5Bterm_account%5D=&amp;query%5BsmartPeriod%5D=custom&amp;query%5Bstart_date%5D=2000-08-11&amp;query%5Bstart_time%5D=00%3A00%3A00&amp;query%5Bstop_date%5D=2010-08-11&amp;query%5Bstop_time%5D=23%3A59%3A59&amp;query%5Btz%5D=%2B0300&amp;query%5Bgroup_by_date%5D=&amp;query%5Borig_id_clients_name%5D=&amp;query%5Borig_code_name%5D=&amp;query%5Borig_code%5D=&amp;query%5Borig_id_companies%5D=&amp;query%5Borig_id_groups%5D=&amp;query%5Bterm_id_clients_name%5D=&amp;query%5Bterm_code_name%5D=&amp;query%5Bterm_code%5D=&amp;query%5Bterm_id_companies%5D=&amp;query%5Bterm_id_groups%5D=&amp;query%5Boutput%5D=web&amp;query%5Bgroup_by%5D%5B0%5D=&amp;query%5Bgroup_by%5D%5B1%5D=&amp;query%5Bgroup_by%5D%5B2%5D=&amp;query%5Bgroup_by%5D%5B3%5D=&amp;query%5Bgroup_by%5D%5B4%5D=&amp;query%5Bgroup_by%5D%5B5%5D=&amp;query%5Bid_code_decks%5D="><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
      </tr>
    </thead>
    <tbody class="rows" id="reporttab">
      <!--  <tr class="row-1">
            <td class="in-decimal">433 976.12</td>
    <td class="in-decimal pos"><b>2 118 327.6324</b></td>
    <td class="in-decimal right zero">4.8812</td>        <td class="in-decimal">421 659.45</td>
    <td class="in-decimal neg"><b>-1 673 319.6700</b></td>
    <td class="in-decimal right zero">3.9684</td>        <td class="in-decimal pos-b"><b>445 007.9624</b></td>
    <td class="in-decimal pos-b right">21.01</td>
        <td class="in-decimal right">434 240.37</td>
    <td class="in-decimal">68.58 %</td>
    <td class="in-decimal">29.95 %</td>
    <td class="in-decimal">0.96</td>
    <td class="in-decimal right">1.77</td>
    <td class="in-decimal">820 291</td>
    <td class="in-decimal">245 657</td>
    <td class="in-decimal">451 892</td>
    <td class="in-decimal">28 146</td>
    <td class="in-decimal last">161 402</td>
</tr>-->
      
    </tbody>
  </table>
  <div class="group-title bottom"> <img width="16" height="16" src="<?php echo $this->webroot?>images/charts.png"> <a onclick="$('#charts_holder').toggle();return false;" href="#"><?php echo __('viewcharts')?> »</a> </div>
  <?php //******************************统计图报表totalcost********************************?>
  <div style="display: none;" id="charts_holder">
    <?php //****价格报表1************?>
    <div id="chart_9be11_div" class="amChart">
      <div id="chart_9be11_div_inner" class="amChartInner"> 
        <script type="text/javascript" src="<?php echo $this->webroot?>amcolumn/swfobject.js"></script>
        <div id="flashcontent"> <strong></strong> </div>
        <script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("<?php  echo  $this->webroot?>amcolumn/amcolumn.swf", "amcolumn", "100%", "300", "8", "#000000");
		so.addVariable("path", "<?php echo  $this->webroot?>amcolumn/");
		so.addVariable("chart_settings", encodeURIComponent("<settings><width>100%</width><height>300</height><preloader_on_reload>1</preloader_on_reload><redraw>1</redraw><digits_after_decimal>2</digits_after_decimal><background><alpha>100</alpha><border_alpha>20</border_alpha></background><grid><category><dashed>1</dashed></category><value><dashed>1</dashed></value></grid><axes><category><width>1</width><color>E7E7E7</color></category><value><width>1</width><color>E7E7E7</color></value></axes><values><value><min>0</min></value></values><depth>25</depth><column><width>85</width><balloon_text>{title}: {value} RMB</balloon_text><grow_time>0</grow_time></column><balloon><text_size>15</text_size><corner_radius>6</corner_radius><max_width>400</max_width><text_color>000000</text_color></balloon><labels><label><text>Total Cost, RMB</text><x>20</x><y>20</y><text_size>20</text_size></label></labels><graphs><graph gid='0'><color>00CC66</color></graph><graph gid='1'><color>993333</color></graph><graph gid='2'><color>FFCC33</color></graph></graphs></settings>"));
		so.addVariable("chart_data",encodeURIComponent("<chart><series><value xid='0'>origterm</value></series><graphs><graph gid='0' title='Orig Cost'><value xid='0'>"+orig_cost+"</value></graph><graph gid='1' title='Term Cost'><value xid='0'>"+term_cost+"</value></graph></graphs></chart>"));
		so.addVariable("preloader_color", "#ffffff");
		so.write("flashcontent");
		// ]]>
	</script> 
        <!-- end of amcolumn script --> 
        
      </div>
    </div>
    <?php //****打入打出收费报表***********?>
    <div id="chart_d8ee4_div" class="amChart">
      <div id="chart_d8ee4_div_inner" class="amChartInner"> 
        <!-- saved from url=(0013)about:internet --> 
        <!-- amcolumn script-->
        
        <div id="flashcontent1"> <strong></strong> </div>
        <script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("<?php  echo  $this->webroot?>amcolumn/amcolumn.swf", "amcolumn", "100%", "300", "8", "#000000");
		so.addVariable("path", "<?php echo  $this->webroot?>amcolumn/");
		so.addVariable("chart_settings", encodeURIComponent("<settings><width>100%</width><height>300</height><preloader_on_reload>1</preloader_on_reload><redraw>1</redraw><digits_after_decimal>2</digits_after_decimal><background><alpha>100</alpha><border_alpha>20</border_alpha></background><grid><category><dashed>1</dashed></category><value><dashed>1</dashed></value></grid><axes><category><width>1</width><color>E7E7E7</color></category><value><width>1</width><color>E7E7E7</color></value></axes><values><value><min>0</min></value></values><depth>25</depth><column><width>85</width><balloon_text>{title}: {value} min</balloon_text><grow_time>0</grow_time></column><balloon><text_size>15</text_size><corner_radius>6</corner_radius><max_width>400</max_width><text_color>000000</text_color></balloon><labels><label><text>Time (Total / Billed) origination</text><x>20</x><y>20</y><text_size>20</text_size></label></labels><graphs><graph gid='0'><color>EB690C</color></graph><graph gid='1'><color>F1C900</color></graph><graph gid='2'><color>AECC18</color></graph></graphs></settings>"));
		so.addVariable("chart_data",encodeURIComponent("<chart><series><value xid='0'>origterm</value></series><graphs><graph gid='1' title='Orig Billed Time'><value xid='0'>"+orig_bill_time/60+"</value></graph><graph gid='2' title='Term Billed Time'><value xid='0'>"+term_bill_time/60+"</value></graph></graphs></chart>"));
		so.addVariable("preloader_color", "#ffffff");
		so.write("flashcontent1");
		// ]]>
	</script> 
        <!-- end of amcolumn script --> 
        
      </div>
    </div>
    <?php //****asr   报表3***********?>
    <div id="chart_a4ecd_div" class="amChart">
      <div id="chart_a4ecd_div_inner" class="amChartInner">
        <div id="flashcontent3"> <strong></strong> </div>
        <script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("<?php  echo  $this->webroot?>amcolumn/amcolumn.swf", "amcolumn", "100%", "300", "8", "#000000");
		so.addVariable("path", "<?php echo  $this->webroot?>amcolumn/");

		so.addVariable("chart_settings", encodeURIComponent("<settings><width>100%</width><height>300</height><preloader_on_reload>1</preloader_on_reload><redraw>1</redraw><digits_after_decimal>2</digits_after_decimal><background><alpha>100</alpha><border_alpha>20</border_alpha></background><grid><category><dashed>1</dashed></category><value><dashed>1</dashed></value></grid><axes><category><width>1</width><color>E7E7E7</color></category><value><width>1</width><color>E7E7E7</color></value></axes><values><value><min>0</min></value></values><depth>25</depth><column><width>85</width><balloon_text>{title}: {value} %</balloon_text><grow_time>0</grow_time></column><balloon><text_size>15</text_size><corner_radius>6</corner_radius><max_width>400</max_width><text_color>000000</text_color></balloon><labels><label><text>ASR, %</text><x>20</x><y>20</y><text_size>20</text_size></label></labels><graphs><graph gid='0'><color>EB690C</color></graph><graph gid='1'><color>F1C900</color></graph></graphs></settings>"));
		so.addVariable("chart_data",encodeURIComponent("<chart><series><value xid='0'>origterm</value></series><graphs><graph gid='0' title='ASR'><value xid='0'>"+success/total_ca+"</value></graph></graphs></chart>"));
		so.addVariable("preloader_color", "#ffffff");
		so.write("flashcontent3");
		// ]]>
	</script> 
      </div>
    </div>
    <?php //****acd报表4***********?>
    <div id="chart_8671f_div" class="amChart">
      <div id="chart_8671f_div_inner" class="amChartInner">
        <div id="flashcontent4"> <strong></strong> </div>
        <script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("<?php  echo  $this->webroot?>amcolumn/amcolumn.swf", "amcolumn", "100%", "300", "8", "#000000");
		so.addVariable("path", "<?php echo  $this->webroot?>amcolumn/");

		so.addVariable("chart_settings", encodeURIComponent("<settings><width>100%</width><height>300</height><preloader_on_reload>1</preloader_on_reload><redraw>1</redraw><digits_after_decimal>2</digits_after_decimal><background><alpha>100</alpha><border_alpha>20</border_alpha></background><grid><category><dashed>1</dashed></category><value><dashed>1</dashed></value></grid><axes><category><width>1</width><color>E7E7E7</color></category><value><width>1</width><color>E7E7E7</color></value></axes><values><value><min>0</min></value></values><depth>25</depth><column><width>85</width><balloon_text>{title}: {value} min</balloon_text><grow_time>0</grow_time></column><balloon><text_size>15</text_size><corner_radius>6</corner_radius><max_width>400</max_width><text_color>000000</text_color></balloon><labels><label><text>ACD, %</text><x>20</x><y>20</y><text_size>20</text_size></label></labels><graphs><graph gid='0'><color>EB690C</color></graph><graph gid='1'><color>F1C900</color></graph></graphs></settings>"));
		so.addVariable("chart_data",encodeURIComponent("<chart><series><value xid='0'>origterm</value></series><graphs><graph gid='0' title='ACD'><value xid='0'>"+acd+"</value></graph></graphs></chart>"));

		//so.addVariable("settings_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_settings_acd.xml"));
		//so.addVariable("data_file", encodeURIComponent("<?php  echo  $this->webroot?>amcolumn/amcolumn_data_acd.xml"));
		so.addVariable("preloader_color", "#ffffff");
		so.write("flashcontent4");
		// ]]>
	</script> 
      </div>
    </div>
    <?php //call的报表?>
    <div id="chart_1f323_div" class="amChart">
      <div id="chart_1f323_div_inner" class="amChartInner">
        <div id="flashcontent5"> <strong></strong> </div>
        <script type="text/javascript">
		// <![CDATA[		
		var so = new SWFObject("<?php  echo  $this->webroot?>amcolumn/amcolumn.swf", "amcolumn", "100%", "300", "8", "#000000");
		so.addVariable("path", "<?php echo  $this->webroot?>amcolumn/");
		so.addVariable("chart_settings", encodeURIComponent("<settings><width>100%</width><height>300</height><preloader_on_reload>1</preloader_on_reload><redraw>1</redraw><digits_after_decimal>2</digits_after_decimal><background><alpha>100</alpha><border_alpha>20</border_alpha></background><grid><category><dashed>1</dashed></category><value><dashed>1</dashed></value></grid><axes><category><width>1</width><color>E7E7E7</color></category><value><width>1</width><color>E7E7E7</color></value></axes><values><value><min>0</min></value></values><depth>25</depth><column><width>85</width><balloon_text>{title}: {value}</balloon_text><grow_time>0</grow_time></column><balloon><text_size>15</text_size><corner_radius>6</corner_radius><max_width>400</max_width><text_color>000000</text_color></balloon><labels><label><text>Calls Count</text><x>20</x><y>20</y><text_size>20</text_size></label></labels><graphs><graph gid='0'><color>EB690C</color></graph><graph gid='1'><color>F1C900</color></graph><graph gid='2'><color>AECC18</color></graph><graph gid='3'><color>0D8ECF</color></graph><graph gid='4'><color>2A0CD0</color></graph><graph gid='5'><color>CD0D74</color></graph></graphs></settings>"));
		so.addVariable("chart_data",encodeURIComponent("<chart><series><value xid='0'>origterm</value></series><graphs><graph gid='0' title='Calls Total'><value xid='0'>"+total_ca+"</value></graph><graph gid='1' title='Calls Not Zero'><value xid='0'>"+nonzero+"</value></graph><graph gid='2' title='Calls Success'><value xid='0'>"+success+"</value></graph><graph gid='3' title='Calls Busy'><value xid='0'>"+busy+"</value></graph><graph gid='4' title='Calls No Channel'><value xid='0'>"+nochannel+"</value></graph><graph gid='5' title='Calls Error'><value xid='0'>"+failed+"</value></graph></graphs></chart>"));
		so.addVariable("preloader_color", "#ffffff");
		so.write("flashcontent5");
		// ]]>
	</script> 
      </div>
    </div>
  </div>
  <?php //*******************************flash报表*****end********************************?>
  <script type="text/javascript">
var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name', 'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};
var _ss_ids_code = {'code': 'query-code', 'id_code_decks': 'query-id_code_decks'};
var _ss_ids_code_name = {'code_name': 'query-code_name', 'id_code_decks': 'query-id_code_decks'};
</script>
  <?php //***********************报表查询参数*********************?>
  <fieldset class="query-box">
    <legend><?php echo __('query')?></legend>
    <form onsubmit="loading();" method="post">
      <table style="width: 960px;" class="form">
        <col style="width: 80px;">
        <col style="width: 240px;">
        <col style="width: 80px;">
        <col style="width: 230px;">
        <col style="width: 80px;">
        <col style="width: 170px;">
        <tbody>
          <tr class="period-block">
            <td class="label"><select class="input in-select" name="query[client_type]" style="width: 90%;"  id="query-client_type">
                <option value="0"><?php echo __('client')?></option>
                <option value="2"><?php echo __('Reseller')?></option>
              </select></td>
            <td class="value" id="client_cell" style="float:left;"><input class="input in-text" name="query[id_clients_name]" value="" readonly="1" style="width: 83%;" onclick="showClients()" id="query-id_clients_name" type="text">
              <img width="9" height="9" onclick="ss_clear('client', _ss_ids_client)" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png"></td>
            <td class="label"><?php echo __('timeprofile')?>:</td>
            <td colspan="5" class="value"><table class="in-date">
                <tbody>
                  <tr>
                    <td style="padding-right: 15px;"><select id="query-smartPeriod" onchange="setPeriod(this.value)" name="query[smartPeriod]" class="input in-select">
                        <option value="curDay" selected="selected"><?php echo __('today')?></option>
                        <option value="prevDay"><?php echo __('yesterday')?></option>
                        <option value="curWeek"><?php echo __('currentweek')?></option>
                        <option value="prevWeek"><?php echo __('previousweek')?></option>
                        <option value="curMonth"><?php echo __('currentmonth')?></option>
                        <option value="prevMonth"><?php echo __('previousmonth')?></option>
                        <option value="curYear"><?php echo __('currentyear')?></option>
                        <option value="prevYear"><?php echo __('previousyear')?></option>
                        <option  value="custom"><?php echo __('custom')?></option>
                      </select></td>
                    <td><table class="in-date">
                        <tbody>
                          <tr>
                            <td><input type="text" id="query-start_date-wDt" class="in-date input in-text" onchange="setPeriod('custom')" onkeydown="setPeriod('custom')" value="<?php echo date('Y-m-d',time()+6*60*60)?>" name="query[start_date]"></td>
                          </tr>
                        </tbody>
                      </table></td>
                    <td><input type="text" id="query-start_time-wDt" onchange="setPeriod('custom')" onkeydown="setPeriod('custom')" style="width: 60px;" value="00:00:00" name="query[start_time]" class="input in-text"></td>
                    <td>&mdash;</td>
                    <td><table class="in-date">
                        <tbody>
                          <tr>
                            <td><input type="text" id="query-stop_date-wDt" class="in-date input in-text" onchange="setPeriod('custom')" onkeydown="setPeriod('custom')" value="<?php echo date('Y-m-d',time()+6*60*60)?>" name="query[stop_date]"></td>
                          </tr>
                        </tbody>
                      </table></td>
                    <td><input type="text" id="query-stop_time-wDt" onchange="setPeriod('custom')" onkeydown="setPeriod('custom')" style="width: 60px;" value="23:59:59" name="query[stop_time]" class="input in-text"></td>
                    <td class="buttons"><input type="submit" value="<?php echo __('query')?>" class="input in-submit"></td>
                  </tr>
                </tbody>
              </table></td>
          </tr>
        </tbody>
      </table>
    </form>
  </fieldset>
  <script type="text/javascript">
//<![CDATA[
tz = $('#query-tz').val();
function showClients ()
{
	ss_ids_custom['client'] = _ss_ids_client;
    val = $('#query-client_type').val();
    tz = $('#query-tz').val(); 
    var url = null;
    if (val == "1")
        url = "<?php echo $this->webroot?>cdrs/choose_cards";
    else if (val == "0") 
    			url = "<?php echo $this->webroot?>cdrs/choose_clients";
    else if (val == "2")
    			url = "<?php echo $this->webroot?>cdrs/choose_resellers";

    cover('cover_tmp');
		 loadPage(url,500,400);
}


document.getElementById("reporttab").appendChild(tr);
//]]>
</script> 
</div>