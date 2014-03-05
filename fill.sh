#!/bin/sh

for i in {1..10000}
do
    php app/console doctrine:fixtures:load -n --append
done
