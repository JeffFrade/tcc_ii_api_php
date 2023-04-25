#!/bin/sh

TIMESTAMP=$(date +"%F")
DOCKER_BACKUP_DIR="/backup/$TIMESTAMP"

docker exec -it tcc-ii-api-mongodb mongodump -u root -p !Tcc@2023 -o $DOCKER_BACKUP_DIR

BACKUP_DIR="/home/ec2-user/backups/mongodb/$TIMESTAMP"

mkdir -p $BACKUP_DIR

sudo mv /home/ec2-user/tcc_ii_api_php/docker/mongodb/backup/$TIMESTAMP/* $BACKUP_DIR
