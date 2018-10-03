<?php

class CdrApiExportShell extends Shell
{
    var $uses = array('CdrApiExportLog', 'Cdr');

    public function main()
    {
        session_start();

        $id = $this->args[0];
        $_SESSION['sst_user_name'] = $this->args[1];
        $_SESSION['sst_password'] = $this->args[2];
        $ingresses = $this->args[3];
        $egresses = $this->args[4];
        $group = $this->args[5];
        $fields = unserialize($this->args[6]);
        if(isset($fields['country_code'])){
            unset($fields['country_code']);
        }

        $record = $this->CdrApiExportLog->find('first', array(
            'conditions' => array(
                'id' => $id
            )
        ));

        $start = $record['CdrApiExportLog']['start_time'];
        $end = $record['CdrApiExportLog']['end_time'];
        $step = ($end - $start) / 60;

        App::import('Vendor', 'Report', array('file' => 'api/cdr/class.report.php'));

        $report = new Report();
        $data = array(
            'start_time' => $start,
            'end_time' => $end,
//            'format' => 'json',
            'group' => $group,
            'fields' => $fields
        );
        if ($ingresses && $ingresses != 'NULL') {
            $data['ingress_id'] = $ingresses;
        }

        if ($egresses && $egresses != 'NULL') {
            $data['egress_id'] = $egresses;
        }

        $result = $report->process($data, false, $id);
        $this->CdrApiExportLog->save(array(
            'id' => $id,
            'status' => 2
        ));

        if ($result['code'] == 200) {
            $result = $result['data'];
            $headerTemplate = array_merge([ 'ingres id' => 'Ingress Name','egress id' => 'Egress Name'], $this->Cdr->code_based_mapping());
            $stream = fopen("{$record['CdrApiExportLog']['ftp_directory']}/{$record['CdrApiExportLog']['filename']}.csv", 'w');
            $headers = array();

            foreach (array_keys($result[0]) as $item) {
                array_push($headers, $headerTemplate[$item]);
            }

            fputcsv($stream, $headers);

            foreach ($result as $item) {
                fputcsv($stream, $item);
            }

            fclose($stream);

            $this->CdrApiExportLog->save(array(
                'id' => $id,
                'status' => 1
            ));
        }
    }
}