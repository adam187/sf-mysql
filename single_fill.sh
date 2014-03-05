#!/bin/sh

php app/console doctrine:fixtures:load -n --purge-with-truncate
