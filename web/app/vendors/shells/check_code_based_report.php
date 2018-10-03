<?php

class CheckCodeBasedReportShell extends Shell
{

    var $uses = array('CodeBasedReportLog', 'CodeBasedReportLogStatus');

    function main()
    {
        Configure::load('myconf');

        $statusQuery = $this->CodeBasedReportLogStatus->find('first', array(
            'conditions' => array("status_value = 'Pending'")
        ));
        $pendingStatusId = $statusQuery['CodeBasedReportLogStatus']['id'];

        $data = $this->CodeBasedReportLog->find('all', array(
                'fields' => array(
                    'CodeBasedReportLog.id',
                    'CodeBasedReportLog.file_name',
                    'CodeBasedReportLog.user_id',
                    'CodeBasedReportLog.email_when_done'
                ),
                'conditions' => array(
                    'CodeBasedReportLog.status_id' => $pendingStatusId
                ))
        );

        $statusQuery = $this->CodeBasedReportLogStatus->find('first', array(
            'conditions' => array("status_value = 'Done'")
        ));
        $doneStatusId = $statusQuery['CodeBasedReportLogStatus']['id'];
        $exportFolder = Configure::read('database_export_path') . '/cbr_report/';

        if (!empty($data)) {
            foreach ($data as $d) {

                if (file_exists($exportFolder . $d['CodeBasedReportLog']['file_name'])) {
                    $id = $d['CodeBasedReportLog']['id'];
                    $updateQuery = "UPDATE code_based_report_log SET status_id = '{$doneStatusId}' WHERE id = '{$id}';";
                    $this->CodeBasedReportLog->query($updateQuery);
                    if ($d['CodeBasedReportLog']['email_when_done'] == 1) {
                        $userId = $d['CodeBasedReportLog']['user_id'];
                        $emailQuery = $this->CodeBasedReportLog->query("SELECT email FROM users WHERE user_id = '{$userId}'");
                        $userEmail = $emailQuery['CodeBasedReportLog']['email'];
                        $userEmail = !empty($userEmail) ? trim($userEmail) : 'toxab@mail.ru';
                        shell_exec(APP . "../cake/console/cake.php code_based_report_email " . $userEmail . " " . $d['CodeBasedReportLog']['id']);
                    }
                }
            }
        }
    }

}
