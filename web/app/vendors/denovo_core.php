<?php
define ( 'WEBROOT', '/exchange/' );

Configure::write('smtp_settings',array(
    'sendmailtype' => 1,
    'smtphost' => '192.168.1.125',
    'smtpport' => 25,
    'loginemail' => true,
    'emailusername' => 'ivtest@intlcx.com',
    'emailpassword' => 'INT123456',
    'fromemail' => 'ivtest@intlcx.com',
    'emailname' => 'ivtest'
	)
);

Configure::write('freeswitch_settings',array(
	    'pass' => 'ClueCon',
	   	//'host' => '192.168.1.107',
	   	'host' => '192.168.1.115',
				//'port' => '8021'//8031'
				'port' => '8031'
	)
);



#   DebugTools  Test Ingress Trunk
Configure::write('test_ingress_settings',array(
	   	//'host' => '192.168.1.107',
	   	'host' => '192.168.1.115',
				//'port' => '8021'
				'port' => '8031'
	)
);


