#!/bin/bash

BBR_DIR="./bbr/"
FILE_PERMS=644
DIR_PERMS=754
NEW_OWNER="www"
NEW_GROUP="www"


echo -e "\n*----------- START SCRIPT! -----------*\n"

chown -R $NEW_OWNER:$NEW_GROUP $BBR_DIR
echo -e "New owner $NEW_OWNER:$NEW_GROUP\n"

find $BBR_DIR -type f -exec chmod $FILE_PERMS {} \;
echo -e "New files perms $FILE_PERMS in $BBR_DIR\n"

find $BBR_DIR -type d -exec chmod $DIR_PERMS {} \;
echo -e "New dirs perms $DIR_PERMS in $BBR_DIR\n"

echo "Restart nginx and php!"
service nginx restart
service php-fpm restart

echo -e "\nDirecotry $BBR_DIR listing:"
ls -lah $BBR_DIR

echo -e "\nDONE!\n"