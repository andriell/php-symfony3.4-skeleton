#!/bin/bash

if [ -L $0 ] ; then
    LOCATION_SCR=$(dirname $(readlink -f $0)) ;
else
    LOCATION_SCR=$(dirname $0) ;
fi ;

cd ${LOCATION_SCR}

php console app:db-backup
