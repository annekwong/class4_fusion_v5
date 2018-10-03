<?php

class RateGenerationRate extends AppModel {

    var $name = 'RateGenerationRate';
    var $useTable = "rate_generation_rate";
    var $primaryKey = "generation_rate_id";

    var $download_schema = array(
        'code',
        'rate',
        'setup_fee',
        'end_date',
        'min_time',
        'interval',
        'grace_time',
        'time_profile_id',
        'seconds',
        'code_name',
        'rate_type',
        'intra_rate',
        'inter_rate',
        'local_rate',
        'country',
        'zone',
        'ocn',
        'lata'
    );
}