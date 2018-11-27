#!/bin/bash

docker run --rm --interactive --tty --volume $PWD/app:/app composer update --ignore-platform-reqs --no-scripts
