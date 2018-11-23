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
docker exec test_php bash -c "./yii migrate --interactive=0"
