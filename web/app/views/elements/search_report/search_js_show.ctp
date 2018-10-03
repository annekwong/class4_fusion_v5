
<script type="text/javascript">
//<![CDATA[
tz = $('#query-tz').val();
function showClients ()
{
    ss_ids_custom['client'] = _ss_ids_client;
    winOpen('<?php echo $this->webroot?>clients/ss_client?types=2&type=0', 500, 530);

}
function showResellers ()
{
    ss_ids_custom['reseller'] = _ss_ids_reseller;
    winOpen('<?php echo $this->webroot?>resellers/ss_reseller?types=2&type=0', 500, 530);

}
function showClients_term ()
{
    ss_ids_custom['client_term'] = _ss_ids_client_term;
    winOpen('<?php echo $this->webroot?>clients/ss_client_term?types=2&type=0', 500, 530);

}

function showss_codes ()
{
    ss_ids_custom['code'] = _ss_ids_code;
    winOpen('<?php echo $this->webroot?>codedecks/ss_code?types=2&type=0', 500, 530);

}
function repaintOutput() {
    if ($('#query-output').val() == 'web') {
        $('#output-sub').show();
    } else {
        $('#output-sub').hide();
    }
}
repaintOutput();

function get_ingress()
{
	var carrier = $("#CdrOrigCarrierSelect").val();
	if(carrier == ''){
		$.ajax({
			url:"<?php echo $this->webroot?>trunks/ajax_options",
			type:'post',
			data:{a:1},
			success:function(array){
				array = array.substring(0, array.length - 1);
				array = array.substring(0, array.length - 1);
				var a = JSON.parse(array);
				$("#CdrIngressAlias option").remove();
				$("#CdrIngressAlias").append('<option value></option>');
				$.each(a,function(idx,item){
					if(item['name'] != null){
						$("#CdrIngressAlias").append("<option value='"+item['id']+"'>"+item['name']+" </option>");
					}
				});
			}
		});
	}else{
		$.getJSON("<?php echo $this->webroot?>trunks/ajax_options?type=ingress&id="+carrier+ "&trunk_type2=0",{},function(d){
			$("#CdrIngressAlias option").remove();
			$("<option value=''></option>").appendTo("#CdrIngressAlias");
			$.each(d,function(idx,item){
				$("<option value='"+item['resource_id']+"'>"+item['alias']+" </option>").appendTo("#CdrIngressAlias");
			});
		});
	}
} 
function get_egress()
{
	var carrier = $("#CdrTermCarrierSelect").val();
	$.getJSON("<?php echo $this->webroot?>trunks/ajax_options?type=egress&id="+carrier,{},function(d){
		$("#CdrEgressAlias option").remove();
		$("<option value=''></option>").appendTo("#CdrEgressAlias");
		$.each(d,function(idx,item){
			$("<option value='"+item['resource_id']+"'>"+item['alias']+" </option>").appendTo("#CdrEgressAlias");
		});	
	});
}
//]]>
</script>
