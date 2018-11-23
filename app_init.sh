#!/usr/bin/env bash

docker run --rm --interactive --tty --volume $PWD:/app composer php app_init.php