
<td class="align_right padding-r10"><?php __('Time') ?></td>

<td class="center">
    <input type="text" id="query-start_date-wDt" class="in-text input" readonly="readonly" value="" name="start_date" style="width: 80px;" >
    &nbsp;
    <input type="text" readonly="readonly" style="width: 60px;cursor:pointer" value="00:00:00" name="start_time" class="input in-text"
        onclick="WdatePicker({dateFmt:'HH:mm:ss'})">
</td>
<td style="width:auto;">&mdash;</td>
<td class="center">
    <input type="text" id="query-stop_date-wDt" class="in-text input" readonly="readonly" value="" name="stop_date" style="width: 80px;">
    &nbsp;
    <input type="text" id="query-stop_time-wDt" readonly="readonly" style="width: 60px;" value="23:59:59" name="stop_time" class="input in-text">
</td>
<td>in&nbsp;&nbsp;&nbsp;&nbsp;
    <select id="query-tz" style="width: 103px;" name="query[tz]" class="input in-select">
        <option value="-1200">GMT -12:00</option>
        <option value="-1100">GMT -11:00</option>
        <option value="-1000">GMT -10:00</option>
        <option value="-0900">GMT -09:00</option>
        <option value="-0800">GMT -08:00</option>
        <option value="-0700">GMT -07:00</option>
        <option value="-0600">GMT -06:00</option>
        <option value="-0500">GMT -05:00</option>
        <option value="-0400">GMT -04:00</option>
        <option value="-0300">GMT -03:00</option>
        <option value="-0200">GMT -02:00</option>
        <option value="-0100">GMT -01:00</option>
        <option value="+0000" selected="selected">GMT +00:00</option>
        <option value="+0100">GMT +01:00</option>
        <option value="+0200">GMT +02:00</option>
        <option value="+0300">GMT +03:00</option>
        <option value="+0400">GMT +04:00</option>
        <option value="+0500">GMT +05:00</option>
        <option value="+0600">GMT +06:00</option>
        <option value="+0700">GMT +07:00</option>
        <option value="+0800">GMT +08:00</option>
        <option value="+0900">GMT +09:00</option>
        <option value="+1000">GMT +10:00</option>
        <option value="+1100">GMT +11:00</option>
        <option value="+1200">GMT +12:00</option>
    </select>
</td>
<td>
    <input type="submit" value="Search" class="btn btn-primary margin-bottom10">
</td>
