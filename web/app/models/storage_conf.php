<?php
class StorageConf extends AppModel{
    var $name = 'StorageConf';
    var $useTable = 'storage_config';
    var $primaryKey = 'id';

    const PARSER_TYPE = array(
        'cdr' => 'cdr',
        'pcap' => 'pcap'
    );
    const STORAGE_TYPE = array(
        'local' => 'local',
        'sftp' => 'sftp',
        'ftp' => 'ftp',
        'gcs' => 'gcs'
    );

}