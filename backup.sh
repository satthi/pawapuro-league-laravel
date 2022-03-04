#!/bin/bash
SCRIPT_DIR=$(cd $(dirname $0); pwd)
cd $SCRIPT_DIR
youbi=`date +%u`
/usr/local/bin/docker-compose exec -T db /usr/local/bin/pg_dump -U postgres default > /tmp/backup.sql 2> /tmp/error.log
bkfilesize=`wc -c /tmp/backup.sql | awk '{print $1}'`
if [ $bkfilesize != 0 ] ; then
sudo mv /tmp/backup.sql /vagrant/Dropbox/game2_{$youbi}.sql
else
echo 'error'
fi
