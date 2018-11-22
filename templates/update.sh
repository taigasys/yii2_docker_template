#!/usr/bin/env bash

# stop containers
docker-compose stop

# git pull new containers
git pull

# update composer
./composer_install.sh

# start containers
docker-compose up -d

# apply migrations
docker exec {PROJECT_NAME}_php bash -c "./yii migrate --interactive=0"
