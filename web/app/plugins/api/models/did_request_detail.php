<?php

class DidRequestDetail extends DidAppModel
{
    var $name = 'DidRequestDetail';
    var $useTable = 'did_request_detail';
    var $primaryKey = 'id';
    
    function get_email_file($request_id, $filename)
    {
        $sql = <<<EOT
copy(
SELECT 
"DidRequest"."id" AS "Request", 
"DidRequestDetail"."number" AS "DID", 
case "DidRequestDetail"."status" when 0 then 'Wating' when 1 then 'Completed' when 2 then 'Failed' END AS "Status", 
"DidRequestDetail"."assigned_time" AS "Date Assigned", 
"DidRespoitory"."country" AS "Country", 
"DidRespoitory"."rate_center" AS "Rate Center" ,
"DidRespoitory"."state"  AS "State", 
"DidRespoitory"."lata" AS "LATA", 
"Resource"."alias" AS "Trunk"
FROM "did_request_detail" 
AS "DidRequestDetail" LEFT JOIN did_request 
AS "DidRequest" ON ("DidRequestDetail"."did_request_id" = "DidRequest"."id") 
LEFT JOIN resource AS "Resource" ON ("DidRequestDetail"."egress_id" = "Resource"."resource_id") 
LEFT JOIN ingress_did_repository AS "DidRespoitory" ON ("DidRequestDetail"."number" = "DidRespoitory"."number") 
WHERE "DidRequestDetail"."did_request_id" = '{$request_id}' ORDER BY "DidRequestDetail"."number") to '/tmp/exports/{$filename}' csv delimiter ',' header
EOT;
        $this->query($sql);
    }
    
}