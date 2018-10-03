This is web user interface for the switch engine. It requires php 5.6 .
Typical installation is in /opt/denovo/web with this structure as is in github, except database folder.

requirements for  this:
you can use either ius or webtatic repo for this, since centos 7 is not coming with php 5.6
epel-release
libnetfilter_conntrack 
postgresql96 postgresql96-devel 
wget lzma openssl-devel 
xz-devel gcc 
libjpeg-turbo-devel ntp 
tcpdump wireshark expect 
bzip2-devel sqlite-devel zip expect telnet

for php you can use either ius or webtatic repo for this, since centos 7 is not coming with php 5.6
php56 
php56-common
php56-gd
php65-pdo
php56-pgsql

in the  etc folder is example file for using it with apache server.
