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

Also you need postgresql installed, we worked with 9.6  and additonal packages of postgresql-contrib, prefix and ip4r

in database folder is the sql files you need to install, default database name is softswitch4v5. if you change that, then you need to change it in all config files that are connecting to database
also this is all set if database is installed locally with rest of the programs. if database is on remote server, you would need to set ip in the config files.

database instruction installation:
1.psql -U postgres db < class4_db_schema.sql -----  Structure of schma

2.psql -U postgres db < db_data.sql  -----  Basic data

3.psql -U postgres db < code.sql ----Code data

4.psql -U postgres db < jurisdiction_prefix.sql  ----- jurisdiction data

5.psql -U postgres db < voip.sql  ------ voip gateway

6.psql -U postgres db < update.sql  ----- Update record data
