This is web user interface for the switch engine. It requires php 5.6 .
Typical installation is in /opt/denovo/web with this structure as is in github, except database folder.

This only work as user interface to the backend part of denovolab switching software!!!

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
install procedure commands:
yum -y install https://mirror.webtatic.com/yum/el7/webtatic-release.rpm
yum -y install httpd  mod_xsendfile lzma openssl-devel xz-devel wget libXtst php56w php65w-opcache php56w-pgsql php56w-gd


you can also install python from source, or you can use some repo install, this is how to install from source:

wget https://www.python.org/ftp/python/3.5.4/Python-3.5.4.tar.xz
tar xf Python-3.5.4.tar.xz
cd Python-3.5.4
./configure
make
make install
/usr/local/bin/pip3 install psycopg2
/usr/local/bin/pip3 install --upgrade pip
/usr/local/bin/pip3 install configparser
/usr/local/bin/pip3 install flask
/usr/local/bin/pip3 install Flask-WTF
/usr/local/bin/pip3 install requests
/usr/local/bin/pip3 install pillow
/usr/local/bin/pip3 install reportlab
/usr/local/bin/pip3 install python-dateutil
/usr/local/bin/pip3 install lockfile
/usr/local/bin/pip3 install phpserialize
/usr/local/bin/pip3 install xlrd pytz psutil
ln -s /usr/local/bin/python3 /usr/bin/python3




database instruction installation:
1.psql -U postgres db < class4_db_schema.sql -----  Structure of schma

2.psql -U postgres db < db_data.sql  -----  Basic data

3.psql -U postgres db < code.sql ----Code data

4.psql -U postgres db < jurisdiction_prefix.sql  ----- jurisdiction data

5.psql -U postgres db < voip.sql  ------ voip gateway

6.psql -U postgres db < update.sql  ----- Update record data


if you didnt already:
yum -y install https://download.postgresql.org/pub/repos/yum/9.6/redhat/rhel-7-x86_64/pgdg-centos96-9.6-3.noarch.rpm
yum -y install postgresql96-server prefix96 ip4r96 postgresql96-contrib httpd

after that, initialise postgresql database:
/usr/psql96/bin/postgresql96-setup initdb
systemctl start postgresql-9.6
systemctl enable postgresql-9.6
su to the postgres user, then  navigate to the database directory

psql -U postgres -c "create user class4_user superuser login password 'password'"

psql -U postgres -c "create database softswitch4v5"

psql -U postgres softswitch4v5 < class4_db_schema.sql ----- DB Structure

psql -U postgres softswitch4v5 < db_data.sql ----- Basic data

psql -U postgres softswitch4v5 < code.sql ----Code data

psql -U postgres softswitch4v5 <  jurisdiction_prefix.sql ----- jurisdiction data

psql -U postgres softswitch4v5 < update.sql ----- Update record data

copy file /etc/denovo.conf into /etc/httpd/conf.d/
run /opt/denovo/web/web/chmod.sh
start apache with systemctl start httpd
if all success, navigate to the web ui with http://yourIP
default user is admin, default pass 123456
if you intend to use in any other than just checking purpose, we strongly recommend to change password immediately

Any questions, and for futher instructions you can visit these links:

Bugs and Enhancement Request -  http://lira.denovolab.com 

Support  - http://help.denovolab.com 

Ask Questions - http://ask.denovolab.com 

Purchase License and LRN Dips - http://portal.denovolab.com

Email us at dnl-class4-user@googlegroups.com

Know more about DNL - http://www.denovolab.com 

We are always there if you need help
