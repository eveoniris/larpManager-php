#!/bin/bash

echo "Check if we need to init MySql"


SQLFILE=larpmanager-anonymized.sql
if [ -f "$SQLFILE" ]; then
	mysql -h larpmdbmysql larpm -u admin --password=password --binary-mode  < $SQLFILE      
else 
    echo "$SQLFILE does not exist. I can't initialize database"
fi

