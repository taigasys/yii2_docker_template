#!/usr/bin/env bash

# stop containers
docker-compose stop

# git pull new containers
git pull

# update composer
./composer_install.sh

# update containers
docker-compose build

# start containers
docker-compose up -d

# waiting until containers fully start
sleep 90s

# apply migrations
docker exec {PROJECT_NAME}_php bash -c "./yii migrate --interactive=0"
