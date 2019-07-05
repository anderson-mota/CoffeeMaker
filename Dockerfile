FROM php:7.3.6-apache-stretch

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php && mv composer.phar /usr/bin/composer

RUN apt-get update && apt-get install -y git
