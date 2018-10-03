<?php
#echo `sh /opt/lampp/htdocs/exchange/vendors/shells/timeout.sh 45 60 sudo ngrep -q  'SIP' -O /opt/lampp/htdocs/exchange/app/tmp/capture_files/pcap_1294319520.pcap -t`;
echo pclose(popen("/usr/bin/ngrep -q SIP -O /opt/lampp/htdocs/exchange/app/tmp/capture_files/pcap_1294319520.pcap -t &",'r'));
?>
