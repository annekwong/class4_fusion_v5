<?php

class DeleteQosRouteReportShell extends Shell
{

    var $uses = array('Client');

    function main()
    {
        $date_25 = isset($this->args[0]) ? (int) $this->args[0] : "";
        if (!$date_25)
        {
            return false;
        }

        $date_25 = trim($date_25) + 0;
        $date_25 = date('Y-m-d H:00:00',$date_25);
        $sql = "delete from qos_route_report where report_time < '$date_25'";
        $this->Client->query($sql);
        $this->out($sql);
    }

}
