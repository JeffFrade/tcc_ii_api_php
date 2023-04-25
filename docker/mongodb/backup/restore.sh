#!/bin/sh
docker exec -it tcc-ii-api-mongodb mongorestore -u=root -p=!Tcc@2023 restore-db/
