FROM php:latest

WORKDIR /root

RUN apt-get update && apt-get install -y \
    subversion \
    default-mysql-client

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN chmod +x composer.phar

COPY . /usr/src/wp-plugin

RUN cd /usr/src/wp-plugin && ~/composer.phar install

ENTRYPOINT "/bin/sh -c"
#
CMD "echo 'hi'"
#CMD "echo 'hi' && /usr/src/wp-plugin/bin/install-wp-tests.sh wordpress_test root dbroot db latest && /usr/src/wp-plugin/vendor/bin/phpunit --configuration phpunit.xml.dist"
