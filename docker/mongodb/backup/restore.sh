#!/bin/sh
docker exec -it tcc-ii-api-mongodb mongorestore -u=root -p=@Tcc2023 restore-db/
