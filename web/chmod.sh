#!/usr/bin/env sh

if [ ! -d app/tmp/ ];then
    mkdir app/tmp
    mkdir app/tmp/cache
    mkdir app/tmp/upload
else
    mkdir app/tmp/cache
    mkdir app/tmp/cache/empty
    mkdir app/tmp/upload
    mkdir app/tmp/upload/csv
fi
chown denovo:apache ../etc/dnl_softswitch.ini
chmod 664 ../etc/dnl_softswitch.ini
echo '' > app/webroot/css/themer.css
if [ -f app/webroot/upload/images/logo.png ];then
    chown apache:apache app/webroot/upload/images/logo.png
fi
if [ -f app/webroot/upload/images/ilogo.png ]; then
    chown apache:apache app/webroot/upload/images/ilogo.png
fi
cat filemode | xargs chown apache:apache -R 
rm -rf app/tmp/cache/cake_*
chmod -R 777 ../download
chmod -R 777 app/webroot
chmod -R 777 app/tmp