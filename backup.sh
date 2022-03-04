#!/bin/bash
SCRIPT_DIR=$(cd $(dirname $0); pwd)
cd $SCRIPT_DIR
youbi=`date +%u`
/usr/local/bin/docker-compose exec -T db /usr/local/bin/pg_dump -U postgres default > /tmp/backup.sql 2> /tmp/error.log
sudo mv /tmp/backup.sql /vagrant/Dropbox/game2_{$youbi}.sql
