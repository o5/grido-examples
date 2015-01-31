#!/bin/sh

if [ ! -f apigen.phar ]; then
    wget http://apigen.org/apigen.phar
fi

php apigen.phar generate --title "Grido@master" -s "../vendor/o5/grido/src" -d "../api/master" --google-cse-id "000181613663465349732" --google-analytics "UA-39540018-1" --tree --deprecated --todo --download --template-theme "bootstrap"