<?php 

class ImportShell extends Shell
{
    var $uses = array('ImportExportLog', 'ApiLog');
    
    public function main()
    {
        $id = $this->args[0];
        $sections = parse_ini_file(CONF_PATH, TRUE, INI_SCANNER_RAW);
        $url = $sections['import']['url'];
        $importLog = $this->ImportExportLog->find('first', array(
            'conditions' => array(
                'id' => $id
            )
        ));

        $ch = curl_init();
        $data = array(
            'file' => $importLog['ImportExportLog']['file_path'],
            'id'   => $id
        );
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json'
        );

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->ApiLog->addRequest($url, $data, $headers, 2, $httpCode);
    }
}
